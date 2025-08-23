<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Http\Request;

class QuanliReviewController extends Controller
{
    public function index()
{
    // Lấy tất cả đánh giá cùng với thông tin người dùng và sản phẩm
    $reviews = Review::with('user', 'product')->get();
    return view('review.index', compact('reviews'));
}
    
    public function update(Request $request, $id)
    {
        $review = Review::findOrFail($id);
    
        // Đảo ngược trạng thái is_reviews
        $review->is_reviews = !$review->is_reviews;
        $review->save();
    
        return back()->with('success', 'Cập nhật trạng thái thành công!');
    }

    
}
