import { Head, Link, router } from '@inertiajs/react';
import { useState } from 'react';
import UserLayout from '../../Layouts/UserLayout';

const T = '#0D9488';
const AV_COLORS = ['#0D9488','#3B82F6','#8B5CF6','#F59E0B','#EF4444','#EC4899','#0F766E'];

function ProviderAvatar({ user, business, size = 56 }) {
    const [err, setErr] = useState(false);
    const src = business?.image ? `/storage/${business.image}`
        : user?.profile_photo ? `/storage/${user.profile_photo}` : null;
    const initial = (user?.first_name ?? business?.name ?? 'U')[0].toUpperCase();
    const color = AV_COLORS[(user?.id ?? 0) % 7];
    return (
        <div style={{ width: size, height: size, borderRadius: '50%', background: color, color: '#fff', display: 'flex', alignItems: 'center', justifyContent: 'center', fontSize: size * 0.36, fontWeight: 700, flexShrink: 0, overflow: 'hidden' }}>
            {src && !err
                ? <img src={src} alt="" onError={() => setErr(true)} style={{ width: '100%', height: '100%', objectFit: 'cover' }} />
                : initial
            }
        </div>
    );
}

function VerifyBadge({ status }) {
    if (status === 'approved') return (
        <span style={{ display: 'inline-flex', alignItems: 'center', gap: 4, fontSize: 11, fontWeight: 600, padding: '3px 8px', borderRadius: 20, background: 'linear-gradient(135deg,#16A34A,#15803D)', color: '#fff' }}>
            <i className="ti ti-shield-check" style={{ fontSize: 12 }} /> موثّق
        </span>
    );
    if (status === 'pending') return (
        <span style={{ display: 'inline-flex', alignItems: 'center', gap: 4, fontSize: 11, fontWeight: 600, padding: '3px 8px', borderRadius: 20, background: 'linear-gradient(135deg,#F59E0B,#D97706)', color: '#fff' }}>
            <i className="ti ti-clock" style={{ fontSize: 12 }} /> قيد التحقق
        </span>
    );
    return null;
}

function InfoRow({ icon, label, value }) {
    if (!value) return null;
    return (
        <div style={{ display: 'flex', alignItems: 'flex-start', gap: 10 }}>
            <div style={{ width: 30, height: 30, borderRadius: 8, background: '#F0FDFA', display: 'flex', alignItems: 'center', justifyContent: 'center', flexShrink: 0 }}>
                <i className={`ti ti-${icon}`} style={{ color: T, fontSize: 13 }} />
            </div>
            <div>
                <div style={{ fontSize: 10, color: '#94A3B8', marginBottom: 1 }}>{label}</div>
                <div style={{ fontSize: 13, fontWeight: 600, color: '#0F172A' }}>{value}</div>
            </div>
        </div>
    );
}

export default function ServiceDetails({ service, authId }) {
    const [chatLoading, setChatLoading] = useState(false);
    const business       = service.business ?? service.user?.businesses;
    const owner          = service.user;
    const img            = service.image ? (service.image.startsWith('http') ? service.image : `/storage/${service.image}`) : null;
    const price          = Number(service.price).toLocaleString();
    const isOwn          = owner?.id === authId;
    const hasOwner       = !!owner?.id;
    const identityStatus = owner?.identity_verification?.status;
    const providerName   = owner
        ? `${owner.first_name ?? ''} ${owner.last_name ?? ''}`.trim()
        : (business?.name ?? null);

    const startChat = () => {
        if (!hasOwner || chatLoading) return;
        setChatLoading(true);
        router.post('/user/chat/start', { business_user_id: owner.id }, {
            onSuccess: () => setChatLoading(false),
            onError:   () => setChatLoading(false),
        });
    };

    return (
        <UserLayout title="تفاصيل الخدمة">
            <Head title={`${service.name} — Skillify`} />

            {/* Breadcrumb */}
            <div style={{ display: 'flex', alignItems: 'center', gap: 6, fontSize: 12, color: '#94A3B8' }}>
                <Link href="/user/services" style={{ color: T, textDecoration: 'none' }}>الخدمات</Link>
                <i className="ti ti-chevron-right" style={{ fontSize: 11 }} />
                <span>{service.name}</span>
            </div>

            <div className="grid grid-cols-1 lg:grid-cols-[1fr_320px]" style={{ gap: 20, alignItems: 'start' }}>

                {/* ── Left column ── */}
                <div style={{ display: 'flex', flexDirection: 'column', gap: 16 }}>

                    {/* Image */}
                    <div style={{ width: '100%', aspectRatio: '16/7', background: 'linear-gradient(135deg,#F0FDFA,#E6FFFA)', borderRadius: 18, overflow: 'hidden', display: 'flex', alignItems: 'center', justifyContent: 'center' }}>
                        {img
                            ? <img src={img} alt={service.name} style={{ width: '100%', height: '100%', objectFit: 'cover' }} />
                            : <i className="ti ti-tool" style={{ fontSize: 56, color: T, opacity: 0.25 }} />
                        }
                    </div>

                    {/* Title + tags + price */}
                    <div style={{ background: '#fff', border: '1px solid #F1F5F9', borderRadius: 16, padding: '22px 24px' }}>
                        <div style={{ display: 'flex', alignItems: 'flex-start', justifyContent: 'space-between', gap: 12, flexWrap: 'wrap', marginBottom: 14 }}>
                            <div style={{ flex: 1 }}>
                                <div style={{ fontSize: 22, fontWeight: 800, color: '#0F172A', lineHeight: 1.2, marginBottom: 10 }}>{service.name}</div>
                                <div style={{ display: 'flex', flexWrap: 'wrap', gap: 6 }}>
                                    {service.category?.name && (
                                        <span style={{ fontSize: 11, color: T, background: '#F0FDFA', padding: '4px 10px', borderRadius: 20, border: '1px solid #99F6E4', display: 'inline-flex', alignItems: 'center', gap: 4 }}>
                                            <i className="ti ti-tag" style={{ fontSize: 12 }} /> {service.category.name}
                                        </span>
                                    )}
                                    {service.subcategory?.name && (
                                        <span style={{ fontSize: 11, color: '#475569', background: '#F8FAFC', padding: '4px 10px', borderRadius: 20, border: '1px solid #E2E8F0', display: 'inline-flex', alignItems: 'center', gap: 4 }}>
                                            <i className="ti ti-point" style={{ fontSize: 12 }} /> {service.subcategory.name}
                                        </span>
                                    )}
                                    {service.city?.name && (
                                        <span style={{ fontSize: 11, color: '#475569', background: '#F8FAFC', padding: '4px 10px', borderRadius: 20, border: '1px solid #E2E8F0', display: 'inline-flex', alignItems: 'center', gap: 4 }}>
                                            <i className="ti ti-map-pin" style={{ fontSize: 12 }} /> {service.city.name}
                                        </span>
                                    )}
                                    <span style={{ fontSize: 11, fontWeight: 600, padding: '4px 10px', borderRadius: 20, background: service.is_active ? '#F0FDF4' : '#FEF2F2', color: service.is_active ? '#15803D' : '#B91C1C', border: `1px solid ${service.is_active ? '#BBF7D0' : '#FECACA'}`, display: 'inline-flex', alignItems: 'center', gap: 4 }}>
                                        <i className={`ti ti-${service.is_active ? 'circle-check' : 'circle-x'}`} style={{ fontSize: 12 }} />
                                        {service.is_active ? 'متاح' : 'غير متاح'}
                                    </span>
                                </div>
                            </div>
                            <div style={{ textAlign: 'left', flexShrink: 0 }}>
                                <div style={{ fontSize: 28, fontWeight: 900, color: T, lineHeight: 1 }}>{price}</div>
                                <div style={{ fontSize: 12, color: '#94A3B8', marginTop: 2 }}>
                                    {service.price_type === 'usd' ? 'دولار أمريكي' : 'ليرة سورية'}
                                </div>
                            </div>
                        </div>

                        {service.description && (
                            <>
                                <div style={{ height: 1, background: '#F1F5F9', margin: '14px 0' }} />
                                <div style={{ fontSize: 13, color: '#475569', lineHeight: 1.8 }}>{service.description}</div>
                            </>
                        )}
                    </div>

                    {/* Service details grid */}
                    <div style={{ background: '#fff', border: '1px solid #F1F5F9', borderRadius: 16, padding: '20px 24px' }}>
                        <div style={{ fontSize: 13, fontWeight: 700, color: '#0F172A', marginBottom: 16, display: 'flex', alignItems: 'center', gap: 8 }}>
                            <div style={{ width: 28, height: 28, borderRadius: 8, background: `linear-gradient(135deg,${T},#0F766E)`, display: 'flex', alignItems: 'center', justifyContent: 'center' }}>
                                <i className="ti ti-info-circle" style={{ color: '#fff', fontSize: 13 }} />
                            </div>
                            تفاصيل الخدمة
                        </div>
                        <div style={{ display: 'grid', gridTemplateColumns: 'repeat(auto-fill,minmax(180px,1fr))', gap: 14 }}>
                            <InfoRow icon="tag"         label="التصنيف"      value={service.category?.name} />
                            <InfoRow icon="bookmark"    label="التصنيف الفرعي" value={service.subcategory?.name} />
                            <InfoRow icon="map-pin"     label="المدينة"       value={service.city?.name} />
                            <InfoRow icon="coin"        label="السعر"         value={`${price} ${service.price_type === 'usd' ? 'USD' : 'SYP'}`} />
                            <InfoRow icon="calendar"    label="تاريخ النشر"   value={new Date(service.created_at).toLocaleDateString('ar', { year: 'numeric', month: 'long', day: 'numeric' })} />
                            <InfoRow icon="refresh"     label="آخر تحديث"     value={new Date(service.updated_at).toLocaleDateString('ar', { year: 'numeric', month: 'long', day: 'numeric' })} />
                        </div>
                    </div>
                </div>

                {/* ── Right column ── */}
                <div style={{ display: 'flex', flexDirection: 'column', gap: 14 }}>

                    {/* Provider card */}
                    <div style={{ background: '#fff', border: '1px solid #F1F5F9', borderRadius: 16, padding: 20 }}>
                        <div style={{ fontSize: 11, fontWeight: 700, color: '#94A3B8', marginBottom: 14, textTransform: 'uppercase', letterSpacing: 0.8 }}>
                            مقدم الخدمة
                        </div>

                        {hasOwner ? (
                            <>
                                <Link href={`/user/users/${owner.id}`} style={{ display: 'flex', alignItems: 'center', gap: 12, marginBottom: 14, textDecoration: 'none' }}>
                                    <div style={{ border: `2px solid ${T}22`, borderRadius: '50%', padding: 2 }}>
                                        <ProviderAvatar user={owner} business={business} size={52} />
                                    </div>
                                    <div>
                                        <div style={{ display: 'flex', alignItems: 'center', gap: 6, flexWrap: 'wrap', marginBottom: 3 }}>
                                            <span style={{ fontSize: 14, fontWeight: 700, color: '#0F172A' }}>{providerName}</span>
                                            <VerifyBadge status={identityStatus} />
                                        </div>
                                        {(business?.name_job || business?.activity) && (
                                            <div style={{ fontSize: 12, color: T }}>{business.name_job ?? business.activity}</div>
                                        )}
                                    </div>
                                </Link>

                                {business?.description && (
                                    <div style={{ fontSize: 12, color: '#475569', lineHeight: 1.65, padding: '10px 12px', background: '#F8FAFC', borderRadius: 10, marginBottom: 14 }}>
                                        {business.description}
                                    </div>
                                )}

                                <div style={{ display: 'flex', flexDirection: 'column', gap: 8, marginBottom: 16 }}>
                                    {owner.city && <div style={{ display: 'flex', alignItems: 'center', gap: 6, fontSize: 12, color: '#475569' }}><i className="ti ti-map-pin" style={{ color: T, fontSize: 13 }} />{owner.city}</div>}
                                    {owner.phone && <div style={{ display: 'flex', alignItems: 'center', gap: 6, fontSize: 12, color: '#475569' }}><i className="ti ti-phone" style={{ color: T, fontSize: 13 }} />{owner.phone}</div>}
                                    {business?.number && <div style={{ display: 'flex', alignItems: 'center', gap: 6, fontSize: 12, color: '#475569' }}><i className="ti ti-device-mobile" style={{ color: T, fontSize: 13 }} />{business.number}</div>}
                                </div>

                                {isOwn ? (
                                    <div style={{ width: '100%', padding: '11px', borderRadius: 10, background: '#F8FAFC', border: '1px solid #E2E8F0', color: '#64748B', fontSize: 13, fontWeight: 600, textAlign: 'center' }}>
                                        هذه خدمتك
                                    </div>
                                ) : (
                                    <div style={{ display: 'flex', flexDirection: 'column', gap: 8 }}>
                                        <button onClick={startChat} disabled={chatLoading} style={{
                                            width: '100%', padding: '12px', borderRadius: 10, border: 'none',
                                            background: chatLoading ? '#F0FDFA' : `linear-gradient(135deg,${T},#0F766E)`,
                                            color: chatLoading ? T : '#fff', fontSize: 13, fontWeight: 700,
                                            cursor: chatLoading ? 'not-allowed' : 'pointer',
                                            display: 'flex', alignItems: 'center', justifyContent: 'center', gap: 8,
                                            boxShadow: chatLoading ? 'none' : `0 4px 14px ${T}44`,
                                            transition: 'all .2s',
                                        }}>
                                            <i className="ti ti-message-circle" style={{ fontSize: 16 }} />
                                            {chatLoading ? 'جارٍ الفتح...' : 'مراسلة مقدم الخدمة'}
                                        </button>
                                        <Link href={`/user/users/${owner.id}`} style={{
                                            width: '100%', padding: '10px', borderRadius: 10, border: '1px solid #E2E8F0',
                                            background: '#fff', color: '#475569', fontSize: 12, fontWeight: 600,
                                            textDecoration: 'none', textAlign: 'center',
                                            display: 'flex', alignItems: 'center', justifyContent: 'center', gap: 6,
                                        }}>
                                            <i className="ti ti-user" style={{ fontSize: 13 }} /> عرض الملف الشخصي
                                        </Link>
                                    </div>
                                )}
                            </>
                        ) : (
                            /* Seeded/anonymous service — no real user */
                            <div style={{ textAlign: 'center', padding: '20px 0' }}>
                                <div style={{ width: 52, height: 52, borderRadius: '50%', background: '#F1F5F9', display: 'flex', alignItems: 'center', justifyContent: 'center', margin: '0 auto 12px' }}>
                                    <i className="ti ti-user-off" style={{ fontSize: 22, color: '#94A3B8' }} />
                                </div>
                                <div style={{ fontSize: 13, fontWeight: 600, color: '#64748B', marginBottom: 4 }}>لا يوجد مقدم خدمة</div>
                                <div style={{ fontSize: 11, color: '#94A3B8' }}>هذه خدمة مُضافة من النظام</div>
                            </div>
                        )}
                    </div>

                    {/* Pricing card */}
                    <div style={{ background: `linear-gradient(135deg,#0D9488,#0F766E)`, borderRadius: 16, padding: 20 }}>
                        <div style={{ fontSize: 11, fontWeight: 700, color: 'rgba(255,255,255,0.7)', marginBottom: 12, textTransform: 'uppercase', letterSpacing: 0.8 }}>
                            التسعير
                        </div>
                        <div style={{ display: 'flex', alignItems: 'flex-end', justifyContent: 'space-between' }}>
                            <div>
                                <div style={{ fontSize: 11, color: 'rgba(255,255,255,0.7)', marginBottom: 4 }}>يبدأ من</div>
                                <div style={{ fontSize: 32, fontWeight: 900, color: '#fff', lineHeight: 1 }}>{price}</div>
                                <div style={{ fontSize: 12, color: 'rgba(255,255,255,0.8)', marginTop: 4 }}>
                                    {service.price_type === 'usd' ? 'دولار أمريكي' : 'ليرة سورية'}
                                </div>
                            </div>
                            <div style={{ width: 52, height: 52, borderRadius: 14, background: 'rgba(255,255,255,0.15)', display: 'flex', alignItems: 'center', justifyContent: 'center' }}>
                                <i className="ti ti-coin" style={{ fontSize: 26, color: '#fff' }} />
                            </div>
                        </div>
                    </div>

                    {/* Quick info */}
                    <div style={{ background: '#fff', border: '1px solid #F1F5F9', borderRadius: 16, padding: '16px 20px', display: 'flex', flexDirection: 'column', gap: 10 }}>
                        <div style={{ fontSize: 11, fontWeight: 700, color: '#94A3B8', textTransform: 'uppercase', letterSpacing: 0.8 }}>معلومات سريعة</div>
                        {service.city?.name && (
                            <div style={{ display: 'flex', alignItems: 'center', gap: 8, fontSize: 12, color: '#475569' }}>
                                <i className="ti ti-map-pin" style={{ color: T, fontSize: 14 }} />
                                <span>{service.city.name}</span>
                            </div>
                        )}
                        <div style={{ display: 'flex', alignItems: 'center', gap: 8, fontSize: 12, color: '#475569' }}>
                            <i className="ti ti-calendar" style={{ color: T, fontSize: 14 }} />
                            <span>نُشرت {new Date(service.created_at).toLocaleDateString('ar', { year: 'numeric', month: 'short', day: 'numeric' })}</span>
                        </div>
                        <div style={{ display: 'flex', alignItems: 'center', gap: 8, fontSize: 12 }}>
                            <i className={`ti ti-${service.is_active ? 'circle-check' : 'circle-x'}`} style={{ color: service.is_active ? '#16A34A' : '#B91C1C', fontSize: 14 }} />
                            <span style={{ color: service.is_active ? '#16A34A' : '#B91C1C', fontWeight: 600 }}>
                                {service.is_active ? 'الخدمة متاحة' : 'الخدمة غير متاحة'}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </UserLayout>
    );
}
