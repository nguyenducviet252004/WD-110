<?php

namespace App\Http\Controllers;

use App\Models\LogoBanner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
class LogoBannerController extends Controller
{
    public function index()
    {
        $logoBanners = LogoBanner::latest('id')->paginate(10);
        return view('logo_banners.index', compact('logoBanners'));
    }

    public function create()
    {
        return view('logo_banners.create');
    }
    public function store(Request $request)
    {
        // Kiểm tra xem trong bảng LogoBanner đã có đủ 3 bản ghi chưa
        $bannerCount = LogoBanner::count();
        if ($bannerCount >= 5) {
            return back()->with('error', 'Đã đủ 5 bản ghi, không thể thêm mới.');
        }

        // Validate dữ liệu từ request
        $data = $request->validate([
            'type' => 'required',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:8192',
            'image' => 'required|image',
        ]);

        // Nếu có file hình ảnh, lưu vào storage
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('logo_banner', 'public'); // Lưu file vào storage/app/public/logo_banner
        }

        // Tạo mới bản ghi
        LogoBanner::create($data);

        return redirect()->route('logo_banners.index')->with('success', 'Thao tác thành công ');
    }
public function edit($id)
    {
        $logoBanner = LogoBanner::findOrFail($id);
        return view('logo_banners.edit', compact('logoBanner'));
    }

    public function update(Request $request, $id)
    {
        try {
            // Kiểm tra dữ liệu gửi lên
            $data = $request->validate([
                'type' => 'required',
                'title' => 'required|string|max:255',
                'description' => 'nullable|string|max:8192',
                'image' => 'nullable|image', // Không bắt buộc phải có hình ảnh
                'is_active' => 'required|boolean', // Đảm bảo is_active được gửi đúng
            ]);

            $logoBanner = LogoBanner::findOrFail($id);

            // Nếu có hình ảnh mới, lưu và cập nhật
            if ($request->hasFile('image')) {
                // Xóa hình cũ nếu có
                if ($logoBanner->image) {
                    Storage::delete('public/' . $logoBanner->image);
                }

                // Lưu hình ảnh mới
                $data['image'] = $request->file('image')->store('logo_banner', 'public');
            }

            // Cập nhật logoBanner
            $logoBanner->update($data);

            return redirect()->route('logo_banners.index')->with('success', 'Thao tác thành công ');
        } catch (\Exception $e) {
            // Nếu có lỗi, trả về thông báo lỗi
            return back()->with('error', 'Có lỗi xảy ra khi cập nhật: ');
        }
    }
}
