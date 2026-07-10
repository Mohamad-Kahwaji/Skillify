<?php

namespace App\Http\Controllers\Auth_SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\SuperAdmin;
use App\Services\EmailOtpService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ForgotPasswordController extends Controller
{
    // ── المرحلة 1: إدخال الإيميل ─────────────────────────────
    public function showForgotPassword()
    {
        return view('auth.super_admin.forgot-password');
    }

    public function sendOtp(Request $request, EmailOtpService $otp)
    {
        $request->validate([
            'email' => 'required|email|exists:super_admins,email',
        ]);

        $otp->sendOtp($request->email, 'super_admins');

        $request->session()->put('sa_reset_email', $request->email);
        $request->session()->forget('sa_reset_verified');

        return redirect()->route('super_admin.verify-otp')
            ->with('status', 'تم إرسال رمز التحقق إلى بريدك الإلكتروني.');
    }

    // ── المرحلة 2: التحقق من الكود ───────────────────────────
    public function showVerifyOtp(Request $request)
    {
        if (!$request->session()->has('sa_reset_email')) {
            return redirect()->route('super_admin.forgot-password');
        }
        return view('auth.super_admin.verify-otp', [
            'email' => $request->session()->get('sa_reset_email'),
        ]);
    }

    public function verifyOtp(Request $request, EmailOtpService $otp)
    {
        $email = $request->session()->get('sa_reset_email');
        if (!$email) {
            return redirect()->route('super_admin.forgot-password');
        }

        $request->validate(['code' => 'required|digits:6']);

        $result = $otp->verifyOtp($email, 'super_admins', $request->code);

        if (!$result['success']) {
            return back()->withErrors(['code' => $result['message']]);
        }

        $request->session()->put('sa_reset_verified', true);

        return redirect()->route('super_admin.reset-password');
    }

    // ── المرحلة 3: كلمة المرور الجديدة ───────────────────────
    public function showResetPassword(Request $request)
    {
        if (!$request->session()->get('sa_reset_verified')) {
            return redirect()->route('super_admin.forgot-password');
        }
        return view('auth.super_admin.reset-password');
    }

    public function resetPassword(Request $request)
    {
        $email = $request->session()->get('sa_reset_email');

        if (!$email || !$request->session()->get('sa_reset_verified')) {
            return redirect()->route('super_admin.forgot-password');
        }

        $request->validate(['password' => 'required|min:8|confirmed']);

        SuperAdmin::where('email', $email)
            ->update(['password' => Hash::make($request->password)]);

        $request->session()->forget(['sa_reset_email', 'sa_reset_verified']);

        return redirect()->route('super_admin.login')
            ->with('status', 'تم تغيير كلمة المرور بنجاح، سجّل الدخول الآن.');
    }
}
