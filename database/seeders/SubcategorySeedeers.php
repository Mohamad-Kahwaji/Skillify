<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Subcategory;
use Illuminate\Database\Seeder;

class SubcategorySeedeers extends Seeder
{
    public function run(): void
    {
        $subcategories = [
            // ── مهنة ────────────────────────────────────
            'الصحة والطب' => [
                ['name_en' => 'General Doctor',    'name_ar' => 'طبيب عام'],
                ['name_en' => 'Dentist',           'name_ar' => 'طبيب أسنان'],
                ['name_en' => 'Pharmacist',        'name_ar' => 'صيدلاني'],
                ['name_en' => 'Nurse',             'name_ar' => 'ممرض / ممرضة'],
                ['name_en' => 'Psychiatrist',      'name_ar' => 'طبيب نفسي'],
                ['name_en' => 'Ophthalmologist',   'name_ar' => 'طبيب عيون'],
            ],
            'القانون والمحاماة' => [
                ['name_en' => 'Lawyer',            'name_ar' => 'محامي'],
                ['name_en' => 'Legal Advisor',     'name_ar' => 'مستشار قانوني'],
                ['name_en' => 'Judge',             'name_ar' => 'قاضي'],
                ['name_en' => 'Notary',            'name_ar' => 'موثق'],
            ],
            'الهندسة' => [
                ['name_en' => 'Civil Engineer',      'name_ar' => 'مهندس مدني'],
                ['name_en' => 'Electrical Engineer', 'name_ar' => 'مهندس كهربائي'],
                ['name_en' => 'Mechanical Engineer', 'name_ar' => 'مهندس ميكانيكي'],
                ['name_en' => 'Architect',           'name_ar' => 'مهندس معماري'],
                ['name_en' => 'Software Engineer',   'name_ar' => 'مهندس برمجيات'],
            ],
            'التعليم' => [
                ['name_en' => 'Teacher',               'name_ar' => 'معلم'],
                ['name_en' => 'University Professor',  'name_ar' => 'أستاذ جامعي'],
                ['name_en' => 'Trainer',               'name_ar' => 'مدرب'],
                ['name_en' => 'Educational Counselor', 'name_ar' => 'مرشد تربوي'],
            ],
            'المحاسبة والمالية' => [
                ['name_en' => 'Accountant',        'name_ar' => 'محاسب'],
                ['name_en' => 'Auditor',           'name_ar' => 'مدقق حسابات'],
                ['name_en' => 'Financial Advisor', 'name_ar' => 'مستشار مالي'],
                ['name_en' => 'Financial Analyst', 'name_ar' => 'محلل مالي'],
            ],
            'تقنية المعلومات' => [
                ['name_en' => 'Web Developer',        'name_ar' => 'مطور ويب'],
                ['name_en' => 'Graphic Designer',     'name_ar' => 'مصمم جرافيك'],
                ['name_en' => 'Information Security', 'name_ar' => 'أمن معلومات'],
                ['name_en' => 'Network Administrator','name_ar' => 'مدير شبكات'],
            ],
            'الفن والتصميم' => [
                ['name_en' => 'Photographer',      'name_ar' => 'مصور فوتوغرافي'],
                ['name_en' => 'Painter',           'name_ar' => 'رسام'],
                ['name_en' => 'Musician',          'name_ar' => 'موسيقي'],
                ['name_en' => 'Interior Designer', 'name_ar' => 'مصمم داخلي'],
            ],
            'الإعلام والصحافة' => [
                ['name_en' => 'Journalist',   'name_ar' => 'صحفي'],
                ['name_en' => 'Broadcaster',  'name_ar' => 'مذيع'],
                ['name_en' => 'Video Editor', 'name_ar' => 'مونتير'],
                ['name_en' => 'Writer',       'name_ar' => 'كاتب'],
            ],

            // ── حرفة ────────────────────────────────────
            'البناء والتشييد' => [
                ['name_en' => 'Mason',     'name_ar' => 'بنّاء'],
                ['name_en' => 'Painter',   'name_ar' => 'دهان'],
                ['name_en' => 'Tiler',     'name_ar' => 'مبلط'],
                ['name_en' => 'Excavator', 'name_ar' => 'حفار'],
            ],
            'الكهرباء والسباكة' => [
                ['name_en' => 'Electrician',        'name_ar' => 'كهربائي'],
                ['name_en' => 'Plumber',            'name_ar' => 'سباك'],
                ['name_en' => 'Heating Technician', 'name_ar' => 'تقني تدفئة'],
                ['name_en' => 'AC Technician',      'name_ar' => 'تقني تكييف'],
            ],
            'النجارة والأثاث' => [
                ['name_en' => 'Carpenter',       'name_ar' => 'نجار'],
                ['name_en' => 'Furniture Maker', 'name_ar' => 'صانع أثاث'],
                ['name_en' => 'Upholsterer',     'name_ar' => 'منجد'],
                ['name_en' => 'Wood Decorator',  'name_ar' => 'ديكور خشبي'],
            ],
            'الحدادة والمعادن' => [
                ['name_en' => 'Blacksmith',          'name_ar' => 'حداد'],
                ['name_en' => 'Welder',              'name_ar' => 'لحام'],
                ['name_en' => 'Metal Door Maker',    'name_ar' => 'صانع أبواب معدنية'],
                ['name_en' => 'Construction Smith',  'name_ar' => 'حداد بناء'],
            ],
            'الخياطة والنسيج' => [
                ['name_en' => "Men's Tailor",   'name_ar' => 'خياط رجالي'],
                ['name_en' => "Women's Tailor", 'name_ar' => 'خياطة نسائية'],
                ['name_en' => 'Weaver',         'name_ar' => 'نساج'],
                ['name_en' => 'Embroidery',     'name_ar' => 'تطريز'],
            ],
            'الطباخة والحلويات' => [
                ['name_en' => 'Chef',         'name_ar' => 'طباخ'],
                ['name_en' => 'Pastry Chef',  'name_ar' => 'حلواني'],
                ['name_en' => 'Bakery',       'name_ar' => 'خبز وفطائر'],
                ['name_en' => 'Home Cooking', 'name_ar' => 'طباخة منزلية'],
            ],
            'الزراعة والبستنة' => [
                ['name_en' => 'Farmer',         'name_ar' => 'مزارع'],
                ['name_en' => 'Gardener',       'name_ar' => 'بستاني'],
                ['name_en' => 'Animal Breeder', 'name_ar' => 'مربي حيوانات'],
                ['name_en' => 'Tree Planting',  'name_ar' => 'تشجير'],
            ],
            'التجميل والعناية' => [
                ['name_en' => 'Barber',            'name_ar' => 'حلاق'],
                ['name_en' => 'Hair Stylist',      'name_ar' => 'مصفف شعر'],
                ['name_en' => 'Beautician',        'name_ar' => 'خبير تجميل'],
                ['name_en' => 'Skincare Specialist','name_ar' => 'خبير عناية بالبشرة'],
            ],

            // ── معمل ────────────────────────────────────
            'مختبر طبي' => [
                ['name_en' => 'Blood Analysis',    'name_ar' => 'تحليل دم'],
                ['name_en' => 'Urine Analysis',    'name_ar' => 'تحليل بول'],
                ['name_en' => 'Hormonal Tests',    'name_ar' => 'تحاليل هرمونية'],
                ['name_en' => 'Bacterial Tests',   'name_ar' => 'تحاليل بكتيرية'],
            ],
            'مختبر تحليل مياه' => [
                ['name_en' => 'Drinking Water Analysis',    'name_ar' => 'تحليل مياه الشرب'],
                ['name_en' => 'Sewage Water Analysis',      'name_ar' => 'تحليل مياه الصرف'],
                ['name_en' => 'Agricultural Water Analysis','name_ar' => 'تحليل مياه زراعية'],
            ],
            'معمل أغذية' => [
                ['name_en' => 'Food Analysis',          'name_ar' => 'تحليل مواد غذائية'],
                ['name_en' => 'Food Quality Control',   'name_ar' => 'فحص جودة أغذية'],
                ['name_en' => 'Food Additives Analysis','name_ar' => 'تحليل مضافات غذائية'],
            ],
            'معمل كيميائي' => [
                ['name_en' => 'Chemical Analysis', 'name_ar' => 'تحليل مواد كيميائية'],
                ['name_en' => 'Soil Testing',       'name_ar' => 'فحص تربة'],
                ['name_en' => 'Metal Analysis',     'name_ar' => 'تحليل معادن'],
            ],
            'معمل طباعة' => [
                ['name_en' => 'Digital Printing',  'name_ar' => 'طباعة رقمية'],
                ['name_en' => 'Offset Printing',   'name_ar' => 'طباعة أوفست'],
                ['name_en' => 'Textile Printing',  'name_ar' => 'طباعة نسيج'],
            ],
            'معمل حاسوب' => [
                ['name_en' => 'Hardware Maintenance',       'name_ar' => 'صيانة أجهزة'],
                ['name_en' => 'Programming & Development',  'name_ar' => 'برمجة وتطوير'],
                ['name_en' => 'Networks & Internet',        'name_ar' => 'شبكات وإنترنت'],
            ],
            'استوديو تصوير' => [
                ['name_en' => 'Photography',      'name_ar' => 'تصوير فوتوغرافي'],
                ['name_en' => 'Videography',      'name_ar' => 'تصوير فيديو'],
                ['name_en' => 'Image Processing', 'name_ar' => 'معالجة صور'],
            ],
            'معمل خياطة' => [
                ['name_en' => 'Clothing Design',    'name_ar' => 'تفصيل ملابس'],
                ['name_en' => 'Machine Embroidery', 'name_ar' => 'تطريز آلي'],
                ['name_en' => 'Fabric Sewing',      'name_ar' => 'خياطة قماش'],
            ],

            // ── شركة ────────────────────────────────────
            'شركة تجارية' => [
                ['name_en' => 'Import',    'name_ar' => 'استيراد'],
                ['name_en' => 'Export',    'name_ar' => 'تصدير'],
                ['name_en' => 'Retail',    'name_ar' => 'تجزئة'],
                ['name_en' => 'Wholesale', 'name_ar' => 'جملة'],
            ],
            'شركة مقاولات' => [
                ['name_en' => 'General Contracting',    'name_ar' => 'مقاولات عامة'],
                ['name_en' => 'Electrical Contracting', 'name_ar' => 'مقاولات كهربائية'],
                ['name_en' => 'Mechanical Contracting', 'name_ar' => 'مقاولات ميكانيكية'],
                ['name_en' => 'Decoration Contracting', 'name_ar' => 'مقاولات ديكور'],
            ],
            'شركة تقنية' => [
                ['name_en' => 'Software Development', 'name_ar' => 'تطوير برمجيات'],
                ['name_en' => 'Cloud Services',       'name_ar' => 'خدمات سحابية'],
                ['name_en' => 'AI Solutions',         'name_ar' => 'حلول ذكاء اصطناعي'],
                ['name_en' => 'Mobile Apps',          'name_ar' => 'تطبيقات موبايل'],
            ],
            'شركة إعلانية' => [
                ['name_en' => 'Digital Advertising',    'name_ar' => 'إعلانات رقمية'],
                ['name_en' => 'Digital Marketing',      'name_ar' => 'تسويق إلكتروني'],
                ['name_en' => 'Media Production',       'name_ar' => 'إنتاج إعلامي'],
                ['name_en' => 'Visual Identity Design', 'name_ar' => 'تصميم هوية بصرية'],
            ],
            'شركة شحن ونقل' => [
                ['name_en' => 'Land Shipping',    'name_ar' => 'شحن بري'],
                ['name_en' => 'Sea Shipping',     'name_ar' => 'شحن بحري'],
                ['name_en' => 'Air Shipping',     'name_ar' => 'شحن جوي'],
                ['name_en' => 'Furniture Moving', 'name_ar' => 'نقل أثاث'],
            ],
            'شركة عقارية' => [
                ['name_en' => 'Real Estate Sales',       'name_ar' => 'بيع عقارات'],
                ['name_en' => 'Real Estate Rental',      'name_ar' => 'إيجار عقارات'],
                ['name_en' => 'Real Estate Development', 'name_ar' => 'تطوير عقاري'],
                ['name_en' => 'Property Management',     'name_ar' => 'إدارة عقارات'],
            ],
            'شركة استيراد وتصدير' => [
                ['name_en' => 'Food Import',                  'name_ar' => 'استيراد مواد غذائية'],
                ['name_en' => 'Local Products Export',        'name_ar' => 'تصدير منتجات محلية'],
                ['name_en' => 'Construction Materials Import','name_ar' => 'استيراد مواد بناء'],
                ['name_en' => 'Raw Materials Import',         'name_ar' => 'استيراد مواد خام'],
            ],
            'شركة خدمات' => [
                ['name_en' => 'Cleaning & Maintenance', 'name_ar' => 'نظافة وصيانة'],
                ['name_en' => 'Security & Guard',       'name_ar' => 'حراسة وأمن'],
                ['name_en' => 'Home Services',          'name_ar' => 'خدمات منزلية'],
                ['name_en' => 'Legal Services',         'name_ar' => 'خدمات قانونية'],
            ],
        ];

        foreach ($subcategories as $categoryName => $items) {
            $category = Category::where('name_ar', $categoryName)->first();
            if (!$category) continue;

            foreach ($items as $item) {
                Subcategory::firstOrCreate(
                    ['name_en' => $item['name_en'], 'category_id' => $category->id],
                    ['name_ar' => $item['name_ar']]
                );
            }
        }
    }
}
