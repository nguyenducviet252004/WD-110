<?php

namespace App\Http\Controllers;

use App\Models\Voucher;
use Illuminate\Http\Request;



class VoucherController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        if ($request->has('toggle_active')) {
            $voucher = Voucher::findOrFail($request->toggle_active);
            $voucher->is_active = !$voucher->is_active; // Đổi trạng thái
            $voucher->save();
            return back()->with('success', 'Trạng thái danh mục đã được cập nhật.');
        }

        $query = Voucher::query();

        // Apply filter by status (active or inactive)
        if ($request->has('status') && $request->status !== '') {
            switch ($request->status) {
                case '0': // Không hoạt động
                    $query->where('is_active', 0);
                    break;
                case '1': // Đang hoạt động
                    $query->where('is_active', 1)
                        ->whereDate('start_day', '<=', now())
                        ->whereDate('end_day', '>=', now());
                    break;
            }
        }

        // Apply filter by expiration status
        if ($request->has('expiry_status') && $request->expiry_status !== '') {
            switch ($request->expiry_status) {
                case 'valid': // Còn hạn
                    $query->whereDate('end_day', '>=', now());
                    break;
                case 'expired': // Đã hết hạn
                    $query->whereDate('end_day', '<', now());
                    break;
            }
        }

        // Apply sorting by discount value (ascending or descending)
        if ($request->has('sort_by') && $request->sort_by !== '') {
            $sortOrder = $request->sort_by == 'desc' ? 'desc' : 'asc';
            $query->orderBy('discount_value', $sortOrder);
        }

        // Select specific columns and paginate results
        $vouchers = $query->select('id', 'code', 'discount_value', 'description', 'start_day', 'end_day', 'is_active', 'total_min', 'total_max')
            ->paginate(10);

        return view('vouchers.index', [
            'vouchers' => $vouchers,
            'status' => $request->status,
            'expiry_status' => $request->expiry_status,
            'sort_by' => $request->sort_by,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('vouchers.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            // Xác thực các trường dữ liệu
            $request->validate([
                'code' => 'required|string|max:10|unique:vouchers',
                'discount_value' => 'required|numeric',
                'description' => 'nullable|string',
                'quantity' => 'required|integer',
                'used_times' => 'nullable|integer|min:0',
                'start_day' => 'nullable|date',
                'end_day' => 'nullable|date',
                'is_active' => 'required|boolean', // Trường 'is_active' thay vì 'status'
                'total_min' => 'required|numeric',
                'total_max' => 'required|numeric'
            ]);

            // Kiểm tra ngày kết thúc phải lớn hơn ngày bắt đầu
            if ($request->end_day < $request->start_day) {
                return back()->with('error', 'Ngày kết thúc phải lớn hơn ngày bắt đầu.');
            }

            // Tạo voucher mới

            Voucher::create([
                'code' => $request->code,
                'discount_value' => $request->discount_value,
                'description' => $request->description,
                'quantity' => $request->quantity,
                'used_times' => $request->used_times ?? 0, // Đảm bảo giá trị mặc định
                'start_day' => $request->start_day,
                'end_day' => $request->end_day,
                'is_active' => $request->is_active,
                'total_min' => $request->total_min,
                'total_max' => $request->total_max,
            ]);

            // Trả về thông báo thành công
            return redirect()->route('vouchers.index')->with('success', 'Voucher được thêm mới thành công.');
        } catch (\Illuminate\Database\QueryException $e) {
            // Xử lý lỗi cơ sở dữ liệu, ví dụ như lỗi kết nối, lỗi khi lưu dữ liệu
            return back()->with('error', 'Đã xảy ra lỗi khi lưu voucher. Vui lòng thử lại sau.');
        } catch (\Exception $e) {
            // Xử lý các lỗi khác
            return back()->with('error', 'Đã xảy ra lỗi không xác định. Vui lòng thử lại sau.');
        }
    }






    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $voucher = Voucher::findOrFail($id);
        return view('vouchers.show', compact('voucher'));
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $voucher = Voucher::findOrFail($id);
        return view('vouchers.edit', compact('voucher'));
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // Kiểm tra xem end_day có nhỏ hơn start_day không
        if ($request->end_day && $request->start_day && $request->end_day < $request->start_day) {
            return back()->with('error', 'Ngày kết thúc không được nhỏ hơn ngày bắt đầu.');
        }

        // Validate dữ liệu còn lại
        $request->validate([
            'code' => 'required|max:10|unique:vouchers,code,' . $id,
            'discount_value' => 'required|numeric',
            'description' => 'nullable|string',
            'quantity' => 'required|integer',
            'start_day' => 'nullable|date',
            'end_day' => 'nullable|date|after_or_equal:start_day',
            'is_active' => 'required|boolean',
            'total_min' => 'required|numeric',
            'total_max' => 'required|numeric'
        ]);

        // Cập nhật voucher
        $voucher = Voucher::findOrFail($id);
        $voucher->update([
            'code' => $request->code,
            'discount_value' => $request->discount_value,
            'description' => $request->description,
            'quantity' => $request->quantity,
            'start_day' => $request->start_day,
            'end_day' => $request->end_day,
            'total_min' => $request->total_min,
            'total_max' => $request->total_max,
            'is_active' => $request->is_active,
        ]);

        return back()->with('success', 'Voucher đã được cập nhật.');
    }





    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $voucher = Voucher::findOrFail($id);
        $voucher->delete();

        return redirect()->route('vouchers.index')->with('success', 'Voucher deleted successfully.');
    }
}
