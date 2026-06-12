<?php

namespace App\Broadcasting;

use App\Models\SuperAdmin;

class SuperadminNotificationChannel
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
    public function join(SuperAdmin $superadmin,$id): bool
    {
        return $superadmin->id ===(int) $id;
    }
}
