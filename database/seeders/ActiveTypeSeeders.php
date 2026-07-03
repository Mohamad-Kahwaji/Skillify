<?php

namespace Database\Seeders;

use App\Models\ActiveType;
use Illuminate\Database\Seeder;

class ActiveTypeSeeders extends Seeder
{
    public function run(): void
    {
        $types = [
            // بيع وشراء
            'بيع أدوات',
            'شراء أدوات',
            'بيع معدات',
            'شراء معدات',
            'بيع مواد',

            // عرض وطلب خدمات
            'عرض خدمة',
            'طلب خدمة',
            'عرض صيانة',
            'طلب صيانة',
            'عرض تركيب',

            // سوق العمل
            'عرض عمل',
            'طلب عمل',
            'شراكة',
            'مقاولة من الباطن',

            // تعليم ومشاركة
            'عرض تدريب',
            'نصيحة ومشاركة خبرة',
            'عرض مشروع منجز',

            // عام
            'إعلان عام',
            'سؤال',
        ];

        foreach ($types as $name) {
            ActiveType::firstOrCreate(['name' => $name]);
        }
    }
}
