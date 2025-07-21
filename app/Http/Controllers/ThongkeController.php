<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Review;
use App\Models\Voucher;
use App\Models\Order_detail;
use App\Models\Voucher_usage;
use App\Models\Product;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ThongkeController extends Controller
{
    public function account(Request $request)
    {
        // Lấy ngày bắt đầu và ngày kết thúc từ form lọc (nếu có)
        $startDate = $request->input('start_date') ? Carbon::parse($request->input('start_date'))->startOfDay() : null;
        $endDate = $request->input('end_date') ? Carbon::parse($request->input('end_date'))->endOfDay() : null;

        // Query cơ bản
        $query = User::where('role', 0);

        // Số lượng người dùng mới tháng này (luôn độcộc lập)
        $currentMonthStart = Carbon::now()->startOfMonth();
        $currentMonthEnd = Carbon::now()->endOfMonth();
        $currentCount = User::where('role', 0)
            ->whereBetween('created_at', [$currentMonthStart, $currentMonthEnd])
            ->count();

        // Số lượng người dùng mới tháng trước (luôn độc lập)
        $previousMonthStart = Carbon::now()->subMonth()->startOfMonth();
        $previousMonthEnd = Carbon::now()->subMonth()->endOfMonth();
        $lastCount = User::where('role', 0)
            ->whereBetween('created_at', [$previousMonthStart, $previousMonthEnd])
            ->count();

        // Số lượng người dùng mới theo form lọc (chỉ khi có bộ lọc)
        $filteredCount = 0;
        if ($startDate && $endDate) {
            $filteredCount = $query->whereBetween('created_at', [$startDate, $endDate])->count();
        }

        // Chuẩn bị dữ liệu đổ vào view 
        $data = [
            'current_count' => $currentCount,   // Tháng nàynày
            'last_count' => $lastCount,         // Tháng trước
            'filtered_count' => $filteredCount, // Theo bộ lọc
        ];

        // Nếu request là AJAX
        if ($request->ajax()) {
            return view('thongke.account', compact('data'))->render();
        }

        // Trả về view
        return view('thongke.account', compact('data'));
    }
}