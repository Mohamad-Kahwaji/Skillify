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
        ['name' => 'دمشق',     'lat' => 33.5138, 'lon' => 36.2765],
        ['name' => 'حلب',      'lat' => 36.2021, 'lon' => 37.1343],
        ['name' => 'حمص',      'lat' => 34.7303, 'lon' => 36.7138],
        ['name' => 'حماة',     'lat' => 35.1321, 'lon' => 36.7560],
        ['name' => 'اللاذقية', 'lat' => 35.5317, 'lon' => 35.7918],
        ['name' => 'طرطوس',    'lat' => 34.8952, 'lon' => 35.8867],
        ['name' => 'درعا',     'lat' => 32.6189, 'lon' => 36.1021],
        ['name' => 'السويداء', 'lat' => 32.7089, 'lon' => 36.5661],
        ['name' => 'إدلب',     'lat' => 35.9325, 'lon' => 36.6342],
        ['name' => 'القامشلي', 'lat' => 37.0511, 'lon' => 41.2274],
    ];

    // ── كلمات مفتاحية لجلب صور الخدمات / الأعمال ─────────────────────────
    private array $businessImageKeywords = [
        'clinic1'        => 'doctor',
        'law1'           => 'lawyer',
        'engineer1'      => 'engineer',
        'accounting1'    => 'accountant',
        'software1'      => 'programmer',
        'edu1'           => 'teacher',
        'art1'           => 'painter',
        'media1'         => 'journalist',
        'carpenter1'     => 'carpenter',
        'electric1'      => 'electrician',
        'plumber1'       => 'plumber',
        'barber1'        => 'barber',
        'chef1'          => 'chef',
        'builder1'       => 'construction',
        'blacksmith1'    => 'blacksmith',
        'tailor1'        => 'tailor',
        'garden1'        => 'gardener',
        'lab1'           => 'laboratory',
        'studio1'        => 'photographer',
        'print1'         => 'printing',
        'computer1'      => 'computerrepair',
        'sewing1'        => 'sewingmachine',
        'water1'         => 'waterlab',
        'food1'          => 'foodfactory',
        'chem1'          => 'chemist',
        'techco1'        => 'startup',
        'construction1' => 'construction',
        'shipping1'      => 'shipping',
        'trading1'       => 'warehouse',
        'agency1'        => 'marketing',
        'realestate1'    => 'realestate',
        'importexport1'  => 'cargo',
        'services1'      => 'cleaning',
        'pharmacy1'      => 'pharmacy',
        'finance1'       => 'finance',
        'mechanic1'      => 'mechanic',
    ];

    public function run(): void
    {
        // ── أصحاب حسابات الأعمال ─────────────────────────────────────────
        $businessOwners = [
            // ── مهنة ────────────────────────────────────────────────────
            [
                'first_name' => 'أحمد',
                'last_name' => 'الحسن',
                'gender' => 'male',
                'city_idx' => 0,
                'biz_name' => 'عيادة الدكتور أحمد الحسن',
                'name_job' => 'طبيب عام',
                'activity' => 'الصحة والطب',
                'desc' => 'عيادة طبية متخصصة تقدم خدمات الرعاية الصحية الأولية وعلاج الأمراض الشائعة.',
                'img' => 'clinic1',
            ],
            [
                'first_name' => 'محمد',
                'last_name' => 'العلي',
                'gender' => 'male',
                'city_idx' => 1,
                'biz_name' => 'مكتب المحامي محمد العلي',
                'name_job' => 'محامي ومستشار قانوني',
                'activity' => 'القانون والمحاماة',
                'desc' => 'مكتب قانوني متخصص في قضايا الأسرة والتجارة والعقارات مع خبرة أكثر من 15 عامًا.',
                'img' => 'law1',
            ],
            [
                'first_name' => 'سامر',
                'last_name' => 'الزعبي',
                'gender' => 'male',
                'city_idx' => 2,
                'biz_name' => 'مكتب هندسة الزعبي',
                'name_job' => 'مهندس مدني',
                'activity' => 'الهندسة',
                'desc' => 'مكتب هندسي متخصص في تصميم المنشآت الإنشائية وإشراف على تنفيذ المشاريع.',
                'img' => 'engineer1',
            ],
            [
                'first_name' => 'باسل',
                'last_name' => 'القاسم',
                'gender' => 'male',
                'city_idx' => 0,
                'biz_name' => 'مكتب محاسبة القاسم',
                'name_job' => 'محاسب قانوني',
                'activity' => 'المحاسبة والمالية',
                'desc' => 'خدمات محاسبية ومالية شاملة للشركات والأفراد، تدقيق حسابات وإعداد تقارير مالية.',
                'img' => 'accounting1',
            ],
            [
                'first_name' => 'زياد',
                'last_name' => 'الصفدي',
                'gender' => 'male',
                'city_idx' => 1,
                'biz_name' => 'مكتب تطوير البرمجيات الصفدي',
                'name_job' => 'مطور ويب وتطبيقات',
                'activity' => 'تقنية المعلومات',
                'desc' => 'تطوير المواقع والتطبيقات وحلول الذكاء الاصطناعي وصيانة الأنظمة الإلكترونية.',
                'img' => 'software1',
            ],
            [
                'first_name' => 'ليان',
                'last_name' => 'شاهين',
                'gender' => 'female',
                'city_idx' => 8,
                'biz_name' => 'أكاديمية المعرفة للتعليم الخصوصي',
                'name_job' => 'مدربة ومعلمة خصوصية',
                'activity' => 'التعليم',
                'desc' => 'أكاديمية تعليمية متخصصة بتقديم دروس خصوصية في جميع المراحل الدراسية ودورات تقوية.',
                'img' => 'edu1',
            ],
            [
                'first_name' => 'كنان',
                'last_name' => 'فرحات',
                'gender' => 'male',
                'city_idx' => 2,
                'biz_name' => 'استوديو الإبداع للتصميم والفنون',
                'name_job' => 'مصمم ورسام محترف',
                'activity' => 'الفن والتصميم',
                'desc' => 'استوديو فني متخصص بالرسم والتصميم الداخلي وتنفيذ اللوحات الفنية حسب الطلب.',
                'img' => 'art1',
            ],
            [
                'first_name' => 'رهف',
                'last_name' => 'ديب',
                'gender' => 'female',
                'city_idx' => 1,
                'biz_name' => 'مكتب الإعلام والتوثيق الصحفي',
                'name_job' => 'صحفية ومونتيرة',
                'activity' => 'الإعلام والصحافة',
                'desc' => 'خدمات إعلامية شاملة: تغطية صحفية، مونتاج فيديو، وإنتاج محتوى إعلامي احترافي.',
                'img' => 'media1',
            ],

            // ── حرفة ────────────────────────────────────────────────────
            [
                'first_name' => 'طارق',
                'last_name' => 'النجار',
                'gender' => 'male',
                'city_idx' => 0,
                'biz_name' => 'نجارة وأثاث الشيخ',
                'name_job' => 'نجار أثاث',
                'activity' => 'النجارة والأثاث',
                'desc' => 'صناعة وتصليح الأثاث المنزلي والمكتبي بأجود أنواع الخشب وتصاميم عصرية.',
                'img' => 'carpenter1',
            ],
            [
                'first_name' => 'حسن',
                'last_name' => 'الخوري',
                'gender' => 'male',
                'city_idx' => 2,
                'biz_name' => 'كهرباء ومنازل البكري',
                'name_job' => 'كهربائي تركيبات',
                'activity' => 'الكهرباء والسباكة',
                'desc' => 'تركيب وصيانة الأنظمة الكهربائية المنزلية والتجارية والصناعية.',
                'img' => 'electric1',
            ],
            [
                'first_name' => 'مراد',
                'last_name' => 'الرشيد',
                'gender' => 'male',
                'city_idx' => 3,
                'biz_name' => 'سباكة وتدفئة الدراجي',
                'name_job' => 'سباك وتقني تدفئة',
                'activity' => 'الكهرباء والسباكة',
                'desc' => 'خدمات السباكة وتركيب أنظمة التدفئة المركزية والمياه الساخنة.',
                'img' => 'plumber1',
            ],
            [
                'first_name' => 'نادر',
                'last_name' => 'الحلاق',
                'gender' => 'male',
                'city_idx' => 4,
                'biz_name' => 'صالون حلاقة النجار',
                'name_job' => 'حلاق متخصص',
                'activity' => 'التجميل والعناية',
                'desc' => 'صالون حلاقة رجالي متكامل يقدم أحدث القصات والعناية بالشعر واللحية.',
                'img' => 'barber1',
            ],
            [
                'first_name' => 'فيصل',
                'last_name' => 'الصالح',
                'gender' => 'male',
                'city_idx' => 0,
                'biz_name' => 'مطبخ الطباخة الفاخرة',
                'name_job' => 'طباخ محترف',
                'activity' => 'الطباخة والحلويات',
                'desc' => 'خدمات تقديم الطعام للمناسبات والأفراح والحفلات مع تشكيلة واسعة من المأكولات السورية.',
                'img' => 'chef1',
            ],
            [
                'first_name' => 'عدنان',
                'last_name' => 'قاسم',
                'gender' => 'male',
                'city_idx' => 3,
                'biz_name' => 'مقاولات البناء المتين',
                'name_job' => 'بنّاء ومقاول',
                'activity' => 'البناء والتشييد',
                'desc' => 'أعمال بناء وتشييد وتبليط وحفر بخبرة طويلة والتزام بالمواعيد وجودة التنفيذ.',
                'img' => 'builder1',
            ],
            [
                'first_name' => 'غسان',
                'last_name' => 'الحداد',
                'gender' => 'male',
                'city_idx' => 7,
                'biz_name' => 'حدادة الأمانة للأبواب والشبابيك',
                'name_job' => 'حداد ولحام',
                'activity' => 'الحدادة والمعادن',
                'desc' => 'تصنيع وتركيب الأبواب والشبابيك المعدنية والدرابزين بجودة عالية وأسعار منافسة.',
                'img' => 'blacksmith1',
            ],
            [
                'first_name' => 'منى',
                'last_name' => 'زيتون',
                'gender' => 'female',
                'city_idx' => 9,
                'biz_name' => 'خياطة الأناقة الرجالية',
                'name_job' => 'خياطة رجالية',
                'activity' => 'الخياطة والنسيج',
                'desc' => 'تفصيل بدلات رجالية وقمصان حسب المقاس بأحدث الموديلات وأجود الأقمشة.',
                'img' => 'tailor1',
            ],
            [
                'first_name' => 'صالح',
                'last_name' => 'المزرعاني',
                'gender' => 'male',
                'city_idx' => 6,
                'biz_name' => 'مشتل الخضرة للبستنة والزراعة',
                'name_job' => 'بستاني ومزارع',
                'activity' => 'الزراعة والبستنة',
                'desc' => 'تنسيق الحدائق المنزلية وزراعة الأشجار المثمرة وتوريد الشتول والأسمدة.',
                'img' => 'garden1',
            ],

            // ── معمل ────────────────────────────────────────────────────
            [
                'first_name' => 'وائل',
                'last_name' => 'الشيخ',
                'gender' => 'male',
                'city_idx' => 0,
                'biz_name' => 'مختبر الحياة للتحاليل الطبية',
                'name_job' => 'مختبر طبي معتمد',
                'activity' => 'مختبر طبي',
                'desc' => 'مختبر طبي متكامل يوفر جميع التحاليل الطبية والهرمونية والبكتيرية بدقة عالية.',
                'img' => 'lab1',
            ],
            [
                'first_name' => 'جمال',
                'last_name' => 'البكري',
                'gender' => 'male',
                'city_idx' => 1,
                'biz_name' => 'استوديو ضوء للتصوير',
                'name_job' => 'مصور احترافي',
                'activity' => 'استوديو تصوير',
                'desc' => 'استوديو تصوير احترافي لحفلات الأعراس والتجارة والإعلانات وتصوير المنتجات.',
                'img' => 'studio1',
            ],
            [
                'first_name' => 'عمر',
                'last_name' => 'المحمد',
                'gender' => 'male',
                'city_idx' => 0,
                'biz_name' => 'معمل النور للطباعة الرقمية',
                'name_job' => 'طباعة رقمية',
                'activity' => 'معمل طباعة',
                'desc' => 'طباعة رقمية وأوفست لجميع المطبوعات التجارية والإعلانية واللافتات والكتالوجات.',
                'img' => 'print1',
            ],
            [
                'first_name' => 'نور',
                'last_name' => 'الأحمد',
                'gender' => 'female',
                'city_idx' => 2,
                'biz_name' => 'مركز تقنية الحاسوب',
                'name_job' => 'تقنية حاسوب',
                'activity' => 'معمل حاسوب',
                'desc' => 'صيانة وتصليح الحواسيب واللابتوبات وتركيب الأنظمة وتطوير الشبكات.',
                'img' => 'computer1',
            ],
            [
                'first_name' => 'لمياء',
                'last_name' => 'حيدر',
                'gender' => 'female',
                'city_idx' => 4,
                'biz_name' => 'ورشة خياطة لمياء',
                'name_job' => 'خياطة نسائية',
                'activity' => 'معمل خياطة',
                'desc' => 'تفصيل وخياطة الملابس النسائية والعرائس مع أجود الأقمشة وأحدث الموديلات.',
                'img' => 'sewing1',
            ],
            [
                'first_name' => 'أيمن',
                'last_name' => 'سلوم',
                'gender' => 'male',
                'city_idx' => 5,
                'biz_name' => 'مختبر النقاء لتحليل المياه',
                'name_job' => 'فني تحليل مياه',
                'activity' => 'مختبر تحليل مياه',
                'desc' => 'تحليل مياه الشرب والصرف الصحي والمياه الزراعية بمعايير دقيقة ومعتمدة.',
                'img' => 'water1',
            ],
            [
                'first_name' => 'رامي',
                'last_name' => 'العمر',
                'gender' => 'male',
                'city_idx' => 0,
                'biz_name' => 'معمل الجودة لتحليل الأغذية',
                'name_job' => 'فني تحليل أغذية',
                'activity' => 'معمل أغذية',
                'desc' => 'فحص جودة المواد الغذائية والمضافات للمطاعم والمصانع الغذائية.',
                'img' => 'food1',
            ],
            [
                'first_name' => 'إياد',
                'last_name' => 'نعمة',
                'gender' => 'male',
                'city_idx' => 1,
                'biz_name' => 'المعمل الكيميائي للفحوصات',
                'name_job' => 'فني كيميائي',
                'activity' => 'معمل كيميائي',
                'desc' => 'تحليل التربة والمعادن والمواد الكيميائية للمشاريع الصناعية والزراعية.',
                'img' => 'chem1',
            ],

            // ── شركة ────────────────────────────────────────────────────
            [
                'first_name' => 'إبراهيم',
                'last_name' => 'السعيد',
                'gender' => 'male',
                'city_idx' => 0,
                'biz_name' => 'شركة المستقبل للتقنية',
                'name_job' => 'شركة برمجيات وتقنية',
                'activity' => 'شركة تقنية',
                'desc' => 'شركة متخصصة في تطوير البرمجيات وتقنية المعلومات وحلول الأعمال الرقمية.',
                'img' => 'techco1',
            ],
            [
                'first_name' => 'وليد',
                'last_name' => 'ياسين',
                'gender' => 'male',
                'city_idx' => 1,
                'biz_name' => 'شركة البناء والتعمير الحديث',
                'name_job' => 'شركة مقاولات عامة',
                'activity' => 'شركة مقاولات',
                'desc' => 'شركة مقاولات متخصصة في تشييد الأبنية السكنية والتجارية والبنية التحتية.',
                'img' => 'construction1',
            ],
            [
                'first_name' => 'معاذ',
                'last_name' => 'المطلق',
                'gender' => 'male',
                'city_idx' => 5,
                'biz_name' => 'شركة الخليج للشحن والنقل',
                'name_job' => 'شركة شحن ونقل',
                'activity' => 'شركة شحن ونقل',
                'desc' => 'شركة شحن ولوجستيات تغطي سوريا والدول المجاورة بري وبحري وجوي.',
                'img' => 'shipping1',
            ],
            [
                'first_name' => 'خلدون',
                'last_name' => 'عيسى',
                'gender' => 'male',
                'city_idx' => 0,
                'biz_name' => 'شركة الريادة للتجارة',
                'name_job' => 'شركة تجارية متخصصة',
                'activity' => 'شركة تجارية',
                'desc' => 'شركة تجارة عامة استيراد وتصدير ومواد غذائية وتجهيزات.',
                'img' => 'trading1',
            ],
            [
                'first_name' => 'دانا',
                'last_name' => 'الشيخ',
                'gender' => 'female',
                'city_idx' => 1,
                'biz_name' => 'وكالة الإبداع للإعلان',
                'name_job' => 'وكالة إعلانية إبداعية',
                'activity' => 'شركة إعلانية',
                'desc' => 'وكالة إعلانية متكاملة للتسويق الرقمي وتصميم الهوية البصرية وإنتاج الإعلانات.',
                'img' => 'agency1',
            ],
            [
                'first_name' => 'هيثم',
                'last_name' => 'دلة',
                'gender' => 'male',
                'city_idx' => 0,
                'biz_name' => 'شركة الديار العقارية',
                'name_job' => 'وسيط عقاري',
                'activity' => 'شركة عقارية',
                'desc' => 'بيع وإيجار وإدارة العقارات السكنية والتجارية في أفضل المناطق.',
                'img' => 'realestate1',
            ],
            [
                'first_name' => 'فادي',
                'last_name' => 'شعبان',
                'gender' => 'male',
                'city_idx' => 4,
                'biz_name' => 'شركة الشام للاستيراد والتصدير',
                'name_job' => 'تاجر استيراد وتصدير',
                'activity' => 'شركة استيراد وتصدير',
                'desc' => 'استيراد وتصدير المواد الغذائية ومواد البناء والمواد الخام بشبكة علاقات واسعة.',
                'img' => 'importexport1',
            ],
            [
                'first_name' => 'سلمى',
                'last_name' => 'السعيد',
                'gender' => 'female',
                'city_idx' => 2,
                'biz_name' => 'شركة النظافة الشاملة',
                'name_job' => 'مديرة خدمات نظافة',
                'activity' => 'شركة خدمات',
                'desc' => 'خدمات نظافة وحراسة وصيانة للمنشآت التجارية والمجمعات السكنية.',
                'img' => 'services1',
            ],

            // ── إضافات لتنويع أكبر ─────────────────────────────────────
            [
                'first_name' => 'رنيم',
                'last_name' => 'عبود',
                'gender' => 'female',
                'city_idx' => 3,
                'biz_name' => 'صيدلية النور',
                'name_job' => 'صيدلانية',
                'activity' => 'الصحة والطب',
                'desc' => 'صيدلية متكاملة توفر جميع الأدوية والمستلزمات الطبية مع استشارة صيدلانية مجانية.',
                'img' => 'pharmacy1',
            ],
            [
                'first_name' => 'عدي',
                'last_name' => 'خير الله',
                'gender' => 'male',
                'city_idx' => 0,
                'biz_name' => 'مكتب الاستشارات المالية الدولية',
                'name_job' => 'مستشار مالي',
                'activity' => 'المحاسبة والمالية',
                'desc' => 'استشارات مالية واستثمارية وإدارة محافظ وتخطيط ضريبي للشركات والأفراد.',
                'img' => 'finance1',
            ],
            [
                'first_name' => 'شادي',
                'last_name' => 'برهوم',
                'gender' => 'male',
                'city_idx' => 6,
                'biz_name' => 'ورشة تصليح السيارات الحديثة',
                'name_job' => 'ميكانيكي سيارات',
                'activity' => 'الهندسة',
                'desc' => 'صيانة شاملة للسيارات: فحص كمبيوتر، تغيير زيت، وإصلاح أعطال المحرك.',
                'img' => 'mechanic1',
            ],
        ];

        // ── مستخدمون عاديون ─────────────────────────────────────────────
        $regularUsers = [
            ['first_name' => 'عمر', 'last_name' => 'ديب', 'gender' => 'male', 'city_idx' => 0],
            ['first_name' => 'علي', 'last_name' => 'الأحمد', 'gender' => 'male', 'city_idx' => 1],
            ['first_name' => 'خالد', 'last_name' => 'الخطيب', 'gender' => 'male', 'city_idx' => 2],
            ['first_name' => 'يوسف', 'last_name' => 'الجمال', 'gender' => 'male', 'city_idx' => 0],
            ['first_name' => 'ماهر', 'last_name' => 'الدراجي', 'gender' => 'male', 'city_idx' => 3],
            ['first_name' => 'ريم', 'last_name' => 'الحسن', 'gender' => 'female', 'city_idx' => 1],
            ['first_name' => 'سارة', 'last_name' => 'العلي', 'gender' => 'female', 'city_idx' => 2],
            ['first_name' => 'لارا', 'last_name' => 'الخطيب', 'gender' => 'female', 'city_idx' => 4],
            ['first_name' => 'رنا', 'last_name' => 'الجمال', 'gender' => 'female', 'city_idx' => 0],
            ['first_name' => 'هدى', 'last_name' => 'البكري', 'gender' => 'female', 'city_idx' => 5],
            ['first_name' => 'غادة', 'last_name' => 'الصالح', 'gender' => 'female', 'city_idx' => 2],
            ['first_name' => 'أمل', 'last_name' => 'الخوري', 'gender' => 'female', 'city_idx' => 1],
            ['first_name' => 'كريم', 'last_name' => 'وهبة', 'gender' => 'male', 'city_idx' => 0],
            ['first_name' => 'ياسر', 'last_name' => 'حمدان', 'gender' => 'male', 'city_idx' => 4],
            ['first_name' => 'بشار', 'last_name' => 'رستم', 'gender' => 'male', 'city_idx' => 2],
            ['first_name' => 'فراس', 'last_name' => 'العلاوي', 'gender' => 'male', 'city_idx' => 1],
            ['first_name' => 'قصي', 'last_name' => 'برازي', 'gender' => 'male', 'city_idx' => 6],
            ['first_name' => 'نضال', 'last_name' => 'شحادة', 'gender' => 'male', 'city_idx' => 7],
            ['first_name' => 'ديمة', 'last_name' => 'قدور', 'gender' => 'female', 'city_idx' => 0],
            ['first_name' => 'ميساء', 'last_name' => 'شرف', 'gender' => 'female', 'city_idx' => 3],
            ['first_name' => 'هبة', 'last_name' => 'نصار', 'gender' => 'female', 'city_idx' => 8],
            ['first_name' => 'جود', 'last_name' => 'الأسود', 'gender' => 'female', 'city_idx' => 9],
            ['first_name' => 'رغد', 'last_name' => 'مراد', 'gender' => 'female', 'city_idx' => 1],
            ['first_name' => 'تيم', 'last_name' => 'حوراني', 'gender' => 'male', 'city_idx' => 2],
            ['first_name' => 'أوس', 'last_name' => 'فتال', 'gender' => 'male', 'city_idx' => 0],
        ];

        // ── محتوى المنشورات ──────────────────────────────────────────────
        $postTemplates = [
            [
                'type' => 'عرض خدمة',
                'titles' => [
                    'أقدم خدمات صيانة كهربائية منزلية بأسعار مناسبة',
                    'مطلوب عمل في مجال البرمجة وتطوير الويب',
                    'عرض شراكة في مشروع تقني واعد',
                    'أبحث عن عمل في مجال الهندسة المدنية',
                    'خدمات تصميم جرافيك وهوية بصرية',
                    'أقدم دروس خصوصية في الرياضيات والفيزياء',
                    'خدمة توصيل طلبات داخل المدينة',
                ],
            ],
            [
                'type' => 'عرض صيانة',
                'titles' => [
                    'صيانة وتصليح أجهزة الحاسوب واللابتوب',
                    'تصليح أجهزة الكهرباء المنزلية والمكيفات',
                    'ترميم وصيانة المنازل والشقق',
                    'صيانة السيارات وتغيير الزيت',
                    'إصلاح أعطال السباكة والتدفئة',
                    'صيانة أجهزة الجوال بجميع الأنواع',
                ],
            ],
            [
                'type' => 'عرض تدريب',
                'titles' => [
                    'دورة تدريبية في البرمجة للمبتدئين',
                    'تعلم اللغة الإنجليزية مع مدرس متخصص',
                    'دورة في التصميم الجرافيكي باحترافية',
                    'تدريب على المحاسبة وإدارة الأعمال',
                    'ورشة عمل في فن الخطاطة والزخرفة',
                    'دورة تصوير فوتوغرافي للمبتدئين والمحترفين',
                ],
            ],
            [
                'type' => 'بيع أدوات',
                'titles' => [
                    'للبيع: أدوات نجارة كاملة بحالة ممتازة',
                    'بيع معدات مختبر طبي مستعملة',
                    'بيع أجهزة حاسوب وطابعات',
                    'أدوات كهربائية للبيع بسعر مناسب',
                    'بيع معدات مطبخ احترافي',
                    'للبيع: معدات تصوير احترافية بحالة ممتازة',
                ],
            ],
            [
                'type' => 'سؤال',
                'titles' => [
                    'ما هو أفضل برنامج لإدارة المشاريع الصغيرة؟',
                    'كيف أحصل على رخصة مزاولة مهنة الطب؟',
                    'هل تنصحونني بالاستثمار في العقارات هذه الفترة؟',
                    'ما هي متطلبات تسجيل شركة في سوريا؟',
                    'أحتاج توصية لمهندس معماري موثوق في حلب',
                    'ما هي أفضل طريقة لتسويق مشروع صغير محلياً؟',
                ],
            ],
            [
                'type' => 'إعلان عام',
                'titles' => [
                    'افتتاح فرع جديد لعيادتنا في حي الميسات',
                    'عرض خاص: خصم 30% على جميع الخدمات هذا الشهر',
                    'نبحث عن شريك لتوسعة مشروع التجارة الإلكترونية',
                    'تم الانتهاء من مشروع برج سكني في حي المزة',
                    'مبروك: نالت شركتنا جائزة التميز في الجودة 2025',
                    'انضموا إلينا في معرض الحرف اليدوية نهاية الشهر',
                ],
            ],
        ];

        $activeTypes = ActiveType::pluck('id', 'name')->toArray();
        $businessTypes = ActiveTypebusiness::pluck('id', 'name')->toArray();

        // ── ربط النشاط بنوع حساب الأعمال ────────────────────────────────
        $activityToType = [
            'الصحة والطب' => 'مهنة',
            'القانون والمحاماة' => 'مهنة',
            'الهندسة' => 'مهنة',
            'التعليم' => 'مهنة',
            'المحاسبة والمالية' => 'مهنة',
            'تقنية المعلومات' => 'مهنة',
            'الفن والتصميم' => 'مهنة',
            'الإعلام والصحافة' => 'مهنة',

            'البناء والتشييد' => 'حرفة',
            'الكهرباء والسباكة' => 'حرفة',
            'النجارة والأثاث' => 'حرفة',
            'الحدادة والمعادن' => 'حرفة',
            'الخياطة والنسيج' => 'حرفة',
            'الطباخة والحلويات' => 'حرفة',
            'الزراعة والبستنة' => 'حرفة',
            'التجميل والعناية' => 'حرفة',

            'مختبر طبي' => 'معمل',
            'مختبر تحليل مياه' => 'معمل',
            'معمل أغذية' => 'معمل',
            'معمل كيميائي' => 'معمل',
            'معمل طباعة' => 'معمل',
            'معمل حاسوب' => 'معمل',
            'استوديو تصوير' => 'معمل',
            'معمل خياطة' => 'معمل',

            'شركة تجارية' => 'شركة',
            'شركة مقاولات' => 'شركة',
            'شركة تقنية' => 'شركة',
            'شركة إعلانية' => 'شركة',
            'شركة شحن ونقل' => 'شركة',
            'شركة عقارية' => 'شركة',
            'شركة استيراد وتصدير' => 'شركة',
            'شركة خدمات' => 'شركة',
        ];

        $prefixes = ['093', '094', '099'];

        $allPeople = array_merge($businessOwners, $regularUsers);

        $postIdx = 0;

        // ═══════════════════════════════════════════════════════════════
        // صور شخصية لأصحاب حسابات الأعمال
        // ═══════════════════════════════════════════════════════════════
        //
        // ضع الصور داخل:
        //
        // public/avatars/business/
        //
        // جميع هذه الصور يجب أن تكون Portrait لأشخاص.
        // لا تستخدم شعارات أو صور محلات أو منتجات هنا.
        //
        // ═══════════════════════════════════════════════════════════════

        $businessMaleAvatars = [
            'business-01.jpg',
            'business-02.jpg',
            'business-03.jpg',
            'business-04.jpg',
            'business-05.jpg',
            'business-06.jpg',
            'business-07.jpg',
            'business-08.jpg',
            'business-09.jpg',
            'business-10.jpg',
        ];

        $businessFemaleAvatars = [
            'business-11.jpg',
            'business-12.jpg',
            'business-13.jpg',
            'business-14.jpg',
            'business-15.jpg',
        ];

        $businessMaleIdx = 0;
        $businessFemaleIdx = 0;

        // ── صور المستخدمين العاديين ─────────────────────────────────────
        $maleAvatarCount = 41;
        $femaleAvatarCount = 20;

        $maleAvatarIdx = 0;
        $femaleAvatarIdx = 0;

        // ═══════════════════════════════════════════════════════════════
        // إنشاء المستخدمين
        // ═══════════════════════════════════════════════════════════════

        foreach ($allPeople as $i => $data) {

            $city = $this->cities[$data['city_idx']];

            $year = rand(1975, 2000);
            $month = rand(1, 12);
            $day = rand(1, 28);

            $phone =
                $prefixes[$i % 3]
                . '1234'
                . str_pad(501 + $i, 3, '0', STR_PAD_LEFT);


            // ═══════════════════════════════════════════════════════════
            // تحديد صورة المستخدم
            // ═══════════════════════════════════════════════════════════

            if (isset($data['biz_name'])) {

                // ───────────────────────────────────────────────────────
                // صاحب حساب أعمال
                // الصورة شخصية فقط
                // ───────────────────────────────────────────────────────

                if ($data['gender'] === 'female') {

                    $avatarFile =
                        $businessFemaleAvatars[$businessFemaleIdx
                            % count($businessFemaleAvatars)];

                    $businessFemaleIdx++;
                } else {

                    $avatarFile =
                        $businessMaleAvatars[$businessMaleIdx
                            % count($businessMaleAvatars)];

                    $businessMaleIdx++;
                }

                $avatar = 'avatars/business/' . $avatarFile;
            } elseif ($data['gender'] === 'female') {

                // ───────────────────────────────────────────────────────
                // مستخدم عادي - أنثى
                // ───────────────────────────────────────────────────────

                $femaleAvatarIdx =
                    $femaleAvatarIdx % $femaleAvatarCount + 1;

                $avatar = sprintf(
                    'avatars/female-%02d.jpg',
                    $femaleAvatarIdx
                );
            } else {

                // ───────────────────────────────────────────────────────
                // مستخدم عادي - ذكر
                // ───────────────────────────────────────────────────────

                $maleAvatarIdx =
                    $maleAvatarIdx % $maleAvatarCount + 1;

                $avatar = sprintf(
                    'avatars/male-%02d.jpg',
                    $maleAvatarIdx
                );
            }


            // ═══════════════════════════════════════════════════════════
            // إنشاء / تحديث المستخدم
            // ═══════════════════════════════════════════════════════════

            $user = User::updateOrCreate(
                ['phone' => $phone],
                [
                    'first_name' => $data['first_name'],
                    'last_name' => $data['last_name'],
                    'gender' => $data['gender'],
                    'email' => 'user' . ($i + 1) . '@hirfa.sy',
                    'password' => Hash::make('password'),
                    'birthdate' => "{$year}-{$month}-{$day}",
                    'city' => $city['name'],
                    'status' => 'active',
                    'profile_photo' => $avatar,
                ]
            );


            // ═══════════════════════════════════════════════════════════
            // حساب الأعمال
            // ═══════════════════════════════════════════════════════════

            if (isset($data['biz_name'])) {

                $typeAr =
                    $activityToType[$data['activity']]
                    ?? 'مهنة';

                $typeId =
                    $businessTypes[$typeAr]
                    ?? 1;

                $keyword =
                    $this->businessImageKeywords[$data['img']]
                    ?? 'business';


                Business::updateOrCreate(
                    [
                        'user_id' => $user->id,
                        'name' => $data['biz_name'],
                    ],
                    [
                        'active_typebusiness_id' => $typeId,

                        'name_job' => $data['name_job'],

                        'number' => $user->phone,

                        'latitude' =>
                        $city['lat']
                            + rand(-50, 50) / 1000,

                        'longitude' =>
                        $city['lon']
                            + rand(-50, 50) / 1000,

                        'description' => $data['desc'],

                        // صورة النشاط التجاري
                        // منفصلة عن صورة صاحب الحساب
                        'image' =>
                        "https://picsum.photos/seed/skillify-business-" . rawurlencode($data['img']) . "/640/480",

                        'activity' => $data['activity'],

                        'status' => 'active',
                    ]
                );
            }


            // ═══════════════════════════════════════════════════════════
            // المنشورات - 2 إلى 3 لكل مستخدم
            // ═══════════════════════════════════════════════════════════

            $numPosts = rand(2, 3);

            for ($p = 0; $p < $numPosts; $p++) {

                $template =
                    $postTemplates[$postIdx % count($postTemplates)];

                $typeId =
                    $activeTypes[$template['type']]
                    ?? 1;

                $title =
                    $template['titles'][$postIdx % count($template['titles'])];

                $imgSeed = $postIdx + 500;

                $postIdx++;


                Post::updateOrCreate(
                    [
                        'user_id' => $user->id,
                        'title' => $title,
                    ],
                    [
                        'active_type_id' => $typeId,

                        'description' =>
                        $this->generatePostDescription(
                            $title,
                            $data['first_name']
                        ),

                        'image' =>
                        rand(0, 1)
                            ? "https://picsum.photos/seed/post{$imgSeed}/640/480"
                            : null,

                        'post_date' =>
                        now()
                            ->subDays(rand(1, 90))
                            ->subHours(rand(0, 23)),

                        'views' => rand(5, 450),

                        'status' => 'published',
                    ]
                );
            }
        }
    }


    // ═══════════════════════════════════════════════════════════════════
    // إنشاء وصف للمنشور
    // ═══════════════════════════════════════════════════════════════════

    private function generatePostDescription(
        string $title,
        string $authorName
    ): string {

        $intros = [
            "السلام عليكم أهل المنصة،",
            "مرحبًا بالجميع،",
            "تحية طيبة لكم،",
            "أتوجه إلى أعضاء منصة Skillify الكرام،",
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

        return
            $intros[rand(0, 3)]
            . " "
            . $bodies[rand(0, 3)]
            . " "
            . $outros[rand(0, 3)];
    }
}
