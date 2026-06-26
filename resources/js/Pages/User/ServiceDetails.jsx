import { Head, Link, router } from '@inertiajs/react';
import { useState } from 'react';
import UserLayout from '../../Layouts/UserLayout';

const AV_COLORS = ['#0D9488','#3B82F6','#8B5CF6','#F59E0B','#EF4444','#EC4899','#0F766E'];

export default function ServiceDetails({ service, authId }) {
    const [chatLoading, setChatLoading] = useState(false);
    const business  = service.business ?? service.user?.businesses;
    const owner     = service.user;
    const initial   = (owner?.first_name ?? 'U')[0].toUpperCase();
    const ownerColor = AV_COLORS[(owner?.id ?? 0) % 7];
    const img       = service.image ? (service.image.startsWith('http') ? service.image : `/storage/${service.image}`) : null;
    const price     = Number(service.price).toLocaleString();

    const startChat = () => {
        if (!owner?.id) return;
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
                <Link href="/user/services" style={{ color: '#0D9488', textDecoration: 'none' }}>الخدمات</Link>
                <i className="ti ti-chevron-right" style={{ fontSize: 11 }} />
                <span style={{ color: '#475569' }}>{service.name}</span>
            </div>

            <div style={{ display: 'grid', gridTemplateColumns: '1fr 340px', gap: 20, alignItems: 'start' }}>
                {/* Left: service info */}
                <div style={{ display: 'flex', flexDirection: 'column', gap: 16 }}>
                    {/* Image */}
                    <div style={{ width: '100%', height: 320, background: '#F1F5F9', borderRadius: 14, overflow: 'hidden', display: 'flex', alignItems: 'center', justifyContent: 'center', fontSize: 60, color: '#94A3B8' }}>
                        {img
                            ? <img src={img} alt={service.name} style={{ width: '100%', height: '100%', objectFit: 'cover' }} />
                            : <i className="ti ti-tool" />
                        }
                    </div>

                    {/* Main info */}
                    <div style={{ background: '#fff', border: '0.5px solid rgba(0,0,0,0.07)', borderRadius: 14, padding: '20px 22px' }}>
                        <div style={{ display: 'flex', alignItems: 'flex-start', justifyContent: 'space-between', gap: 12, flexWrap: 'wrap', marginBottom: 14 }}>
                            <div>
                                <div style={{ fontSize: 20, fontWeight: 700, color: '#0F172A', marginBottom: 6 }}>{service.name}</div>
                                <div style={{ display: 'flex', flexWrap: 'wrap', gap: 6 }}>
                                    {service.category?.name_en && (
                                        <span style={{ fontSize: 11, color: '#0D9488', background: '#F0FDFA', padding: '3px 10px', borderRadius: 20, border: '0.5px solid #99F6E4', display: 'inline-flex', alignItems: 'center', gap: 4 }}>
                                            <i className="ti ti-tag" /> {service.category.name_en}
                                        </span>
                                    )}
                                    {service.subcategory?.name_en && (
                                        <span style={{ fontSize: 11, color: '#475569', background: '#F1F5F9', padding: '3px 10px', borderRadius: 20, border: '0.5px solid rgba(0,0,0,0.08)', display: 'inline-flex', alignItems: 'center', gap: 4 }}>
                                            <i className="ti ti-point" /> {service.subcategory.name_en}
                                        </span>
                                    )}
                                    {service.city?.name_en && (
                                        <span style={{ fontSize: 11, color: '#475569', background: '#F1F5F9', padding: '3px 10px', borderRadius: 20, border: '0.5px solid rgba(0,0,0,0.08)', display: 'inline-flex', alignItems: 'center', gap: 4 }}>
                                            <i className="ti ti-map-pin" /> {service.city.name_en}
                                        </span>
                                    )}
                                </div>
                            </div>
                            <div style={{ textAlign: 'right' }}>
                                <div style={{ fontSize: 24, fontWeight: 800, color: '#0D9488' }}>{price}</div>
                                <div style={{ fontSize: 11, color: '#94A3B8' }}>{service.price_type?.toUpperCase()}</div>
                            </div>
                        </div>

                        {service.description && (
                            <p style={{ fontSize: 13, color: '#475569', lineHeight: 1.7, marginTop: 10 }}>
                                {service.description}
                            </p>
                        )}
                    </div>
                </div>

                {/* Right: provider card + CTA */}
                <div style={{ display: 'flex', flexDirection: 'column', gap: 14 }}>
                    {/* Provider */}
                    <div style={{ background: '#fff', border: '0.5px solid rgba(0,0,0,0.07)', borderRadius: 14, padding: 18 }}>
                        <div style={{ fontSize: 12, fontWeight: 600, color: '#475569', marginBottom: 14, textTransform: 'uppercase', letterSpacing: 0.5 }}>
                            مقدم الخدمة
                        </div>
                        <div style={{ display: 'flex', alignItems: 'center', gap: 12, marginBottom: 14 }}>
                            <div style={{ width: 52, height: 52, borderRadius: '50%', background: ownerColor, color: '#fff', display: 'flex', alignItems: 'center', justifyContent: 'center', fontSize: 18, fontWeight: 700, flexShrink: 0 }}>
                                {business?.image
                                    ? <img src={`/storage/${business.image}`} alt="" style={{ width: '100%', height: '100%', objectFit: 'cover', borderRadius: '50%' }} />
                                    : initial
                                }
                            </div>
                            <div>
                                <div style={{ fontSize: 14, fontWeight: 700, color: '#0F172A' }}>
                                    {business?.name ?? `${owner?.first_name ?? ''} ${owner?.last_name ?? ''}`}
                                </div>
                                {(business?.name_job || business?.activity) && (
                                    <div style={{ fontSize: 12, color: '#0D9488' }}>{business.name_job ?? business.activity}</div>
                                )}
                            </div>
                        </div>

                        {business?.description && (
                            <p style={{ fontSize: 12, color: '#475569', lineHeight: 1.6, marginBottom: 14, padding: '10px 12px', background: '#F8FAFC', borderRadius: 8 }}>
                                {business.description}
                            </p>
                        )}

                        {owner && owner.id !== authId && (
                            <button onClick={startChat} disabled={chatLoading} style={{
                                width: '100%', padding: '11px', borderRadius: 10, border: 'none',
                                background: '#0D9488', color: '#fff', fontSize: 13, fontWeight: 700,
                                cursor: chatLoading ? 'not-allowed' : 'pointer',
                                display: 'flex', alignItems: 'center', justifyContent: 'center', gap: 8,
                                opacity: chatLoading ? 0.7 : 1,
                            }}>
                                <i className="ti ti-message-circle" /> {chatLoading ? 'جارٍ الفتح...' : 'مراسلة مقدم الخدمة'}
                            </button>
                        )}
                    </div>

                    {/* Price summary */}
                    <div style={{ background: '#F0FDFA', border: '0.5px solid #99F6E4', borderRadius: 14, padding: 18 }}>
                        <div style={{ fontSize: 12, fontWeight: 600, color: '#0F766E', marginBottom: 10, textTransform: 'uppercase', letterSpacing: 0.5 }}>
                            التسعير
                        </div>
                        <div style={{ display: 'flex', alignItems: 'flex-end', justifyContent: 'space-between' }}>
                            <div>
                                <div style={{ fontSize: 11, color: '#0D9488' }}>يبدأ من</div>
                                <div style={{ fontSize: 26, fontWeight: 800, color: '#0D9488', lineHeight: 1.1 }}>{price}</div>
                                <div style={{ fontSize: 11, color: '#0D9488', marginTop: 2 }}>{service.price_type?.toUpperCase()}</div>
                            </div>
                            <i className="ti ti-coin" style={{ fontSize: 38, color: '#0D9488', opacity: 0.2 }} />
                        </div>
                    </div>
                </div>
            </div>
        </UserLayout>
    );
}
