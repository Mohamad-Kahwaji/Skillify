import { Link, useForm } from '@inertiajs/react';

export default function ForgotPassword() {
    const { data, setData, post, processing, errors } = useForm({
        phone: '',
    });

    const submit = () => post('/forgot-password');

    return (
        <div dir="rtl" className="min-h-screen bg-gray-50 flex items-center justify-center px-4">
            <div className="w-full max-w-md">

                <div className="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">

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
                        className="w-full py-3 bg-[#0D9488] hover:bg-[#0B7C72] text-white text-sm font-semibold rounded-xl transition-colors disabled:opacity-60 disabled:cursor-not-allowed flex items-center justify-center gap-2"
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
                        <Link href="/login" className="text-[#0D9488] font-medium hover:underline">
                            تسجيل الدخول
                        </Link>
                    </p>

                </div>
            </div>
        </div>
    );
}
