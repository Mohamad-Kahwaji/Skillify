import { Head, Link, router } from '@inertiajs/react';
import { useState } from 'react';
import UserLayout from '../../Layouts/UserLayout';

const STATUS_STYLE = {
    approved: { background: '#F0FDF4', color: '#134E4A' },
    pending:  { background: '#FEF3C7', color: '#92400E' },
    rejected: { background: '#FEF2F2', color: '#B91C1C' },
    inactive: { background: '#F3F4F6', color: '#6B7280' },
};

const STATUS_LABELS = {
    approved: 'مقبول',
    pending:  'قيد المراجعة',
    rejected: 'مرفوض',
    inactive: 'غير نشط',
};

function ServiceCard({ service, onDelete }) {
    const [confirmDel, setConfirmDel] = useState(false);
    const status = !service.is_active ? 'inactive' : (service.status ?? 'approved');
    const badge  = STATUS_STYLE[status] ?? STATUS_STYLE.pending;
    const cat    = service.category?.name_ar ?? service.category?.name_en ?? '';
    const sub    = service.subcategory?.name_ar ?? service.subcategory?.name_en ?? '';
    const city   = service.city?.name_ar ?? service.city?.name_en ?? '';
    const price  = Number(service.price).toLocaleString();
    const img    = service.image ? (service.image.startsWith('http') ? service.image : `/storage/${service.image}`) : null;

    const handleDelete = () => {
        if (confirmDel) { router.delete(`/user/my-services/${service.id}`, { preserveScroll: true }); }
        else { setConfirmDel(true); setTimeout(() => setConfirmDel(false), 3000); }
    };

    return (
        <div style={{ background: '#fff', border: '0.5px solid rgba(0,0,0,0.07)', borderRadius: 14, overflow: 'hidden', display: 'flex', flexDirection: 'column', position: 'relative', transition: 'border-color .15s, box-shadow .15s' }}
            onMouseEnter={e => { e.currentTarget.style.borderColor = '#0D9488'; e.currentTarget.style.boxShadow = '0 4px 18px rgba(13,148,136,.1)'; }}
            onMouseLeave={e => { e.currentTarget.style.borderColor = 'rgba(0,0,0,0.07)'; e.currentTarget.style.boxShadow = 'none'; }}
        >
            {/* Status ribbon */}
            <div style={{ position: 'absolute', top: 10, right: 10, zIndex: 2 }}>
                <span style={{ ...badge, display: 'inline-flex', alignItems: 'center', gap: 4, fontSize: 10, fontWeight: 600, padding: '3px 10px', borderRadius: 20 }}>
                    {STATUS_LABELS[status] ?? status}
                </span>
            </div>

            {/* Thumbnail */}
            <div style={{ width: '100%', height: 160, background: '#F1F5F9', overflow: 'hidden', display: 'flex', alignItems: 'center', justifyContent: 'center', fontSize: 38, color: '#94A3B8' }}>
                {img ? <img src={img} alt={service.name} style={{ width: '100%', height: '100%', objectFit: 'cover' }} /> : <i className="ti ti-tool" />}
            </div>

            {/* Body */}
            <div style={{ padding: '14px 16px', flex: 1, display: 'flex', flexDirection: 'column', gap: 7 }}>
                <div style={{ fontSize: 14, fontWeight: 700, color: '#0F172A' }}>{service.name}</div>
                <div style={{ display: 'flex', flexWrap: 'wrap', gap: 5 }}>
                    {cat && <span style={{ fontSize: 10, color: '#94A3B8', background: '#F1F5F9', padding: '2px 7px', borderRadius: 20, border: '0.5px solid rgba(0,0,0,0.07)', display: 'inline-flex', alignItems: 'center', gap: 3 }}><i className="ti ti-tag" style={{ fontSize: 10 }} />{cat}</span>}
                    {sub && <span style={{ fontSize: 10, color: '#94A3B8', background: '#F1F5F9', padding: '2px 7px', borderRadius: 20, border: '0.5px solid rgba(0,0,0,0.07)', display: 'inline-flex', alignItems: 'center', gap: 3 }}><i className="ti ti-point" style={{ fontSize: 10 }} />{sub}</span>}
                    {city && <span style={{ fontSize: 10, color: '#94A3B8', background: '#F1F5F9', padding: '2px 7px', borderRadius: 20, border: '0.5px solid rgba(0,0,0,0.07)', display: 'inline-flex', alignItems: 'center', gap: 3 }}><i className="ti ti-map-pin" style={{ fontSize: 10 }} />{city}</span>}
                </div>
                {service.description && (
                    <div style={{ fontSize: 12, color: '#475569', lineHeight: 1.55, display: '-webkit-box', WebkitLineClamp: 2, WebkitBoxOrient: 'vertical', overflow: 'hidden' }}>
                        {service.description}
                    </div>
                )}
            </div>

            {/* Footer */}
            <div style={{ padding: '11px 16px', borderTop: '0.5px solid rgba(0,0,0,0.07)', display: 'flex', alignItems: 'center', justifyContent: 'space-between', gap: 8 }}>
                <div>
                    <span style={{ fontSize: 15, fontWeight: 800, color: '#0D9488' }}>{price}</span>
                    <small style={{ fontSize: 10, fontWeight: 400, color: '#94A3B8', marginLeft: 3 }}>{service.price_type?.toUpperCase()}</small>
                </div>
                <div style={{ display: 'flex', gap: 6 }}>
                    <button onClick={handleDelete} style={{
                        display: 'inline-flex', alignItems: 'center', justifyContent: 'center',
                        width: 30, height: 30, borderRadius: 6,
                        border: `0.5px solid ${confirmDel ? '#F87171' : 'rgba(0,0,0,0.12)'}`,
                        background: confirmDel ? '#FEF2F2' : 'none',
                        color: confirmDel ? '#B91C1C' : '#475569', cursor: 'pointer', fontSize: 14,
                    }}>
                        <i className="ti ti-trash" />
                    </button>
                </div>
            </div>
        </div>
    );
}

export default function MyServices({ services, flash }) {
    const [filter, setFilter] = useState('all');

    const counts = {
        all:      services?.length ?? 0,
        approved: (services ?? []).filter(s => s.status === 'approved' && s.is_active).length,
        pending:  (services ?? []).filter(s => s.status === 'pending').length,
        rejected: (services ?? []).filter(s => s.status === 'rejected').length,
        inactive: (services ?? []).filter(s => !s.is_active).length,
    };

    const filtered = filter === 'all' ? services : (services ?? []).filter(s => {
        if (filter === 'inactive') return !s.is_active;
        return s.status === filter;
    });

    const TABS = [
        { key: 'all',      icon: 'ti-layout-grid',   label: 'الكل' },
        { key: 'approved', icon: 'ti-circle-check',  label: 'مقبول' },
        { key: 'pending',  icon: 'ti-clock',          label: 'قيد المراجعة' },
        { key: 'rejected', icon: 'ti-circle-x',       label: 'مرفوض' },
    ];

    return (
        <UserLayout title="خدماتي">
            <Head title="خدماتي — Skillify" />

            {flash?.success && (
                <div style={{ background: '#F0FDF4', border: '1px solid #9FE1CB', borderRadius: 10, padding: '10px 16px', color: '#134E4A', fontSize: 13 }}>
                    <i className="ti ti-circle-check" style={{ marginRight: 6 }} />{flash.success}
                </div>
            )}

            <div style={{ display: 'flex', alignItems: 'flex-start', justifyContent: 'space-between', gap: 12, flexWrap: 'wrap' }}>
                <div>
                    <div style={{ fontSize: 20, fontWeight: 600, color: '#0F172A' }}>خدماتي</div>
                    <div style={{ fontSize: 13, color: '#475569', marginTop: 2 }}>{counts.all} خدمة مدرجة</div>
                </div>
                <Link href="/user/profile?tab=services" style={{ display: 'inline-flex', alignItems: 'center', gap: 6, padding: '9px 18px', background: '#0D9488', color: '#fff', border: 'none', borderRadius: 10, fontSize: 13, fontWeight: 600, textDecoration: 'none' }}>
                    <i className="ti ti-plus" /> إضافة خدمة
                </Link>
            </div>

            {counts.pending > 0 && (
                <div style={{ display: 'flex', alignItems: 'flex-start', gap: 12, padding: '12px 16px', borderRadius: 10, background: '#FFFBEB', border: '0.5px solid #FDE68A', color: '#78350F', fontSize: 13 }}>
                    <i className="ti ti-clock" style={{ fontSize: 17, flexShrink: 0, marginTop: 1 }} />
                    <span><strong>{counts.pending} خدمة قيد المراجعة.</strong> سيقوم المشرف بمعالجتها قريباً.</span>
                </div>
            )}
            {counts.rejected > 0 && (
                <div style={{ display: 'flex', alignItems: 'flex-start', gap: 12, padding: '12px 16px', borderRadius: 10, background: '#FEF2F2', border: '0.5px solid #FECACA', color: '#B91C1C', fontSize: 13 }}>
                    <i className="ti ti-circle-x" style={{ fontSize: 17, flexShrink: 0, marginTop: 1 }} />
                    <span><strong>{counts.rejected} خدمة مرفوضة.</strong> يرجى التواصل مع الدعم.</span>
                </div>
            )}

            {counts.all > 0 && (
                <div style={{ display: 'flex', gap: 6, flexWrap: 'wrap', alignItems: 'center' }}>
                    {TABS.map(({ key, icon, label }) => (
                        counts[key] > 0 || key === 'all' ? (
                            <button key={key} onClick={() => setFilter(key)} style={{
                                display: 'inline-flex', alignItems: 'center', gap: 5,
                                padding: '6px 14px', borderRadius: 20, fontSize: 12, fontWeight: 500,
                                border: `0.5px solid ${filter === key ? '#0D9488' : 'rgba(0,0,0,0.12)'}`,
                                background: filter === key ? '#0D9488' : '#fff',
                                color: filter === key ? '#fff' : '#475569', cursor: 'pointer',
                            }}>
                                <i className={`ti ${icon}`} /> {label}
                                <span style={{ fontSize: 10, fontWeight: 700, padding: '1px 6px', borderRadius: 20, background: 'rgba(255,255,255,0.2)' }}>
                                    {counts[key]}
                                </span>
                            </button>
                        ) : null
                    ))}
                </div>
            )}

            {!filtered?.length ? (
                <div style={{ padding: '64px 24px', textAlign: 'center', background: '#fff', border: '0.5px solid rgba(0,0,0,0.07)', borderRadius: 14, color: '#94A3B8' }}>
                    <i className="ti ti-tool" style={{ fontSize: 48, display: 'block', marginBottom: 12, opacity: 0.3 }} />
                    <p style={{ fontSize: 14, marginBottom: 16 }}>لا توجد خدمات هنا بعد.</p>
                    <Link href="/user/profile?tab=services" style={{ display: 'inline-flex', alignItems: 'center', gap: 6, padding: '8px 18px', background: '#0D9488', color: '#fff', borderRadius: 8, fontSize: 13, fontWeight: 500, textDecoration: 'none' }}>
                        <i className="ti ti-plus" /> أضف خدمتك الأولى
                    </Link>
                </div>
            ) : (
                <div style={{ display: 'grid', gridTemplateColumns: 'repeat(auto-fill,minmax(280px,1fr))', gap: 16 }}>
                    {filtered.map(s => <ServiceCard key={s.id} service={s} />)}
                </div>
            )}
        </UserLayout>
    );
}
