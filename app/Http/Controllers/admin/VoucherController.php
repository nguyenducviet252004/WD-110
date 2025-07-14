<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\VoucherRequest;
use App\Models\Voucher;
use Illuminate\Http\Request;

class VoucherController extends Controller
{
    public function index(Request $request)
    {
        $query = Voucher::query();

        // Lọc trạng thái hoạt động
        if ($request->filled('is_active')) {
            $query->where('is_active', $request->is_active);
        }

        // Lọc theo khoảng ngày
        if ($request->filled('start_day')) {
            $query->whereDate('start_day', '>=', $request->start_day);
        }

        if ($request->filled('end_day')) {
            $query->whereDate('end_day', '<=', $request->end_day);
        }

        // Lọc theo giá trị giảm giá
        if ($request->filled('discount_min')) {
            $query->where('discount_value', '>=', $request->discount_min);
        }

        if ($request->filled('discount_max')) {
            $query->where('discount_value', '<=', $request->discount_max);
        }

        // Sắp xếp theo giá trị giảm giá
        if ($request->filled('sort_by')) {
            $query->orderBy('discount_value', $request->sort_by);
        } else {
            $query->latest();
        }

        $vouchers = $query->paginate(10)->appends($request->query());

        return view('vouchers.index', compact('vouchers'));
    }


    public function create()
    {
        return view('vouchers.create');
    }
    public function store(VoucherRequest $request)
    {
        Voucher::create($request->validated());
        return redirect()->route('admin.vouchers.index')->with('success', 'Tạo thành công.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    public function edit(Voucher $voucher)
    {
        return view('vouchers.edit', compact('voucher'));
    }

    public function update(VoucherRequest $request, Voucher $voucher)
    {
        $voucher->update($request->validated());
        return redirect()->route('admin.vouchers.index')->with('success', 'Cập nhật thành công.');
    }

    public function destroy(Voucher $voucher)
    {
        $voucher->delete();
        return redirect()->route('admin.vouchers.index')->with('success', 'Xóa voucher thành công.');
    }
}
