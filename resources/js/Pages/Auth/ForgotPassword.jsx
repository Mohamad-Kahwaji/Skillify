import { Head, Link, useForm } from '@inertiajs/react';

export default function ForgotPassword() {
    const { data, setData, post, processing, errors } = useForm({
        phone: '',
    });

    const submit = () => post('/forgot-password');

    return (
        <>
            <Head title="نسيت كلمة المرور — Skillify" />

            <div dir="rtl" className="min-h-screen flex">

                {/* ── Left: Brand Panel ── */}
                <div className="hidden lg:flex lg:w-1/2 relative bg-gradient-to-br from-[#134E4A] via-[#134E4A] to-[#0D9488] flex-col items-center justify-center p-12 overflow-hidden">
                    <div className="absolute top-0 left-0 w-80 h-80 bg-white/5 rounded-full -translate-y-1/2 -translate-x-1/2" />
                    <div className="absolute bottom-0 right-0 w-64 h-64 bg-teal-400/10 rounded-full translate-y-1/2 translate-x-1/2" />

                    <div className="relative text-center text-white max-w-xs">
                        <div className="flex items-center justify-center mb-10">
                            <img src="/images/logo.png" alt="Skillify" className="h-14 w-auto" />
                        </div>

                        <h2 className="text-3xl font-extrabold mb-4 leading-tight">
                            استعادة كلمة المرور
                        </h2>
                        <p className="text-teal-200 text-base leading-relaxed mb-10">
                            لا مشكلة، بيصير مع الكل. أدخل رقم هاتفك وبنرسلّك رمز تحقق لاستعادة حسابك.
                        </p>

                        <div className="space-y-4 text-right">
                            {[
                                { icon: '📱', text: 'رمز تحقق عبر واتساب' },
                                { icon: '🔒', text: 'إجراء آمن ومشفّر' },
                                { icon: '⚡', text: 'استعادة الحساب خلال دقائق' },
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
                    <div className="lg:hidden flex items-center mb-10">
                        <img src="/images/logo-dark-text.jpg" alt="Skillify" className="h-14 w-auto" />
                    </div>

                    <div className="w-full max-w-sm">
                        <div className="bg-white rounded-3xl shadow-xl border border-gray-100 p-8">

                            {/* Header */}
                            <div className="text-center mb-8">
                                <div className="w-14 h-14 mx-auto mb-4 rounded-2xl bg-[#0D9488]/10 flex items-center justify-center">
                                    <svg className="w-7 h-7 text-[#0D9488]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                    </svg>
                                </div>
                                <h1 className="text-xl font-bold text-gray-900">نسيت كلمة المرور؟</h1>
                                <p className="text-sm text-gray-500 mt-2">
                                    أدخل رقم هاتفك المسجل وسنرسل لك رمز تحقق عبر واتساب
                                </p>
                            </div>

                            {/* Phone */}
                            <div className="mb-6">
                                <label className="block text-sm font-semibold text-gray-700 mb-1.5">رقم الهاتف</label>
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
                                        onKeyDown={e => e.key === 'Enter' && submit()}
                                        placeholder="05xxxxxxxx"
                                        autoFocus
                                        className={`w-full pl-10 pr-4 py-3 bg-white border rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#0D9488]/30 focus:border-[#0D9488] transition-all placeholder:text-gray-300 ${errors.phone ? 'border-red-400' : 'border-gray-200'}`}
                                    />
                                </div>
                                {errors.phone && <p className="text-red-500 text-xs mt-1.5">{errors.phone}</p>}
                            </div>

                            {/* Submit */}
                            <button
                                onClick={submit}
                                disabled={processing}
                                className="w-full py-3.5 bg-[#0D9488] hover:bg-[#0F766E] text-white text-sm font-bold rounded-xl transition-all shadow-lg shadow-teal-200 disabled:opacity-60 disabled:cursor-not-allowed flex items-center justify-center gap-2"
                            >
                                {processing ? (
                                    <span className="w-4 h-4 border-2 border-white/30 border-t-white rounded-full animate-spin" />
                                ) : (
                                    <>
                                        <svg className="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                                        </svg>
                                        إرسال رمز التحقق
                                    </>
                                )}
                            </button>

                            {/* Back to login */}
                            <p className="text-center text-sm text-gray-500 mt-6">
                                تذكرت كلمة المرور؟{' '}
                                <Link href="/login" className="text-[#0D9488] font-bold hover:underline">
                                    تسجيل الدخول
                                </Link>
                            </p>
                        </div>

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
