import { Head, Link } from '@inertiajs/react';
import { useState } from 'react';
import UserLayout from '../../Layouts/UserLayout';
import { storageUrl } from '../../utils/image';

function StatCard({ icon, iconBg, iconColor, value, label }) {
    return (
        <div className="ui-surface ui-interactive dashboard-stat-card">
            <div className="dashboard-stat-icon" style={{ background: iconBg, color: iconColor }}>
                <i className={`ti ${icon}`} />
            </div>
            <div>
                <div style={{ fontSize: 26, fontWeight: 800, lineHeight: 1, color: '#0F172A' }}>{value}</div>
                <div style={{ fontSize: 12, color: '#475569', marginTop: 3 }}>{label}</div>
            </div>
        </div>
    );
}

function ServiceCard({ service }) {
    const categoryName = service.category?.name ?? service.subcategory?.name ?? '';
    const price = Number(service.price).toLocaleString();

    return (
        <div style={{
            background: '#fff', border: '1px solid rgba(0,0,0,0.07)',
            borderRadius: 14, overflow: 'hidden',
            transition: 'box-shadow 0.15s, transform 0.15s',
        }}
            onMouseEnter={e => { e.currentTarget.style.boxShadow = '0 6px 20px rgba(0,0,0,.09)'; e.currentTarget.style.transform = 'translateY(-2px)'; }}
            onMouseLeave={e => { e.currentTarget.style.boxShadow = 'none'; e.currentTarget.style.transform = 'none'; }}
        >
            {service.image
                ? <img src={service.image} alt={service.name} style={{ width: '100%', height: 140, objectFit: 'cover' }} />
                : <div style={{ width: '100%', height: 140, background: 'linear-gradient(135deg,#F0FDFA,#F5F3FF)', display: 'flex', alignItems: 'center', justifyContent: 'center', fontSize: 36 }}>🛠️</div>
            }
            <div style={{ padding: '14px 16px' }}>
                {categoryName && (
                    <div style={{ fontSize: 11, color: '#0D9488', fontWeight: 600, marginBottom: 5, textTransform: 'uppercase', letterSpacing: 0.4 }}>
                        {categoryName}
                    </div>
                )}
                <div style={{ fontSize: 14, fontWeight: 600, lineHeight: 1.4, marginBottom: 6, color: '#0F172A' }}>{service.name}</div>
                <div style={{ fontSize: 12, color: '#475569', lineHeight: 1.5, display: '-webkit-box', WebkitLineClamp: 2, WebkitBoxOrient: 'vertical', overflow: 'hidden' }}>
                    {service.description}
                </div>
            </div>
            <div style={{ display: 'flex', alignItems: 'center', justifyContent: 'space-between', padding: '10px 16px', borderTop: '1px solid rgba(0,0,0,0.07)' }}>
                <div>
                    <span style={{ fontSize: 15, fontWeight: 700, color: '#0F172A' }}>
                        {service.price_type === 'usd' ? `$${price}` : `${price} SYP`}
                    </span>
                </div>
                <Link href={`/user/services/${service.id}/details`} style={{
                    padding: '6px 14px', background: '#0D9488', color: '#fff',
                    borderRadius: 8, fontSize: 12, fontWeight: 600, textDecoration: 'none',
                }}>
                    عرض
                </Link>
            </div>
        </div>
    );
}

function ProCard({ business }) {
    const avatarUrl = `https://ui-avatars.com/api/?name=${encodeURIComponent(business.name)}&background=0D9488&color=fff&size=128`;

    return (
        <div style={{
            background: '#fff', border: '1px solid rgba(0,0,0,0.07)',
            borderRadius: 14, padding: '20px 16px',
            display: 'flex', flexDirection: 'column', alignItems: 'center', textAlign: 'center',
            transition: 'box-shadow 0.15s, transform 0.15s',
        }}
            onMouseEnter={e => { e.currentTarget.style.boxShadow = '0 6px 20px rgba(0,0,0,.09)'; e.currentTarget.style.transform = 'translateY(-2px)'; }}
            onMouseLeave={e => { e.currentTarget.style.boxShadow = 'none'; e.currentTarget.style.transform = 'none'; }}
        >
            <img
                src={business.image
                    ? (business.image.startsWith('http') ? business.image : `/storage/${business.image}`)
                    : avatarUrl}
                alt={business.name}
                onError={e => { e.target.src = avatarUrl; }}
                style={{ width: 60, height: 60, borderRadius: 16, objectFit: 'cover', marginBottom: 12, border: '2px solid rgba(0,0,0,0.07)' }}
            />
            <div style={{ fontSize: 14, fontWeight: 700, color: '#0F172A', marginBottom: 3 }}>{business.name}</div>
            <div style={{ fontSize: 12, color: '#0D9488', fontWeight: 600, marginBottom: 6 }}>{business.name_job}</div>
            <div style={{ fontSize: 11, color: '#94A3B8' }}>
                <span style={{ display: 'inline-block', width: 8, height: 8, borderRadius: '50%', background: '#22C55E', marginRight: 4 }} />
                {business.activity}
            </div>
        </div>
    );
}

function AdCard({ ad }) {
    const [imageFailed, setImageFailed] = useState(false);
    const imageUrl = imageFailed ? null : storageUrl(ad.image);

    return (
        <div className="ui-surface ui-interactive" style={{ overflow: 'hidden' }}>
            <div style={{ position: 'relative', height: 148, overflow: 'hidden', background: 'linear-gradient(135deg,#134E4A,#0D9488)' }}>
                {imageUrl ? (
                    <img
                        src={imageUrl}
                        alt={ad.title}
                        onError={() => setImageFailed(true)}
                        style={{ width: '100%', height: '100%', objectFit: 'cover', display: 'block' }}
                    />
                ) : (
                    <div style={{ width: '100%', height: '100%', display: 'flex', alignItems: 'center', justifyContent: 'center', color: '#fff' }}>
                        <i className="ti ti-speakerphone" style={{ fontSize: 34, opacity: 0.9 }} />
                    </div>
                )}
                <div style={{ position: 'absolute', inset: 0, background: 'linear-gradient(180deg,rgba(15,23,42,0) 45%,rgba(15,23,42,.45))', pointerEvents: 'none' }} />
                {ad.company_name && (
                    <span style={{ position: 'absolute', right: 12, bottom: 10, display: 'inline-flex', alignItems: 'center', gap: 5, maxWidth: 'calc(100% - 24px)', padding: '5px 9px', borderRadius: 8, background: 'rgba(15,23,42,.62)', color: '#fff', fontSize: 11, fontWeight: 700, backdropFilter: 'blur(5px)' }}>
                        <i className="ti ti-building-store" />
                        <span style={{ overflow: 'hidden', textOverflow: 'ellipsis', whiteSpace: 'nowrap' }}>{ad.company_name}</span>
                    </span>
                )}
            </div>
            <div style={{ padding: '14px 16px 16px' }}>
                <div style={{ fontSize: 14, fontWeight: 700, color: '#0F172A', marginBottom: 6, lineHeight: 1.45 }}>{ad.title}</div>
                <div style={{ fontSize: 12, color: '#64748B', lineHeight: 1.65, display: '-webkit-box', WebkitLineClamp: 2, WebkitBoxOrient: 'vertical', overflow: 'hidden' }}>
                    {ad.description}
                </div>
            </div>
        </div>
    );
}

const QUICK_LINKS = [
    { href: '/user/services',        icon: 'ti-briefcase',  label: 'الخدمات' },
    { href: '/user/community-posts', icon: 'ti-users',      label: 'المجتمع' },
    { href: '/user/profile',         icon: 'ti-user-edit',  label: 'ملفي الشخصي' },
];

const grid3 = { display: 'grid', gridTemplateColumns: 'repeat(auto-fill,minmax(260px,1fr))', gap: 14 };

export default function Dashboard({ postsCount, conversationsCount, servicesCount, recentServices, topBusinesses, recentAds }) {
    const today = new Date().toLocaleDateString('ar-SY', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' });

    return (
        <UserLayout title="لوحة التحكم">
            <Head title="لوحة التحكم — Skillify" />

            {/* Welcome Banner */}
            <div className="dashboard-hero">
                <div className="dashboard-hero-content">
                    <h2 style={{ fontSize: 22, fontWeight: 800, marginBottom: 6 }}>مرحباً بك في Skillify</h2>
                    <p style={{ fontSize: 13, opacity: 0.84 }}>{today} - إليك ما هو جديد</p>
                </div>
                <i className="ti ti-sparkles dashboard-hero-icon" style={{ fontSize: 50, opacity: 0.44, flexShrink: 0 }} />
            </div>

            {/* Stats */}
            <div style={grid3}>
                <StatCard icon="ti-file-text"      iconBg="#F0FDFA" iconColor="#0D9488" value={postsCount}         label="منشوراتي" />
                <StatCard icon="ti-message-circle" iconBg="#EFF6FF" iconColor="#2563EB" value={conversationsCount} label="المحادثات" />
                <StatCard icon="ti-briefcase"      iconBg="#F5F3FF" iconColor="#0891B2" value={servicesCount}      label="الخدمات المتاحة" />
            </div>

            {/* Quick Actions */}
            <div className="ui-surface">
                <div style={{ padding: '16px 20px', borderBottom: '1px solid rgba(0,0,0,0.07)', fontWeight: 700, fontSize: 14 }}>
                    إجراءات سريعة
                </div>
                <div className="dashboard-quick-grid">
                    {QUICK_LINKS.map(({ href, icon, label }) => (
                        <Link key={href} href={href} className="dashboard-quick-action ui-interactive">
                            <i className={`ti ${icon}`} />
                            {label}
                        </Link>
                    ))}
                </div>
            </div>

            {/* Recent Services */}
            <div>
                <div className="dashboard-section-heading">
                    <div className="dashboard-section-title"><i className="ti ti-sparkles" style={{ color: '#0D9488' }} /> خدمات جديدة</div>
                    <Link href="/user/services" className="dashboard-section-link">عرض الكل <i className="ti ti-arrow-left" /></Link>
                </div>
                {recentServices?.length > 0 ? (
                    <div style={grid3}>
                        {recentServices.map(service => <ServiceCard key={service.id} service={service} />)}
                    </div>
                ) : (
                    <div style={{ background: '#fff', border: '1px solid rgba(0,0,0,0.07)', borderRadius: 14, padding: '40px 20px', textAlign: 'center', color: '#94A3B8', fontSize: 13 }}>
                        <i className="ti ti-briefcase" style={{ fontSize: 36, display: 'block', marginBottom: 10 }} />
                        لا توجد خدمات متاحة بعد.
                    </div>
                )}
            </div>

            {/* Top Professionals */}
            <div>
                <div className="dashboard-section-heading">
                    <div className="dashboard-section-title"><i className="ti ti-award" style={{ color: '#F59E0B' }} /> أبرز المزودين</div>
                    <Link href="/user/services" className="dashboard-section-link">عرض الخدمات <i className="ti ti-arrow-left" /></Link>
                </div>
                {topBusinesses?.length > 0 ? (
                    <div style={grid3}>
                        {topBusinesses.map(b => <ProCard key={b.id} business={b} />)}
                    </div>
                ) : (
                    <div style={{ background: '#fff', border: '1px solid rgba(0,0,0,0.07)', borderRadius: 14, padding: '40px 20px', textAlign: 'center', color: '#94A3B8', fontSize: 13 }}>
                        <i className="ti ti-users" style={{ fontSize: 36, display: 'block', marginBottom: 10 }} />
                        لا يوجد مزودون مسجلون بعد.
                    </div>
                )}
            </div>

            {/* Recent Ads */}
            <div>
                <div className="dashboard-section-heading">
                    <div className="dashboard-section-title"><i className="ti ti-speakerphone" style={{ color: '#2563EB' }} /> أحدث الإعلانات</div>
                    <Link href="/user/ads" className="dashboard-section-link">عرض الكل <i className="ti ti-arrow-left" /></Link>
                </div>
                {recentAds?.length > 0 ? (
                    <div style={{ display: 'grid', gridTemplateColumns: 'repeat(auto-fill,minmax(260px,1fr))', gap: 14 }}>
                        {recentAds.map(a => <AdCard key={a.id} ad={a} />)}
                    </div>
                ) : (
                    <div style={{ background: '#fff', border: '1px solid rgba(0,0,0,0.07)', borderRadius: 14, padding: '40px 20px', textAlign: 'center', color: '#94A3B8', fontSize: 13 }}>
                        <i className="ti ti-speakerphone" style={{ fontSize: 36, display: 'block', marginBottom: 10 }} />
                        لا توجد إعلانات نشطة حالياً.
                    </div>
                )}
            </div>

        </UserLayout>
    );
}
