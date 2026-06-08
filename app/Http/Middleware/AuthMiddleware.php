<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AuthMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Super admins can access admin routes without restriction
        if (Auth::guard('super_admins')->check()) {
            Auth::setDefaultDriver('super_admins');
            return $next($request);
        }

        if (!Auth::guard('admins')->check()) {
            return redirect()->route('admin.login');
        }

        if (Auth::guard('admins')->user()->status === 'inactive') {
            Auth::guard('admins')->logout();
            return redirect()->route('admin.login')->withErrors(['account' => 'حسابك موقوف، تواصل مع الدعم.']);
        }

        Auth::setDefaultDriver('admins');
        return $next($request);
    }
}
