<?php

namespace App\Notifications;

use App\Notifications\Concerns\ViaFcm;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Broadcasting\PrivateChannel;

class NewServiceRequestNotification extends Notification
{
    use Queueable, ViaFcm;

    public function __construct(
        private string $username,
        private string $serviceName
    ) {}

    public function via(object $notifiable): array
    {
        return $this->channels($notifiable);
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'title'        => 'طلب خدمة جديد',
            'message'      => "أضاف {$this->username} خدمة جديدة «{$this->serviceName}» بانتظار المراجعة.",
            'type'         => 'info',
            'service_name' => $this->serviceName,
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
