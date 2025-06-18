<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with(['category', 'variants.size', 'variants.color'])->get();
        return response()->json([
            'status' => 'success',
            'data' => $products
        ], 200);
    }

    public function store(StoreProductRequest $request)
    {
        return DB::transaction(function () use ($request) {
            $productData = $request->except('variants', 'thumb_image');

            // Xử lý upload thumb_image
            if ($request->hasFile('thumb_image')) {
                $productData['thumb_image'] = $request->file('thumb_image')->store('products', 'public');
            }

            $product = Product::create($productData);

            // Xử lý variants
            if ($request->has('variants')) {
                foreach ($request->input('variants') as $index => $variant) {
                    $variantData = [
                        'product_size_id' => $variant['product_size_id'],
                        'product_color_id' => $variant['product_color_id'],
                        'quantity' => $variant['quantity'],
                    ];

                    // Xử lý upload image cho variant
                    if ($request->hasFile("variants.$index.image")) {
                        $variantData['image'] = $request->file("variants.$index.image")->store('variants', 'public');
                    }

                    $product->variants()->create($variantData);
                }
            }

            return response()->json([
                'status' => 'success',
                'data' => $product->load(['category', 'variants.size', 'variants.color'])
            ], 201);
        });
    }

    public function show(Product $product)
    {
        return response()->json([
            'status' => 'success',
            'data' => $product->load(['category', 'variants.size', 'variants.color'])
        ], 200);
    }

    public function update(UpdateProductRequest $request, Product $product)
    {
        return DB::transaction(function () use ($request, $product) {
            $productData = $request->except('variants', 'thumb_image');

            // Xử lý upload thumb_image mới
            if ($request->hasFile('thumb_image')) {
                // Xóa ảnh cũ nếu tồn tại
                if ($product->thumb_image) {
                    Storage::disk('public')->delete($product->thumb_image);
                }
                $productData['thumb_image'] = $request->file('thumb_image')->store('products', 'public');
            }

            $product->update($productData);

            // Xử lý variants
            if ($request->has('variants')) {
                $existingVariantIds = $product->variants()->pluck('id')->toArray();
                $newVariantIds = array_filter(array_column($request->input('variants'), 'id') ?? []);

                // Xóa các biến thể không còn trong danh sách
                $product->variants()->whereNotIn('id', $newVariantIds)->each(function ($variant) {
                    if ($variant->image) {
                        Storage::disk('public')->delete($variant->image);
                    }
                    $variant->delete();
                });

                // Cập nhật hoặc tạo mới biến thể
                foreach ($request->input('variants') as $index => $variant) {
                    $variantData = [
                        'product_size_id' => $variant['product_size_id'],
                        'product_color_id' => $variant['product_color_id'],
                        'quantity' => $variant['quantity'],
                    ];

                    // Xử lý upload image cho variant
                    if ($request->hasFile("variants.$index.image")) {
                        // Xóa ảnh cũ nếu tồn tại
                        if (isset($variant['id']) && $existingVariant = $product->variants()->find($variant['id'])) {
                            if ($existingVariant->image) {
                                Storage::disk('public')->delete($existingVariant->image);
                            }
                        }
                        $variantData['image'] = $request->file("variants.$index.image")->store('variants', 'public');
                    }

                    if (isset($variant['id']) && in_array($variant['id'], $existingVariantIds)) {
                        $product->variants()->find($variant['id'])->update($variantData);
                    } else {
                        $product->variants()->create($variantData);
                    }
                }
            }

            return response()->json([
                'status' => 'success',
                'data' => $product->load(['category', 'variants.size', 'variants.color'])
            ], 200);
        });
    }

    public function destroy(Product $product)
    {
        // Xóa ảnh của product
        if ($product->thumb_image) {
            Storage::disk('public')->delete($product->thumb_image);
        }

        // Xóa ảnh của variants
        foreach ($product->variants as $variant) {
            if ($variant->image) {
                Storage::disk('public')->delete($variant->image);
            }
        }

        $product->delete();
        return response()->json([
            'status' => 'success',
            'message' => 'Product deleted successfully'
        ], 200);
    }
}
