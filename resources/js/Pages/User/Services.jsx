import { Head, Link, router } from '@inertiajs/react';
import { useEffect, useMemo, useRef, useState } from 'react';
import L from 'leaflet';
import 'leaflet/dist/leaflet.css';
import markerIcon2x from 'leaflet/dist/images/marker-icon-2x.png';
import markerIcon from 'leaflet/dist/images/marker-icon.png';
import markerShadow from 'leaflet/dist/images/marker-shadow.png';
import SelectMenu from '../../Components/SelectMenu';
import UserLayout from '../../Layouts/UserLayout';
import { avatarUrl, storageUrl } from '../../utils/image';

delete L.Icon.Default.prototype._getIconUrl;
L.Icon.Default.mergeOptions({
    iconRetinaUrl: markerIcon2x,
    iconUrl: markerIcon,
    shadowUrl: markerShadow,
});

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
    const src = avatarUrl(user);
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

function getServiceCoords(service) {
    const lat = service?.business?.latitude ?? service?.user?.businesses?.latitude ?? service?.user?.latitude ?? null;
    const lng = service?.business?.longitude ?? service?.user?.businesses?.longitude ?? service?.user?.longitude ?? null;
    if (lat == null || lng == null || Number.isNaN(Number(lat)) || Number.isNaN(Number(lng))) return null;
    return [Number(lat), Number(lng)];
}

function ServiceMap({ items, userLocation }) {
    const containerRef = useRef(null);
    const mapRef = useRef(null);
    const markersRef = useRef([]);

    useEffect(() => {
        if (!containerRef.current) return;

        const center = userLocation || (items[0] ? getServiceCoords(items[0]) : [33.5138, 36.2765]);
        const map = L.map(containerRef.current, { zoomControl: true, attributionControl: false }).setView(center, userLocation ? 12 : 10);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; OpenStreetMap contributors',
        }).addTo(map);
        L.control.attribution({ prefix: false }).addTo(map);

        if (userLocation) {
            const homeMarker = L.marker([userLocation.lat, userLocation.lng], {
                icon: L.divIcon({
                    className: '',
                    html: '<div style="width:16px;height:16px;border-radius:50%;background:#10B981;border:3px solid #fff;box-shadow:0 0 0 5px rgba(16,185,129,0.18);"></div>',
                    iconSize: [18, 18],
                    iconAnchor: [9, 9],
                })
            }).addTo(map);
            homeMarker.bindPopup('موقعك الحالي');
            markersRef.current.push(homeMarker);
        }

        items.forEach((service, index) => {
            const coords = getServiceCoords(service);
            if (!coords) return;
            const marker = L.marker(coords, {
                icon: L.divIcon({
                    className: '',
                    html: '<div style="width:16px;height:16px;border-radius:50%;background:#0D9488;border:3px solid #fff;box-shadow:0 0 0 5px rgba(13,148,136,0.15);"></div>',
                    iconSize: [18, 18],
                    iconAnchor: [9, 9],
                })
            }).addTo(map);

            const distance = Number(service.distance_km ?? 0);
            const label = distance > 0 ? `${distance.toFixed(1)} كم` : 'موقع الخدمة';
            marker.bindPopup(`<div style="font-family:'Cairo',sans-serif;min-width:160px;padding:2px"><div style="font-weight:700;color:#0F172A;margin-bottom:4px">${service.name}</div><div style="font-size:12px;color:#475569">${service.user?.first_name ?? 'مزود الخدمة'} • ${label}</div></div>`);
            markersRef.current.push(marker);
            if (index === 0 && !userLocation) {
                map.setView(coords, 11);
            }
        });

        mapRef.current = map;
        setTimeout(() => map.invalidateSize(), 150);

        return () => {
            markersRef.current.forEach(marker => marker.remove());
            markersRef.current = [];
            if (mapRef.current) {
                mapRef.current.remove();
                mapRef.current = null;
            }
        };
    }, [items, userLocation]);

    return <div ref={containerRef} style={{ width: '100%', height: 260, borderRadius: 16, overflow: 'hidden', border: '1px solid rgba(15, 23, 42, 0.08)', boxShadow: '0 10px 24px rgba(15, 23, 42, 0.05)' }} />;
}

function ServiceCard({ service, authId, isNearest = false }) {
    const [chatLoading, setChatLoading] = useState(false);
    const category    = service.category?.name ?? '';
    const subcategory = service.subcategory?.name ?? '';
    const cityName    = service.city?.name ?? '';
    const price       = Number(service.price).toLocaleString();
    const imageSrc    = storageUrl(service.image);
    const isOwner = service.user?.id === authId;
    const identityStatus = service.user?.identity_verification?.status;
    const distanceLabel = useMemo(() => {
        if (service.distance_km == null || Number.isNaN(Number(service.distance_km))) return null;
        const km = Number(service.distance_km);
        if (km < 1) return `${Math.round(km * 1000)} م`;
        return `${km.toFixed(1)} كم`;
    }, [service.distance_km]);

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
            background: isNearest ? 'linear-gradient(180deg,#F0FDF4,#FFFFFF 30%)' : '#fff',
            border: isNearest ? '1.5px solid rgba(13,148,136,0.22)' : '0.5px solid rgba(0,0,0,0.07)',
            borderRadius: 18, overflow: 'hidden',
            display: 'flex', flexDirection: 'column',
            transition: 'border-color 0.15s, box-shadow 0.15s, transform 0.15s',
            position: 'relative',
            boxShadow: isNearest ? '0 18px 24px rgba(13,148,136,0.08)' : '0 8px 16px rgba(15,23,42,0.02)',
        }}
            onMouseEnter={e => { e.currentTarget.style.borderColor = isNearest ? 'rgba(13,148,136,0.35)' : 'rgba(13,148,136,0.2)'; e.currentTarget.style.boxShadow = isNearest ? '0 18px 26px rgba(13,148,136,0.12)' : '0 6px 20px rgba(13,148,136,0.08)'; e.currentTarget.style.transform = 'translateY(-2px)'; }}
            onMouseLeave={e => { e.currentTarget.style.borderColor = isNearest ? 'rgba(13,148,136,0.22)' : 'rgba(0,0,0,0.07)'; e.currentTarget.style.boxShadow = isNearest ? '0 18px 24px rgba(13,148,136,0.08)' : '0 8px 16px rgba(15,23,42,0.02)'; e.currentTarget.style.transform = 'translateY(0)'; }}
        >
            {isNearest && (
                <div style={{
                    position: 'absolute', top: 12, left: 12, zIndex: 2,
                    background: 'linear-gradient(135deg,#0D9488,#0F766E)', color: '#fff',
                    borderRadius: 999, padding: '6px 10px', fontSize: 10, fontWeight: 800,
                    boxShadow: '0 8px 18px rgba(13,148,136,0.24)',
                }}>
                    <i className="ti ti-navigation" style={{ fontSize: 10, marginLeft: 4 }} /> الأقرب لك
                </div>
            )}
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
                    {distanceLabel && (
                        <span style={{ display: 'inline-flex', alignItems: 'center', gap: 3, fontSize: 11, color: '#0D9488', fontWeight: 600 }}>
                            <i className="ti ti-navigation" style={{ fontSize: 12 }} /> {distanceLabel} بعيداً
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
    const [radius, setRadius] = useState(filters?.radius ?? '');
    const [userLocation, setUserLocation] = useState(
        filters?.lat && filters?.lng ? { lat: Number(filters.lat), lng: Number(filters.lng) } : null
    );
    const [locating, setLocating] = useState(false);
    const autoLocationRequested = useRef(false);

    useEffect(() => {
        if (autoLocationRequested.current || typeof navigator === 'undefined' || !navigator.geolocation) {
            return;
        }

        if (filters?.lat && filters?.lng) {
            autoLocationRequested.current = true;
            return;
        }

        autoLocationRequested.current = true;
        setLocating(true);

        navigator.geolocation.getCurrentPosition(
            (pos) => {
                const lat = pos.coords.latitude;
                const lng = pos.coords.longitude;
                setUserLocation({ lat, lng });
                applyFilter({ lat, lng });
                setLocating(false);
            },
            () => {
                setLocating(false);
            },
            { enableHighAccuracy: true, timeout: 15000, maximumAge: 0 }
        );
    }, []);

    const applyFilter = (params = {}) => {
        const payload = {
            q: params.q ?? q,
            city: params.city ?? city,
            price_type: params.price_type ?? priceType,
            category: params.category ?? category,
            radius: params.radius ?? radius,
            lat: params.lat ?? userLocation?.lat ?? '',
            lng: params.lng ?? userLocation?.lng ?? '',
        };

        router.get('/user/services', payload, {
            preserveState: true,
            replace: true,
            preserveScroll: true,
        });
    };

    const submit = (e) => {
        e.preventDefault();
        applyFilter({});
    };

    const clearFilters = () => {
        setQ(''); setCity(''); setPriceType(''); setCategory(''); setRadius(''); setUserLocation(null);
        router.get('/user/services');
    };

    const requestUserLocation = () => {
        if (!navigator.geolocation) {
            alert('المتصفح لا يدعم تحديد الموقع تلقائياً.');
            return;
        }

        setLocating(true);
        navigator.geolocation.getCurrentPosition(
            (pos) => {
                const lat = pos.coords.latitude;
                const lng = pos.coords.longitude;
                setUserLocation({ lat, lng });
                applyFilter({ lat, lng });
                setLocating(false);
            },
            () => {
                setLocating(false);
                alert('تعذر الوصول إلى موقعك. تأكد من تفعيل الموقع في المتصفح والمحاولة مرة أخرى.');
            },
            { enableHighAccuracy: true, timeout: 15000, maximumAge: 0 }
        );
    };

    const items = services?.data ?? [];
    const total = services?.total ?? 0;
    const links = services?.links ?? [];
    const hasFilters = q || city || priceType || category || radius || userLocation;

    return (
        <UserLayout title="الخدمات">
            <Head title="الخدمات — Skillify" />
            <style>{`
                @keyframes spin{from{transform:rotate(0deg)}to{transform:rotate(360deg)}}
                .type-select-shell {
                    background: linear-gradient(180deg, #F8FBFA 0%, #F3F7F7 100%);
                    border: 1px solid rgba(15, 23, 42, 0.06);
                    border-radius: 18px;
                    padding: 6px;
                    box-shadow: inset 0 1px 0 rgba(255,255,255,0.7), 0 10px 18px rgba(15, 23, 42, 0.03);
                }
                .category-strip {
                    scrollbar-width: thin;
                    scrollbar-color: rgba(148,163,184,0.5) transparent;
                }
                .category-strip::-webkit-scrollbar {
                    height: 6px;
                }
                .category-strip::-webkit-scrollbar-thumb {
                    background: rgba(148, 163, 184, 0.4);
                    border-radius: 99px;
                }
                .category-pill {
                    position: relative;
                    letter-spacing: -0.01em;
                    min-height: 38px;
                }
                .category-pill::before {
                    content: '';
                    position: absolute;
                    inset: 0;
                    border-radius: inherit;
                    background: linear-gradient(180deg, rgba(255,255,255,0.2), rgba(255,255,255,0));
                    pointer-events: none;
                }
                .result-badge {
                    border: 1px solid rgba(13, 148, 136, 0.12);
                    background: linear-gradient(135deg, rgba(13,148,136,0.08), rgba(15,118,110,0.03));
                    color: #0F766E;
                    box-shadow: 0 8px 18px rgba(13,148,136,0.08);
                }
                .map-panel {
                    background: linear-gradient(180deg, #ffffff 0%, #f8fafc 100%);
                    border: 1px solid rgba(15,23,42,0.08);
                    box-shadow: 0 14px 32px rgba(15,23,42,0.04);
                }
                @media (max-width: 768px) {
                    .service-page-shell { padding: 0 4px; }
                    .service-header { flex-direction: column; align-items: stretch; }
                    .service-actions { width: 100%; }
                    .service-actions > * { flex: 1; }
                    .service-tools { display: grid !important; grid-template-columns: 1fr 1fr !important; }
                    .service-tools > * { width: 100%; }
                    .service-tools select, .service-input-wrap, .service-tools button { width: 100%; }
                    .service-grid { grid-template-columns: 1fr !important; }
                    .category-strip { gap: 8px !important; }
                    .category-pill { padding: 8px 12px !important; font-size: 11px !important; }
                }
                @media (max-width: 520px) {
                    .service-tools { grid-template-columns: 1fr !important; }
                    .service-header-title { font-size: 22px !important; }
                    .service-header-subtitle { font-size: 12px !important; }
                    .service-topbar { padding: 12px !important; }
                    .type-select-shell { padding: 5px !important; }
                    .result-badge { width: 100%; justify-content: center; }
                }
            `}</style>

<div className="service-page-shell" style={{ display:'flex', flexDirection:'column', gap:18 }}>
                <div className="service-topbar" style={{
                    background: 'linear-gradient(135deg, rgba(13,148,136,0.12), rgba(15,118,110,0.04) 35%, rgba(255,255,255,0.9) 100%)',
                    border: '1px solid rgba(13,148,136,0.12)',
                    borderRadius: 22,
                    padding: 18,
                    boxShadow: '0 16px 44px rgba(15, 23, 42, 0.05)',
                }}>
                    <div className="service-header" style={{ display:'flex', alignItems:'flex-start', justifyContent:'space-between', gap:12, flexWrap:'wrap' }}>
                        <div>
                            <div className="service-header-title" style={{ fontSize: 26, fontWeight: 800, letterSpacing: '-0.4px', color: '#0F172A', lineHeight:1.1 }}>الخدمات المتاحة</div>
                            <div className="service-header-subtitle" style={{ fontSize: 13, color: '#64748B', marginTop: 6 }}>تصفح أفضل الخدمات الأقرب لك، وبحثك أصبح أسرع وأذكى.</div>
                        </div>
                        <div className="service-actions" style={{ display:'flex', gap:8, flexWrap:'wrap', alignItems:'center' }}>
                            <button type="button" onClick={requestUserLocation} disabled={locating} style={{
                                display:'inline-flex', alignItems:'center', gap:6,
                                padding:'10px 14px', borderRadius:12,
                                background: userLocation ? 'linear-gradient(135deg,#ECFDF5,#DCFCE7)' : 'linear-gradient(135deg,#F0FDFA,#ECFEFF)',
                                border: `1px solid ${userLocation ? '#A7F3D0' : '#99F6E4'}`,
                                color:'#0D9488', fontSize:12, fontWeight:800, cursor: locating ? 'not-allowed' : 'pointer',
                                boxShadow: '0 10px 18px rgba(13,148,136,0.08)',
                            }}>
                                <i className={locating ? 'ti ti-loader-2' : 'ti ti-current-location'} style={{ fontSize:13, animation: locating ? 'spin 1s linear infinite' : 'none' }} />
                                {locating ? 'يتم تحديد الموقع...' : userLocation ? 'الأقرب لموقعي' : 'استخدم موقعي'}
                            </button>
                            <Link href="/user/my-services" style={{
                                display:'inline-flex', alignItems:'center', gap:6,
                                padding:'10px 16px', borderRadius:12,
                                background:'linear-gradient(135deg,#0D9488,#0F766E)',
                                border:'1px solid rgba(13,148,136,0.15)',
                                color:'#fff', fontSize:12, fontWeight:700, textDecoration:'none',
                                boxShadow: '0 12px 24px rgba(13,148,136,0.18)',
                            }}>
                                <i className="ti ti-plus" /> خدماتي
                            </Link>
                        </div>
                    </div>
                </div>

                <form onSubmit={submit} style={{ display:'flex', flexDirection:'column', gap:12 }}>
                    <div style={{
                        background:'#fff', border:'1px solid rgba(15,23,42,0.06)', borderRadius:18,
                        padding:12, boxShadow:'0 12px 26px rgba(15,23,42,0.04)',
                    }}>
                        <div className="service-tools" style={{ display:'flex', gap:8, flexWrap:'wrap' }}>
                            <div className="service-input-wrap" style={{ position:'relative', flex:1, minWidth:180, minHeight: 44 }}>
                                <i className="ti ti-search" style={{ position:'absolute', top:'50%', transform:'translateY(-50%)', left:12, fontSize:16, color:'#94A3B8', pointerEvents:'none' }} />
                                <input
                                    type="text" value={q} onChange={e => setQ(e.target.value)}
                                    placeholder="ابحث عن خدمة أو اسم مزود..."
                                    onKeyDown={e => e.key === 'Enter' && submit(e)}
                                    style={{ width:'100%', height:44, padding:'12px 14px 12px 40px', border:'1px solid rgba(15,23,42,0.08)', borderRadius:12, background:'#F8FAFC', fontSize:13, color:'#0F172A', outline:'none', boxShadow:'inset 0 1px 2px rgba(15,23,42,0.02)' }}
                                />
                            </div>
                            <SelectMenu
                                value={city}
                                placeholder="كل المدن"
                                icon="ti-map-pin"
                                options={[{ value: '', label: 'كل المدن' }, ...(cities ?? []).map(c => ({ value: c.id, label: c.name }))]}
                                onChange={next => { setCity(next); applyFilter({ city: next }); }}
                            />
                            <SelectMenu
                                value={radius}
                                placeholder="كل المسافات"
                                icon="ti-route"
                                options={[
                                    { value: '', label: 'كل المسافات' }, { value: '5', label: 'حتى 5 كم' },
                                    { value: '10', label: 'حتى 10 كم' }, { value: '20', label: 'حتى 20 كم' },
                                    { value: '50', label: 'حتى 50 كم' }, { value: '100', label: 'حتى 100 كم' },
                                ]}
                                onChange={next => { setRadius(next); applyFilter({ radius: next }); }}
                            />
                            <SelectMenu
                                value={priceType}
                                placeholder="كل العملات"
                                icon="ti-coin"
                                options={[{ value: '', label: 'كل العملات' }, { value: 'usd', label: 'دولار أمريكي' }, { value: 'syp', label: 'ليرة سورية' }]}
                                onChange={next => { setPriceType(next); applyFilter({ price_type: next }); }}
                            />
                            {hasFilters && (
                                <button type="button" onClick={clearFilters} title="مسح التصفية" style={{
                                    padding:'12px 14px', borderRadius:12, fontSize:12,
                                    border:'1px solid rgba(239,68,68,0.2)', background:'linear-gradient(135deg,#FEF2F2,#FFF1F2)', color:'#EF4444',
                                    cursor:'pointer', display:'inline-flex', alignItems:'center', gap:4, flexShrink:0,
                                }}>
                                    <i className="ti ti-x" style={{ fontSize:13 }} /> مسح
                                </button>
                            )}
                        </div>
                    </div>

                    {(categories ?? []).length > 0 && (
                        <div className="type-select-shell">
                            <div className="category-strip" style={{ overflowX:'auto', padding: '2px 2px 4px' }}>
                                <div style={{ display:'flex', gap:8, width:'max-content', minWidth:'100%', alignItems:'center' }}>
                                    {['', ...categories].map(cat => {
                                        const catId   = cat?.id ?? '';
                                        const catName = cat?.name ?? 'الكل';
                                        const isActive = catId === category;
                                        return (
                                            <button key={catId || '__all'} type="button" className="category-pill"
                                                onClick={() => { setCategory(catId); applyFilter({ category: catId }); }}
                                                style={{
                                                    padding:'9px 16px', borderRadius:12, fontSize:12, fontWeight:700,
                                                    border: isActive ? '1px solid rgba(13,148,136,0.15)' : '1px solid transparent',
                                                    background: isActive ? 'linear-gradient(135deg,#0D9488,#0F766E)' : 'rgba(255,255,255,0.65)',
                                                    color: isActive ? '#fff' : '#475569',
                                                    cursor:'pointer', whiteSpace:'nowrap', transition:'all .18s ease',
                                                    boxShadow: isActive ? '0 12px 20px rgba(13,148,136,0.16)' : 'inset 0 1px 0 rgba(255,255,255,0.8)',
                                                    display:'inline-flex', alignItems:'center', justifyContent:'center',
                                                    minHeight:36,
                                                    backdropFilter: 'blur(4px)',
                                                }}>
                                                {catName}
                                            </button>
                                        );
                                    })}
                                </div>
                            </div>
                        </div>
                    )}
                </form>

                <div style={{ display:'flex', alignItems:'center', justifyContent:'space-between', gap:10, flexWrap:'wrap', padding:'0 4px' }}>
                    <div className="result-badge" style={{ fontSize:12, display:'inline-flex', alignItems:'center', gap:6, fontWeight:700, borderRadius:999, padding:'8px 12px' }}>
                        <i className="ti ti-layout-grid" style={{ fontSize:13, color:'#0D9488' }} />
                        {userLocation ? `الأقرب لموقعك • ${total} خدمة متاحة` : `${total} خدمة متاحة`}
                    </div>
                    <div style={{
                        display:'inline-flex', alignItems:'center', gap:6,
                        padding:'8px 12px', borderRadius:999,
                        background:'linear-gradient(135deg,#F8FAFC,#EEF2FF)', border:'1px solid rgba(148,163,184,0.22)',
                        fontSize:11, color:'#475569', fontWeight:700,
                        boxShadow:'0 8px 16px rgba(15,23,42,0.02)',
                    }}>
                        <i className="ti ti-rocket" style={{ color:'#0D9488' }} /> نتائج أفضل حسب القرب
                    </div>
            </div>

            {items.length > 0 && (
                <div className="map-panel" style={{ borderRadius: 18, padding: 12 }}>
                    <div style={{ display: 'flex', alignItems: 'center', justifyContent: 'space-between', gap: 8, marginBottom: 10, flexWrap: 'wrap' }}>
                        <div style={{ fontSize: 13, fontWeight: 700, color: '#0F172A', display: 'flex', alignItems: 'center', gap: 6 }}>
                            <i className="ti ti-map" style={{ color: '#0D9488' }} /> أقرب النتائج على الخريطة
                        </div>
                        {userLocation && (
                            <div style={{ fontSize: 11, color: '#64748B', background: '#F8FAFC', borderRadius: 999, padding: '6px 10px', border: '1px solid rgba(148,163,184,0.18)' }}>
                                {items[0]?.distance_km != null ? `الأقرب: ${Number(items[0].distance_km).toFixed(1)} كم` : 'تحديد الموقع نشط'}
                            </div>
                        )}
                    </div>
                    <ServiceMap items={items} userLocation={userLocation} />
                </div>
            )}

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
                <div className="service-grid" style={{ display: 'grid', gridTemplateColumns: 'repeat(auto-fill,minmax(270px,1fr))', gap: 16 }}>
                    {items.map((s, index) => (
                        <ServiceCard key={s.id} service={s} authId={authId} isNearest={Boolean(userLocation && index === 0)} />
                    ))}
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
            </div>
        </UserLayout>
    );
}
