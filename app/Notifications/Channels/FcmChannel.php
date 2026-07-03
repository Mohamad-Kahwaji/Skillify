<?php

namespace App\Notifications\Channels;

use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FcmChannel
{
    public function send(object $notifiable, Notification $notification): void
    {
        $serverKey = config('services.fcm.server_key');
        if (!$serverKey) return;

        $token = $notifiable->fcm_token ?? null;
        if (!$token) return;

        if (!method_exists($notification, 'toArray')) return;
        $data = $notification->toArray($notifiable);

        try {
            $response = Http::withHeaders([
                'Authorization' => 'key=' . $serverKey,
                'Content-Type'  => 'application/json',
            ])->post('https://fcm.googleapis.com/fcm/send', [
                'to'           => $token,
                'notification' => [
                    'title' => $data['title']   ?? 'Skillify',
                    'body'  => $data['message'] ?? '',
                    'icon'  => '/favicon.ico',
                    'click_action' => config('app.url') . '/user/notifications',
                ],
                'data' => $data,
            ]);

            if (!$response->successful()) {
                Log::warning('FCM send failed', [
                    'status'   => $response->status(),
                    'response' => $response->body(),
                    'notifiable' => get_class($notifiable) . '#' . $notifiable->getKey(),
                ]);
            }
        } catch (\Throwable $e) {
            Log::error('FCM channel exception', ['error' => $e->getMessage()]);
        }
    }
}
