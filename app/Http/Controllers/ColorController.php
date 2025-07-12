<?php

namespace App\Http\Controllers;

use App\Models\Color;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ColorController extends Controller
{
     public function index()
    {
        // Lấy tất cả màu sắc, kèm theo số lượng sản phẩm sử dụng mỗi màu
        $data = Color::latest('id')->paginate(5);

        // Đếm số lượng sản phẩm sử dụng mỗi màu
        foreach ($data as $color) {
            $color->product_count = DB::table('product_color')
                ->where('color_id', $color->id)
                ->count();
        }

        return view('bienthe.colors.index', compact('data'));
    }

     public function create()
    {
        return view('bienthe.colors.create');
    }

        public function store(Request $request)
    {
        $data = $request->validate([
            'name_color'      => 'required|max:25',
            'hex_color'        => 'required|max:25'
        ]);


        try {
            Color::query()->create($data);
            return redirect()->route('colors.index')
                ->with('success', 'Thêm mới màu sắc thành công');
        } catch (\Throwable $th) {
            return back()
                ->with('success', false)
                ->with('error', $th->getMessage());
        }
    }

     public function show(Color $color)
    {
        // return view('bienthe.colors.show', compact('color'));
    }

     public function edit(Color $color)
    {
        return view('bienthe.colors.edit', compact('color'));
    }

public function update(Request $request, Color $color)
    {
        $data = $request->validate([
            'name_color'      => 'required|max:25',
        ]);

        try {
            $color->update($data);  // Cập nhật bản ghi cụ thể
            return redirect('colors')->with('success', 'Thao tác thành công ');
        } catch (\Throwable $th) {
            return back()
                ->with('error', 'Đã có lỗi xảy ra vui lòng thử lại ');
        }
    }

     public function destroy(Color $color)
    {
        try {
            // Kiểm tra xem có sản phẩm nào liên kết với màu sắc này không
            $isProductUsingColor = DB::table('product_color')
                ->where('color_id', $color->id)
                ->exists();

            if ($isProductUsingColor) {
                return back()->with('error', 'Không thể xóa màu sắc này vì có sản phẩm liên quan.');
            }

            // Nếu không có sản phẩm nào sử dụng color này, thực hiện xóa
            $color->delete();
            return back()->with('success', 'Xóa màu sắc thành công.');
        } catch (\Throwable $th) {
            return back()
                ->with('success', false)
                ->with('error', $th->getMessage());
        }
    }
}
