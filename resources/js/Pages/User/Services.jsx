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
import ReportButton from '../../Components/ReportButton';

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
            background: isNearest ? 'linear-gradient(180deg,#F0FDF4,#FFFFFF 28%)' : 'linear-gradient(180deg,#ffffff,#f8fafc)',
            border: isNearest ? '1.5px solid rgba(16,185,129,0.28)' : '1px solid rgba(148,163,184,0.18)',
            borderRadius: 22, overflow: 'hidden',
            display: 'flex', flexDirection: 'column',
            transition: 'all 0.2s ease',
            position: 'relative',
            boxShadow: isNearest ? '0 18px 34px rgba(13,148,136,0.12)' : '0 18px 28px rgba(15,23,42,0.06)',
        }}
            onMouseEnter={e => { e.currentTarget.style.transform = 'translateY(-3px)'; e.currentTarget.style.boxShadow = isNearest ? '0 22px 34px rgba(13,148,136,0.16)' : '0 22px 30px rgba(15,23,42,0.08)'; }}
            onMouseLeave={e => { e.currentTarget.style.transform = 'translateY(0)'; e.currentTarget.style.boxShadow = isNearest ? '0 18px 34px rgba(13,148,136,0.12)' : '0 18px 28px rgba(15,23,42,0.06)'; }}
        >
            {isNearest && (
                <div style={{
                    position: 'absolute', top: 12, left: 12, zIndex: 2,
                    background: 'linear-gradient(135deg,#0D9488,#0F766E)', color: '#fff',
                    borderRadius: 999, padding: '6px 10px', fontSize: 10, fontWeight: 800,
                    boxShadow: '0 10px 18px rgba(13,148,136,0.24)',
                }}>
                    <i className="ti ti-navigation" style={{ fontSize: 10, marginLeft: 4 }} /> الأقرب لك
                </div>
            )}
            <div style={{ width: '100%', height: 192, background: 'linear-gradient(135deg,#F0FDFA,#E0F2FE)', display: 'flex', alignItems: 'center', justifyContent: 'center', overflow: 'hidden', position: 'relative' }}>
                {imageSrc
                    ? <img src={imageSrc} alt={service.name} style={{ width: '100%', height: '100%', objectFit: 'cover' }} />
                    : <i className="ti ti-tool" style={{ fontSize: 42, color: '#0D9488', opacity: 0.35 }} />
                }
                {category && (
                    <span style={{ position: 'absolute', top: 12, right: 12, fontSize: 10, fontWeight: 700, padding: '4px 10px', borderRadius: 999, background: 'rgba(255,255,255,0.92)', color: '#0D9488', backdropFilter: 'blur(4px)', border: '1px solid rgba(13,148,136,0.12)' }}>
                        {category}
                    </span>
                )}
            </div>

            <div style={{ padding: '16px 16px 12px', flex: 1, display: 'flex', flexDirection: 'column', gap: 10 }}>
                <div style={{ display:'flex', justifyContent:'space-between', alignItems:'flex-start', gap:10 }}>
                    <div style={{ fontSize: 17, fontWeight: 800, color: '#0F172A', lineHeight: 1.35, flex:1 }}>{service.name}</div>
                    <div style={{ fontSize: 12, color: '#0D9488', fontWeight: 800, background: '#ECFDF5', borderRadius: 999, padding: '5px 8px', border: '1px solid rgba(16,185,129,0.18)' }}>
                        {service.price_type === 'usd' ? 'USD' : 'SYP'}
                    </div>
                </div>
                {service.description && (
                    <div style={{ fontSize: 12.5, color: '#64748B', lineHeight: 1.7, flex: 1, display: '-webkit-box', WebkitLineClamp: 2, WebkitBoxOrient: 'vertical', overflow: 'hidden' }}>
                        {service.description}
                    </div>
                )}
                <div style={{ display: 'flex', gap: 8, flexWrap: 'wrap', alignItems: 'center' }}>
                    {cityName && (
                        <span style={{ display: 'inline-flex', alignItems: 'center', gap: 4, fontSize: 11, color: '#64748B', background: '#F8FAFC', border: '1px solid rgba(148,163,184,0.18)', borderRadius: 999, padding: '4px 8px' }}>
                            <i className="ti ti-map-pin" style={{ fontSize: 11 }} /> {cityName}
                        </span>
                    )}
                    {subcategory && (
                        <span style={{ display: 'inline-flex', alignItems: 'center', gap: 4, fontSize: 11, color: '#64748B', background: '#F8FAFC', border: '1px solid rgba(148,163,184,0.18)', borderRadius: 999, padding: '4px 8px' }}>
                            <i className="ti ti-tag" style={{ fontSize: 11 }} /> {subcategory}
                        </span>
                    )}
                    {distanceLabel && (
                        <span style={{ display: 'inline-flex', alignItems: 'center', gap: 4, fontSize: 11, color: '#0D9488', fontWeight: 700, background: '#ECFDF5', border: '1px solid rgba(16,185,129,0.12)', borderRadius: 999, padding: '4px 8px' }}>
                            <i className="ti ti-navigation" style={{ fontSize: 11 }} /> {distanceLabel}
                        </span>
                    )}
                </div>
            </div>

            {service.user && (
                <div style={{ display: 'flex', alignItems: 'center', gap: 10, padding: '0 16px 12px', marginTop: -2 }}>
                    <ProviderAvatar user={service.user} size={30} />
                    <Link href={`/user/users/${service.user.id}`} style={{ fontSize: 12, color: '#475569', flex: 1, textDecoration: 'none', fontWeight: 700 }}
                        onMouseEnter={e => e.currentTarget.style.color = '#0D9488'}
                        onMouseLeave={e => e.currentTarget.style.color = '#475569'}
                    >
                        {service.user.first_name} {service.user.last_name}
                    </Link>
                    <VerifiedBadge status={identityStatus} />
                </div>
            )}

            <div style={{ padding: '12px 16px 16px', borderTop: '1px solid rgba(148,163,184,0.12)', display: 'flex', alignItems: 'center', justifyContent: 'space-between', background: 'rgba(248,250,252,0.66)' }}>
                <div style={{ display: 'flex', flexDirection: 'column' }}>
                    <span style={{ fontSize: 16, fontWeight: 900, color: '#0F172A' }}>{price}</span>
                    <span style={{ fontSize: 10, color: '#94A3B8' }}>
                        {service.price_type === 'usd' ? 'دولار أمريكي' : 'ليرة سورية'}
                    </span>
                </div>
                <div style={{ display: 'flex', alignItems: 'center', gap: 8 }}>
                    <ReportButton type="service" id={service.id} compact />
                    {!isOwner && (
                        <button onClick={startChat} disabled={chatLoading} title="مراسلة مقدم الخدمة" style={{
                            width: 36, height: 36, borderRadius: 10, border: '1px solid rgba(13,148,136,0.2)',
                            background: chatLoading ? '#F0FDFA' : '#fff', color: '#0D9488',
                            cursor: chatLoading ? 'not-allowed' : 'pointer',
                            display: 'flex', alignItems: 'center', justifyContent: 'center', fontSize: 16, flexShrink: 0,
                            transition: 'all .15s',
                        }}
                            onMouseEnter={e => { if (!chatLoading) { e.currentTarget.style.background = '#F0FDFA'; e.currentTarget.style.borderColor = '#0D9488'; } }}
                            onMouseLeave={e => { e.currentTarget.style.background = '#fff'; e.currentTarget.style.borderColor = 'rgba(13,148,136,0.2)'; }}
                        >
                            <i className={chatLoading ? 'ti ti-loader-2' : 'ti ti-message-circle'} style={chatLoading ? { animation: 'spin 1s linear infinite' } : {}} />
                        </button>
                    )}
                    <Link href={`/user/services/${service.id}/details`} style={{
                        padding: '8px 16px', background: 'linear-gradient(135deg,#0D9488,#0F766E)', color: '#fff',
                        borderRadius: 10, fontSize: 12, fontWeight: 700, textDecoration: 'none',
                        display: 'inline-flex', alignItems: 'center', gap: 6,
                        boxShadow: '0 12px 20px rgba(13,148,136,0.22)',
                    }}>
                        التفاصيل <i className="ti ti-arrow-left" style={{ fontSize: 12 }} />
                    </Link>
                </div>
            </div>
        </div>
    );
}

export default function Services({ services, cities, categories, filters, authId }) {
    const filterState = filters && typeof filters === 'object' && !Array.isArray(filters) ? filters : {};
    const [q, setQ] = useState(filterState.q ?? '');
    const [city, setCity] = useState(filterState.city ?? '');
    const [priceType, setPriceType] = useState(filterState.price_type ?? '');
    const [category, setCategory] = useState(filterState.category ?? '');
    const [radius, setRadius] = useState(filterState.radius ?? '');
    const [sort, setSort] = useState(typeof filterState.sort === 'string' ? filterState.sort : 'nearest');
    const [verifiedOnly, setVerifiedOnly] = useState(Boolean(filterState.verified_only));
    const [filtersOpen, setFiltersOpen] = useState(false);
    const [userLocation, setUserLocation] = useState(
        filterState.lat && filterState.lng ? { lat: Number(filterState.lat), lng: Number(filterState.lng) } : null
    );
    const [locating, setLocating] = useState(false);
    const autoLocationRequested = useRef(false);

    useEffect(() => {
        if (autoLocationRequested.current || typeof navigator === 'undefined' || !navigator.geolocation) {
            return;
        }

        if (filterState.lat && filterState.lng) {
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
            sort: params.sort ?? sort,
            verified_only: params.verified_only ?? (verifiedOnly ? 1 : 0),
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
        setQ(''); setCity(''); setPriceType(''); setCategory(''); setRadius(''); setSort('nearest'); setVerifiedOnly(false); setFiltersOpen(false); setUserLocation(null);
        router.get('/user/services', { sort: 'nearest', verified_only: 0 });
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
    const hasFilters = q || city || priceType || category || radius || userLocation || sort !== 'nearest' || verifiedOnly;

    return (
        <UserLayout title="الخدمات">
            <Head title="الخدمات — Skillify" />
            <style>{`
                @keyframes spin{from{transform:rotate(0deg)}to{transform:rotate(360deg)}}
                .explore-shell {
                    display: flex;
                    flex-direction: column;
                    gap: 18px;
                    padding: 8px 4px 24px;
                }
                .explore-hero {
                    background: linear-gradient(135deg, #0F172A 0%, #1E293B 28%, #0F766E 100%);
                    border: 1px solid rgba(148,163,184,0.22);
                    border-radius: 28px;
                    padding: 20px 22px;
                    position: relative;
                    overflow: hidden;
                    box-shadow: 0 18px 36px rgba(15,23,42,0.14);
                }
                .explore-hero::before {
                    content: '';
                    position: absolute;
                    inset: 0;
                    background: radial-gradient(circle at top left, rgba(255,255,255,0.18), transparent 36%);
                    pointer-events: none;
                }
                .explore-hero-inner {
                    position: relative;
                    z-index: 1;
                    display: flex;
                    justify-content: space-between;
                    align-items: center;
                    gap: 16px;
                    flex-wrap: wrap;
                }
                .explore-title {
                    font-weight: 900;
                    letter-spacing: -0.5px;
                    color: #fff;
                    font-size: 30px;
                    line-height: 1.1;
                }
                .explore-subtitle {
                    color: rgba(226,232,240,0.9);
                    margin-top: 8px;
                    font-size: 13px;
                }
                .explore-actions {
                    display: flex;
                    flex-wrap: wrap;
                    gap: 8px;
                    align-items: center;
                }
                .action-btn {
                    display: inline-flex;
                    align-items: center;
                    gap: 6px;
                    border-radius: 12px;
                    padding: 10px 14px;
                    font-size: 12px;
                    font-weight: 800;
                    cursor: pointer;
                    border: 1px solid transparent;
                    transition: transform 0.2s ease, box-shadow 0.2s ease;
                }
                .action-btn:hover { transform: translateY(-1px); }
                .filter-shell {
                    position: sticky;
                    top: 12px;
                    z-index: 20;
                    background: rgba(255,255,255,0.9);
                    border: 1px solid rgba(148,163,184,0.22);
                    backdrop-filter: blur(14px);
                    box-shadow: 0 16px 26px rgba(15,23,42,0.08);
                    border-radius: 20px;
                    padding: 12px;
                }
                .filter-row {
                    display: flex;
                    gap: 10px;
                    flex-wrap: wrap;
                    align-items: center;
                }
                .search-box {
                    flex: 1 1 260px;
                    position: relative;
                    min-width: 180px;
                }
                .search-box input {
                    width: 100%;
                    height: 48px;
                    border-radius: 14px;
                    background: linear-gradient(180deg, #F8FAFC 0%, #F1F5F9 100%);
                    border: 1px solid rgba(148,163,184,0.18);
                    color: #0F172A;
                    font-size: 13px;
                    padding: 12px 16px 12px 42px;
                    outline: none;
                    box-shadow: inset 0 1px 2px rgba(15,23,42,0.02);
                }
                .search-box i {
                    position: absolute;
                    left: 14px;
                    top: 50%;
                    transform: translateY(-50%);
                    color: #94A3B8;
                    font-size: 16px;
                    pointer-events: none;
                }
                .advanced-btn {
                    display: inline-flex;
                    align-items: center;
                    gap: 6px;
                    padding: 11px 14px;
                    border-radius: 12px;
                    background: linear-gradient(135deg, #0D9488, #0F766E);
                    color: #fff;
                    font-size: 12px;
                    font-weight: 800;
                    border: none;
                    cursor: pointer;
                    box-shadow: 0 12px 20px rgba(13,148,136,0.18);
                }
                .ghost-btn {
                    display: inline-flex;
                    align-items: center;
                    gap: 6px;
                    border-radius: 12px;
                    padding: 11px 14px;
                    background: #fff;
                    border: 1px solid rgba(148,163,184,0.28);
                    color: #475569;
                    font-size: 12px;
                    font-weight: 800;
                    cursor: pointer;
                }
                .advanced-panel {
                    margin-top: 12px;
                    display: grid;
                    grid-template-columns: repeat(auto-fit, minmax(170px, 1fr));
                    gap: 10px;
                    border-top: 1px solid rgba(148,163,184,0.14);
                    padding-top: 12px;
                }
                .type-select-shell {
                    background: linear-gradient(180deg, #F9FBFB 0%, #F3F7F7 100%);
                    border: 1px solid rgba(15, 23, 42, 0.06);
                    border-radius: 18px;
                    padding: 6px;
                    box-shadow: inset 0 1px 0 rgba(255,255,255,0.8), 0 12px 18px rgba(15, 23, 42, 0.03);
                }
                .category-strip {
                    scrollbar-width: thin;
                    scrollbar-color: rgba(148,163,184,0.5) transparent;
                }
                .category-strip::-webkit-scrollbar { height: 6px; }
                .category-strip::-webkit-scrollbar-thumb { background: rgba(148,163,184,0.45); border-radius: 999px; }
                .category-pill {
                    position: relative;
                    letter-spacing: -0.01em;
                    min-height: 40px;
                }
                .result-badge {
                    border: 1px solid rgba(13,148,136,0.14);
                    background: linear-gradient(135deg, rgba(13,148,136,0.08), rgba(15,118,110,0.03));
                    color: #0F766E;
                    box-shadow: 0 12px 18px rgba(13,148,136,0.08);
                }
                .map-panel {
                    background: linear-gradient(180deg, #ffffff 0%, #f8fafc 100%);
                    border: 1px solid rgba(15,23,42,0.08);
                    box-shadow: 0 16px 30px rgba(15,23,42,0.04);
                }
                @media (max-width: 768px) {
                    .explore-shell { gap: 14px; padding: 4px 2px 18px; }
                    .explore-title { font-size: 24px; }
                    .explore-actions { width: 100%; }
                    .explore-actions > * { flex: 1; }
                    .filter-row { display: grid; grid-template-columns: 1fr; }
                    .advanced-btn, .ghost-btn { width: 100%; justify-content: center; }
                    .category-strip { gap: 8px !important; }
                    .service-grid { grid-template-columns: 1fr !important; }
                    .filter-shell { padding: 10px; }
                }
                @media (max-width: 520px) {
                    .explore-hero { padding: 16px; }
                    .explore-title { font-size: 22px; }
                    .explore-subtitle { font-size: 12px; }
                    .explore-actions { grid-template-columns: 1fr 1fr; display: grid; }
                    .explore-actions > * { width: 100%; }
                    .advanced-panel { grid-template-columns: 1fr; }
                    .result-badge { width: 100%; justify-content: center; }
                }
            `}</style>

            <div className="explore-shell">
                <div className="explore-hero">
                    <div className="explore-hero-inner">
                        <div>
                            <div className="explore-title">استكشف الخدمات</div>
                            <div className="explore-subtitle">ابحث عن الخبرات المناسبة لك، واستعرض الخدمات الأقرب إليك بشكل أسرع وأوضح.</div>
                        </div>
                        <div className="explore-actions">
                            <button type="button" onClick={requestUserLocation} disabled={locating} className="action-btn" style={{
                                background: userLocation ? 'linear-gradient(135deg,#ECFDF5,#DCFCE7)' : 'linear-gradient(135deg,#F0FDFA,#ECFEFF)',
                                borderColor: userLocation ? '#A7F3D0' : '#99F6E4',
                                color: '#0D9488',
                                boxShadow: '0 10px 18px rgba(13,148,136,0.08)',
                                opacity: locating ? 0.8 : 1,
                            }}>
                                <i className={locating ? 'ti ti-loader-2' : 'ti ti-current-location'} style={{ fontSize: 13, animation: locating ? 'spin 1s linear infinite' : 'none' }} />
                                {locating ? 'يتم تحديد الموقع...' : userLocation ? 'الأقرب لموقعي' : 'استخدم موقعي'}
                            </button>
                            <Link href="/user/my-services" className="action-btn" style={{
                                background: 'linear-gradient(135deg,#9B8CF7,#7C3AED)',
                                borderColor: 'rgba(167,139,250,0.5)',
                                color: '#fff',
                                boxShadow: '0 12px 24px rgba(124,58,237,0.25)',
                            }}>
                                <i className="ti ti-plus" /> خدماتي
                            </Link>
                        </div>
                    </div>
                </div>

                <div className="filter-shell">
                    <div className="filter-row">
                        <div className="search-box">
                            <i className="ti ti-search" />
                            <input
                                type="text"
                                value={q}
                                onChange={e => setQ(e.target.value)}
                                onKeyDown={e => e.key === 'Enter' && submit(e)}
                                placeholder="ابحث عن خدمة أو اسم مزود..."
                            />
                        </div>
                        <button type="button" onClick={() => setFiltersOpen(v => !v)} className="advanced-btn">
                            <i className="ti ti-adjustments" /> تصفية متقدمة
                        </button>
                        {hasFilters && (
                            <button type="button" onClick={clearFilters} className="ghost-btn">
                                <i className="ti ti-x" /> مسح
                            </button>
                        )}
                    </div>

                    {filtersOpen && (
                        <div className="advanced-panel">
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
                                    { value: '', label: 'كل المسافات' },
                                    { value: '5', label: 'حتى 5 كم' },
                                    { value: '10', label: 'حتى 10 كم' },
                                    { value: '20', label: 'حتى 20 كم' },
                                    { value: '50', label: 'حتى 50 كم' },
                                    { value: '100', label: 'حتى 100 كم' },
                                ]}
                                onChange={next => { setRadius(next); applyFilter({ radius: next }); }}
                            />
                            <SelectMenu
                                value={sort}
                                placeholder="الترتيب"
                                icon="ti-arrows-sort"
                                options={[
                                    { value: 'nearest', label: 'الأقرب أولاً' },
                                    { value: 'newest', label: 'الأحدث أولاً' },
                                    { value: 'price_low', label: 'السعر: الأقل أولاً' },
                                    { value: 'price_high', label: 'السعر: الأعلى أولاً' },
                                ]}
                                onChange={next => { setSort(next); applyFilter({ sort: next }); }}
                            />
                            <SelectMenu
                                value={priceType}
                                placeholder="كل العملات"
                                icon="ti-coin"
                                options={[{ value: '', label: 'كل العملات' }, { value: 'usd', label: 'دولار أمريكي' }, { value: 'syp', label: 'ليرة سورية' }]}
                                onChange={next => { setPriceType(next); applyFilter({ price_type: next }); }}
                            />
                        </div>
                    )}
                </div>

                {(categories ?? []).length > 0 && (
                    <div className="type-select-shell">
                        <div className="category-strip" style={{ overflowX: 'auto', padding: '2px 2px 4px' }}>
                            <div style={{ display: 'flex', gap: 8, width: 'max-content', minWidth: '100%', alignItems: 'center' }}>
                                {['', ...categories].map(cat => {
                                    const catId = cat?.id ?? '';
                                    const catName = cat?.name ?? 'الكل';
                                    const isActive = catId === category;
                                    return (
                                        <button
                                            key={catId || '__all'}
                                            type="button"
                                            className="category-pill"
                                            onClick={() => { setCategory(catId); applyFilter({ category: catId }); }}
                                            style={{
                                                padding: '9px 16px', borderRadius: 12, fontSize: 12, fontWeight: 700,
                                                border: isActive ? '1px solid rgba(13,148,136,0.15)' : '1px solid transparent',
                                                background: isActive ? 'linear-gradient(135deg,#0D9488,#0F766E)' : 'rgba(255,255,255,0.7)',
                                                color: isActive ? '#fff' : '#475569',
                                                cursor: 'pointer', whiteSpace: 'nowrap', transition: 'all .18s ease',
                                                boxShadow: isActive ? '0 12px 20px rgba(13,148,136,0.16)' : 'inset 0 1px 0 rgba(255,255,255,0.8)',
                                                display: 'inline-flex', alignItems: 'center', justifyContent: 'center', minHeight: 36,
                                            }}
                                        >
                                            {catName}
                                        </button>
                                    );
                                })}
                            </div>
                        </div>
                    </div>
                )}

                <div style={{ display: 'flex', alignItems: 'center', justifyContent: 'space-between', gap: 10, flexWrap: 'wrap', padding: '0 4px' }}>
                    <div className="result-badge" style={{ fontSize: 12, display: 'inline-flex', alignItems: 'center', gap: 6, fontWeight: 700, borderRadius: 999, padding: '8px 12px' }}>
                        <i className="ti ti-layout-grid" style={{ fontSize: 13, color: '#0D9488' }} />
                        {userLocation ? `الأقرب لموقعك • ${total} خدمة متاحة` : `${total} خدمة متاحة`}
                    </div>
                    <div style={{ display: 'flex', gap: 8, flexWrap: 'wrap', alignItems: 'center' }}>
                        <button type="button" onClick={() => { setVerifiedOnly(v => !v); applyFilter({ verified_only: !verifiedOnly ? 1 : 0 }); }} style={{
                            display: 'inline-flex', alignItems: 'center', gap: 6,
                            padding: '8px 12px', borderRadius: 999,
                            background: verifiedOnly ? 'linear-gradient(135deg,#F0FDF4,#DCFCE7)' : 'linear-gradient(135deg,#F8FAFC,#EEF2FF)',
                            border: `1px solid ${verifiedOnly ? '#A7F3D0' : 'rgba(148,163,184,0.22)'}`,
                            color: verifiedOnly ? '#15803D' : '#475569', fontSize: 11, fontWeight: 800,
                            boxShadow: verifiedOnly ? '0 8px 16px rgba(21,128,61,0.08)' : '0 8px 16px rgba(15,23,42,0.02)',
                            cursor: 'pointer',
                        }}>
                            <i className="ti ti-shield-check" /> مزودون موثوقون
                        </button>
                        <div style={{
                            display: 'inline-flex', alignItems: 'center', gap: 6,
                            padding: '8px 12px', borderRadius: 999,
                            background: 'linear-gradient(135deg,#F8FAFC,#EEF2FF)', border: '1px solid rgba(148,163,184,0.22)',
                            fontSize: 11, color: '#475569', fontWeight: 700,
                            boxShadow: '0 8px 16px rgba(15,23,42,0.02)',
                        }}>
                            <i className="ti ti-rocket" style={{ color: '#0D9488' }} /> نتائج أفضل حسب القرب
                        </div>
                    </div>
                </div>

                {items.length > 0 && (
                    <div className="map-panel" style={{ borderRadius: 22, padding: 14, background: 'linear-gradient(180deg, rgba(255,255,255,0.98) 0%, rgba(248,250,252,0.96) 100%)' }}>
                        <div style={{ display: 'flex', alignItems: 'center', justifyContent: 'space-between', gap: 8, marginBottom: 10, flexWrap: 'wrap' }}>
                            <div style={{ fontSize: 13, fontWeight: 800, color: '#0F172A', display: 'flex', alignItems: 'center', gap: 6 }}>
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
                    <div className="service-grid" style={{ display: 'grid', gridTemplateColumns: 'repeat(auto-fill,minmax(290px,1fr))', gap: 18 }}>
                        {items.map((s, index) => (
                            <ServiceCard key={s.id} service={s} authId={authId} isNearest={Boolean(userLocation && index === 0)} />
                        ))}
                    </div>
                )}

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
