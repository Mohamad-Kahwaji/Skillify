<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Seeder;

class ServiceSeedeers extends Seeder
{
    // ── كلمات مفتاحية إنجليزية لجلب صور واقعية مطابقة لكل خدمة ─────────────
    private array $imageKeywords = [
        // Health & medical
        'doctor1'      => 'doctor,medical,home-visit',
        'dentist1'     => 'dentist,dental-clinic',
        'therapy1'     => 'psychologist,therapy,consultation',
        'eye1'         => 'ophthalmology,eye-exam,optometry',

        // Law
        'lawyer1'      => 'lawyer,legal-consultation,law-office',
        'notary1'      => 'contract,signing,legal-document',

        // Engineering & technology
        'architect1'   => 'architect,architectural-blueprint,house-design',
        'civil1'       => 'civil-engineering,construction-site,engineer',
        'appdev1'      => 'mobile-app-development,software-developer,coding',
        'website1'     => 'web-development,laptop,coding',
        'design1'      => 'graphic-design,branding,designer',
        'erp1'         => 'enterprise-software,dashboard,software',
        'cloud1'       => 'cloud-server,data-center,cloud-computing',
        'pcbuild1'     => 'computer-repair,desktop-pc,technician',
        'network1'     => 'network-cabling,server-rack,network-technician',

        // Education & finance
        'teacher1'     => 'teacher,private-tutor,math-class',
        'english1'     => 'english-class,language-learning,teacher',
        'accountant1'  => 'accountant,financial-statements,office',
        'audit1'       => 'financial-audit,accounting,documents',

        // Construction & home services
        'mason1'      => 'masonry,bricklaying,construction-worker',
        'painter1'    => 'house-painting,interior-painting,painter',
        'tiler1'      => 'tile-installation,bathroom-tiles,construction',
        'electric2'   => 'electrician,electrical-panel,wiring',
        'heating1'    => 'central-heating,radiator,heating-system',
        'ac1'         => 'air-conditioner,ac-technician,split-ac',
        'kitchen1'    => 'custom-kitchen,cabinetry,modern-kitchen',
        'furniture1'  => 'furniture-restoration,woodworking,antique-furniture',
        'villa1'      => 'villa-construction,modern-house,construction',
        'decor1'      => 'interior-design,luxury-interior,living-room',
        'gate1'       => 'wrought-iron-gate,metalwork,blacksmith',
        'railing1'    => 'metal-railing,staircase,metalwork',
        'cleaning1'   => 'professional-house-cleaning,cleaning-service',
        'security1'   => 'security-guard,building-security,security-service',

        // Fashion & food
        'dress1'      => 'wedding-dress,bridal-gown,bridal-fashion',
        'suit1'       => 'tailored-suit,mens-formalwear,tailoring',
        'catering1'   => 'catering,event-food,professional-catering',
        'sweets1'     => 'syrian-desserts,baklava,pastry',
        'makeup1'     => 'bridal-makeup,makeup-artist,beauty',
        'barber2'     => 'barbershop,mens-haircut,beard',

        // Laboratories
        'bloodtest1'  => 'blood-test,medical-laboratory,blood-analysis',
        'hormone1'    => 'medical-laboratory,hormone-test,lab',
        'watertest1'  => 'water-quality-testing,laboratory,water-analysis',
        'irrigation1' => 'irrigation,agriculture,water-system',
        'foodtest1'   => 'food-quality-testing,food-laboratory,food-science',
        'additives1'  => 'food-laboratory,food-additives,quality-control',
        'soiltest1'   => 'soil-testing,agricultural-laboratory,soil-analysis',
        'chemtest1'   => 'chemical-laboratory,chemistry,quality-control',

        // Photography, printing & media
        'wedding1'    => 'wedding-photography,wedding-photographer',
        'product1'    => 'product-photography,product-studio,commercial-photography',
        'banner1'     => 'large-format-printing,billboard-printing,print-shop',
        'brochure1'   => 'brochure-printing,commercial-printing,print-shop',
        'portrait1'   => 'professional-portrait,portrait-photography,studio',
        'interior1'   => 'interior-design,3d-interior,home-design',
        'editing1'    => 'video-editing,video-editor,post-production',
        'press1'      => 'journalist,press-conference,news-photography',

        // Logistics, real estate & business
        'moving1'     => 'furniture-moving,moving-truck,movers',
        'shipping2'   => 'freight-truck,logistics,shipping',
        'apartment1'  => 'modern-apartment,real-estate,apartment',
        'office1'     => 'commercial-office,office-space,real-estate',
        'wholesale1'  => 'warehouse,wholesale,inventory',
        'retail1'     => 'retail-store,shop,merchandise',
        'adscampaign1' => 'digital-marketing,social-media-advertising,marketing',
        'branding1'   => 'brand-identity,branding,creative-agency',
        'importmat1'  => 'construction-materials,building-supplies,warehouse',
        'export1'     => 'agricultural-export,agriculture,produce',
        'landscaping1' => 'landscaping,garden-design,gardener',
        'seedlings1'  => 'plant-nursery,seedlings,agriculture',
        'uniform1'    => 'work-uniform,uniform-manufacturing,tailoring',
        'embroidery1' => 'embroidery,machine-embroidery,clothing',
    ];

    public function run(): void
    {
        $cities = [
            'دمشق',
            'حلب',
            'حمص',
            'حماة',
            'اللاذقية',
            'طرطوس',
            'درعا',
            'السويداء',
            'إدلب',
            'القامشلي',
        ];

        $services = [

            // ── الصحة والطب ───────────────────────────────────────────────
            [
                'name'        => 'زيارة منزلية — طبيب عام',
                'category'    => 'الصحة والطب',
                'subcategory' => 'طبيب عام',
                'description' => 'طبيب عام معتمد يزور منزلك للفحص والتشخيص وإعطاء الوصفة الطبية.',
                'price'       => 15.00,
                'price_type' => 'usd',
                'city_idx'    => 0,
                'img_seed' => 'doctor1',
            ],
            [
                'name'        => 'جلسة علاج أسنان متكاملة',
                'category'    => 'الصحة والطب',
                'subcategory' => 'طبيب أسنان',
                'description' => 'علاج التسوس وتبييض الأسنان وتركيب التيجان والخلع مع مواعيد مرنة.',
                'price'       => 25.00,
                'price_type' => 'usd',
                'city_idx'    => 1,
                'img_seed' => 'dentist1',
            ],
            [
                'name'        => 'استشارة نفسية عبر الإنترنت',
                'category'    => 'الصحة والطب',
                'subcategory' => 'طبيب نفسي',
                'description' => 'جلسات علاج نفسي وإرشاد عبر الفيديو مع طبيب نفسي معتمد.',
                'price'       => 20.00,
                'price_type' => 'usd',
                'city_idx'    => 0,
                'img_seed' => 'therapy1',
            ],
            [
                'name'        => 'فحص عيون متكامل',
                'category'    => 'الصحة والطب',
                'subcategory' => 'طبيب عيون',
                'description' => 'فحص شامل للعين يشمل اختبار النظر وقياس الضغط وإعطاء الوصفة الطبية.',
                'price'       => 18.00,
                'price_type' => 'usd',
                'city_idx'    => 2,
                'img_seed' => 'eye1',
            ],

            // ── القانون والمحاماة ─────────────────────────────────────────
            [
                'name'        => 'استشارة قانونية متخصصة',
                'category'    => 'القانون والمحاماة',
                'subcategory' => 'مستشار قانوني',
                'description' => 'نصائح قانونية شاملة في قضايا الأسرة والتجارة والعقارات.',
                'price'       => 2500000,
                'price_type' => 'syp',
                'city_idx'    => 1,
                'img_seed' => 'lawyer1',
            ],
            [
                'name'        => 'صياغة عقود البيع والشراء',
                'category'    => 'القانون والمحاماة',
                'subcategory' => 'موثق',
                'description' => 'صياغة واعتماد عقود العقارات والبضائع بشكل احترافي.',
                'price'       => 3000000,
                'price_type' => 'syp',
                'city_idx'    => 0,
                'img_seed' => 'notary1',
            ],

            // ── الهندسة ───────────────────────────────────────────────────
            [
                'name'        => 'تصميم معماري كامل لمنزل سكني',
                'category'    => 'الهندسة',
                'subcategory' => 'مهندس معماري',
                'description' => 'تصميم معماري وإنشائي متكامل مع رسومات التنفيذ وجداول الكميات.',
                'price'       => 150.00,
                'price_type' => 'usd',
                'city_idx'    => 0,
                'img_seed' => 'architect1',
            ],
            [
                'name'        => 'إشراف هندسي مدني في الموقع',
                'category'    => 'الهندسة',
                'subcategory' => 'مهندس مدني',
                'description' => 'إشراف يومي وأسبوعي على مشاريع البناء لضمان معايير الجودة.',
                'price'       => 80.00,
                'price_type' => 'usd',
                'city_idx'    => 2,
                'img_seed' => 'civil1',
            ],
            [
                'name'        => 'تطوير تطبيق جوال احترافي',
                'category'    => 'الهندسة',
                'subcategory' => 'مهندس برمجيات',
                'description' => 'تطوير احترافي لتطبيقات Android وiOS بتصميم UI/UX عصري.',
                'price'       => 500.00,
                'price_type' => 'usd',
                'city_idx'    => 1,
                'img_seed' => 'appdev1',
            ],

            // ── التعليم ───────────────────────────────────────────────────
            [
                'name'        => 'دروس خصوصية في الرياضيات والفيزياء',
                'category'    => 'التعليم',
                'subcategory' => 'معلم',
                'description' => 'دروس خصوصية في الرياضيات والفيزياء لطلاب المرحلة الثانوية مع مدرس متمرس.',
                'price'       => 500000,
                'price_type' => 'syp',
                'city_idx'    => 3,
                'img_seed' => 'teacher1',
            ],
            [
                'name'        => 'دورة لغة إنجليزية متقدمة',
                'category'    => 'التعليم',
                'subcategory' => 'مدرب',
                'description' => 'دورة إنجليزية مكثفة تشمل المحادثة والكتابة والتحضير لـ IELTS وTOEFL.',
                'price'       => 10.00,
                'price_type' => 'usd',
                'city_idx'    => 0,
                'img_seed' => 'english1',
            ],

            // ── المحاسبة والمالية ─────────────────────────────────────────
            [
                'name'        => 'خدمة مسك الدفاتر الشهرية',
                'category'    => 'المحاسبة والمالية',
                'subcategory' => 'محاسب',
                'description' => 'مسك دفاتر كامل وقيود يومية وبيانات مالية شهرية للشركات.',
                'price'       => 50.00,
                'price_type' => 'usd',
                'city_idx'    => 0,
                'img_seed' => 'accountant1',
            ],
            [
                'name'        => 'مراجعة مالية سنوية',
                'category'    => 'المحاسبة والمالية',
                'subcategory' => 'مدقق حسابات',
                'description' => 'تدقيق سنوي وإعداد تقارير الإقفال المالية للشركات.',
                'price'       => 200.00,
                'price_type' => 'usd',
                'city_idx'    => 1,
                'img_seed' => 'audit1',
            ],

            // ── تقنية المعلومات ───────────────────────────────────────────
            [
                'name'        => 'تطوير موقع إلكتروني احترافي',
                'category'    => 'تقنية المعلومات',
                'subcategory' => 'مطور ويب',
                'description' => 'تصميم وتطوير مواقع احترافية ومتاجر إلكترونية بأحدث التقنيات.',
                'price'       => 300.00,
                'price_type' => 'usd',
                'city_idx'    => 0,
                'img_seed' => 'website1',
            ],
            [
                'name'        => 'تصميم هوية بصرية كاملة',
                'category'    => 'تقنية المعلومات',
                'subcategory' => 'مصمم جرافيك',
                'description' => 'تصميم شعار وبطاقة عمل وورق رسمي وملف تعريف الشركة.',
                'price'       => 100.00,
                'price_type' => 'usd',
                'city_idx'    => 1,
                'img_seed' => 'design1',
            ],

            // ── البناء والتشييد ───────────────────────────────────────────
            [
                'name'        => 'أعمال البناء والأساسات',
                'category'    => 'البناء والتشييد',
                'subcategory' => 'بنّاء',
                'description' => 'بناء من الأساسات حتى الجدران والسقف مع ضمان الجودة.',
                'price'       => 4000000,
                'price_type' => 'syp',
                'city_idx'    => 2,
                'img_seed' => 'mason1',
            ],
            [
                'name'        => 'دهان شامل لشقة سكنية',
                'category'    => 'البناء والتشييد',
                'subcategory' => 'دهان',
                'description' => 'دهان الجدران والأسقف بدهانات ممتازة مع تحضير الأسطح وإزالة الطبقات القديمة.',
                'price'       => 1500000,
                'price_type' => 'syp',
                'city_idx'    => 3,
                'img_seed' => 'painter1',
            ],
            [
                'name'        => 'تبليط الحمامات والسيراميك',
                'category'    => 'البناء والتشييد',
                'subcategory' => 'مبلط',
                'description' => 'تركيب دقيق للبلاط السيراميكي والبورسيلاني للأرضيات والجدران.',
                'price'       => 2000000,
                'price_type' => 'syp',
                'city_idx'    => 4,
                'img_seed' => 'tiler1',
            ],

            // ── الكهرباء والسباكة ─────────────────────────────────────────
            [
                'name'        => 'تمديد كهربائي كامل للمنزل',
                'category'    => 'الكهرباء والسباكة',
                'subcategory' => 'كهربائي',
                'description' => 'تركيب شبكة كهربائية سكنية تشمل لوحات التوزيع والكابلات.',
                'price'       => 3000000,
                'price_type' => 'syp',
                'city_idx'    => 0,
                'img_seed' => 'electric2',
            ],
            [
                'name'        => 'تركيب نظام تدفئة مركزية',
                'category'    => 'الكهرباء والسباكة',
                'subcategory' => 'تقني تدفئة',
                'description' => 'تركيب أنظمة التدفئة المركزية مع شبكة مشعات كاملة.',
                'price'       => 5000000,
                'price_type' => 'syp',
                'city_idx'    => 3,
                'img_seed' => 'heating1',
            ],
            [
                'name'        => 'تركيب وصيانة مكيفات سبليت',
                'category'    => 'الكهرباء والسباكة',
                'subcategory' => 'تقني تكييف',
                'description' => 'تركيب وصيانة وفحص مكيفات السبليت مع إعادة شحن الغاز.',
                'price'       => 800000,
                'price_type' => 'syp',
                'city_idx'    => 1,
                'img_seed' => 'ac1',
            ],

            // ── النجارة والأثاث ───────────────────────────────────────────
            [
                'name'        => 'مطبخ خشبي مصنوع حسب الطلب',
                'category'    => 'النجارة والأثاث',
                'subcategory' => 'صانع أثاث',
                'description' => 'تصميم وتصنيع مطابخ عصرية مركبة من خشب صلب ممتاز.',
                'price'       => 12000000,
                'price_type' => 'syp',
                'city_idx'    => 0,
                'img_seed' => 'kitchen1',
            ],
            [
                'name'        => 'ترميم الأثاث القديم والكلاسيكي',
                'category'    => 'النجارة والأثاث',
                'subcategory' => 'نجار',
                'description' => 'ترميم وتأهيل الأثاث القديم والكلاسيكي مع استبدال القطع التالفة.',
                'price'       => 1000000,
                'price_type' => 'syp',
                'city_idx'    => 2,
                'img_seed' => 'furniture1',
            ],

            // ── الخياطة والنسيج ───────────────────────────────────────────
            [
                'name'        => 'خياطة فستان زفاف حسب الطلب',
                'category'    => 'الخياطة والنسيج',
                'subcategory' => 'خياطة نسائية',
                'description' => 'فساتين زفاف وسهرة مصممة بالقياس من أجود الأقمشة وأحدث التصاميم.',
                'price'       => 100.00,
                'price_type' => 'usd',
                'city_idx'    => 4,
                'img_seed' => 'dress1',
            ],
            [
                'name'        => 'بدلة رجالية رسمية على المقاس',
                'category'    => 'الخياطة والنسيج',
                'subcategory' => 'خياط رجالي',
                'description' => 'بدلات رجالية رسمية وكاجوال على القياس من أفخر أقمشة الكشمير والصوف.',
                'price'       => 60.00,
                'price_type' => 'usd',
                'city_idx'    => 1,
                'img_seed' => 'suit1',
            ],

            // ── الطباخة والحلويات ─────────────────────────────────────────
            [
                'name'        => 'خدمة تقديم طعام للأفراح والمناسبات',
                'category'    => 'الطباخة والحلويات',
                'subcategory' => 'طباخ',
                'description' => 'إعداد وتقديم المأكولات السورية الأصيلة للأفراح والتجمعات الكبيرة.',
                'price'       => 35.00,
                'price_type' => 'usd',
                'city_idx'    => 0,
                'img_seed' => 'catering1',
            ],
            [
                'name'        => 'حلويات شرقية سورية — طلبات مخصصة',
                'category'    => 'الطباخة والحلويات',
                'subcategory' => 'حلواني',
                'description' => 'معمول وبقلاوة وكنافة وتشكيلة متنوعة من الحلويات الشرقية حسب الطلب.',
                'price'       => 500000,
                'price_type' => 'syp',
                'city_idx'    => 3,
                'img_seed' => 'sweets1',
            ],

            // ── التجميل والعناية ──────────────────────────────────────────
            [
                'name'        => 'مكياج عرائس وسهرة',
                'category'    => 'التجميل والعناية',
                'subcategory' => 'خبير تجميل',
                'description' => 'مكياج عرائس ومناسبات احترافي مع تسريح شعر فاخر.',
                'price'       => 40.00,
                'price_type' => 'usd',
                'city_idx'    => 0,
                'img_seed' => 'makeup1',
            ],
            [
                'name'        => 'قص شعر وتشكيل لحية للرجال',
                'category'    => 'التجميل والعناية',
                'subcategory' => 'حلاق',
                'description' => 'قصة شعر عصرية وتشكيل دقيق للحية مع علاجات العناية بالشعر.',
                'price'       => 200000,
                'price_type' => 'syp',
                'city_idx'    => 5,
                'img_seed' => 'barber2',
            ],

            // ── مختبر طبي ─────────────────────────────────────────────────
            [
                'name'        => 'تحليل دم كامل (CBC)',
                'category'    => 'مختبر طبي',
                'subcategory' => 'تحليل دم',
                'description' => 'فحص دم كامل مع تقرير طبي مفصل يُسلَّم في نفس اليوم.',
                'price'       => 300000,
                'price_type' => 'syp',
                'city_idx'    => 0,
                'img_seed' => 'bloodtest1',
            ],
            [
                'name'        => 'فحوصات هرمونية وغدة درقية',
                'category'    => 'مختبر طبي',
                'subcategory' => 'تحاليل هرمونية',
                'description' => 'فحوصات هرمونات الغدة الدرقية (T3 وT4 وTSH) مع لوحة الهرمونات الجنسية.',
                'price'       => 500000,
                'price_type' => 'syp',
                'city_idx'    => 2,
                'img_seed' => 'hormone1',
            ],

            // ── استوديو تصوير ─────────────────────────────────────────────
            [
                'name'        => 'تصوير زفاف فوتوغرافي وفيديو متكامل',
                'category'    => 'استوديو تصوير',
                'subcategory' => 'تصوير فوتوغرافي',
                'description' => 'تغطية فوتوغرافية وفيديو احترافية لحفلات الزفاف مع المونتاج والتسليم.',
                'price'       => 200.00,
                'price_type' => 'usd',
                'city_idx'    => 1,
                'img_seed' => 'wedding1',
            ],
            [
                'name'        => 'تصوير منتجات تجارية',
                'category'    => 'استوديو تصوير',
                'subcategory' => 'تصوير فوتوغرافي',
                'description' => 'تصوير المنتجات على خلفيات بيضاء وملونة للمتاجر الإلكترونية والكتالوجات.',
                'price'       => 50.00,
                'price_type' => 'usd',
                'city_idx'    => 0,
                'img_seed' => 'product1',
            ],

            // ── معمل طباعة ────────────────────────────────────────────────
            [
                'name'        => 'طباعة لافتات كبيرة وإعلانية',
                'category'    => 'معمل طباعة',
                'subcategory' => 'طباعة رقمية',
                'description' => 'طباعة رقمية عالية الجودة للافتات والإعلانات واللوحات الترويجية.',
                'price'       => 600000,
                'price_type' => 'syp',
                'city_idx'    => 0,
                'img_seed' => 'banner1',
            ],
            [
                'name'        => 'طباعة أوفست للكتالوجات والبروشورات',
                'category'    => 'معمل طباعة',
                'subcategory' => 'طباعة أوفست',
                'description' => 'تصميم وطباعة أوفست للكتالوجات والبروشورات الترويجية بدقة عالية.',
                'price'       => 1000000,
                'price_type' => 'syp',
                'city_idx'    => 1,
                'img_seed' => 'brochure1',
            ],

            // ── شركة تقنية ───────────────────────────────────────────────
            [
                'name'        => 'تطوير نظام ERP للمؤسسات',
                'category'    => 'شركة تقنية',
                'subcategory' => 'تطوير برمجيات',
                'description' => 'حل ERP متكامل لإدارة المخزون والمحاسبة والموارد البشرية والعمليات.',
                'price'       => 2000.00,
                'price_type' => 'usd',
                'city_idx'    => 0,
                'img_seed' => 'erp1',
            ],
            [
                'name'        => 'استضافة سحابية وخدمة النسخ الاحتياطي',
                'category'    => 'شركة تقنية',
                'subcategory' => 'خدمات سحابية',
                'description' => 'استضافة سحابية آمنة مع حلول نسخ احتياطي آلية ودعم تقني على مدار الساعة.',
                'price'       => 30.00,
                'price_type' => 'usd',
                'city_idx'    => 0,
                'img_seed' => 'cloud1',
            ],

            // ── شركة مقاولات ─────────────────────────────────────────────
            [
                'name'        => 'بناء فيلا سكنية جاهزة للتسليم',
                'category'    => 'شركة مقاولات',
                'subcategory' => 'مقاولات عامة',
                'description' => 'بناء كامل من الأساسات حتى التشطيب الكامل بأجود مواد البناء.',
                'price'       => 50000.00,
                'price_type' => 'usd',
                'city_idx'    => 1,
                'img_seed' => 'villa1',
            ],
            [
                'name'        => 'ديكور داخلي فاخر — شقة 200م²',
                'category'    => 'شركة مقاولات',
                'subcategory' => 'مقاولات ديكور',
                'description' => 'تنفيذ ديكور داخلي راقٍ للشقق السكنية والمكاتب.',
                'price'       => 8000.00,
                'price_type' => 'usd',
                'city_idx'    => 0,
                'img_seed' => 'decor1',
            ],

            // ── شركة شحن ونقل ─────────────────────────────────────────────
            [
                'name'        => 'نقل أثاث بين المدن',
                'category'    => 'شركة شحن ونقل',
                'subcategory' => 'نقل أثاث',
                'description' => 'نقل أثاث وأمتعة منزلية احترافي بين مدن سوريا مع تأمين شامل.',
                'price'       => 1500000,
                'price_type' => 'syp',
                'city_idx'    => 5,
                'img_seed' => 'moving1',
            ],
            [
                'name'        => 'شحن بري عبر الحدود',
                'category'    => 'شركة شحن ونقل',
                'subcategory' => 'شحن بري',
                'description' => 'شحن بضائع إلى لبنان والأردن وتركيا والدول المجاورة براً.',
                'price'       => 2.50,
                'price_type' => 'usd',
                'city_idx'    => 0,
                'img_seed' => 'shipping2',
            ],

            // ── شركة عقارية ──────────────────────────────────────────────
            [
                'name'        => 'شقق سكنية للبيع — حي المزة',
                'category'    => 'شركة عقارية',
                'subcategory' => 'بيع عقارات',
                'description' => 'شقق متنوعة المساحات للبيع في المزة والكفرسوسة بأسعار تنافسية.',
                'price'       => 80000.00,
                'price_type' => 'usd',
                'city_idx'    => 0,
                'img_seed' => 'apartment1',
            ],
            [
                'name'        => 'مكاتب تجارية للإيجار',
                'category'    => 'شركة عقارية',
                'subcategory' => 'إيجار عقارات',
                'description' => 'تأجير مكاتب ومحلات تجارية في أحياء الأعمال المتميزة بحلب ودمشق.',
                'price'       => 500.00,
                'price_type' => 'usd',
                'city_idx'    => 1,
                'img_seed' => 'office1',
            ],

            // ── شركة خدمات ───────────────────────────────────────────────
            [
                'name'        => 'خدمة تنظيف منازل',
                'category'    => 'شركة خدمات',
                'subcategory' => 'نظافة وصيانة',
                'description' => 'تنظيف شامل للمنازل والشقق بمنتجات متخصصة وطاقم مدرب.',
                'price'       => 400000,
                'price_type' => 'syp',
                'city_idx'    => 0,
                'img_seed' => 'cleaning1',
            ],
            [
                'name'        => 'خدمات أمن وحراسة للمنشآت',
                'category'    => 'شركة خدمات',
                'subcategory' => 'حراسة وأمن',
                'description' => 'أفراد أمن مدربون للمنشآت التجارية والمجمعات السكنية.',
                'price'       => 600000,
                'price_type' => 'syp',
                'city_idx'    => 1,
                'img_seed' => 'security1',
            ],

            // ── الفن والتصميم ────────────────────────────────────────────
            [
                'name'        => 'جلسة تصوير بورتريه احترافية',
                'category'    => 'الفن والتصميم',
                'subcategory' => 'مصور فوتوغرافي',
                'description' => 'جلسة تصوير شخصي احترافية في الاستوديو أو خارجه مع تعديل الصور.',
                'price'       => 40.00,
                'price_type' => 'usd',
                'city_idx'    => 2,
                'img_seed' => 'portrait1',
            ],
            [
                'name'        => 'تصميم ديكور داخلي لغرفة معيشة',
                'category'    => 'الفن والتصميم',
                'subcategory' => 'مصمم داخلي',
                'description' => 'تصميم ثلاثي الأبعاد لغرف المعيشة واختيار الأثاث والألوان المناسبة.',
                'price'       => 150.00,
                'price_type' => 'usd',
                'city_idx'    => 0,
                'img_seed' => 'interior1',
            ],

            // ── الإعلام والصحافة ──────────────────────────────────────────
            [
                'name'        => 'مونتاج فيديو احترافي',
                'category'    => 'الإعلام والصحافة',
                'subcategory' => 'مونتير',
                'description' => 'مونتاج وتحرير فيديوهات إعلانية وتوثيقية مع مؤثرات بصرية احترافية.',
                'price'       => 80.00,
                'price_type' => 'usd',
                'city_idx'    => 1,
                'img_seed' => 'editing1',
            ],
            [
                'name'        => 'تغطية صحفية لفعاليات ومؤتمرات',
                'category'    => 'الإعلام والصحافة',
                'subcategory' => 'صحفي',
                'description' => 'تغطية إعلامية مكتوبة ومصورة للفعاليات والمؤتمرات والمناسبات.',
                'price'       => 300000,
                'price_type' => 'syp',
                'city_idx'    => 1,
                'img_seed' => 'press1',
            ],

            // ── الحدادة والمعادن ──────────────────────────────────────────
            [
                'name'        => 'تصنيع بوابة حديد مزخرفة',
                'category'    => 'الحدادة والمعادن',
                'subcategory' => 'حداد',
                'description' => 'تصميم وتصنيع بوابات وشبابيك حديدية مزخرفة حسب الطلب.',
                'price'       => 3500000,
                'price_type' => 'syp',
                'city_idx'    => 7,
                'img_seed' => 'gate1',
            ],
            [
                'name'        => 'لحام ودرابزين معدني للسلالم',
                'category'    => 'الحدادة والمعادن',
                'subcategory' => 'لحام',
                'description' => 'تصنيع وتركيب درابزين معدني للسلالم والشرفات بجودة عالية.',
                'price'       => 1800000,
                'price_type' => 'syp',
                'city_idx'    => 7,
                'img_seed' => 'railing1',
            ],

            // ── الزراعة والبستنة ──────────────────────────────────────────
            [
                'name'        => 'تنسيق وزراعة حديقة منزلية',
                'category'    => 'الزراعة والبستنة',
                'subcategory' => 'بستاني',
                'description' => 'تصميم وتنسيق الحدائق المنزلية وزراعة النباتات والأشجار.',
                'price'       => 700000,
                'price_type' => 'syp',
                'city_idx'    => 6,
                'img_seed' => 'landscaping1',
            ],
            [
                'name'        => 'توريد شتول وأشجار مثمرة',
                'category'    => 'الزراعة والبستنة',
                'subcategory' => 'مزارع',
                'description' => 'توريد شتول الخضار والأشجار المثمرة بأسعار الجملة للمزارعين.',
                'price'       => 15.00,
                'price_type' => 'usd',
                'city_idx'    => 6,
                'img_seed' => 'seedlings1',
            ],

            // ── مختبر تحليل مياه ──────────────────────────────────────────
            [
                'name'        => 'تحليل شامل لمياه الشرب',
                'category'    => 'مختبر تحليل مياه',
                'subcategory' => 'تحليل مياه الشرب',
                'description' => 'فحص جودة مياه الشرب من الآبار والشبكة العامة وفق المعايير الصحية.',
                'price'       => 250000,
                'price_type' => 'syp',
                'city_idx'    => 5,
                'img_seed' => 'watertest1',
            ],
            [
                'name'        => 'تحليل مياه الري الزراعي',
                'category'    => 'مختبر تحليل مياه',
                'subcategory' => 'تحليل مياه زراعية',
                'description' => 'فحص ملوحة وصلاحية مياه الري للمحاصيل الزراعية.',
                'price'       => 200000,
                'price_type' => 'syp',
                'city_idx'    => 5,
                'img_seed' => 'irrigation1',
            ],

            // ── معمل أغذية ────────────────────────────────────────────────
            [
                'name'        => 'فحص جودة منتج غذائي جديد',
                'category'    => 'معمل أغذية',
                'subcategory' => 'فحص جودة أغذية',
                'description' => 'فحص وتحليل شامل لجودة وسلامة المنتجات الغذائية قبل الطرح بالسوق.',
                'price'       => 400000,
                'price_type' => 'syp',
                'city_idx'    => 0,
                'img_seed' => 'foodtest1',
            ],
            [
                'name'        => 'تحليل المضافات الغذائية',
                'category'    => 'معمل أغذية',
                'subcategory' => 'تحليل مضافات غذائية',
                'description' => 'تحليل نسب المواد الحافظة والمضافات في المنتجات الغذائية المصنّعة.',
                'price'       => 350000,
                'price_type' => 'syp',
                'city_idx'    => 0,
                'img_seed' => 'additives1',
            ],

            // ── معمل كيميائي ──────────────────────────────────────────────
            [
                'name'        => 'تحليل عينة تربة زراعية',
                'category'    => 'معمل كيميائي',
                'subcategory' => 'فحص تربة',
                'description' => 'تحليل خصائص التربة الكيميائية لتحديد الأسمدة المناسبة للمحصول.',
                'price'       => 300000,
                'price_type' => 'syp',
                'city_idx'    => 1,
                'img_seed' => 'soiltest1',
            ],
            [
                'name'        => 'تحليل مواد كيميائية صناعية',
                'category'    => 'معمل كيميائي',
                'subcategory' => 'تحليل مواد كيميائية',
                'description' => 'تحليل تركيبة المواد الكيميائية الخام للمصانع والمشاريع الصناعية.',
                'price'       => 500000,
                'price_type' => 'syp',
                'city_idx'    => 1,
                'img_seed' => 'chemtest1',
            ],

            // ── معمل حاسوب ────────────────────────────────────────────────
            [
                'name'        => 'صيانة وتجميع أجهزة كمبيوتر',
                'category'    => 'معمل حاسوب',
                'subcategory' => 'صيانة أجهزة',
                'description' => 'تجميع وصيانة أجهزة الحاسوب المكتبية وترقية القطع بأفضل الأسعار.',
                'price'       => 400000,
                'price_type' => 'syp',
                'city_idx'    => 2,
                'img_seed' => 'pcbuild1',
            ],
            [
                'name'        => 'تمديد وتركيب شبكات إنترنت',
                'category'    => 'معمل حاسوب',
                'subcategory' => 'شبكات وإنترنت',
                'description' => 'تمديد شبكات إنترنت داخلية للمنازل والمكاتب مع تركيب نقاط واي فاي.',
                'price'       => 600000,
                'price_type' => 'syp',
                'city_idx'    => 2,
                'img_seed' => 'network1',
            ],

            // ── معمل خياطة ────────────────────────────────────────────────
            [
                'name'        => 'تفصيل زي موحد لموظفين',
                'category'    => 'معمل خياطة',
                'subcategory' => 'تفصيل ملابس',
                'description' => 'تفصيل أزياء موحدة للشركات والمطاعم بكميات وجودة عالية.',
                'price'       => 25.00,
                'price_type' => 'usd',
                'city_idx'    => 4,
                'img_seed' => 'uniform1',
            ],
            [
                'name'        => 'تطريز شعارات على الملابس',
                'category'    => 'معمل خياطة',
                'subcategory' => 'تطريز آلي',
                'description' => 'تطريز آلي دقيق لشعارات الشركات على القمصان والقبعات.',
                'price'       => 150000,
                'price_type' => 'syp',
                'city_idx'    => 4,
                'img_seed' => 'embroidery1',
            ],

            // ── شركة تجارية ───────────────────────────────────────────────
            [
                'name'        => 'استيراد مستلزمات مكتبية بالجملة',
                'category'    => 'شركة تجارية',
                'subcategory' => 'استيراد',
                'description' => 'استيراد وتوزيع المستلزمات المكتبية والقرطاسية بأسعار جملة تنافسية.',
                'price'       => 3000.00,
                'price_type' => 'usd',
                'city_idx'    => 0,
                'img_seed' => 'wholesale1',
            ],
            [
                'name'        => 'توزيع بضائع بالتجزئة للمحلات',
                'category'    => 'شركة تجارية',
                'subcategory' => 'تجزئة',
                'description' => 'توزيع منتجات استهلاكية متنوعة للمحلات التجارية داخل المدينة.',
                'price'       => 1000000,
                'price_type' => 'syp',
                'city_idx'    => 0,
                'img_seed' => 'retail1',
            ],

            // ── شركة إعلانية ──────────────────────────────────────────────
            [
                'name'        => 'حملة إعلانات ممولة على مواقع التواصل',
                'category'    => 'شركة إعلانية',
                'subcategory' => 'إعلانات رقمية',
                'description' => 'إدارة حملات إعلانية مدفوعة على فيسبوك وإنستغرام مع تقارير أداء دورية.',
                'price'       => 250.00,
                'price_type' => 'usd',
                'city_idx'    => 1,
                'img_seed' => 'adscampaign1',
            ],
            [
                'name'        => 'تصميم هوية بصرية متكاملة للعلامة التجارية',
                'category'    => 'شركة إعلانية',
                'subcategory' => 'تصميم هوية بصرية',
                'description' => 'تصميم الشعار وكتيب الهوية البصرية الكامل للعلامات التجارية الجديدة.',
                'price'       => 400.00,
                'price_type' => 'usd',
                'city_idx'    => 1,
                'img_seed' => 'branding1',
            ],

            // ── شركة استيراد وتصدير ───────────────────────────────────────
            [
                'name'        => 'استيراد مواد بناء من الخارج',
                'category'    => 'شركة استيراد وتصدير',
                'subcategory' => 'استيراد مواد بناء',
                'description' => 'استيراد مواد البناء والتشطيبات عالية الجودة من الأسواق العالمية.',
                'price'       => 10000.00,
                'price_type' => 'usd',
                'city_idx'    => 4,
                'img_seed' => 'importmat1',
            ],
            [
                'name'        => 'تصدير منتجات زراعية محلية',
                'category'    => 'شركة استيراد وتصدير',
                'subcategory' => 'تصدير منتجات محلية',
                'description' => 'تصدير المنتجات الزراعية والغذائية السورية إلى الأسواق الإقليمية.',
                'price'       => 5000.00,
                'price_type' => 'usd',
                'city_idx'    => 4,
                'img_seed' => 'export1',
            ],
        ];

        $businesses = \App\Models\Business::whereNotNull('activity')->get()->groupBy('activity');
        $anyBusinessOwnerIds = \App\Models\Business::pluck('user_id')->toArray();

        foreach ($services as $srv) {
            $cityName = $cities[$srv['city_idx'] % \count($cities)];

            $city        = \App\Models\City::firstWhere('name', $cityName);
            $category    = \App\Models\Category::firstWhere('name', $srv['category']);
            $subcategory = \App\Models\Subcategory::firstWhere('name', $srv['subcategory']);

            if (! $city || ! $category || ! $subcategory) {
                continue;
            }

            // اربط كل خدمة بصاحب عمل من نفس النشاط إن أمكن، وإلا بصاحب عمل عشوائي
            $matchingBusinesses = $businesses->get($srv['category']);
            $userId = $matchingBusinesses && $matchingBusinesses->isNotEmpty()
                ? $matchingBusinesses->random()->user_id
                : ($anyBusinessOwnerIds[array_rand($anyBusinessOwnerIds)] ?? null);

            Service::firstOrCreate(
                ['name' => $srv['name'], 'city_id' => $city->id],
                [
                    'user_id'        => $userId,
                    'description'    => $srv['description'],
                    'category_id'    => $category->id,
                    'subcategory_id' => $subcategory->id,
                    'city_id'        => $city->id,
                    'image'          => "https://picsum.photos/seed/skillify-demo-service-{$srv['img_seed']}/640/480",
                    'price'          => $srv['price'],
                    'price_type'     => $srv['price_type'],
                    'is_active'      => true,
                    'status'         => 'approved',
                ]
            );
        }
    }
}
