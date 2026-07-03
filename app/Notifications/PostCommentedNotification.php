<?php

namespace App\Notifications;

use App\Models\Post;
use App\Models\User;
use App\Notifications\Concerns\ViaFcm;
use Illuminate\Bus\Queueable;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;

class PostCommentedNotification extends Notification
{
    use Queueable, ViaFcm;

    public function __construct(public User $commenter, public Post $post) {}

    public function via(object $notifiable): array
    {
        return $this->channels($notifiable);
    }

    public function toDatabase($notifiable): array
    {
        return [
            'title'        => 'تعليق على منشورك',
            'message'      => ($this->commenter->first_name ?? $this->commenter->name) . ' علّق على منشورك',
            'post_id'      => $this->post->id,
            'commenter_id' => $this->commenter->id,
            'type'         => 'post_commented',
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
