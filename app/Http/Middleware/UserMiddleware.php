<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class UserMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::guard('users')->check()) {
            return redirect()->route('user.login');
        }

        $user = Auth::guard('users')->user();

        if ($user->status === 'inactive') {
            Auth::guard('users')->logout();
            return redirect()->route('user.login')->withErrors(['account' => 'حسابك موقوف، تواصل مع الدعم.']);
        }

        // Auto-assign default role if user has none
        if ($user->roles->isEmpty()) {
            try {
                $user->syncBusinessRole();
            } catch (\Exception $e) {
                // Roles not seeded yet — continue without assigning
            }
        }

        Auth::setDefaultDriver('users');
        return $next($request);
    }
}
