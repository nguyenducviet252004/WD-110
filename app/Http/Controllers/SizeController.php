<?php

namespace App\Http\Controllers;

use App\Models\Size;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
class SizeController extends Controller
{
     public function index()
    {
        // Lấy tất cả kích thước, kèm theo số lượng sản phẩm sử dụng mỗi kích thước
        $data = Size::latest('id')->paginate(5);

        // Đếm số lượng sản phẩm liên quan đến mỗi kích thước
        foreach ($data as $size) {
            $size->product_count = DB::table('product_size')
                ->where('size_id', $size->id)
                ->count();
        }

        return view('bienthe.sizes.index', compact('data'));
    }

     /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('bienthe.sizes.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'size'      => 'required|max:25',
        ]);

        try {
            Size::query()->create($data);
            return redirect()->route('sizes.index')
                ->with('success', 'Thêm mới thành công');
        } catch (\Throwable $th) {
            return back()
                ->with('success', false)
                ->with('error', $th->getMessage());
        }
    }

     /**
     * Display the specified resource.
     */
    public function show(Size $size)
    {
        return view('bienthe.sizes.show', compact('size'));
    }

     /**
     * Show the form for editing the specified resource.
     */
    public function edit(Size $size)
    {
        return view('bienthe.sizes.edit', compact('size'));
    }

     /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Size $size)
    {
        $data = $request->validate([
            'size'      => 'required|max:25',
        ]);

        try {
            $size->update($data);  // Cập nhật bản ghi cụ thể
            return redirect('sizes')->with('success', true);
        } catch (\Throwable $th) {
            return back()
                ->with('success', false)
                ->with('error', $th->getMessage());
        }
    }

     /**
     * Remove the specified resource from storage.
     */
    public function destroy(Size $size)
    {
        try {
            // Kiểm tra xem có sản phẩm nào liên kết với kích thước này không
            $isProductUsingSize = DB::table('product_size')
                ->where('size_id', $size->id)
                ->exists();

            if ($isProductUsingSize) {
                return back()->with('error', 'Không thể xóa kích thước này vì có sản phẩm liên quan.');
            }

            // Nếu không có sản phẩm nào sử dụng size này, thực hiện xóa
            $size->delete();
            return back()->with('success', 'Xóa kích thước thành công.');
        } catch (\Throwable $th) {
            return back()
                ->with('success', false)
                ->with('error', $th->getMessage());
        }
    }
}
