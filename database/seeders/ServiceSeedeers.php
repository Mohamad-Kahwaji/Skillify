<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Seeder;

class ServiceSeedeers extends Seeder
{
    public function run(): void
    {
        // cities (English names — used for DB lookup via name_en)
        $cities = [
            'Damascus', 'Aleppo',   'Homs',       'Hama',       'Latakia',
            'Tartus',   'Daraa',    'As-Suwayda', 'Idlib',      'Qamishli',
        ];

        $services = [

            // ── الصحة والطب ───────────────────────────────────────────────
            [
                'name'        => 'زيارة منزلية — طبيب عام',
                'category'    => 'Health & Medicine',
                'subcategory' => 'General Doctor',
                'description' => 'طبيب عام معتمد يزور منزلك للفحص والتشخيص وإعطاء الوصفة الطبية.',
                'price'       => 15.00,  'price_type' => 'usd',
                'city_idx'    => 0,
                'img_seed'    => 'doctor1',
            ],
            [
                'name'        => 'جلسة علاج أسنان متكاملة',
                'category'    => 'Health & Medicine',
                'subcategory' => 'Dentist',
                'description' => 'علاج التسوس وتبييض الأسنان وتركيب التيجان والخلع مع مواعيد مرنة.',
                'price'       => 25.00,  'price_type' => 'usd',
                'city_idx'    => 1,
                'img_seed'    => 'dentist1',
            ],
            [
                'name'        => 'استشارة نفسية عبر الإنترنت',
                'category'    => 'Health & Medicine',
                'subcategory' => 'Psychiatrist',
                'description' => 'جلسات علاج نفسي وإرشاد عبر الفيديو مع طبيب نفسي معتمد.',
                'price'       => 20.00,  'price_type' => 'usd',
                'city_idx'    => 0,
                'img_seed'    => 'therapy1',
            ],
            [
                'name'        => 'فحص عيون متكامل',
                'category'    => 'Health & Medicine',
                'subcategory' => 'Ophthalmologist',
                'description' => 'فحص شامل للعين يشمل اختبار النظر وقياس الضغط وإعطاء الوصفة الطبية.',
                'price'       => 18.00,  'price_type' => 'usd',
                'city_idx'    => 2,
                'img_seed'    => 'eye1',
            ],

            // ── القانون والشؤون القانونية ─────────────────────────────────
            [
                'name'        => 'استشارة قانونية متخصصة',
                'category'    => 'Law & Legal',
                'subcategory' => 'Legal Advisor',
                'description' => 'نصائح قانونية شاملة في قضايا الأسرة والتجارة والعقارات.',
                'price'       => 2500000, 'price_type' => 'syp',
                'city_idx'    => 1,
                'img_seed'    => 'lawyer1',
            ],
            [
                'name'        => 'صياغة عقود البيع والشراء',
                'category'    => 'Law & Legal',
                'subcategory' => 'Notary',
                'description' => 'صياغة واعتماد عقود العقارات والبضائع بشكل احترافي.',
                'price'       => 3000000, 'price_type' => 'syp',
                'city_idx'    => 0,
                'img_seed'    => 'notary1',
            ],

            // ── الهندسة ───────────────────────────────────────────────────
            [
                'name'        => 'تصميم معماري كامل لمنزل سكني',
                'category'    => 'Engineering',
                'subcategory' => 'Architect',
                'description' => 'تصميم معماري وإنشائي متكامل مع رسومات التنفيذ وجداول الكميات.',
                'price'       => 150.00, 'price_type' => 'usd',
                'city_idx'    => 0,
                'img_seed'    => 'architect1',
            ],
            [
                'name'        => 'إشراف هندسي مدني في الموقع',
                'category'    => 'Engineering',
                'subcategory' => 'Civil Engineer',
                'description' => 'إشراف يومي وأسبوعي على مشاريع البناء لضمان معايير الجودة.',
                'price'       => 80.00,  'price_type' => 'usd',
                'city_idx'    => 2,
                'img_seed'    => 'civil1',
            ],
            [
                'name'        => 'تطوير تطبيق جوال احترافي',
                'category'    => 'Engineering',
                'subcategory' => 'Software Engineer',
                'description' => 'تطوير احترافي لتطبيقات Android وiOS بتصميم UI/UX عصري.',
                'price'       => 500.00, 'price_type' => 'usd',
                'city_idx'    => 1,
                'img_seed'    => 'appdev1',
            ],

            // ── التعليم ───────────────────────────────────────────────────
            [
                'name'        => 'دروس خصوصية في الرياضيات والفيزياء',
                'category'    => 'Education',
                'subcategory' => 'Teacher',
                'description' => 'دروس خصوصية في الرياضيات والفيزياء لطلاب المرحلة الثانوية مع مدرس متمرس.',
                'price'       => 500000, 'price_type' => 'syp',
                'city_idx'    => 3,
                'img_seed'    => 'teacher1',
            ],
            [
                'name'        => 'دورة لغة إنجليزية متقدمة',
                'category'    => 'Education',
                'subcategory' => 'Trainer',
                'description' => 'دورة إنجليزية مكثفة تشمل المحادثة والكتابة والتحضير لـ IELTS وTOEFL.',
                'price'       => 10.00,  'price_type' => 'usd',
                'city_idx'    => 0,
                'img_seed'    => 'english1',
            ],

            // ── المحاسبة والمالية ─────────────────────────────────────────
            [
                'name'        => 'خدمة مسك الدفاتر الشهرية',
                'category'    => 'Accounting & Finance',
                'subcategory' => 'Accountant',
                'description' => 'مسك دفاتر كامل وقيود يومية وبيانات مالية شهرية للشركات.',
                'price'       => 50.00,  'price_type' => 'usd',
                'city_idx'    => 0,
                'img_seed'    => 'accountant1',
            ],
            [
                'name'        => 'مراجعة مالية سنوية',
                'category'    => 'Accounting & Finance',
                'subcategory' => 'Auditor',
                'description' => 'تدقيق سنوي وإعداد تقارير الإقفال المالية للشركات.',
                'price'       => 200.00, 'price_type' => 'usd',
                'city_idx'    => 1,
                'img_seed'    => 'audit1',
            ],

            // ── تقنية المعلومات ───────────────────────────────────────────
            [
                'name'        => 'تطوير موقع إلكتروني احترافي',
                'category'    => 'Information Technology',
                'subcategory' => 'Web Developer',
                'description' => 'تصميم وتطوير مواقع احترافية ومتاجر إلكترونية بأحدث التقنيات.',
                'price'       => 300.00, 'price_type' => 'usd',
                'city_idx'    => 0,
                'img_seed'    => 'website1',
            ],
            [
                'name'        => 'تصميم هوية بصرية كاملة',
                'category'    => 'Information Technology',
                'subcategory' => 'Graphic Designer',
                'description' => 'تصميم شعار وبطاقة عمل وورق رسمي وملف تعريف الشركة.',
                'price'       => 100.00, 'price_type' => 'usd',
                'city_idx'    => 1,
                'img_seed'    => 'design1',
            ],

            // ── البناء ────────────────────────────────────────────────────
            [
                'name'        => 'أعمال البناء والأساسات',
                'category'    => 'Construction',
                'subcategory' => 'Mason',
                'description' => 'بناء من الأساسات حتى الجدران والسقف مع ضمان الجودة.',
                'price'       => 4000000, 'price_type' => 'syp',
                'city_idx'    => 2,
                'img_seed'    => 'mason1',
            ],
            [
                'name'        => 'دهان شامل لشقة سكنية',
                'category'    => 'Construction',
                'subcategory' => 'Painter',
                'description' => 'دهان الجدران والأسقف بدهانات ممتازة مع تحضير الأسطح وإزالة الطبقات القديمة.',
                'price'       => 1500000, 'price_type' => 'syp',
                'city_idx'    => 3,
                'img_seed'    => 'painter1',
            ],
            [
                'name'        => 'تبليط الحمامات والسيراميك',
                'category'    => 'Construction',
                'subcategory' => 'Tiler',
                'description' => 'تركيب دقيق للبلاط السيراميكي والبورسيلاني للأرضيات والجدران.',
                'price'       => 2000000, 'price_type' => 'syp',
                'city_idx'    => 4,
                'img_seed'    => 'tiler1',
            ],

            // ── الكهرباء والسباكة ─────────────────────────────────────────
            [
                'name'        => 'تمديد كهربائي كامل للمنزل',
                'category'    => 'Electrical & Plumbing',
                'subcategory' => 'Electrician',
                'description' => 'تركيب شبكة كهربائية سكنية تشمل لوحات التوزيع والكابلات.',
                'price'       => 3000000, 'price_type' => 'syp',
                'city_idx'    => 0,
                'img_seed'    => 'electric2',
            ],
            [
                'name'        => 'تركيب نظام تدفئة مركزية',
                'category'    => 'Electrical & Plumbing',
                'subcategory' => 'Heating Technician',
                'description' => 'تركيب أنظمة التدفئة المركزية (نفط أو غاز أو كهرباء) مع شبكة مشعات كاملة.',
                'price'       => 5000000, 'price_type' => 'syp',
                'city_idx'    => 3,
                'img_seed'    => 'heating1',
            ],
            [
                'name'        => 'تركيب وصيانة مكيفات سبليت',
                'category'    => 'Electrical & Plumbing',
                'subcategory' => 'AC Technician',
                'description' => 'تركيب وصيانة وفحص مكيفات السبليت مع إعادة شحن الغاز.',
                'price'       => 800000,  'price_type' => 'syp',
                'city_idx'    => 1,
                'img_seed'    => 'ac1',
            ],

            // ── النجارة والأثاث ───────────────────────────────────────────
            [
                'name'        => 'مطبخ خشبي مصنوع حسب الطلب',
                'category'    => 'Carpentry & Furniture',
                'subcategory' => 'Furniture Maker',
                'description' => 'تصميم وتصنيع مطابخ عصرية مركبة من خشب صلب ممتاز.',
                'price'       => 12000000, 'price_type' => 'syp',
                'city_idx'    => 0,
                'img_seed'    => 'kitchen1',
            ],
            [
                'name'        => 'ترميم الأثاث القديم والكلاسيكي',
                'category'    => 'Carpentry & Furniture',
                'subcategory' => 'Carpenter',
                'description' => 'ترميم وتأهيل الأثاث القديم والكلاسيكي مع استبدال القطع التالفة.',
                'price'       => 1000000, 'price_type' => 'syp',
                'city_idx'    => 2,
                'img_seed'    => 'furniture1',
            ],

            // ── الخياطة والمنسوجات ────────────────────────────────────────
            [
                'name'        => 'خياطة فستان زفاف حسب الطلب',
                'category'    => 'Tailoring & Textiles',
                'subcategory' => "Women's Tailor",
                'description' => 'فساتين زفاف وسهرة مصممة بالقياس من أجود الأقمشة وأحدث التصاميم.',
                'price'       => 100.00,  'price_type' => 'usd',
                'city_idx'    => 4,
                'img_seed'    => 'dress1',
            ],
            [
                'name'        => 'بدلة رجالية رسمية على المقاس',
                'category'    => 'Tailoring & Textiles',
                'subcategory' => "Men's Tailor",
                'description' => 'بدلات رجالية رسمية وكاجوال على القياس من أفخر أقمشة الكشمير والصوف.',
                'price'       => 60.00,   'price_type' => 'usd',
                'city_idx'    => 1,
                'img_seed'    => 'suit1',
            ],

            // ── الطبخ والحلويات ───────────────────────────────────────────
            [
                'name'        => 'خدمة تقديم طعام للأفراح والمناسبات',
                'category'    => 'Cooking & Pastry',
                'subcategory' => 'Chef',
                'description' => 'إعداد وتقديم المأكولات السورية الأصيلة للأفراح والتجمعات الكبيرة.',
                'price'       => 35.00,   'price_type' => 'usd',
                'city_idx'    => 0,
                'img_seed'    => 'catering1',
            ],
            [
                'name'        => 'حلويات شرقية سورية — طلبات مخصصة',
                'category'    => 'Cooking & Pastry',
                'subcategory' => 'Pastry Chef',
                'description' => 'معمول وبقلاوة وكنافة وتشكيلة متنوعة من الحلويات الشرقية حسب الطلب.',
                'price'       => 500000,  'price_type' => 'syp',
                'city_idx'    => 3,
                'img_seed'    => 'sweets1',
            ],

            // ── الجمال والعناية ───────────────────────────────────────────
            [
                'name'        => 'مكياج عرائس وسهرة',
                'category'    => 'Beauty & Care',
                'subcategory' => 'Beautician',
                'description' => 'مكياج عرائس ومناسبات احترافي مع تسريح شعر فاخر.',
                'price'       => 40.00,   'price_type' => 'usd',
                'city_idx'    => 0,
                'img_seed'    => 'makeup1',
            ],
            [
                'name'        => 'قص شعر وتشكيل لحية للرجال',
                'category'    => 'Beauty & Care',
                'subcategory' => 'Barber',
                'description' => 'قصة شعر عصرية وتشكيل دقيق للحية مع علاجات العناية بالشعر.',
                'price'       => 200000,  'price_type' => 'syp',
                'city_idx'    => 5,
                'img_seed'    => 'barber2',
            ],

            // ── مختبرات طبية ─────────────────────────────────────────────
            [
                'name'        => 'تحليل دم كامل (CBC)',
                'category'    => 'Medical Laboratory',
                'subcategory' => 'Blood Analysis',
                'description' => 'فحص دم كامل مع تقرير طبي مفصل يُسلَّم في نفس اليوم.',
                'price'       => 300000,  'price_type' => 'syp',
                'city_idx'    => 0,
                'img_seed'    => 'bloodtest1',
            ],
            [
                'name'        => 'فحوصات هرمونية وغدة درقية',
                'category'    => 'Medical Laboratory',
                'subcategory' => 'Hormonal Tests',
                'description' => 'فحوصات هرمونات الغدة الدرقية (T3 وT4 وTSH) مع لوحة الهرمونات الجنسية.',
                'price'       => 500000,  'price_type' => 'syp',
                'city_idx'    => 2,
                'img_seed'    => 'hormone1',
            ],

            // ── استوديو تصوير ─────────────────────────────────────────────
            [
                'name'        => 'تصوير زفاف فوتوغرافي وفيديو متكامل',
                'category'    => 'Photography Studio',
                'subcategory' => 'Photography',
                'description' => 'تغطية فوتوغرافية وفيديو احترافية لحفلات الزفاف مع المونتاج والتسليم.',
                'price'       => 200.00,  'price_type' => 'usd',
                'city_idx'    => 1,
                'img_seed'    => 'wedding1',
            ],
            [
                'name'        => 'تصوير منتجات تجارية',
                'category'    => 'Photography Studio',
                'subcategory' => 'Photography',
                'description' => 'تصوير المنتجات على خلفيات بيضاء وملونة للمتاجر الإلكترونية والكتالوجات.',
                'price'       => 50.00,   'price_type' => 'usd',
                'city_idx'    => 0,
                'img_seed'    => 'product1',
            ],

            // ── مطبعة ────────────────────────────────────────────────────
            [
                'name'        => 'طباعة لافتات كبيرة وإعلانية',
                'category'    => 'Print Workshop',
                'subcategory' => 'Digital Printing',
                'description' => 'طباعة رقمية عالية الجودة للافتات والإعلانات واللوحات الترويجية.',
                'price'       => 600000,  'price_type' => 'syp',
                'city_idx'    => 0,
                'img_seed'    => 'banner1',
            ],
            [
                'name'        => 'طباعة أوفست للكتالوجات والبروشورات',
                'category'    => 'Print Workshop',
                'subcategory' => 'Offset Printing',
                'description' => 'تصميم وطباعة أوفست للكتالوجات والبروشورات الترويجية بدقة عالية.',
                'price'       => 1000000, 'price_type' => 'syp',
                'city_idx'    => 1,
                'img_seed'    => 'brochure1',
            ],

            // ── شركة تقنية ───────────────────────────────────────────────
            [
                'name'        => 'تطوير نظام ERP للمؤسسات',
                'category'    => 'Tech Company',
                'subcategory' => 'Software Development',
                'description' => 'حل ERP متكامل لإدارة المخزون والمحاسبة والموارد البشرية والعمليات.',
                'price'       => 2000.00, 'price_type' => 'usd',
                'city_idx'    => 0,
                'img_seed'    => 'erp1',
            ],
            [
                'name'        => 'استضافة سحابية وخدمة النسخ الاحتياطي',
                'category'    => 'Tech Company',
                'subcategory' => 'Cloud Services',
                'description' => 'استضافة سحابية آمنة مع حلول نسخ احتياطي آلية ودعم تقني على مدار الساعة.',
                'price'       => 30.00,   'price_type' => 'usd',
                'city_idx'    => 0,
                'img_seed'    => 'cloud1',
            ],

            // ── شركة مقاولات ─────────────────────────────────────────────
            [
                'name'        => 'بناء فيلا سكنية جاهزة للتسليم',
                'category'    => 'Contracting Company',
                'subcategory' => 'General Contracting',
                'description' => 'بناء كامل من الأساسات حتى التشطيب الكامل بأجود مواد البناء.',
                'price'       => 50000.00,'price_type' => 'usd',
                'city_idx'    => 1,
                'img_seed'    => 'villa1',
            ],
            [
                'name'        => 'ديكور داخلي فاخر — شقة 200م²',
                'category'    => 'Contracting Company',
                'subcategory' => 'Decoration Contracting',
                'description' => 'تنفيذ ديكور داخلي راقٍ للشقق السكنية والمكاتب.',
                'price'       => 8000.00, 'price_type' => 'usd',
                'city_idx'    => 0,
                'img_seed'    => 'decor1',
            ],

            // ── الشحن والنقل ─────────────────────────────────────────────
            [
                'name'        => 'نقل أثاث بين المدن',
                'category'    => 'Shipping & Transport',
                'subcategory' => 'Furniture Moving',
                'description' => 'نقل أثاث وأمتعة منزلية احترافي بين مدن سوريا مع تأمين شامل.',
                'price'       => 1500000, 'price_type' => 'syp',
                'city_idx'    => 5,
                'img_seed'    => 'moving1',
            ],
            [
                'name'        => 'شحن بري عبر الحدود',
                'category'    => 'Shipping & Transport',
                'subcategory' => 'Land Shipping',
                'description' => 'شحن بضائع إلى لبنان والأردن وتركيا والدول المجاورة براً.',
                'price'       => 2.50,    'price_type' => 'usd',
                'city_idx'    => 0,
                'img_seed'    => 'shipping2',
            ],

            // ── شركة عقارية ──────────────────────────────────────────────
            [
                'name'        => 'شقق سكنية للبيع — حي المزة',
                'category'    => 'Real Estate Company',
                'subcategory' => 'Real Estate Sales',
                'description' => 'شقق متنوعة المساحات للبيع في المزة والكفرسوسة بأسعار تنافسية.',
                'price'       => 80000.00,'price_type' => 'usd',
                'city_idx'    => 0,
                'img_seed'    => 'apartment1',
            ],
            [
                'name'        => 'مكاتب تجارية للإيجار',
                'category'    => 'Real Estate Company',
                'subcategory' => 'Real Estate Rental',
                'description' => 'تأجير مكاتب ومحلات تجارية في أحياء الأعمال المتميزة بحلب ودمشق.',
                'price'       => 500.00,  'price_type' => 'usd',
                'city_idx'    => 1,
                'img_seed'    => 'office1',
            ],

            // ── شركة خدمات ───────────────────────────────────────────────
            [
                'name'        => 'خدمة تنظيف منازل',
                'category'    => 'Services Company',
                'subcategory' => 'Cleaning & Maintenance',
                'description' => 'تنظيف شامل للمنازل والشقق بمنتجات متخصصة وطاقم مدرب.',
                'price'       => 400000,  'price_type' => 'syp',
                'city_idx'    => 0,
                'img_seed'    => 'cleaning1',
            ],
            [
                'name'        => 'خدمات أمن وحراسة للمنشآت',
                'category'    => 'Services Company',
                'subcategory' => 'Security & Guard',
                'description' => 'أفراد أمن مدربون للمنشآت التجارية والمجمعات السكنية.',
                'price'       => 600000,  'price_type' => 'syp',
                'city_idx'    => 1,
                'img_seed'    => 'security1',
            ],
        ];

        foreach ($services as $srv) {
            $cityName = $cities[$srv['city_idx'] % \count($cities)];

            $city        = \App\Models\City::where('name_en', $cityName)->first();
            $category    = \App\Models\Category::where('name_en', $srv['category'])->first();
            $subcategory = \App\Models\Subcategory::where('name_en', $srv['subcategory'])->first();

            if (! $city || ! $category || ! $subcategory) {
                continue;
            }

            Service::firstOrCreate(
                ['name' => $srv['name'], 'city_id' => $city->id],
                [
                    'description'    => $srv['description'],
                    'category_id'    => $category->id,
                    'subcategory_id' => $subcategory->id,
                    'city_id'        => $city->id,
                    'image'          => "https://picsum.photos/seed/{$srv['img_seed']}/640/480",
                    'price'          => $srv['price'],
                    'price_type'     => $srv['price_type'],
                ]
            );
        }
    }
}
