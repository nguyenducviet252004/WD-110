<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Voucher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class VoucherController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Kiểm tra xem người dùng đã đăng nhập hay chưa
        if (!Auth::check()) {
            return response()->json(['message' => 'User not logged in.'], 401);
        }

        $userId = Auth::id();

        // Lấy giỏ hàng của người dùng
        $cart = Cart::where('user_id', $userId)->first();
        if (!$cart) {
            return response()->json(['message' => 'No cart found.'], 400);
        }

        $cartItems = CartItem::where('cart_id', $cart->id)->get();
        if ($cartItems->isEmpty()) {
            return response()->json(['message' => 'No items in the cart.'], 400);
        }

        // Tính tổng giá trị đơn hàng
        $totalAmount = $cartItems->sum(fn($item) => $item->quantity * $item->price);

        // Lấy tất cả các voucher hợp lệ
        $vouchers = Voucher::where('is_active', 1) // Voucher đang hoạt động
            ->where('quantity', '>', 0) // Voucher còn số lượng
            ->where(function ($query) {
                $query->whereNull('start_day')
                    ->orWhere('start_day', '<=', now());
            })
            ->where(function ($query) {
                $query->whereNull('end_day')
                    ->orWhere('end_day', '>=', now());
            })
            ->get();

        $applicableVouchers = [];

        foreach ($vouchers as $voucher) {
            // Kiểm tra xem voucher này đã được người dùng sử dụng hay chưa
            $voucherUsageExists = DB::table('voucher_usages')
                ->where('user_id', $userId)
                ->where('voucher_id', $voucher->id)
                ->exists();

            if ($voucherUsageExists) {
                continue; // Bỏ qua voucher nếu đã sử dụng
            }

            // Kiểm tra tổng giá trị giỏ hàng có đủ điều kiện với total_min và total_max
            if ($totalAmount >= $voucher->total_min && ($voucher->total_max === null || $totalAmount <= $voucher->total_max)) {
                // Nếu voucher thỏa mãn điều kiện, thêm vào danh sách voucher hợp lệ
                $applicableVouchers[] = $voucher;
            }
        }

        // Sắp xếp các voucher theo giá trị giảm giá từ cao đến thấp
        usort($applicableVouchers, function ($a, $b) {
            return $b->discount_value <=> $a->discount_value;
        });

        // Trả về mảng các voucher hợp lệ
        return response()->json([
            'status' => true,
            'total_amount' => $totalAmount,
            'vouchers' => $applicableVouchers, // Trả về mảng các voucher có thể áp dụng
        ]);
    }




    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) {}

    /**
     * Display the specified resource.
     */
    public function show(Voucher  $voucher)
    {
        try {
            return response()->json($voucher);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Không thể lấy thông tin voucher: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Voucher  $voucher) {}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Voucher  $voucher) {}
}