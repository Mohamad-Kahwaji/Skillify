<?php

namespace App\Providers;

use App\Models\SuperAdmin;
use App\Notifications\Channels\FcmChannel;
use App\Notifications\Channels\SyncBroadcastChannel;
use Illuminate\Contracts\Broadcasting\Broadcaster;
use Illuminate\Http\Request;
use Illuminate\Notifications\ChannelManager;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        // Super admins bypass all permission/gate checks unconditionally.
        Gate::before(function ($user, string $_ability) {
            if ($user instanceof SuperAdmin) {
                return true;
            }
        });

        // Synchronous broadcast channel — broadcasts to Reverb without queue worker.
        app(ChannelManager::class)->extend('sync_broadcast', function ($app) {
            return new SyncBroadcastChannel($app->make(Broadcaster::class));
        });

        // FCM push notification channel.
        app(ChannelManager::class)->extend('fcm', function ($app) {
            return new FcmChannel();
        });

        // Register AFTER the framework's booted() callback so our route overwrites it.
        // (Routes are indexed by method+URI — last one registered wins.)
        $this->app->booted(function () {
            Route::post('/broadcasting/auth', function (Request $request) {
                $channel = $request->input('channel_name', '');
                $socketId = $request->input('socket_id', '');

                \Log::info('[BroadcastAuth] Request received', [
                    'channel'    => $channel,
                    'socket_id'  => $socketId,
                    'has_csrf'   => $request->hasHeader('X-CSRF-TOKEN'),
                    'user_agent' => $request->userAgent(),
                ]);

                if (str_starts_with($channel, 'private-superadmins.')) {
                    $authed = $request->user('super_admins');
                } elseif (str_starts_with($channel, 'private-admins.')) {
                    $authed = $request->user('admins');
                } else {
                    $authed = $request->user('users');
                }

                \Log::info('[BroadcastAuth] Auth result', [
                    'channel' => $channel,
                    'authed'  => $authed ? get_class($authed) . '#' . $authed->id : 'NULL',
                ]);

                if (!$authed) abort(403);
                $request->setUserResolver(fn () => $authed);

                try {
                    $response = Broadcast::auth($request);
                    \Log::info('[BroadcastAuth] Success', ['channel' => $channel]);
                    return $response;
                } catch (\Throwable $e) {
                    \Log::error('[BroadcastAuth] Broadcast::auth failed', [
                        'channel' => $channel,
                        'error'   => $e->getMessage(),
                    ]);
                    throw $e;
                }
            })->middleware('web')
              ->withoutMiddleware(\Illuminate\Foundation\Http\Middleware\PreventRequestForgery::class);
        });
    }
}
