<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    // Xem giỏ hàng
    public function index()
    {
        $cart = CartItem::with('product')
            ->where('user_id', auth()->id())
            ->get();

        return response()->json($cart);
    }

    // Thêm sản phẩm vào giỏ
    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1'
        ]);

        $cart = CartItem::updateOrCreate(
            [
                'user_id' => auth()->id(),
                'product_id' => $request->product_id
            ],
            [
                'quantity' => DB::raw("quantity + {$request->quantity}")
            ]
        );

        return response()->json(['message' => 'Đã thêm vào giỏ hàng', 'cart' => $cart]);
    }

    // Cập nhật số lượng sản phẩm
    public function update(Request $request, $id)
    {
        $item = CartItem::where('user_id', auth()->id())->findOrFail($id);
        $request->validate(['quantity' => 'required|integer|min:1']);

        $item->update(['quantity' => $request->quantity]);

        return response()->json(['message' => 'Cập nhật thành công']);
    }

    // Xóa 1 sản phẩm khỏi giỏ
    public function destroy($id)
    {
        CartItem::where('user_id', auth()->id())->where('id', $id)->delete();
        return response()->json(['message' => 'Đã xóa khỏi giỏ hàng']);
    }

    // (Tuỳ chọn) Xoá toàn bộ giỏ
    public function clear()
    {
        CartItem::where('user_id', auth()->id())->delete();
        return response()->json(['message' => 'Đã xóa toàn bộ giỏ hàng']);
    }
}
