import { router, usePage } from '@inertiajs/react';
import { useEffect, useRef, useState } from 'react';

const RESEND_SECONDS = 60;

export default function VerifyOtp({ phone }) {
    const { errors } = usePage().props;

    const [digits, setDigits] = useState(['', '', '', '', '', '']);
    const [processing, setProcessing] = useState(false);
    const [typing, setTyping] = useState(false); // لإخفاء الخطأ أثناء إعادة الكتابة
    const [countdown, setCountdown] = useState(RESEND_SECONDS);
    const inputsRef = useRef([]);

    const code = digits.join('');
    const showError = errors?.code && !typing;

    // عداد إعادة الإرسال
    useEffect(() => {
        if (countdown <= 0) return;
        const t = setTimeout(() => setCountdown(c => c - 1), 1000);
        return () => clearTimeout(t);
    }, [countdown]);

    const submit = (value) => {
        setTyping(false);
        router.post('/verify-otp', { code: value }, {
            onStart: () => setProcessing(true),
            onFinish: () => setProcessing(false),
            onError: () => {
                setDigits(['', '', '', '', '', '']);
                inputsRef.current[0]?.focus();
            },
        });
    };

    const updateDigits = (next, focusIndex) => {
        setDigits(next);
        setTyping(true);
        if (focusIndex !== null && inputsRef.current[focusIndex]) {
            inputsRef.current[focusIndex].focus();
        }
        // اكتملت الست خانات ← إرسال تلقائي
        const value = next.join('');
        if (value.length === 6 && next.every(d => d !== '')) {
            submit(value);
        }
    };

    const handleChange = (index, value) => {
        const digit = value.replace(/\D/g, '').slice(-1);
        const next = [...digits];
        next[index] = digit;
        updateDigits(next, digit && index < 5 ? index + 1 : null);
    };

    const handleKeyDown = (index, e) => {
        if (e.key === 'Backspace' && !digits[index] && index > 0) {
            const next = [...digits];
            next[index - 1] = '';
            updateDigits(next, index - 1);
        }
    };

    const handlePaste = (e) => {
        e.preventDefault();
        const pasted = e.clipboardData.getData('text').replace(/\D/g, '').slice(0, 6);
        if (!pasted) return;
        const next = ['', '', '', '', '', ''];
        pasted.split('').forEach((d, i) => (next[i] = d));
        updateDigits(next, Math.min(pasted.length, 5));
    };

    const resend = () => {
        router.post('/forgot-password', { phone }, {
            onSuccess: () => {
                setCountdown(RESEND_SECONDS);
                setDigits(['', '', '', '', '', '']);
                setTyping(false);
                inputsRef.current[0]?.focus();
            },
        });
    };

    // إخفاء وسط الرقم: 0999999999 ← 099***9999
    const maskedPhone = phone
        ? phone.slice(0, 3) + '***' + phone.slice(-4)
        : '';

    return (
        <div dir="rtl" className="min-h-screen bg-gray-50 flex items-center justify-center px-4">
            <div className="w-full max-w-md">

                <div className="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">

                    {/* Header */}
                    <div className="text-center mb-8">
                        <div className="w-14 h-14 mx-auto mb-4 rounded-2xl bg-[#25D366]/10 flex items-center justify-center">
                            <svg className="w-7 h-7 text-[#25D366]" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z" />
                            </svg>
                        </div>
                        <h1 className="text-xl font-bold text-gray-900">أدخل رمز التحقق</h1>
                        <p className="text-sm text-gray-500 mt-2">
                            أرسلنا رمزاً من 6 أرقام عبر واتساب إلى
                            <span className="font-semibold text-gray-700" dir="ltr"> {maskedPhone}</span>
                        </p>
                    </div>

                    {/* OTP boxes — LTR حتى يكون ترتيب الأرقام طبيعي */}
                    <div dir="ltr" className="flex justify-center gap-2.5 mb-2" onPaste={handlePaste}>
                        {digits.map((digit, i) => (
                            <input
                                key={i}
                                ref={el => (inputsRef.current[i] = el)}
                                type="text"
                                inputMode="numeric"
                                maxLength={1}
                                value={digit}
                                autoFocus={i === 0}
                                onChange={e => handleChange(i, e.target.value)}
                                onKeyDown={e => handleKeyDown(i, e)}
                                className={`w-12 h-14 text-center text-xl font-bold bg-white border rounded-xl focus:outline-none focus:ring-2 focus:ring-[#0D9488]/30 focus:border-[#0D9488] transition-all ${showError ? 'border-red-400' : 'border-gray-200'}`}
                            />
                        ))}
                    </div>
                    {showError && <p className="text-red-500 text-xs text-center mb-2">{errors.code}</p>}

                    {/* Submit */}
                    <button
                        onClick={() => submit(code)}
                        disabled={processing || code.length !== 6}
                        className="w-full mt-4 py-3 bg-[#0D9488] hover:bg-[#0B7C72] text-white text-sm font-semibold rounded-xl transition-colors disabled:opacity-60 disabled:cursor-not-allowed flex items-center justify-center gap-2"
                    >
                        {processing ? (
                            <span className="w-4 h-4 border-2 border-white/30 border-t-white rounded-full animate-spin" />
                        ) : (
                            'تحقق من الرمز'
                        )}
                    </button>

                    {/* Resend */}
                    <div className="text-center mt-6 text-sm text-gray-500">
                        {countdown > 0 ? (
                            <span>
                                يمكنك طلب رمز جديد بعد{' '}
                                <span className="font-semibold text-gray-700">{countdown}</span> ثانية
                            </span>
                        ) : (
                            <button
                                onClick={resend}
                                className="text-[#0D9488] font-medium hover:underline"
                            >
                                إعادة إرسال الرمز
                            </button>
                        )}
                    </div>

                </div>
            </div>
        </div>
    );
}
