<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\Order_detail;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Ship_address;
use App\Models\Voucher;
use App\Models\Voucher_usage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    public function store(Request $request)
    {
        Log::info('OrderController@store: Người dùng chưa đăng nhập.');
        try {
            // Kiểm tra đăng nhập
            if (!Auth::check()) {
                Log::warning('OrderController@store: Người dùng chưa đăng nhập.');
                return response()->json(['message' => 'User not logged in.'], 401);
            }

            $userId = Auth::id();

            // Lấy địa chỉ giao hàng mặc định hoặc mới nhất
            $shippingAddress = Ship_address::where('user_id', $userId)
                ->orderByDesc('is_default')
                ->orderByDesc('created_at')
                ->first();

            if (!$shippingAddress) {
                Log::error('OrderController@store: Không tìm thấy địa chỉ giao hàng.', ['user_id' => $userId]);
                return response()->json([
                    'message' => 'No shipping address found. Please add a new address.',
                    'redirect_url' => route('address.create')
                ], 400);
            }

            // Lấy giỏ hàng của người dùng
            $cart = Cart::where('user_id', $userId)->first();
            if (!$cart) {
                Log::error('OrderController@store: Không tìm thấy giỏ hàng.', ['user_id' => $userId]);
                return response()->json(['message' => 'No items in the cart.'], 400);
            }

            $cartItems = CartItem::with(['productVariant.product', 'productVariant.color', 'productVariant.size'])->where('cart_id', $cart->id)->get();
            if ($cartItems->isEmpty()) {
                Log::error('OrderController@store: Giỏ hàng trống.', ['cart_id' => $cart->id]);
                return response()->json(['message' => 'No items in the cart.'], 400);
            }
            Log::info('OrderController@store: Số lượng mặt hàng trong giỏ', ['cart_items_count' => $cartItems->count()]);

            // Tính tổng số lượng và tổng giá trị đơn hàng
            $totalQuantity = $cartItems->sum('quantity');
            $subtotal = $cartItems->sum(fn($item) => $item->quantity * $item->price);
            $shippingFee = 40000; // Phí vận chuyển cố định
            $voucherId = $request->input('voucher_id');
            $discountValue = 0;
            $voucher = null;
            if ($voucherId) {
                $voucher = Voucher::find($voucherId);
                if ($voucher && $voucher->is_active == 1 && $voucher->quantity > 0) {
                    $currentDate = now();
                    $voucherUsageExists = DB::table('voucher_usages')
                        ->where('user_id', $userId)
                        ->where('voucher_id', $voucherId)
                        ->exists();
                    if ($voucherUsageExists) return response()->json(['message' => 'Bạn đã sử dụng voucher này rồi.'], 400);
                    if ($currentDate < $voucher->start_day || $currentDate > $voucher->end_day) return response()->json(['message' => 'Phiếu giảm giá đã hết hạn hoặc chưa có hiệu lực.'], 400);
                    if ($subtotal + $shippingFee <= $voucher->total_min) return response()->json(['message' => 'Tổng số tiền đặt hàng thấp hơn mức tối thiểu bắt buộc để được hưởng ưu đãi.'], 400);
                    if ($subtotal + $shippingFee >= $voucher->total_max) return response()->json(['message' => 'Tổng số tiền đặt hàng vượt quá mức tối đa được phép hưởng ưu đãi.'], 400);
                    $discountValue = min($voucher->discount_value, $subtotal + $shippingFee);
                } else {
                    return response()->json(['message' => 'Phiếu mua hàng không hợp lệ'], 400);
                }
            }
            $totalAmount = $subtotal + $shippingFee - $discountValue;
            Log::info('OrderController@store: Tổng tiền sau giảm giá (đã bao gồm phí ship)', ['final_total_amount' => $totalAmount]);
            $maxRetry = 20;
            $order = null;
            $orderId = null;
            $today = now()->format('dmY');
            for ($try = 0; $try < $maxRetry; $try++) {
                DB::beginTransaction();
                try {
                    $randomSuffix = str_pad(random_int(0, 9999999), 6, '0', STR_PAD_LEFT);
                    $orderId = $today . $randomSuffix;
                    if (Order::where('id', $orderId)->exists()) {
                         // Trùng ID, thử lại
                        DB::rollBack();
                        usleep(100000); // Chờ 0.1 giây trước khi thử lại
                        continue;
                    }

                    $order = Order::create([
                        'id' => $orderId,
                        'user_id' => $userId,
                        'quantity' => $totalQuantity,
                        'total_amount' => $totalAmount,
                        'shipping_fee' => $shippingFee, // Lưu phí vận chuyển
                        'payment_method' => $request->input('payment_method', 1),
                        'ship_method' => $request->input('ship_method', 1),
                        'voucher_id' => $voucherId,
                        'ship_address_id' => $shippingAddress->id,
                        'discount_value' => $discountValue,
                        'status' => 0, // Đang chờ xử lý
                    ]);
                    DB::commit();
                    break;
                } catch (\Illuminate\Database\QueryException $e) {
                    DB::rollBack();
                    if ($e->getCode() == '23000') {
                        usleep(100000); // Chờ 0.1 giây trước khi thử lại
                        continue;
                    }
                    throw $e;
                }
            }
            if (!$order) {
                return response()->json(['message' => 'Không thể tạo đơn hàng, vui lòng thử lại sau.'], 500);
            }

            // Xử lý chi tiết đơn hàng
            $orderDetails = [];
            foreach ($cartItems as $cartItem) {
                $productVariant = $cartItem->productVariant;
                $product = $productVariant->product;
                $orderDetail = Order_detail::create([
                    'order_id' => $orderId,
                    'product_id' => $cartItem->product_id,
                    'product_variant_id' => $productVariant->id,
                    'product_name' => $product->name,
                    'quantity' => $cartItem->quantity,
                    'price' => $cartItem->price,
                    'price_sale' => $cartItem->price,
                    'total' => $cartItem->quantity * $cartItem->price,
                    'size_id' => $productVariant->size_id,
                    'size_name' => $productVariant->size->size ?? null,
                    'color_id' => $productVariant->color_id,
                ]);
                Log::info('OrderController@store: Chi tiết đơn hàng đã tạo', ['order_detail_id' => $orderDetail->id, 'product_name' => $product->name, 'quantity' => $cartItem->quantity, 'price' => $cartItem->price]);
                if ($productVariant) {
                    $productVariant->quantity -= $cartItem->quantity;
                    $productVariant->save();
                    Log::info('OrderController@store: Số lượng biến thể sản phẩm được cập nhật.', ['product_variant_id' => $productVariant->id, 'new_quantity' => $productVariant->quantity]);
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
                Log::info('OrderController@store: Voucher usage được ghi lại.', ['user_id' => $userId, 'voucher_id' => $voucherId]);
            }

            // Kiểm tra phương thức thanh toán: nếu là Online Payment (payment_method = 2)
            if ($request->input('payment_method') == 2) {
                Log::info('OrderController@store: Phương thức thanh toán là Online Payment.');
                $paymentResponse = $this->createPaymentUrl($request, $totalAmount, $order->id);
                $paymentData = $paymentResponse->getData(true);
                Log::info('OrderController@store: Phản hồi từ createPaymentUrl.', ['payment_response' => $paymentData]);
                if (isset($paymentData['payment_url'])) {
                    Log::info('OrderController@store: Trả về URL thanh toán.', ['payment_url' => $paymentData['payment_url']]);
                    return response()->json([
                        'status' => true,
                        'message' => 'Order created successfully, please complete your payment.',
                        'payment_url' => $paymentData['payment_url'],
                    ], 201);
                } else {
                    Log::error('OrderController@store: Không tạo được URL thanh toán.', ['payment_data' => $paymentData]);
                    return response()->json(['message' => 'Failed to create payment URL.'], 500);
                }
            }        

            // Nếu là COD, xóa giỏ hàng và trả về kết quả đơn hàng đã được tạo
            Log::info('OrderController@store: Phương thức thanh toán là COD. Xóa giỏ hàng và trả về kết quả đơn hàng.');
            CartItem::where('cart_id', $cart->id)->delete();
            Log::info('OrderController@store: Các mặt hàng trong giỏ hàng đã bị xóa (COD).', ['cart_id' => $cart->id]);
            return response()->json([
                'status' => true,
                'message' => 'Đơn hàng đã được tạo thành công',
                'order_id' => $orderId,
                'total_amount' => $totalAmount,
                'shipping_fee' => $shippingFee,
                'order_details' => $orderDetails,
            ], 201);
        } catch (\Exception $e) {
            Log::error('OrderController@store: Đã xảy ra lỗi trong quá trình xử lý đơn hàng.', ['exception' => $e->getMessage()]);
            return response()->json(['message' => 'An error occurred: ' . $e->getMessage()], 500);
        }
    }

    // Hàm để tạo URL thanh toán VNPay
    public function createPaymentUrl(Request $request, $totalAmount, $orderId)
    {
        // Kiểm tra cấu hình VNPay
        $vnp_TmnCode = env('VNP_TMN_CODE'); // Mã website của bạn trên VNPay
        $vnp_HashSecret = env('VNP_HASH_SECRET'); // Chuỗi bí mật để mã hóa
        $vnp_Url = env('VNP_URL'); // URL thanh toán của VNPay
        $vnp_ReturnUrl = env('VNP_RETURN_URL'); // URL trả về sau khi thanh toán

        // Validate cấu hình
        if (!$vnp_TmnCode || !$vnp_HashSecret || !$vnp_Url || !$vnp_ReturnUrl) {
            Log::error('VNPay configuration missing', [
                'VNP_TMN_CODE' => $vnp_TmnCode ? 'SET' : 'MISSING',
                'VNP_HASH_SECRET' => $vnp_HashSecret ? 'SET' : 'MISSING',
                'VNP_URL' => $vnp_Url ? 'SET' : 'MISSING',
                'VNP_RETURN_URL' => $vnp_ReturnUrl ? 'SET' : 'MISSING'
            ]);
            return response()->json(['error' => 'Cấu hình thanh toán chưa hoàn tất'], 500);
        }

        // Ghi log thông tin cấu hình
        Log::info('VNPay Config:', [
            'VNP_TMN_CODE' => $vnp_TmnCode,
            'VNP_HASH_SECRET' => $vnp_HashSecret ? 'SET' : 'MISSING',
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
        try {
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

        } catch (\Exception $e) {
            Log::error('Error creating VNPay URL', [
                'error' => $e->getMessage(),
                'order_id' => $orderId,
                'amount' => $totalAmount
            ]);

            return response()->json(['error' => 'Lỗi khi tạo URL thanh toán: ' . $e->getMessage()], 500);
        }
    }
    
    public function createFromSelection(Request $request)
    {
        Log::info('OrderController@createFromSelection: Bắt đầu xử lý đơn hàng từ các mục đã chọn.', ['request_data' => $request->all()]);
        try {
            // 1. Validate request
            $validated = $request->validate([
                'cart_item_ids' => 'required|array|min:1',
                'cart_item_ids.*' => 'integer|exists:cart_items,id',
                'payment_method' => 'required|integer',
                'voucher_id' => 'nullable|integer|exists:vouchers,id',
            ]);

            $cartItemIds = $validated['cart_item_ids'];

            // 2. Check user authentication
            if (!Auth::check()) {
            Log::warning('OrderController@createFromSelection: Người dùng chưa đăng nhập.');
                return response()->json(['message' => 'User not logged in.'], 401);
            }
            $userId = Auth::id();

            // 3. Get shipping address
            $shippingAddress = Ship_address::where('user_id', $userId)
                ->orderByDesc('is_default')
                ->orderByDesc('created_at')
                ->first();
            if (!$shippingAddress) {
                Log::error('OrderController@createFromSelection: Không tìm thấy địa chỉ giao hàng.', ['user_id' => $userId]);
                return response()->json(['message' => 'No shipping address found. Please add a new address.'], 400);
            }

            // 4. Get selected cart items and verify ownership
            $cartItems = CartItem::with(['productVariant.product', 'productVariant.color', 'productVariant.size'])
                ->whereIn('id', $cartItemIds)
                ->whereHas('cart', function ($query) use ($userId) {
                    $query->where('user_id', $userId);
                })
                ->get();

            if ($cartItems->count() !== count($cartItemIds)) {
                Log::error('OrderController@createFromSelection: Một số mục trong giỏ hàng không hợp lệ hoặc không thuộc về người dùng.', ['user_id' => $userId, 'requested_ids' => $cartItemIds]);
                return response()->json(['message' => 'Invalid cart items provided.'], 400);
            }

            if ($cartItems->isEmpty()) {
                Log::error('OrderController@createFromSelection: Không có mục nào được chọn trong giỏ hàng.', ['user_id' => $userId]);
                return response()->json(['message' => 'No items selected from the cart.'], 400);
            }

            // 5. Calculate total amount and quantity
            $totalQuantity = $cartItems->sum('quantity');
            $subtotal = $cartItems->sum(fn($item) => $item->quantity * $item->price);
            $shippingFee = 40000;
            $voucherId = $validated['voucher_id'] ?? null;
            $discountValue = 0;
            $voucher = null;
            if ($voucherId) {
                $voucher = Voucher::find($voucherId);
                if ($voucher && $voucher->is_active == 1 && $voucher->quantity > 0) {
                    $currentDate = now();
                    $voucherUsageExists = DB::table('voucher_usages')
                        ->where('user_id', $userId)
                        ->where('voucher_id', $voucherId)
                        ->exists();
                    if ($voucherUsageExists) return response()->json(['message' => 'Bạn đã sử dụng voucher này rồi.'], 400);
                    if ($currentDate < $voucher->start_day || $currentDate > $voucher->end_day) return response()->json(['message' => 'Phiếu giảm giá đã hết hạn hoặc chưa có hiệu lực.'], 400);
                    if ($subtotal + $shippingFee <= $voucher->total_min) return response()->json(['message' => 'Tổng số tiền đặt hàng thấp hơn mức tối thiểu bắt buộc để được hưởng ưu đãi.'], 400);
                    if ($subtotal + $shippingFee >= $voucher->total_max) return response()->json(['message' => 'Tổng số tiền đặt hàng vượt quá mức tối đa được phép hưởng ưu đãi.'], 400);
                    $discountValue = min($voucher->discount_value, $subtotal + $shippingFee);
                } else {
                    return response()->json(['message' => 'Phiếu mua hàng không hợp lệ'], 400);
                }
            }
            $totalAmount = $subtotal + $shippingFee - $discountValue;
            Log::info('OrderController@createFromSelection: Tổng số lượng và tổng tiền', ['total_quantity' => $totalQuantity, 'total_amount' => $totalAmount]);

            // Create Order ID (random 6 digits for uniqueness)
            $today = now()->format('dmY');
            $maxRetry = 20;
            $orderId = null;
            for ($try = 0; $try < $maxRetry; $try++) {
                $randomSuffix = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
                $orderId = $today . $randomSuffix;
                if (!Order::where('id', $orderId)->exists()) {
                    break; // Unique ID found
                }
                usleep(100000); // Wait 0.1 second before retrying
            }
            if (!$orderId) {
                return response()->json(['message' => 'Không thể tạo mã đơn hàng, vui lòng thử lại.'], 500);
            }

            $order = Order::create([
                'id' => $orderId,
                'user_id' => $userId,
                'quantity' => $totalQuantity,
                'total_amount' => $totalAmount,
                'payment_method' => $validated['payment_method'],
                'ship_method' => $request->input('ship_method', 1),
                'voucher_id' => $voucherId,
                'ship_address_id' => $shippingAddress->id,
                'discount_value' => $discountValue,
                'status' => 0, // Pending
            ]);

            // Tạo chi tiết đơn hàng, giữ nguyên giá gốc, không phân bổ giảm giá vào từng sản phẩm
            foreach ($cartItems as $cartItem) {
                $productVariant = $cartItem->productVariant;
                $product = $productVariant->product;
                Order_detail::create([
                    'order_id' => $orderId,
                    'product_id' => $product->id,
                    'product_variant_id' => $productVariant->id,
                    'product_name' => $product->name,
                    'quantity' => $cartItem->quantity,
                    'price' => $cartItem->price,
                    'price_sale' => $cartItem->price,
                    'total' => $cartItem->quantity * $cartItem->price,
                    'size_id' => $productVariant->size_id,
                    'size_name' => $productVariant->size->size ?? null,
                    'color_id' => $productVariant->color_id,
                ]);
                if ($productVariant) {
                    $productVariant->quantity -= $cartItem->quantity;
                    $productVariant->save();
                }
            }

            // Ghi thông tin vào bảng voucher_usages nếu có voucher và đơn hàng thành công
            if ($voucherId && $voucher) {
                Voucher_usage::create([
                    'user_id' => $userId,
                    'order_id' => $order->id,
                    'voucher_id' => $voucherId,
                    'discount_value' => $discountValue,
                ]);
                Log::info('OrderController@createFromSelection: Voucher usage được ghi lại.', ['user_id' => $userId, 'voucher_id' => $voucherId]);
            }

            // Handle payment method
            if ($validated['payment_method'] == 2) { // Online Payment
                $paymentResponse = $this->createPaymentUrl($request, $totalAmount, $order->id);
                $paymentData = $paymentResponse->getData(true);
                if (isset($paymentData['payment_url'])) {
                    // Don't delete cart items until payment is confirmed
                    return response()->json([
                        'status' => true,
                        'message' => 'Order created, please complete payment.',
                        'payment_url' => $paymentData['payment_url'],
                        'order_id' => $orderId,
                    ], 201);
                } else {
                    return response()->json(['message' => 'Failed to create payment URL.'], 500);
                }
            }

            // If COD, remove selected cart items
            CartItem::whereIn('id', $cartItemIds)->delete();
            Log::info('OrderController@createFromSelection: Các mục đã chọn trong giỏ hàng đã bị xóa (COD).', ['cart_item_ids' => $cartItemIds]);

            return response()->json([
                'status' => true,
                'message' => 'Đơn hàng đã được tạo thành công.',
                'order_id' => $orderId,
                'total_amount' => $totalAmount,
                'shipping_fee' => $shippingFee,
                'discount_value' => $discountValue,
            ], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('OrderController@createFromSelection: Lỗi xác thực.', ['errors' => $e->errors()]);
            return response()->json(['message' => 'Validation failed', 'errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            Log::error('OrderController@createFromSelection: Đã xảy ra lỗi.', ['exception' => $e->getMessage()]);
            return response()->json(['message' => 'An error occurred: ' . $e->getMessage()], 500);
        }
    }
                
}
