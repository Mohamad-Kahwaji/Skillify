import { Head, Link, router } from '@inertiajs/react';
import { useState } from 'react';
import UserLayout from '../../Layouts/UserLayout';
import { avatarUrl, storageUrl } from '../../utils/image';
import ReportButton from '../../Components/ReportButton';

const COLORS = ['#0D9488', '#2563EB', '#7C3AED', '#D97706', '#DB2777', '#0891B2'];

function Avatar({ user, size = 62 }) {
    const [failed, setFailed] = useState(false);
    const src = avatarUrl(user);
    const initials = `${user?.first_name?.[0] ?? ''}${user?.last_name?.[0] ?? ''}`.toUpperCase() || '؟';
    const color = COLORS[(user?.id ?? 0) % COLORS.length];

    return (
        <div style={{ width: size, height: size, borderRadius: '50%', background: color, color: '#fff', display: 'flex', alignItems: 'center', justifyContent: 'center', fontSize: size * 0.34, fontWeight: 800, overflow: 'hidden', flexShrink: 0, border: '3px solid #fff', boxShadow: '0 8px 18px rgba(15,23,42,0.14)' }}>
            {src && !failed ? <img src={src} alt="" onError={() => setFailed(true)} style={{ width: '100%', height: '100%', objectFit: 'cover' }} /> : initials}
        </div>
    );
}

function BusinessCard({ business }) {
    const [serviceImageFailed, setServiceImageFailed] = useState(false);
    const owner = business.user;
    const service = owner?.services?.[0] ?? null;
    const serviceImage = service?.image && !serviceImageFailed ? storageUrl(service.image) : null;
    const name = business.name || `${owner?.first_name ?? ''} ${owner?.last_name ?? ''}`.trim() || 'حساب أعمال';
    const city = business.city || owner?.city;
    const verified = owner?.identity_verification?.status === 'approved';

    return (
        <article style={{ background: '#fff', border: '1px solid rgba(148,163,184,0.2)', borderRadius: 22, overflow: 'hidden', boxShadow: '0 16px 30px rgba(15,23,42,0.06)', display: 'flex', flexDirection: 'column', transition: 'transform .2s ease, box-shadow .2s ease' }}
            onMouseEnter={e => { e.currentTarget.style.transform = 'translateY(-4px)'; e.currentTarget.style.boxShadow = '0 22px 36px rgba(15,23,42,0.1)'; }}
            onMouseLeave={e => { e.currentTarget.style.transform = 'translateY(0)'; e.currentTarget.style.boxShadow = '0 16px 30px rgba(15,23,42,0.06)'; }}
        >
            <div style={{ height: 172, background: '#F8FAFC', position: 'relative', overflow: 'hidden' }}>
                {serviceImage && <img src={serviceImage} alt={service?.name ?? name} onError={() => setServiceImageFailed(true)} style={{ width: '100%', height: '100%', objectFit: 'cover', display: 'block' }} />}
                {serviceImage && <div style={{ position: 'absolute', inset: 0, background: 'linear-gradient(180deg, transparent 35%, rgba(15,23,42,.55))', pointerEvents: 'none' }} />}
                {verified && <span style={{ position: 'absolute', top: 12, right: 12, background: '#DCFCE7', color: '#166534', border: '1px solid #86EFAC', borderRadius: 999, padding: '5px 9px', fontSize: 10, fontWeight: 800 }}><i className="ti ti-shield-check" /> موثّق</span>}
            </div>

            <div style={{ padding: '18px 18px 18px', flex: 1, display: 'flex', flexDirection: 'column' }}>
                <div style={{ marginBottom: 14, display: 'flex', alignItems: 'center', gap: 11, minHeight: 56 }}>
                    <Avatar user={owner} size={56} />
                    <div style={{ minWidth: 0, flex: 1 }}>
                        <div style={{ fontSize: 17, fontWeight: 900, color: '#0F172A', lineHeight: 1.35, overflow: 'hidden', textOverflow: 'ellipsis', whiteSpace: 'nowrap' }}>{name}</div>
                        {business.name_job && <div style={{ color: '#0D9488', fontSize: 12, fontWeight: 800, marginTop: 5, overflow: 'hidden', textOverflow: 'ellipsis', whiteSpace: 'nowrap' }}>{business.name_job}</div>}
                    </div>
                    {verified && <span title="حساب موثّق" style={{ width: 28, height: 28, borderRadius: 9, display: 'inline-flex', alignItems: 'center', justifyContent: 'center', background: '#F0FDF4', color: '#16A34A', border: '1px solid #BBF7D0', flexShrink: 0 }}><i className="ti ti-shield-check" /></span>}
                    {business.gallery?.length > 0 && <span style={{ background: 'rgba(255,255,255,.96)', color: '#64748B', border: '1px solid #E2E8F0', borderRadius: 999, padding: '5px 9px', fontSize: 10, fontWeight: 700, boxShadow: '0 5px 12px rgba(15,23,42,.08)', flexShrink: 0 }}><i className="ti ti-photo" /> {business.gallery.length} صور</span>}
                </div>
                <p style={{ color: business.description ? '#64748B' : '#CBD5E1', fontSize: 12.5, lineHeight: 1.7, minHeight: 43, margin: '12px 0 12px', display: '-webkit-box', WebkitLineClamp: 2, WebkitBoxOrient: 'vertical', overflow: 'hidden' }}>{business.description || 'حساب أعمال جاهز للتواصل واستقبال الاستفسارات.'}</p>
                <div style={{ display: 'flex', gap: 7, flexWrap: 'wrap', alignItems: 'center', minHeight: 30 }}>
                    {city && <span style={{ display: 'inline-flex', alignItems: 'center', gap: 4, color: '#64748B', background: '#F8FAFC', border: '1px solid #E2E8F0', borderRadius: 999, padding: '5px 8px', fontSize: 10.5 }}><i className="ti ti-map-pin" /> {city}</span>}
                    {business.activity && <span style={{ display: 'inline-flex', alignItems: 'center', gap: 4, color: '#64748B', background: '#F8FAFC', border: '1px solid #E2E8F0', borderRadius: 999, padding: '5px 8px', fontSize: 10.5 }}><i className="ti ti-briefcase" /> {business.activity}</span>}
                    {business.distance_km != null && <span style={{ display: 'inline-flex', alignItems: 'center', gap: 4, color: '#0D9488', background: '#F0FDFA', border: '1px solid #99F6E4', borderRadius: 999, padding: '5px 8px', fontSize: 10.5, fontWeight: 800 }}><i className="ti ti-navigation" /> {Number(business.distance_km).toFixed(1)} كم</span>}
                </div>
            </div>

            <div style={{ padding: '13px 18px 16px', borderTop: '1px solid #F1F5F9', display: 'flex', alignItems: 'center', justifyContent: 'space-between', gap: 10, background: 'linear-gradient(180deg,#fff,#F8FAFC)' }}>
                <span style={{ display: 'inline-flex', alignItems: 'center', gap: 5, fontSize: 10.5, color: '#64748B', fontWeight: 700 }}><span style={{ width: 7, height: 7, borderRadius: '50%', background: '#22C55E', boxShadow: '0 0 0 4px #DCFCE7' }} /> حساب أعمال نشط</span>
                <div style={{ display: 'flex', gap: 7, alignItems: 'center' }}>
                    <ReportButton type="business" id={business.id} compact />
                    {owner?.id && <button type="button" title="مراسلة صاحب الحساب" onClick={() => router.post('/user/chat/start', { business_user_id: owner.id })} style={{ width: 36, height: 36, borderRadius: 10, border: '1px solid #99F6E4', background: '#F0FDFA', color: '#0D9488', cursor: 'pointer', display: 'inline-flex', alignItems: 'center', justifyContent: 'center', transition: 'all .15s' }}><i className="ti ti-message-circle" /></button>}
                    {owner?.id && <Link href={`/user/users/${owner.id}`} style={{ padding: '9px 13px', borderRadius: 10, background: 'linear-gradient(135deg,#0D9488,#0F766E)', color: '#fff', textDecoration: 'none', fontSize: 11.5, fontWeight: 800, boxShadow: '0 7px 14px rgba(13,148,136,.18)' }}>الملف <i className="ti ti-arrow-left" /></Link>}
                </div>
            </div>
        </article>
    );
}

export default function Explore({ businesses, cities, filters }) {
    const [q, setQ] = useState(filters?.q ?? '');
    const [city, setCity] = useState(filters?.city ?? '');
    const [locating, setLocating] = useState(false);
    const [nearby, setNearby] = useState(Boolean(filters?.lat && filters?.lng));
    const items = businesses?.data ?? [];
    const links = businesses?.links ?? [];
    const total = businesses?.total ?? 0;

    const submit = e => {
        e.preventDefault();
        router.get('/user/explore', { q, city, sort: '', lat: '', lng: '' }, { preserveState: true, preserveScroll: true, replace: true });
        setNearby(false);
    };

    const clear = () => {
        setQ('');
        setCity('');
        setNearby(false);
        router.get('/user/explore', {}, { preserveState: true, replace: true });
    };

    const requestNearby = () => {
        if (!navigator.geolocation) {
            alert('المتصفح لا يدعم تحديد الموقع.');
            return;
        }
        setLocating(true);
        navigator.geolocation.getCurrentPosition(
            ({ coords }) => {
                router.get('/user/explore', { q, city, lat: coords.latitude, lng: coords.longitude }, {
                    preserveState: true, preserveScroll: true, replace: true,
                    onFinish: () => setLocating(false),
                });
                setNearby(true);
            },
            () => {
                setLocating(false);
                alert('تعذر الوصول إلى موقعك. فعّل الموقع من المتصفح وحاول مرة أخرى.');
            },
            { enableHighAccuracy: true, timeout: 15000, maximumAge: 300000 }
        );
    };

    return (
        <UserLayout title="استكشاف">
            <Head title="استكشاف حسابات الأعمال — Skillify" />
            <style>{`@media(max-width:700px){.explore-business-grid{grid-template-columns:1fr!important}.explore-filter{grid-template-columns:1fr!important}.explore-hero-title{font-size:25px!important}.explore-actions{width:100%!important}.explore-actions>*{flex:1!important;justify-content:center!important}}`}</style>
            <div style={{ display: 'flex', flexDirection: 'column', gap: 18, padding: '8px 4px 24px' }}>
                <section style={{ background: 'linear-gradient(135deg,#0F172A 0%,#134E4A 58%,#0D9488 100%)', borderRadius: 26, padding: '26px 24px', color: '#fff', boxShadow: '0 18px 36px rgba(15,23,42,.16)' }}>
                    <div style={{ maxWidth: 680 }}>
                        <div className="explore-hero-title" style={{ fontSize: 32, fontWeight: 900, lineHeight: 1.2 }}>استكشف حسابات الأعمال</div>
                        <div style={{ color: 'rgba(226,232,240,.9)', fontSize: 13, lineHeight: 1.8, marginTop: 8 }}>ابحث عن أصحاب الأعمال والاختصاصات القريبة منك، حتى لو لم يضيفوا خدمة بعد.</div>
                    </div>
                    <div className="explore-actions" style={{ display: 'flex', gap: 8, flexWrap: 'wrap', marginTop: 18 }}>
                        <button type="button" onClick={requestNearby} disabled={locating} style={{ display: 'inline-flex', alignItems: 'center', gap: 7, padding: '10px 14px', borderRadius: 12, border: '1px solid rgba(153,246,228,.55)', background: nearby ? '#D1FAE5' : 'rgba(255,255,255,.12)', color: nearby ? '#065F46' : '#ECFEFF', fontFamily: 'inherit', fontSize: 12, fontWeight: 800, cursor: locating ? 'wait' : 'pointer' }}><i className={locating ? 'ti ti-loader-2' : 'ti ti-current-location'} style={locating ? { animation: 'spin 1s linear infinite' } : {}} /> {locating ? 'جاري تحديد موقعك...' : nearby ? 'الأقرب لموقعي' : 'عرض الأقرب لموقعي'}</button>
                        <Link href="/user/services" style={{ display: 'inline-flex', alignItems: 'center', gap: 7, padding: '10px 14px', borderRadius: 12, background: '#fff', color: '#0F766E', textDecoration: 'none', fontSize: 12, fontWeight: 800 }}>استكشف الخدمات <i className="ti ti-arrow-left" /></Link>
                    </div>
                </section>

                <form onSubmit={submit} style={{ position: 'sticky', top: 12, zIndex: 20, display: 'grid', gridTemplateColumns: '1fr 190px auto auto', gap: 10, alignItems: 'center', padding: 12, background: 'rgba(255,255,255,.92)', border: '1px solid rgba(148,163,184,.22)', borderRadius: 18, backdropFilter: 'blur(14px)', boxShadow: '0 16px 26px rgba(15,23,42,.08)' }} className="explore-filter">
                    <div style={{ position: 'relative' }}><i className="ti ti-search" style={{ position: 'absolute', left: 14, top: '50%', transform: 'translateY(-50%)', color: '#94A3B8' }} /><input value={q} onChange={e => setQ(e.target.value)} placeholder="ابحث بالاسم أو الاختصاص أو المدينة..." style={{ width: '100%', boxSizing: 'border-box', height: 46, borderRadius: 12, border: '1px solid #E2E8F0', background: '#F8FAFC', padding: '10px 14px 10px 40px', fontFamily: 'inherit', fontSize: 12.5, outline: 'none' }} /></div>
                    <select value={city} onChange={e => setCity(e.target.value)} style={{ height: 46, borderRadius: 12, border: '1px solid #E2E8F0', background: '#F8FAFC', padding: '0 12px', fontFamily: 'inherit', color: '#475569', fontSize: 12, cursor: 'pointer' }}><option value="">كل المدن ({(cities ?? []).length})</option>{(cities ?? []).map(item => <option key={item} value={item}>{item}</option>)}</select>
                    <button type="submit" style={{ height: 46, border: 0, borderRadius: 12, padding: '0 16px', background: 'linear-gradient(135deg,#0D9488,#0F766E)', color: '#fff', fontFamily: 'inherit', fontSize: 12, fontWeight: 800, cursor: 'pointer' }}><i className="ti ti-search" /> بحث</button>
                    {(q || city) && <button type="button" onClick={clear} style={{ height: 46, border: '1px solid #CBD5E1', borderRadius: 12, padding: '0 14px', background: '#fff', color: '#475569', fontFamily: 'inherit', fontSize: 12, fontWeight: 700, cursor: 'pointer' }}><i className="ti ti-x" /> مسح</button>}
                </form>

                <div style={{ display: 'flex', alignItems: 'center', justifyContent: 'space-between', gap: 10, flexWrap: 'wrap' }}>
                    <div style={{ display: 'flex', gap: 8, alignItems: 'center', flexWrap: 'wrap' }}>
                        <div style={{ color: '#0F766E', background: '#F0FDFA', border: '1px solid #99F6E4', borderRadius: 999, padding: '8px 13px', fontSize: 12, fontWeight: 800 }}><i className="ti ti-building-store" /> {total} حساب أعمال متاح</div>
                        {nearby && <div style={{ color: '#166534', background: '#F0FDF4', border: '1px solid #BBF7D0', borderRadius: 999, padding: '8px 13px', fontSize: 12, fontWeight: 800 }}><i className="ti ti-navigation" /> مرتبة حسب القرب</div>}
                    </div>
                    <Link href="/user/services" style={{ color: '#475569', textDecoration: 'none', fontSize: 12, fontWeight: 700 }}>استكشف الخدمات <i className="ti ti-arrow-left" /></Link>
                </div>

                {items.length ? <div className="explore-business-grid" style={{ display: 'grid', gridTemplateColumns: 'repeat(auto-fill,minmax(290px,1fr))', gap: 18 }}>{items.map(business => <BusinessCard key={business.id} business={business} />)}</div> : <div style={{ textAlign: 'center', padding: '70px 24px', background: '#fff', border: '1px solid #E2E8F0', borderRadius: 20, color: '#64748B' }}><i className="ti ti-building-store" style={{ display: 'block', fontSize: 48, opacity: .3, marginBottom: 12 }} /><div style={{ fontWeight: 800, marginBottom: 6 }}>لا توجد حسابات أعمال مطابقة</div><div style={{ fontSize: 12 }}>جرّب تغيير كلمة البحث أو المدينة.</div></div>}

                {links.length > 3 && <div style={{ display: 'flex', justifyContent: 'center', gap: 6, flexWrap: 'wrap' }}>{links.map((link, index) => link.url ? <button key={index} type="button" onClick={() => router.get(link.url)} style={{ padding: '7px 13px', borderRadius: 8, border: `1px solid ${link.active ? '#0D9488' : '#E2E8F0'}`, background: link.active ? '#0D9488' : '#fff', color: link.active ? '#fff' : '#475569', cursor: 'pointer' }} dangerouslySetInnerHTML={{ __html: link.label }} /> : <span key={index} style={{ padding: '7px 13px', color: '#94A3B8' }} dangerouslySetInnerHTML={{ __html: link.label }} />)}</div>}
            </div>
        </UserLayout>
    );
}
