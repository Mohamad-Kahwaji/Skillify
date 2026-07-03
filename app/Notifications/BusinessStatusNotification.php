<?php

namespace App\Notifications;

use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;

class BusinessStatusNotification extends Notification
{
    use Queueable;

    public function __construct(
        private string $status,
        private string $businessName
    ) {}

    public function via(object $notifiable): array
    {
        return ['database', 'sync_broadcast'];
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

    public function toDatabase(object $notifiable): array
    {
        $messages = [
            'approved' => [
                'title'   => 'تم قبول حساب عملك',
                'message' => "تهانينا! تم قبول حساب عملك «{$this->businessName}» وأصبح نشطاً على المنصة.",
                'type'    => 'success',
            ],
            'rejected' => [
                'title'   => 'تم رفض حساب عملك',
                'message' => "نأسف، تم رفض طلب حساب عملك «{$this->businessName}». يمكنك التواصل مع الدعم لمزيد من المعلومات.",
                'type'    => 'error',
            ],
        ];

        return $messages[$this->status] ?? [
            'title'   => 'تحديث حساب العمل',
            'message' => "تم تحديث حالة حساب عملك «{$this->businessName}».",
            'type'    => 'info',
        ];
    }

    public function toArray(object $notifiable): array
    {
        return $this->toDatabase($notifiable);
    }
}
