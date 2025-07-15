<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Gallery;
use App\Models\Size;
use App\Models\Color;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Throwable;

class ProductController extends Controller
{
     public function index(Request $request)
    {
        // Initialize query with relations
        $query = Product::with(['galleries', 'categories', 'colors']);

        // Filter by is_active
        if ($request->filled('is_active')) {
            $query->where('is_active', $request->is_active);
        }

        // Filter by price range
        if ($request->filled('price_range')) {
            if ($request->price_range == 'under_200k') {
                $query->where('price', '<', 200000);
            } elseif ($request->price_range == '200k_500k') {
                $query->whereBetween('price', [200000, 500000]);
            } elseif ($request->price_range == 'over_500k') {
                $query->where('price', '>', 500000);
            }
        }

        // Sort by price order
        if ($request->filled('price_order')) {
            $query->orderBy('price', $request->price_order);
        }

        // Check if there's an 'is_active' action
        if($request->has('toggle_is_active')) {
            $product = Product::findOrFail($request->id);
            $product->is_active = !$product->is_active;
            $product->save();

            // Return a response indicating success
            return redirect()->route('products.index')->with('success', 'Trạng thái sản phẩm đã được cập nhật.');
        }

        // Get paginated results
        $products = $query->latest()->paginate(5);

        // Define color mapping
        $colorMap = [
            'Đỏ' => '#FF0000',
            'Đen' => '#000000',
            'Xanh dương' => '#0000FF',
            'Xanh lá' => '#00FF00',
            'Vàng' => '#FFFF00',
            'Cam' => '#FFA500',
            'Tím' => '#800080',
        ];

        return view('products.index', compact('products', 'colorMap'));
    }

    public function create()
    {
        $categories = Category::all();
        $sizes = Size::all();
        $colors = Color::all();

        return view('products.create', compact('categories', 'sizes', 'colors'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'avatar' => 'nullable|image',
            'import_price' => 'required|numeric|min:0',
            'price' => 'required|numeric|min:0',
            'quantity' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'category_id' => 'required|exists:categories,id',
            'is_active' => 'required|boolean', // Đảm bảo is_active là boolean
            'sizes' => 'array',
            'sizes.*' => 'exists:sizes,id',
            'colors' => 'array',
            'colors.*' => 'exists:colors,id',
            'image_path' => 'required|array',
            'image_path.*' => 'nullable|image',
        ]);

        try {
            // Kiểm tra nếu không có sizes hoặc colors
            if (!$request->has('sizes') || count($request->sizes) == 0) {
                return back()->with('error', 'Thiếu biến thể kích thước (size).');
            }

            if (!$request->has('colors') || count($request->colors) == 0) {
                return back()->with('error', 'Thiếu biến thể màu sắc (color).');
            }

            $avatarPath = null;
            if ($request->hasFile('avatar')) {
                $avatarPath = $request->file('avatar')->store('ProductAvatars', 'public');
            }

            $productData = $request->all();
            $productData['avatar'] = $avatarPath;
            $productData['status'] = $request->has('is_active') && $request->is_active == 1 ? 1 : 0; // Chuyển thành status 1 hoặc 0

            $product = Product::create($productData);

            if ($request->has('sizes')) {
                $product->sizes()->attach($request->sizes);
            }

            if ($request->has('colors')) {
                $product->colors()->attach($request->colors);
            }

            if ($request->hasFile('image_path')) {
                foreach ($request->file('image_path') as $image) {
                    $imagePath = $image->store('ProductGalleries', 'public');
                    Gallery::create(['product_id' => $product->id, 'image_path' => $imagePath]);
                }
            }

            return redirect()->route('products.index')->with('success', 'Sản phẩm đã được tạo thành công.');
        } catch (\Exception $e) {
            return back()->with('error', 'Có lỗi vui lòng thử lại sau ');
        }
    }

    public function edit(Product $product)
    {
        $categories = Category::all();
        $sizes = Size::all();
        $colors = Color::all();
        $product->load(['sizes', 'colors', 'galleries']);

        return view('products.editproduct', compact('product', 'categories', 'sizes', 'colors'));
    }

    public function update(Request $request, Product $product)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'import_price' => 'required|numeric|min:0',
                'price' => 'required|numeric|min:0',
                'quantity' => 'required|numeric|min:0',
                'description' => 'nullable|string',
                'category_id' => 'required|exists:categories,id',
                'sizes' => 'array|nullable',
                'colors' => 'array|nullable',
                'is_active' => 'boolean',
                'avatar' => 'nullable|image',
                'images.*' => 'nullable|image',
                'delete_gallery' => 'array|nullable',
            ]);

            // Xử lý ảnh đại diện
            if ($request->hasFile('avatar')) {
                // Xóa ảnh cũ nếu có
                if ($product->avatar) {
                    Storage::disk('public')->delete($product->avatar);
                }
                // Lưu ảnh mới
                $avatarPath = $request->file('avatar')->store('ProductAvatars', 'public');
                $product->update(['avatar' => $avatarPath]);
            }

            // Cập nhật các thông tin sản phẩm khác, bao gồm `is_active`
            $product->update($request->except(['avatar', 'images', 'delete_gallery']));

            // Đồng bộ kích thước và màu sắc
            $product->sizes()->sync($request->sizes);
            $product->colors()->sync($request->colors);

            // Xử lý hình ảnh trong thư viện
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    $imagePath = $image->store('ProductImages', 'public');
                    $product->galleries()->create(['image_path' => $imagePath]);
                }
            }

            // Xóa hình ảnh được chọn
            if ($request->has('delete_gallery')) {
                $product->galleries()->whereIn('id', $request->delete_gallery)->delete();
            }

            return redirect()->route('products.index')->with('success', 'Sản phẩm được cập nhật thành công');
        } catch (\Exception $e) {
            return back()->with('error', 'Đã có lỗi xảy ra, vui lòng thử lại.');
        }
    }

    public function destroy(Product $product)
    {
        // Kiểm tra nếu có đơn hàng nào chứa sản phẩm này và có trạng thái là 0, 1, hoặc 2
        $orderDetails = DB::table('order_details')
            ->join('orders', 'order_details.order_id', '=', 'orders.id')
            ->where('order_details.product_id', $product->id)
            ->whereIn('orders.status', [0, 1, 2]) // Kiểm tra trạng thái đơn hàng
            ->exists();

        if ($orderDetails) {
            return back()->with('error', 'Không thể xóa sản phẩm này vì có trong đơn hàng đang chờ xử lý, đang chuẩn bị hoặc đang vận chuyển.');
        }

        // Cập nhật các bản ghi liên quan trong bảng reviews
        DB::table('reviews')
            ->where('product_id', $product->id)
            ->update([
                'product_id' => null,  // Đặt product_id thành null
                'image_path' => null,  // Xóa đường dẫn ảnh nếu cần
                'comment' => null,     // Xóa nội dung đánh giá nếu cần
                'is_reviews' => 0,     // Đặt trạng thái là không còn đánh giá
            ]);

        // Cập nhật các bản ghi trong order_details về null
        DB::table('order_details')
            ->where('product_id', $product->id)
            ->update([
                'product_id' => null,  // Xóa thông tin sản phẩm
                'price' => null,       // Xóa giá sản phẩm
                'quantity' => null,    // Xóa số lượng
                'total' => null,       // Xóa tổng tiền
                'size_id' => null,     // Xóa size
                'color_id' => null,    // Xóa màu sắc
                'is_deleted' => true,  // Đánh dấu là đã xóa
            ]);

        // Tiến hành xóa sản phẩm
        try {
            // Xóa avatar
            Storage::disk('public')->delete($product->avatar);

           // Xóa gallery
            foreach ($product->galleries as $gallery) {
                Storage::disk('public')->delete($gallery->image_path);
                $gallery->delete();
            }
            
             // Xóa sản phẩm
            $product->delete();

            return back()->with('success', 'Thao tác thành công');
        } catch (\Exception $e) {
            return back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }
}


