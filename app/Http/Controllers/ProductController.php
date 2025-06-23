<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductSize;
use App\Models\ProductColor;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        // Khởi tạo query với relations
        $query = Product::with(['category', 'variants.size', 'variants.color']);

        // Lọc theo trạng thái
        if ($request->filled('is_active')) {
            $query->where('is_active', $request->is_active);
        }

        // Lọc theo khoảng giá
        if ($request->filled('price_range')) {
            if ($request->price_range == 'under_200k') {
                $query->where('price_regular', '<', 200000);
            } elseif ($request->price_range == '200k_500k') {
                $query->whereBetween('price_regular', [200000, 500000]);
            } elseif ($request->price_range == 'over_500k') {
                $query->where('price_regular', '>', 500000);
            }
        }

        // Sắp xếp theo giá
        if ($request->filled('price_order')) {
            $query->orderBy('price_regular', $request->price_order);
        }

        // Xử lý toggle trạng thái
        if ($request->has('toggle_is_active')) {
            $product = Product::findOrFail($request->input('product_id'));
            $product->is_active = !$product->is_active;
            $product->save();

            return redirect()->route('products.index')
                ->with('success', 'Trạng thái sản phẩm đã được cập nhật.');
        }

        $products = $query->latest()->paginate(10);
        $categories = Category::where('is_active', true)->get();

        return view('products.index', compact('products', 'categories'));
    }

    public function create()
    {
        $categories = Category::where('is_active', true)->get();
        $sizes = ProductSize::all();
        $colors = ProductColor::all();
        return view('products.create', compact('categories', 'sizes', 'colors'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'thumb_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'price_regular' => 'required|numeric|min:0',
            'price_sale' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'content' => 'nullable|string',
            'category_id' => 'required|exists:categories,id',
            'is_active' => 'boolean',
            'variants' => 'required|array',
            'variants.*.product_size_id' => 'required|exists:product_sizes,id',
            'variants.*.product_color_id' => 'required|exists:product_colors,id',
            'variants.*.quantity' => 'required|integer|min:0',
            'variants.*.image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        try {
            DB::beginTransaction();

            // Upload ảnh đại diện
            $thumbPath = null;
            if ($request->hasFile('thumb_image')) {
                $thumbPath = $request->file('thumb_image')->store('products/thumbnails', 'public');
            }

            // Tạo sản phẩm
            $product = Product::create([
                'category_id' => $request->category_id,
                'name' => $request->name,
                'slug' => Str::slug($request->name),
                'sku' => $this->generateSku($request->name),
                'thumb_image' => $thumbPath,
                'price_regular' => $request->price_regular,
                'price_sale' => $request->price_sale,
                'description' => $request->description,
                'content' => $request->content,
                'views' => 0,
                'is_active' => $request->boolean('is_active', true)
            ]);

            // Tạo các biến thể
            foreach ($request->variants as $variant) {
                $variantData = [
                    'product_id' => $product->id,
                    'product_size_id' => $variant['product_size_id'],
                    'product_color_id' => $variant['product_color_id'],
                    'quantity' => $variant['quantity'],
                ];

                // Upload ảnh biến thể nếu có
                if (isset($variant['image']) && $variant['image']) {
                    $variantData['image'] = $variant['image']->store('products/variants', 'public');
                }

                ProductVariant::create($variantData);
            }

            DB::commit();
            return redirect()->route('products.index')
                ->with('success', 'Thêm mới sản phẩm thành công');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Lỗi tạo sản phẩm: ' . $e->getMessage());
            return back()->with('error', 'Có lỗi xảy ra, vui lòng thử lại sau.')
                ->withInput();
        }
    }

    public function edit(Product $product)
    {
        $product->load(['variants.size', 'variants.color']);
        $categories = Category::where('is_active', true)->get();
        $sizes = ProductSize::all();
        $colors = ProductColor::all();

        return view('products.edit', compact('product', 'categories', 'sizes', 'colors'));
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'thumb_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'price_regular' => 'required|numeric|min:0',
            'price_sale' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'content' => 'nullable|string',
            'category_id' => 'required|exists:categories,id',
            'is_active' => 'boolean',
            'variants' => 'required|array',
            'variants.*.id' => 'nullable|exists:product_variants,id',
            'variants.*.product_size_id' => 'required|exists:product_sizes,id',
            'variants.*.product_color_id' => 'required|exists:product_colors,id',
            'variants.*.quantity' => 'required|integer|min:0',
            'variants.*.image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        try {
            DB::beginTransaction();

            // Xử lý ảnh đại diện
            if ($request->hasFile('thumb_image')) {
                if ($product->thumb_image) {
                    Storage::disk('public')->delete($product->thumb_image);
                }
                $thumbPath = $request->file('thumb_image')->store('products/thumbnails', 'public');
                $product->thumb_image = $thumbPath;
            }

            // Cập nhật thông tin sản phẩm
            $product->update([
                'category_id' => $request->category_id,
                'name' => $request->name,
                'slug' => Str::slug($request->name),
                'price_regular' => $request->price_regular,
                'price_sale' => $request->price_sale,
                'description' => $request->description,
                'content' => $request->content,
                'is_active' => $request->boolean('is_active', true),
            ]);

            // Cập nhật biến thể
            $currentVariantIds = $product->variants->pluck('id')->toArray();
            $newVariantIds = [];

            foreach ($request->variants as $variantData) {
                if (isset($variantData['id'])) {
                    // Cập nhật biến thể hiện có
                    $variant = ProductVariant::find($variantData['id']);
                    if ($variant) {
                        $variant->update([
                            'product_size_id' => $variantData['product_size_id'],
                            'product_color_id' => $variantData['product_color_id'],
                            'quantity' => $variantData['quantity'],
                        ]);

                        // Xử lý ảnh biến thể
                        if (isset($variantData['image']) && $variantData['image']) {
                            if ($variant->image) {
                                Storage::disk('public')->delete($variant->image);
                            }
                            $variant->image = $variantData['image']->store('products/variants', 'public');
                            $variant->save();
                        }

                        $newVariantIds[] = $variant->id;
                    }
                } else {
                    // Tạo biến thể mới
                    $newVariant = ProductVariant::create([
                        'product_id' => $product->id,
                        'product_size_id' => $variantData['product_size_id'],
                        'product_color_id' => $variantData['product_color_id'],
                        'quantity' => $variantData['quantity'],
                    ]);

                    if (isset($variantData['image']) && $variantData['image']) {
                        $newVariant->image = $variantData['image']->store('products/variants', 'public');
                        $newVariant->save();
                    }

                    $newVariantIds[] = $newVariant->id;
                }
            }

            // Xóa các biến thể không còn sử dụng
            $variantsToDelete = array_diff($currentVariantIds, $newVariantIds);
            foreach ($variantsToDelete as $variantId) {
                $variant = ProductVariant::find($variantId);
                if ($variant) {
                    if ($variant->image) {
                        Storage::disk('public')->delete($variant->image);
                    }
                    $variant->delete();
                }
            }

            DB::commit();
            return redirect()->route('products.index')
                ->with('success', 'Sản phẩm được cập nhật thành công');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Lỗi cập nhật sản phẩm: ' . $e->getMessage());
            return back()->with('error', 'Đã có lỗi xảy ra, vui lòng thử lại.')
                ->withInput();
        }
    }

    public function destroy(Product $product)
    {
        try {
            DB::beginTransaction();

            // Kiểm tra đơn hàng đang xử lý
            $hasActiveOrders = DB::table('order_items')
                ->join('orders', 'order_items.order_id', '=', 'orders.id')
                ->whereIn('orders.status_order', ['pending', 'processing', 'shipping'])
                ->where('order_items.product_variant_id', function($query) use ($product) {
                    $query->select('id')
                        ->from('product_variants')
                        ->where('product_id', $product->id);
                })
                ->exists();

            if ($hasActiveOrders) {
                return back()->with('error', 'Không thể xóa sản phẩm này vì có trong đơn hàng đang xử lý.');
            }

            // Xóa ảnh sản phẩm
            if ($product->thumb_image) {
                Storage::disk('public')->delete($product->thumb_image);
            }

            // Xóa ảnh và dữ liệu biến thể
            foreach ($product->variants as $variant) {
                if ($variant->image) {
                    Storage::disk('public')->delete($variant->image);
                }
                $variant->delete();
            }

            // Xóa sản phẩm
            $product->delete();

            DB::commit();
            return back()->with('success', 'Thao tác thành công');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Lỗi xóa sản phẩm: ' . $e->getMessage());
            return back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    /**
     * Tạo mã SKU cho sản phẩm
     */
    private function generateSku($name)
    {
        $prefix = strtoupper(substr(Str::slug($name), 0, 3));
        $timestamp = now()->format('ymd');
        $random = strtoupper(Str::random(3));
        return $prefix . $timestamp . $random;
    }
}
