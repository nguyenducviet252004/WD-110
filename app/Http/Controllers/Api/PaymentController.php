<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Voucher_usage;
use Illuminate\Http\Request;
use App\Models\Payments;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    public function handlePaymentResult(Request $request)
    {
        try {
            Log::info('Payment result received', ['data' => $request->all()]);

            // Lấy các tham số trả về từ VNPay
            $vnpAmount = $request->input('vnp_Amount');
            $vnpTransactionNo = $request->input('vnp_TransactionNo');
            $vnpResponseCode = $request->input('vnp_ResponseCode');
            $vnpTxnRef = $request->input('vnp_TxnRef');
            $vnpSecureHash = $request->input('vnp_SecureHash');

            // Lấy secret key từ file .env
            $vnpHashSecret = env('VNP_HASH_SECRET');

            // Kiểm tra chữ ký bảo mật
            $secureHashCheck = $this->generateVNPaySecureHash($request, $vnpHashSecret);

            Log::info('Secure hash comparison', [
                'vnp_SecureHash' => $vnpSecureHash,
                'generated_hash' => $secureHashCheck,
            ]);

            // Kiểm tra sự khớp của mã hash
            if ($vnpSecureHash !== $secureHashCheck) {
                Log::warning('Invalid secure hash', ['vnp_TxnRef' => $vnpTxnRef]);
                return response()->json(['message' => 'Invalid secure hash.'], 400);
            }

            // Kiểm tra mã kết quả thanh toán
            $order = Order::find($vnpTxnRef);

            if (!$order) {
                Log::warning('Order not found', ['vnp_TxnRef' => $vnpTxnRef]);
                return redirect('http://localhost:3000/order-error');
            }

            // Nếu mã thanh toán thành công
            if ($vnpResponseCode === '00') {
                // Kiểm tra xem đơn hàng đã được thanh toán chưa
                $existingPayment = Payment::where('order_id', $order->id)->where('status', 'success')->first();
                if ($existingPayment) {
                    Log::warning('Payment already processed', ['order_id' => $order->id]);
                    return redirect('http://localhost:3000/thank');
                }

                Payment::create([
                    'order_id' => $order->id,
                    'transaction_id' => $vnpTransactionNo,
                    'payment_method' => 'online',
                    'amount' => $vnpAmount / 100,
                    'status' => 'success',
                    'response_code' => $vnpResponseCode,
                    'secure_hash' => $vnpSecureHash,
                ]);

                // Cập nhật trạng thái đơn hàng
                $order->status = 1; // Đã thanh toán - đang xử lý
                $order->message = 'Đã thanh toán thành công'; 
                $order->save();

                // XÓA GIỎ HÀNG KHI THANH TOÁN ONLINE THÀNH CÔNG
                $cart = \App\Models\Cart::where('user_id', $order->user_id)->first();
                if ($cart) {
                    \App\Models\CartItem::where('cart_id', $cart->id)->delete();
                    Log::info('Payment successful: Cart items deleted', ['order_id' => $order->id, 'cart_id' => $cart->id]);
                }

                Log::info('Payment successful', ['order_id' => $order->id]);
                return redirect('http://localhost:3000/thank');
            } else {
                // Kiểm tra xem đã có bản ghi thanh toán thất bại chưa
                $existingFailedPayment = Payment::where('order_id', $order->id)->where('status', 'failed')->first();
                if ($existingFailedPayment) {
                    Log::warning('Failed payment already processed', ['order_id' => $order->id]);
                    return redirect('http://localhost:3000/order-error');
                }

                // Tạo bản ghi thanh toán thất bại
                Payment::create([
                    'order_id' => $order->id,
                    'transaction_id' => $vnpTransactionNo,
                    'payment_method' => 'online',
                    'amount' => $vnpAmount / 100,
                    'status' => 'failed',
                    'response_code' => $vnpResponseCode,
                    'secure_hash' => $vnpSecureHash,
                ]);
            
                // KHÔI PHỤC TỒN KHO KHI THANH TOÁN THẤT BẠI
                $orderDetails = \App\Models\Order_detail::where('order_id', $order->id)->get();
                foreach ($orderDetails as $orderDetail) {
                    $productVariant = \App\Models\ProductVariant::find($orderDetail->product_variant_id);
                    if ($productVariant) {
                        $productVariant->quantity += $orderDetail->quantity;
                        $productVariant->save();
                        Log::info('Payment failed: Stock restored', [
                            'product_variant_id' => $productVariant->id,
                            'restored_quantity' => $orderDetail->quantity,
                            'new_quantity' => $productVariant->quantity
                        ]);
                    }
                }
            
                // Kiểm tra và xóa voucher nếu có
                $voucherUsage = Voucher_usage::where('order_id', $order->id)->first();
                if ($voucherUsage) {
                    // Hoàn trả voucher
                    $voucher = \App\Models\Voucher::find($voucherUsage->voucher_id);
                    if ($voucher) {
                        $voucher->increment('quantity');
                        $voucher->decrement('used_times');
                        Log::info('Payment failed: Voucher restored', ['voucher_id' => $voucher->id]);
                    }
                    // Xóa voucher sử dụng
                    $voucherUsage->delete();
                }
            
                // Cập nhật trạng thái đơn hàng và thông báo
                $order->status = 4; // Trạng thái "Hủy"
                $order->message = 'Đơn hàng của bạn đã bị hủy do thanh toán thất bại'; // Thông báo cho người dùng
                $order->save();
            
                return redirect('http://localhost:3000/order-error');
            }
            
        } catch (\Exception $e) {
            Log::error('Payment handling error', [
                'error' => $e->getMessage(),
                'request_data' => $request->all(),
            ]);

            return redirect('http://localhost:3000');
        }
    }
    private function generateVNPaySecureHash(Request $request, $secretKey)
    {
        $vnpParams = $request->except('vnp_SecureHash');
        ksort($vnpParams);
        $query = '';
        foreach ($vnpParams as $key => $value) {
            $query .= urlencode($key) . '=' . urlencode($value) . '&';
        }
        $query = rtrim($query, '&');
        return hash_hmac('sha512', $query, $secretKey);
    }

    /**
     * Kiểm tra trạng thái thanh toán của đơn hàng
     */
    public function checkPaymentStatus($orderId)
    {
        try {
            $order = Order::with(['payment'])->find($orderId);
            
            if (!$order) {
                return response()->json(['message' => 'Không tìm thấy đơn hàng'], 404);
            }

            $payment = $order->payment;
            
            if (!$payment) {
                return response()->json([
                    'order_id' => $order->id,
                    'payment_status' => 'pending',
                    'message' => 'Chưa có thông tin thanh toán'
                ]);
            }

            return response()->json([
                'order_id' => $order->id,
                'payment_status' => $payment->status,
                'transaction_id' => $payment->transaction_id,
                'amount' => $payment->amount,
                'response_code' => $payment->response_code,
                'order_status' => $order->status,
                'order_message' => $order->message
            ]);

        } catch (\Exception $e) {
            Log::error('Check payment status error', [
                'error' => $e->getMessage(),
                'order_id' => $orderId
            ]);
            return response()->json(['message' => 'Lỗi khi kiểm tra trạng thái thanh toán'], 500);
        }
    }
}
