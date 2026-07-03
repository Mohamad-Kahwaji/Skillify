<?php

namespace App\Notifications;

use App\Notifications\Concerns\ViaFcm;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Broadcasting\PrivateChannel;

class NewRequestNotification extends Notification
{
    use Queueable, ViaFcm;

    public function __construct(
        private string $username,
        private int $requestId
    ) {}

    public function via(object $notifiable): array
    {
        return $this->channels($notifiable);
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'title'      => 'طلب حساب أعمال جديد',
            'message'    => "طلب جديد من {$this->username}",
            'type'       => 'info',
            'request_id' => $this->requestId,
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