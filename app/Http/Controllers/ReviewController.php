<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Review;
use Illuminate\Http\Request;
use Throwable;
class ReviewController extends Controller
{
    public function store(Request $request, $orderId)
    {
        // Validate the incoming request
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
            'image' => 'nullable|image',
        ]);

        try {
            // Kiểm tra nếu `order_id` đã tồn tại trong bảng reviews
            $existingReview = Review::where('order_id', $orderId)->first();
            if ($existingReview) {
                return redirect()->back()->withErrors('Đơn hàng này đã được đánh giá.');
            }

            // Lọc các từ không hợp lệ trong bình luận
            $badWords = ['dkm', 'đéo', 'cứt']; // Thêm các từ không hợp lệ vào đây
            $comment = $request->input('comment', '');

            foreach ($badWords as $badWord) {
                if (stripos($comment, $badWord) !== false) { // Kiểm tra từ khóa không hợp lệ
                    return redirect()->back()->withErrors('Bình luận của bạn chứa từ ngữ không hợp lệ. Vui lòng sửa đổi.');
                }
            }
            // Xác nhận đơn hàng tồn tại và hợp lệ
            $order = Order::findOrFail($orderId);

            // Kiểm tra trạng thái đơn hàng (chỉ cho phép đánh giá nếu trạng thái = 3)
            if ($order->status != 3) {
                return redirect()->back()->with('error', 'Bạn chỉ có thể đánh giá đơn hàng đã hoàn thành.');
            }

            // Xử lý file upload nếu có
            $imagePath = null;
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('reviews', 'public');
            }

            // Lưu đánh giá vào bảng reviews
            Review::create([
                'order_id' => $order->id,
                'user_id' => auth()->id(),
                'product_id' =>  $order->orderDetails
                    ->filter(function ($detail) {
                        return $detail->product_id !== null; // Chỉ lấy các bản ghi có product_id hợp lệ
                    })
                    ->first()?->product_id, // Lấy bản ghi đầu tiên hợp lệ, // Giả sử mỗi đơn hàng chỉ có 1 sản phẩm
                'image_path' => $imagePath,
                'rating' => $request->input('rating'),
                'comment' => $comment,
            ]);
            // Đánh dấu đơn hàng đã được đánh giá
            $order->update(['is_reviewed' => true]);

            return back()->with('success', 'Đánh giá của bạn đã được gửi thành công.');
        } catch (Throwable $e) {
            return back()->with('error', 'Lỗi: ' . $e->getMessage());
        }
    }
}