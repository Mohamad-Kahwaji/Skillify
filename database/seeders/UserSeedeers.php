<?php

namespace Database\Seeders;

use App\Models\ActiveType;
use App\Models\ActiveTypebusiness;
use App\Models\Business;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeedeers extends Seeder
{
    // ── المدن السورية مع إحداثياتها ──────────────────────────────────────
    private array $cities = [
        ['name' => 'دمشق',      'lat' => 33.5138, 'lon' => 36.2765],
        ['name' => 'حلب',       'lat' => 36.2021, 'lon' => 37.1343],
        ['name' => 'حمص',       'lat' => 34.7303, 'lon' => 36.7138],
        ['name' => 'حماة',      'lat' => 35.1321, 'lon' => 36.7560],
        ['name' => 'اللاذقية',  'lat' => 35.5317, 'lon' => 35.7918],
        ['name' => 'طرطوس',     'lat' => 34.8952, 'lon' => 35.8867],
        ['name' => 'درعا',      'lat' => 32.6189, 'lon' => 36.1021],
        ['name' => 'السويداء',  'lat' => 32.7089, 'lon' => 36.5661],
        ['name' => 'إدلب',      'lat' => 35.9325, 'lon' => 36.6342],
        ['name' => 'القامشلي',  'lat' => 37.0511, 'lon' => 41.2274],
    ];

    public function run(): void
    {
        // ── بيانات المستخدمين ──────────────────────────────────────────
        $users = [
            // 20 ذكر
            ['first_name'=>'أحمد',    'last_name'=>'الحسن',    'gender'=>'male',   'phone'=>'0931234501'],
            ['first_name'=>'محمد',    'last_name'=>'العلي',    'gender'=>'male',   'phone'=>'0941234502'],
            ['first_name'=>'عمر',     'last_name'=>'المحمد',   'gender'=>'male',   'phone'=>'0931234503'],
            ['first_name'=>'علي',     'last_name'=>'الأحمد',   'gender'=>'male',   'phone'=>'0941234504'],
            ['first_name'=>'خالد',    'last_name'=>'الخطيب',   'gender'=>'male',   'phone'=>'0991234505'],
            ['first_name'=>'يوسف',    'last_name'=>'الجمال',   'gender'=>'male',   'phone'=>'0931234506'],
            ['first_name'=>'إبراهيم', 'last_name'=>'السعيد',   'gender'=>'male',   'phone'=>'0941234507'],
            ['first_name'=>'سامر',    'last_name'=>'الزعبي',   'gender'=>'male',   'phone'=>'0931234508'],
            ['first_name'=>'رامي',    'last_name'=>'العمر',    'gender'=>'male',   'phone'=>'0991234509'],
            ['first_name'=>'باسل',    'last_name'=>'القاسم',   'gender'=>'male',   'phone'=>'0941234510'],
            ['first_name'=>'وائل',    'last_name'=>'الشيخ',    'gender'=>'male',   'phone'=>'0931234511'],
            ['first_name'=>'جمال',    'last_name'=>'البكري',   'gender'=>'male',   'phone'=>'0941234512'],
            ['first_name'=>'طارق',    'last_name'=>'النجار',   'gender'=>'male',   'phone'=>'0991234513'],
            ['first_name'=>'نادر',    'last_name'=>'الحلاق',   'gender'=>'male',   'phone'=>'0931234514'],
            ['first_name'=>'فيصل',    'last_name'=>'الصالح',   'gender'=>'male',   'phone'=>'0941234515'],
            ['first_name'=>'ماهر',    'last_name'=>'الدراجي',  'gender'=>'male',   'phone'=>'0931234516'],
            ['first_name'=>'معاذ',    'last_name'=>'المطلق',   'gender'=>'male',   'phone'=>'0991234517'],
            ['first_name'=>'حسن',     'last_name'=>'الخوري',   'gender'=>'male',   'phone'=>'0941234518'],
            ['first_name'=>'زياد',    'last_name'=>'الصفدي',   'gender'=>'male',   'phone'=>'0931234519'],
            ['first_name'=>'مراد',    'last_name'=>'الرشيد',   'gender'=>'male',   'phone'=>'0941234520'],
            // 10 أنثى
            ['first_name'=>'ريم',     'last_name'=>'الحسن',    'gender'=>'female', 'phone'=>'0931234521'],
            ['first_name'=>'سارة',    'last_name'=>'العلي',    'gender'=>'female', 'phone'=>'0941234522'],
            ['first_name'=>'نور',     'last_name'=>'الأحمد',   'gender'=>'female', 'phone'=>'0991234523'],
            ['first_name'=>'لارا',    'last_name'=>'الخطيب',   'gender'=>'female', 'phone'=>'0931234524'],
            ['first_name'=>'رنا',     'last_name'=>'الجمال',   'gender'=>'female', 'phone'=>'0941234525'],
            ['first_name'=>'سلمى',    'last_name'=>'السعيد',   'gender'=>'female', 'phone'=>'0931234526'],
            ['first_name'=>'هدى',     'last_name'=>'البكري',   'gender'=>'female', 'phone'=>'0991234527'],
            ['first_name'=>'دانا',    'last_name'=>'الشيخ',    'gender'=>'female', 'phone'=>'0941234528'],
            ['first_name'=>'غادة',    'last_name'=>'الصالح',   'gender'=>'female', 'phone'=>'0931234529'],
            ['first_name'=>'أمل',     'last_name'=>'الخوري',   'gender'=>'female', 'phone'=>'0941234530'],
        ];

        // ── بيانات الأعمال (20 عمل) ──────────────────────────────────────
        $businesses = [
            // مهنة (5)
            ['name'=>'عيادة الدكتور أحمد الحسن',    'name_job'=>'طبيب عام',           'activity'=>'الصحة والطب',          'city_idx'=>0, 'type_ar'=>'مهنة',  'desc'=>'عيادة طبية متخصصة تقدم خدمات الرعاية الصحية الأولية وعلاج الأمراض الشائعة.',          'img'=>'https://picsum.photos/seed/clinic1/640/480'],
            ['name'=>'مكتب المحامي محمد العلي',     'name_job'=>'محامي ومستشار قانوني','activity'=>'القانون والمحاماة',     'city_idx'=>1, 'type_ar'=>'مهنة',  'desc'=>'مكتب قانوني متخصص في قضايا الأسرة والتجارة والعقارات مع خبرة أكثر من 15 عامًا.',       'img'=>'https://picsum.photos/seed/law1/640/480'],
            ['name'=>'مكتب هندسة الزعبي',           'name_job'=>'مهندس مدني',          'activity'=>'الهندسة',              'city_idx'=>2, 'type_ar'=>'مهنة',  'desc'=>'مكتب هندسي متخصص في تصميم المنشآت الإنشائية وإشراف على تنفيذ المشاريع.',               'img'=>'https://picsum.photos/seed/engineer1/640/480'],
            ['name'=>'مكتب محاسبة القاسم',          'name_job'=>'محاسب قانوني',        'activity'=>'المحاسبة والمالية',    'city_idx'=>0, 'type_ar'=>'مهنة',  'desc'=>'خدمات محاسبية ومالية شاملة للشركات والأفراد، تدقيق حسابات وإعداد تقارير مالية.',        'img'=>'https://picsum.photos/seed/accounting1/640/480'],
            ['name'=>'مكتب تطوير البرمجيات الصفدي', 'name_job'=>'مطور ويب وتطبيقات',  'activity'=>'تقنية المعلومات',      'city_idx'=>1, 'type_ar'=>'مهنة',  'desc'=>'تطوير المواقع والتطبيقات وحلول الذكاء الاصطناعي وصيانة الأنظمة الإلكترونية.',           'img'=>'https://picsum.photos/seed/software1/640/480'],
            // حرفة (5)
            ['name'=>'نجارة وأثاث الشيخ',           'name_job'=>'نجار أثاث',           'activity'=>'النجارة والأثاث',      'city_idx'=>0, 'type_ar'=>'حرفة',  'desc'=>'صناعة وتصليح الأثاث المنزلي والمكتبي بأجود أنواع الخشب وتصاميم عصرية.',               'img'=>'https://picsum.photos/seed/carpenter1/640/480'],
            ['name'=>'كهرباء ومنازل البكري',         'name_job'=>'كهربائي تركيبات',     'activity'=>'الكهرباء والسباكة',    'city_idx'=>2, 'type_ar'=>'حرفة',  'desc'=>'تركيب وصيانة الأنظمة الكهربائية المنزلية والتجارية والصناعية.',                        'img'=>'https://picsum.photos/seed/electric1/640/480'],
            ['name'=>'سباكة وتدفئة الدراجي',        'name_job'=>'سباك وتقني تدفئة',    'activity'=>'الكهرباء والسباكة',    'city_idx'=>3, 'type_ar'=>'حرفة',  'desc'=>'خدمات السباكة وتركيب أنظمة التدفئة المركزية والمياه الساخنة.',                         'img'=>'https://picsum.photos/seed/plumber1/640/480'],
            ['name'=>'صالون حلاقة النجار',          'name_job'=>'حلاق متخصص',          'activity'=>'التجميل والعناية',     'city_idx'=>4, 'type_ar'=>'حرفة',  'desc'=>'صالون حلاقة رجالي متكامل يقدم أحدث القصات والعناية بالشعر واللحية.',                  'img'=>'https://picsum.photos/seed/barber1/640/480'],
            ['name'=>'مطبخ الطباخة الفاخرة',        'name_job'=>'طباخ محترف',          'activity'=>'الطباخة والحلويات',    'city_idx'=>0, 'type_ar'=>'حرفة',  'desc'=>'خدمات تقديم الطعام للمناسبات والأفراح والحفلات مع تشكيلة واسعة من المأكولات السورية.', 'img'=>'https://picsum.photos/seed/chef1/640/480'],
            // معمل (5)
            ['name'=>'مختبر الحياة للتحاليل الطبية','name_job'=>'مختبر طبي معتمد',     'activity'=>'مختبر طبي',            'city_idx'=>0, 'type_ar'=>'معمل',  'desc'=>'مختبر طبي متكامل يوفر جميع التحاليل الطبية والهرمونية والبكتيرية بدقة عالية.',         'img'=>'https://picsum.photos/seed/lab1/640/480'],
            ['name'=>'استوديو ضوء للتصوير',         'name_job'=>'مصور احترافي',        'activity'=>'استوديو تصوير',        'city_idx'=>1, 'type_ar'=>'معمل',  'desc'=>'استوديو تصوير احترافي لحفلات الأعراس والتجارة والإعلانات وتصوير المنتجات.',            'img'=>'https://picsum.photos/seed/studio1/640/480'],
            ['name'=>'معمل النور للطباعة الرقمية',  'name_job'=>'طباعة رقمية',         'activity'=>'معمل طباعة',           'city_idx'=>0, 'type_ar'=>'معمل',  'desc'=>'طباعة رقمية وأوفست لجميع المطبوعات التجارية والإعلانية واللافتات والكتالوجات.',         'img'=>'https://picsum.photos/seed/print1/640/480'],
            ['name'=>'مركز تقنية الحاسوب',          'name_job'=>'تقني حاسوب',          'activity'=>'معمل حاسوب',           'city_idx'=>2, 'type_ar'=>'معمل',  'desc'=>'صيانة وتصليح الحواسيب واللابتوبات وتركيب الأنظمة وتطوير الشبكات.',                   'img'=>'https://picsum.photos/seed/computer1/640/480'],
            ['name'=>'ورشة خياطة لمياء',            'name_job'=>'خياطة نسائية',        'activity'=>'معمل خياطة',           'city_idx'=>4, 'type_ar'=>'معمل',  'desc'=>'تفصيل وخياطة الملابس النسائية والعرائس مع أجود الأقمشة وأحدث الموديلات.',             'img'=>'https://picsum.photos/seed/sewing1/640/480'],
            // شركة (5)
            ['name'=>'شركة المستقبل للتقنية',       'name_job'=>'شركة برمجيات وتقنية', 'activity'=>'شركة تقنية',           'city_idx'=>0, 'type_ar'=>'شركة',  'desc'=>'شركة متخصصة في تطوير البرمجيات وتقنية المعلومات وحلول الأعمال الرقمية.',               'img'=>'https://picsum.photos/seed/techco1/640/480'],
            ['name'=>'شركة البناء والتعمير الحديث', 'name_job'=>'شركة مقاولات عامة',   'activity'=>'شركة مقاولات',         'city_idx'=>1, 'type_ar'=>'شركة',  'desc'=>'شركة مقاولات متخصصة في تشييد الأبنية السكنية والتجارية والبنية التحتية.',               'img'=>'https://picsum.photos/seed/construction1/640/480'],
            ['name'=>'شركة الخليج للشحن والنقل',    'name_job'=>'شركة شحن ونقل',       'activity'=>'شركة شحن ونقل',        'city_idx'=>5, 'type_ar'=>'شركة',  'desc'=>'شركة شحن ولوجستيات تغطي سوريا والدول المجاورة بري وبحري وجوي.',                       'img'=>'https://picsum.photos/seed/shipping1/640/480'],
            ['name'=>'شركة الريادة للتجارة',        'name_job'=>'شركة تجارية متخصصة',  'activity'=>'شركة تجارية',          'city_idx'=>0, 'type_ar'=>'شركة',  'desc'=>'شركة تجارة عامة استيراد وتصدير ومواد غذائية وتجهيزات.',                               'img'=>'https://picsum.photos/seed/trading1/640/480'],
            ['name'=>'وكالة الإبداع للإعلان',       'name_job'=>'وكالة إعلانية إبداعية','activity'=>'شركة إعلانية',        'city_idx'=>1, 'type_ar'=>'شركة',  'desc'=>'وكالة إعلانية متكاملة للتسويق الرقمي وتصميم الهوية البصرية وإنتاج الإعلانات.',        'img'=>'https://picsum.photos/seed/agency1/640/480'],
        ];

        // ── محتوى المنشورات ──────────────────────────────────────────────
        $postTemplates = [
            ['type' => 'عرض خدمة',    'titles' => [
                'أقدم خدمات صيانة كهربائية منزلية بأسعار مناسبة',
                'مطلوب عمل في مجال البرمجة وتطوير الويب',
                'عرض شراكة في مشروع تقني واعد',
                'أبحث عن عمل في مجال الهندسة المدنية',
                'خدمات تصميم جرافيك وهوية بصرية',
            ]],
            ['type' => 'عرض صيانة',   'titles' => [
                'صيانة وتصليح أجهزة الحاسوب واللابتوب',
                'تصليح أجهزة الكهرباء المنزلية والمكيفات',
                'ترميم وصيانة المنازل والشقق',
                'صيانة السيارات وتغيير الزيت',
                'إصلاح أعطال السباكة والتدفئة',
            ]],
            ['type' => 'عرض تدريب',   'titles' => [
                'دورة تدريبية في البرمجة للمبتدئين',
                'تعلم اللغة الإنجليزية مع مدرس متخصص',
                'دورة في التصميم الجرافيكي باحترافية',
                'تدريب على المحاسبة وإدارة الأعمال',
                'ورشة عمل في فن الخطاطة والزخرفة',
            ]],
            ['type' => 'بيع أدوات',   'titles' => [
                'للبيع: أدوات نجارة كاملة بحالة ممتازة',
                'بيع معدات مختبر طبي مستعملة',
                'بيع أجهزة حاسوب وطابعات',
                'أدوات كهربائية للبيع بسعر مناسب',
                'بيع معدات مطبخ احترافي',
            ]],
            ['type' => 'سؤال',        'titles' => [
                'ما هو أفضل برنامج لإدارة المشاريع الصغيرة؟',
                'كيف أحصل على رخصة مزاولة مهنة الطب؟',
                'هل تنصحونني بالاستثمار في العقارات هذه الفترة؟',
                'ما هي متطلبات تسجيل شركة في سوريا؟',
                'أحتاج توصية لمهندس معماري موثوق في حلب',
            ]],
            ['type' => 'إعلان عام',   'titles' => [
                'افتتاح فرع جديد لعيادتنا في حي الميسات',
                'عرض خاص: خصم 30% على جميع الخدمات هذا الشهر',
                'نبحث عن شريك لتوسعة مشروع التجارة الإلكترونية',
                'تم الانتهاء من مشروع برج سكني في حي المزة',
                'مبروك: نالت شركتنا جائزة التميز في الجودة 2025',
            ]],
        ];

        $activeTypes = ActiveType::pluck('id', 'name')->toArray();
        $businessTypes = ActiveTypebusiness::pluck('id', 'name')->toArray();

        // ── إنشاء المستخدمين ──────────────────────────────────────────────
        foreach ($users as $i => $data) {
            $city  = $this->cities[$i % \count($this->cities)];
            $year  = rand(1975, 2000);
            $month = rand(1, 12);
            $day   = rand(1, 28);

            $user = User::firstOrCreate(
                ['phone' => $data['phone']],
                [
                    'first_name' => $data['first_name'],
                    'last_name'  => $data['last_name'],
                    'gender'     => $data['gender'],
                    'email'      => 'user' . ($i + 1) . '@hirfa.sy',
                    'password'   => Hash::make('password'),
                    'birthdate'  => "{$year}-{$month}-{$day}",
                    'city'       => $city['name'],
                    'status'     => 'active',
                ]
            );

            // ── الأعمال (أول 20 مستخدم) ──────────────────────────────────
            if ($i < 20 && isset($businesses[$i])) {
                $biz      = $businesses[$i];
                $typeId   = $businessTypes[$biz['type_ar']] ?? 1;
                $cityData = $this->cities[$biz['city_idx']];

                Business::firstOrCreate(
                    ['user_id' => $user->id, 'name' => $biz['name']],
                    [
                        'active_typebusiness_id' => $typeId,
                        'name_job'    => $biz['name_job'],
                        'number'      => $user->phone,
                        'latitude'    => $cityData['lat'] + rand(-50, 50) / 1000,
                        'longitude'   => $cityData['lon'] + rand(-50, 50) / 1000,
                        'description' => $biz['desc'],
                        'image'       => $biz['img'],
                        'activity'    => $biz['activity'],
                        'status'      => 'active',
                    ]
                );
            }

            // ── المنشورات (2-3 لكل مستخدم) ───────────────────────────────
            $numPosts = rand(2, 3);
            for ($p = 0; $p < $numPosts; $p++) {
                $template = $postTemplates[($i + $p) % \count($postTemplates)];
                $typeId   = $activeTypes[$template['type']] ?? 1;
                $title    = $template['titles'][($i + $p) % \count($template['titles'])];
                $imgSeed  = $i * 10 + $p + 100;

                Post::firstOrCreate(
                    ['user_id' => $user->id, 'title' => $title],
                    [
                        'active_type_id' => $typeId,
                        'description'    => $this->generatePostDescription($title, $data['first_name']),
                        'image'          => rand(0, 1) ? "https://picsum.photos/seed/post{$imgSeed}/640/480" : null,
                        'post_date'      => now()->subDays(rand(1, 90))->subHours(rand(0, 23)),
                        'views'          => rand(5, 450),
                        'status'         => 'published',
                    ]
                );
            }
        }
    }

    private function generatePostDescription(string $title, string $authorName): string
    {
        $intros = [
            "السلام عليكم أهل المنصة،",
            "مرحبًا بالجميع،",
            "تحية طيبة لكم،",
            "أتوجه إلى أعضاء منصة حرفة الكرام،",
        ];

        $bodies = [
            "أُعلن عن توفر هذه الخدمة بأسعار تنافسية وجودة عالية. نعمل باحترافية وندعمكم في تحقيق أهدافكم.",
            "لدي خبرة طويلة في هذا المجال وأسعى لتقديم أفضل خدمة ممكنة بأسلوب عملي واحترافي.",
            "نرحب بالتواصل والاستفسار في أي وقت. جاهزون للعمل معكم لتنفيذ مشاريعكم بالمستوى المطلوب.",
            "للتواصل يمكن مراسلتي عبر الرسائل المباشرة أو الاتصال المباشر. أسعار تنافسية وخدمة متميزة.",
        ];

        $outros = [
            "يسعدنا خدمتكم.",
            "في انتظار تواصلكم.",
            "نتشرف بخدمتكم.",
            "شكرًا للاهتمام.",
        ];

        return $intros[rand(0, 3)] . " " . $bodies[rand(0, 3)] . " " . $outros[rand(0, 3)];
    }
}
