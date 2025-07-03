<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductGallery;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductApiController extends Controller
{
    public function index()
    {
        $products = Product::with('category')->latest()->paginate(10);
        return response()->json($products);
    }

    public function show($id)
    {
        $product = Product::with([
            'category',
            'galleries',
            'variants.size',
            'variants.color'
        ])->findOrFail($id);

        return response()->json($product);
    }



    public function store(Request $request)
    {
        $data = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'slug' => 'required|string|unique:products',
            'sku' => 'required|string|unique:products',
            'thumb_image' => 'nullable|string',
            'price_regular' => 'required|numeric|min:0',
            'price_sale' => 'nullable|numeric|min:0',
            'description' => 'nullable|string',
            'content' => 'nullable|string',
            'is_active' => 'boolean',
            'variants' => 'array',
            'variants.*.product_color_id' => 'required|exists:product_colors,id',
            'variants.*.product_size_id' => 'required|exists:product_sizes,id',
            'variants.*.quantity' => 'required|integer|min:0',
            'variants.*.image' => 'nullable|string',
            'galleries' => 'array',
            'galleries.*' => 'string',
        ]);

        DB::beginTransaction();
        try {
            $product = Product::create($data);

            foreach ($data['variants'] as $variant) {
                $variant['product_id'] = $product->id;
                ProductVariant::create($variant);
            }

            foreach ($data['galleries'] as $image) {
                ProductGallery::create([
                    'product_id' => $product->id,
                    'image' => $image,
                ]);
            }

            DB::commit();
            return response()->json(['message' => 'Tạo sản phẩm thành công', 'product' => $product], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Lỗi khi tạo sản phẩm', 'details' => $e->getMessage()], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $data = $request->validate([
            'category_id' => 'sometimes|exists:categories,id',
            'name' => 'sometimes|string|max:255',
            'slug' => 'sometimes|string|unique:products,slug,' . $product->id,
            'sku' => 'sometimes|string|unique:products,sku,' . $product->id,
            'thumb_image' => 'nullable|string',
            'price_regular' => 'sometimes|numeric|min:0',
            'price_sale' => 'nullable|numeric|min:0',
            'description' => 'nullable|string',
            'content' => 'nullable|string',
            'is_active' => 'boolean',
            'variants' => 'array',
            'variants.*.product_color_id' => 'required|exists:product_colors,id',
            'variants.*.product_size_id' => 'required|exists:product_sizes,id',
            'variants.*.quantity' => 'required|integer|min:0',
            'variants.*.image' => 'nullable|string',
            'galleries' => 'array',
            'galleries.*' => 'string',
        ]);

        DB::beginTransaction();
        try {
            $product->update($data);

            // Xóa dữ liệu cũ
            ProductVariant::where('product_id', $product->id)->delete();
            ProductGallery::where('product_id', $product->id)->delete();

            // Tạo lại variants
            foreach ($data['variants'] as $variant) {
                $variant['product_id'] = $product->id;
                ProductVariant::create($variant);
            }

            foreach ($data['galleries'] as $image) {
                ProductGallery::create([
                    'product_id' => $product->id,
                    'image' => $image,
                ]);
            }

            DB::commit();
            return response()->json(['message' => 'Cập nhật thành công', 'product' => $product]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Lỗi khi cập nhật', 'details' => $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();

        return response()->json(['message' => 'Xóa sản phẩm thành công']);
    }
}
