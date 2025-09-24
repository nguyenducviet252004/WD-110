<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatAdminController extends Controller
{
    public function index()
    {
        $this->authorizeAdmin();
        $token = Auth::user()->createToken('admin-chat')->plainTextToken;
        return view('admin.chat.index', compact('token'));
    }

    private function authorizeAdmin(): void
    {
        /** @var User|null $user */
        $user = Auth::user();
        if (!$user || (int)($user->role ?? 0) !== 1) {
            abort(403, 'Forbidden');
        }
    }
}


