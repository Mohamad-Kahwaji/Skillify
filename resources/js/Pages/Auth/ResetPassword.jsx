import { useForm } from '@inertiajs/react';
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
        <div dir="rtl" className="min-h-screen bg-gray-50 flex items-center justify-center px-4">
            <div className="w-full max-w-md">

                <div className="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">

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
                        className="w-full mt-1 py-3 bg-[#0D9488] hover:bg-[#0B7C72] text-white text-sm font-semibold rounded-xl transition-colors disabled:opacity-60 disabled:cursor-not-allowed flex items-center justify-center gap-2"
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
            </div>
        </div>
    );
}
