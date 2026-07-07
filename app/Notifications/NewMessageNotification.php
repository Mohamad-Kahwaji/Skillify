<?php

namespace App\Notifications;

use App\Models\Message;
use App\Notifications\Concerns\ViaFcm;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;

class NewMessageNotification extends Notification
{
    use Queueable, ViaFcm;

    public function __construct(public Message $message) {}

    public function via(object $notifiable): array
    {
        return $this->channels($notifiable);
    }

    public function toDatabase(object $notifiable): array
    {
        $sender = $this->message->user;

        return [
            'title'           => trim(($sender->first_name ?? '') . ' ' . ($sender->last_name ?? '')) ?: 'رسالة جديدة',
            'message'         => $this->message->message_text ?? '📎 ملف مرفق',
            'conversation_id' => $this->message->conversation_id,
            'sender_id'       => $this->message->user_id,
            'type'            => 'new_message',
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
