<?php

namespace App\Notifications;

use App\Notifications\Concerns\ViaFcm;
use Illuminate\Bus\Queueable;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;

class UserBlockedNotification extends Notification
{
    use Queueable, ViaFcm;

    public function __construct(private string $reason = 'لم يتم تحديد سبب') {}

    public function via(object $notifiable): array
    {
        return $this->channels($notifiable);
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'title'   => 'تم تعليق حسابك',
            'message' => 'تم تعليق حسابك. السبب: ' . $this->reason,
            'type'    => 'warning',
        ];
    }

    public function toBroadcast(object $notifiable): BroadcastMessage
    {
        return new BroadcastMessage($this->toDatabase($notifiable));
    }

    public function broadcastOn(?object $notifiable = null): array
    {
        if (!$notifiable) return [];
        return [new PrivateChannel("users.{$notifiable->id}.notifications")];
    }

    public function toArray(object $notifiable): array
    {
        return $this->toDatabase($notifiable);
    }
}
