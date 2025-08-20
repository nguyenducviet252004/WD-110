<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Color;
use App\Models\Product;
use App\Models\Size;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
     public function store(Request $request)
    {
        try {
            // Xác thực dữ liệu đầu vào
            $request->validate([
                'product_id' => 'required|exists:products,id',
                'quantity' => 'required|integer|min:1',
                'color_id' => 'required|exists:colors,id',
                'size_id' => 'required|exists:sizes,id',
            ]);

            // Tìm hoặc tạo giỏ hàng cho người dùng hiện tại
            $cart = Cart::firstOrCreate([
                'user_id' => Auth::id(),
            ]);

            // Lấy thông tin sản phẩm
            $product = Product::findOrFail($request->product_id);

            // Kiểm tra số lượng có đủ không
            if ($request->quantity > $product->quantity) {
                return response()->json(['message' => 'Số lượng yêu cầu vượt quá số lượng có sẵn trong kho.'], 400);
            }

            // Kiểm tra sản phẩm với cùng màu sắc và kích cỡ trong giỏ hàng
            $cartItem = CartItem::where('cart_id', $cart->id)
                ->where('product_id', $product->id)
                ->where('color_id', $request->color_id)
                ->where('size_id', $request->size_id)
                ->first();

            if ($cartItem) {
                // Cập nhật số lượng nếu vượt quá kho
                if (($cartItem->quantity + $request->quantity) > $product->quantity) {
                    return response()->json(['message' => 'Số lượng tổng cộng sau khi thêm vượt quá số lượng có sẵn trong kho.'], 400);
                }

                // Cập nhật giỏ hàng
                $cartItem->quantity += $request->quantity;
                $cartItem->total = $cartItem->quantity * $product->price;
                $cartItem->save();
            } else {
                // Thêm sản phẩm mới vào giỏ hàng với biến thể
                $cartItem = CartItem::create([
                    'cart_id' => $cart->id,
                    'product_id' => $product->id,
                    'color_id' => $request->color_id,
                    'size_id' => $request->size_id,
                    'quantity' => $request->quantity,
                    'price' => $product->price,
                    'total' => $request->quantity * $product->price,
                ]);
            }

            // Lấy thông tin màu sắc và kích thước
            $color = Color::findOrFail($request->color_id);
            $size = Size::findOrFail($request->size_id);

            // Dữ liệu trả về
            $responseData = [
                'id' => $cartItem->id,
                'product_id' => $product->id,
                'product_name' => $product->name,
                'color' => $color->name_color,
                'size' => $size->size,
                'quantity' => $cartItem->quantity,
                'price' => $product->price,
                'total' => $cartItem->total,
                'message' => 'Sản phẩm đã được thêm vào giỏ hàng.',
            ];

            return response()->json($responseData, 201);
        } catch (\Exception $e) {
            // Xử lý lỗi và trả về thông điệp lỗi
            return response()->json(['message' => 'Có lỗi xảy ra: ' . $e->getMessage()], 500);
        }
    }
}
