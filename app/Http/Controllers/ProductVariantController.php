<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Size;
use App\Models\Color;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductVariantController extends Controller
{
    /**
     * Display variants for a specific product
     */
    public function index(Product $product)
    {
        $variants = $product->variants()->with(['size', 'color'])->paginate(10);
        return view('product-variants.index', compact('product', 'variants'));
    }

    /**
     * Show the form for creating a new variant
     */
    public function create(Product $product)
    {
        $sizes = Size::all();
        $colors = Color::all();

        // Lấy các combinations đã tồn tại để không hiển thị
        $existingCombinations = $product->variants()
            ->get(['size_id', 'color_id'])
            ->map(function($variant) {
                return $variant->size_id . '-' . $variant->color_id;
            })->toArray();

        return view('product-variants.create', compact('product', 'sizes', 'colors', 'existingCombinations'));
    }

    /**
     * Store a newly created variant
     */
    public function store(Request $request, Product $product)
    {
        $request->validate([
            'size_id' => 'required|exists:sizes,id',
            'color_id' => 'required|exists:colors,id',
            'quantity' => 'required|integer|min:0',
            'price' => 'required|numeric|min:0',
            'price_sale' => 'nullable|numeric|min:0|lt:price',
        ]);

        // Kiểm tra combination đã tồn tại chưa
        $existingVariant = ProductVariant::where([
            'product_id' => $product->id,
            'size_id' => $request->size_id,
            'color_id' => $request->color_id,
        ])->first();

        if ($existingVariant) {
            return back()->with('error', 'Biến thể với size và màu này đã tồn tại!');
        }

        ProductVariant::create([
            'product_id' => $product->id,
            'size_id' => $request->size_id,
            'color_id' => $request->color_id,
            'quantity' => $request->quantity,
            'price' => $request->price,
            'price_sale' => $request->price_sale,
        ]);

        return redirect()->route('product-variants.index', $product)
                        ->with('success', 'Biến thể đã được tạo thành công!');
    }

    /**
     * Show the form for editing a variant
     */
    public function edit(Product $product, ProductVariant $variant)
    {
        $sizes = Size::all();
        $colors = Color::all();

        return view('product-variants.edit', compact('product', 'variant', 'sizes', 'colors'));
    }

    /**
     * Update the specified variant
     */
    public function update(Request $request, Product $product, ProductVariant $variant)
    {
        $request->validate([
            'size_id' => 'required|exists:sizes,id',
            'color_id' => 'required|exists:colors,id',
            'quantity' => 'required|integer|min:0',
            'price' => 'required|numeric|min:0',
            'price_sale' => 'nullable|numeric|min:0|lt:price',
        ]);

        // Kiểm tra combination mới có trung với variant khác không
        $existingVariant = ProductVariant::where([
            'product_id' => $product->id,
            'size_id' => $request->size_id,
            'color_id' => $request->color_id,
        ])->where('id', '!=', $variant->id)->first();

        if ($existingVariant) {
            return back()->with('error', 'Biến thể với size và màu này đã tồn tại!');
        }

        $variant->update([
            'size_id' => $request->size_id,
            'color_id' => $request->color_id,
            'quantity' => $request->quantity,
            'price' => $request->price,
            'price_sale' => $request->price_sale,
        ]);

        return redirect()->route('product-variants.index', $product)
                        ->with('success', 'Biến thể đã được cập nhật thành công!');
    }

    /**
     * Remove the specified variant
     */
    public function destroy(Product $product, ProductVariant $variant)
    {
        // Kiểm tra xem variant có trong đơn hàng đang xử lý không
        $hasActiveOrders = DB::table('order_details')
            ->join('orders', 'order_details.order_id', '=', 'orders.id')
            ->where('order_details.product_variant_id', $variant->id)
            ->whereIn('orders.status', [0, 1, 2])
            ->exists();

        if ($hasActiveOrders) {
            return back()->with('error', 'Không thể xóa biến thể này vì có trong đơn hàng đang xử lý!');
        }

        // Cập nhật các order_details liên quan
        DB::table('order_details')
            ->where('product_variant_id', $variant->id)
            ->update([
                'product_variant_id' => null,
                'is_deleted' => true,
            ]);

        // Cập nhật cart_items liên quan
        DB::table('cart_items')
            ->where('product_variant_id', $variant->id)
            ->delete();

        $variant->delete();

        return redirect()->route('product-variants.index', $product)
                        ->with('success', 'Biến thể đã được xóa thành công!');
    }

    /**
     * Bulk create variants for all size/color combinations
     */
    public function bulkCreate(Request $request, Product $product)
    {
        $request->validate([
            'price' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:0',
            'price_sale' => 'nullable|numeric|min:0|lt:price',
        ]);

        $sizes = Size::all();
        $colors = Color::all();
        $created = 0;

        foreach ($sizes as $size) {
            foreach ($colors as $color) {
                // Kiểm tra combination đã tồn tại chưa
                $exists = ProductVariant::where([
                    'product_id' => $product->id,
                    'size_id' => $size->id,
                    'color_id' => $color->id,
                ])->exists();

                if (!$exists) {
                    ProductVariant::create([
                        'product_id' => $product->id,
                        'size_id' => $size->id,
                        'color_id' => $color->id,
                        'quantity' => $request->quantity,
                        'price' => $request->price,
                        'price_sale' => $request->price_sale,
                    ]);
                    $created++;
                }
            }
        }

        return redirect()->route('product-variants.index', $product)
                        ->with('success', "Đã tạo {$created} biến thể mới!");
    }
}
