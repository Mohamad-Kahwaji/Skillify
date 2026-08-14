<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlatformSetting extends Model
{
    protected $fillable = [
        'whatsapp_number',
        'contact_email',
        'contact_phone',
        'about_title',
        'about_body',
    ];

    public static function current(): self
    {
        return static::firstOrCreate(['id' => 1], [
            'whatsapp_number' => '+963995227120',
            'contact_phone'   => '+963995227120',
            'contact_email'   => 'support@skillify.sy',
            'about_title'     => 'عن منصة Skillify',
            'about_body'      => 'Skillify منصة تربط العملاء بالمهنيين وأصحاب الأعمال، لتسهيل اكتشاف الخدمات، التواصل المباشر، وعرض الفرص والإعلانات.',
        ]);
    }

    public function whatsappUrl(string $message = ''): string
    {
        $number = preg_replace('/\D+/', '', $this->whatsapp_number ?? '963995227120');
        return 'https://wa.me/' . $number . ($message ? '?text=' . rawurlencode($message) : '');
    }
}
