<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
     public function user(Request $request)
    {
        $token = $request->cookie('token');
    
        // Nếu token có định dạng "ID|token", chỉ lấy phần token
        if ($token && strpos($token, '|') !== false) {
            $tokenParts = explode('|', $token);
            $token = end($tokenParts); // Lấy phần sau dấu '|'
        }
    
        return view('user.dashboard', ['token' => $token]);
    }

    public function changepass()
    {
        return view('user.changepass');
    }

    public function changepass_(Request $request)
    {
        try {
            $request->validate([
                'current_password' => 'required',
                'new_password' => 'required|confirmed',
            ]);
    
            $user = Auth::user();
    
            // Kiểm tra mật khẩu hiện tại
            if (!Hash::check($request->current_password, $user->password)) {
                return back()->withErrors(['current_password' => 'Mật khẩu hiện tại không đúng.']);
            }
    
            // Kiểm tra độ dài mật khẩu mới
            if (strlen($request->new_password) < 6) {
                return back()->withErrors(['new_password' => 'Mật khẩu mới phải có ít nhất 6 ký tự.']);
            }
                 /**
                 * @var User $user
                 */
    
            // Cập nhật mật khẩu
            $user->update(['password' => Hash::make($request->new_password)]);
    
            return back()->with('success', 'Mật khẩu đã được thay đổi thành công.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Đã xảy ra lỗi. Vui lòng thử lại sau.']);
        }
    }

    
}
