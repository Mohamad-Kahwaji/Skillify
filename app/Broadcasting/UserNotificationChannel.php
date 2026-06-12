<?php

namespace App\Broadcasting;

use App\Models\User;

class UserNotificationChannel
{
    public function join(User $user, $id): bool
    {
        return $user->id === (int) $id;
    }
}