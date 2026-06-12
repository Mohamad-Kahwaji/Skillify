<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Broadcasting\PrivateChannel;

class NewRequestNotification extends Notification
{
    use Queueable;

    public function __construct(
        private string $username,
        private int $requestId,
        private ?object $notifiable = null
    ) {}

    public function via(object $notifiable): array
    {
        return ['database', 'broadcast'];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'title'      => 'New Request',
            'message'    => "You have a new request from {$this->username}",
            'type'       => 'info',
            'request_id' => $this->requestId,
        ];
    }

    public function toBroadcast(object $notifiable): BroadcastMessage
    {
        return new BroadcastMessage($this->toDatabase($notifiable));
    }

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('admins.' . $this->notifiable->id . '.notifications'),
        ];
    }

    public function toArray(object $notifiable): array
    {
        return $this->toDatabase($notifiable);
    }
}