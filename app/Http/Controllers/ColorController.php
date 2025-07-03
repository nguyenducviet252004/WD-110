<?php

namespace App\Http\Controllers;

use App\Models\Color;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ColorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Lấy tất cả màu sắc, kèm theo số lượng sản phẩm sử dụng mỗi màu
        $data = Color::latest('id')->paginate(5);

        // Đếm số lượng sản phẩm sử dụng mỗi màu
        foreach ($data as $color) {
            $color->product_count = DB::table('product_colors')
                ->where('id', $color->id)
                ->count();
        }

        return view('colors.index', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('colors.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255|unique:product_colors',
            'code' => 'required|string|max:25'
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

    /**
     * Display the specified resource.
     */
    public function show(Color $color)
    {
        return view('colors.show', compact('color'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Color $color)
    {
        return view('colors.edit', compact('color'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Color $color)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255|unique:product_colors,name,' . $color->id,
            'code' => 'required|string|max:25'
        ]);

        try {
            $color->update($data);  // Cập nhật bản ghi cụ thể
           
        return redirect()->route('colors.index')->with('success', 'Thao tác thành công');
        } catch (\Throwable $th) {
            return back()
                ->with('error', 'Đã có lỗi xảy ra vui lòng thử lại');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Color $color)
    {
        try {
            // Kiểm tra xem có sản phẩm nào liên kết với màu sắc này không (dựa vào bảng product_variants)
            $isProductUsingColor = DB::table('product_variants')
                ->where('product_color_id', $color->id)
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