<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Ship_address;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\PersonalAccessToken;
use Laravel\Sanctum\Sanctum;
use Throwable;
class AccountController extends Controller
{


    public function register(Request $request)
    {
        // Validate incoming request
        $request->validate([
            'email' => ['required', 'regex:/^[\w\.\-]+@([\w\-]+\.)+[a-zA-Z]{2,4}$/', 'unique:users,email'],
            'password' => 'required|string|min:6',
            'confirmPassword' => 'required|same:password', // Ensure confirm_password matches password
        ]);
    
        try {
            // Prepare user data
            $userData = [
                'email' => $request->input('email'),
                'password' => Hash::make($request->input('password')),  // Hash the password
                'role' => $request->filled('role') ? $request->input('role') : 0,  // Default role is 0
            ];
    
            // Create user
            $user = User::create($userData);
    
            // Return success response
            return response()->json([
                'status' => true,
                'message' => 'Đăng kí thành công',
                'data' => [
                    'email' => $user->email,
                ]
            ], 200);
    
        } catch (Throwable $e) {
            // Handle error
            return back()->with('error', $e->getMessage());
        }
    }
    public function login(Request $request)
    {
        try {
            // Validate input
            $credentials = $request->validate([
                'email' => 'required|email',
                'password' => 'required',
            ]);
    
            // Kiểm tra xem có tồn tại tài khoản với email và mật khẩu không
            if (Auth::attempt(['email' => $credentials['email'], 'password' => $credentials['password']])) {
    
                // Lấy thông tin người dùng đã đăng nhập
                $user = Auth::user();
    
                // Kiểm tra trạng thái tài khoản
                if ($user->is_active == 0) {
                    Auth::logout();  // Đảm bảo không có session nào được tạo
                    return response()->json([
                        'error' => 'Tài khoản của bạn đã bị khóa. Vui lòng liên hệ quản trị viên.'
                    ], 403);
                }
    
                /** @var User $user */
                // Tạo token cho người dùng (để dùng ở React)
                $token = $user->createToken('API Token')->plainTextToken;
    
                // Trả về dữ liệu người dùng và token
                return response()->json([
                    'status' => true,
                    'message' => 'Đăng nhập thành công',
                    'data' => [
                        'user' => [
                            'id'        => $user->id,
                            'email'     => $user->email,
                            'username'  => $user->username,
                            'fullname'  => $user->fullname,
                            'birth_day' => $user->birth_day,
                            'phone'     => $user->phone,
                            'address'   => $user->address,
                            'role'      => $user->role,
                            'is_active' => $user->is_active,
                            'avatar'    => $user->avatar ? asset('storage/' . ltrim($user->avatar, '/')) : null,
                        ],
                        'token' => $token
                    ]
                ], 200);
            }
    
            return response()->json([
                'error' => 'Tài khoản không tồn tại hoặc sai tài khoản, mật khẩu'
            ], 401);
        } catch (Throwable $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }
    
public function show($userId)
    {
        try {
            $user = User::with('shipAddresses')->find($userId);

            if (!$user) {
                return response()->json(['message' => 'User not found'], 404);
            }
            $token = substr($user->createToken('API Token')->plainTextToken, 3);

            $user->avatar = asset('storage/' . $user->avatar);

            $filePath = storage_path('app/user_' . $userId . '.txt');

            if (file_exists($filePath)) {
                $data = json_decode(file_get_contents($filePath), true);
                $data['token'] = $token;
                $data['user'] = $user;

                return response()->json($data);
            }
            return response()->json([
                'token' => $token,
                'user' => $user
            ]);
        } catch (Throwable $e) {
            return response()->json(['message' => 'Error occurred: ' . $e->getMessage()], 404);
        }
    }
    public function logout(Request $request)
    {
        try {
            // Retrieve the authenticated user
            $user = Auth::user();

            // Revoke all of the user's tokens
            $user->tokens->each(function ($token) {
                $token->delete();
            });

            return response()->json([
                'status' => true,
                'message' => 'Đăng xuất thành công'
            ], 200);
        } catch (Throwable $e) {
            return response()->json(['error' => 'Có lỗi xảy ra: ' . $e->getMessage()], 500);
        }
    }