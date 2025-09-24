<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    public function listForCurrentUser(Request $request)
    {
        /** @var User|null $user */
        $user = Auth::user();
        if (!$user) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        $messages = Message::where('user_id', $user->id)
            ->orderBy('created_at', 'asc')
            ->get();

        return response()->json(['messages' => $messages]);
    }

    public function sendByCurrentUser(Request $request)
    {
        /** @var User|null $user */
        $user = Auth::user();
        if (!$user) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        $data = $request->validate([
            'content' => 'required|string|max:2000',
        ]);

        $message = Message::create([
            'user_id' => $user->id,
            'sender_type' => 'user',
            'content' => $data['content'],
        ]);

        return response()->json(['message' => $message], 201);
    }

    public function listForAdmin(Request $request, int $userId)
    {
        $this->authorizeAdmin();

        $messages = Message::where('user_id', $userId)
            ->orderBy('created_at', 'asc')
            ->get();

        return response()->json(['messages' => $messages]);
    }

    public function sendByAdmin(Request $request, int $userId)
    {
        $this->authorizeAdmin();

        $data = $request->validate([
            'content' => 'required|string|max:2000',
        ]);

        $message = Message::create([
            'user_id' => $userId,
            'sender_type' => 'admin',
            'content' => $data['content'],
        ]);

        return response()->json(['message' => $message], 201);
    }

    public function adminConversations(Request $request)
    {
        $this->authorizeAdmin();

        // Lấy cuộc trò chuyện gần nhất theo user (không lọc đã đọc)
        $rows = Message::query()
            ->select('user_id')
            ->selectRaw('MAX(id) as last_message_id')
            ->groupBy('user_id')
            ->orderByDesc('last_message_id')
            ->limit(50)
            ->get();

        $conversations = $rows->map(function ($row) {
            $last = Message::find($row->last_message_id);
            $unread = Message::where('user_id', $row->user_id)
                ->where('sender_type', 'user')
                ->whereNull('read_at')
                ->count();
            $user = User::find($row->user_id);
            return [
                'user_id' => $row->user_id,
                'user_name' => $user?->fullname ?? $user?->email ?? ('User #' . $row->user_id),
                'last_message' => $last?->content ?? '',
                'last_time' => optional($last?->created_at)->toDateTimeString(),
                'unread' => $unread,
            ];
        })->values();

        return response()->json(['conversations' => $conversations]);
    }

    public function markReadForAdmin(Request $request, int $userId)
    {
        $this->authorizeAdmin();
        Message::where('user_id', $userId)
            ->where('sender_type', 'user')
            ->whereNull('read_at')
            ->update(['read_at' => now()]);
        return response()->json(['success' => true]);
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


