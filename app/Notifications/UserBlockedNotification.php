<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class UserBlockedNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct( private string $reason = '',)
    {

    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['dataBase','broadcast'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toDataBase(object $notifiable): array
    {
        return [
            'title' => 'Account Blocked',
            'message' => "Your account has been blocked. Reason: .{$this->reason}",
            'type' => 'worning',
        ];
        }

    public function toBroadcast(object $notifiable):BroadcastMessage
    {
        return new BroadcastMessage($this->toDataBase($notifiable));

    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
