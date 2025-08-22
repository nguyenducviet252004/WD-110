<?php
namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Str;
use Throwable;

class AccountController extends Controller{
    public function register()
    {
        return view('account.login', ['activeTab' => 'signup']);
    }

     public function register_(Request $request)
    {
        $user = $request->validate([
            'email' => ['required', 'regex:/^[\w\.\-]+@([\w\-]+\.)+[a-zA-Z]{2,4}$/', 'unique:users,email'],
            'password' => 'required|string|min:6|confirmed', // Use 'confirmed' for password confirmation
        ]);

        try {
            $user['password'] = Hash::make($request->input('password'));
            $user['role'] = $request->filled('role') ? $request->input('role') : 0;

            $user = User::query()->create($user);

            Auth::login($user);
            $request->session()->regenerate();

            try {
                $token = $user->createToken('login')->plainTextToken;

                // Tách chuỗi token để lấy phần token thực tế
                $tokenParts = explode('|', $token);
                $actualToken = isset($tokenParts[1]) ? $tokenParts[1] : $token; // Lấy phần thứ hai nếu có

                // Lưu vào cookie mà JavaScript có thể truy cập
                $cookie = cookie('token', $actualToken, 0, null, null, false, false); // không HttpOnly
                $userId = $user->id;
                $userCookie = cookie('user_id', $userId, 0, null, null, false, false); // không HttpOnly

                // Lưu ID và token vào storage
                Storage::disk('local')->put('user_' . $userId . '.txt', json_encode([
                    'user_id' => $userId,
                    'token' => $actualToken,
                ]));
            } catch (\Exception $e) {
                return back()->withErrors(['token' => $e->getMessage()]);
            }

            return redirect("http://localhost:3000/?user_id={$userId}")
                ->with('success', 'Đăng kí tài khoản thành công')
                ->withCookie($cookie)
                ->withCookie($userCookie);
        } catch (Throwable $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function login()
    {
        return view('account.login', ['activeTab' => 'signup']);
    }

      public function login_(Request $request)
    {
        $credentials = $request->validate([
            'account' => 'required',
            'password' => 'required',
        ]);

        $loginType = filter_var($credentials['account'], FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        $loginCredentials = [
            $loginType => $credentials['account'],
            'password' => $credentials['password'],
        ];

        if (Auth::attempt($loginCredentials, true)) {
            $request->session()->regenerate();

            /** @var User $user */
            $user = Auth::user();

            // Check if the user is active
            if ($user->is_active == 0) {
                Auth::logout();
                return back()->withErrors([
                    'account' => 'Tài khoản của bạn đã bị khóa. Vui lòng liên hệ quản trị viên.',
                ])->onlyInput('account');
            }

            Log::info('User ID: ' . $user->id);
            Log::info('User Type: ' . get_class($user));

            try {
                $token = $user->createToken('login')->plainTextToken;

                // Tách chuỗi token để lấy phần token thực tế
                $tokenParts = explode('|', $token);
                $actualToken = isset($tokenParts[1]) ? $tokenParts[1] : $token; // Lấy phần thứ hai nếu có

                // Lưu vào cookie mà JavaScript có thể truy cập
                $cookie = cookie('token', $actualToken, 0, null, null, false, false); // không HttpOnly
                $userId = $user->id;
                $userCookie = cookie('user_id', $userId, 0, null, null, false, false); // không HttpOnly

                // Lưu ID và token vào storage
                Storage::disk('local')->put('user_' . $userId . '.txt', json_encode([
                    'user_id' => $userId,
                    'token' => $actualToken,
                ]));

            } catch (\Exception $e) {
                return back()->withErrors(['token' => $e->getMessage()]);
            }

            // Chuyển hướng với ID người dùng qua URL
            return redirect("http://localhost:3000/?user_id={$userId}")
                ->with('success', 'Đăng nhập thành công')
                ->withCookie($cookie)
                ->withCookie($userCookie);
        }

        return back()->withErrors([
            'account' => 'Tài khoản không tồn tại hoặc sai tài khoản, mật khẩu',
        ])->onlyInput('account');
    }

     public function logout(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();

        if ($user) {
            // Xóa tất cả các token của người dùng
            $user->tokens()->delete();

            // Lấy ID người dùng từ Auth
            $userId = Auth::id();

            if ($userId) {
                // Tạo đường dẫn file chính xác
                $filePath = 'user_' . $userId . '.txt';

                // Kiểm tra sự tồn tại của file và xóa nếu có
                if (Storage::exists($filePath)) {
                    Storage::delete($filePath);
                } else {
                    Log::info("File không tồn tại: " . $filePath);
                }
            }
        }

        //đăng xuất ng dùng
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Xóa session và cookie
        session()->forget('laravel_session');
        Cookie::forget('token');
        Cookie::forget('user_id');
        session()->flush();

        // Chuyển hướng
        return redirect('http://localhost:3000?logout=true')->with('success', 'Đã đăng xuất thành công');
    }


     public function rspassword()
    {
        return view('account.login', ['activeTab' => 'forgot']);
    }

    public function rspassword_(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        return $status === Password::RESET_LINK_SENT
            ? back()->with(['success' => 'Thành công, vui lòng mở hòm thư trong địa chỉ email đã nhập'])
            : back()->withErrors(['errors' => 'Thất bại, không tìm thấy địa chỉ email này']);
    }

       public function updatepassword($token)
    {
        $email = request()->query('email');
        return view('account.resetpass', ['token' => $token, 'email' => $email]);
    }

    public function updatepassword_(Request $request)
    {
          $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|confirmed',
        ]);
         $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->setRememberToken(Str::random(60));

                $user->save();

                event(new PasswordReset($user));
            }
        );
         if ($status === Password::PASSWORD_RESET) {
            return redirect('http://localhost:3000')->with('success', 'Cập nhật mật khẩu thành công, xin mời đăng nhập');
        } else {
            return back()->withErrors(['errors' => 'Có lỗi xảy ra, vui lòng thử lại!']);

        }
    }

    public function verify(Request $request)
    {
            /**
             * @var User $user
             */
        $user = Auth::user();

        // Kiểm tra xem email đã được xác thực chưa
        if ($user->email_verified_at != null) {
            return redirect()->back()->with('success', 'Email của bạn đã được xác thực');
        }

        // Gửi email xác minh
        $user->sendEmailVerificationNotification();


        return redirect()->back()->with('success', 'Email xác minh đã được gửi tới hòm thư của bạn');
    }

    public function verifydone(Request $request, $id, $hash)
{

    $user = User::findOrFail($id);

    // Kiểm tra mã hash với email đã được băm
    if (! hash_equals((string) $hash, (string) sha1($user->getEmailForVerification()))) {
        return redirect()->route('home')->withErrors(['email' => 'Invalid verification link.']);
    }

    // Xác thực email
    if (!$user->hasVerifiedEmail()) {
        $user->markEmailAsVerified();
    }

    return redirect()->route('user.edit')->with('success', 'Xác minh email thành công');
}


}
