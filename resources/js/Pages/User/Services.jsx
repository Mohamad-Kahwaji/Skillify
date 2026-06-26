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

function ServiceCard({ service }) {
    const category    = service.category?.name_ar ?? service.category?.name_en ?? '';
    const subcategory = service.subcategory?.name_ar ?? service.subcategory?.name_en ?? '';
    const cityName    = service.city?.name_ar ?? service.city?.name_en ?? '';
    const price       = Number(service.price).toLocaleString();
    const imageSrc    = service.image
        ? (service.image.startsWith('http') ? service.image : `/storage/${service.image}`)
        : null;

    return (
        <div style={{
            background: '#fff', border: '0.5px solid rgba(0,0,0,0.07)',
            borderRadius: 14, overflow: 'hidden',
            display: 'flex', flexDirection: 'column',
            transition: 'border-color 0.15s, box-shadow 0.15s',
        }}
            onMouseEnter={e => { e.currentTarget.style.borderColor = 'rgba(0,0,0,0.12)'; e.currentTarget.style.boxShadow = '0 4px 16px rgba(0,0,0,0.07)'; }}
            onMouseLeave={e => { e.currentTarget.style.borderColor = 'rgba(0,0,0,0.07)'; e.currentTarget.style.boxShadow = 'none'; }}
        >
            {/* Image */}
            <div style={{ width: '100%', height: 150, background: '#F1F5F9', display: 'flex', alignItems: 'center', justifyContent: 'center', overflow: 'hidden' }}>
                {imageSrc
                    ? <img src={imageSrc} alt={service.name} style={{ width: '100%', height: '100%', objectFit: 'cover' }} />
                    : <i className="ti ti-tool" style={{ fontSize: 36, color: '#94A3B8' }} />
                }
            </div>

            {/* Body */}
            <div style={{ padding: 16, flex: 1, display: 'flex', flexDirection: 'column', gap: 8 }}>
                <div style={{ fontSize: 14, fontWeight: 600, color: '#0F172A' }}>{service.name}</div>
                {service.description && (
                    <div style={{ fontSize: 12, color: '#475569', lineHeight: 1.55, flex: 1 }}>{service.description}</div>
                )}
                <div style={{ display: 'flex', gap: 8, flexWrap: 'wrap', alignItems: 'center' }}>
                    {category && (
                        <span style={{ display: 'inline-flex', alignItems: 'center', gap: 4, fontSize: 11, color: '#94A3B8' }}>
                            <i className="ti ti-tag" style={{ fontSize: 13 }} /> {category}
                        </span>
                    )}
                    {subcategory && (
                        <span style={{ display: 'inline-flex', alignItems: 'center', gap: 4, fontSize: 11, color: '#94A3B8' }}>
                            <i className="ti ti-point" style={{ fontSize: 13 }} /> {subcategory}
                        </span>
                    )}
                    {cityName && (
                        <span style={{ display: 'inline-flex', alignItems: 'center', gap: 4, fontSize: 11, color: '#94A3B8' }}>
                            <i className="ti ti-map-pin" style={{ fontSize: 13 }} /> {cityName}
                        </span>
                    )}
                </div>
            </div>

            {/* Provider */}
            {service.user && (
                <div style={{ display: 'flex', alignItems: 'center', gap: 7, padding: '8px 16px', borderTop: '0.5px solid rgba(0,0,0,0.05)', background: '#FAFAFA' }}>
                    <div style={{
                        width: 24, height: 24, borderRadius: '50%',
                        background: AV_COLORS[(service.user.id ?? 0) % 7],
                        color: '#fff', display: 'flex', alignItems: 'center', justifyContent: 'center',
                        fontSize: 10, fontWeight: 700, flexShrink: 0,
                    }}>
                        {(service.user.first_name ?? '?')[0].toUpperCase()}
                    </div>
                    <span style={{ fontSize: 11, color: '#475569', flex: 1 }}>
                        {service.user.first_name} {service.user.last_name}
                    </span>
                    <VerifiedBadge status={service.identity_status} />
                </div>
            )}

            {/* Footer */}
            <div style={{ padding: '12px 16px', borderTop: '0.5px solid rgba(0,0,0,0.07)', display: 'flex', alignItems: 'center', justifyContent: 'space-between' }}>
                <div>
                    <span style={{ fontSize: 15, fontWeight: 700, color: '#0D9488' }}>{price}</span>
                    <span style={{ fontSize: 11, color: '#94A3B8', marginLeft: 4 }}>
                        {service.price_type === 'usd' ? 'USD' : 'SYP'}
                    </span>
                </div>
                <div style={{ display: 'flex', alignItems: 'center', gap: 8 }}>
                    <span style={{
                        fontSize: 11, fontWeight: 600, padding: '2px 8px', borderRadius: 20,
                        background: service.is_active ? '#F0FDF4' : '#FEF2F2',
                        color: service.is_active ? '#15803D' : '#B91C1C',
                    }}>
                        {service.is_active ? 'متاح' : 'غير متاح'}
                    </span>
                    <Link href={`/user/services/${service.id}/details`} style={{
                        padding: '5px 12px', background: '#0D9488', color: '#fff',
                        borderRadius: 6, fontSize: 12, fontWeight: 500, textDecoration: 'none',
                    }}>
                        التفاصيل
                    </Link>
                </div>
            </div>
        </div>
    );
}

export default function Services({ services, cities, categories, filters }) {
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

            <div>
                <div style={{ fontSize: 20, fontWeight: 600, letterSpacing: '-0.3px', color: '#0F172A' }}>الخدمات المتاحة</div>
                <div style={{ fontSize: 13, color: '#475569', marginTop: 2 }}>تصفح وابحث عن الخدمة المناسبة</div>
            </div>

            {/* Filters */}
            <form onSubmit={submit} style={{ display: 'flex', flexDirection: 'column', gap: 10 }}>
                {/* Search */}
                <div style={{ position: 'relative' }}>
                    <i className="ti ti-search" style={{ position: 'absolute', top: '50%', transform: 'translateY(-50%)', left: 12, fontSize: 16, color: '#94A3B8', pointerEvents: 'none' }} />
                    <input
                        type="text" value={q} onChange={e => setQ(e.target.value)}
                        placeholder="ابحث عن خدمة..."
                        onKeyDown={e => e.key === 'Enter' && submit(e)}
                        style={{ width: '100%', padding: '9px 14px 9px 38px', border: '0.5px solid rgba(0,0,0,0.12)', borderRadius: 10, background: '#fff', fontSize: 13, color: '#0F172A', outline: 'none' }}
                    />
                </div>

                {/* Dropdowns */}
                <div style={{ display: 'flex', gap: 10, flexWrap: 'wrap', alignItems: 'center' }}>
                    <select value={city} onChange={e => { setCity(e.target.value); applyFilter({ city: e.target.value }); }}
                        style={{ padding: '7px 12px', borderRadius: 6, border: '0.5px solid rgba(0,0,0,0.12)', background: '#fff', fontSize: 12, color: '#475569', outline: 'none', cursor: 'pointer' }}>
                        <option value="">كل المدن</option>
                        {(cities ?? []).map(c => <option key={c.id} value={c.id}>{c.name_ar ?? c.name_en}</option>)}
                    </select>
                    <select value={priceType} onChange={e => { setPriceType(e.target.value); applyFilter({ price_type: e.target.value }); }}
                        style={{ padding: '7px 12px', borderRadius: 6, border: '0.5px solid rgba(0,0,0,0.12)', background: '#fff', fontSize: 12, color: '#475569', outline: 'none', cursor: 'pointer' }}>
                        <option value="">كل العملات</option>
                        <option value="usd">USD</option>
                        <option value="syp">SYP</option>
                    </select>
                    {hasFilters && (
                        <button type="button" onClick={clearFilters} style={{
                            padding: '5px 14px', borderRadius: 20, fontSize: 12, fontWeight: 500,
                            border: '0.5px solid #F87171', background: '#fff', color: '#F87171', cursor: 'pointer',
                            display: 'inline-flex', alignItems: 'center', gap: 4,
                        }}>
                            <i className="ti ti-x" style={{ fontSize: 11 }} /> مسح التصفية
                        </button>
                    )}
                </div>

                {/* Category chips */}
                {(categories ?? []).length > 0 && (
                    <div style={{ display: 'flex', gap: 8, flexWrap: 'wrap' }}>
                        {['', ...categories].map(cat => {
                            const catId  = cat?.id ?? '';
                            const catName = cat?.name_ar ?? cat?.name_en ?? 'الكل';
                            const isActive = catId === category;
                            return (
                                <button key={catId || '__all'} type="button"
                                    onClick={() => { setCategory(catId); applyFilter({ category: catId }); }}
                                    style={{
                                        padding: '5px 14px', borderRadius: 20, fontSize: 12, fontWeight: 500,
                                        border: `0.5px solid ${isActive ? '#0D9488' : 'rgba(0,0,0,0.12)'}`,
                                        background: isActive ? '#F0FDFA' : '#fff',
                                        color: isActive ? '#134E4A' : '#475569',
                                        cursor: 'pointer', whiteSpace: 'nowrap',
                                        display: 'inline-block',
                                    }}>
                                    {catName}
                                </button>
                            );
                        })}
                    </div>
                )}
            </form>

            <div style={{ fontSize: 12, color: '#94A3B8' }}>{total} خدمة</div>

            {items.length === 0 ? (
                <div style={{ textAlign: 'center', padding: '60px 24px', color: '#94A3B8' }}>
                    <i className="ti ti-briefcase-off" style={{ fontSize: 48, display: 'block', marginBottom: 12, opacity: 0.35 }} />
                    <p style={{ fontSize: 14 }}>لا توجد خدمات مطابقة لمعاييرك</p>
                </div>
            ) : (
                <div style={{ display: 'grid', gridTemplateColumns: 'repeat(auto-fill,minmax(270px,1fr))', gap: 14 }}>
                    {items.map(s => <ServiceCard key={s.id} service={s} />)}
                </div>
            )}

            {/* Pagination */}
            {links.length > 3 && (
                <div style={{ display: 'flex', justifyContent: 'center', gap: 6, flexWrap: 'wrap' }}>
                    {links.map((link, i) => (
                        link.url ? (
                            <button key={i} onClick={() => router.get(link.url)}
                                style={{
                                    padding: '6px 12px', borderRadius: 8, fontSize: 12,
                                    border: `1px solid ${link.active ? '#0D9488' : 'rgba(0,0,0,0.12)'}`,
                                    background: link.active ? '#0D9488' : '#fff',
                                    color: link.active ? '#fff' : '#475569', cursor: 'pointer',
                                }}
                                dangerouslySetInnerHTML={{ __html: link.label }}
                            />
                        ) : (
                            <span key={i} style={{ padding: '6px 12px', fontSize: 12, color: '#94A3B8' }}
                                dangerouslySetInnerHTML={{ __html: link.label }}
                            />
                        )
                    ))}
                </div>
            )}
        </UserLayout>
    );
}
