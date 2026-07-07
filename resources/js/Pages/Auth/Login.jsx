import { useState } from 'react';
import { Head, Link, useForm } from '@inertiajs/react';

export default function Login() {
    const [showPass, setShowPass] = useState(false);

    const { data, setData, post, processing, errors } = useForm({
        phone: '',
        password: '',
        remember: false,
    });

    const submit = (e) => {
        e.preventDefault();
        post('/login');
    };

    return (
        <>
            <Head title="تسجيل الدخول — Skillify" />

            <div className="min-h-screen flex">

                {/* ── Left: Brand Panel ── */}
                <div className="hidden lg:flex lg:w-1/2 relative bg-gradient-to-br from-[#134E4A] via-[#134E4A] to-[#0D9488] flex-col items-center justify-center p-12 overflow-hidden">
                    <div className="absolute top-0 left-0 w-80 h-80 bg-white/5 rounded-full -translate-y-1/2 -translate-x-1/2" />
                    <div className="absolute bottom-0 right-0 w-64 h-64 bg-teal-400/10 rounded-full translate-y-1/2 translate-x-1/2" />

                    <div className="relative text-center text-white max-w-xs">
                        <div className="flex items-center justify-center gap-3 mb-10">
                            <div className="w-12 h-12 rounded-2xl bg-white/15 flex items-center justify-center backdrop-blur-sm border border-white/20">
                                <svg className="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2.5} d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                                </svg>
                            </div>
                            <span className="text-2xl font-extrabold tracking-tight">Skillify</span>
                        </div>

                        <h2 className="text-3xl font-extrabold mb-4 leading-tight">
                            مرحباً بعودتك!
                        </h2>
                        <p className="text-teal-200 text-base leading-relaxed mb-10">
                            سجّل دخولك للوصول إلى أفضل المهنيين والخدمات على المنصة.
                        </p>

                        <div className="space-y-4 text-right">
                            {[
                                { icon: '⚡', text: 'تواصل فوري مع المهنيين' },
                                { icon: '🔒', text: 'بيانات آمنة ومشفرة' },
                                { icon: '🌟', text: 'آلاف المهنيين الموثوقين' },
                            ].map(f => (
                                <div key={f.text} className="flex items-center gap-3 bg-white/10 rounded-2xl px-4 py-3 backdrop-blur-sm">
                                    <span className="text-xl">{f.icon}</span>
                                    <span className="text-sm font-medium text-teal-100">{f.text}</span>
                                </div>
                            ))}
                        </div>
                    </div>
                </div>

                {/* ── Right: Form Panel ── */}
                <div className="w-full lg:w-1/2 flex flex-col items-center justify-center bg-[#F0FDFA] px-6 py-12">
                    {/* Mobile logo */}
                    <div className="lg:hidden flex items-center gap-2.5 mb-10">
                        <div className="w-9 h-9 rounded-xl bg-[#0D9488] flex items-center justify-center">
                            <svg className="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2.5} d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                            </svg>
                        </div>
                        <span className="text-xl font-extrabold text-gray-900">Skillify</span>
                    </div>

                    <div className="w-full max-w-sm">
                        <div className="mb-8">
                            <h1 className="text-3xl font-extrabold text-gray-900 mb-2">تسجيل الدخول</h1>
                            <p className="text-gray-500 text-sm">أدخل بيانات حسابك للوصول إلى لوحتك</p>
                        </div>

                        <form onSubmit={submit} className="space-y-5">

                            {/* Phone */}
                            <div>
                                <label className="block text-sm font-semibold text-gray-700 mb-1.5">
                                    رقم الهاتف
                                </label>
                                <div className="relative">
                                    <span className="absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400">
                                        <svg className="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                        </svg>
                                    </span>
                                    <input
                                        type="tel"
                                        value={data.phone}
                                        onChange={e => setData('phone', e.target.value)}
                                        placeholder="05xxxxxxxx"
                                        className={`w-full pl-10 pr-4 py-3 bg-white border rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#0D9488]/30 focus:border-[#0D9488] transition-all placeholder:text-gray-300 ${errors.phone ? 'border-red-400' : 'border-gray-200'}`}
                                    />
                                </div>
                                {errors.phone && <p className="text-red-500 text-xs mt-1.5">{errors.phone}</p>}
                            </div>

                            {/* Password */}
                            <div>
                                <div className="flex items-center justify-between mb-1.5">
                                    <label className="text-sm font-semibold text-gray-700">كلمة المرور</label>
                                    <Link href="/forgot-password" className="text-xs text-[#0D9488] hover:underline font-medium">
                                        نسيت كلمة المرور؟
                                    </Link>
                                </div>
                                <div className="relative">
                                    <span className="absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400">
                                        <svg className="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                        </svg>
                                    </span>
                                    <input
                                        type={showPass ? 'text' : 'password'}
                                        value={data.password}
                                        onChange={e => setData('password', e.target.value)}
                                        placeholder="••••••••"
                                        className={`w-full pl-10 pr-10 py-3 bg-white border rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#0D9488]/30 focus:border-[#0D9488] transition-all ${errors.password ? 'border-red-400' : 'border-gray-200'}`}
                                    />
                                    <button type="button" onClick={() => setShowPass(v => !v)}
                                        className="absolute right-3.5 top-1/2 -translate-y-1/2 text-gray-400 hover:text-[#0D9488] transition-colors">
                                        {showPass
                                            ? <svg className="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" /></svg>
                                            : <svg className="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                        }
                                    </button>
                                </div>
                                {errors.password && <p className="text-red-500 text-xs mt-1.5">{errors.password}</p>}
                            </div>

                            {/* Remember */}
                            <label className="flex items-center gap-3 cursor-pointer group">
                                <div className="relative">
                                    <input type="checkbox" checked={data.remember} onChange={e => setData('remember', e.target.checked)} className="sr-only peer" />
                                    <div className="w-5 h-5 rounded border-2 border-gray-200 peer-checked:bg-[#0D9488] peer-checked:border-[#0D9488] transition-all flex items-center justify-center">
                                        {data.remember && (
                                            <svg className="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={3} d="M5 13l4 4L19 7" />
                                            </svg>
                                        )}
                                    </div>
                                </div>
                                <span className="text-sm text-gray-600 group-hover:text-gray-800 transition-colors">تذكرني</span>
                            </label>

                            {/* Submit */}
                            <button type="submit" disabled={processing}
                                className="w-full py-3.5 bg-[#0D9488] text-white font-bold rounded-xl hover:bg-[#0F766E] disabled:opacity-60 disabled:cursor-not-allowed transition-all shadow-lg shadow-teal-200 text-sm flex items-center justify-center gap-2">
                                {processing && (
                                    <svg className="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24">
                                        <circle className="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" strokeWidth="4" />
                                        <path className="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z" />
                                    </svg>
                                )}
                                {processing ? 'جارٍ الدخول...' : 'تسجيل الدخول'}
                            </button>
                        </form>

                        <p className="text-center text-sm text-gray-500 mt-6">
                            ليس لديك حساب؟{' '}
                            <Link href="/register" className="text-[#0D9488] font-bold hover:underline">
                                انضم إلى Skillify
                            </Link>
                        </p>

                        <div className="mt-4 text-center">
                            <Link href="/" className="text-xs text-gray-400 hover:text-gray-600 transition-colors inline-flex items-center gap-1">
                                <svg className="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                                </svg>
                                العودة للرئيسية
                            </Link>
                        </div>
                    </div>
                </div>

            </div>
        </>
    );
}
