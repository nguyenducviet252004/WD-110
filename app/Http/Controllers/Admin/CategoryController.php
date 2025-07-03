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

        return view('admin.categories.index', compact('categories'));

    }

    public function create()
    {
        return view('admin.categories.create');
    }

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
            'is_active' => 1,
        ]);

        return redirect()->route('admin.categories.index')->with('success', 'Thêm mới thành công');
    }

    public function show(Category $category)
    {
        return view('admin.categories.show', compact('category'));
    }

    public function edit(Category $category)
    {
        return view('admin.categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        try {
            // Kiểm tra và validate input
            $request->validate([
                'name' => 'required|unique:categories,name,' . $category->id . '|max:255',
                'slug' => 'required|max:190|unique:categories,slug,' . $category->id,
                'description' => 'required|string',
                'status' => 'boolean',
                'is_active' => 'boolean',
            ]);

            // Cập nhật danh mục
            $category->update([
                'name' => $request->name,
                'slug' => $request->slug,
                'description' => $request->description,
                'status' => $request->status,
                'is_active' => $request->is_active,
            ]);

            return redirect()->route('admin.categories.index')->with('success', 'Cập nhật thành công');
        } catch (\Exception $e) {
            // Nếu có lỗi, trả về thông báo lỗi
            return back()->with('error', 'Có lỗi xảy ra khi cập nhật danh mục: ');
        }
    }

    public function destroy(Category $category)
    {


        $category->delete();

        return back()->with('success', 'Xóa danh mục thành công');
    }
}
