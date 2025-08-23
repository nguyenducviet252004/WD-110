<?php

namespace App\Helpers;

class OrderHelper
{
    // Lấy danh sách trạng thái được phép chuyển đổi từ trạng thái hiện tại
    public static function getNextAllowedStatuses($currentStatus)
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

    // Lấy tên trạng thái ID
    public static function getStatusName($statusId)
    {
        $statusNames = [
            0 => 'Chờ xử lý',
            1 => 'Đã xử lý',
            2 => 'Đang vận chuyển',
            3 => 'Giao hàng thành công',
            4 => 'Đã hủy',
            5 => 'Đã trả lại'
        ];

        return $statusNames[$statusId] ?? 'Không xác định';
    }

    // Kiểm tra xem có thể chuyển từ trạng thái này qua trạng thái khác không
    public static function canTransitionTo($fromStatus, $toStatus)
    {
        $allowedStatuses = self::getNextAllowedStatuses($fromStatus);
        return in_array($toStatus, $allowedStatuses);
    }
}