<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Models\Message;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

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

        return Inertia::render('User/Chat', [
            'conversation'  => array_merge($conversation->toArray(), [
                'user_one' => $conversation->userOne?->only('id','first_name','last_name'),
                'user_two' => $conversation->userTwo?->only('id','first_name','last_name'),
            ]),
            'messages'      => $messages,
            'conversations' => $conversations->map(function ($c) use ($userId) {
                return array_merge($c->toArray(), [
                    'user_one' => $c->userOne ? $c->userOne->only('id','first_name','last_name') : null,
                    'user_two' => $c->userTwo ? $c->userTwo->only('id','first_name','last_name') : null,
                ]);
            }),
            'otherUser'     => $otherUser ? $otherUser->only('id','first_name','last_name') : null,
            'authId'        => $userId,
        ]);
    }
}
