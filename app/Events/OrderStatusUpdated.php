<?php

namespace App\Events;

use App\Models\Order;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class OrderStatusUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $order;
    public $oldStatus;
    public $newStatus;
    public $updatedBy;

    /**
     * Create a new event instance.
     */
    public function __construct(Order $order, $oldStatus, $newStatus, $updatedBy = 'admin')
    {
        $this->order = $order;
        $this->oldStatus = $oldStatus;
        $this->newStatus = $newStatus;
        $this->updatedBy = $updatedBy;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('order.' . $this->order->id),
            new Channel('orders'),
        ];
    }

    /**
     * Get the data to broadcast.
     */
    public function broadcastWith(): array
    {
        return [
            'order_id' => $this->order->id,
            'user_id' => $this->order->user_id,
            'old_status' => $this->oldStatus,
            'new_status' => $this->newStatus,
            'status_text' => $this->getStatusText($this->newStatus),
            'updated_by' => $this->updatedBy,
            'updated_at' => now()->toISOString(),
            'message' => $this->order->message,
        ];
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
