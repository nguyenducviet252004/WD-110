<?php

namespace App\Http\Controllers;

use App\Mail\OrderDelivered;
use App\Mail\OrderStatusChanged;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with('user', 'shipAddress', 'orderDetails');
    
        if ($request->has('status') && $request->input('status') !== '') {
            // Lọc theo trạng thái nếu có giá trị trong input
            $status = $request->input('status');
            if ($status !== '') {
                $query->where('status', $status);
            }
        }
    
        // Lấy danh sách đơn hàng (nếu không có trạng thái, sẽ lấy tất cả)
        $orders = $query->orderByRaw("CASE WHEN status = 0 THEN 0 ELSE 1 END")
            ->latest()
            ->paginate(5);
    
        if ($request->has('order_id') && $request->has('status')) {
            $orderId = $request->input('order_id');
            $newStatus = $request->input('status');
    
            $order = Order::find($orderId);
    
            if ($order) {
                $oldStatus = $order->status; // Lưu trạng thái cũ để so sánh
    
                // Kiểm tra nếu trạng thái chuyển sang 4 (hủy đơn)
                if ($newStatus == 4) {
                    // Cập nhật cột message khi trạng thái đơn hàng là 4 (hủy)
                    $order->message = 'Đơn hàng đã bị hủy bởi hệ thống';
                }
    
                // Điều kiện gửi email nếu trạng thái thay đổi từ 0 sang 1
                if ($oldStatus == 0 && $newStatus == 1) {
                    // Gửi email thông báo cho người dùng
                    Mail::to($order->user->email)->send(new OrderStatusChanged($order));
                }
                
                // Điều kiện gửi mail nếu trạng thái chuyển từ 2 sang 3
                if($oldStatus == 2 && $newStatus == 3) {
                    // Gửi email thông báo đơn hàng đã được giao
                    Mail::to($order->user->email)->send(new OrderDelivered($order)); // Bạn có thể tạo 1 Mailable mới cho OrderDelivered
                }

                // Cập nhật trạng thái đơn hàng
                $order->status = $newStatus;
                $order->save();

                return redirect()->back()->with('success', 'Cập nhật trạng thái đơn hàng thành công.');
            }

            return redirect()->back()->with('error', 'Đơn hàng không tồn tại.');
        }

        return view('order.index', compact('orders'));
    }

    public function show(string $id)
    {
        $order = Order::with([
            'user',
            'product',
            'shipAddress',
            'orderDetails.product',
            'orderDetails.color',
            'orderDetails.size',
            'payment' // load thông tin thanh toán đơn hàng
        ])->findOrFail($id);

        return view('order.show', compact('order'));
    }

    public function edit(string $id)
    {
        //
    }

}