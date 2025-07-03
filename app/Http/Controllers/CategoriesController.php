<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoriesController extends Controller
{
    // Hiển thị danh sách danh mục
    public function index(Request $request)
    {
        $categories = Category::latest('id')->paginate(5);
        if ($categories->currentPage() > $categories->lastPage()) {
            return redirect()->route('categories.index', ['page' => $categories->lastPage()]);
        }
        return view('categories.index', compact('categories'));
    }

    // Hiển thị form tạo mới danh mục
    public function create()
    {
        return view('categories.create');
    }

    // Lưu danh mục mới
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:categories|max:255',
            'slug' => 'required|max:190|unique:categories,slug',
            'description' => 'required|string',
            'status' => 'boolean',
            'is_active' => 'boolean',
        ]);
        Category::create([
            'name' => $request->name,
            'slug' => $request->slug,
            'description' => $request->description,
            'status' => $request->status,
            'is_active' => $request->is_active ?? 1,
        ]);
        return redirect()->route('categories.index')->with('success', 'Thêm mới thành công');
    }

    // Hiển thị chi tiết một danh mục
    public function show(Category $category)
    {
        return view('categories.show', compact('category'));
    }

    // Hiển thị form chỉnh sửa danh mục
    public function edit(Category $category)
    {
        return view('categories.edit', compact('category'));
    }

    // Cập nhật danh mục
    public function update(Request $request, Category $category)
    {
        try {
            $request->validate([
                'name' => 'required|unique:categories,name,' . $category->id . '|max:255',
                'slug' => 'required|max:190|unique:categories,slug,' . $category->id,
                'description' => 'required|string',
                'status' => 'boolean',
                'is_active' => 'boolean',
            ]);
            $category->update([
                'name' => $request->name,
                'slug' => $request->slug,
                'description' => $request->description,
                'status' => $request->status,
                'is_active' => $request->is_active,
            ]);
            return redirect()->route('categories.index')->with('success', 'Cập nhật thành công');
        } catch (\Exception $e) {
            return back()->with('error', 'Có lỗi xảy ra khi cập nhật danh mục: ');
        }
    }

    // Xóa danh mục
    public function destroy(Category $category)
    {
        if ($category->products()->exists()) {
            return back()->with('error', 'Không thể xóa danh mục này vì có sản phẩm liên quan.');
        }
        $category->delete();
        return back()->with('success', 'Xóa danh mục thành công');
    }

    // Đổi trạng thái ẩn/hiện
    public function toggleStatus(Category $category)
    {
        $category->is_active = !$category->is_active;
        $category->save();
        return redirect()->route('categories.index')->with('success', 'Đã cập nhật trạng thái danh mục!');
    }
}