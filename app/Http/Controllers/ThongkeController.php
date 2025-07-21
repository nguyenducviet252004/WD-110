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

    public function orders(Request $request)
    {
        // Lấy ngày bắt đầu và ngày kết thúc từ form lọc (nếu có)
        $startDate = $request->input('start_date') ? Carbon::parse($request->input('start_date'))->startOfDay() : null;
        $endDate = $request->input('end_date') ? Carbon::parse($request->input('end_date'))->endOfDay() : null;

        // Query cho đơn hàng đã hoàn thành (tất cả đơn hàng)
        $query = Order::where('status', 3);

        // Tính toán doanh thu và số lượng đơn hàng cho tháng này, không bị ảnh hưởng bởi bộ lọc
        $currentMonthStart = Carbon::now()->startOfMonth();
        $currentMonthEnd = Carbon::now()->endOfMonth();
        $currentRevenue = Order::where('status', 3)
            ->whereBetween('created_at', [$currentMonthStart, $currentMonthEnd])
            ->sum('total_amount');
        $currentOrderCout = Order::where('status', 3)
            ->whereBetween('created_at', [$currentMonthStart, $currentMonthEnd])
            ->count();

        // Tính toán doanh thu và số lượng đơn hàng cho tháng trước, không bị ảnh hưởng bởi bộ lọc
        $lastMonthStart = Carbon::now()->subMonth()->startOfMonth();
        $lastMonthEnd = Carbon::now()->subMonth()->endOfMonth();
        $lastRevenue = Order::where('status', 3)
            ->whereBetween('created_at', [$lastMonthStart, $lastMonthEnd])
            ->sum('total_amount');
        $lastOrderCount = Order::where('status', 3)
            ->whereBetween('created_at', [$lastMonthStart, $lastMonthEnd])
            ->count();

        // Tính sự thay đổi giữa tháng hiện tại và tháng trước
        $changeRevenue = $currentRevenue - $lastRevenue;
        $orderCountChange = $currentOrderCout - $lastOrderCount;

        // Tính doanh thu và số lượng đơn hàng theo bộ lọc (nếu có)
        if ($startDate && $endDate){
            // Nếu có bộ lọc, sử dụng whereBetween() đẻ lấy dữ liệu trong khoảng thời gian đã chọn
            $filteredRevenue = $query->whereBetween('created_at', [$startDate, $endDate])->sum('total_amount');
            $filteredOrderCount = $query->whereBetween('created_at', [$startDate, $endDate])->count();
        } else {
            // Nếu không có bộ lọc thì mặc định là 0
            $filteredRevenue = 0;
            $filteredOrderCount = 0;
        }

        // Dữ liệu để trả về view
        $data = [
            'current_revenue' => $currentRevenue,           // Doanh thu tháng này
            'current_order_count' => $currentOrderCout,     // Số lượng đơn hàng tháng này
            'last_revenue' => $lastRevenue,                 // Doanh thu tháng trước
            'last_order_count' => $lastOrderCount,          // Số lượng đơn hàng tháng trước
            'change_revenue' => $changeRevenue,             // Sự thay đổi doanh thu
            'order_count_change' => $orderCountChange,      // Sự thay đổi số lượng đơn hàng
            'filtered_revenue' => $filteredRevenue,         // Doanh thu theo bộ lọc
            'filtered_order_count' => $filteredOrderCount,  // Số lượng đơn hàng theo bộ lọc
        ];

        // Kiểm tra AJAX để trả về view phù hợp
        if ($request->ajax()) {
            return view('thongke.orders', compact('data'))->render();
        }

        // Trả về view chính
        return view('thongke.orders', compact('data'));
    }

    public function topproduct(Request $request)
    {
        // Lấy ngày bắt đầu và kết thúc từ bộ lọc hoặc sử dụng giá trị mặc định
        $startDate = $request->input('start_date', now()->startOfDay());
        $endDate = $request->input('end_date', now()->endOfDay());

        // Lấy danh sách sanr phẩm bán chạy nhất dựa trên khoảng thời gian
        $topProducts = Order_detail::select(
            'product_id',
            DB::raw('SUM(quantity) as total_quantity'),
            DB::raw('COUNT(order_id) as sales_count'),
            DB::raw('SUM(total) as total_revenue')
        )
            ->whereHas('order', function ($query) use ($startDate, $endDate) {
                $query->whereBetween('creates_at', [$startDate, $endDate]);
            })
            ->groupBy('product_id')
            ->orderBy('total_quantity', 'desc')
            ->take(10) // Lấy 10 sản phẩm bán chạy nhất
            ->get()
            ->map(function ($item) {
                $product = Product::find($item->product_id);
                return [
                    'product_id' => $item->product_id,
                    'product_name' => $product ? $product->name : 'Unknown Product',
                    'image' => $product ? $product->avatar : null,
                    'total_quantity' => $item->total_quantity,
                    'sales_count' => $item->sales_count,
                    'total_revenue' => $item->total_revenue
                ];
            })
            ->toArray();

        // Kiểm tra nếu không có dữ liệu sản phẩm
        if (empty($topProducts)) {
            $topProducts = null; // Hoặc bất kì thông báo nào cho biết không có dữ liệu
        }

        // Nếu dây là yêu cầu AJAX, trả về view đã render cho sản phẩm bán chạy nhất
        if ($request->ajax()) {
            return view('thongke.topproduct', compact('topProducts'))->render();
        }

        // Trả về view chính
        return view('thongke.topproduct', compact('topProducts'));
    }
}