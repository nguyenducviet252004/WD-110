<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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

}
