<?php

namespace App\Notifications;

use App\Notifications\Concerns\ViaFcm;
use Illuminate\Bus\Queueable;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;

class ModerationWarningNotification extends Notification
{
    use Queueable, ViaFcm;

    public function __construct(private string $message, private int $warningId, private string $targetType, private int $targetId) {}

    public function via(object $notifiable): array
    {
        return $this->channels($notifiable);
    }

    public function toDatabase(object $notifiable): array
    {
        return ['title' => 'تحذير من إدارة Skillify', 'message' => $this->message, 'type' => 'warning', 'warning_id' => $this->warningId, 'target_type' => $this->targetType, 'target_id' => $this->targetId];
    }

    public function toBroadcast(object $notifiable): BroadcastMessage
    {
        return new BroadcastMessage($this->toDatabase($notifiable));
    }

    public function broadcastOn(?object $notifiable = null): array
    {
        return $notifiable ? [new PrivateChannel("users.{$notifiable->id}.notifications")] : [];
    }

    public function toArray(object $notifiable): array
    {
        return $this->toDatabase($notifiable);
    }
}
