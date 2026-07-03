<?php

namespace App\Notifications\Concerns;

trait ViaFcm
{
    protected function channels(object $notifiable): array
    {
        $channels = ['database', 'sync_broadcast'];

        if (!empty($notifiable->fcm_token) && config('services.fcm.server_key')) {
            $channels[] = 'fcm';
        }

        return $channels;
    }
}
