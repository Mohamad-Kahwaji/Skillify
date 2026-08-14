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
            ->with(['userOne.businesses', 'userTwo.businesses'])
            ->firstOrFail();

        $messages = Message::where('conversation_id', $conversationId)
            ->with('user:id,first_name,last_name')
            ->orderBy('created_at', 'asc')
            ->get();

        $conversations = Conversation::where('user_id_1', $userId)
            ->orWhere('user_id_2', $userId)
            ->with(['userOne.businesses', 'userTwo.businesses'])
            ->orderByDesc('last_message_at')
            ->get();

        $otherUser = $conversation->user_id_1 == $userId
            ? $conversation->userTwo
            : $conversation->userOne;

        return Inertia::render('User/Chat', [
            'conversation'  => array_merge($conversation->toArray(), [
                'user_one' => $conversation->userOne ? array_merge($conversation->userOne->only(['id', 'first_name', 'last_name', 'profile_photo']), [
                    'businesses' => $conversation->userOne->businesses ? $conversation->userOne->businesses->only(['id', 'image']) : null,
                ]) : null,
                'user_two' => $conversation->userTwo ? array_merge($conversation->userTwo->only(['id', 'first_name', 'last_name', 'profile_photo']), [
                    'businesses' => $conversation->userTwo->businesses ? $conversation->userTwo->businesses->only(['id', 'image']) : null,
                ]) : null,
            ]),
            'messages'      => $messages,
            'conversations' => $conversations->map(function ($c) use ($userId) {
                return array_merge($c->toArray(), [
                    'user_one' => $c->userOne ? array_merge($c->userOne->only(['id', 'first_name', 'last_name', 'profile_photo']), [
                        'businesses' => $c->userOne->businesses ? $c->userOne->businesses->only(['id', 'image']) : null,
                    ]) : null,
                    'user_two' => $c->userTwo ? array_merge($c->userTwo->only(['id', 'first_name', 'last_name', 'profile_photo']), [
                        'businesses' => $c->userTwo->businesses ? $c->userTwo->businesses->only(['id', 'image']) : null,
                    ]) : null,
                ]);
            }),
            'otherUser'     => $otherUser ? array_merge($otherUser->only(['id', 'first_name', 'last_name', 'profile_photo']), [
                'businesses' => $otherUser->businesses ? $otherUser->businesses->only(['id', 'image']) : null,
            ]) : null,
            'authId'        => $userId,
        ]);
    }
}
