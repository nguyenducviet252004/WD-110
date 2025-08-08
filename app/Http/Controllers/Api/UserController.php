<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

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
}
