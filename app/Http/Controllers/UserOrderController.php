<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserOrderController extends Controller
{
    public function index(Request $request)
    {
        // Lấy người dùng hiện tại
        $user = auth()->user();

        // Khởi tạo truy vấn với eager loading
        $query = Order::with(['orderDetails.product.categories', 'user', 'review', 'orderDetails.color', 'orderDetails.size'])
            ->where('user_id', $user->id); // Lọc đơn hàng theo người dùng hiện tại

        // Nếu có giá trị status, áp dụng điều kiện where
        if ($request->has('status') && $request->get('status') !== null) {
            $status = $request->get('status');
            $query->where('status', $status);
        }

        // Lấy danh sách đơn hàng, phân trang
        $orders = $query->latest()->paginate(3);

        // Trả về view với danh sách đơn hàng
        return view('user.order', compact('orders'));
    }

    public function show($id)
    {
        // Retrieve the order by ID, including product and user relationships
        $order = Order::with(['product', 'user'])->find($id);

        // Check if the order exists
        if (!$order) {
            return back()->with('error', 'Đơn hàng không tồn tại.');
        }

        // Flash the order data to the session
        session(['selected_order' => $order]);

        // Redirect to the index view to display the selected order details
        return redirect()->route('userorder.show')->with('message', 'Chi tiết đơn hàng đã được hiển thị.');
    }

    public function update(Request $request, $orderId)
    {
        try {
            // Retrieve the order
            $order = Order::where('id', $orderId)
                ->where('user_id', Auth::id())
                ->whereIn('status', [0, 1]) // Allow canceling when pending or processed
                ->first();


            if (!$order) {
                return response()->json(['message' => 'Đơn hàng không tồn tại '], 404);
            }

            // Retrieve the cancellation reason and add custom message if provided
            $cancelReason = $request->input('cancel_reason');
            $otherReason = $request->input('other_reason');
            $message = $cancelReason;

            if ($cancelReason === 'Khác' && !empty($otherReason)) {
                $message .= ": " . $otherReason;
            }

            // Store the cancellation reason in the message field
            $order->message = $message;
            $order->save();

            // Retrieve each order detail and adjust product quantities
            foreach ($order->orderDetails as $orderDetail) {
                $product = Product::find($orderDetail->product_id);

                if ($product) {
                    // Increase the product's quantity and decrease the sold quantity
                    $product->quantity += $orderDetail->quantity;
                    $product->sell_quantity -= $orderDetail->quantity;
                    $product->save();
                }
            }

            // Update order status to indicate it was canceled (set status to 4)
            $order->status = 4; // Assuming 4 is for canceled orders
            $order->save();

            return back()->with('success', 'Hủy đơn hàng thành công, bạn có thể mua sắm lại.');
        } catch (\Exception $e) {
            return back()->with('error', 'Đã có lỗi xảy ra, vui lòng thử lại sau ');
        }
    }

     public function done(Request $request, $orderId)
    {
        // Tìm đơn hàng theo ID
        $order = Order::findOrFail($orderId);

        // Kiểm tra nếu trạng thái đơn hàng chưa phải là "Vận chuyển"
        if ($order->status !== 2) {
            return redirect()->back()->with('error', 'Không thể xác nhận nhận hàng khi đơn hàng chưa được vận chuyển.');
        }

        // Cập nhật trạng thái đơn hàng thành "Hoàn thành" (status = 3)
        $order->status = 3;
        $order->message = 'Hoàn thành';
        $order->save();

        // Gửi thông báo thành công và chuyển hướng
        return back()->with('success', 'Đơn hàng đã được xác nhận hoàn thành.');
    }
}
