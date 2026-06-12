<?php

namespace App\Broadcasting;

use App\Models\Admin;


class AdminNotificationChannel
{
    /**
     * Create a new channel instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Authenticate the user's access to the channel.
     */
    public function join(Admin $admin,$id): bool
    {
        return $admin->id ===(int) $id;
    }
}
