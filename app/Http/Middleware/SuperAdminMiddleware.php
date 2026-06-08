<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class SuperAdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::guard('super_admins')->check()) {
            return redirect()->route('super_admin.login');
        }
        Auth::setDefaultDriver('super_admins');
        return $next($request);
    }
}
