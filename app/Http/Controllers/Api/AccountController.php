<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Ship_address;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Laravel\Sanctum\PersonalAccessToken;
use Throwable;

class AccountController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'regex:/^[\w\.\-]+@([\w\-]+\.)+[a-zA-Z]{2,4}$/', 'unique:users,email'],
            'password' => 'required|string|min:6',
            'confirmPassword' => 'required|same:password',
        ]);

        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => User::ROLE_MEMBER,
                'is_active' => true
            ]);

            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'status' => true,
                'message' => 'Đăng ký thành công',
                'data' => [
                    'user' => [
                        'id' => $user->id,
                        'name' => $user->name,
                        'email' => $user->email,
                        'role' => $user->role
                    ],
                    'token' => $token
                ]
            ], 201);
        } catch (Throwable $e) {
            Log::error('Registration error: ' . $e->getMessage());
            return response()->json([
                'status' => false,
                'message' => 'Đã xảy ra lỗi khi đăng ký',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function login(Request $request)
    {
        try {
            $credentials = $request->validate([
                'email' => 'required|email',
                'password' => 'required',
            ]);

            if (!Auth::attempt($credentials)) {
                return response()->json([
                    'status' => false,
                    'message' => 'Email hoặc mật khẩu không chính xác'
                ], 401);
            }

            $user = Auth::user();

            if (!$user->isActive()) {
                Auth::logout();
                return response()->json([
                    'status' => false,
                    'message' => 'Tài khoản đã bị khóa'
                ], 403);
            }

            // Revoke all existing tokens
            $user->tokens()->delete();

            // Create new token
            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'status' => true,
                'message' => 'Đăng nhập thành công',
                'data' => [
                    'user' => [
                        'id' => $user->id,
                        'name' => $user->name,
                        'email' => $user->email,
                        'phone' => $user->phone,
                        'avatar' => $user->avatar ? asset('storage/' . ltrim($user->avatar, '/')) : null,
                        'role' => $user->role,
                        'is_active' => $user->is_active
                    ],
                    'token' => $token
                ]
            ], 200);
        } catch (Throwable $e) {
            Log::error('Login error: ' . $e->getMessage());
            return response()->json([
                'status' => false,
                'message' => 'Đã xảy ra lỗi khi đăng nhập',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function logout(Request $request)
    {
        try {
            // Revoke the token that was used to authenticate the current request
            $request->user()->currentAccessToken()->delete();

            return response()->json([
                'status' => true,
                'message' => 'Đăng xuất thành công'
            ]);
        } catch (Throwable $e) {
            Log::error('Logout error: ' . $e->getMessage());
            return response()->json([
                'status' => false,
                'message' => 'Đã xảy ra lỗi khi đăng xuất',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function checkAuth(Request $request)
    {
        try {
            $user = $request->user();

            if (!$user) {
                return response()->json([
                    'authenticated' => false,
                    'message' => 'Unauthorized'
                ], 401);
            }

            if (!$user->isActive()) {
                return response()->json([
                    'authenticated' => false,
                    'message' => 'Tài khoản đã bị khóa'
                ], 403);
            }

            return response()->json([
                'authenticated' => true,
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'phone' => $user->phone,
                    'avatar' => $user->avatar ? asset('storage/' . ltrim($user->avatar, '/')) : null,
                    'role' => $user->role,
                    'is_active' => $user->is_active
                ]
            ]);
        } catch (Throwable $e) {
            Log::error('Auth check error: ' . $e->getMessage());
            return response()->json([
                'authenticated' => false,
                'message' => 'Đã xảy ra lỗi khi kiểm tra xác thực',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
