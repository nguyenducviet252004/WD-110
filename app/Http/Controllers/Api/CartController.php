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

      public function show($userId)
    {
        try {
            // Lấy giỏ hàng của người dùng
            $cart = Cart::where('user_id', $userId)->first();
    
            // Kiểm tra xem người dùng có giỏ hàng không
            if (!$cart) {
                return response()->json(['message' => 'Giỏ hàng không tồn tại.'], 404);
            }
    
            // Lấy tất cả các sản phẩm trong giỏ hàng, kèm theo thông tin màu sắc và kích thước
            $cartItems = CartItem::with(['product', 'color', 'size'])
                ->where('cart_id', $cart->id)
                ->get();
    
            // Kiểm tra nếu không có sản phẩm nào trong giỏ hàng
            if ($cartItems->isEmpty()) {
                return response()->json(['message' => 'Giỏ hàng không có sản phẩm.'], 404);
            }
    
            // Tạo dữ liệu trả về cho tất cả sản phẩm trong giỏ hàng
            $responseData = $cartItems->map(function ($cartItem) {
                $colorName = $cartItem->color ? $cartItem->color->name_color : null;  // Lấy tên màu
                $sizeName = $cartItem->size ? $cartItem->size->size : null;  // Lấy tên size
    
                return [
                    'id' => $cartItem->id,
                    'product_id' => $cartItem->product_id,
                    'product_name' => $cartItem->product->name,
                    'avatar' => $cartItem->product->avatar,
                    'color' => $colorName,
                    'size' => $sizeName,
                    'quantity' => $cartItem->quantity,
                    'price' => $cartItem->price,
                    'total' => $cartItem->total,
                ];
            });
    
            return response()->json([
                'status' => true,
                'cart_items' => $responseData,
                'message' => 'Thông tin giỏ hàng của người dùng.'
            ], 200);
    
        } catch (\Exception $e) {
            return response()->json(['message' => 'Có lỗi xảy ra: ' . $e->getMessage()], 500); // Trả về mã 500 Internal Server Error
        }
    }

     public function update(Request $request, $itemId)
    {
        try {
            // Validate only the provided fields
            $validatedData = $request->validate([
                'quantity' => 'nullable|integer|min:1',
                'color_id' => 'nullable|exists:colors,id',
                'size_id' => 'nullable|exists:sizes,id',
            ]);
        
            // Find the cart item
            $cartItem = CartItem::with(['product', 'color', 'size'])->findOrFail($itemId);
            $product = Product::findOrFail($cartItem->product_id); // Get the product to calculate price
        
            // If quantity is provided, check if it's within the available stock
            if (isset($validatedData['quantity'])) {
                if ($validatedData['quantity'] > $product->quantity) {
                    // Return error response if quantity is greater than available stock
                    return response()->json([
                        'message' => 'Số lượng yêu cầu vượt quá số lượng còn lại trong kho.',
                        'available_quantity' => $product->quantity,
                    ], 400); // Bad Request response
                }
                
                $cartItem->quantity = $validatedData['quantity'];
                $cartItem->total = $cartItem->quantity * $product->price;
            }
    
            if (isset($validatedData['color_id'])) {
                $cartItem->color_id = $validatedData['color_id'];
            }
    
            if (isset($validatedData['size_id'])) {
                $cartItem->size_id = $validatedData['size_id'];
            }
    
            $cartItem->save();
        
            // Get color and size names (if available)
            $colorName = $cartItem->color ? $cartItem->color->name_color : null;
            $sizeName = $cartItem->size ? $cartItem->size->size : null;
        
            // Return response data
            $responseData = [
                'id' => $cartItem->id,
                'product_id' => $cartItem->product_id,
                'product_name' => $product->name,
                'color' => $colorName, // Color name
                'size' => $sizeName,   // Size name
                'quantity' => $cartItem->quantity,
                'price' => $product->price,
                'total' => $cartItem->total,
                'message' => 'Giỏ hàng đã được cập nhật.',
                'available_quantity' => $product->quantity, // Show available quantity
            ];
        
            return response()->json($responseData, 200); // Return OK response
        } catch (\Exception $e) {
            return response()->json(['message' => 'Có lỗi xảy ra: ' . $e->getMessage()], 500); // Return error response
        }
    }

     public function destroy($itemId)
    {
        try {
            $cartItem = CartItem::findOrFail($itemId);
            $cartItem->delete();

            return response()->json(['message' => 'Sản phẩm đã được xóa khỏi giỏ hàng.'], 200); // Trả về mã 200 OK
        } catch (\Exception $e) {
            return response()->json(['message' => 'Có lỗi xảy ra: ' . $e->getMessage()], 500); // Trả về mã 500 Internal Server Error
        }
    }
}
