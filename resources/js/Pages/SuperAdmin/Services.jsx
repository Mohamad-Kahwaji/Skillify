import { Head, router } from '@inertiajs/react';
import { useState } from 'react';
import SuperAdminLayout from '../../Layouts/SuperAdminLayout';

const AV = ['#6D28D9','#0D9488','#2563EB','#D97706','#DC2626','#0891B2','#7C3AED','#059669'];

const STATUS_CFG = {
    approved: { bg: '#ECFDF5', color: '#065F46', label: 'مقبول',         icon: 'ti-circle-check', border: '#6EE7B7', dot: '#10B981' },
    pending:  { bg: '#FEF3C7', color: '#92400E', label: 'قيد المراجعة', icon: 'ti-clock',         border: '#FDE68A', dot: '#F59E0B' },
    rejected: { bg: '#FEF2F2', color: '#991B1B', label: 'مرفوض',         icon: 'ti-circle-x',     border: '#FCA5A5', dot: '#EF4444' },
};

const TABS = [
    { key: 'all',      label: 'الكل',          color: '#3730A3', bg: '#EEF2FF', border: '#C7D2FE', icon: 'ti-apps' },
    { key: 'pending',  label: 'قيد المراجعة', color: '#92400E', bg: '#FEF3C7', border: '#FDE68A', icon: 'ti-clock' },
    { key: 'approved', label: 'مقبول',          color: '#065F46', bg: '#ECFDF5', border: '#6EE7B7', icon: 'ti-circle-check' },
    { key: 'rejected', label: 'مرفوض',         color: '#991B1B', bg: '#FEF2F2', border: '#FCA5A5', icon: 'ti-circle-x' },
];

function ServiceImage({ service, size = 40, colorIdx = 0 }) {
    const [err, setErr] = useState(false);
    const av1 = AV[colorIdx % AV.length];
    const av2 = AV[(colorIdx + 2) % AV.length];
    const img = (!err && service.image)
        ? (service.image.startsWith('http') ? service.image : `/storage/${service.image}`)
        : null;

    if (img) {
        return (
            <img src={img} alt={service.name} onError={() => setErr(true)}
                style={{ width: size, height: size, borderRadius: 10, objectFit: 'cover', flexShrink: 0, border: '1px solid rgba(0,0,0,0.07)' }} />
        );
    }
    return (
        <div style={{
            width: size, height: size, borderRadius: 10, flexShrink: 0,
            background: `linear-gradient(135deg,${av1},${av2})`,
            display: 'flex', alignItems: 'center', justifyContent: 'center',
            fontSize: size * 0.38, color: '#fff',
        }}>
            <i className="ti ti-tool" />
        </div>
    );
}

export default function Services({ services }) {
    const [tab,    setTab]    = useState('all');
    const [search, setSearch] = useState('');

    const all = services ?? [];

    const filtered = all
        .filter(s => tab === 'all' || s.status === tab)
        .filter(s =>
            `${s.name ?? ''} ${s.user?.first_name ?? ''} ${s.user?.last_name ?? ''} ${s.category?.name ?? ''} ${s.city?.name ?? ''}`
                .toLowerCase().includes(search.toLowerCase())
        );

    const counts = TABS.reduce((acc, t) => {
        acc[t.key] = t.key === 'all' ? all.length : all.filter(s => s.status === t.key).length;
        return acc;
    }, {});

    const patch   = (id, action) => router.patch(`/super-admin/services/${id}/${action}`, {}, { preserveScroll: true });
    const destroy = (id) => { if (!confirm('حذف هذه الخدمة نهائياً؟')) return; router.delete(`/super-admin/services/${id}`, { preserveScroll: true }); };

    return (
        <SuperAdminLayout title="الخدمات">
            <Head title="الخدمات — Skillify" />

            {/* Header */}
            <div style={{ display: 'flex', alignItems: 'flex-start', justifyContent: 'space-between', flexWrap: 'wrap', gap: 12 }}>
                <div>
                    <h1 style={{ fontSize: 22, fontWeight: 800, color: '#1E1B4B', margin: 0, letterSpacing: -0.5 }}>جميع الخدمات</h1>
                    <p style={{ fontSize: 12, color: '#94A3B8', marginTop: 4 }}>{all.length} خدمة مدرجة</p>
                </div>
                <div style={{ position: 'relative' }}>
                    <i className="ti ti-search" style={{ position: 'absolute', top: '50%', right: 12, transform: 'translateY(-50%)', color: '#94A3B8', fontSize: 14, pointerEvents: 'none' }} />
                    <input value={search} onChange={e => setSearch(e.target.value)}
                        placeholder="بحث بالاسم، المزود، الفئة، المدينة..."
                        style={{ width: 280, padding: '9px 38px 9px 14px', border: '1px solid rgba(0,0,0,0.09)', borderRadius: 10, fontSize: 13, outline: 'none', boxSizing: 'border-box', fontFamily: "'Cairo','Inter',sans-serif", background: '#FAFAFA', direction: 'rtl', transition: 'border-color .15s' }}
                        onFocus={e => e.target.style.borderColor = '#7C3AED'}
                        onBlur={e => e.target.style.borderColor = 'rgba(0,0,0,0.09)'} />
                </div>
            </div>

            {/* Tabs */}
            <div style={{ display: 'flex', gap: 8, flexWrap: 'wrap', alignItems: 'center' }}>
                {TABS.map(t => (
                    <button key={t.key} onClick={() => setTab(t.key)} style={{
                        display: 'inline-flex', alignItems: 'center', gap: 6, padding: '7px 16px', borderRadius: 24,
                        border: `1.5px solid ${tab === t.key ? t.color : 'rgba(0,0,0,0.08)'}`,
                        background: tab === t.key ? t.bg : '#fff',
                        color: tab === t.key ? t.color : '#64748B',
                        fontSize: 12.5, fontWeight: tab === t.key ? 700 : 500, cursor: 'pointer',
                        fontFamily: "'Cairo','Inter',sans-serif", transition: 'all 0.13s',
                        boxShadow: tab === t.key ? '0 2px 8px rgba(0,0,0,0.08)' : 'none',
                    }}>
                        <i className={`ti ${t.icon}`} style={{ fontSize: 13 }} />
                        {t.label}
                        <span style={{ background: tab === t.key ? 'rgba(0,0,0,0.1)' : '#F1F5F9', color: tab === t.key ? t.color : '#64748B', borderRadius: 20, padding: '1px 8px', fontSize: 11, fontWeight: 700 }}>
                            {counts[t.key]}
                        </span>
                    </button>
                ))}
                {search && (
                    <span style={{ fontSize: 12, color: '#64748B', display: 'flex', alignItems: 'center', gap: 4 }}>
                        <i className="ti ti-filter" /> {filtered.length} نتيجة
                        <button onClick={() => setSearch('')} style={{ background: 'none', border: 'none', cursor: 'pointer', color: '#94A3B8', fontSize: 11, padding: '0 4px' }}>
                            <i className="ti ti-x" />
                        </button>
                    </span>
                )}
            </div>

            {/* Table */}
            <div style={{ background: '#fff', border: '1px solid rgba(0,0,0,0.07)', borderRadius: 16, overflow: 'hidden', boxShadow: '0 2px 12px rgba(0,0,0,0.05)' }}>
                <div style={{ overflowX: 'auto' }}>
                <table style={{ width: '100%', borderCollapse: 'collapse', fontSize: 13 }}>
                    <thead>
                        <tr style={{ background: 'linear-gradient(to left,#F8FAFC,#EEF2FF)', borderBottom: '1px solid rgba(0,0,0,0.06)' }}>
                            {[
                                { label: 'الخدمة',    w: '26%' },
                                { label: 'التصنيف',   w: '15%' },
                                { label: 'المزود',    w: '15%' },
                                { label: 'السعر',     w: '11%' },
                                { label: 'الحالة',   w: '12%' },
                                { label: 'إجراءات',  w: '21%' },
                            ].map(h => (
                                <th key={h.label} style={{ padding: '13px 16px', textAlign: 'right', fontWeight: 700, color: '#4C1D95', whiteSpace: 'nowrap', width: h.w, fontSize: 11.5 }}>
                                    {h.label}
                                </th>
                            ))}
                        </tr>
                    </thead>
                    <tbody>
                        {!filtered.length ? (
                            <tr>
                                <td colSpan={6} style={{ padding: '72px 24px', textAlign: 'center' }}>
                                    <div style={{ width: 64, height: 64, borderRadius: '50%', background: '#F5F3FF', display: 'flex', alignItems: 'center', justifyContent: 'center', margin: '0 auto 16px' }}>
                                        <i className="ti ti-tool" style={{ fontSize: 28, color: '#C4B5FD' }} />
                                    </div>
                                    <div style={{ fontSize: 14, fontWeight: 700, color: '#64748B', marginBottom: 6 }}>لا توجد خدمات</div>
                                    <p style={{ fontSize: 13, color: '#94A3B8', margin: 0 }}>جرّب تغيير الفلتر أو كلمة البحث.</p>
                                </td>
                            </tr>
                        ) : filtered.map((s, i) => {
                            const st  = STATUS_CFG[s.status] ?? STATUS_CFG.pending;
                            const uv1 = AV[(i + 1) % AV.length];
                            const uv2 = AV[(i + 3) % AV.length];

                            return (
                                <tr key={s.id}
                                    style={{ borderBottom: '1px solid rgba(0,0,0,0.04)', transition: 'background 0.12s' }}
                                    onMouseEnter={e => e.currentTarget.style.background = '#FAFAFF'}
                                    onMouseLeave={e => e.currentTarget.style.background = 'transparent'}
                                >
                                    {/* Service */}
                                    <td style={{ padding: '12px 16px' }}>
                                        <div style={{ display: 'flex', alignItems: 'center', gap: 11 }}>
                                            <ServiceImage service={s} size={40} colorIdx={i} />
                                            <div style={{ minWidth: 0 }}>
                                                <div style={{ fontWeight: 700, color: '#0F172A', overflow: 'hidden', textOverflow: 'ellipsis', whiteSpace: 'nowrap', maxWidth: 190, fontSize: 13 }}>
                                                    {s.name}
                                                </div>
                                                {s.city?.name && (
                                                    <div style={{ fontSize: 11, color: '#94A3B8', marginTop: 2, display: 'flex', alignItems: 'center', gap: 3 }}>
                                                        <i className="ti ti-map-pin" style={{ fontSize: 10 }} />{s.city.name}
                                                    </div>
                                                )}
                                            </div>
                                        </div>
                                    </td>

                                    {/* Category */}
                                    <td style={{ padding: '12px 16px' }}>
                                        <div style={{ fontSize: 12.5, fontWeight: 600, color: '#334155' }}>{s.category?.name ?? '—'}</div>
                                        {s.subcategory?.name && <div style={{ fontSize: 11, color: '#94A3B8', marginTop: 2 }}>{s.subcategory.name}</div>}
                                    </td>

                                    {/* Provider */}
                                    <td style={{ padding: '12px 16px' }}>
                                        <div style={{ display: 'flex', alignItems: 'center', gap: 8 }}>
                                            <div style={{
                                                width: 30, height: 30, borderRadius: '50%', flexShrink: 0,
                                                background: `linear-gradient(135deg,${uv1},${uv2})`,
                                                display: 'flex', alignItems: 'center', justifyContent: 'center',
                                                fontSize: 11, fontWeight: 700, color: '#fff',
                                            }}>
                                                {(s.user?.first_name?.[0] ?? 'م').toUpperCase()}
                                            </div>
                                            <div style={{ fontSize: 12.5, fontWeight: 600, color: '#0F172A', whiteSpace: 'nowrap', overflow: 'hidden', textOverflow: 'ellipsis', maxWidth: 110 }}>
                                                {s.user?.first_name} {s.user?.last_name}
                                            </div>
                                        </div>
                                    </td>

                                    {/* Price */}
                                    <td style={{ padding: '12px 16px', whiteSpace: 'nowrap' }}>
                                        <div style={{ fontSize: 14, fontWeight: 800, color: '#1E1B4B', fontVariantNumeric: 'tabular-nums' }}>
                                            {s.price_type === 'usd' ? `$${Number(s.price ?? 0).toLocaleString()}` : Number(s.price ?? 0).toLocaleString()}
                                        </div>
                                        <div style={{ fontSize: 10, color: '#94A3B8', marginTop: 1 }}>
                                            {s.price_type === 'usd' ? 'دولار' : 'ل.س'}
                                        </div>
                                    </td>

                                    {/* Status */}
                                    <td style={{ padding: '12px 16px' }}>
                                        <span style={{
                                            display: 'inline-flex', alignItems: 'center', gap: 5,
                                            fontSize: 11, fontWeight: 700, padding: '4px 10px', borderRadius: 20,
                                            background: st.bg, color: st.color, border: `1px solid ${st.border}`,
                                        }}>
                                            <span style={{ width: 5, height: 5, borderRadius: '50%', background: st.dot, display: 'inline-block' }} />
                                            {st.label}
                                        </span>
                                    </td>

                                    {/* Actions */}
                                    <td style={{ padding: '12px 16px' }}>
                                        <div style={{ display: 'flex', gap: 5, alignItems: 'center', flexWrap: 'wrap' }}>
                                            {s.status !== 'approved' && (
                                                <button onClick={() => patch(s.id, 'approve')} style={{
                                                    display: 'inline-flex', alignItems: 'center', gap: 4,
                                                    padding: '5px 11px', borderRadius: 8,
                                                    border: '1px solid #6EE7B7', background: '#ECFDF5', color: '#065F46',
                                                    fontSize: 11.5, fontWeight: 700, cursor: 'pointer', fontFamily: "'Cairo','Inter',sans-serif", transition: 'all .13s',
                                                }}
                                                    onMouseEnter={e => e.currentTarget.style.background = '#D1FAE5'}
                                                    onMouseLeave={e => e.currentTarget.style.background = '#ECFDF5'}
                                                >
                                                    <i className="ti ti-check" /> قبول
                                                </button>
                                            )}
                                            {s.status !== 'rejected' && (
                                                <button onClick={() => patch(s.id, 'reject')} style={{
                                                    display: 'inline-flex', alignItems: 'center', gap: 4,
                                                    padding: '5px 11px', borderRadius: 8,
                                                    border: '1px solid #FCA5A5', background: '#FEF2F2', color: '#991B1B',
                                                    fontSize: 11.5, fontWeight: 700, cursor: 'pointer', fontFamily: "'Cairo','Inter',sans-serif", transition: 'all .13s',
                                                }}
                                                    onMouseEnter={e => e.currentTarget.style.background = '#FEE2E2'}
                                                    onMouseLeave={e => e.currentTarget.style.background = '#FEF2F2'}
                                                >
                                                    <i className="ti ti-x" /> رفض
                                                </button>
                                            )}
                                            {s.status !== 'pending' && (
                                                <button onClick={() => patch(s.id, 'pending')} title="إعادة للمراجعة" style={{
                                                    width: 32, height: 32, borderRadius: 8,
                                                    border: '1px solid rgba(0,0,0,0.1)', background: '#FAFAFA', color: '#64748B',
                                                    fontSize: 14, cursor: 'pointer', display: 'flex', alignItems: 'center', justifyContent: 'center', transition: 'all .13s',
                                                }}
                                                    onMouseEnter={e => e.currentTarget.style.background = '#FEF3C7'}
                                                    onMouseLeave={e => e.currentTarget.style.background = '#FAFAFA'}
                                                >
                                                    <i className="ti ti-clock" />
                                                </button>
                                            )}
                                            <button onClick={() => destroy(s.id)} title="حذف" style={{
                                                width: 32, height: 32, borderRadius: 8, cursor: 'pointer',
                                                border: '1px solid #FCA5A5', background: '#FEF2F2', color: '#DC2626',
                                                fontSize: 14, display: 'flex', alignItems: 'center', justifyContent: 'center', transition: 'all .13s',
                                            }}
                                                onMouseEnter={e => { e.currentTarget.style.background = '#FEE2E2'; e.currentTarget.style.transform = 'scale(1.08)'; }}
                                                onMouseLeave={e => { e.currentTarget.style.background = '#FEF2F2'; e.currentTarget.style.transform = 'scale(1)'; }}
                                            >
                                                <i className="ti ti-trash" />
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            );
                        })}
                    </tbody>
                </table>
                </div>

                {filtered.length > 0 && (
                    <div style={{ padding: '10px 16px', borderTop: '1px solid rgba(0,0,0,0.04)', background: '#FAFAFF', fontSize: 11.5, color: '#94A3B8', textAlign: 'center' }}>
                        عرض {filtered.length} من {all.length} خدمة
                    </div>
                )}
            </div>
        </SuperAdminLayout>
    );
}
