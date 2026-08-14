<?php

namespace Database\Seeders;

use App\Models\Advertisement;
use App\Models\Admin;
use App\Models\Business;
use App\Models\Category;
use App\Models\IdentityVerification;
use App\Models\Post;
use App\Models\Report;
use App\Models\Service;
use App\Models\Service_request;
use App\Models\Subcategory;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class PresentationDataSeeder extends Seeder
{
    /**
     * Presentation/demo data only.
     *
     * All names, phone numbers and identity numbers below are synthetic.
     * Locations and service scenarios are realistic, but do not represent
     * real customer records.
     *
     * Run after the normal Skillify seeders.
     */
    public function run(): void
    {
        $password = Hash::make('password');

        $cities = [
            'دمشق'    => ['lat' => 33.5138, 'lon' => 36.2765],
            'حلب'     => ['lat' => 36.2021, 'lon' => 37.1343],
            'حمص'     => ['lat' => 34.7303, 'lon' => 36.7138],
            'حماة'    => ['lat' => 35.1321, 'lon' => 36.7560],
            'اللاذقية' => ['lat' => 35.5317, 'lon' => 35.7918],
            'طرطوس'   => ['lat' => 34.8952, 'lon' => 35.8867],
            'درعا'    => ['lat' => 32.6189, 'lon' => 36.1021],
            'السويداء' => ['lat' => 32.7089, 'lon' => 36.5661],
        ];

        $owners = [
            ['first'=>'مازن','last'=>'الحمصي','gender'=>'male','city'=>'حمص','job'=>'فني تكييف وتبريد','activity'=>'الكهرباء والسباكة','business'=>'حلول التبريد الحديثة'],
            ['first'=>'راما','last'=>'الشامي','gender'=>'female','city'=>'دمشق','job'=>'مصممة جرافيك وهوية بصرية','activity'=>'الفن والتصميم','business'=>'استوديو راما للتصميم'],
            ['first'=>'كريم','last'=>'الحلبي','gender'=>'male','city'=>'حلب','job'=>'مطور مواقع ومتاجر إلكترونية','activity'=>'تقنية المعلومات','business'=>'حلول كريم الرقمية'],
            ['first'=>'سليم','last'=>'العلوي','gender'=>'male','city'=>'اللاذقية','job'=>'مصور مناسبات ومنتجات','activity'=>'استوديو تصوير','business'=>'ستوديو ضوء الساحل'],
            ['first'=>'جنى','last'=>'الدمشقية','gender'=>'female','city'=>'دمشق','job'=>'مدرسة رياضيات وفيزياء','activity'=>'التعليم','business'=>'أكاديمية جنى التعليمية'],
            ['first'=>'أنس','last'=>'الحموي','gender'=>'male','city'=>'حماة','job'=>'سباك وتقني تدفئة','activity'=>'الكهرباء والسباكة','business'=>'خدمات أنس للتدفئة والسباكة'],
            ['first'=>'ليث','last'=>'الطرطوسي','gender'=>'male','city'=>'طرطوس','job'=>'نجار وصانع أثاث','activity'=>'النجارة والأثاث','business'=>'ورشة ليث للأثاث'],
            ['first'=>'سارة','last'=>'الدرعاوية','gender'=>'female','city'=>'درعا','job'=>'مصممة أزياء وخياطة','activity'=>'الخياطة والنسيج','business'=>'دار سارة للأزياء'],
            ['first'=>'فراس','last'=>'السويداني','gender'=>'male','city'=>'السويداء','job'=>'مصور ومونتير فيديو','activity'=>'الإعلام والصحافة','business'=>'فراس للإنتاج المرئي'],
            ['first'=>'نور','last'=>'الدمشقي','gender'=>'female','city'=>'دمشق','job'=>'محاسبة ومستشارة مالية','activity'=>'المحاسبة والمالية','business'=>'مكتب نور للمحاسبة'],
            ['first'=>'عمار','last'=>'الحلبي','gender'=>'male','city'=>'حلب','job'=>'مهندس مدني','activity'=>'الهندسة','business'=>'مكتب أبعاد الهندسي'],
            ['first'=>'هبة','last'=>'الساحلية','gender'=>'female','city'=>'اللاذقية','job'=>'صاحبة خدمات تنظيف وصيانة','activity'=>'شركة خدمات','business'=>'بيت مرتب للخدمات المنزلية'],
        ];

        $businessTypeNames = [
            'الصحة والطب'=>'مهنة','القانون والمحاماة'=>'مهنة','الهندسة'=>'مهنة',
            'المحاسبة والمالية'=>'مهنة','تقنية المعلومات'=>'مهنة','التعليم'=>'مهنة',
            'الفن والتصميم'=>'مهنة','الإعلام والصحافة'=>'مهنة','البناء والتشييد'=>'حرفة',
            'الكهرباء والسباكة'=>'حرفة','النجارة والأثاث'=>'حرفة','الحدادة والمعادن'=>'حرفة',
            'الخياطة والنسيج'=>'حرفة','الطباخة والحلويات'=>'حرفة','الزراعة والبستنة'=>'حرفة',
            'التجميل والعناية'=>'حرفة','استوديو تصوير'=>'معمل','معمل حاسوب'=>'معمل',
            'شركة خدمات'=>'شركة','شركة تقنية'=>'شركة',
        ];

        $createdOwners = [];
        foreach ($owners as $i => $data) {
            $phone = '09' . str_pad((70000000 + $i), 8, '0', STR_PAD_LEFT);

            $user = User::firstOrCreate(
                ['phone' => $phone],
                [
                    'first_name' => $data['first'],
                    'last_name'  => $data['last'],
                    'gender'     => $data['gender'],
                    'email'      => 'demo.pro.' . ($i + 1) . '@skillify.test',
                    'password'   => $password,
                    'birthdate'  => (1978 + ($i % 18)) . '-' . str_pad((1 + ($i % 12)), 2, '0', STR_PAD_LEFT) . '-15',
                    'city'       => $data['city'],
                    'status'     => 'active',
                ]
            );

            $city = $cities[$data['city']];
            $typeName = $businessTypeNames[$data['activity']] ?? 'مهنة';
            $typeId = \App\Models\ActiveTypebusiness::where('name', $typeName)->value('id');

            $business = Business::firstOrCreate(
                ['user_id' => $user->id, 'name' => $data['business']],
                [
                    'active_typebusiness_id' => $typeId ?: 1,
                    'name_job'    => $data['job'],
                    'number'      => $phone,
                    'latitude'    => $city['lat'] + (($i % 5) - 2) * 0.003,
                    'longitude'   => $city['lon'] + (($i % 4) - 1.5) * 0.004,
                    'description' => $this->businessDescription($data['job'], $data['city']),
                    'image'       => "https://picsum.photos/seed/skillify-demo-business-{$i}/640/480",
                    'activity'    => $data['activity'],
                    'status'      => 'active',
                ]
            );

            $createdOwners[] = ['user'=>$user, 'business'=>$business, 'city'=>$data['city']];
        }

        $regularUsers = [
            ['ياسر','الحموي','male','حماة'], ['ريم','الحلبية','female','حلب'],
            ['جاد','الدمشقي','male','دمشق'], ['ميس','الحمصية','female','حمص'],
            ['وسيم','الطرطوسي','male','طرطوس'], ['لارا','اللاذقية','female','اللاذقية'],
            ['سامر','الدرعاوي','male','درعا'], ['رؤى','السويداء','female','السويداء'],
            ['عمر','الدمشقي','male','دمشق'], ['تالا','الحلبية','female','حلب'],
            ['فادي','الحمصي','male','حمص'], ['دانا','الساحلية','female','اللاذقية'],
        ];

        $createdUsers = [];
        foreach ($regularUsers as $i => [$first,$last,$gender,$city]) {
            $phone = '09' . str_pad((80000000 + $i), 8, '0', STR_PAD_LEFT);
            $createdUsers[] = User::firstOrCreate(
                ['phone' => $phone],
                [
                    'first_name'=>$first,'last_name'=>$last,'gender'=>$gender,
                    'email'=>'demo.client.'.($i+1).'@skillify.test',
                    'password'=>$password,
                    'birthdate'=>(1985 + ($i % 12)).'-'.str_pad(1+($i%12),2,'0',STR_PAD_LEFT).'-20',
                    'city'=>$city,'status'=>'active',
                ]
            );
        }

        $postTypes = [
            'عرض خدمة','طلب خدمة','عرض تدريب','سؤال','بيع أدوات','إعلان عام'
        ];
        $postTitles = [
            ['عرض خدمة','تصميم متجر إلكتروني متجاوب للشركات الصغيرة'],
            ['طلب خدمة','مطلوب فني تكييف لفحص جهاز سبليت في حمص'],
            ['طلب خدمة','مطلوب مدرس رياضيات لطلاب المرحلة الثانوية في دمشق'],
            ['عرض خدمة','تصوير منتجات لمتجر إلكتروني مع تسليم الصور معدلة'],
            ['سؤال','ما أفضل طريقة لاختيار فني موثوق لصيانة المنزل؟'],
            ['عرض تدريب','دورة عملية في أساسيات Laravel وبناء REST APIs'],
            ['بيع أدوات','للبيع معدات تصوير مستعملة بحالة جيدة'],
            ['إعلان عام','متاح تنفيذ أعمال صيانة منزلية خلال أيام الأسبوع'],
            ['طلب خدمة','مطلوب نجار لتنفيذ مكتبة حائط حسب المقاس'],
            ['عرض خدمة','إعداد تقارير مالية ومسك دفاتر للمشاريع الصغيرة'],
            ['طلب خدمة','مطلوب مصمم هوية بصرية لمشروع غذائي جديد'],
            ['عرض خدمة','مونتاج فيديوهات قصيرة للمحتوى الإعلاني والسوشال ميديا'],
        ];
        $activeTypeIds = \App\Models\ActiveType::pluck('id','name')->toArray();

        $authors = array_merge(
            array_map(fn($x) => $x['user'], $createdOwners),
            $createdUsers
        );

        foreach ($postTitles as $i => [$type,$title]) {
            $author = $authors[$i % count($authors)];
            Post::firstOrCreate(
                ['user_id'=>$author->id,'title'=>$title],
                [
                    'active_type_id'=>$activeTypeIds[$type] ?? 1,
                    'description'=>$this->postDescription($title, $author->first_name),
                    'image'=>($i % 3 === 0) ? "https://picsum.photos/seed/skillify-demo-post-{$i}/640/480" : null,
                    'post_date'=>now()->subDays(2 + $i * 3)->setTime(9 + ($i % 8), 30),
                    'views'=>35 + ($i * 47),
                    'status'=>'published',
                ]
            );
        }

        $serviceCatalog = [
            ['business'=>0,'category'=>'الكهرباء والسباكة','subcategory'=>'تقني تكييف','name'=>'فحص وصيانة مكيف سبليت','price'=>12,'type'=>'usd','desc'=>'فحص الضاغط وتنظيف الوحدة الداخلية والخارجية وقياس ضغط الغاز وتحديد سبب ضعف التبريد.'],
            ['business'=>0,'category'=>'الكهرباء والسباكة','subcategory'=>'تقني تكييف','name'=>'تركيب مكيف سبليت جديد','price'=>18,'type'=>'usd','desc'=>'تركيب الوحدة الداخلية والخارجية مع تمديد المواسير واختبار التشغيل بعد التركيب.'],
            ['business'=>1,'category'=>'الفن والتصميم','subcategory'=>'مصمم جرافيك','name'=>'تصميم هوية بصرية لمشروع ناشئ','price'=>180,'type'=>'usd','desc'=>'شعار وهوية بصرية أساسية مع ألوان وخطوط وتطبيقات مناسبة للمنصات الرقمية.'],
            ['business'=>1,'category'=>'الفن والتصميم','subcategory'=>'مصمم جرافيك','name'=>'تصميم منشورات وحملات سوشال ميديا','price'=>75,'type'=>'usd','desc'=>'حزمة تصاميم متناسقة لحملة إعلانية تشمل منشورات وقصص بمقاسات المنصات الشائعة.'],
            ['business'=>2,'category'=>'تقنية المعلومات','subcategory'=>'مطور ويب','name'=>'تطوير متجر إلكتروني صغير','price'=>450,'type'=>'usd','desc'=>'تطوير متجر إلكتروني متجاوب مع إدارة المنتجات والطلبات ولوحة تحكم بسيطة.'],
            ['business'=>2,'category'=>'تقنية المعلومات','subcategory'=>'مطور ويب','name'=>'صيانة وتحسين موقع Laravel','price'=>120,'type'=>'usd','desc'=>'تحليل أخطاء Laravel وتحسين الاستعلامات وإصلاح المشاكل الوظيفية والأداء.'],
            ['business'=>3,'category'=>'استوديو تصوير','subcategory'=>'تصوير فوتوغرافي','name'=>'تصوير منتجات لمتجر إلكتروني','price'=>60,'type'=>'usd','desc'=>'تصوير المنتجات بخلفية مناسبة مع إضاءة احترافية وتسليم صور معدلة جاهزة للنشر.'],
            ['business'=>3,'category'=>'استوديو تصوير','subcategory'=>'تصوير فوتوغرافي','name'=>'تغطية مناسبة عائلية','price'=>100,'type'=>'usd','desc'=>'تغطية فوتوغرافية لمناسبة عائلية وتسليم مجموعة مختارة من الصور بعد المعالجة.'],
            ['business'=>4,'category'=>'التعليم','subcategory'=>'معلم','name'=>'دروس خصوصية في الرياضيات','price'=>8,'type'=>'usd','desc'=>'جلسات فردية لطلاب المرحلة الثانوية مع مراجعة المنهج وحل نماذج امتحانية.'],
            ['business'=>4,'category'=>'التعليم','subcategory'=>'معلم','name'=>'دروس فيزياء للمرحلة الثانوية','price'=>9,'type'=>'usd','desc'=>'شرح المفاهيم الأساسية والتطبيق على مسائل امتحانية مع متابعة مستوى الطالب.'],
            ['business'=>5,'category'=>'الكهرباء والسباكة','subcategory'=>'تقني تدفئة','name'=>'صيانة شبكة تدفئة منزلية','price'=>20,'type'=>'usd','desc'=>'فحص المضخة والتمديدات والمشعات ومعالجة التسريب أو ضعف الدوران.'],
            ['business'=>5,'category'=>'الكهرباء والسباكة','subcategory'=>'سباك','name'=>'إصلاح تسربات المياه المنزلية','price'=>250000,'type'=>'syp','desc'=>'تحديد مصدر التسرب وإصلاح الوصلات والتمديدات المتضررة مع اختبار الشبكة.'],
            ['business'=>6,'category'=>'النجارة والأثاث','subcategory'=>'نجار','name'=>'تصنيع مكتبة حائط حسب المقاس','price'=>250,'type'=>'usd','desc'=>'تصميم وتنفيذ مكتبة حائط حسب أبعاد المكان مع خيارات متعددة للألوان والتقسيمات.'],
            ['business'=>6,'category'=>'النجارة والأثاث','subcategory'=>'صانع أثاث','name'=>'ترميم خزانة خشبية قديمة','price'=>90,'type'=>'usd','desc'=>'فك القطع المتضررة وإعادة التأهيل والصنفرة والدهان مع الحفاظ على الشكل الأصلي.'],
            ['business'=>7,'category'=>'الخياطة والنسيج','subcategory'=>'خياطة نسائية','name'=>'تفصيل فستان سهرة حسب المقاس','price'=>80,'type'=>'usd','desc'=>'تفصيل فستان سهرة حسب المقاس مع اختيار القماش والتعديلات حتى التسليم النهائي.'],
            ['business'=>7,'category'=>'الخياطة والنسيج','subcategory'=>'خياطة نسائية','name'=>'تعديل فستان وتضييق المقاس','price'=>150000,'type'=>'syp','desc'=>'تعديلات دقيقة على القياس والطول والسحاب مع الحفاظ على شكل القطعة.'],
            ['business'=>8,'category'=>'الإعلام والصحافة','subcategory'=>'مونتير','name'=>'مونتاج فيديو إعلاني قصير','price'=>65,'type'=>'usd','desc'=>'مونتاج فيديو قصير للسوشال ميديا مع قص اللقطات وإضافة النصوص والموسيقى المناسبة.'],
            ['business'=>9,'category'=>'المحاسبة والمالية','subcategory'=>'محاسب','name'=>'إعداد تقرير مالي شهري','price'=>40,'type'=>'usd','desc'=>'تنظيم القيود وإعداد ملخص الإيرادات والمصروفات والتدفقات النقدية للمشروع.'],
            ['business'=>10,'category'=>'الهندسة','subcategory'=>'مهندس مدني','name'=>'إشراف هندسي على أعمال التشطيب','price'=>35,'type'=>'usd','desc'=>'زيارة ميدانية ومتابعة تنفيذ أعمال التشطيب ومطابقة المواصفات والكميات.'],
            ['business'=>11,'category'=>'شركة خدمات','subcategory'=>'نظافة وصيانة','name'=>'تنظيف شقة بعد أعمال الدهان','price'=>450000,'type'=>'syp','desc'=>'تنظيف شامل وإزالة آثار الدهان والغبار وتجهيز الشقة للسكن.'],
        ];

        foreach ($serviceCatalog as $i => $srv) {
            $owner = $createdOwners[$srv['business']];
            $city = $cities[$owner['city']];
            $category = Category::firstWhere('name',$srv['category']);
            $subcategory = Subcategory::firstWhere('name',$srv['subcategory']);

            if (!$category || !$subcategory) {
                continue;
            }

            Service::firstOrCreate(
                ['name'=>$srv['name'],'city_id'=>\App\Models\City::where('name',$owner['city'])->value('id')],
                [
                    'user_id'=>$owner['user']->id,
                    'description'=>$srv['desc'],
                    'category_id'=>$category->id,
                    'subcategory_id'=>$subcategory->id,
                    'city_id'=>\App\Models\City::where('name',$owner['city'])->value('id'),
                    'image'=>"https://picsum.photos/seed/skillify-demo-service-{$i}/640/480",
                    'price'=>$srv['price'],
                    'price_type'=>$srv['type'],
                    'is_active'=>true,
                    'status'=>'approved',
                ]
            );
        }

        // Service-request locations used by the map/discovery demo.
        $requestLocations = [
            ['city'=>'دمشق','lat'=>33.5197,'lon'=>36.2937],
            ['city'=>'دمشق','lat'=>33.5073,'lon'=>36.3019],
            ['city'=>'حلب','lat'=>36.1981,'lon'=>37.1512],
            ['city'=>'حلب','lat'=>36.2183,'lon'=>37.1089],
            ['city'=>'حمص','lat'=>34.7425,'lon'=>36.7241],
            ['city'=>'حمص','lat'=>34.7189,'lon'=>36.7056],
            ['city'=>'حماة','lat'=>35.1412,'lon'=>36.7683],
            ['city'=>'اللاذقية','lat'=>35.5214,'lon'=>35.7804],
            ['city'=>'طرطوس','lat'=>34.9073,'lon'=>35.8751],
            ['city'=>'درعا','lat'=>32.6250,'lon'=>36.1050],
            ['city'=>'السويداء','lat'=>32.7140,'lon'=>36.5710],
            ['city'=>'دمشق','lat'=>33.5412,'lon'=>36.2601],
        ];

        foreach ($requestLocations as $i => $loc) {
            $user = $createdUsers[$i % count($createdUsers)];
            Service_request::firstOrCreate(
                ['user_id'=>$user->id,'latitude'=>$loc['lat'],'longitude'=>$loc['lon']]
            );
        }

        // Ads: active, scheduled, ending soon, and pending.
        $admin = Admin::first();
        if ($admin) {
            $ads = [
                ['title'=>'خصم على صيانة المكيفات','description'=>'فحص وتنظيف وصيانة مكيفات السبليت قبل ارتفاع الطلب في فصل الصيف.','company_name'=>'حلول التبريد الحديثة','start_date'=>now()->subDays(4)->toDateString(),'end_date'=>now()->addDays(18)->toDateString(),'status'=>'approved'],
                ['title'=>'تصميم هوية بصرية للمشاريع الجديدة','description'=>'باقة إطلاق تشمل الشعار والألوان والخطوط وقوالب السوشال ميديا.','company_name'=>'استوديو راما للتصميم','start_date'=>now()->subDays(2)->toDateString(),'end_date'=>now()->addDays(28)->toDateString(),'status'=>'approved'],
                ['title'=>'عرض محدود على تنظيف المنازل','description'=>'حجز مسبق لخدمة التنظيف بعد الدهان أو الانتقال إلى منزل جديد.','company_name'=>'بيت مرتب للخدمات المنزلية','start_date'=>now()->subDays(20)->toDateString(),'end_date'=>now()->addDays(2)->toDateString(),'status'=>'approved'],
                ['title'=>'دورة Laravel للمبتدئين','description'=>'برنامج تدريبي عملي يركز على بناء REST APIs وقواعد البيانات والمصادقة.','company_name'=>'أكاديمية جنى التعليمية','start_date'=>now()->addDays(7)->toDateString(),'end_date'=>now()->addDays(45)->toDateString(),'status'=>'approved'],
                ['title'=>'خدمة تصوير منتجات للمتاجر','description'=>'جلسات تصوير مخصصة للمتاجر الإلكترونية مع تسليم صور جاهزة للنشر.','company_name'=>'ستوديو ضوء الساحل','start_date'=>now()->addDays(3)->toDateString(),'end_date'=>now()->addDays(30)->toDateString(),'status'=>'pending'],
            ];

            foreach ($ads as $i => $ad) {
                Advertisement::firstOrCreate(
                    ['title'=>$ad['title'],'company_name'=>$ad['company_name']],
                    array_merge($ad, [
                        'admin_id'=>$admin->id,
                        'image'=>"https://picsum.photos/seed/skillify-demo-ad-{$i}/600/400",
                    ])
                );
            }
        }

        // Reports intentionally point to real seeded posts so the moderation UI
        // has useful examples.
        $reportCases = [
            'مطلوب فني تكييف لفحص جهاز سبليت في حمص' => 'المنشور يكرر طلباً موجوداً بالفعل ويحتوي على تفاصيل غير واضحة عن الخدمة.',
            'للبيع معدات تصوير مستعملة بحالة جيدة' => 'المنشور لا يوضح حالة المعدات بشكل كافٍ ويحتاج مراجعة قبل النشر.',
            'مطلوب مصمم هوية بصرية لمشروع غذائي جديد' => 'المستخدم طلب نقل المحادثة إلى وسيلة خارجية قبل توضيح تفاصيل الطلب.',
        ];

        foreach ($reportCases as $title => $reason) {
            $post = Post::where('title',$title)->first();
            if (!$post) continue;

            $reporter = User::where('id','!=',$post->user_id)->inRandomOrder()->first();
            if (!$reporter) continue;

            Report::firstOrCreate(
                ['post_id'=>$post->id,'user_id'=>$reporter->id],
                ['reason'=>$reason]
            );
        }

        // Synthetic identity-verification cases for the admin queue.
        if (class_exists(IdentityVerification::class) && $admin) {
            $verificationCases = [
                [$createdOwners[0]['user'],'مازن الحمّصي','PRES-DEMO-1001','approved',98.4],
                [$createdOwners[1]['user'],'راما الشامي','PRES-DEMO-1002','approved',96.8],
                [$createdOwners[2]['user'],'كريم الحلبي','PRES-DEMO-1003','pending',null],
                [$createdOwners[5]['user'],'أنس الحموي','PRES-DEMO-1004','rejected',61.2],
                [$createdOwners[8]['user'],'فراس السويداني','PRES-DEMO-1005','pending',null],
                [$createdOwners[10]['user'],'عمار الحلبي','PRES-DEMO-1006','approved',94.7],
            ];

            foreach ($verificationCases as $i => [$user,$fullName,$idNumber,$status,$score]) {
                IdentityVerification::firstOrCreate(
                    ['id_number'=>$idNumber],
                    [
                        'user_id'=>$user->id,
                        'full_name'=>$fullName,
                        'id_type'=>'national_id',
                        'front_image'=>"identity/demo/front-{$i}.jpg",
                        'back_image'=>"identity/demo/back-{$i}.jpg",
                        'status'=>$status,
                        'match_score'=>$score,
                        'extracted_data'=>json_encode([
                            'full_name'=>$fullName,
                            'document_type'=>'national_id',
                            'synthetic_demo_record'=>true,
                            'note'=>'Demo data — not a real identity document.',
                        ], JSON_UNESCAPED_UNICODE),
                        'reviewed_by'=>in_array($status,['approved','rejected']) ? $admin->id : null,
                        'reviewed_at'=>in_array($status,['approved','rejected']) ? now()->subDays(1) : null,
                        'rejection_reason'=>$status === 'rejected'
                            ? 'البيانات المستخرجة لا تتطابق بدرجة كافية مع بيانات الحساب.'
                            : null,
                    ]
                );
            }
        }

        $this->command?->info('Skillify presentation dataset added successfully.');
        $this->command?->info('Synthetic demo accounts use password: password');
    }

    private function businessDescription(string $job, string $city): string
    {
        return "خدمة مهنية متخصصة في {$job} داخل {$city}. يتم استقبال الطلبات بعد توضيح نطاق العمل، وتحديد الموعد والتكلفة قبل البدء، مع التركيز على جودة التنفيذ والالتزام بالوقت.";
    }

    private function postDescription(string $title, string $author): string
    {
        return "مرحباً، أنا {$author}. {$title}. أرحب بالاستفسارات وتوضيح التفاصيل المطلوبة عبر رسائل المنصة، ويمكن الاتفاق على الموعد والسعر بعد معرفة نطاق العمل والموقع.";
    }
}
