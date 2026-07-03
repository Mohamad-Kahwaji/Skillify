<?php

namespace App\Http\Middleware;

use App\Models\Business;
use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determines the current asset version.
     *
     * @see https://inertiajs.com/asset-versioning
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @see https://inertiajs.com/shared-data
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        $user       = $request->user('users');
        $admin      = $request->user('admins');
        $superAdmin = $request->user('super_admins');

        return [
            ...parent::share($request),
            'auth' => [
                'user' => $user ? [
                    'id'         => $user->id,
                    'first_name' => $user->first_name,
                    'last_name'  => $user->last_name,
                    'email'      => $user->email,
                    'phone'      => $user->phone,
                ] : null,
                'admin' => $admin ? [
                    'id'         => $admin->id,
                    'first_name' => $admin->first_name,
                    'last_name'  => $admin->last_name,
                    'email'      => $admin->email,
                    'role'       => $admin->role,
                ] : ($superAdmin ? [
                    'id'         => $superAdmin->id,
                    'first_name' => $superAdmin->first_name ?? 'Super',
                    'last_name'  => $superAdmin->last_name  ?? 'Admin',
                    'email'      => $superAdmin->email,
                    'role'       => 'super_admin',
                ] : null),
            ],
            'flash' => [
                'success' => $request->session()->get('success'),
                'error'   => $request->session()->get('error'),
            ],
            'badges' => [
                'pending_businesses' => ($admin || $superAdmin)
                    ? Business::where('status', 'pending')->count()
                    : 0,
                'unread_notifications' => $admin
                    ? $admin->unreadNotifications()->count()
                    : ($superAdmin
                        ? $superAdmin->unreadNotifications()->count()
                        : ($user ? $user->unreadNotifications()->count() : 0)),
            ],
        ];
    }
}
