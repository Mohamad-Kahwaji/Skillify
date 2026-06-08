<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class HasBusinessMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user     = auth('users')->user();
        $business = $user?->businesses;

        if (!$business) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'يجب أن يكون لديك حساب أعمال نشط.'], 403);
            }
            return redirect()->route('user.profile', ['tab' => 'business'])
                ->with('error', 'يجب أن يكون لديك حساب أعمال نشط لإضافة الخدمات.');
        }

        return $next($request);
    }
}
