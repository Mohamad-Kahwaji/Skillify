<?php

namespace App\Notifications\Channels;

use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\Broadcaster;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Str;

class SyncBroadcastChannel
{
    public function __construct(private Broadcaster $broadcaster) {}

    public function send(object $notifiable, Notification $notification): void
    {
        if (!method_exists($notification, 'toBroadcast')) return;

        $message  = $notification->toBroadcast($notifiable);
        $channels = method_exists($notification, 'broadcastOn')
            ? $notification->broadcastOn($notifiable)
            : [];

        if (empty($channels)) {
            $class    = str_replace('\\', '.', get_class($notifiable));
            $channels = ['private-' . $class . '.' . $notifiable->getKey()];
        }

        $data = array_merge($message->data, [
            'id'   => (string) Str::uuid(),
            'type' => get_class($notification),
        ]);

        foreach ($channels as $channel) {
            // PrivateChannel stores name as 'private-{name}' already
            $name = $channel instanceof Channel ? $channel->name : $channel;

            try {
                $this->broadcaster->broadcast(
                    [$name],
                    'Illuminate\\Notifications\\Events\\BroadcastNotificationCreated',
                    $data
                );
            } catch (\Throwable $e) {
                // Reverb may not be running in dev — log and continue so DB save isn't broken
                logger()->warning('SyncBroadcastChannel: broadcast failed', [
                    'channel' => $name,
                    'error'   => $e->getMessage(),
                ]);
            }
        }
    }
}
