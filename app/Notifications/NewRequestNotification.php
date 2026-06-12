<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Broadcast;

class NewRequestNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct( private string $username,private int $requestid)
    {

    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database','broadcast'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function todatabase(object $notifiable): array
    {
        return [
            'title' => 'New Request',
            'message' => "You have a new request from {$this->username} with request id: {$this->requestid}",
            'type' => 'info',
            'request_id' => $this->requestid,
        ];
        }

        public function tobroadcast(object $notifiable): BroadcastMessage
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
