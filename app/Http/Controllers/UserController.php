<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

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
    public function edit()
    {
        $user = Auth::user();
        $addresses = $user->shipAddresses;

        return view('user.update', compact('user', 'addresses'));
    }

    public function update(Request $request)
    {
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
            $user['avatar'] = Storage::put('UserAvatar', $request->file('avatar'));
        }

        $addressId = $request->input('address_id'); // Nhận ID địa chỉ từ request
        if ($addressId) {
            DB::transaction(function () use ($user, $addressId) {
                // Đặt tất cả các địa chỉ khác về 0
                   /**
                 * @var User $user
                 */
                  /**
                 * @var Ship_address $user
                 */
                $user->shipAddresses()->update(['is_default' => 0]);

                // Cập nhật địa chỉ được chọn thành mặc định
                $address = $user->shipAddresses()->find($addressId);
                if ($address) {
                    $address->is_default = 1; // Đặt is_default thành 1
                    $address->save(); // Lưu lại thay đổi
                }
            });
        }
        
           /**
             * @var User $user
             */
        $user->save();

        return redirect()->back()->with('success', 'Thông tin tài khoản đã được cập nhật thành công.');
    }
}
