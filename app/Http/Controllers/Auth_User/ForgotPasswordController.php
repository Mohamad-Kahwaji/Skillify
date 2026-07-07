<?php

namespace App\Http\Controllers\Auth_User;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\WhatsAppOtpService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;

class ForgotPasswordController extends Controller
{
    public function __construct(
        private WhatsAppOtpService $otpService
    ) {}

    // ── المرحلة 1: إدخال الرقم ──────────────────────────────

    public function showForgotPassword()
    {
        return Inertia::render('Auth/ForgotPassword');
    }

    public function sendOtp(Request $request)
    {
        $request->validate([
            'phone' => 'required|string|exists:users,phone',
        ], [
            'phone.required' => 'رقم الهاتف مطلوب.',
            'phone.exists'   => 'هذا الرقم غير مسجل لدينا.',
        ]);

        $this->otpService->sendOtp($request->phone);

        // نخزن الرقم بالسيشن حتى ما ينبعت من الواجهة بالخطوات الجاية
        $request->session()->put('otp_phone', $request->phone);

        return redirect()->route('user.verify-otp');
    }

    // ── المرحلة 2: التحقق من الكود ──────────────────────────

    public function showVerifyOtp(Request $request)
    {
        // إذا فات عالصفحة بدون ما يطلب كود → رجّعه للبداية
        if (!$request->session()->has('otp_phone')) {
            return redirect()->route('user.forgot-password');
        }

        return Inertia::render('Auth/VerifyOtp', [
            'phone' => $request->session()->get('otp_phone'),
        ]);
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'code' => 'required|digits:6',
        ], [
            'code.required' => 'رمز التحقق مطلوب.',
            'code.digits'   => 'رمز التحقق يجب أن يتكون من 6 أرقام.',
        ]);

        $phone = $request->session()->get('otp_phone');

        if (!$phone) {
            return redirect()->route('user.forgot-password');
        }

        $result = $this->otpService->verifyOtp($phone, $request->code);

        if (!$result['ok']) {
            throw ValidationException::withMessages([
                'code' => $result['message'],
            ]);
        }

        // نجح التحقق → توكن عشوائي بالسيشن بيفتح مرحلة تغيير الباسورد
        $request->session()->put('otp_reset_token', Str::random(64));

        return redirect()->route('user.reset-password');
    }

    // ── المرحلة 3: كلمة المرور الجديدة ──────────────────────

    public function showResetForm(Request $request)
    {
        // ممنوع يوصل لهون إلا إذا تحقق بنجاح
        if (!$request->session()->has('otp_reset_token')) {
            return redirect()->route('user.forgot-password');
        }

        return Inertia::render('Auth/ResetPassword');
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'password' => 'required|min:8|confirmed',
        ], [
            'password.required'  => 'كلمة المرور مطلوبة.',
            'password.min'       => 'كلمة المرور يجب أن تكون 8 أحرف على الأقل.',
            'password.confirmed' => 'كلمتا المرور غير متطابقتين.',
        ]);

        $phone = $request->session()->get('otp_phone');
        $token = $request->session()->get('otp_reset_token');

        if (!$phone || !$token) {
            return redirect()->route('user.forgot-password');
        }

        User::where('phone', $phone)
            ->update(['password' => Hash::make($request->password)]);

        // تنظيف: حذف الكود من الداتابيس ومسح السيشن
        $this->otpService->invalidate($phone);
        $request->session()->forget(['otp_phone', 'otp_reset_token']);

        return redirect()->route('user.login')
            ->with('status', 'تم تغيير كلمة المرور بنجاح، سجّل دخولك.');
    }
}
