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
    public function handle(Request $request, Closure $next): Response
    {
        $password = $request->admin_password;
        if(!$password){
            return redirect()->back()->withErrors(['admin_password' => 'Password is required.']);
        }
        if (!Hash::check($password,Auth::guard('admins')->password)) {
            return redirect()->back()->withErrors(['admin_password' => 'Incorrect password.']);
        }
        return $next($request);
    }
}
