<?php

namespace App\Services;

use App\Mail\PasswordOtpMail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class EmailOtpService
{
    public function sendOtp(string $email, string $guard): void
    {
        $code = (string) random_int(100000, 999999);

        DB::table('email_otp_codes')->updateOrInsert(
            ['email' => $email, 'guard' => $guard],
            [
                'code'       => hash('sha256', $code),
                'attempts'   => 0,
                'expires_at' => now()->addMinutes((int) config('email_otp.expires_in')),
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        Mail::to($email)->send(new PasswordOtpMail($code));
    }

    public function verifyOtp(string $email, string $guard, string $code): array
    {
        $record = DB::table('email_otp_codes')
            ->where('email', $email)
            ->where('guard', $guard)
            ->first();

        if (!$record) {
            return ['success' => false, 'message' => 'لا يوجد رمز فعّال، اطلب رمزاً جديداً.'];
        }

        if (now()->greaterThan($record->expires_at)) {
            $this->invalidate($email, $guard);
            return ['success' => false, 'message' => 'انتهت صلاحية الرمز، اطلب رمزاً جديداً.'];
        }

        if ($record->attempts >= (int) config('email_otp.max_attempts')) {
            $this->invalidate($email, $guard);
            return ['success' => false, 'message' => 'تجاوزت الحد الأقصى للمحاولات، اطلب رمزاً جديداً.'];
        }

        if (!hash_equals($record->code, hash('sha256', $code))) {
            DB::table('email_otp_codes')
                ->where('email', $email)
                ->where('guard', $guard)
                ->increment('attempts');

            return ['success' => false, 'message' => 'الرمز غير صحيح.'];
        }

        $this->invalidate($email, $guard);
        return ['success' => true];
    }

    public function invalidate(string $email, string $guard): void
    {
        DB::table('email_otp_codes')
            ->where('email', $email)
            ->where('guard', $guard)
            ->delete();
    }
}
