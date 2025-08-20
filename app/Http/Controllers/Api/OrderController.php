<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\Order_detail;
use App\Models\Product;
use App\Models\Ship_address;
use App\Models\Voucher;
use App\Models\Voucher_usage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    public function store(Request $request)
    {
        try {
            // Kiểm tra xem người dùng đã đăng nhập hay chưa
            if (!Auth::check()) {
                return response()->json(['message' => 'User not logged in.'], 401);
            }

            $userId = Auth::id();

            // Lấy địa chỉ giao hàng mặc định hoặc mới nhất
            $shippingAddress = Ship_address::where('user_id', $userId)
                ->orderByDesc('is_default')
                ->orderByDesc('created_at')
                ->first();

            if (!$shippingAddress) {
                return response()->json([
                    'message' => 'No shipping address found. Please add a new address.',
                    'redirect_url' => route('address.create')
                ], 400);
            }

            // Lấy giỏ hàng của người dùng
            $cart = Cart::where('user_id', $userId)->first();
            if (!$cart) {
                return response()->json(['message' => 'No items in the cart.'], 400);
            }

            $cartItems = CartItem::where('cart_id', $cart->id)->get();
            if ($cartItems->isEmpty()) {
                return response()->json(['message' => 'No items in the cart.'], 400);
            }

            // Tính tổng số lượng và tổng giá trị đơn hàng
            $totalQuantity = $cartItems->sum('quantity');
            $totalAmount = $cartItems->sum(fn($item) => $item->quantity * $item->price);

            // Kiểm tra voucher và tính toán giảm giá (nếu có)
            $voucherId = $request->input('voucher_id');
            $discountValue = 0;

            if ($voucherId) {
                $voucher = Voucher::find($voucherId);

                if ($voucher && $voucher->is_active == 1 && $voucher->quantity > 0) {
                    $currentDate = now();

                    // Kiểm tra nếu người dùng đã sử dụng voucher này
                    $voucherUsageExists = DB::table('voucher_usages')
                        ->where('user_id', auth()->id())
                        ->where('voucher_id', $voucherId)
                        ->exists();

                    if ($voucherUsageExists) {
                        return response()->json(['message' => 'Bạn đã sử dụng voucher này rồi.'], 400);
                    }

                    // Kiểm tra ngày bắt đầu và ngày kết thúc của voucher
                    if ($currentDate < $voucher->start_day || $currentDate > $voucher->end_day) {
                        return response()->json(['message' => 'Phiếu giảm giá đã hết hạn hoặc chưa có hiệu lực.'], 400);
                    }

                    // Kiểm tra tổng tiền đơn hàng
                    if ($totalAmount <= $voucher->total_min) {
                        return response()->json(['message' => 'Tổng số tiền đặt hàng thấp hơn mức tối thiểu bắt buộc để được hưởng ưu đãi.'], 400);
                    }

                    if ($totalAmount >= $voucher->total_max) {
                        return response()->json(['message' => 'Tổng số tiền đặt hàng vượt quá mức tối đa được phép hưởng ưu đãi.'], 400);
                    }

                    // Tính giá trị giảm giá và cập nhật số lượng voucher
                    $discountValue = min($voucher->discount_value, $totalAmount);
                    $voucher->increment('used_times');
                    $voucher->decrement('quantity');
                } else {
                    return response()->json(['message' => 'Phiếu mua hàng không hợp lệ'], 400);
                }
            }

            $totalAmount -= $discountValue;

            // Tạo ID đơn hàng theo định dạng ngày tháng năm + số tăng dần
            $today = now()->format('dmY');
            $latestOrder = Order::where('id', 'LIKE', "$today%")->latest('id')->first();

            $newIdSuffix = $latestOrder
                ? (int)substr($latestOrder->id, -4) + 1
                : 1;

            $orderId = $today . str_pad($newIdSuffix, 4, '0', STR_PAD_LEFT);

            $order = Order::create([
                'id' => $orderId, // Gán ID thủ công
                'user_id' => $userId,
                'quantity' => $totalQuantity,
                'total_amount' => $totalAmount,
                'payment_method' => $request->input('payment_method', 1),
                'ship_method' => $request->input('ship_method', 1),
                'voucher_id' => $voucherId,
                'ship_address_id' => $shippingAddress->id,
                'discount_value' => $discountValue,
                'status' => 0, // Đang chờ xử lý
            ]);

            $orderDetails = [];
            foreach ($cartItems as $cartItem) {
                $orderDetail = Order_detail::create([
                    'order_id' => $orderId,
                    'product_id' => $cartItem->product_id,
                    'quantity' => $cartItem->quantity,
                    'price' => $cartItem->price,
                    'total' => $cartItem->quantity * $cartItem->price,
                    'size_id' => $cartItem->size_id,
                    'color_id' => $cartItem->color_id,
                ]);

                $product = Product::find($cartItem->product_id);
                if ($product) {
                    $product->quantity -= $cartItem->quantity;
                    $product->sell_quantity += $cartItem->quantity;
                    $product->save();
                }

                $orderDetails[] = $orderDetail;
            }

            // Ghi thông tin vào bảng voucher_usages nếu có voucher
            if ($voucherId && $voucher) {
                Voucher_usage::create([
                    'user_id' => $userId,
                    'order_id' => $order->id,
                    'voucher_id' => $voucherId,
                    'discount_value' => $discountValue,
                ]);
            }

            // Xóa các item trong giỏ hàng
            CartItem::where('cart_id', $cart->id)->delete();

            // Kiểm tra phương thức thanh toán: nếu là Online Payment (payment_method = 2)
            if ($request->input('payment_method') == 2) {
                $vnpayUrl = $this->createPaymentUrl($request, $totalAmount, $order->id); // Chuyển tham số đúng vào hàm
                Log::info($vnpayUrl);
                return response()->json([
                    'status' => true,
                    'message' => 'Order created successfully, please complete your payment.',
                    'payment_url' => $vnpayUrl,
                ], 201);
            }

            // Nếu là COD, trả về kết quả đơn hàng đã được tạo
            return response()->json([
                'status' => true,
                'message' => 'Khởi tạo giao dịch thành công, vui lòng thanh toán để hoàn thành đơn hàng ',
                'order_id' => $orderId,
                'total_amount' => $totalAmount,
                'order_details' => $orderDetails,
            ], 201);
        } catch (\Exception $e) {
            return response()->json(['message' => 'An error occurred: ' . $e->getMessage()], 500);
        }
    }
    
    public function createPaymentUrl(Request $request, $totalAmount, $orderId)
    {
        $vnp_TmnCode = env('VNP_TMN_CODE'); // Mã website của bạn trên VNPay
        $vnp_HashSecret = env('VNP_HASH_SECRET'); // Chuỗi bí mật để mã hóa
        $vnp_Url = env('VNP_URL'); // URL thanh toán của VNPay
        $vnp_ReturnUrl = env('VNP_RETURN_URL'); // URL trả về sau khi thanh toán

        // Ghi log thông tin cấu hình
        Log::info('VNPay Config:', [
            'VNP_TMN_CODE' => $vnp_TmnCode,
            'VNP_HASH_SECRET' => $vnp_HashSecret,
            'VNP_URL' => $vnp_Url,
            'VNP_RETURN_URL' => $vnp_ReturnUrl
        ]);

        // Kiểm tra số tiền hợp lệ
        if ($totalAmount < 5000 || $totalAmount >= 1000000000) {
            Log::error('Invalid transaction amount:', ['amount' => $totalAmount]);
            return response()->json(['error' => 'Số tiền không hợp lệ, phải từ 5,000 VNĐ đến dưới 1 tỷ VNĐ.'], 400);
        }

        // Dữ liệu giao dịch
        $vnp_TxnRef = $orderId; // Mã giao dịch là order_id
        $vnp_OrderInfo = 'Thanh toán đơn hàng #' . $orderId; // Nội dung thanh toán
        $vnp_OrderType = 'billpayment'; // Loại giao dịch
        $vnp_Amount = (int) ($totalAmount * 100); // Số tiền (VNĐ nhân 100)
        $vnp_Locale = 'vn'; // Ngôn ngữ
        $vnp_IpAddr = $request->ip(); // IP của người dùng

        // Ghi log dữ liệu đầu vào
        Log::info('VNPay Request Input:', $request->all());
        Log::info('Transaction Data:', [
            'TxnRef' => $vnp_TxnRef,
            'OrderInfo' => $vnp_OrderInfo,
            'OrderType' => $vnp_OrderType,
            'Amount' => $vnp_Amount,
            'Locale' => $vnp_Locale,
            'IpAddr' => $vnp_IpAddr
        ]);

        // Tạo dữ liệu đầu vào cho VNPay
        $inputData = [
            "vnp_Version" => "2.1.0",
            "vnp_TmnCode" => $vnp_TmnCode,
            "vnp_Amount" => $vnp_Amount,
            "vnp_Command" => "pay",
            "vnp_CreateDate" => date('YmdHis'),
            "vnp_CurrCode" => "VND",
            "vnp_IpAddr" => $vnp_IpAddr,
            "vnp_Locale" => $vnp_Locale,
            "vnp_OrderInfo" => $vnp_OrderInfo,
            "vnp_OrderType" => $vnp_OrderType,
            "vnp_ReturnUrl" => $vnp_ReturnUrl,
            "vnp_TxnRef" => $vnp_TxnRef,
        ];

        // Sắp xếp dữ liệu đầu vào theo thứ tự
        ksort($inputData);

        // Tạo chuỗi dữ liệu để mã hóa
        $query = "";
        $hashdata = "";
        foreach ($inputData as $key => $value) {
            $hashdata .= ($hashdata ? '&' : '') . urlencode($key) . "=" . urlencode($value);
            $query .= urlencode($key) . "=" . urlencode($value) . '&';
        }

        // Ghi log dữ liệu sau khi xử lý
        Log::info('VNPay Input Data Sorted:', $inputData);
        Log::info('Hash Data String:', ['hashdata' => $hashdata]);

        // Tính toán mã hash sử dụng chuỗi dữ liệu và secret key
        $vnpSecureHash = hash_hmac('sha512', $hashdata, $vnp_HashSecret);
        Log::info('Generated Secure Hash:', ['secure_hash' => $vnpSecureHash]);

        // Tạo URL thanh toán
        $vnp_Url = $vnp_Url . "?" . $query . 'vnp_SecureHash=' . $vnpSecureHash;
        Log::info('Generated VNPay URL:', ['url' => $vnp_Url]);

        return response()->json(['payment_url' => $vnp_Url]);
    }
}
