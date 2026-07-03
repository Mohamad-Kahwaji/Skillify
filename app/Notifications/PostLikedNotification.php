<?php

namespace App\Notifications;

use App\Models\Post;
use App\Models\User;
use App\Notifications\Concerns\ViaFcm;
use Illuminate\Bus\Queueable;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;

class PostLikedNotification extends Notification
{
    use Queueable, ViaFcm;

    public function __construct(public User $liker, public Post $post) {}

    public function via(object $notifiable): array
    {
        return $this->channels($notifiable);
    }

    public function toDatabase($notifiable): array
    {
        return [
            'title'   => 'إعجاب بمنشورك',
            'message' => ($this->liker->first_name ?? $this->liker->name) . ' أعجب بمنشورك',
            'post_id'  => $this->post->id,
            'liker_id' => $this->liker->id,
            'type'    => 'post_liked',
        ];
    }

    public function toBroadcast($notifiable): BroadcastMessage
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
