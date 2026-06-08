<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\Advertisement;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdSeeder extends Seeder
{
    public function run(): void
    {
        // Ensure at least one admin exists for the foreign key
        $admin = Admin::first() ?? Admin::create([
            'id_number'  => '0000000000',
            'first_name' => 'System',
            'last_name'  => 'Admin',
            'email'      => 'admin@hirfa.test',
            'password'   => Hash::make('password'),
            'phone'      => '+963000000000',
            'role'       => 'admin',
        ]);

        $adminId = $admin->id;
        $today   = now();

        $ads = [
            // ── Active, no dates (always running) ─────────────────────────
            [
                'title'        => 'خدمات الكهرباء المنزلية',
                'description'  => 'احصل على أفضل خدمات الكهرباء المنزلية والتجارية بأسعار تنافسية. فريق متخصص وخبرة أكثر من 10 سنوات.',
                'company_name' => 'شركة الإضاءة العالمية',
                'image'        => 'https://images.unsplash.com/photo-1621905251918-48416bd8575a?w=600&h=400&fit=crop',
                'start_date'   => null,
                'end_date'     => null,
                'status'       => 'approved',
            ],
            [
                'title'        => 'أثاث وديكورات فاخرة',
                'description'  => 'أحدث تصاميم الأثاث الفاخر لمنزلك وشركتك. توصيل مجاني وضمان سنتين على جميع المنتجات.',
                'company_name' => 'بيت الأثاث الحديث',
                'image'        => 'https://images.unsplash.com/photo-1555041469-a586c61ea9bc?w=600&h=400&fit=crop',
                'start_date'   => null,
                'end_date'     => null,
                'status'       => 'approved',
            ],

            // ── Active, within date range ──────────────────────────────────
            [
                'title'        => 'عروض رمضان الخاصة',
                'description'  => 'خصومات تصل إلى 50% على جميع خدمات النظافة والصيانة المنزلية طوال شهر رمضان المبارك.',
                'company_name' => 'خدمات النظافة المتميزة',
                'image'        => 'https://images.unsplash.com/photo-1558618666-fcd25c85cd64?w=600&h=400&fit=crop',
                'start_date'   => $today->copy()->subDays(10)->toDateString(),
                'end_date'     => $today->copy()->addDays(20)->toDateString(),
                'status'       => 'approved',
            ],
            [
                'title'        => 'صيانة أجهزة التكييف',
                'description'  => 'استعد لفصل الصيف مع خدمة الصيانة الشاملة لمكيفاتك. فحص مجاني للوحدات الجديدة.',
                'company_name' => 'تقنية التبريد المتطور',
                'image'        => 'https://images.unsplash.com/photo-1631193817605-a81a670fd8f2?w=600&h=400&fit=crop',
                'start_date'   => $today->copy()->subDays(5)->toDateString(),
                'end_date'     => $today->copy()->addDays(45)->toDateString(),
                'status'       => 'approved',
            ],
            [
                'title'        => 'مدرسة البرمجة والتصميم',
                'description'  => 'تعلم البرمجة وتصميم المواقع من الصفر مع مدربين محترفين. دورات مكثفة ومرنة تناسب جدولك.',
                'company_name' => 'أكاديمية كود',
                'image'        => 'https://images.unsplash.com/photo-1516321318423-f06f85e504b3?w=600&h=400&fit=crop',
                'start_date'   => $today->copy()->subDays(2)->toDateString(),
                'end_date'     => $today->copy()->addDays(60)->toDateString(),
                'status'       => 'approved',
            ],

            // ── Ending soon (within 3 days) ────────────────────────────────
            [
                'title'        => 'تخفيضات نهاية الموسم',
                'description'  => 'آخر فرصة للاستفادة من عروض نهاية الموسم! خصم 30% على خدمات السباكة والتمديدات.',
                'company_name' => 'السباك الماهر',
                'image'        => 'https://images.unsplash.com/photo-1504328345606-18bbc8c9d7d1?w=600&h=400&fit=crop',
                'start_date'   => $today->copy()->subDays(27)->toDateString(),
                'end_date'     => $today->copy()->addDays(2)->toDateString(),
                'status'       => 'approved',
            ],
            [
                'title'        => 'عرض محدود: دهان وديكور',
                'description'  => 'عرض حصري وشيك على الانتهاء! طلاء مهني بأفضل الأصباغ العالمية. اتصل الآن قبل فوات الأوان.',
                'company_name' => 'فن الألوان',
                'image'        => 'https://images.unsplash.com/photo-1562259929-b4e1fd3aef09?w=600&h=400&fit=crop',
                'start_date'   => $today->copy()->subDays(28)->toDateString(),
                'end_date'     => $today->copy()->addDay()->toDateString(),
                'status'       => 'approved',
            ],

            // ── Future / upcoming (starts later) ──────────────────────────
            [
                'title'        => 'افتتاح معرض الحرف اليدوية',
                'description'  => 'يسعدنا دعوتكم لحضور معرضنا السنوي للحرف اليدوية والمنتجات التراثية. دخول مجاني للجميع.',
                'company_name' => 'جمعية الحرف التقليدية',
                'image'        => 'https://images.unsplash.com/photo-1513475382585-d06e58bcb0e0?w=600&h=400&fit=crop',
                'start_date'   => $today->copy()->addDays(7)->toDateString(),
                'end_date'     => $today->copy()->addDays(14)->toDateString(),
                'status'       => 'approved',
            ],

            // ── Pending (awaiting review) ──────────────────────────────────
            [
                'title'        => 'خدمة توصيل السريع',
                'description'  => 'توصيل في غضون ساعتين لأي موقع داخل المدينة. تتبع حي للطلب وضمان سلامة المنتج.',
                'company_name' => 'سرعة للتوصيل',
                'image'        => 'https://images.unsplash.com/photo-1578575437130-527eed3abbec?w=600&h=400&fit=crop',
                'start_date'   => $today->copy()->addDays(3)->toDateString(),
                'end_date'     => $today->copy()->addDays(33)->toDateString(),
                'status'       => 'pending',
            ],
            [
                'title'        => 'شاليهات للإيجار الصيفي',
                'description'  => 'استمتع بإجازتك الصيفية في شاليهاتنا المجهزة بالكامل على شاطئ البحر. حجز مبكر يوفر 20%.',
                'company_name' => 'ريزورت البحر الأزرق',
                'image'        => 'https://images.unsplash.com/photo-1571896349842-33c89424de2d?w=600&h=400&fit=crop',
                'start_date'   => null,
                'end_date'     => null,
                'status'       => 'pending',
            ],
        ];

        foreach ($ads as $ad) {
            Advertisement::create(array_merge($ad, ['admin_id' => $adminId]));
        }

        $this->command->info('✓ ' . count($ads) . ' advertisements seeded.');
    }
}
