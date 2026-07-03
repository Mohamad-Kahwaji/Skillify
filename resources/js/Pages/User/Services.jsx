import { Head, Link, router } from '@inertiajs/react';
import { useState } from 'react';
import UserLayout from '../../Layouts/UserLayout';

const AV_COLORS = ['#0D9488','#3B82F6','#8B5CF6','#F59E0B','#EF4444','#EC4899','#0F766E'];

function VerifiedBadge({ status }) {
    if (status === 'approved') return (
        <span title="هوية موثّقة" style={{
            display: 'inline-flex', alignItems: 'center', gap: 3,
            fontSize: 10, fontWeight: 600, padding: '2px 6px', borderRadius: 20,
            background: '#F0FDF4', color: '#15803D', border: '1px solid #BBF7D0',
        }}>
            <i className="ti ti-shield-check" style={{ fontSize: 11 }} /> موثّق
        </span>
    );
    if (status === 'pending') return (
        <span title="توثيق قيد المراجعة" style={{
            display: 'inline-flex', alignItems: 'center', gap: 3,
            fontSize: 10, fontWeight: 600, padding: '2px 6px', borderRadius: 20,
            background: '#FFFBEB', color: '#B45309', border: '1px solid #FDE68A',
        }}>
            <i className="ti ti-clock" style={{ fontSize: 11 }} /> قيد التحقق
        </span>
    );
    return null;
}

function ProviderAvatar({ user, size = 24 }) {
    const [err, setErr] = useState(false);
    const src = user?.businesses?.image
        ? `/storage/${user.businesses.image}`
        : user?.profile_photo ? `/storage/${user.profile_photo}` : null;
    const initial = (user?.first_name ?? '?')[0].toUpperCase();
    const color = AV_COLORS[(user?.id ?? 0) % 7];
    return (
        <div style={{ width: size, height: size, borderRadius: '50%', background: color, color: '#fff', display: 'flex', alignItems: 'center', justifyContent: 'center', fontSize: size * 0.42, fontWeight: 700, flexShrink: 0, overflow: 'hidden' }}>
            {src && !err
                ? <img src={src} alt="" onError={() => setErr(true)} style={{ width: '100%', height: '100%', objectFit: 'cover' }} />
                : initial
            }
        </div>
    );
}

function ServiceCard({ service, authId }) {
    const [chatLoading, setChatLoading] = useState(false);
    const category    = service.category?.name ?? '';
    const subcategory = service.subcategory?.name ?? '';
    const cityName    = service.city?.name ?? '';
    const price       = Number(service.price).toLocaleString();
    const imageSrc    = service.image
        ? (service.image.startsWith('http') ? service.image : `/storage/${service.image}`)
        : null;
    const isOwner = service.user?.id === authId;
    const identityStatus = service.user?.identity_verification?.status;

    const startChat = (e) => {
        e.preventDefault();
        if (!service.user?.id || chatLoading) return;
        setChatLoading(true);
        router.post('/user/chat/start', { business_user_id: service.user.id }, {
            onSuccess: () => setChatLoading(false),
            onError:   () => setChatLoading(false),
        });
    };

    return (
        <div style={{
            background: '#fff', border: '0.5px solid rgba(0,0,0,0.07)',
            borderRadius: 16, overflow: 'hidden',
            display: 'flex', flexDirection: 'column',
            transition: 'border-color 0.15s, box-shadow 0.15s',
        }}
            onMouseEnter={e => { e.currentTarget.style.borderColor = 'rgba(13,148,136,0.2)'; e.currentTarget.style.boxShadow = '0 6px 20px rgba(13,148,136,0.08)'; }}
            onMouseLeave={e => { e.currentTarget.style.borderColor = 'rgba(0,0,0,0.07)'; e.currentTarget.style.boxShadow = 'none'; }}
        >
            {/* Image */}
            <div style={{ width: '100%', height: 156, background: 'linear-gradient(135deg,#F0FDFA,#E6FFFA)', display: 'flex', alignItems: 'center', justifyContent: 'center', overflow: 'hidden', position: 'relative' }}>
                {imageSrc
                    ? <img src={imageSrc} alt={service.name} style={{ width: '100%', height: '100%', objectFit: 'cover' }} />
                    : <i className="ti ti-tool" style={{ fontSize: 38, color: '#0D9488', opacity: 0.35 }} />
                }
                {category && (
                    <span style={{ position: 'absolute', top: 10, right: 10, fontSize: 10, fontWeight: 600, padding: '3px 8px', borderRadius: 20, background: 'rgba(255,255,255,0.92)', color: '#0D9488', backdropFilter: 'blur(4px)' }}>
                        {category}
                    </span>
                )}
            </div>

            {/* Body */}
            <div style={{ padding: '14px 16px', flex: 1, display: 'flex', flexDirection: 'column', gap: 8 }}>
                <div style={{ fontSize: 14, fontWeight: 700, color: '#0F172A', lineHeight: 1.3 }}>{service.name}</div>
                {service.description && (
                    <div style={{ fontSize: 12, color: '#64748B', lineHeight: 1.6, flex: 1, display: '-webkit-box', WebkitLineClamp: 2, WebkitBoxOrient: 'vertical', overflow: 'hidden' }}>
                        {service.description}
                    </div>
                )}
                <div style={{ display: 'flex', gap: 10, flexWrap: 'wrap', alignItems: 'center', marginTop: 2 }}>
                    {cityName && (
                        <span style={{ display: 'inline-flex', alignItems: 'center', gap: 3, fontSize: 11, color: '#94A3B8' }}>
                            <i className="ti ti-map-pin" style={{ fontSize: 12 }} /> {cityName}
                        </span>
                    )}
                    {subcategory && (
                        <span style={{ display: 'inline-flex', alignItems: 'center', gap: 3, fontSize: 11, color: '#94A3B8' }}>
                            <i className="ti ti-tag" style={{ fontSize: 12 }} /> {subcategory}
                        </span>
                    )}
                </div>
            </div>

            {/* Provider */}
            {service.user && (
                <div style={{ display: 'flex', alignItems: 'center', gap: 8, padding: '8px 16px', borderTop: '0.5px solid rgba(0,0,0,0.05)', background: '#FAFAFA' }}>
                    <ProviderAvatar user={service.user} size={26} />
                    <Link href={`/user/users/${service.user.id}`} style={{ fontSize: 11, color: '#475569', flex: 1, textDecoration: 'none', fontWeight: 500 }}
                        onMouseEnter={e => e.currentTarget.style.color = '#0D9488'}
                        onMouseLeave={e => e.currentTarget.style.color = '#475569'}
                    >
                        {service.user.first_name} {service.user.last_name}
                    </Link>
                    <VerifiedBadge status={identityStatus} />
                </div>
            )}

            {/* Footer */}
            <div style={{ padding: '12px 16px', borderTop: '0.5px solid rgba(0,0,0,0.07)', display: 'flex', alignItems: 'center', justifyContent: 'space-between' }}>
                <div style={{ display: 'flex', flexDirection: 'column' }}>
                    <span style={{ fontSize: 16, fontWeight: 800, color: '#0D9488' }}>{price}</span>
                    <span style={{ fontSize: 10, color: '#94A3B8' }}>
                        {service.price_type === 'usd' ? 'دولار أمريكي' : 'ليرة سورية'}
                    </span>
                </div>
                <div style={{ display: 'flex', alignItems: 'center', gap: 6 }}>
                    {!isOwner && (
                        <button onClick={startChat} disabled={chatLoading} title="مراسلة مقدم الخدمة" style={{
                            width: 34, height: 34, borderRadius: 8, border: '1px solid rgba(13,148,136,0.25)',
                            background: chatLoading ? '#F0FDFA' : '#fff', color: '#0D9488',
                            cursor: chatLoading ? 'not-allowed' : 'pointer',
                            display: 'flex', alignItems: 'center', justifyContent: 'center', fontSize: 16, flexShrink: 0,
                            transition: 'all .15s',
                        }}
                            onMouseEnter={e => { if (!chatLoading) { e.currentTarget.style.background = '#F0FDFA'; e.currentTarget.style.borderColor = '#0D9488'; } }}
                            onMouseLeave={e => { e.currentTarget.style.background = '#fff'; e.currentTarget.style.borderColor = 'rgba(13,148,136,0.25)'; }}
                        >
                            <i className={chatLoading ? 'ti ti-loader-2' : 'ti ti-message-circle'} style={chatLoading ? { animation: 'spin 1s linear infinite' } : {}} />
                        </button>
                    )}
                    <Link href={`/user/services/${service.id}/details`} style={{
                        padding: '7px 16px', background: 'linear-gradient(135deg,#0D9488,#0F766E)', color: '#fff',
                        borderRadius: 8, fontSize: 12, fontWeight: 600, textDecoration: 'none',
                        display: 'inline-flex', alignItems: 'center', gap: 4,
                    }}>
                        التفاصيل <i className="ti ti-arrow-left" style={{ fontSize: 12 }} />
                    </Link>
                </div>
            </div>
        </div>
    );
}

export default function Services({ services, cities, categories, filters, authId }) {
    const [q, setQ] = useState(filters?.q ?? '');
    const [city, setCity] = useState(filters?.city ?? '');
    const [priceType, setPriceType] = useState(filters?.price_type ?? '');
    const [category, setCategory] = useState(filters?.category ?? '');

    const applyFilter = (params) => {
        router.get('/user/services', { q, city, price_type: priceType, category, ...params }, {
            preserveState: true, replace: true,
        });
    };

    const submit = (e) => {
        e.preventDefault();
        applyFilter({});
    };

    const clearFilters = () => {
        setQ(''); setCity(''); setPriceType(''); setCategory('');
        router.get('/user/services');
    };

    const items = services?.data ?? [];
    const total = services?.total ?? 0;
    const links = services?.links ?? [];
    const hasFilters = q || city || priceType || category;

    return (
        <UserLayout title="الخدمات">
            <Head title="الخدمات — Skillify" />
            <style>{`@keyframes spin{from{transform:rotate(0deg)}to{transform:rotate(360deg)}}`}</style>

            {/* Header */}
            <div style={{ display: 'flex', alignItems: 'flex-start', justifyContent: 'space-between', gap: 12, flexWrap: 'wrap' }}>
                <div>
                    <div style={{ fontSize: 20, fontWeight: 700, letterSpacing: '-0.3px', color: '#0F172A' }}>الخدمات المتاحة</div>
                    <div style={{ fontSize: 13, color: '#64748B', marginTop: 2 }}>تصفح وابحث عن الخدمة المناسبة</div>
                </div>
                <Link href="/user/my-services" style={{
                    display: 'inline-flex', alignItems: 'center', gap: 6,
                    padding: '8px 16px', borderRadius: 10,
                    background: '#F0FDFA', border: '1px solid #99F6E4',
                    color: '#0D9488', fontSize: 12, fontWeight: 600, textDecoration: 'none',
                }}>
                    <i className="ti ti-plus" /> خدماتي
                </Link>
            </div>

            {/* Search bar */}
            <form onSubmit={submit} style={{ display: 'flex', flexDirection: 'column', gap: 10 }}>
                <div style={{ display: 'flex', gap: 8, flexWrap: 'wrap' }}>
                    <div style={{ position: 'relative', flex: 1, minWidth: 180 }}>
                        <i className="ti ti-search" style={{ position: 'absolute', top: '50%', transform: 'translateY(-50%)', left: 12, fontSize: 16, color: '#94A3B8', pointerEvents: 'none' }} />
                        <input
                            type="text" value={q} onChange={e => setQ(e.target.value)}
                            placeholder="ابحث عن خدمة..."
                            onKeyDown={e => e.key === 'Enter' && submit(e)}
                            style={{ width: '100%', padding: '10px 14px 10px 40px', border: '0.5px solid rgba(0,0,0,0.12)', borderRadius: 10, background: '#fff', fontSize: 13, color: '#0F172A', outline: 'none' }}
                        />
                    </div>
                    <select value={city} onChange={e => { setCity(e.target.value); applyFilter({ city: e.target.value }); }}
                        style={{ padding: '10px 12px', borderRadius: 10, border: '0.5px solid rgba(0,0,0,0.12)', background: '#fff', fontSize: 12, color: '#475569', outline: 'none', cursor: 'pointer', minWidth: 110 }}>
                        <option value="">كل المدن</option>
                        {(cities ?? []).map(c => <option key={c.id} value={c.id}>{c.name}</option>)}
                    </select>
                    <select value={priceType} onChange={e => { setPriceType(e.target.value); applyFilter({ price_type: e.target.value }); }}
                        style={{ padding: '10px 12px', borderRadius: 10, border: '0.5px solid rgba(0,0,0,0.12)', background: '#fff', fontSize: 12, color: '#475569', outline: 'none', cursor: 'pointer', minWidth: 100 }}>
                        <option value="">كل العملات</option>
                        <option value="usd">USD</option>
                        <option value="syp">SYP</option>
                    </select>
                    {hasFilters && (
                        <button type="button" onClick={clearFilters} title="مسح التصفية" style={{
                            padding: '10px 14px', borderRadius: 10, fontSize: 12,
                            border: '0.5px solid #FCA5A5', background: '#FEF2F2', color: '#EF4444',
                            cursor: 'pointer', display: 'inline-flex', alignItems: 'center', gap: 4, flexShrink: 0,
                        }}>
                            <i className="ti ti-x" style={{ fontSize: 13 }} /> مسح
                        </button>
                    )}
                </div>

                {/* Category chips — horizontally scrollable */}
                {(categories ?? []).length > 0 && (
                    <div style={{ overflowX: 'auto', paddingBottom: 4 }}>
                        <div style={{ display: 'flex', gap: 6, width: 'max-content' }}>
                            {['', ...categories].map(cat => {
                                const catId   = cat?.id ?? '';
                                const catName = cat?.name ?? 'الكل';
                                const isActive = catId === category;
                                return (
                                    <button key={catId || '__all'} type="button"
                                        onClick={() => { setCategory(catId); applyFilter({ category: catId }); }}
                                        style={{
                                            padding: '5px 14px', borderRadius: 20, fontSize: 12, fontWeight: 500,
                                            border: `1px solid ${isActive ? '#0D9488' : 'rgba(0,0,0,0.1)'}`,
                                            background: isActive ? '#0D9488' : '#fff',
                                            color: isActive ? '#fff' : '#64748B',
                                            cursor: 'pointer', whiteSpace: 'nowrap', transition: 'all .12s',
                                        }}>
                                        {catName}
                                    </button>
                                );
                            })}
                        </div>
                    </div>
                )}
            </form>

            {/* Count */}
            <div style={{ fontSize: 12, color: '#94A3B8', display: 'flex', alignItems: 'center', gap: 6 }}>
                <i className="ti ti-layout-grid" style={{ fontSize: 13 }} />
                {total} خدمة متاحة
            </div>

            {/* Grid */}
            {items.length === 0 ? (
                <div style={{ textAlign: 'center', padding: '60px 24px', color: '#94A3B8', background: '#fff', borderRadius: 16, border: '0.5px solid rgba(0,0,0,0.07)' }}>
                    <i className="ti ti-briefcase-off" style={{ fontSize: 52, display: 'block', marginBottom: 16, opacity: 0.25 }} />
                    <div style={{ fontSize: 15, fontWeight: 600, color: '#64748B', marginBottom: 6 }}>
                        {hasFilters ? 'لا توجد خدمات مطابقة لبحثك' : 'لا توجد خدمات متاحة حالياً'}
                    </div>
                    <div style={{ fontSize: 13, marginBottom: 20 }}>
                        {hasFilters ? 'جرّب تغيير معايير البحث أو مسح التصفية.' : 'كن أول من يضيف خدمة على المنصة!'}
                    </div>
                    {hasFilters ? (
                        <button onClick={clearFilters} style={{ padding: '9px 22px', borderRadius: 10, background: '#0D9488', color: '#fff', border: 'none', fontSize: 13, fontWeight: 600, cursor: 'pointer' }}>
                            مسح التصفية
                        </button>
                    ) : (
                        <Link href="/user/my-services" style={{ display: 'inline-flex', alignItems: 'center', gap: 6, padding: '9px 22px', borderRadius: 10, background: '#0D9488', color: '#fff', fontSize: 13, fontWeight: 600, textDecoration: 'none' }}>
                            <i className="ti ti-plus" /> أضف خدمتك
                        </Link>
                    )}
                </div>
            ) : (
                <div style={{ display: 'grid', gridTemplateColumns: 'repeat(auto-fill,minmax(270px,1fr))', gap: 16 }}>
                    {items.map(s => <ServiceCard key={s.id} service={s} authId={authId} />)}
                </div>
            )}

            {/* Pagination */}
            {links.length > 3 && (
                <div style={{ display: 'flex', justifyContent: 'center', gap: 6, flexWrap: 'wrap' }}>
                    {links.map((link, i) => (
                        link.url ? (
                            <button key={i} onClick={() => router.get(link.url)}
                                style={{
                                    padding: '7px 14px', borderRadius: 8, fontSize: 12,
                                    border: `1px solid ${link.active ? '#0D9488' : 'rgba(0,0,0,0.12)'}`,
                                    background: link.active ? '#0D9488' : '#fff',
                                    color: link.active ? '#fff' : '#475569', cursor: 'pointer',
                                }}
                                dangerouslySetInnerHTML={{ __html: link.label }}
                            />
                        ) : (
                            <span key={i} style={{ padding: '7px 14px', fontSize: 12, color: '#94A3B8' }}
                                dangerouslySetInnerHTML={{ __html: link.label }}
                            />
                        )
                    ))}
                </div>
            )}
        </UserLayout>
    );
}
