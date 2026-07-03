<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ForgotPasswordController extends Controller
{
    public function showForgotPassword()
    {
        return view('auth.admin.forgot-password');
    }

    public function sendResetLinkEmail(Request $request)
    {
        $request->validate([
            'email'    => 'required|email|exists:admins,email',
            'password' => 'required|min:8|confirmed',
        ]);

        Admin::where('email', $request->email)
            ->update(['password' => Hash::make($request->password)]);

        return redirect()->route('admin.login')
            ->with('status', 'تم تغيير كلمة المرور بنجاح، يمكنك تسجيل الدخول الآن.');
    }
}
