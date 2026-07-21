import { Head, useForm } from '@inertiajs/react';
import { useState } from 'react';
import AdminLayout, { C } from '../../Layouts/AdminLayout';

const FONT = "'Cairo','Inter',sans-serif";

const INPUT_BASE = {
    width: '100%', padding: '11px 14px',
    background: '#F8FAFC', border: '1.5px solid rgba(15,23,42,0.10)',
    borderRadius: 10, color: C.textDark,
    fontSize: 13.5, fontFamily: FONT,
    outline: 'none', boxSizing: 'border-box',
    transition: 'border-color .15s, box-shadow .15s',
};

const LABEL_S = { fontSize: 12, fontWeight: 700, color: C.textMed, marginBottom: 6, display: 'flex', alignItems: 'center', gap: 5 };

function Field({ label, icon, error, children }) {
    return (
        <div>
            <label style={LABEL_S}>
                {icon && <i className={`ti ${icon}`} style={{ color: C.primary, fontSize: 12 }} />}
                {label}
            </label>
            {children}
            {error && (
                <p style={{ fontSize: 11, color: C.dangerText, marginTop: 5, display: 'flex', alignItems: 'center', gap: 4 }}>
                    <i className="ti ti-alert-circle" style={{ fontSize: 11 }} />{error}
                </p>
            )}
        </div>
    );
}

function TextInput({ style: extraStyle, ...props }) {
    const [focused, setFocused] = useState(false);
    return (
        <input
            {...props}
            style={{
                ...INPUT_BASE,
                borderColor: focused ? C.primary : 'rgba(15,23,42,0.10)',
                boxShadow: focused ? `0 0 0 3px ${C.primaryMuted}` : 'none',
                ...extraStyle,
            }}
            onFocus={() => setFocused(true)}
            onBlur={() => setFocused(false)}
        />
    );
}

function PasswordField({ label, icon = 'ti-lock', error, value, onChange, placeholder, autoComplete }) {
    const [visible, setVisible] = useState(false);
    return (
        <Field label={label} icon={icon} error={error}>
            <div style={{ position: 'relative' }}>
                <TextInput
                    type={visible ? 'text' : 'password'}
                    value={value}
                    onChange={onChange}
                    placeholder={placeholder}
                    autoComplete={autoComplete}
                    style={{ paddingLeft: 38 }}
                />
                <button type="button" onClick={() => setVisible(v => !v)} tabIndex={-1} style={{
                    position: 'absolute', left: 10, top: '50%', transform: 'translateY(-50%)',
                    background: 'none', border: 'none', color: C.textFaint, cursor: 'pointer', fontSize: 15,
                    padding: 4, display: 'flex', alignItems: 'center',
                }}>
                    <i className={`ti ${visible ? 'ti-eye-off' : 'ti-eye'}`} />
                </button>
            </div>
        </Field>
    );
}

function SectionTitle({ icon, children, sub }) {
    return (
        <div style={{ display: 'flex', alignItems: 'center', gap: 8, marginBottom: sub ? 3 : 16 }}>
            <div style={{ width: 3, height: 16, borderRadius: 2, background: `linear-gradient(180deg,${C.primary},${C.teal})` }} />
            <i className={`ti ${icon}`} style={{ color: C.primary, fontSize: 13 }} />
            <span style={{ fontSize: 13, fontWeight: 800, color: C.textDark }}>{children}</span>
        </div>
    );
}

export default function Profile({ admin }) {
    const { data, setData, put, processing, errors, reset } = useForm({
        first_name: admin.first_name ?? '',
        last_name: admin.last_name ?? '',
        phone: admin.phone ?? '',
        email: admin.email ?? '',
        current_password: '',
        new_password: '',
        new_password_confirmation: '',
    });

    const submit = (e) => {
        e.preventDefault();
        put('/admin/profile', {
            preserveScroll: true,
            onSuccess: () => reset('current_password', 'new_password', 'new_password_confirmation'),
        });
    };

    const initials = (admin.first_name ?? 'A')[0].toUpperCase();

    return (
        <AdminLayout title="الملف الشخصي">
            <Head title="الملف الشخصي" />

            <div style={{ display: 'flex', flexDirection: 'column', gap: 20 }}>

                {/* Identity header */}
                <div style={{
                    background: C.cardBg, borderRadius: 18, border: C.cardBorder, boxShadow: C.cardShadow,
                    padding: '24px 26px', display: 'flex', alignItems: 'center', gap: 16,
                }}>
                    <div style={{
                        width: 60, height: 60, borderRadius: 16, flexShrink: 0,
                        background: `linear-gradient(135deg,${C.primary},${C.teal})`,
                        display: 'flex', alignItems: 'center', justifyContent: 'center',
                        color: '#fff', fontSize: 22, fontWeight: 800,
                        boxShadow: `0 6px 18px ${C.primaryMuted}`,
                    }}>
                        {initials}
                    </div>
                    <div style={{ minWidth: 0 }}>
                        <div style={{ fontSize: 17, fontWeight: 800, color: C.textDark, overflow: 'hidden', textOverflow: 'ellipsis', whiteSpace: 'nowrap' }}>
                            {admin.first_name} {admin.last_name}
                        </div>
                        <div style={{ display: 'flex', alignItems: 'center', gap: 8, flexWrap: 'wrap', marginTop: 6 }}>
                            <span style={{ display: 'inline-flex', alignItems: 'center', gap: 5, fontSize: 11, fontWeight: 700, color: C.primaryDark, background: C.primaryMuted, borderRadius: 20, padding: '3px 11px' }}>
                                <i className="ti ti-shield-star" style={{ fontSize: 12 }} /> {admin.role === 'super_admin' ? 'مدير عام' : 'مشرف'}
                            </span>
                            {admin.id_number && (
                                <span style={{ fontSize: 11, color: C.textFaint }}>رقم الهوية: {admin.id_number}</span>
                            )}
                        </div>
                    </div>
                </div>

                {/* Edit form */}
                <div style={{ background: C.cardBg, borderRadius: 18, border: C.cardBorder, boxShadow: C.cardShadow, padding: 26 }}>
                    <form onSubmit={submit} style={{ display: 'flex', flexDirection: 'column', gap: 24 }}>

                        {/* Personal info */}
                        <div>
                            <SectionTitle icon="ti-user-edit">المعلومات الشخصية</SectionTitle>
                            <div className="grid grid-cols-1 sm:grid-cols-2" style={{ gap: 14, marginBottom: 14 }}>
                                <Field label="الاسم الأول" icon="ti-user" error={errors.first_name}>
                                    <TextInput value={data.first_name} onChange={e => setData('first_name', e.target.value)} required />
                                </Field>
                                <Field label="الاسم الأخير" icon="ti-user" error={errors.last_name}>
                                    <TextInput value={data.last_name} onChange={e => setData('last_name', e.target.value)} required />
                                </Field>
                            </div>
                            <div className="grid grid-cols-1 sm:grid-cols-2" style={{ gap: 14 }}>
                                <Field label="رقم الهاتف" icon="ti-phone" error={errors.phone}>
                                    <TextInput value={data.phone} onChange={e => setData('phone', e.target.value)} dir="ltr" />
                                </Field>
                                <Field label="البريد الإلكتروني" icon="ti-mail" error={errors.email}>
                                    <TextInput type="email" value={data.email} onChange={e => setData('email', e.target.value)} dir="ltr" />
                                </Field>
                            </div>
                        </div>

                        <div style={{ height: 1, background: 'rgba(15,23,42,0.06)' }} />

                        {/* Password change */}
                        <div>
                            <SectionTitle icon="ti-lock" sub>كلمة المرور</SectionTitle>
                            <p style={{ fontSize: 11.5, color: C.textFaint, margin: '0 0 14px' }}>اتركها فارغة إذا ما بدك تغيّر كلمة المرور الحالية</p>
                            <div className="grid grid-cols-1 sm:grid-cols-2" style={{ gap: 14 }}>
                                <PasswordField
                                    label="كلمة المرور الجديدة" error={errors.new_password}
                                    value={data.new_password} onChange={e => setData('new_password', e.target.value)}
                                    placeholder="اتركه فارغاً إذا لم ترغب بالتغيير" autoComplete="new-password"
                                />
                                <PasswordField
                                    label="تأكيد كلمة المرور الجديدة" icon="ti-lock-check" error={errors.new_password_confirmation}
                                    value={data.new_password_confirmation} onChange={e => setData('new_password_confirmation', e.target.value)}
                                    placeholder="أعد كتابتها" autoComplete="new-password"
                                />
                            </div>
                        </div>

                        <div style={{ height: 1, background: 'rgba(15,23,42,0.06)' }} />

                        {/* Confirm identity */}
                        <div style={{ background: C.warningBg, border: `1.5px solid ${C.warningBorder}`, borderRadius: 14, padding: '16px 18px' }}>
                            <div style={{ display: 'flex', alignItems: 'center', gap: 8, marginBottom: 10 }}>
                                <i className="ti ti-shield-lock" style={{ color: C.warningText, fontSize: 15 }} />
                                <span style={{ fontSize: 12.5, fontWeight: 700, color: C.warningText }}>تأكيد الهوية</span>
                            </div>
                            <PasswordField
                                label="كلمة المرور الحالية" error={errors.current_password}
                                value={data.current_password} onChange={e => setData('current_password', e.target.value)}
                                placeholder="أدخل كلمة مرورك الحالية لتأكيد التغييرات" autoComplete="current-password"
                            />
                        </div>

                        <div style={{ display: 'flex', justifyContent: 'flex-end' }}>
                            <button type="submit" disabled={processing} style={{
                                display: 'inline-flex', alignItems: 'center', gap: 8,
                                padding: '11px 26px', borderRadius: 11, border: 'none',
                                background: `linear-gradient(135deg,${C.primary},${C.teal})`, color: '#fff',
                                fontSize: 13.5, fontWeight: 700, fontFamily: FONT,
                                cursor: processing ? 'not-allowed' : 'pointer',
                                opacity: processing ? 0.7 : 1,
                                boxShadow: `0 4px 14px ${C.primaryMuted}`,
                            }}>
                                <i className="ti ti-device-floppy" style={{ fontSize: 15 }} />
                                {processing ? 'جارٍ الحفظ...' : 'حفظ التغييرات'}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </AdminLayout>
    );
}
