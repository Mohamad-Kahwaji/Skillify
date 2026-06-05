<?php

use App\Models\Conversation;
use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

// قناة المحادثة الخاصة
Broadcast::channel('conversation.{conversationId}', function ($user, $conversationId) {
    return Conversation::where('id', $conversationId)
        ->where(function ($q) use ($user) {
            $q->where('user_id_1', $user->id)
            ->orWhere('user_id_2', $user->id);
        })->exists(); // بيرجع true إذا المستخدم عضو، false إذا لا
});


Broadcast::channel('notifications.{userId}', function ($user, $userId) {
    return (int) $user->id === (int) $userId;
});
