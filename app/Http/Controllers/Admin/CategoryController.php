<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::latest('id')->paginate(5);

        if ($categories->currentPage() > $categories->lastPage()) {
            return redirect()->route('admin.categories.index', ['page' => $categories->lastPage()]);
        }

        return view('categories.index', compact('categories'));

    }

    public function create()
    {
        return view('categories.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:categories|max:255',
            'slug' => 'nullable|max:190|unique:categories,slug',
            'description' => 'nullable|string',
            'status' => 'boolean',
            'is_active' => 'required|boolean',
        ]);

        Category::create([
            'name' => $request->name,
            'slug' => $request->slug,
            'description' => $request->description,
            'status' => $request->status,
            'is_active' => 11,
        ]);

        return redirect()->route('categories.index')->with('success', 'Thêm mới thành công');
    }

    public function show(Category $category)
    {
        return view('categories.show', compact('category'));
    }

    public function edit(Category $category)
    {
        return view('categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        try {
            // Kiểm tra và validate input
            $request->validate([
                'name' => 'required|unique:categories,name,' . $category->id . '|max:255',
                'slug' => 'nullable|max:190|unique:categories,slug,' . $category->id,
                'description' => 'nullable|string',
                'status' => 'boolean',
                'is_active' => 'required|boolean',
            ]);

            // Cập nhật danh mục
            $category->update([
                'name' => $request->name,
                'slug' => $request->slug,
                'description' => $request->description,
                'status' => $request->status,
                'is_active' => $request->is_active,
            ]);

            return back()->with('success', 'Cập nhật thành công');
        } catch (\Exception $e) {
            // Nếu có lỗi, trả về thông báo lỗi
            return back()->with('error', 'Có lỗi xảy ra khi cập nhật danh mục: ');
        }
    }

    public function destroy(Category $category)
    {
        if ($category->products()->count() > 0) {
            return back()->with('error', 'Không thể xóa danh mục vì có sản phẩm liên quan.');
        }

        if ($category->blogs()->count() > 0) {
            return back()->with('error', 'Không thể xóa danh mục vì có bài viết liên quan.');
        }

        $category->delete();

        return back()->with('success', 'Xóa danh mục thành công');
    }
}
