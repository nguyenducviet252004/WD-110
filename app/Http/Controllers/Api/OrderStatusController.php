<?php

namespace App\Http\Controllers\Api;

use App\Events\OrderStatusUpdated;
use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class OrderStatusController extends Controller
{
    /**
     * Update order status via API
     */
    public function updateStatus(Request $request, $orderId)
    {
        try {
            $request->validate([
                'status' => 'required|integer|min:0|max:5',
                'message' => 'nullable|string|max:255'
            ]);

            $order = Order::findOrFail($orderId);
            $oldStatus = $order->status;
            $newStatus = $request->input('status');

            // Validate status transition
            $allowedTransitions = [
                0 => [1, 4], // Chờ xử lý -> Đã xử lý hoặc Hủy
                1 => [2, 4], // Đã xử lý -> Đang vận chuyển hoặc Hủy
                2 => [3, 4], // Đang vận chuyển -> Giao hàng thành công hoặc Hủy
                3 => [5],    // Giao hàng thành công -> Đã trả lại
                4 => [],     // Đã hủy -> Không thể chuyển
                5 => []      // Đã trả lại -> Không thể chuyển
            ];

            if (!in_array($newStatus, $allowedTransitions[$oldStatus])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Không thể chuyển từ trạng thái này sang trạng thái khác'
                ], 400);
            }

            // Update order status
            $order->status = $newStatus;
            if ($request->has('message')) {
                $order->message = $request->input('message');
            }
            $order->save();

            // Broadcast the event
            event(new OrderStatusUpdated($order, $oldStatus, $newStatus, 'admin'));

            return response()->json([
                'success' => true,
                'message' => 'Cập nhật trạng thái thành công',
                'data' => [
                    'order_id' => $order->id,
                    'old_status' => $oldStatus,
                    'new_status' => $newStatus,
                    'status_text' => $this->getStatusText($newStatus)
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Error updating order status: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi cập nhật trạng thái'
            ], 500);
        }
    }

    /**
     * Get order status
     */
    public function getStatus($orderId)
    {
        try {
            $order = Order::findOrFail($orderId);
            
            return response()->json([
                'success' => true,
                'data' => [
                    'order_id' => $order->id,
                    'status' => $order->status,
                    'status_text' => $this->getStatusText($order->status),
                    'message' => $order->message,
                    'updated_at' => $order->updated_at
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy đơn hàng'
            ], 404);
        }
    }

    /**
     * Get status text for display
     */
    private function getStatusText($status): string
    {
        $statusMap = [
            0 => 'Chờ xử lý',
            1 => 'Đã xử lý',
            2 => 'Đang vận chuyển',
            3 => 'Giao hàng thành công',
            4 => 'Đã hủy',
            5 => 'Đã trả lại'
        ];

        return $statusMap[$status] ?? 'Không xác định';
    }
}
