import { useState } from 'react';
import { Head, Link } from '@inertiajs/react';

const DEMO_PROFESSIONALS = [
    {
        id: 'demo-1',
        name: 'د. أحمد السيد',
        name_job: 'طبيب قلبية',
        description: 'طبيب قلبية معتمد بخبرة تزيد عن ١٢ عاماً في علاج أمراض القلب وإجراء القسطرة القلبية.',
        image: null,
    },
    {
        id: 'demo-2',
        name: 'محمد العلي',
        name_job: 'مطوّر ويب متكامل',
        description: 'مهندس برمجيات متخصص في React وNode.js والحوسبة السحابية، نفّذ أكثر من ٥٠ مشروعاً ناجحاً.',
        image: null,
    },
    {
        id: 'demo-3',
        name: 'المحامية سارة الحسن',
        name_job: 'مستشارة قانونية',
        description: 'متخصصة في قانون الشركات والعقود وتسوية النزاعات، بخبرة واسعة في القضايا التجارية.',
        image: null,
    },
    {
        id: 'demo-4',
        name: 'ليلى نصر',
        name_job: 'مصممة داخلية',
        description: 'مصممة داخلية حاصلة على جوائز تصمم مساحات جميلة وعملية للمشاريع السكنية والتجارية.',
        image: null,
    },
    {
        id: 'demo-5',
        name: 'يوسف إبراهيم',
        name_job: 'محاسب قانوني معتمد',
        description: 'محاسب بخبرة ١٥ عاماً في التخطيط الضريبي والتدقيق المالي والاستشارات للشركات الصغيرة والمتوسطة.',
        image: null,
    },
    {
        id: 'demo-6',
        name: 'رنا خليل',
        name_job: 'استراتيجية تسويق',
        description: 'خبيرة تسويق رقمي متخصصة في بناء العلامات التجارية وتحقيق أعلى عائد على الاستثمار.',
        image: null,
    },
];

const DEMO_ADS = [
    {
        id: 'demo-ad-1',
        title: 'مطلوب مطوّر React متمرّس',
        description: 'نبحث عن مطوّر React موهوب للانضمام إلى شركتنا الناشئة في مجال التقنية المالية. عمل عن بُعد، راتب تنافسي.',
        company_name: 'FinTech Innovations',
        image: null,
    },
    {
        id: 'demo-ad-2',
        title: 'خدمات قانونية للشركات الناشئة',
        description: 'حزم قانونية متكاملة للشركات في مراحلها الأولى — من التأسيس إلى اتفاقيات المستثمرين. احجز استشارة مجانية.',
        company_name: 'LexBridge للمحاماة',
        image: null,
    },
    {
        id: 'demo-ad-3',
        title: 'محاسبة أعمال وتقديم إقرارات ضريبية',
        description: 'خدمات محاسبية احترافية مصمّمة للشركات الصغيرة: مسك الدفاتر الشهري، إقرارات ضريبة القيمة المضافة، والتقارير السنوية.',
        company_name: 'ClearBooks للمحاسبة',
        image: null,
    },
];

const DEMO_CATEGORIES = [
    { id: 'c1', name: 'الصحة والطب' },
    { id: 'c2', name: 'القانون والاستشارات' },
    { id: 'c3', name: 'الهندسة' },
    { id: 'c4', name: 'التعليم والتدريس' },
    { id: 'c5', name: 'المحاسبة والمالية' },
    { id: 'c6', name: 'تقنية المعلومات' },
    { id: 'c7', name: 'الفن والتصميم' },
    { id: 'c8', name: 'الإعلام والصحافة' },
];

export default function Landing({ ads = [], topProfessionals = [], categories = [], stats = {} }) {
    const [mobileOpen, setMobileOpen] = useState(false);

    const displayProfessionals = topProfessionals.length > 0 ? topProfessionals : DEMO_PROFESSIONALS;
    const displayAds           = ads.length > 0            ? ads            : DEMO_ADS;
    const displayCategories    = categories.length > 0     ? categories     : DEMO_CATEGORIES;
    const isDemo               = topProfessionals.length === 0;

    const avatar = (name) =>
        `https://ui-avatars.com/api/?name=${encodeURIComponent(name)}&background=0D9488&color=fff&size=128`;

    return (
        <>
            <Head title="Skillify — منصة المهنيين والشركات" />

            <div dir="rtl">

            {/* ── Navbar ── */}
            <nav className="fixed inset-x-0 top-0 z-50 bg-white/95 backdrop-blur-sm border-b border-gray-100 shadow-sm">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">

                    <div className="flex items-center gap-2.5">
                        <div className="w-8 h-8 rounded-lg bg-[#0D9488] flex items-center justify-center shadow-sm">
                            <svg className="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2.5} d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                            </svg>
                        </div>
                        <span className="text-xl font-bold text-gray-900 tracking-tight">Skillify</span>
                    </div>

                    <div className="hidden md:flex items-center gap-1 text-sm">
                        {[
                            { href: '#how',           label: 'كيف يعمل' },
                            { href: '#categories',    label: 'الفئات' },
                            { href: '#professionals', label: 'المهنيون' },
                            { href: '#ads',           label: 'الإعلانات' },
                        ].map(l => (
                            <a key={l.href} href={l.href}
                                className="px-3 py-2 rounded-lg text-gray-600 hover:text-[#0D9488] hover:bg-[#F0FDFA] transition-all">
                                {l.label}
                            </a>
                        ))}
                    </div>

                    <div className="hidden md:flex items-center gap-3">
                        <Link href="/login"
                            className="px-4 py-2 text-sm font-medium text-gray-700 border border-gray-200 rounded-lg hover:border-[#0D9488] hover:text-[#0D9488] transition-all">
                            تسجيل الدخول
                        </Link>
                        <Link href="/register"
                            className="px-4 py-2 text-sm font-semibold bg-[#0D9488] text-white rounded-lg hover:bg-[#0F766E] transition-all shadow-sm shadow-teal-200">
                            انضم مجاناً
                        </Link>
                    </div>

                    <button className="md:hidden p-2 text-gray-500" onClick={() => setMobileOpen(v => !v)}>
                        <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            {mobileOpen
                                ? <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M6 18L18 6M6 6l12 12" />
                                : <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M4 6h16M4 12h16M4 18h16" />
                            }
                        </svg>
                    </button>
                </div>

                {mobileOpen && (
                    <div className="md:hidden bg-white border-t border-gray-100 px-4 py-4 space-y-2">
                        <a href="#how"           className="block py-2 text-gray-600 hover:text-[#0D9488]" onClick={() => setMobileOpen(false)}>كيف يعمل</a>
                        <a href="#categories"    className="block py-2 text-gray-600 hover:text-[#0D9488]" onClick={() => setMobileOpen(false)}>الفئات</a>
                        <a href="#professionals" className="block py-2 text-gray-600 hover:text-[#0D9488]" onClick={() => setMobileOpen(false)}>المهنيون</a>
                        <a href="#ads"           className="block py-2 text-gray-600 hover:text-[#0D9488]" onClick={() => setMobileOpen(false)}>الإعلانات</a>
                        <div className="flex gap-3 pt-3 border-t border-gray-100">
                            <Link href="/login"    className="flex-1 text-center py-2.5 border border-gray-200 rounded-lg text-sm font-medium text-gray-700">تسجيل الدخول</Link>
                            <Link href="/register" className="flex-1 text-center py-2.5 bg-[#0D9488] rounded-lg text-sm font-semibold text-white">انضم مجاناً</Link>
                        </div>
                    </div>
                )}
            </nav>

            <main className="pt-16">

                {/* ── Hero ── */}
                <section className="relative min-h-[88vh] flex items-center overflow-hidden bg-gradient-to-br from-[#F0FDFA] via-white to-[#F0FDFA]">
                    <div className="absolute top-20 left-10 w-72 h-72 bg-[#0D9488]/10 rounded-full blur-3xl pointer-events-none" />
                    <div className="absolute bottom-20 right-10 w-96 h-96 bg-teal-400/10 rounded-full blur-3xl pointer-events-none" />

                    <div className="relative max-w-5xl mx-auto px-4 text-center w-full py-20">
                        <span className="inline-flex items-center gap-2 mb-6 px-4 py-1.5 bg-[#F0FDFA] text-[#0D9488] text-sm font-semibold rounded-full border border-[#0D9488]/20">
                            <span className="w-1.5 h-1.5 rounded-full bg-[#0D9488] animate-pulse" />
                            منصة المهنيين والشركات
                        </span>
                        <h1 className="text-5xl md:text-7xl font-extrabold text-gray-900 leading-[1.2] tracking-tight mb-6">
                            اكتشف أفضل
                            <br />
                            <span className="text-transparent bg-clip-text bg-gradient-to-r from-[#0D9488] to-[#0891B2]">
                                المهنيين
                            </span>
                            <br />
                            بكل سهولة
                        </h1>
                        <p className="text-xl text-gray-500 max-w-2xl mx-auto mb-10 leading-relaxed">
                            Skillify يربطك بأفضل المهنيين والشركات — تصفّح، تواصل، وابدأ مشروعك اليوم.
                        </p>
                        <div className="flex flex-col sm:flex-row gap-4 justify-center">
                            <Link href="/register"
                                className="inline-flex items-center justify-center gap-2 px-8 py-4 bg-[#0D9488] text-white font-bold rounded-2xl text-lg hover:bg-[#0F766E] transition-all shadow-xl shadow-teal-300/50">
                                ابدأ مجاناً
                                <svg className="w-5 h-5 rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2.5} d="M17 8l4 4m0 0l-4 4m4-4H3" />
                                </svg>
                            </Link>
                            <a href="#professionals"
                                className="inline-flex items-center justify-center gap-2 px-8 py-4 bg-white text-gray-800 font-bold rounded-2xl text-lg border border-gray-200 hover:border-[#0D9488] hover:text-[#0D9488] transition-all">
                                تصفح المهنيين
                            </a>
                        </div>

                        <div className="mt-12 flex items-center justify-center gap-6 text-sm text-gray-400 flex-wrap">
                            {['✅ تسجيل مجاني', '🔒 آمن وموثوق', '⚡ تواصل فوري', '🌍 آلاف المهنيين'].map(t => (
                                <span key={t}>{t}</span>
                            ))}
                        </div>
                    </div>
                </section>

                {/* ── Stats ── */}
                <section className="bg-[#134E4A] text-white py-14">
                    <div className="max-w-5xl mx-auto px-4 grid grid-cols-2 md:grid-cols-4 gap-8 text-center">
                        {[
                            { label: 'مهني مسجّل',      value: stats.professionals ?? 0, icon: '👨‍💼' },
                            { label: 'مستخدم نشط',      value: stats.users ?? 0,         icon: '👥' },
                            { label: 'فئة متخصصة',      value: stats.categories ?? 0,    icon: '📂' },
                            { label: 'خدمة متاحة',      value: stats.services ?? 0,      icon: '🛠️' },
                        ].map(s => (
                            <div key={s.label} className="flex flex-col items-center gap-1">
                                <div className="text-3xl mb-1">{s.icon}</div>
                                <div className="text-4xl font-extrabold text-white">{s.value.toLocaleString()}+</div>
                                <div className="text-sm text-teal-200">{s.label}</div>
                            </div>
                        ))}
                    </div>
                </section>

                {/* ── How it Works ── */}
                <section id="how" className="py-24 bg-white">
                    <div className="max-w-5xl mx-auto px-4 text-center">
                        <p className="text-[#0D9488] font-semibold text-sm mb-2 uppercase tracking-wider">كيف يعمل</p>
                        <h2 className="text-4xl font-extrabold text-gray-900 mb-3">ثلاث خطوات بسيطة</h2>
                        <p className="text-gray-500 mb-14 text-lg">ابدأ رحلتك مع Skillify</p>
                        <div className="grid grid-cols-1 md:grid-cols-3 gap-8">
                            {[
                                { n: '١', icon: '📝', title: 'أنشئ حسابك', desc: 'سجّل مجاناً في أقل من دقيقة', color: 'bg-teal-50 text-teal-600' },
                                { n: '٢', icon: '🔍', title: 'تصفح المهنيين', desc: 'استكشف مئات المهنيين وقارن خدماتهم وتقييماتهم', color: 'bg-violet-50 text-violet-600' },
                                { n: '٣', icon: '💬', title: 'تواصل مباشرةً', desc: 'راسل المهني فوراً وابدأ التعاون', color: 'bg-sky-50 text-sky-600' },
                            ].map(item => (
                                <div key={item.n} className="relative bg-gray-50 rounded-3xl p-8 text-center group hover:shadow-lg hover:-translate-y-1 transition-all duration-300">
                                    <div className="absolute -top-4 left-1/2 -translate-x-1/2 w-8 h-8 bg-[#0D9488] text-white text-sm font-extrabold rounded-full flex items-center justify-center shadow">
                                        {item.n}
                                    </div>
                                    <div className={`w-16 h-16 rounded-2xl ${item.color} mx-auto mb-4 flex items-center justify-center text-3xl`}>
                                        {item.icon}
                                    </div>
                                    <h3 className="text-lg font-bold text-gray-900 mb-2">{item.title}</h3>
                                    <p className="text-sm text-gray-500 leading-relaxed">{item.desc}</p>
                                </div>
                            ))}
                        </div>
                    </div>
                </section>

                {/* ── Categories ── */}
                <section id="categories" className="py-24 bg-[#F0FDFA]">
                    <div className="max-w-6xl mx-auto px-4">
                        <div className="text-center mb-14">
                            <p className="text-[#0D9488] font-semibold text-sm mb-2 uppercase tracking-wider">الفئات</p>
                            <h2 className="text-4xl font-extrabold text-gray-900 mb-3">تصفح حسب التخصص</h2>
                            <p className="text-gray-500 text-lg">اختر المجال الذي تبحث عنه</p>
                        </div>
                        <div className="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
                            {displayCategories.map(cat => (
                                <Link key={cat.id} href="/register"
                                    className="group bg-white hover:bg-[#0D9488] border border-gray-100 hover:border-[#0D9488] rounded-2xl p-5 text-center transition-all duration-300 hover:shadow-xl hover:-translate-y-1">
                                    <div className="w-12 h-12 rounded-xl bg-[#F0FDFA] group-hover:bg-white/20 mx-auto mb-3 flex items-center justify-center">
                                        <svg className="w-6 h-6 text-[#0D9488] group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                    <div className="text-sm font-semibold text-gray-800 group-hover:text-white transition-colors">
                                        {cat.name}
                                    </div>
                                </Link>
                            ))}
                        </div>
                    </div>
                </section>

                {/* ── Top Professionals ── */}
                <section id="professionals" className="py-24 bg-white">
                    <div className="max-w-6xl mx-auto px-4">
                        <div className="text-center mb-14">
                            <p className="text-[#0D9488] font-semibold text-sm mb-2 uppercase tracking-wider">المهنيون</p>
                            <h2 className="text-4xl font-extrabold text-gray-900 mb-3">أبرز المهنيين الموثّقين</h2>
                            <p className="text-gray-500 text-lg">
                                {isDemo
                                    ? 'نموذج عن المهنيين الذين ستجدهم على Skillify'
                                    : 'نخبة من المهنيين الموثّقين على المنصة'}
                            </p>
                            {isDemo && (
                                <span className="inline-block mt-3 px-3 py-1 bg-amber-50 text-amber-600 text-xs font-semibold rounded-full border border-amber-200">
                                    ✨ ملفات تجريبية — مهنيون حقيقيون قريباً
                                </span>
                            )}
                        </div>
                        <div className="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
                            {displayProfessionals.map(pro => (
                                <div key={pro.id} className="group bg-white rounded-3xl p-6 shadow-sm border border-gray-100 hover:shadow-xl hover:-translate-y-1 transition-all duration-300 flex flex-col items-center text-center">
                                    <div className="relative mb-4">
                                        <img
                                            src={pro.image ? `/storage/${pro.image}` : avatar(pro.name)}
                                            alt={pro.name}
                                            className="w-20 h-20 rounded-2xl object-cover ring-4 ring-[#F0FDFA] group-hover:ring-[#0D9488]/30 transition-all"
                                            onError={e => { e.target.src = avatar(pro.name); }}
                                        />
                                        <span className="absolute -bottom-1 -right-1 w-5 h-5 bg-emerald-400 border-2 border-white rounded-full" />
                                    </div>
                                    <h3 className="font-bold text-gray-900 text-base">{pro.name}</h3>
                                    <p className="text-sm text-[#0D9488] font-semibold mt-0.5 mb-2">{pro.name_job}</p>
                                    <p className="text-xs text-gray-400 line-clamp-2 leading-relaxed">{pro.description}</p>
                                    <Link href="/register"
                                        className="mt-4 w-full py-2.5 text-sm font-semibold border-2 border-[#F0FDFA] text-[#0D9488] rounded-xl hover:bg-[#0D9488] hover:text-white hover:border-[#0D9488] transition-all">
                                        عرض الملف الشخصي
                                    </Link>
                                </div>
                            ))}
                        </div>
                    </div>
                </section>

                {/* ── Ads / Listings ── */}
                <section id="ads" className="py-24 bg-[#F0FDFA]">
                    <div className="max-w-6xl mx-auto px-4">
                        <div className="text-center mb-14">
                            <p className="text-[#0D9488] font-semibold text-sm mb-2 uppercase tracking-wider">الإعلانات</p>
                            <h2 className="text-4xl font-extrabold text-gray-900 mb-3">أحدث الفرص</h2>
                            <p className="text-gray-500 text-lg">
                                {ads.length === 0
                                    ? 'نموذج عن الفرص التي ستجدها على Skillify'
                                    : 'أحدث الفرص والعروض المتاحة'}
                            </p>
                            {ads.length === 0 && (
                                <span className="inline-block mt-3 px-3 py-1 bg-amber-50 text-amber-600 text-xs font-semibold rounded-full border border-amber-200">
                                    ✨ إعلانات تجريبية — إعلانات حقيقية قريباً
                                </span>
                            )}
                        </div>
                        <div className="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
                            {displayAds.map(ad => (
                                <div key={ad.id} className="group bg-white rounded-3xl overflow-hidden shadow-sm border border-gray-100 hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
                                    <div className="h-44 bg-gradient-to-br from-[#F0FDFA] to-[#F0FDFA] overflow-hidden relative">
                                        {ad.image
                                            ? <img src={`/storage/${ad.image}`} alt={ad.title} className="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" />
                                            : (
                                                <div className="w-full h-full flex flex-col items-center justify-center gap-2">
                                                    <div className="w-14 h-14 rounded-2xl bg-[#0D9488]/10 flex items-center justify-center text-3xl">📢</div>
                                                    <span className="text-xs text-[#0D9488]/60 font-medium">{ad.company_name}</span>
                                                </div>
                                            )
                                        }
                                        <div className="absolute inset-0 bg-gradient-to-t from-black/10 to-transparent opacity-0 group-hover:opacity-100 transition-opacity" />
                                    </div>
                                    <div className="p-5">
                                        <div className="inline-flex items-center gap-1 text-xs text-[#0D9488] font-semibold bg-[#F0FDFA] px-2.5 py-1 rounded-full mb-3">
                                            🏢 {ad.company_name}
                                        </div>
                                        <h3 className="font-bold text-gray-900 text-base mb-1.5 line-clamp-1">{ad.title}</h3>
                                        <p className="text-sm text-gray-500 line-clamp-2 leading-relaxed">{ad.description}</p>
                                        <Link href="/register"
                                            className="mt-4 inline-flex items-center gap-1 text-sm text-[#0D9488] font-semibold hover:gap-2 transition-all">
                                            اعرف أكثر
                                            <svg className="w-4 h-4 rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2.5} d="M9 5l7 7-7 7" />
                                            </svg>
                                        </Link>
                                    </div>
                                </div>
                            ))}
                        </div>
                    </div>
                </section>

                {/* ── CTA / Login Card ── */}
                <section className="py-28 bg-gradient-to-br from-[#134E4A] via-[#134E4A] to-[#0D9488] relative overflow-hidden">
                    <div className="absolute inset-0 opacity-20"
                        style={{ backgroundImage: 'radial-gradient(circle at 20% 50%, white 0%, transparent 50%), radial-gradient(circle at 80% 20%, rgba(139,92,246,0.5) 0%, transparent 50%)' }} />
                    <div className="relative max-w-4xl mx-auto px-4 text-center text-white">
                        <h2 className="text-4xl md:text-5xl font-extrabold mb-4 leading-tight">
                            هل أنت مستعد للبدء؟
                        </h2>
                        <p className="text-teal-200 text-xl mb-12 max-w-xl mx-auto">
                            انضم إلى آلاف المهنيين والشركات على Skillify — مجاناً وفورياً.
                        </p>

                        <div className="max-w-sm mx-auto bg-white/10 backdrop-blur-md border border-white/20 rounded-3xl p-8 shadow-2xl">
                            <h3 className="text-lg font-bold mb-6 text-white">ادخل إلى حسابك</h3>
                            <div className="space-y-3">
                                <Link href="/login"
                                    className="flex items-center justify-center gap-2.5 w-full py-3.5 bg-white text-[#0D9488] font-bold rounded-2xl hover:bg-[#F0FDFA] transition-all text-sm shadow-lg">
                                    <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                                    </svg>
                                    تسجيل الدخول
                                </Link>
                                <Link href="/register"
                                    className="flex items-center justify-center gap-2.5 w-full py-3.5 bg-[#0D9488] text-white font-bold rounded-2xl hover:bg-[#0F766E] transition-all text-sm border border-white/20">
                                    <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                                    </svg>
                                    إنشاء حساب جديد
                                </Link>
                            </div>
                            <p className="text-teal-200/70 text-xs mt-5">
                                بالتسجيل، أنت توافق على شروط الخدمة وسياسة الخصوصية.
                            </p>
                        </div>
                    </div>
                </section>

                {/* ── Footer ── */}
                <footer className="bg-[#0D1F1E] text-gray-500 py-12">
                    <div className="max-w-6xl mx-auto px-4">
                        <div className="flex flex-col md:flex-row items-center justify-between gap-6">
                            <div className="flex items-center gap-2.5">
                                <div className="w-7 h-7 rounded-lg bg-[#0D9488] flex items-center justify-center">
                                    <svg className="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2.5} d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                                    </svg>
                                </div>
                                <span className="text-white font-bold">Skillify</span>
                            </div>
                            <div className="flex gap-6 text-sm">
                                <a href="#" className="hover:text-white transition-colors">من نحن</a>
                                <a href="#" className="hover:text-white transition-colors">سياسة الخصوصية</a>
                                <a href="#" className="hover:text-white transition-colors">تواصل معنا</a>
                            </div>
                            <p className="text-xs">© {new Date().getFullYear()} Skillify. جميع الحقوق محفوظة.</p>
                        </div>
                    </div>
                </footer>

            </main>

            </div>
        </>
    );
}
