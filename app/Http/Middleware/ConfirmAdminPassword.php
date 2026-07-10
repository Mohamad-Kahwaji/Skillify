<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;

class ConfirmAdminPassword
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next, string $guard = 'admins'): Response
    {
        $password = $request->input('current_password');

        if (!$password) {
            return redirect()->back()->withErrors([
                'current_password' => 'كلمة المرور مطلوبة لتأكيد العملية.',
            ]);
        }

        $account = Auth::guard($guard)->user();

        if (!$account || !Hash::check($password, $account->password)) {
            return redirect()->back()->withErrors([
                'current_password' => 'كلمة المرور غير صحيحة.',
            ]);
        }

        return $next($request);
    }
}
