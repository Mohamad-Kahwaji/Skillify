<?php

namespace App\Http\Controllers\Auth_User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLogin()
    {
        if (Auth::guard('users')->check()) {
            return redirect()->route('user.dashboard');
        }
        return view('auth.user.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'phone'    => 'required|string',
            'password' => 'required',
        ]);

        if (Auth::guard('users')->attempt([
            'phone'    => $request->phone,
            'password' => $request->password,
        ])) {
            $request->session()->regenerate();
            return redirect()->intended(route('user.dashboard'));
        }

        return back()->withErrors([
            'phone' => 'رقم الهاتف أو كلمة المرور غير صحيحة.',
        ])->withInput($request->only('phone'));
    }


}
