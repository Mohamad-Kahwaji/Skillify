<?php

namespace Database\Seeders;

use App\Models\ActiveTypebusiness;
use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeedeers extends Seeder
{
    public function run(): void
    {
        $categories = [
            // ── مهنة ────────────────────────────────────
            'مهنة' => [
                ['name_en' => 'Health & Medicine',     'name_ar' => 'الصحة والطب'],
                ['name_en' => 'Law & Legal',           'name_ar' => 'القانون والمحاماة'],
                ['name_en' => 'Engineering',           'name_ar' => 'الهندسة'],
                ['name_en' => 'Education',             'name_ar' => 'التعليم'],
                ['name_en' => 'Accounting & Finance',  'name_ar' => 'المحاسبة والمالية'],
                ['name_en' => 'Information Technology','name_ar' => 'تقنية المعلومات'],
                ['name_en' => 'Art & Design',          'name_ar' => 'الفن والتصميم'],
                ['name_en' => 'Media & Journalism',    'name_ar' => 'الإعلام والصحافة'],
            ],

            // ── حرفة ────────────────────────────────────
            'حرفة' => [
                ['name_en' => 'Construction',          'name_ar' => 'البناء والتشييد'],
                ['name_en' => 'Electrical & Plumbing', 'name_ar' => 'الكهرباء والسباكة'],
                ['name_en' => 'Carpentry & Furniture', 'name_ar' => 'النجارة والأثاث'],
                ['name_en' => 'Blacksmithing & Metals','name_ar' => 'الحدادة والمعادن'],
                ['name_en' => 'Tailoring & Textiles',  'name_ar' => 'الخياطة والنسيج'],
                ['name_en' => 'Cooking & Pastry',      'name_ar' => 'الطباخة والحلويات'],
                ['name_en' => 'Agriculture & Gardening','name_ar' => 'الزراعة والبستنة'],
                ['name_en' => 'Beauty & Care',         'name_ar' => 'التجميل والعناية'],
            ],

            // ── معمل ────────────────────────────────────
            'معمل' => [
                ['name_en' => 'Medical Laboratory',    'name_ar' => 'مختبر طبي'],
                ['name_en' => 'Water Analysis Lab',    'name_ar' => 'مختبر تحليل مياه'],
                ['name_en' => 'Food Laboratory',       'name_ar' => 'معمل أغذية'],
                ['name_en' => 'Chemical Laboratory',   'name_ar' => 'معمل كيميائي'],
                ['name_en' => 'Print Workshop',        'name_ar' => 'معمل طباعة'],
                ['name_en' => 'Computer Workshop',     'name_ar' => 'معمل حاسوب'],
                ['name_en' => 'Photography Studio',    'name_ar' => 'استوديو تصوير'],
                ['name_en' => 'Sewing Workshop',       'name_ar' => 'معمل خياطة'],
            ],

            // ── شركة ────────────────────────────────────
            'شركة' => [
                ['name_en' => 'Trading Company',       'name_ar' => 'شركة تجارية'],
                ['name_en' => 'Contracting Company',   'name_ar' => 'شركة مقاولات'],
                ['name_en' => 'Tech Company',          'name_ar' => 'شركة تقنية'],
                ['name_en' => 'Advertising Company',   'name_ar' => 'شركة إعلانية'],
                ['name_en' => 'Shipping & Transport',  'name_ar' => 'شركة شحن ونقل'],
                ['name_en' => 'Real Estate Company',   'name_ar' => 'شركة عقارية'],
                ['name_en' => 'Import & Export',       'name_ar' => 'شركة استيراد وتصدير'],
                ['name_en' => 'Services Company',      'name_ar' => 'شركة خدمات'],
            ],
        ];

        foreach ($categories as $typeName => $items) {
            $type = ActiveTypebusiness::where('name_ar', $typeName)->first();
            if (!$type) continue;

            foreach ($items as $item) {
                Category::firstOrCreate(
                    ['name_en' => $item['name_en'], 'active_typebusiness_id' => $type->id],
                    ['name_ar' => $item['name_ar']]
                );
            }
        }
    }
}
