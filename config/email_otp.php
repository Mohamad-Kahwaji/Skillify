<?php

return [

    // مدة صلاحية الكود بالدقائق
    'expires_in'   => env('EMAIL_OTP_EXPIRES_IN', 10),

    // أقصى عدد محاولات إدخال خاطئة قبل إلغاء الكود
    'max_attempts' => env('EMAIL_OTP_MAX_ATTEMPTS', 5),

];
