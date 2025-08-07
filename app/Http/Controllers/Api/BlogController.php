<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    public function index()
    {
        try {
            // Lấy tất cả blog và chuyển đổi hình ảnh sang URL đầy đủ
            $blogs = Blog::all()->map(function ($blog) {
                return [
                    'id' => $blog->id,
                    'category_id' => $blog->category_id,
                    'title' => $blog->title,
                    'description' => $blog->description,
                    'content' => $blog->content,
                    'image' => $blog->image ? asset('storage/' . $blog->image) : null, // Đường dẫn đầy đủ
                    'is_active' => $blog->is_active,
                    'created_at' => $blog->created_at,
                    'updated_at' => $blog->updated_at,
                ];
            });
            // Kiểm tra nếu không có blog nào
            if ($blogs->isEmpty()) {
                return response()->json(['message' => 'Không có blog nào'], 404);
            }

            return response()->json($blogs, 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Có lỗi xảy ra: ' . $e->getMessage()], 500);
        }
    }
    public function show($id)
    {
        try {
            // Lấy blog theo ID
            $blog = Blog::findOrFail($id);

            // Tạo dữ liệu chi tiết với đường dẫn đầy đủ của ảnh
            $blogDetails = [
                'id' => $blog->id,
                'category_id' => $blog->category_id,
                'title' => $blog->title,
                'description' => $blog->description,
                'content' => $blog->content,
                'image' => $blog->image ? asset('storage/' . $blog->image) : null, // Đường dẫn đầy đủ
                'is_active' => $blog->is_active,
                'created_at' => $blog->created_at,
                'updated_at' => $blog->updated_at,
            ];

            return response()->json($blogDetails, 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['message' => 'Blog không tồn tại'], 404);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Có lỗi xảy ra: ' . $e->getMessage()], 500);
        }
    }
}
