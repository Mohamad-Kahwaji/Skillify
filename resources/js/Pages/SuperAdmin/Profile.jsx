import { Head, useForm } from '@inertiajs/react';
import { useState } from 'react';
import SuperAdminLayout from '../../Layouts/SuperAdminLayout';

const inputStyle = {
    width: '100%', padding: '10px 12px', fontSize: 13,
    borderRadius: 10, border: '1px solid rgba(0,0,0,0.12)',
    background: '#F8FAFC', color: '#1E1B4B', outline: 'none',
    fontFamily: "'Cairo','Inter',sans-serif",
};

const labelStyle = { display: 'block', fontSize: 12, fontWeight: 600, color: '#475569', marginBottom: 6 };

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
        email: admin.email ?? '',
        current_password: '',
        new_password: '',
        new_password_confirmation: '',
    });

    const [showNewPw, setShowNewPw] = useState(false);

    const submit = (e) => {
        e.preventDefault();
        put(route('super_admin.profile.update'), {
            preserveScroll: true,
            onSuccess: () => reset('current_password', 'new_password', 'new_password_confirmation'),
        });
    };

    return (
        <SuperAdminLayout title="My Profile">
            <Head title="My Profile" />

            <div style={{ maxWidth: 560 }}>
                <div style={{
                    background: '#fff', borderRadius: 14, border: '1px solid rgba(0,0,0,0.08)',
                    boxShadow: '0 1px 3px rgba(15,23,42,0.07), 0 4px 20px rgba(15,23,42,0.04)',
                    padding: 24, display: 'flex', flexDirection: 'column', gap: 20,
                }}>
                    <div style={{ display: 'flex', alignItems: 'center', gap: 12 }}>
                        <div style={{
                            width: 48, height: 48, borderRadius: 12,
                            background: 'linear-gradient(135deg,#7C3AED,#A78BFA)',
                            display: 'flex', alignItems: 'center', justifyContent: 'center',
                            color: '#fff', fontSize: 18, fontWeight: 700, flexShrink: 0,
                        }}>
                            {(admin.first_name ?? 'S')[0].toUpperCase()}
                        </div>
                        <div>
                            <div style={{ fontSize: 15, fontWeight: 700, color: '#1E1B4B' }}>{admin.first_name} {admin.last_name}</div>
                            <div style={{ fontSize: 12, color: '#7C3AED', fontWeight: 600 }}>Super Admin</div>
                        </div>
                    </div>

                    <form onSubmit={submit} style={{ display: 'flex', flexDirection: 'column', gap: 16 }}>
                        <div style={{ display: 'grid', gridTemplateColumns: '1fr 1fr', gap: 14 }}>
                            <Field label="First Name" error={errors.first_name}>
                                <input style={inputStyle} value={data.first_name} onChange={e => setData('first_name', e.target.value)} />
                            </Field>
                            <Field label="Last Name" error={errors.last_name}>
                                <input style={inputStyle} value={data.last_name} onChange={e => setData('last_name', e.target.value)} />
                            </Field>
                        </div>

                        <Field label="Email Address" error={errors.email}>
                            <input type="email" style={inputStyle} value={data.email} onChange={e => setData('email', e.target.value)} dir="ltr" />
                        </Field>

                        <div style={{ borderTop: '1px solid rgba(0,0,0,0.08)', paddingTop: 16, display: 'flex', flexDirection: 'column', gap: 14 }}>
                            <div style={{ fontSize: 13, fontWeight: 700, color: '#1E1B4B' }}>Change Password (optional)</div>

                            <div style={{ position: 'relative' }}>
                                <Field label="New Password" error={errors.new_password}>
                                    <input
                                        type={showNewPw ? 'text' : 'password'}
                                        style={inputStyle}
                                        value={data.new_password}
                                        onChange={e => setData('new_password', e.target.value)}
                                        placeholder="Leave blank to keep your current password"
                                        autoComplete="new-password"
                                    />
                                </Field>
                                <button type="button" onClick={() => setShowNewPw(v => !v)} style={{
                                    position: 'absolute', left: 10, top: 32, background: 'none', border: 'none',
                                    color: '#94A3B8', cursor: 'pointer', fontSize: 14,
                                }}>
                                    <i className={`ti ${showNewPw ? 'ti-eye-off' : 'ti-eye'}`} />
                                </button>
                            </div>

                            <Field label="Confirm New Password" error={errors.new_password_confirmation}>
                                <input
                                    type={showNewPw ? 'text' : 'password'}
                                    style={inputStyle}
                                    value={data.new_password_confirmation}
                                    onChange={e => setData('new_password_confirmation', e.target.value)}
                                    autoComplete="new-password"
                                />
                            </Field>
                        </div>

                        <div style={{ borderTop: '1px solid rgba(0,0,0,0.08)', paddingTop: 16 }}>
                            <Field label="Current Password" error={errors.current_password}>
                                <input
                                    type="password"
                                    style={inputStyle}
                                    value={data.current_password}
                                    onChange={e => setData('current_password', e.target.value)}
                                    placeholder="Enter your current password to confirm changes"
                                    autoComplete="current-password"
                                />
                            </Field>
                        </div>

                        <button type="submit" disabled={processing} style={{
                            alignSelf: 'flex-start', padding: '10px 22px', borderRadius: 10,
                            background: '#7C3AED', color: '#fff', border: 'none', cursor: 'pointer',
                            fontSize: 13, fontWeight: 700, opacity: processing ? 0.7 : 1,
                            display: 'flex', alignItems: 'center', gap: 8,
                        }}>
                            <i className="ti ti-device-floppy" style={{ fontSize: 15 }} />
                            Save Changes
                        </button>
                    </form>
                </div>
            </div>
        </SuperAdminLayout>
    );
}
