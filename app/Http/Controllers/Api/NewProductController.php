<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Color;
use App\Models\Size;
use Illuminate\Http\Request;

class NewProductController extends Controller
{
    public function index()
    {
        try {
            // Lấy 30 sản phẩm được thêm gần đây nhất và thông tin liên quan
            $products = Product::with(['categories:id, name', 'colors:id, name_color', 'sizes:id,size'])
                ->where('is_active', 1)
                ->orderBy('created_at', 'desc') // Sắp xếp sản phẩm mới nhất lên đầu
                ->limit(30) // Giới hạn 30 sản phẩm
                ->get();

            // Lấy tất cả màu sắc và kích thước từ bảng colors và sizes
            $allColors = Color::all(); // Tất cả các màu sắc
            $allSizes = Size::all();   // Tất cả các kích thướcthước

            // Chuyển đổi dữ liệu sản phẩm
            $products = $products->map(function ($product) {
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'description' => $product->description,
                    'avatar_url' => $product->avatar ? asset('storage/ProductAvatars/' . basename($product->avatar)) : null,
                    'categories' => $product->categories,
                    'price' => $product->price,
                    'avatar' => $product->avatar,
                    'quantity' => $product->quantity,
                    'view' => $product->view,
                    'colors' => $product->colors, // Màu sắc liên quan đến sản phẩm
                    'sizes' => $product->sizes,   // Kích thước liên quan đến sản phẩm
                    'created_at' => $product->created_at,
                    'updated_at' => $product->updated_at,
                ];
            });

            $response = [
                'products' => $products,   // Danh sách sản phẩm
                'all_colors' => $allColors, // Tất cả các màu sắc
                'all_sizes' => $allSizes,   // Tất cả các kích thước
            ];

            return response()->json($response);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Không thể lấy danh sách sản phẩm. ' . $e->getMessage()], 500);
        }
    }
}