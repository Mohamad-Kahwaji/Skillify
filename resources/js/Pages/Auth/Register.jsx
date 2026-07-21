import { useState } from 'react';
import { Head, Link, useForm } from '@inertiajs/react';

function Field({ label, error, children }) {
    return (
        <div>
            <label className="block text-sm font-semibold text-gray-700 mb-1.5">{label}</label>
            {children}
            {error && <p className="text-red-500 text-xs mt-1">{error}</p>}
        </div>
    );
}

const inputClass = (err) =>
    `w-full px-4 py-3 bg-white border rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#0D9488]/30 focus:border-[#0D9488] transition-all placeholder:text-gray-300 ${err ? 'border-red-400' : 'border-gray-200'}`;

export default function Register() {
    const [showPass, setShowPass] = useState(false);
    const [showConfirm, setShowConfirm] = useState(false);

    const { data, setData, post, processing, errors } = useForm({
        first_name: '',
        middle_name: '',
        last_name: '',
        phone: '',
        email: '',
        password: '',
        password_confirmation: '',
        gender: '',
        city: '',
        birthdate: '',
    });

    const submit = (e) => {
        e.preventDefault();
        post('/register');
    };

    return (
        <>
            <Head title="إنشاء حساب — Skillify" />

            <div className="min-h-screen bg-[#F0FDFA] flex flex-col items-center justify-center px-4 py-12">

                {/* Logo */}
                <Link href="/" className="flex items-center mb-8">
                    <img src="/images/logo-dark-text.jpg" alt="Skillify" className="h-16 w-auto" />
                </Link>

                <div className="w-full max-w-2xl bg-white rounded-3xl shadow-xl border border-gray-100 overflow-hidden">
                    {/* Header */}
                    <div className="bg-gradient-to-r from-[#0D9488] to-[#134E4A] px-10 py-8 text-white">
                        <h1 className="text-2xl font-extrabold mb-1">إنشاء حساب جديد</h1>
                        <p className="text-teal-200 text-sm">انضم إلى Skillify مجاناً وابدأ رحلتك</p>
                        <div className="flex items-center gap-2 mt-4">
                            {['المعلومات الشخصية', 'تفاصيل الحساب'].map((s, i) => (
                                <div key={s} className="flex items-center gap-2">
                                    <div className="flex items-center gap-1.5">
                                        <div className="w-5 h-5 rounded-full bg-white/30 flex items-center justify-center text-xs font-bold">{i + 1}</div>
                                        <span className="text-xs text-teal-200">{s}</span>
                                    </div>
                                    {i === 0 && <div className="w-6 h-px bg-white/30" />}
                                </div>
                            ))}
                        </div>
                    </div>

                    <form onSubmit={submit} className="px-10 py-8 space-y-5">

                        {/* Row: first + middle + last name */}
                        <div className="grid grid-cols-3 gap-4">
                            <Field label="الاسم الأول" error={errors.first_name}>
                                <input type="text" value={data.first_name} onChange={e => setData('first_name', e.target.value)}
                                    placeholder="محمد" className={inputClass(errors.first_name)} />
                            </Field>
                            <Field label="الاسم الأوسط" error={errors.middle_name}>
                                <input type="text" value={data.middle_name} onChange={e => setData('middle_name', e.target.value)}
                                    placeholder="علي" className={inputClass(errors.middle_name)} />
                            </Field>
                            <Field label="الاسم الأخير" error={errors.last_name}>
                                <input type="text" value={data.last_name} onChange={e => setData('last_name', e.target.value)}
                                    placeholder="أحمد" className={inputClass(errors.last_name)} />
                            </Field>
                        </div>

                        {/* Row: gender + birthdate */}
                        <div className="grid grid-cols-2 gap-4">
                            <Field label="الجنس" error={errors.gender}>
                                <select value={data.gender} onChange={e => setData('gender', e.target.value)}
                                    className={inputClass(errors.gender)}>
                                    <option value="">اختر...</option>
                                    <option value="male">ذكر</option>
                                    <option value="female">أنثى</option>
                                </select>
                            </Field>
                            <Field label="تاريخ الميلاد (اختياري)" error={errors.birthdate}>
                                <input type="date" value={data.birthdate} onChange={e => setData('birthdate', e.target.value)}
                                    className={inputClass(errors.birthdate)} />
                            </Field>
                        </div>

                        {/* City */}
                        <Field label="المدينة" error={errors.city}>
                            <input type="text" value={data.city} onChange={e => setData('city', e.target.value)}
                                placeholder="دمشق" className={inputClass(errors.city)} />
                        </Field>

                        <div className="h-px bg-gray-100 my-1" />

                        {/* Phone */}
                        <Field label="رقم الهاتف" error={errors.phone}>
                            <input type="tel" value={data.phone} onChange={e => setData('phone', e.target.value)}
                                placeholder="05xxxxxxxx" className={inputClass(errors.phone)} />
                        </Field>

                        {/* Email */}
                        <Field label="البريد الإلكتروني" error={errors.email}>
                            <input type="email" value={data.email} onChange={e => setData('email', e.target.value)}
                                placeholder="name@example.com" className={inputClass(errors.email)} />
                        </Field>

                        {/* Password */}
                        <Field label="كلمة المرور" error={errors.password}>
                            <div className="relative">
                                <input type={showPass ? 'text' : 'password'} value={data.password}
                                    onChange={e => setData('password', e.target.value)}
                                    placeholder="٨ أحرف على الأقل"
                                    className={inputClass(errors.password) + ' pr-10'} />
                                <button type="button" onClick={() => setShowPass(v => !v)}
                                    className="absolute right-3.5 top-1/2 -translate-y-1/2 text-gray-400 hover:text-[#0D9488] transition-colors">
                                    <svg className="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d={showPass ? "M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" : "M15 12a3 3 0 11-6 0 3 3 0 016 0zM2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"} />
                                    </svg>
                                </button>
                            </div>
                        </Field>

                        {/* Confirm Password */}
                        <Field label="تأكيد كلمة المرور" error={errors.password_confirmation}>
                            <div className="relative">
                                <input type={showConfirm ? 'text' : 'password'} value={data.password_confirmation}
                                    onChange={e => setData('password_confirmation', e.target.value)}
                                    placeholder="••••••••"
                                    className={inputClass(errors.password_confirmation) + ' pr-10'} />
                                <button type="button" onClick={() => setShowConfirm(v => !v)}
                                    className="absolute right-3.5 top-1/2 -translate-y-1/2 text-gray-400 hover:text-[#0D9488] transition-colors">
                                    <svg className="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d={showConfirm ? "M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" : "M15 12a3 3 0 11-6 0 3 3 0 016 0zM2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"} />
                                    </svg>
                                </button>
                            </div>
                        </Field>

                        {/* Terms */}
                        <p className="text-xs text-gray-400 text-center">
                            بالتسجيل، أنت توافق على{' '}
                            <a href="#" className="text-[#0D9488] hover:underline">شروط الخدمة</a>
                            {' '}و{' '}
                            <a href="#" className="text-[#0D9488] hover:underline">سياسة الخصوصية</a>
                        </p>

                        {/* Submit */}
                        <button type="submit" disabled={processing}
                            className="w-full py-3.5 bg-[#0D9488] text-white font-bold rounded-xl hover:bg-[#0F766E] disabled:opacity-60 disabled:cursor-not-allowed transition-all shadow-lg shadow-teal-200 text-sm flex items-center justify-center gap-2">
                            {processing && (
                                <svg className="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24">
                                    <circle className="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" strokeWidth="4" />
                                    <path className="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z" />
                                </svg>
                            )}
                            {processing ? 'جارٍ الإنشاء...' : 'إنشاء حساب'}
                        </button>

                        <p className="text-center text-sm text-gray-500">
                            لديك حساب بالفعل؟{' '}
                            <Link href="/login" className="text-[#0D9488] font-bold hover:underline">
                                تسجيل الدخول
                            </Link>
                        </p>
                    </form>
                </div>
            </div>
        </>
    );
}
