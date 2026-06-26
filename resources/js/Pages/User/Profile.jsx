import { Head, useForm, router } from '@inertiajs/react';
import { useState } from 'react';
import UserLayout from '../../Layouts/UserLayout';

const INPUT = { width: '100%', padding: '9px 12px', background: '#F1F5F9', border: '0.5px solid rgba(0,0,0,0.12)', borderRadius: 6, color: '#0F172A', fontSize: 13, fontFamily: 'Inter,sans-serif', outline: 'none' };
const LABEL = { fontSize: 12, fontWeight: 500, color: '#475569', display: 'block', marginBottom: 4 };

function Field({ label, error, children }) {
    return (
        <div style={{ display: 'flex', flexDirection: 'column', gap: 0 }}>
            <label style={LABEL}>{label}</label>
            {children}
            {error && <p style={{ fontSize: 11, color: '#EF4444', marginTop: 3 }}>{error}</p>}
        </div>
    );
}

function VerificationBanner({ verification }) {
    if (!verification) return (
        <div style={{ display: 'flex', alignItems: 'center', justifyContent: 'space-between', gap: 12, padding: '12px 16px', background: '#F8FAFC', border: '1px solid rgba(0,0,0,0.07)', borderRadius: 12 }}>
            <div style={{ display: 'flex', alignItems: 'center', gap: 10 }}>
                <div style={{ width: 36, height: 36, borderRadius: 10, background: '#E2E8F0', display: 'flex', alignItems: 'center', justifyContent: 'center' }}>
                    <i className="ti ti-id-badge" style={{ fontSize: 18, color: '#94A3B8' }} />
                </div>
                <div>
                    <div style={{ fontSize: 13, fontWeight: 600, color: '#0F172A' }}>توثيق الهوية</div>
                    <div style={{ fontSize: 12, color: '#64748B' }}>وثّق هويتك لزيادة الثقة ومصداقيتك على المنصة</div>
                </div>
            </div>
            <a href="/user/identity-verification" style={{ padding: '7px 16px', background: '#0D9488', color: '#fff', borderRadius: 8, fontSize: 12, fontWeight: 600, textDecoration: 'none', whiteSpace: 'nowrap' }}>
                بدء التوثيق
            </a>
        </div>
    );
    if (verification.status === 'approved') return (
        <div style={{ display: 'flex', alignItems: 'center', gap: 12, padding: '12px 16px', background: '#F0FDF4', border: '1px solid #BBF7D0', borderRadius: 12 }}>
            <div style={{ width: 36, height: 36, borderRadius: 10, background: '#DCFCE7', display: 'flex', alignItems: 'center', justifyContent: 'center' }}>
                <i className="ti ti-shield-check" style={{ fontSize: 20, color: '#16A34A' }} />
            </div>
            <div>
                <div style={{ fontSize: 13, fontWeight: 700, color: '#15803D' }}>هويتك موثّقة ✓</div>
                <div style={{ fontSize: 12, color: '#166534' }}>حسابك موثّق ويظهر لجميع المستخدمين بشارة الثقة</div>
            </div>
        </div>
    );
    if (verification.status === 'pending') return (
        <div style={{ display: 'flex', alignItems: 'center', gap: 12, padding: '12px 16px', background: '#FFFBEB', border: '1px solid #FDE68A', borderRadius: 12 }}>
            <div style={{ width: 36, height: 36, borderRadius: 10, background: '#FEF9C3', display: 'flex', alignItems: 'center', justifyContent: 'center' }}>
                <i className="ti ti-clock" style={{ fontSize: 20, color: '#CA8A04' }} />
            </div>
            <div>
                <div style={{ fontSize: 13, fontWeight: 700, color: '#A16207' }}>طلب التوثيق قيد المراجعة</div>
                <div style={{ fontSize: 12, color: '#92400E' }}>سيتم إشعارك فور مراجعة طلبك من قِبل الفريق</div>
            </div>
        </div>
    );
    if (verification.status === 'rejected') return (
        <div style={{ display: 'flex', alignItems: 'center', justifyContent: 'space-between', gap: 12, padding: '12px 16px', background: '#FEF2F2', border: '1px solid #FECACA', borderRadius: 12 }}>
            <div style={{ display: 'flex', alignItems: 'center', gap: 10 }}>
                <div style={{ width: 36, height: 36, borderRadius: 10, background: '#FEE2E2', display: 'flex', alignItems: 'center', justifyContent: 'center' }}>
                    <i className="ti ti-shield-x" style={{ fontSize: 20, color: '#DC2626' }} />
                </div>
                <div>
                    <div style={{ fontSize: 13, fontWeight: 700, color: '#B91C1C' }}>تم رفض طلب التوثيق</div>
                    {verification.rejection_reason && (
                        <div style={{ fontSize: 12, color: '#991B1B', marginTop: 2 }}>{verification.rejection_reason}</div>
                    )}
                </div>
            </div>
            <a href="/user/identity-verification" style={{ padding: '7px 14px', background: '#DC2626', color: '#fff', borderRadius: 8, fontSize: 12, fontWeight: 600, textDecoration: 'none', whiteSpace: 'nowrap' }}>
                إعادة التقديم
            </a>
        </div>
    );
    return null;
}

export default function Profile({ user, business, userServices, activeTypes, categories, subcategories, cities, flash, verification }) {
    const [tab, setTab] = useState('profile');

    const profileForm = useForm({
        first_name: user.first_name ?? '',
        last_name:  user.last_name ?? '',
        phone:      user.phone ?? '',
        city:       user.city ?? '',
        gender:     user.gender ?? '',
        birthdate:  user.birthdate ?? '',
    });

    const submitProfile = (e) => {
        e.preventDefault();
        profileForm.put('/user/profile', { preserveScroll: true });
    };

    const svcCount = userServices?.length ?? 0;

    const TABS = [
        { key: 'profile',  icon: 'ti-user',     label: 'ملفي الشخصي' },
        { key: 'business', icon: 'ti-briefcase', label: 'حساب العمل', badge: business ? business.status : null },
        { key: 'services', icon: 'ti-tool',      label: 'خدماتي', badge: svcCount || null },
    ];

    return (
        <UserLayout title="الملف الشخصي">
            <Head title="حسابي — Skillify" />

            {flash?.success && (
                <div style={{ background: '#F0FDF4', border: '1px solid #9FE1CB', borderRadius: 10, padding: '10px 16px', color: '#134E4A', fontSize: 13 }}>
                    <i className="ti ti-circle-check" style={{ marginRight: 6 }} />{flash.success}
                </div>
            )}

            <div style={{ display: 'flex', alignItems: 'flex-start', justifyContent: 'space-between', gap: 12 }}>
                <div>
                    <div style={{ display: 'flex', alignItems: 'center', gap: 8 }}>
                        <span style={{ fontSize: 20, fontWeight: 600, color: '#0F172A' }}>حسابي</span>
                        {verification?.status === 'approved' && (
                            <span style={{ display: 'inline-flex', alignItems: 'center', gap: 4, fontSize: 11, fontWeight: 700, padding: '3px 9px', borderRadius: 20, background: '#F0FDF4', color: '#15803D', border: '1px solid #BBF7D0' }}>
                                <i className="ti ti-shield-check" style={{ fontSize: 13 }} /> موثّق
                            </span>
                        )}
                    </div>
                    <div style={{ fontSize: 13, color: '#475569', marginTop: 2 }}>إدارة ملفك الشخصي وحساب عملك وخدماتك</div>
                </div>
            </div>

            <VerificationBanner verification={verification} />

            {/* Tab Bar */}
            <div style={{ display: 'flex', gap: 2, background: '#fff', border: '0.5px solid rgba(0,0,0,0.07)', borderRadius: 14, padding: 4, width: 'fit-content' }}>
                {TABS.map(({ key, icon, label, badge }) => (
                    <button key={key} onClick={() => setTab(key)} style={{
                        display: 'flex', alignItems: 'center', gap: 7,
                        padding: '8px 18px', borderRadius: 11,
                        fontSize: 13, fontWeight: 500, border: 'none', cursor: 'pointer', whiteSpace: 'nowrap',
                        background: tab === key ? '#0D9488' : 'none',
                        color: tab === key ? '#fff' : '#475569',
                        boxShadow: tab === key ? '0 2px 8px rgba(13,148,136,.25)' : 'none',
                        transition: 'all .15s',
                    }}>
                        <i className={`ti ${icon}`} /> {label}
                        {badge && (
                            <span style={{ fontSize: 10, fontWeight: 700, padding: '1px 6px', borderRadius: 20, background: tab === key ? 'rgba(255,255,255,0.25)' : '#F1F5F9', color: tab === key ? '#fff' : '#94A3B8', lineHeight: 1.4 }}>
                                {badge}
                            </span>
                        )}
                    </button>
                ))}
            </div>

            {/* ── PROFILE TAB ── */}
            {tab === 'profile' && (
                <div style={{ background: '#fff', border: '1px solid rgba(0,0,0,0.07)', borderRadius: 14 }}>
                    <div style={{ padding: '16px 20px', borderBottom: '1px solid rgba(0,0,0,0.07)', fontWeight: 700, fontSize: 14, display: 'flex', alignItems: 'center', gap: 6 }}>
                        <i className="ti ti-user-edit" /> المعلومات الشخصية
                    </div>
                    <div style={{ padding: 20 }}>
                        <form onSubmit={submitProfile}>
                            <div style={{ display: 'grid', gridTemplateColumns: '1fr 1fr', gap: 14 }}>
                                <Field label="الاسم الأول" error={profileForm.errors.first_name}>
                                    <input style={INPUT} value={profileForm.data.first_name} onChange={e => profileForm.setData('first_name', e.target.value)} required />
                                </Field>
                                <Field label="الاسم الأخير" error={profileForm.errors.last_name}>
                                    <input style={INPUT} value={profileForm.data.last_name} onChange={e => profileForm.setData('last_name', e.target.value)} required />
                                </Field>
                                <Field label="رقم الهاتف" error={profileForm.errors.phone}>
                                    <input style={INPUT} value={profileForm.data.phone} onChange={e => profileForm.setData('phone', e.target.value)} required />
                                </Field>
                                <Field label="المدينة" error={profileForm.errors.city}>
                                    <select style={INPUT} value={profileForm.data.city} onChange={e => profileForm.setData('city', e.target.value)} required>
                                        <option value="">— اختر مدينة —</option>
                                        {(cities ?? []).map(c => <option key={c.id} value={c.name_ar}>{c.name_ar} / {c.name_en}</option>)}
                                    </select>
                                </Field>
                                <Field label="الجنس" error={profileForm.errors.gender}>
                                    <select style={INPUT} value={profileForm.data.gender} onChange={e => profileForm.setData('gender', e.target.value)} required>
                                        <option value="">— اختر —</option>
                                        <option value="male">ذكر</option>
                                        <option value="female">أنثى</option>
                                    </select>
                                </Field>
                                <Field label="تاريخ الميلاد" error={profileForm.errors.birthdate}>
                                    <input type="date" style={INPUT} value={profileForm.data.birthdate} onChange={e => profileForm.setData('birthdate', e.target.value)} required />
                                </Field>
                            </div>
                            <div style={{ marginTop: 16, display: 'flex', justifyContent: 'flex-end' }}>
                                <button type="submit" disabled={profileForm.processing} style={{ display: 'inline-flex', alignItems: 'center', gap: 6, background: '#0D9488', color: '#fff', border: 'none', cursor: 'pointer', padding: '9px 20px', borderRadius: 10, fontSize: 13, fontWeight: 600, opacity: profileForm.processing ? 0.7 : 1 }}>
                                    <i className="ti ti-device-floppy" /> {profileForm.processing ? 'جارٍ الحفظ...' : 'حفظ التغييرات'}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            )}

            {/* ── BUSINESS TAB ── */}
            {tab === 'business' && (
                <div style={{ background: '#fff', border: '1px solid rgba(0,0,0,0.07)', borderRadius: 14, padding: 20 }}>
                    {business ? (
                        <div>
                            <div style={{ display: 'flex', alignItems: 'center', gap: 16, marginBottom: 20 }}>
                                <div style={{ width: 68, height: 68, borderRadius: 10, background: '#0D9488', display: 'flex', alignItems: 'center', justifyContent: 'center', fontSize: 26, fontWeight: 700, color: '#fff', flexShrink: 0, overflow: 'hidden' }}>
                                    {business.image
                                        ? <img src={`/storage/${business.image}`} alt={business.name} style={{ width: '100%', height: '100%', objectFit: 'cover' }} />
                                        : business.name?.[0]?.toUpperCase()
                                    }
                                </div>
                                <div>
                                    <div style={{ fontSize: 16, fontWeight: 700, color: '#0F172A' }}>{business.name}</div>
                                    <div style={{ fontSize: 13, color: '#0D9488', fontWeight: 500 }}>{business.name_job}</div>
                                    <span style={{
                                        display: 'inline-flex', alignItems: 'center', gap: 4, fontSize: 11, fontWeight: 600, padding: '3px 10px', borderRadius: 20, marginTop: 4,
                                        background: business.status === 'active' ? '#F0FDF4' : '#FEF3C7',
                                        color: business.status === 'active' ? '#134E4A' : '#92400E',
                                    }}>
                                        {business.status === 'active' ? 'نشط' : 'قيد المراجعة'}
                                    </span>
                                </div>
                            </div>
                            {business.description && <p style={{ fontSize: 13, color: '#475569', lineHeight: 1.6 }}>{business.description}</p>}
                        </div>
                    ) : (
                        <div style={{ textAlign: 'center', padding: '48px 24px', color: '#94A3B8' }}>
                            <i className="ti ti-briefcase" style={{ fontSize: 42, display: 'block', marginBottom: 10, opacity: 0.25 }} />
                            <p style={{ fontSize: 13, marginBottom: 14 }}>لا يوجد حساب عمل بعد.</p>
                        </div>
                    )}
                </div>
            )}

            {/* ── SERVICES TAB ── */}
            {tab === 'services' && (
                <div style={{ background: '#fff', border: '1px solid rgba(0,0,0,0.07)', borderRadius: 14, padding: 20 }}>
                    <div style={{ fontSize: 15, fontWeight: 700, marginBottom: 16 }}>خدماتي ({svcCount})</div>
                    {!svcCount ? (
                        <div style={{ textAlign: 'center', padding: '48px 24px', color: '#94A3B8' }}>
                            <i className="ti ti-tool" style={{ fontSize: 42, display: 'block', marginBottom: 10, opacity: 0.25 }} />
                            <p style={{ fontSize: 13 }}>لا توجد خدمات مدرجة بعد.</p>
                        </div>
                    ) : (
                        <div style={{ display: 'grid', gridTemplateColumns: 'repeat(auto-fill,minmax(240px,1fr))', gap: 14 }}>
                            {(userServices ?? []).map(s => (
                                <div key={s.id} style={{ border: '0.5px solid rgba(0,0,0,0.07)', borderRadius: 10, overflow: 'hidden' }}>
                                    <div style={{ width: '100%', height: 120, background: '#F1F5F9', display: 'flex', alignItems: 'center', justifyContent: 'center', fontSize: 28, color: '#94A3B8' }}>
                                        {s.image ? <img src={s.image.startsWith('http') ? s.image : `/storage/${s.image}`} style={{ width: '100%', height: '100%', objectFit: 'cover' }} alt={s.name} /> : <i className="ti ti-tool" />}
                                    </div>
                                    <div style={{ padding: '10px 12px' }}>
                                        <div style={{ fontSize: 13, fontWeight: 700, marginBottom: 4 }}>{s.name}</div>
                                        <div style={{ fontSize: 14, fontWeight: 800, color: '#0D9488' }}>
                                            {Number(s.price).toLocaleString()} <small style={{ fontSize: 10, fontWeight: 400, color: '#94A3B8' }}>{s.price_type?.toUpperCase()}</small>
                                        </div>
                                    </div>
                                </div>
                            ))}
                        </div>
                    )}
                </div>
            )}
        </UserLayout>
    );
}
