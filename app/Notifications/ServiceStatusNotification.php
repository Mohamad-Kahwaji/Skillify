<?php

namespace App\Notifications;

use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;

class ServiceStatusNotification extends Notification
{
    use Queueable;

    public function __construct(
        private string $status,
        private string $serviceName
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
                'title'   => 'تم قبول خدمتك',
                'message' => "تهانينا! تم قبول خدمتك «{$this->serviceName}» وأصبحت ظاهرة على المنصة.",
                'type'    => 'success',
            ],
            'rejected' => [
                'title'   => 'تم رفض خدمتك',
                'message' => "نأسف، تم رفض خدمتك «{$this->serviceName}». يمكنك التواصل مع الدعم لمزيد من المعلومات.",
                'type'    => 'error',
            ],
            'pending' => [
                'title'   => 'خدمتك قيد المراجعة',
                'message' => "تم إعادة خدمتك «{$this->serviceName}» إلى قائمة انتظار المراجعة.",
                'type'    => 'info',
            ],
        ];

        return $messages[$this->status] ?? [
            'title'   => 'تحديث حالة الخدمة',
            'message' => "تم تحديث حالة خدمتك «{$this->serviceName}».",
            'type'    => 'info',
        ];
    }

    public function toArray(object $notifiable): array
    {
        return $this->toDatabase($notifiable);
    }
}
