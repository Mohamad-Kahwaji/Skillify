import { Head, Link, useForm } from '@inertiajs/react';
import { useRef, useState } from 'react';

function EyeIcon({ open }) {
    return open ? (
        <svg className="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18" />
        </svg>
    ) : (
        <svg className="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
        </svg>
    );
}

function PasswordField({ label, value, error, show, toggle, placeholder, autoFocus = false, inputRef, onChange, onEnter }) {
    return (
        <div className="mb-5">
            <label className="block text-sm font-semibold text-gray-700 mb-1.5">{label}</label>
            <div className="relative">
                <span className="absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400">
                    <svg className="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                </span>
                <input
                    ref={inputRef}
                    type={show ? 'text' : 'password'}
                    value={value}
                    onChange={onChange}
                    onKeyDown={e => e.key === 'Enter' && onEnter()}
                    placeholder={placeholder}
                    autoFocus={autoFocus}
                    className={`w-full pl-10 pr-11 py-3 bg-white border rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#0D9488]/30 focus:border-[#0D9488] transition-all placeholder:text-gray-300 ${error ? 'border-red-400' : 'border-gray-200'}`}
                />
                <button
                    type="button"
                    onClick={toggle}
                    className="absolute right-3.5 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 transition-colors"
                >
                    <EyeIcon open={show} />
                </button>
            </div>
            {error && <p className="text-red-500 text-xs mt-1.5">{error}</p>}
        </div>
    );
}

export default function ResetPassword() {
    const { data, setData, post, processing, errors } = useForm({
        password: '',
        password_confirmation: '',
    });

    const [showPass, setShowPass] = useState(false);
    const [showConfirm, setShowConfirm] = useState(false);
    const passwordRef = useRef(null);

    const submit = () => post('/reset-password', {
        onError: () => passwordRef.current?.focus(),
    });

    return (
        <>
            <Head title="كلمة مرور جديدة — Skillify" />

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
                            كلمة مرور جديدة وآمنة
                        </h2>
                        <p className="text-teal-200 text-base leading-relaxed mb-10">
                            تم التحقق من هويتك بنجاح، اختر كلمة مرور قوية لحماية حسابك.
                        </p>

                        <div className="space-y-4 text-right">
                            {[
                                { icon: '✅', text: '8 أحرف على الأقل' },
                                { icon: '🔠', text: 'مزيج من الأحرف الكبيرة والصغيرة' },
                                { icon: '🔢', text: 'أرقام أو رموز خاصة' },
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
                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                    </svg>
                                </div>
                                <h1 className="text-xl font-bold text-gray-900">كلمة المرور الجديدة</h1>
                                <p className="text-sm text-gray-500 mt-2">
                                    تم التحقق من هويتك بنجاح، اختر كلمة مرور جديدة لحسابك
                                </p>
                            </div>

                            <PasswordField
                                label="كلمة المرور الجديدة"
                                value={data.password}
                                error={errors.password}
                                onChange={e => setData('password', e.target.value)}
                                onEnter={submit}
                                show={showPass}
                                toggle={() => setShowPass(s => !s)}
                                placeholder="8 أحرف على الأقل"
                                autoFocus
                                inputRef={passwordRef}
                            />

                            <PasswordField
                                label="تأكيد كلمة المرور"
                                value={data.password_confirmation}
                                error={errors.password_confirmation}
                                onChange={e => setData('password_confirmation', e.target.value)}
                                onEnter={submit}
                                show={showConfirm}
                                toggle={() => setShowConfirm(s => !s)}
                                placeholder="أعد كتابة كلمة المرور"
                            />

                            {/* Submit */}
                            <button
                                onClick={submit}
                                disabled={processing}
                                className="w-full mt-1 py-3.5 bg-[#0D9488] hover:bg-[#0F766E] text-white text-sm font-bold rounded-xl transition-all shadow-lg shadow-teal-200 disabled:opacity-60 disabled:cursor-not-allowed flex items-center justify-center gap-2"
                            >
                                {processing ? (
                                    <span className="w-4 h-4 border-2 border-white/30 border-t-white rounded-full animate-spin" />
                                ) : (
                                    <>
                                        <svg className="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M5 13l4 4L19 7" />
                                        </svg>
                                        حفظ كلمة المرور
                                    </>
                                )}
                            </button>
                        </div>

                        <div className="mt-4 text-center">
                            <Link href="/login" className="text-xs text-gray-400 hover:text-gray-600 transition-colors inline-flex items-center gap-1">
                                <svg className="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                                </svg>
                                العودة لتسجيل الدخول
                            </Link>
                        </div>
                    </div>
                </div>

            </div>
        </>
    );
}
