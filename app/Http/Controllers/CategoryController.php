<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Ship_address;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Kiểm tra nếu có yêu cầu cập nhật trạng thái is_active
        if ($request->has('toggle_active')) {
            $category = Category::findOrFail($request->toggle_active);
            $category->is_active = !$category->is_active; // Đổi trạng thái
            $category->save();
            return back()->with('success', 'Trạng thái danh mục đã được cập nhật.');
        }

        // Lấy danh sách danh mục
        $categories = Category::latest('id')->paginate(5);
        return view('categories.index', compact('categories'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('categories.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {


        $request->validate([
            'name' => 'required|unique:categories|max:255',
            'is_active' => 'required|boolean',
        ]);

        Category::create([
            'name' => $request->name,
            'is_active' => 1,
        ]);

        return redirect()->route('categories.index')->with('success', 'Thêm mới thành công');
    }


    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        return view('categories.show', compact('category'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        return view('categories.edit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category)
    {
        try {
            // Kiểm tra và validate input
            $request->validate([
                'name' => 'required|unique:categories,name,' . $category->id . '|max:255',
                'is_active' => 'required|boolean',
            ]);

            // Kiểm tra nếu địa chỉ được chọn là mặc định
            if ($request->is_default) {
                // Cập nhật tất cả các địa chỉ của người dùng thành không mặc định
                Ship_address::where('user_id', auth()->id()) // Assuming 'user_id' is the column linking the user
                    ->update(['is_default' => false]);
            }

            // Cập nhật danh mục
            $category->update([
                'name' => $request->name,
                'is_active' => $request->is_active,
            ]);

            return back()->with('success', 'Cập nhật thành công');
        } catch (\Exception $e) {
            // Nếu có lỗi, trả về thông báo lỗi
            return back()->with('error', 'Có lỗi xảy ra khi cập nhật danh mục: ');
        }
    }




    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        // Kiểm tra xem danh mục có sản phẩm nào không
        if ($category->products()->count() > 0) {
            return back()->with('error', 'Không thể xóa danh mục này vì có sản phẩm liên quan.');
        }

        // Kiểm tra xem danh mục có bài blog nào liên quan không
        if ($category->blogs()->count() > 0) {
            return back()->with('error', 'Không thể xóa danh mục này vì có bài viết liên quan.');
        }

        // Nếu không có sản phẩm hoặc bài blog liên quan, thực hiện xóa
        $category->delete();
        return back()->with('success', 'Xóa thành công');
    }
}
