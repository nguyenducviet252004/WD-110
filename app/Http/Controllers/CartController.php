<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use Illuminate\Http\Request;

class CartController extends Controller
{
    // GET /api/cart - Lấy danh sách giỏ hàng
    public function index()
    {
        $items = CartItem::with('productVariant')
            ->where('user_id', auth()->id())
            ->get();

        return response()->json($items);
    }

    // POST /api/cart/add - Thêm sản phẩm vào giỏ
    public function add(Request $request)
    {
        $data = $request->validate([
            'product_variant_id' => 'required|exists:product_variants,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $item = CartItem::firstOrNew([
            'user_id' => auth()->id(),
            'product_variant_id' => $data['product_variant_id'],
        ]);

        $item->quantity += $data['quantity'];
        $item->save();

        return response()->json([
            'message' => 'Đã thêm vào giỏ hàng',
            'item' => $item,
        ]);
    }

    // PUT /api/cart/update/{id} - Cập nhật số lượng sản phẩm trong giỏ
    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $item = CartItem::where('user_id', auth()->id())->findOrFail($id);
        $item->update(['quantity' => $data['quantity']]);

        return response()->json([
            'message' => 'Đã cập nhật giỏ hàng',
            'item' => $item,
        ]);
    }

    // DELETE /api/cart/remove/{id} - Xóa 1 sản phẩm trong giỏ
    public function destroy($id)
    {
        $item = CartItem::where('user_id', auth()->id())->findOrFail($id);
        $item->delete();

        return response()->json([
            'message' => 'Đã xóa sản phẩm khỏi giỏ hàng',
        ]);
    }

    // DELETE /api/cart/clear - Xóa toàn bộ giỏ hàng
    public function clear()
    {
        CartItem::where('user_id', auth()->id())->delete();

        return response()->json([
            'message' => 'Đã xóa toàn bộ giỏ hàng',
        ]);
    }
}
