<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Models\Message;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    public function show(int $conversationId)
    {
        $userId = Auth::guard('users')->id();

        $conversation = Conversation::where('id', $conversationId)
            ->where(fn($q) => $q->where('user_id_1', $userId)->orWhere('user_id_2', $userId))
            ->with(['userOne', 'userTwo'])
            ->firstOrFail();

        $messages = Message::where('conversation_id', $conversationId)
            ->with('user:id,first_name,last_name')
            ->orderBy('created_at', 'asc')
            ->get();

        $conversations = Conversation::where('user_id_1', $userId)
            ->orWhere('user_id_2', $userId)
            ->with(['userOne', 'userTwo'])
            ->orderByDesc('last_message_at')
            ->get();

        $otherUser = $conversation->user_id_1 == $userId
            ? $conversation->userTwo
            : $conversation->userOne;

        return view('chat', compact('conversation', 'messages', 'conversations', 'otherUser'));
    }
}
