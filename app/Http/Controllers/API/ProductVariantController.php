<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\ProductColor;
use App\Models\ProductSize;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class ProductVariantController extends Controller
{
    public function index(Product $product)
    {
        $variants = $product->variants()
            ->with(['size', 'color'])
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => $variants
        ]);
    }

    public function store(Request $request, Product $product)
    {
        $request->validate([
            'product_size_id' => 'required|exists:product_sizes,id',
            'product_color_id' => 'required|exists:product_colors,id',
            'quantity' => 'required|integer|min:0',
            'image' => 'nullable|image|max:2048'
        ]);

        return DB::transaction(function () use ($request, $product) {
            // Kiểm tra xem biến thể đã tồn tại chưa
            $existingVariant = $product->variants()
                ->where('product_size_id', $request->product_size_id)
                ->where('product_color_id', $request->product_color_id)
                ->first();

            if ($existingVariant) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Variant already exists'
                ], 422);
            }

            $variant = new ProductVariant([
                'product_size_id' => $request->product_size_id,
                'product_color_id' => $request->product_color_id,
                'quantity' => $request->quantity
            ]);

            if ($request->hasFile('image')) {
                $variant->image = $request->file('image')->store('variants', 'public');
            }

            $product->variants()->save($variant);
            $variant->load(['size', 'color']);

            return response()->json([
                'status' => 'success',
                'message' => 'Variant created successfully',
                'data' => $variant
            ], 201);


        });
    }

    public function update(Request $request, Product $product, ProductVariant $variant)
    {
        if ($variant->product_id !== $product->id) {
            return response()->json([
                'status' => 'error',
                'message' => 'This variant does not belong to the specified product'
            ], 403);
        }

        $request->validate([
            'product_size_id' => 'exists:product_sizes,id',
            'product_color_id' => 'exists:product_colors,id',
            'quantity' => 'integer|min:0',
            'image' => 'nullable|image|max:2048'
        ]);

        return DB::transaction(function () use ($request, $product, $variant) {
            $data = $request->only(['product_size_id', 'product_color_id', 'quantity']);

            if ($request->hasFile('image')) {
                if ($variant->image) {
                    Storage::disk('public')->delete($variant->image);
                }
                $data['image'] = $request->file('image')->store('variants', 'public');
            }

            // Kiểm tra trùng lặp nếu thay đổi size hoặc color
            if (isset($data['product_size_id']) || isset($data['product_color_id'])) {
                $exists = $product->variants()
                    ->where('id', '!=', $variant->id)
                    ->where('product_size_id', $data['product_size_id'] ?? $variant->product_size_id)
                    ->where('product_color_id', $data['product_color_id'] ?? $variant->product_color_id)
                    ->exists();

                if ($exists) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'A variant with these attributes already exists'
                    ], 422);
                }
            }

            $variant->update($data);
            $variant->refresh();
            $variant->load(['size', 'color']);

            return response()->json([
                'status' => 'success',
                'message' => 'Variant updated successfully',
                'data' => $variant
            ]);
        });
    }

    public function destroy(Product $product, ProductVariant $variant)
    {
        if ($variant->product_id !== $product->id) {
            return response()->json([
                'status' => 'error',
                'message' => 'This variant does not belong to the specified product'
            ], 403);
        }

        return DB::transaction(function () use ($variant) {
            if ($variant->image) {
                Storage::disk('public')->delete($variant->image);
            }

            $variant->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Variant deleted successfully'
            ]);
        });
    }

    public function colors()
    {
        $colors = ProductColor::all();
        return response()->json([
            'status' => 'success',
            'data' => $colors
        ]);
    }

    public function sizes()
    {
        $sizes = ProductSize::all();
        return response()->json([
            'status' => 'success',
            'data' => $sizes
        ]);
    }

    public function show(Product $product, ProductVariant $variant)
    {
        if ($variant->product_id !== $product->id) {
            return response()->json([
                'status' => 'error',
                'message' => 'This variant does not belong to the specified product'
            ], 403);
        }
        $variant->load(['size', 'color']);
        return response()->json([
            'status' => 'success',
            'data' => $variant
        ]);
    }
}
