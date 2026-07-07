<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class WhatsAppOtpService
{
    public function sendOtp(string $phone): void
{
    // 1) توليد كود عشوائي من 6 أرقام (بيسمح بأصفار بالبداية متل 042318)
    $code = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);

    // 2) تخزين الـ hash تبعه (مش الكود نفسه) مع صلاحية وتصفير المحاولات
    DB::table('password_otp_codes')->updateOrInsert(
        ['phone' => $phone],
        [
            'code_hash'   => Hash::make($code),
            'expires_at'  => now()->addMinutes(config('whatsapp.otp_expires_minutes')),
            'attempts'    => 0,
            'verified_at' => null,
            'created_at'  => now(),
            'updated_at'  => now(),
        ]
    );

    // 3) الإرسال حسب الـ driver
    $this->deliver($phone, $code);
}

private function deliver(string $phone, string $code): void
{
    $driver = config('whatsapp.driver');

    if ($driver === 'log') {
        Log::info("WhatsApp OTP for {$phone}: {$code}");
        return;
    }

    // driver === 'api' : الربط مع WhatsApp Cloud API (Future Prospect)
    Log::warning("WhatsApp API driver not implemented yet. OTP for {$phone} was not sent.");
}


public function verifyOtp(string $phone, string $code): array
{
    $record = DB::table('password_otp_codes')
        ->where('phone', $phone)
        ->first();

    // 1) ما في كود مطلوب أصلاً لهالرقم
    if (!$record) {
        return ['ok' => false, 'message' => 'لم يتم طلب رمز تحقق لهذا الرقم.'];
    }

    // 2) انتهت الصلاحية
    if (now()->greaterThan($record->expires_at)) {
        return ['ok' => false, 'message' => 'انتهت صلاحية الرمز، اطلب رمزاً جديداً.'];
    }

    // 3) تجاوز عدد المحاولات
    if ($record->attempts >= config('whatsapp.otp_max_attempts')) {
        return ['ok' => false, 'message' => 'تجاوزت عدد المحاولات المسموح، اطلب رمزاً جديداً.'];
    }

    // 4) الكود غلط → زيادة العداد
    if (!Hash::check($code, $record->code_hash)) {
        DB::table('password_otp_codes')
            ->where('phone', $phone)
            ->increment('attempts');

        return ['ok' => false, 'message' => 'الرمز غير صحيح.'];
    }

    // 5) الكود صح → تعليم التحقق
    DB::table('password_otp_codes')
        ->where('phone', $phone)
        ->update(['verified_at' => now(), 'updated_at' => now()]);

    return ['ok' => true, 'message' => 'تم التحقق بنجاح.'];
}

public function invalidate(string $phone): void
{
    DB::table('password_otp_codes')->where('phone', $phone)->delete();
}
}
