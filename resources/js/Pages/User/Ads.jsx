import { Head } from '@inertiajs/react';
import UserLayout from '../../Layouts/UserLayout';

function AdCard({ ad }) {
    const img = ad.image ? (ad.image.startsWith('http') ? ad.image : `/storage/${ad.image}`) : null;

    return (
        <div style={{
            background: '#fff', border: '0.5px solid rgba(0,0,0,0.07)',
            borderRadius: 14, overflow: 'hidden',
            transition: 'box-shadow 0.15s, transform 0.15s',
        }}
            onMouseEnter={e => { e.currentTarget.style.boxShadow = '0 6px 20px rgba(0,0,0,.09)'; e.currentTarget.style.transform = 'translateY(-2px)'; }}
            onMouseLeave={e => { e.currentTarget.style.boxShadow = 'none'; e.currentTarget.style.transform = 'none'; }}
        >
            {img
                ? <img src={img} alt={ad.title} style={{ width: '100%', height: 170, objectFit: 'cover', display: 'block' }} />
                : <div style={{ width: '100%', height: 170, background: 'linear-gradient(135deg,#134E4A 0%,#0D9488 100%)', display: 'flex', alignItems: 'center', justifyContent: 'center', color: '#fff', fontSize: 44 }}>
                    <i className="ti ti-speakerphone" />
                  </div>
            }
            <div style={{ padding: '16px 18px' }}>
                {ad.company_name && (
                    <div style={{ fontSize: 11, color: '#0D9488', fontWeight: 600, marginBottom: 5, textTransform: 'uppercase', letterSpacing: 0.5, display: 'inline-flex', alignItems: 'center', gap: 4 }}>
                        <i className="ti ti-building" /> {ad.company_name}
                    </div>
                )}
                <div style={{ fontSize: 15, fontWeight: 700, color: '#0F172A', marginBottom: 8, lineHeight: 1.3 }}>{ad.title}</div>
                {ad.description && (
                    <p style={{ fontSize: 12, color: '#475569', lineHeight: 1.65, marginBottom: 10, display: '-webkit-box', WebkitLineClamp: 3, WebkitBoxOrient: 'vertical', overflow: 'hidden' }}>
                        {ad.description}
                    </p>
                )}
                {(ad.start_date || ad.end_date) && (
                    <div style={{ display: 'inline-flex', alignItems: 'center', gap: 6, fontSize: 11, color: '#94A3B8', background: '#F8FAFC', padding: '4px 10px', borderRadius: 20 }}>
                        <i className="ti ti-calendar" />
                        {ad.start_date && <span>{ad.start_date}</span>}
                        {ad.start_date && ad.end_date && <i className="ti ti-arrow-right" style={{ fontSize: 10 }} />}
                        {ad.end_date && <span>{ad.end_date}</span>}
                    </div>
                )}
            </div>
        </div>
    );
}

export default function Ads({ advertisements }) {
    const count = (advertisements ?? []).length;

    return (
        <UserLayout title="الإعلانات">
            <Head title="الإعلانات — Skillify" />

            {/* Header */}
            <div style={{
                background: 'linear-gradient(135deg,#134E4A 0%,#0D9488 60%,#0891B2 100%)',
                borderRadius: 14, padding: '22px 28px', color: '#fff',
                display: 'flex', alignItems: 'center', justifyContent: 'space-between',
                boxShadow: '0 4px 20px rgba(13,148,136,.22)',
            }}>
                <div>
                    <div style={{ fontSize: 18, fontWeight: 700, marginBottom: 3 }}>الإعلانات المميزة</div>
                    <div style={{ fontSize: 13, opacity: 0.8 }}>{count} إعلان نشط</div>
                </div>
                <i className="ti ti-speakerphone" style={{ fontSize: 48, opacity: 0.18, flexShrink: 0 }} />
            </div>

            {!count ? (
                <div style={{ textAlign: 'center', padding: '72px 24px', background: '#fff', border: '0.5px solid rgba(0,0,0,0.07)', borderRadius: 14, color: '#94A3B8' }}>
                    <i className="ti ti-speakerphone" style={{ fontSize: 52, display: 'block', marginBottom: 14, opacity: 0.25 }} />
                    <div style={{ fontSize: 15, fontWeight: 600, marginBottom: 6, color: '#64748B' }}>لا توجد إعلانات حالياً</div>
                    <div style={{ fontSize: 13 }}>تابع المنصة للاطلاع على أحدث العروض والإعلانات.</div>
                </div>
            ) : (
                <div style={{ display: 'grid', gridTemplateColumns: 'repeat(auto-fill,minmax(290px,1fr))', gap: 16 }}>
                    {(advertisements ?? []).map(ad => <AdCard key={ad.id} ad={ad} />)}
                </div>
            )}
        </UserLayout>
    );
}
