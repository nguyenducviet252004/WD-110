<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Services\StatisticsService;

class AdminController extends Controller
{


    public function search(Request $request)
    {
        $query = $request->input('query');

        // Tìm kiếm trong các bảng cần thiết (Ví dụ tìm kiếm trong bảng products)
        $results = Product::where('name', 'like', '%' . $query . '%')->get();

        return response()->json([
            'results' => $results
        ]);
    }
    public function admin(Request $request)
{
    // Tính tổng số user
    $totalUsers = User::where('role', 0)->count();

    // Tính tổng đơn hàng đã hoàn thành (status = 3 là hoàn thành)
    $completedOrders = Order::where('status', 3)->count();

    // Tính tổng đơn hàng chưa xử lý (status = 1 là chưa xử lý)
    $pendingOrders = Order::where('status', 0)->count();

    // Tính tổng doanh thu
    $totalRevenue = Order::where('status', 3)->sum('total_amount');

    return view('admin.dashboard', compact('totalUsers', 'completedOrders', 'totalRevenue', 'pendingOrders'));
}

public function edit()
    {
        $user = Auth::user();

        return view('admin.update', compact('user'));
    }

    public function update(Request $request)
    {
        /**
         * @var User $user
         */
        $user = Auth::user();

        $request->validate([
            'fullname' => 'nullable|string|max:255',
            'birth_day' => 'nullable|date',
            'phone' => 'nullable|string|max:15',
            'email' => 'nullable|email|unique:users,email,' . $user->id,
            'address' => 'nullable|string|max:255',
            'avatar' => 'nullable|image',
        ]);

        $user->fullname = $request->input('fullname');
        $user->birth_day = $request->input('birth_day');
        $user->phone = $request->input('phone');
        $user->email = $request->input('email');
        $user->address = $request->input('address');

        if ($request->hasFile('avatar')) {
            // Xóa ảnh cũ nếu tồn tại
            if ($user->avatar && Storage::exists($user->avatar)) {
                Storage::delete($user->avatar);
            }
            // Lưu ảnh mới và cập nhật đường dẫn vào cột avatar
            $user['avatar'] = Storage::put('AdminAvatar', $request->file('avatar'));
        }

        $user->save();

        return redirect()->back()->with('success', 'Thông tin tài khoản đã được cập nhật thành công.');
    }

public function changepass()
    {
        return view('admin.changepass');
    }

    public function changepass_(Request $request)
    {

        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:6|confirmed',
        ]);

        /**
         * @var User $user
         */
        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return redirect()->back()->withErrors(['current_password' => 'Mật khẩu hiện tại không đúng.']);
        }

        $user->password = Hash::make($request->new_password);
        $user->save();

        return redirect()->back()->with('success', 'Mật khẩu đã được thay đổi thành công.');
    }
}
