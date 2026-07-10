import { Head, useForm } from '@inertiajs/react';
import { useState } from 'react';
import AdminLayout, { C } from '../../Layouts/AdminLayout';

const inputStyle = {
    width: '100%', padding: '10px 12px', fontSize: 13,
    borderRadius: 10, border: `1px solid rgba(15,23,42,0.12)`,
    background: '#F8FAFC', color: C.textDark, outline: 'none',
    fontFamily: "'Cairo','Inter',sans-serif",
};

const labelStyle = { display: 'block', fontSize: 12, fontWeight: 600, color: C.textMed, marginBottom: 6 };

function Field({ label, error, children }) {
    return (
        <div>
            <label style={labelStyle}>{label}</label>
            {children}
            {error && <div style={{ fontSize: 11, color: '#DC2626', marginTop: 5 }}>{error}</div>}
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

    const [showNewPw, setShowNewPw] = useState(false);

    const submit = (e) => {
        e.preventDefault();
        put(route('admin.profile.update'), {
            preserveScroll: true,
            onSuccess: () => reset('current_password', 'new_password', 'new_password_confirmation'),
        });
    };

    return (
        <AdminLayout title="الملف الشخصي">
            <Head title="الملف الشخصي" />

            <div style={{ maxWidth: 560 }}>
                <div style={{
                    background: C.cardBg, borderRadius: C.cardRadius, boxShadow: C.cardShadow, border: C.cardBorder,
                    padding: 24, display: 'flex', flexDirection: 'column', gap: 20,
                }}>
                    <div style={{ display: 'flex', alignItems: 'center', gap: 12 }}>
                        <div style={{
                            width: 48, height: 48, borderRadius: 12,
                            background: 'linear-gradient(135deg,#0EA5E9,#0D9488)',
                            display: 'flex', alignItems: 'center', justifyContent: 'center',
                            color: '#fff', fontSize: 18, fontWeight: 700, flexShrink: 0,
                        }}>
                            {(admin.first_name ?? 'A')[0].toUpperCase()}
                        </div>
                        <div>
                            <div style={{ fontSize: 15, fontWeight: 700, color: C.textDark }}>{admin.first_name} {admin.last_name}</div>
                            <div style={{ fontSize: 12, color: C.textMuted }}>{admin.role === 'super_admin' ? 'مدير عام' : 'مشرف'} {admin.id_number ? `— رقم الهوية: ${admin.id_number}` : ''}</div>
                        </div>
                    </div>

                    <form onSubmit={submit} style={{ display: 'flex', flexDirection: 'column', gap: 16 }}>
                        <div style={{ display: 'grid', gridTemplateColumns: '1fr 1fr', gap: 14 }}>
                            <Field label="الاسم الأول" error={errors.first_name}>
                                <input style={inputStyle} value={data.first_name} onChange={e => setData('first_name', e.target.value)} />
                            </Field>
                            <Field label="الاسم الأخير" error={errors.last_name}>
                                <input style={inputStyle} value={data.last_name} onChange={e => setData('last_name', e.target.value)} />
                            </Field>
                        </div>

                        <Field label="رقم الهاتف" error={errors.phone}>
                            <input style={inputStyle} value={data.phone} onChange={e => setData('phone', e.target.value)} dir="ltr" />
                        </Field>

                        <Field label="البريد الإلكتروني" error={errors.email}>
                            <input type="email" style={inputStyle} value={data.email} onChange={e => setData('email', e.target.value)} dir="ltr" />
                        </Field>

                        <div style={{ borderTop: C.cardBorder, paddingTop: 16, display: 'flex', flexDirection: 'column', gap: 14 }}>
                            <div style={{ fontSize: 13, fontWeight: 700, color: C.textDark }}>تغيير كلمة المرور (اختياري)</div>

                            <div style={{ position: 'relative' }}>
                                <Field label="كلمة المرور الجديدة" error={errors.new_password}>
                                    <input
                                        type={showNewPw ? 'text' : 'password'}
                                        style={inputStyle}
                                        value={data.new_password}
                                        onChange={e => setData('new_password', e.target.value)}
                                        placeholder="اتركه فارغاً إذا لم ترغب بالتغيير"
                                        autoComplete="new-password"
                                    />
                                </Field>
                                <button type="button" onClick={() => setShowNewPw(v => !v)} style={{
                                    position: 'absolute', left: 10, top: 32, background: 'none', border: 'none',
                                    color: C.textMuted, cursor: 'pointer', fontSize: 14,
                                }}>
                                    <i className={`ti ${showNewPw ? 'ti-eye-off' : 'ti-eye'}`} />
                                </button>
                            </div>

                            <Field label="تأكيد كلمة المرور الجديدة" error={errors.new_password_confirmation}>
                                <input
                                    type={showNewPw ? 'text' : 'password'}
                                    style={inputStyle}
                                    value={data.new_password_confirmation}
                                    onChange={e => setData('new_password_confirmation', e.target.value)}
                                    autoComplete="new-password"
                                />
                            </Field>
                        </div>

                        <div style={{ borderTop: C.cardBorder, paddingTop: 16 }}>
                            <Field label="كلمة المرور الحالية" error={errors.current_password}>
                                <input
                                    type="password"
                                    style={inputStyle}
                                    value={data.current_password}
                                    onChange={e => setData('current_password', e.target.value)}
                                    placeholder="أدخل كلمة مرورك الحالية لتأكيد التغييرات"
                                    autoComplete="current-password"
                                />
                            </Field>
                        </div>

                        <button type="submit" disabled={processing} style={{
                            alignSelf: 'flex-start', padding: '10px 22px', borderRadius: 10,
                            background: C.primary, color: '#fff', border: 'none', cursor: 'pointer',
                            fontSize: 13, fontWeight: 700, opacity: processing ? 0.7 : 1,
                            display: 'flex', alignItems: 'center', gap: 8,
                        }}>
                            <i className="ti ti-device-floppy" style={{ fontSize: 15 }} />
                            حفظ التغييرات
                        </button>
                    </form>
                </div>
            </div>
        </AdminLayout>
    );
}
