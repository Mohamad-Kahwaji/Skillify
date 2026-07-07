import { Head, Link } from '@inertiajs/react';
import { useState } from 'react';
import SuperAdminLayout from '../../Layouts/SuperAdminLayout';

const T = '#7C3AED';

function Lightbox({ src, onClose }) {
    if (!src) return null;
    return (
        <div onClick={onClose} style={{ position: 'fixed', inset: 0, background: 'rgba(0,0,0,0.85)', zIndex: 999, display: 'flex', alignItems: 'center', justifyContent: 'center', padding: 20 }}>
            <img src={src} alt="" onClick={e => e.stopPropagation()} style={{ maxWidth: '90vw', maxHeight: '90vh', borderRadius: 14, boxShadow: '0 24px 80px rgba(0,0,0,0.6)', objectFit: 'contain' }} />
            <button onClick={onClose} style={{ position: 'absolute', top: 20, left: 20, width: 40, height: 40, borderRadius: '50%', border: 'none', background: 'rgba(255,255,255,0.12)', color: '#fff', fontSize: 18, cursor: 'pointer', display: 'flex', alignItems: 'center', justifyContent: 'center' }}>
                <i className="ti ti-x" />
            </button>
        </div>
    );
}

function VerifyBadge({ status }) {
    if (status === 'approved') return (
        <span style={{ display: 'inline-flex', alignItems: 'center', gap: 5, fontSize: 12, fontWeight: 700, padding: '4px 13px', borderRadius: 20, background: 'linear-gradient(135deg,#16A34A,#15803D)', color: '#fff' }}>
            <i className="ti ti-shield-check" style={{ fontSize: 14 }} /> موثّق
        </span>
    );
    if (status === 'pending') return (
        <span style={{ display: 'inline-flex', alignItems: 'center', gap: 5, fontSize: 12, fontWeight: 700, padding: '4px 13px', borderRadius: 20, background: 'linear-gradient(135deg,#F59E0B,#D97706)', color: '#fff' }}>
            <i className="ti ti-clock" style={{ fontSize: 14 }} /> قيد التحقق
        </span>
    );
    return (
        <span style={{ display: 'inline-flex', alignItems: 'center', gap: 5, fontSize: 12, fontWeight: 700, padding: '4px 13px', borderRadius: 20, background: '#F1F5F9', color: '#64748B', border: '1px solid #E2E8F0' }}>
            <i className="ti ti-shield-x" style={{ fontSize: 14 }} /> غير موثّق
        </span>
    );
}

function SectionCard({ icon, title, subtitle, children }) {
    return (
        <div style={{ background: '#fff', border: '1.5px solid #F1F5F9', borderRadius: 18, overflow: 'hidden' }}>
            <div style={{ padding: '16px 22px', borderBottom: '1px solid #F1F5F9', display: 'flex', alignItems: 'center', gap: 10 }}>
                <div style={{ width: 34, height: 34, borderRadius: 10, background: `linear-gradient(135deg,${T},#5B21B6)`, display: 'flex', alignItems: 'center', justifyContent: 'center', flexShrink: 0 }}>
                    <i className={`ti ti-${icon}`} style={{ color: '#fff', fontSize: 15 }} />
                </div>
                <div>
                    <div style={{ fontSize: 14, fontWeight: 700, color: '#1E1B4B' }}>{title}</div>
                    {subtitle && <div style={{ fontSize: 11, color: '#94A3B8' }}>{subtitle}</div>}
                </div>
            </div>
            {children}
        </div>
    );
}

export default function UserProfile({ profile, verifyStatus }) {
    const [lightbox, setLightbox] = useState(null);
    const business = profile.businesses;
    const fullName = `${profile.first_name ?? ''} ${profile.last_name ?? ''}`.trim();

    return (
        <SuperAdminLayout title={fullName}>
            <Head title={`${fullName} — Skillify`} />

            <Lightbox src={lightbox} onClose={() => setLightbox(null)} />

            <div style={{ display: 'flex', alignItems: 'center', gap: 6, fontSize: 12, color: '#94A3B8' }}>
                <Link href="/super-admin/users" style={{ color: T, textDecoration: 'none' }}>المستخدمون</Link>
                <i className="ti ti-chevron-left" style={{ fontSize: 11 }} />
                <span>{fullName}</span>
            </div>

            {/* Hero card */}
            <div style={{ background: '#fff', border: '1.5px solid #F1F5F9', borderRadius: 22, overflow: 'hidden', boxShadow: '0 4px 24px rgba(0,0,0,0.06)' }}>
                <div style={{ height: 120, background: `linear-gradient(135deg,${T},#5B21B6 60%,#1E1B4B)`, position: 'relative', overflow: 'hidden' }}>
                    {business?.image && <img src={`/storage/${business.image}`} alt="" style={{ position: 'absolute', inset: 0, width: '100%', height: '100%', objectFit: 'cover', opacity: 0.15, filter: 'blur(8px)', transform: 'scale(1.1)' }} />}
                </div>
                <div style={{ padding: '0 26px 24px' }}>
                    <div style={{ position: 'relative', display: 'inline-block', marginTop: -42, marginBottom: 14 }}>
                        <div style={{ width: 88, height: 88, borderRadius: '50%', border: '4px solid #fff', boxShadow: '0 6px 20px rgba(0,0,0,0.15)', background: T, display: 'flex', alignItems: 'center', justifyContent: 'center', overflow: 'hidden' }}>
                            {business?.image
                                ? <img src={`/storage/${business.image}`} alt="" style={{ width: '100%', height: '100%', objectFit: 'cover' }} />
                                : <span style={{ fontSize: 30, fontWeight: 800, color: '#fff' }}>{(profile.first_name?.[0] ?? 'U').toUpperCase()}</span>
                            }
                        </div>
                    </div>

                    <div style={{ display: 'flex', alignItems: 'flex-start', justifyContent: 'space-between', gap: 14, flexWrap: 'wrap' }}>
                        <div>
                            <div style={{ display: 'flex', alignItems: 'center', gap: 10, flexWrap: 'wrap', marginBottom: 6 }}>
                                <span style={{ fontSize: 21, fontWeight: 800, color: '#1E1B4B' }}>{fullName}</span>
                                <VerifyBadge status={verifyStatus} />
                            </div>
                            {business?.name_job && (
                                <div style={{ fontSize: 13, color: T, fontWeight: 600, marginBottom: 6, display: 'flex', alignItems: 'center', gap: 5 }}>
                                    <i className="ti ti-briefcase" style={{ fontSize: 12 }} />{business.name_job}
                                </div>
                            )}
                            <div style={{ display: 'flex', gap: 14, flexWrap: 'wrap' }}>
                                <span style={{ fontSize: 12, color: '#64748B', display: 'flex', alignItems: 'center', gap: 4 }}><i className="ti ti-mail" style={{ color: T, fontSize: 12 }} />{profile.email}</span>
                                {profile.city && <span style={{ fontSize: 12, color: '#64748B', display: 'flex', alignItems: 'center', gap: 4 }}><i className="ti ti-map-pin" style={{ color: T, fontSize: 12 }} />{profile.city}</span>}
                                {profile.phone && <span style={{ fontSize: 12, color: '#64748B', display: 'flex', alignItems: 'center', gap: 4 }}><i className="ti ti-phone" style={{ color: T, fontSize: 12 }} />{profile.phone}</span>}
                            </div>
                        </div>
                        <span style={{ fontSize: 11, fontWeight: 700, padding: '5px 12px', borderRadius: 20, background: profile.status === 'active' ? '#D1FAE5' : '#FEF3C7', color: profile.status === 'active' ? '#065F46' : '#92400E' }}>
                            {profile.status === 'active' ? 'حساب نشط' : 'حساب موقوف'}
                        </span>
                    </div>
                </div>
            </div>

            {/* Business Info */}
            {business && (
                <SectionCard icon="building-store" title="معلومات النشاط التجاري" subtitle={business.name ?? ''}>
                    <div style={{ padding: '18px 22px', display: 'flex', flexDirection: 'column', gap: 14 }}>
                        <div style={{ display: 'grid', gridTemplateColumns: 'repeat(auto-fill,minmax(200px,1fr))', gap: 12 }}>
                            {business.number && (
                                <div>
                                    <div style={{ fontSize: 11, color: '#94A3B8', marginBottom: 2 }}>رقم التواصل</div>
                                    <div style={{ fontSize: 13, fontWeight: 600, color: '#0F172A' }}>{business.number}</div>
                                </div>
                            )}
                            <div>
                                <div style={{ fontSize: 11, color: '#94A3B8', marginBottom: 2 }}>حالة الحساب التجاري</div>
                                <div style={{ fontSize: 13, fontWeight: 600, color: '#0F172A' }}>
                                    {business.status === 'active' ? 'نشط' : business.status === 'pending' ? 'قيد المراجعة' : 'مرفوض'}
                                </div>
                            </div>
                        </div>
                        {business.description && (
                            <div style={{ background: '#F8FAFC', borderRadius: 10, padding: '12px 16px' }}>
                                <div style={{ fontSize: 11, color: '#94A3B8', marginBottom: 6 }}>نبذة عن النشاط</div>
                                <div style={{ fontSize: 13, color: '#475569', lineHeight: 1.7 }}>{business.description}</div>
                            </div>
                        )}
                    </div>
                </SectionCard>
            )}

            {/* Portfolio Gallery */}
            {business?.gallery?.length > 0 && (
                <SectionCard icon="photo" title="معرض الأعمال" subtitle={`${business.gallery.length} عمل`}>
                    <div style={{ padding: 18, display: 'grid', gridTemplateColumns: 'repeat(auto-fill,minmax(160px,1fr))', gap: 12 }}>
                        {business.gallery.map(g => (
                            <div key={g.id} onClick={() => setLightbox(`/storage/${g.image}`)}
                                style={{ cursor: 'zoom-in', borderRadius: 12, overflow: 'hidden', border: '1px solid #F1F5F9', aspectRatio: '4 / 3' }}>
                                <img src={`/storage/${g.image}`} alt="" style={{ width: '100%', height: '100%', objectFit: 'cover' }} />
                            </div>
                        ))}
                    </div>
                </SectionCard>
            )}

            {/* Services */}
            {profile.services?.length > 0 && (
                <SectionCard icon="tool" title={`خدمات ${fullName}`} subtitle={`${profile.services.length} خدمة`}>
                    <div style={{ display: 'grid', gridTemplateColumns: 'repeat(auto-fill,minmax(200px,1fr))', gap: 1 }}>
                        {profile.services.map(s => (
                            <div key={s.id} style={{ padding: '14px 18px', display: 'flex', alignItems: 'center', gap: 12, borderRight: '1px solid #F1F5F9' }}>
                                <div style={{ width: 38, height: 38, borderRadius: 10, background: '#F5F3FF', display: 'flex', alignItems: 'center', justifyContent: 'center', flexShrink: 0 }}>
                                    <i className="ti ti-tool" style={{ color: T, fontSize: 16 }} />
                                </div>
                                <div style={{ minWidth: 0 }}>
                                    <div style={{ fontSize: 13, fontWeight: 600, color: '#0F172A', overflow: 'hidden', textOverflow: 'ellipsis', whiteSpace: 'nowrap' }}>{s.name}</div>
                                    <div style={{ fontSize: 11, color: '#94A3B8', marginTop: 2 }}>{s.category?.name ?? ''}{s.city?.name ? ` · ${s.city.name}` : ''}</div>
                                </div>
                            </div>
                        ))}
                    </div>
                </SectionCard>
            )}

            {/* Recent Posts */}
            {profile.posts?.length > 0 && (
                <SectionCard icon="news" title="المنشورات الحديثة" subtitle={`${profile.posts.length} منشور`}>
                    <div style={{ display: 'flex', flexDirection: 'column' }}>
                        {profile.posts.map((post, i) => (
                            <div key={post.id} style={{ padding: '14px 22px', borderTop: i === 0 ? 'none' : '1px solid #F8FAFC' }}>
                                <div style={{ fontSize: 13, fontWeight: 700, color: '#0F172A', marginBottom: 4 }}>{post.title}</div>
                                <div style={{ fontSize: 12, color: '#64748B', lineHeight: 1.55 }}>{post.description}</div>
                            </div>
                        ))}
                    </div>
                </SectionCard>
            )}

            {!business && !profile.services?.length && !profile.posts?.length && (
                <div style={{ textAlign: 'center', padding: '48px 24px', color: '#94A3B8', background: '#fff', borderRadius: 18, border: '1.5px solid #F1F5F9' }}>
                    <i className="ti ti-user-off" style={{ fontSize: 44, display: 'block', marginBottom: 12, opacity: 0.3 }} />
                    <div style={{ fontSize: 14, fontWeight: 600, marginBottom: 4 }}>لا توجد معلومات إضافية</div>
                    <div style={{ fontSize: 12 }}>لم يضف هذا المستخدم خدمات أو منشورات بعد.</div>
                </div>
            )}
        </SuperAdminLayout>
    );
}
