<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Services\EmailOtpService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ForgotPasswordController extends Controller
{
    // ── المرحلة 1: إدخال الإيميل ─────────────────────────────
    public function showForgotPassword()
    {
        return view('auth.admin.forgot-password');
    }

    public function sendOtp(Request $request, EmailOtpService $otp)
    {
        $request->validate([
            'email' => 'required|email|exists:admins,email',
        ], [
            'email.required' => 'البريد الإلكتروني مطلوب.',
            'email.email'    => 'صيغة البريد الإلكتروني غير صحيحة.',
            'email.exists'   => 'لا يوجد حساب مسجّل بهذا البريد الإلكتروني.',
        ]);

        $otp->sendOtp($request->email, 'admins');

        $request->session()->put('admin_reset_email', $request->email);
        $request->session()->forget('admin_reset_verified');

        return redirect()->route('admin.verify-otp')
            ->with('status', 'تم إرسال رمز التحقق إلى بريدك الإلكتروني.');
    }

    // ── المرحلة 2: التحقق من الكود ───────────────────────────
    public function showVerifyOtp(Request $request)
    {
        if (!$request->session()->has('admin_reset_email')) {
            return redirect()->route('admin.forgot-password');
        }
        return view('auth.admin.verify-otp', [
            'email' => $request->session()->get('admin_reset_email'),
        ]);
    }

    public function verifyOtp(Request $request, EmailOtpService $otp)
    {
        $email = $request->session()->get('admin_reset_email');
        if (!$email) {
            return redirect()->route('admin.forgot-password');
        }

        $request->validate(['code' => 'required|digits:6'], [
            'code.required' => 'رمز التحقق مطلوب.',
            'code.digits'   => 'رمز التحقق يجب أن يتكون من 6 أرقام.',
        ]);

        $result = $otp->verifyOtp($email, 'admins', $request->code);

        if (!$result['success']) {
            return back()->withErrors(['code' => $result['message']]);
        }

        $request->session()->put('admin_reset_verified', true);

        return redirect()->route('admin.reset-password');
    }

    // ── المرحلة 3: كلمة المرور الجديدة ───────────────────────
    public function showResetPassword(Request $request)
    {
        if (!$request->session()->get('admin_reset_verified')) {
            return redirect()->route('admin.forgot-password');
        }
        return view('auth.admin.reset-password');
    }

    public function resetPassword(Request $request)
    {
        $email = $request->session()->get('admin_reset_email');

        if (!$email || !$request->session()->get('admin_reset_verified')) {
            return redirect()->route('admin.forgot-password');
        }

        $request->validate(['password' => 'required|min:8|confirmed'], [
            'password.required'  => 'كلمة المرور مطلوبة.',
            'password.min'       => 'كلمة المرور يجب أن تكون 8 أحرف على الأقل.',
            'password.confirmed' => 'كلمتا المرور غير متطابقتين.',
        ]);

        Admin::where('email', $email)
            ->update(['password' => Hash::make($request->password)]);

        $request->session()->forget(['admin_reset_email', 'admin_reset_verified']);

        return redirect()->route('admin.login')
            ->with('status', 'تم تغيير كلمة المرور بنجاح، سجّل الدخول الآن.');
    }
}
