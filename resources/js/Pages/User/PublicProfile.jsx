import { Head, Link, router } from '@inertiajs/react';
import { useState } from 'react';
import UserLayout from '../../Layouts/UserLayout';

const T = '#0D9488';
const AV_COLORS = ['#0D9488','#3B82F6','#8B5CF6','#F59E0B','#EF4444','#EC4899','#0F766E'];

function Avatar({ user, size = 80 }) {
    const [err, setErr] = useState(false);
    const business = user.businesses;
    const src = business?.image ? `/storage/${business.image}`
        : user.profile_photo ? `/storage/${user.profile_photo}` : null;
    const initial = [user.first_name?.[0], user.last_name?.[0]].filter(Boolean).join('').toUpperCase();
    const color = AV_COLORS[(user.id ?? 0) % 7];
    return (
        <div style={{ width: size, height: size, borderRadius: '50%', background: color, color: '#fff', display: 'flex', alignItems: 'center', justifyContent: 'center', fontSize: size * 0.34, fontWeight: 800, overflow: 'hidden', flexShrink: 0 }}>
            {(src && !err)
                ? <img src={src} alt="" onError={() => setErr(true)} style={{ width: '100%', height: '100%', objectFit: 'cover' }} />
                : (initial || <i className="ti ti-user" />)
            }
        </div>
    );
}

function VerifyBadge({ status }) {
    if (status === 'approved') return (
        <span style={{ display: 'inline-flex', alignItems: 'center', gap: 5, fontSize: 12, fontWeight: 700, padding: '4px 13px', borderRadius: 20, background: 'linear-gradient(135deg,#16A34A,#15803D)', color: '#fff', boxShadow: '0 3px 10px rgba(22,163,74,0.35)' }}>
            <i className="ti ti-shield-check" style={{ fontSize: 14 }} /> موثّق
        </span>
    );
    if (status === 'pending') return (
        <span style={{ display: 'inline-flex', alignItems: 'center', gap: 5, fontSize: 12, fontWeight: 700, padding: '4px 13px', borderRadius: 20, background: 'linear-gradient(135deg,#F59E0B,#D97706)', color: '#fff', boxShadow: '0 3px 10px rgba(245,158,11,0.35)' }}>
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
                <div style={{ width: 34, height: 34, borderRadius: 10, background: `linear-gradient(135deg,${T},#0F766E)`, display: 'flex', alignItems: 'center', justifyContent: 'center', flexShrink: 0 }}>
                    <i className={`ti ti-${icon}`} style={{ color: '#fff', fontSize: 15 }} />
                </div>
                <div>
                    <div style={{ fontSize: 14, fontWeight: 700, color: '#0F172A' }}>{title}</div>
                    {subtitle && <div style={{ fontSize: 11, color: '#94A3B8' }}>{subtitle}</div>}
                </div>
            </div>
            {children}
        </div>
    );
}

export default function PublicProfile({ profile, authId, isSelf, verifyStatus }) {
    const [chatLoading, setChatLoading] = useState(false);
    const business = profile.businesses;
    const fullName = `${profile.first_name ?? ''} ${profile.last_name ?? ''}`.trim();

    const startChat = () => {
        setChatLoading(true);
        router.post('/user/chat/start', { business_user_id: profile.id }, {
            onSuccess: () => setChatLoading(false),
            onError:   () => setChatLoading(false),
        });
    };

    return (
        <UserLayout title={fullName}>
            <Head title={`${fullName} — Skillify`} />

            {/* Breadcrumb */}
            <div style={{ display: 'flex', alignItems: 'center', gap: 6, fontSize: 12, color: '#94A3B8' }}>
                <Link href="/user/explore" style={{ color: T, textDecoration: 'none' }}>الاستكشاف</Link>
                <i className="ti ti-chevron-right" style={{ fontSize: 11 }} />
                <span>{fullName}</span>
            </div>

            {/* Hero card */}
            <div style={{ background: '#fff', border: '1.5px solid #F1F5F9', borderRadius: 22, overflow: 'hidden', boxShadow: '0 4px 24px rgba(0,0,0,0.06)' }}>
                {/* Cover */}
                <div style={{ height: 130, background: `linear-gradient(135deg,#0D9488,#134E4A 60%,#052e16)`, position: 'relative', overflow: 'hidden' }}>
                    {business?.image && <img src={`/storage/${business.image}`} alt="" style={{ position: 'absolute', inset: 0, width: '100%', height: '100%', objectFit: 'cover', opacity: 0.15, filter: 'blur(8px)', transform: 'scale(1.1)', pointerEvents: 'none' }} />}
                    <div style={{ position: 'absolute', inset: 0, backgroundImage: 'linear-gradient(rgba(255,255,255,0.04) 1px,transparent 1px),linear-gradient(90deg,rgba(255,255,255,0.04) 1px,transparent 1px)', backgroundSize: '32px 32px' }} />
                </div>

                {/* White section */}
                <div style={{ padding: '0 26px 24px', position: 'relative' }}>
                    {/* Avatar */}
                    <div style={{ position: 'relative', display: 'inline-block', marginTop: -46, marginBottom: 14 }}>
                        <div style={{ border: '4px solid #fff', borderRadius: '50%', boxShadow: '0 6px 20px rgba(0,0,0,0.15)', display: 'inline-block' }}>
                            <Avatar user={profile} size={96} />
                        </div>
                        {verifyStatus === 'approved' ? (
                            <div style={{ position: 'absolute', bottom: 4, right: 2, width: 26, height: 26, borderRadius: '50%', background: 'linear-gradient(135deg,#16A34A,#15803D)', border: '3px solid #fff', display: 'flex', alignItems: 'center', justifyContent: 'center', boxShadow: '0 2px 8px rgba(22,163,74,0.5)' }}>
                                <i className="ti ti-shield-check" style={{ fontSize: 11, color: '#fff' }} />
                            </div>
                        ) : verifyStatus === 'pending' ? (
                            <div style={{ position: 'absolute', bottom: 4, right: 2, width: 26, height: 26, borderRadius: '50%', background: 'linear-gradient(135deg,#F59E0B,#D97706)', border: '3px solid #fff', display: 'flex', alignItems: 'center', justifyContent: 'center', boxShadow: '0 2px 8px rgba(245,158,11,0.45)' }}>
                                <i className="ti ti-clock" style={{ fontSize: 11, color: '#fff' }} />
                            </div>
                        ) : null}
                    </div>

                    {/* Name + actions */}
                    <div style={{ display: 'flex', alignItems: 'flex-start', justifyContent: 'space-between', gap: 14, flexWrap: 'wrap' }}>
                        <div>
                            <div style={{ display: 'flex', alignItems: 'center', gap: 10, flexWrap: 'wrap', marginBottom: 6 }}>
                                <span style={{ fontSize: 22, fontWeight: 800, color: '#0F172A' }}>{fullName}</span>
                                <VerifyBadge status={verifyStatus} />
                            </div>
                            {business?.name_job && (
                                <div style={{ fontSize: 13, color: T, fontWeight: 600, marginBottom: 6, display: 'flex', alignItems: 'center', gap: 5 }}>
                                    <i className="ti ti-briefcase" style={{ fontSize: 12 }} />{business.name_job}
                                </div>
                            )}
                            <div style={{ display: 'flex', gap: 14, flexWrap: 'wrap' }}>
                                {profile.city && <span style={{ fontSize: 12, color: '#64748B', display: 'flex', alignItems: 'center', gap: 4 }}><i className="ti ti-map-pin" style={{ color: T, fontSize: 12 }} />{profile.city}</span>}
                                {profile.phone && <span style={{ fontSize: 12, color: '#64748B', display: 'flex', alignItems: 'center', gap: 4 }}><i className="ti ti-phone" style={{ color: T, fontSize: 12 }} />{profile.phone}</span>}
                            </div>
                        </div>

                        {/* Action buttons */}
                        <div style={{ display: 'flex', gap: 10, flexWrap: 'wrap' }}>
                            {!isSelf && (
                                <button onClick={startChat} disabled={chatLoading} style={{
                                    display: 'inline-flex', alignItems: 'center', gap: 8,
                                    padding: '11px 24px', borderRadius: 12,
                                    background: chatLoading ? '#F0FDFA' : `linear-gradient(135deg,${T},#0F766E)`,
                                    color: chatLoading ? T : '#fff', border: `1.5px solid ${T}`,
                                    fontSize: 13.5, fontWeight: 700, cursor: chatLoading ? 'not-allowed' : 'pointer',
                                    boxShadow: chatLoading ? 'none' : `0 4px 14px ${T}44`,
                                    transition: 'all .2s', flexShrink: 0,
                                }}>
                                    <i className="ti ti-message-circle" style={{ fontSize: 16 }} />
                                    {chatLoading ? 'جارٍ الفتح...' : 'مراسلة'}
                                </button>
                            )}
                            {isSelf && (
                                <Link href="/user/profile" style={{ display: 'inline-flex', alignItems: 'center', gap: 8, padding: '11px 22px', borderRadius: 12, background: '#F8FAFC', border: '1.5px solid #E2E8F0', fontSize: 13, fontWeight: 600, color: '#475569', textDecoration: 'none' }}>
                                    <i className="ti ti-edit" /> تعديل الملف
                                </Link>
                            )}
                        </div>
                    </div>
                </div>
            </div>

            {/* Business Info */}
            {business && (
                <SectionCard icon="building-store" title="معلومات النشاط التجاري" subtitle={business.name ?? ''}>
                    <div style={{ padding: '18px 22px', display: 'flex', flexDirection: 'column', gap: 14 }}>
                        <div style={{ display: 'grid', gridTemplateColumns: 'repeat(auto-fill,minmax(200px,1fr))', gap: 12 }}>
                            {business.name && (
                                <div style={{ display: 'flex', alignItems: 'flex-start', gap: 10 }}>
                                    <div style={{ width: 32, height: 32, borderRadius: 8, background: '#F0FDFA', display: 'flex', alignItems: 'center', justifyContent: 'center', flexShrink: 0 }}>
                                        <i className="ti ti-id-badge" style={{ color: T, fontSize: 14 }} />
                                    </div>
                                    <div>
                                        <div style={{ fontSize: 11, color: '#94A3B8', marginBottom: 2 }}>اسم النشاط</div>
                                        <div style={{ fontSize: 13, fontWeight: 600, color: '#0F172A' }}>{business.name}</div>
                                    </div>
                                </div>
                            )}
                            {business.name_job && (
                                <div style={{ display: 'flex', alignItems: 'flex-start', gap: 10 }}>
                                    <div style={{ width: 32, height: 32, borderRadius: 8, background: '#F0FDFA', display: 'flex', alignItems: 'center', justifyContent: 'center', flexShrink: 0 }}>
                                        <i className="ti ti-briefcase" style={{ color: T, fontSize: 14 }} />
                                    </div>
                                    <div>
                                        <div style={{ fontSize: 11, color: '#94A3B8', marginBottom: 2 }}>المسمى الوظيفي</div>
                                        <div style={{ fontSize: 13, fontWeight: 600, color: '#0F172A' }}>{business.name_job}</div>
                                    </div>
                                </div>
                            )}
                            {business.number && (
                                <div style={{ display: 'flex', alignItems: 'flex-start', gap: 10 }}>
                                    <div style={{ width: 32, height: 32, borderRadius: 8, background: '#F0FDFA', display: 'flex', alignItems: 'center', justifyContent: 'center', flexShrink: 0 }}>
                                        <i className="ti ti-phone" style={{ color: T, fontSize: 14 }} />
                                    </div>
                                    <div>
                                        <div style={{ fontSize: 11, color: '#94A3B8', marginBottom: 2 }}>رقم التواصل</div>
                                        <div style={{ fontSize: 13, fontWeight: 600, color: '#0F172A' }}>{business.number}</div>
                                    </div>
                                </div>
                            )}
                            {business.status && (
                                <div style={{ display: 'flex', alignItems: 'flex-start', gap: 10 }}>
                                    <div style={{ width: 32, height: 32, borderRadius: 8, background: '#F0FDFA', display: 'flex', alignItems: 'center', justifyContent: 'center', flexShrink: 0 }}>
                                        <i className="ti ti-circle-check" style={{ color: T, fontSize: 14 }} />
                                    </div>
                                    <div>
                                        <div style={{ fontSize: 11, color: '#94A3B8', marginBottom: 2 }}>حالة الحساب</div>
                                        <span style={{
                                            fontSize: 12, fontWeight: 600, padding: '2px 10px', borderRadius: 20,
                                            background: business.status === 'active' ? '#F0FDF4' : '#FFFBEB',
                                            color: business.status === 'active' ? '#15803D' : '#B45309',
                                        }}>
                                            {business.status === 'active' ? 'نشط' : 'قيد المراجعة'}
                                        </span>
                                    </div>
                                </div>
                            )}
                        </div>
                        {business.description && (
                            <div style={{ background: '#F8FAFC', borderRadius: 10, padding: '12px 16px' }}>
                                <div style={{ fontSize: 11, color: '#94A3B8', marginBottom: 6, display: 'flex', alignItems: 'center', gap: 4 }}>
                                    <i className="ti ti-file-description" style={{ fontSize: 12 }} /> نبذة عن النشاط
                                </div>
                                <div style={{ fontSize: 13, color: '#475569', lineHeight: 1.7 }}>{business.description}</div>
                            </div>
                        )}
                    </div>
                </SectionCard>
            )}

            {/* Services */}
            {profile.services?.length > 0 && (
                <SectionCard icon="tool" title={`خدمات ${fullName}`} subtitle={`${profile.services.length} خدمة متاحة`}>
                    <div style={{ display: 'grid', gridTemplateColumns: 'repeat(auto-fill,minmax(200px,1fr))', gap: 1 }}>
                        {profile.services.map(s => (
                            <Link key={s.id} href={`/user/services/${s.id}/details`} style={{ padding: '14px 18px', display: 'flex', alignItems: 'center', gap: 12, textDecoration: 'none', borderRight: '1px solid #F1F5F9', transition: 'background .12s' }}
                                onMouseEnter={e => e.currentTarget.style.background = '#F8FFFE'}
                                onMouseLeave={e => e.currentTarget.style.background = 'transparent'}
                            >
                                <div style={{ width: 38, height: 38, borderRadius: 10, background: '#F0FDFA', display: 'flex', alignItems: 'center', justifyContent: 'center', flexShrink: 0 }}>
                                    {s.image
                                        ? <img src={`/storage/${s.image}`} alt="" style={{ width: '100%', height: '100%', objectFit: 'cover', borderRadius: 10 }} />
                                        : <i className="ti ti-tool" style={{ color: T, fontSize: 16 }} />
                                    }
                                </div>
                                <div style={{ minWidth: 0 }}>
                                    <div style={{ fontSize: 13, fontWeight: 600, color: '#0F172A', overflow: 'hidden', textOverflow: 'ellipsis', whiteSpace: 'nowrap' }}>{s.name}</div>
                                    <div style={{ fontSize: 11, color: '#94A3B8', marginTop: 2 }}>{s.category?.name ?? ''}{s.city?.name ? ` · ${s.city.name}` : ''}</div>
                                </div>
                                <i className="ti ti-chevron-left" style={{ fontSize: 13, color: '#CBD5E1', marginRight: 'auto', flexShrink: 0 }} />
                            </Link>
                        ))}
                    </div>
                </SectionCard>
            )}

            {/* Recent Posts */}
            {profile.posts?.length > 0 && (
                <SectionCard icon="news" title="المنشورات الحديثة" subtitle={`${profile.posts.length} منشور`}>
                    <div style={{ display: 'flex', flexDirection: 'column', divide: '1px solid #F1F5F9' }}>
                        {profile.posts.map((post, i) => (
                            <div key={post.id} style={{ padding: '14px 22px', borderTop: i === 0 ? 'none' : '1px solid #F8FAFC', display: 'flex', gap: 14, alignItems: 'flex-start' }}>
                                {post.image && (
                                    <div style={{ width: 52, height: 52, borderRadius: 10, overflow: 'hidden', flexShrink: 0 }}>
                                        <img
                                            src={post.image.startsWith('http') ? post.image : `/storage/${post.image}`}
                                            alt=""
                                            style={{ width: '100%', height: '100%', objectFit: 'cover' }}
                                        />
                                    </div>
                                )}
                                {!post.image && (
                                    <div style={{ width: 52, height: 52, borderRadius: 10, background: '#F0FDFA', display: 'flex', alignItems: 'center', justifyContent: 'center', flexShrink: 0 }}>
                                        <i className="ti ti-news" style={{ color: T, fontSize: 20 }} />
                                    </div>
                                )}
                                <div style={{ flex: 1, minWidth: 0 }}>
                                    <div style={{ fontSize: 13, fontWeight: 700, color: '#0F172A', marginBottom: 4, overflow: 'hidden', textOverflow: 'ellipsis', whiteSpace: 'nowrap' }}>{post.title}</div>
                                    <div style={{ fontSize: 12, color: '#64748B', lineHeight: 1.55, display: '-webkit-box', WebkitLineClamp: 2, WebkitBoxOrient: 'vertical', overflow: 'hidden' }}>
                                        {post.description}
                                    </div>
                                    <div style={{ fontSize: 11, color: '#94A3B8', marginTop: 6 }}>
                                        {new Date(post.created_at).toLocaleDateString('ar', { year: 'numeric', month: 'short', day: 'numeric' })}
                                    </div>
                                </div>
                            </div>
                        ))}
                    </div>
                </SectionCard>
            )}

            {/* Empty state when nothing to show */}
            {!business && !profile.services?.length && !profile.posts?.length && (
                <div style={{ textAlign: 'center', padding: '48px 24px', color: '#94A3B8', background: '#fff', borderRadius: 18, border: '1.5px solid #F1F5F9' }}>
                    <i className="ti ti-user-off" style={{ fontSize: 44, display: 'block', marginBottom: 12, opacity: 0.3 }} />
                    <div style={{ fontSize: 14, fontWeight: 600, marginBottom: 4 }}>لا توجد معلومات إضافية</div>
                    <div style={{ fontSize: 12 }}>لم يضف هذا المستخدم خدمات أو منشورات بعد.</div>
                </div>
            )}
        </UserLayout>
    );
}
