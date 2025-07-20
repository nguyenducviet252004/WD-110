<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Throwable;
class ManagerUserController extends Controller
{
    public function index(Request $request)
{
    $query = User::query();

    // Lọc chỉ lấy tài khoản có role là 0
    $query->where('role', 0);

    // Lọc theo trạng thái is_active nếu có
    if ($request->has('is_active')) {
        $is_active = $request->get('is_active');
        if ($is_active === 'locked') {
            $query->where('is_active', 0); // Đã khóa
        } elseif ($is_active === 'normal') {
            $query->where('is_active', 1); // Bình thường
        }
    }
    // Lấy dữ liệu với phân trang
    $data = $query->latest()->paginate(5);

    return view('managers.index', compact('data'));
}



    public function update($id)
    {
        try {
            $user = User::findOrFail($id);
            $user->is_active = !$user->is_active; // Chuyển đổi trạng thái
            $user->save();

            $message = $user->is_active ? 'Mở khóa tài khoản thành công.' : 'Khóa tài khoản thành công.';
            return back()->with('success', $message);
        } catch (Throwable $e) {
            return back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }
}
