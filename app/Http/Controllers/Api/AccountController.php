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