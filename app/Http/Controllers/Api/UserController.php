<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function show($userId)
    {
        $user = User::find($userId);

        if (!$user) {
            return response()->json(['message' => 'Người dùng không tìm thấy'], 404);
        }

        return response()->json($user, 200);
    }
    
    public function update(Request $request, $id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $validator = Validator::make($request->all(), [
            'username' => 'nullable|string|max:255',
            'fullname' => 'nullable|string|max:255',
            'phone' => 'nullable|numeric|digits:10',
            'email' => 'nullable|email|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        $user->avatar = $request->avatar ?? $user->avatar;
        $user->username = $request->username ?? $user->username;
        $user->fullname = $request->fullname ?? $user->fullname;
        $user->birth_day = $request->birth_day ?? $user->birth_day;
        $user->phone = $request->phone ?? $user->phone;
        $user->email = $request->email ?? $user->email;
        $user->address = $request->address ?? $user->address;

        $user->save();

        return response()->json([
            'message' => 'User updated successfully',
            'data' => $user
        ], 200);
    }
}
