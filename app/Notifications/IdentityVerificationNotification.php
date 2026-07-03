<?php

namespace App\Notifications;

use App\Notifications\Concerns\ViaFcm;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;

class IdentityVerificationNotification extends Notification
{
    use Queueable, ViaFcm;

    public function __construct(
        private string $status,
        private ?string $reason = null
    ) {}

    public function via(object $notifiable): array
    {
        return $this->channels($notifiable);
    }

    public function toDatabase(object $notifiable): array
    {
        return match ($this->status) {
            'approved' => [
                'title'   => 'تم قبول طلب توثيق هويتك',
                'message' => 'تهانينا! تم مراجعة وقبول طلب توثيق هويتك. حسابك الآن موثّق.',
                'type'    => 'success',
                'status'  => 'approved',
            ],
            'rejected' => [
                'title'   => 'تم رفض طلب توثيق هويتك',
                'message' => $this->reason
                    ? "تم رفض طلب توثيق هويتك. السبب: {$this->reason}"
                    : 'تم رفض طلب توثيق هويتك. يمكنك التقديم مجدداً بعد مراجعة متطلبات التوثيق.',
                'type'    => 'error',
                'status'  => 'rejected',
                'reason'  => $this->reason,
            ],
            default => [
                'title'   => 'تحديث حالة التوثيق',
                'message' => 'تم تحديث حالة طلب توثيق هويتك.',
                'type'    => 'info',
                'status'  => $this->status,
            ],
        };
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
