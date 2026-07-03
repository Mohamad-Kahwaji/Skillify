<?php

namespace App\Notifications;

use App\Notifications\Concerns\ViaFcm;
use Illuminate\Bus\Queueable;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;

class SystemAnnouncementNotification extends Notification
{
    use Queueable, ViaFcm;

    public function __construct(private string $title, private string $message) {}

    public function via(object $notifiable): array
    {
        return $this->channels($notifiable);
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'title'   => $this->title,
            'message' => $this->message,
            'type'    => 'announcement',
        ];
    }

    public function toBroadcast(object $notifiable): BroadcastMessage
    {
        return new BroadcastMessage($this->toDatabase($notifiable));
    }

    public function broadcastOn(?object $notifiable = null): array
    {
        if (!$notifiable) return [];
        $guard = match (true) {
            $notifiable instanceof \App\Models\SuperAdmin => 'superadmins',
            $notifiable instanceof \App\Models\Admin      => 'admins',
            default                                        => 'users',
        };
        return [new PrivateChannel("{$guard}.{$notifiable->id}.notifications")];
    }

    public function toArray(object $notifiable): array
    {
        return $this->toDatabase($notifiable);
    }
}
