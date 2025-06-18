<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Traits\ApiResponse;
use App\Traits\LoadRelations;
use Illuminate\Http\Request;
use App\Http\Requests\API\StoreCategoryRequest;

class CategoryController extends Controller
{
    use ApiResponse, LoadRelations;

    protected $validRelations = [
        'products',
        'products.tags',
        'products.galleries',
        'products.variants',
    ];

    // Hiển thị danh sách danh mục sản phẩm
    public function index(Request $request)
    {
        $categories = Category::query()->withCount('products');
        $this->loadRelations($categories, $request);

        $perPage = $request->query('per_page', 10);

        return $this->ok('Lấy danh sách danh mục thành công', $categories->paginate($perPage));
    }

    public function show(Request $request, string $slug)
    {
        $category = Category::whereSlug($slug)->first();

        if (!$category) return $this->not_found("Danh mục không tồn tại");

        $this->loadRelations($category, $request, true);

        return $this->ok("Lấy thông tin danh mục thành công", $category);
    }

    // Hiển thị danh sách sản phẩm thuộc danh mục theo slug
    public function products(string $slug)
    {
        $category = Category::where('slug', $slug)->first();

        if (!$category) {
            return $this->not_found("Danh mục không tồn tại");
        }

        $products = $category->products()->with([
            'variants',
        ])->get();

        if ($products->isEmpty()) {
            return $this->ok("Danh mục này chưa có sản phẩm", []);
        }

        return $this->ok("Danh sách sản phẩm thuộc danh mục", $products);
    }

    // Tạo mới danh mục sản phẩm
    public function store(StoreCategoryRequest $request)
    {
        $data = $request->validated();

        $category = Category::create($data);

        return $this->created("Tạo danh mục thành công", $category);
    }

    // Cập nhật danh mục sản phẩm
    public function update(Request $request, string $slug)
    {
        $category = Category::whereSlug($slug)->first();

        if (!$category) return $this->not_found("Danh mục không tồn tại");

        $data = $request->validate([
            'name'        => 'sometimes|string|max:255',
            'slug'        => 'nullable|string|max:190|unique:categories,slug,' . $category->id,
            'description' => 'nullable|string',
            'status'      => 'boolean',
            'is_active'   => 'boolean',
        ]);

        $category->update($data);

        return $this->ok("Cập nhật danh mục thành công", $category);
    }

    // Xóa danh mục sản phẩm
    public function destroy(string $slug)
    {
        $category = Category::whereSlug($slug)->first();

        if (!$category) return $this->not_found("Danh mục không tồn tại");

        $category->delete();

        return $this->no_content();
    }


}
