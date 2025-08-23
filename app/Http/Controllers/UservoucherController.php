<?php

namespace App\Http\Controllers;

use App\Models\Voucher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UservoucherController extends Controller
{
    public function index()
    {
        $userId = Auth::id(); // Lấy ID người dùng đang đăng nhập

        // Lấy danh sách voucher chưa được người dùng sử dụng
        $vouchers = Voucher::where('is_active', 1)
            ->whereDoesntHave('voucherUsages', function ($query) use ($userId) {
                $query->where('user_id', $userId);
            })
            ->paginate(5);

        return view('user.voucher', compact('vouchers'));
    }
}
