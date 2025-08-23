<?php

namespace App\Http\Controllers;

use App\Events\OrderStatusUpdated;
use App\Mail\OrderDelivered;
use App\Mail\OrderStatusChanged;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with(['user', 'shipAddress', 'orderDetails', 'payment']);
    
        // Lọc theo trạng thái đơn hàng
        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        // Lọc theo mã đơn hàng
        if ($request->filled('keyword')) {
            $keyword = $request->input('keyword');
            $query->where('id', 'like', "%$keyword%") ;
        }

        // Lấy danh sách đơn hàng
        $orders = $query->latest()->paginate(5);

        if ($request->has('order_id') && $request->has('status')) {
            $orderId = $request->input('order_id');
            $newStatus = $request->input('status');

            $order = Order::find($orderId);

            if ($order) {
                $oldStatus = $order->status; // Lưu trạng thái cũ để so sánh

                // VALIDATION: Kiểm tra quy tắc cập nhật trạng thái
                $allowedTransitions = [
                    0 => [1, 4], // Chờ xử lý -> Đã xử lý hoặc Hủy
                    1 => [2, 4], // Đã xử lý -> Đang vận chuyển hoặc Hủy
                    2 => [3], // Đang vận chuyển -> Giao hàng thành công
                    3 => [5],    // Giao hàng thành công -> Đã trả lại
                    4 => [],     // Đã hủy -> Không thể chuyển sang trạng thái khác
                    5 => []      // Đã trả lại -> Không thể chuyển sang trạng thái khác
                ];

                // Kiểm tra xem có được phép chuyển từ trạng thái cũ sang trạng thái mới không
                if (!in_array($newStatus, $allowedTransitions[$oldStatus])) {
                    $statusNames = [
                        0 => 'Chờ xử lý',
                        1 => 'Đã xử lý',
                        2 => 'Đang vận chuyển',
                        3 => 'Giao hàng thành công',
                        4 => 'Đã hủy',
                        5 => 'Đã trả lại'
                    ];

                    $currentStatusName = $statusNames[$oldStatus] ?? 'Không xác định';
                    $newStatusName = $statusNames[$newStatus] ?? 'Không xác định';

                    return redirect()->back()->with('error',
                        "Không thể chuyển từ trạng thái '{$currentStatusName}' sang '{$newStatusName}'. " .
                        "Chỉ có thể cập nhật từng bước một theo quy trình: Chờ xử lý → Đã xử lý → Đang vận chuyển → Giao hàng thành công"
                    );
                }

                // Kiểm tra nếu trạng thái chuyển sang 4 (hủy đơn)
                if ($newStatus == 4) {
                    // Cập nhật cột message khi trạng thái đơn hàng là 4 (hủy)
                    $order->message = 'Đơn hàng đã bị hủy bởi hệ thống';
                }

                // Điều kiện gửi email nếu trạng thái thay đổi từ 0 sang 1
                if ($oldStatus == 0 && $newStatus == 1) {
                    try {
                        // Gửi email thông báo cho người dùng
                        Mail::to($order->user->email)->send(new OrderStatusChanged($order));
                    } catch (\Exception $e) {
                        // Log lỗi nhưng không dừng quá trình cập nhật đơn hàng
                        Log::error('Lỗi gửi email thông báo trạng thái đơn hàng: ' . $e->getMessage());
                    }
                }

                // Điều kiện gửi email nếu trạng thái thay đổi từ 2 sang 3
                if ($oldStatus == 2 && $newStatus == 3) {
                    try {
                        // Gửi email thông báo giao hàng thành công
                        Mail::to($order->user->email)->send(new OrderDelivered($order));
                    } catch (\Exception $e) {
                        // Log lỗi nhưng không dừng quá trình cập nhật đơn hàng
                        Log::error('Lỗi gửi email thông báo giao hàng: ' . $e->getMessage());
                    }
                    // Cập nhật sell_quantity cho từng sản phẩm trong đơn hàng
                    foreach ($order->orderDetails as $orderDetail) {
                        $product = $orderDetail->product;
                        if ($product) {
                            $product->sell_quantity += $orderDetail->quantity;
                            $product->save();
                        }
                    }
                }

                // Cập nhật trạng thái mới của đơn hàng
                $order->status = $newStatus;
                $order->save();

                // Broadcast event for realtime updates
                event(new OrderStatusUpdated($order, $oldStatus, $newStatus, 'admin'));

                return redirect()->back()->with('success', 'Trạng thái đơn hàng đã được cập nhật!');
            }

            return redirect()->back()->with('error', 'Không tìm thấy đơn hàng.');
        }

        return view('order.index', compact('orders'));
    }

    // Helper function để lấy danh sách trạng thái được phép chuyển
    private function getNextAllowedStatuses($currentStatus)
    {
        $allowedTransitions = [
            0 => [1, 4], // Chờ xử lý -> Đã xử lý hoặc Hủy
            1 => [2, 4], // Đã xử lý -> Đang vận chuyển hoặc Hủy
            2 => [3], // Đang vận chuyển -> Giao hàng thành công
            3 => [5],    // Giao hàng thành công -> Đã trả lại
            4 => [],     // Đã hủy -> Không thể chuyển
            5 => []      // Đã trả lại -> Không thể chuyển
        ];

        return $allowedTransitions[$currentStatus] ?? [];
    }

    public function show(string $id)
    {
        $order = Order::with([
            'user',
            'product',
            'shipAddress',
            'orderDetails.product' => function ($query) {
                $query->select('id', 'name', 'img_thumb');
            },
            'orderDetails.color',
            'orderDetails.size',
            'payment' // Load thông tin thanh toán của đơn hàng
        ])->findOrFail($id);

        // Debug logging để kiểm tra dữ liệu địa chỉ
        if (config('app.debug')) {
            Log::info('Order Debug Info', [
                'order_id' => $order->id,
                'ship_address_id' => $order->ship_address_id,
                'has_ship_address' => $order->shipAddress ? 'Yes' : 'No',
                'ship_address_data' => $order->shipAddress ? [
                    'recipient_name' => $order->shipAddress->recipient_name,
                    'phone_number' => $order->shipAddress->phone_number,
                    'ship_address' => $order->shipAddress->ship_address,
                    'address_length' => strlen($order->shipAddress->ship_address ?? '')
                ] : null
            ]);
        }

        // Kiểm tra và cảnh báo nếu không có địa chỉ giao hàng
        if (!$order->shipAddress && $order->ship_address_id) {
            Log::warning('Order has ship_address_id but no shipAddress relationship loaded', [
                'order_id' => $order->id,
                'ship_address_id' => $order->ship_address_id
            ]);
        }

        return view('order.show', compact('order'));
    }

    public function edit(string $id)
    {
        //
    }

}