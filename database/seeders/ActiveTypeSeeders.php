<?php

namespace Database\Seeders;

use App\Models\ActiveType;
use Illuminate\Database\Seeder;

class ActiveTypeSeeders extends Seeder
{
    public function run(): void
    {
        $types = [
            // ── بيع وشراء ──────────────────────────────
            ['name_en' => 'Tool Sale',         'name_ar' => 'بيع أدوات'],
            ['name_en' => 'Tool Purchase',     'name_ar' => 'شراء أدوات'],
            ['name_en' => 'Equipment Sale',    'name_ar' => 'بيع معدات'],
            ['name_en' => 'Equipment Purchase','name_ar' => 'شراء معدات'],
            ['name_en' => 'Material Sale',     'name_ar' => 'بيع مواد'],

            // ── عرض وطلب خدمات ─────────────────────────
            ['name_en' => 'Service Offer',     'name_ar' => 'عرض خدمة'],
            ['name_en' => 'Service Request',   'name_ar' => 'طلب خدمة'],
            ['name_en' => 'Maintenance Offer', 'name_ar' => 'عرض صيانة'],
            ['name_en' => 'Maintenance Request','name_ar' => 'طلب صيانة'],
            ['name_en' => 'Installation Offer','name_ar' => 'عرض تركيب'],

            // ── سوق العمل ───────────────────────────────
            ['name_en' => 'Job Offer',         'name_ar' => 'عرض عمل'],
            ['name_en' => 'Job Seeking',       'name_ar' => 'طلب عمل'],
            ['name_en' => 'Partnership',       'name_ar' => 'شراكة'],
            ['name_en' => 'Subcontracting',    'name_ar' => 'مقاولة من الباطن'],

            // ── تعليم ومشاركة ───────────────────────────
            ['name_en' => 'Training Offer',    'name_ar' => 'عرض تدريب'],
            ['name_en' => 'Tip & Tutorial',    'name_ar' => 'نصيحة ومشاركة خبرة'],
            ['name_en' => 'Project Showcase',  'name_ar' => 'عرض مشروع منجز'],

            // ── عام ─────────────────────────────────────
            ['name_en' => 'Announcement',      'name_ar' => 'إعلان عام'],
            ['name_en' => 'Question',          'name_ar' => 'سؤال'],
        ];

        foreach ($types as $type) {
            ActiveType::firstOrCreate(
                ['name_en' => $type['name_en']],
                ['name_ar' => $type['name_ar']]
            );
        }
    }
}
