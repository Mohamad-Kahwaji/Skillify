<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class ForgotPasswordController extends Controller
{
    public function showForgotPassword()
    {
        return view('auth.admin.forgot-password');
    }

    public function sendResetLinkEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:admins,email',
        ]);

        $token = Str::random(64);

        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $request->email],
            ['token' => hash('sha256', $token), 'created_at' => now()]
        );

        Mail::send('auth.admin.emails.reset-password', ['token' => $token], function ($mail) use ($request) {
            $mail->to($request->email)->subject('Reset Admin Password');
        });

        return back()->with('status', 'تم إرسال رابط إعادة التعيين إلى بريدك الإلكتروني.');
    }

    public function showResetForm(string $token)
    {
        return view('auth.admin.reset-password', compact('token'));
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'email'    => 'required|email|exists:admins,email',
            'password' => 'required|min:8|confirmed',
            'token'    => 'required',
        ]);

        $record = DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->first();

        if (!$record || $record->token !== hash('sha256', $request->token)) {
            return back()->withErrors(['token' => 'الرابط غير صالح أو منتهي الصلاحية.']);
        }

        if (now()->diffInMinutes($record->created_at) > 60) {
            return back()->withErrors(['token' => 'انتهت صلاحية الرابط، أعد الطلب.']);
        }

        Admin::where('email', $request->email)
            ->update(['password' => Hash::make($request->password)]);

        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        return redirect()->route('admin.login')->with('status', 'تم تغيير كلمة المرور بنجاح.');
    }
}
