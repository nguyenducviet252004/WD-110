<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BlogController extends Controller
{
    public function index()
    {
        $blogs = Blog::with('category')->get();
        return view('blog.index', compact('blogs'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('blog.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:1024',
            'content' => 'required|string',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:8192',
            'is_active' => 'required|boolean',
        ]);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('blogs', 'public');
        }

        Blog::create($data);
        return redirect()->route('blog.index')->with('success', 'Thêm mới bài viết thành công ');
    }

    public function edit($id)
    {
        // Lấy bài viết theo ID
        $blog = Blog::findOrFail($id);
        $categories = Category::all(); // Lấy tất cả danh mục
        return view('blog.edit', compact('blog', 'categories'));
    }

    public function update(Request $request, $id)
    {
        try {
            // Validate dữ liệu
            $data = $request->validate([
                'category_id' => 'required|exists:categories,id',
                'title' => 'required|string|max:255',
                'description' => 'required|string|max:1024',
                'content' => 'required|string',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:8192',
                'is_active' => 'required|boolean',
            ]);

            // Lấy bài viết cần cập nhật
            $blog = Blog::findOrFail($id);

            // Kiểm tra nếu có file ảnh mới thì lưu ảnh
            if ($request->hasFile('image')) {
                // Xóa ảnh cũ nếu có
                if ($blog->image) {
                    Storage::disk('public')->delete($blog->image);
                }
                $data['image'] = $request->file('image')->store('blogs', 'public');
            }

            // Cập nhật bài viết
            $blog->update($data);

            // Quay lại trang danh sách với thông báo thành công
            return redirect()->route('blog.index')->with('success', 'Cập nhật thành công ');
        } catch (\Exception $e) {
            // Nếu có lỗi, trả về thông báo lỗi
            return back()->with('error', 'Có lỗi xảy ra khi cập nhật bài viết: ');
        }
    }



    public function destroy(Blog $blog)
    {
        $blog->delete();
        return redirect()->route('blog.index')->with('success', 'Blog deleted successfully.');
    }
}
