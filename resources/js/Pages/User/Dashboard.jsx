import { Head, Link } from '@inertiajs/react';
import UserLayout from '../../Layouts/UserLayout';

function StatCard({ icon, iconBg, iconColor, value, label }) {
    return (
        <div style={{
            background: '#fff', border: '1px solid rgba(0,0,0,0.07)',
            borderRadius: 14, padding: '18px 20px',
            display: 'flex', alignItems: 'center', gap: 14,
            transition: 'box-shadow 0.15s',
        }}
            onMouseEnter={e => e.currentTarget.style.boxShadow = '0 4px 16px rgba(0,0,0,.07)'}
            onMouseLeave={e => e.currentTarget.style.boxShadow = 'none'}
        >
            <div style={{
                width: 44, height: 44, borderRadius: 12,
                display: 'flex', alignItems: 'center', justifyContent: 'center',
                fontSize: 20, flexShrink: 0,
                background: iconBg, color: iconColor,
            }}>
                <i className={`ti ${icon}`} />
            </div>
            <div>
                <div style={{ fontSize: 24, fontWeight: 700, lineHeight: 1, color: '#0F172A' }}>{value}</div>
                <div style={{ fontSize: 12, color: '#475569', marginTop: 3 }}>{label}</div>
            </div>
        </div>
    );
}

function ServiceCard({ service }) {
    const categoryName = service.category?.name_ar ?? service.category?.name_en ?? service.subcategory?.name_ar ?? service.subcategory?.name_en ?? '';
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
    return (
        <div style={{
            background: '#fff', border: '1px solid rgba(0,0,0,0.07)',
            borderRadius: 14, overflow: 'hidden',
            transition: 'box-shadow 0.15s',
        }}
            onMouseEnter={e => e.currentTarget.style.boxShadow = '0 6px 20px rgba(0,0,0,.09)'}
            onMouseLeave={e => e.currentTarget.style.boxShadow = 'none'}
        >
            {ad.image
                ? <img src={`/storage/${ad.image}`} alt={ad.title} style={{ width: '100%', height: 120, objectFit: 'cover' }} />
                : <div style={{ width: '100%', height: 120, background: 'linear-gradient(135deg,#134E4A,#0D9488)', display: 'flex', alignItems: 'center', justifyContent: 'center', color: '#fff', fontSize: 32 }}>📢</div>
            }
            <div style={{ padding: '14px 16px' }}>
                <div style={{ fontSize: 11, color: '#0D9488', fontWeight: 600, marginBottom: 5 }}>🏢 {ad.company_name}</div>
                <div style={{ fontSize: 14, fontWeight: 600, color: '#0F172A', marginBottom: 4 }}>{ad.title}</div>
                <div style={{ fontSize: 12, color: '#475569', display: '-webkit-box', WebkitLineClamp: 2, WebkitBoxOrient: 'vertical', overflow: 'hidden' }}>
                    {ad.description}
                </div>
            </div>
        </div>
    );
}

const QUICK_LINKS = [
    { href: '/user/explore',         icon: 'ti-search',     label: 'استكشاف' },
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
            <div style={{
                background: 'linear-gradient(135deg,#0D9488 0%,#0891B2 100%)',
                borderRadius: 14, padding: '24px 28px', color: '#fff',
                display: 'flex', alignItems: 'center', justifyContent: 'space-between', gap: 16,
                boxShadow: '0 4px 20px rgba(13,148,136,.25)',
            }}>
                <div>
                    <h2 style={{ fontSize: 20, fontWeight: 700, marginBottom: 4 }}>مرحباً بك في Skillify! 👋</h2>
                    <p style={{ fontSize: 13, opacity: 0.8 }}>{today} — إليك ما هو جديد</p>
                </div>
                <i className="ti ti-sparkles" style={{ fontSize: 52, opacity: 0.18, flexShrink: 0 }} />
            </div>

            {/* Stats */}
            <div style={grid3}>
                <StatCard icon="ti-file-text"      iconBg="#F0FDFA" iconColor="#0D9488" value={postsCount}         label="منشوراتي" />
                <StatCard icon="ti-message-circle" iconBg="#EFF6FF" iconColor="#2563EB" value={conversationsCount} label="المحادثات" />
                <StatCard icon="ti-briefcase"      iconBg="#F5F3FF" iconColor="#0891B2" value={servicesCount}      label="الخدمات المتاحة" />
            </div>

            {/* Quick Actions */}
            <div style={{ background: '#fff', border: '1px solid rgba(0,0,0,0.07)', borderRadius: 14 }}>
                <div style={{ padding: '16px 20px', borderBottom: '1px solid rgba(0,0,0,0.07)', fontWeight: 700, fontSize: 14 }}>
                    إجراءات سريعة
                </div>
                <div style={{ padding: '16px 20px', display: 'grid', gridTemplateColumns: 'repeat(4,1fr)', gap: 10 }}>
                    {QUICK_LINKS.map(({ href, icon, label }) => (
                        <Link key={href} href={href} style={{
                            display: 'flex', flexDirection: 'column', alignItems: 'center', gap: 8,
                            padding: '16px 12px', borderRadius: 10,
                            background: '#fff', border: '1px solid rgba(0,0,0,0.07)',
                            color: '#0F172A', fontSize: 12, fontWeight: 500,
                            textAlign: 'center', textDecoration: 'none',
                            transition: 'background 0.12s, border-color 0.12s',
                        }}
                            onMouseEnter={e => { e.currentTarget.style.background = '#F0FDFA'; e.currentTarget.style.borderColor = '#0D9488'; e.currentTarget.style.color = '#0D9488'; }}
                            onMouseLeave={e => { e.currentTarget.style.background = '#fff'; e.currentTarget.style.borderColor = 'rgba(0,0,0,0.07)'; e.currentTarget.style.color = '#0F172A'; }}
                        >
                            <i className={`ti ${icon}`} style={{ fontSize: 24, color: '#0D9488' }} />
                            {label}
                        </Link>
                    ))}
                </div>
            </div>

            {/* Recent Services */}
            <div>
                <div style={{ display: 'flex', alignItems: 'center', justifyContent: 'space-between', marginBottom: 16 }}>
                    <div style={{ fontSize: 16, fontWeight: 700, color: '#0F172A' }}>✨ خدمات جديدة</div>
                    <Link href="/user/services" style={{ fontSize: 12, color: '#0D9488', fontWeight: 600, textDecoration: 'none' }}>عرض الكل ←</Link>
                </div>
                {recentServices?.length > 0 ? (
                    <div style={grid3}>
                        {recentServices.map(s => <ServiceCard key={s.id} service={s} />)}
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
                <div style={{ display: 'flex', alignItems: 'center', justifyContent: 'space-between', marginBottom: 16 }}>
                    <div style={{ fontSize: 16, fontWeight: 700, color: '#0F172A' }}>⭐ أبرز المزودين</div>
                    <Link href="/user/explore" style={{ fontSize: 12, color: '#0D9488', fontWeight: 600, textDecoration: 'none' }}>استكشاف الكل ←</Link>
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
                <div style={{ display: 'flex', alignItems: 'center', justifyContent: 'space-between', marginBottom: 16 }}>
                    <div style={{ fontSize: 16, fontWeight: 700, color: '#0F172A' }}>📢 أحدث الإعلانات</div>
                    <Link href="/user/ads" style={{ fontSize: 12, color: '#0D9488', fontWeight: 600, textDecoration: 'none' }}>عرض الكل ←</Link>
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
