import { Head, router } from '@inertiajs/react';
import { useState } from 'react';
import SuperAdminLayout from '../../Layouts/SuperAdminLayout';

const AV = ['#6D28D9','#0D9488','#2563EB','#D97706','#DC2626','#0891B2','#7C3AED','#059669'];

const STATUS_CFG = {
    approved:     { bg: '#D1FAE5', color: '#065F46', label: 'مقبول',          icon: 'ti-circle-check',  border: '#6EE7B7' },
    pending:      { bg: '#FEF3C7', color: '#92400E', label: 'قيد المراجعة',   icon: 'ti-clock',         border: '#FDE68A' },
    rejected:     { bg: '#FEE2E2', color: '#991B1B', label: 'مرفوض',          icon: 'ti-circle-x',      border: '#FCA5A5' },
    under_review: { bg: '#DBEAFE', color: '#1E40AF', label: 'تحت المراجعة',  icon: 'ti-eye',           border: '#93C5FD' },
};
const DEFAULT_ST = STATUS_CFG.pending;

const TABS = [
    { key: 'all',      label: 'الكل',           color: '#1E1B4B', bg: '#EEF2FF', border: '#C7D2FE', icon: 'ti-apps' },
    { key: 'pending',  label: 'قيد المراجعة',  color: '#92400E', bg: '#FEF3C7', border: '#FDE68A', icon: 'ti-clock' },
    { key: 'approved', label: 'مقبول',           color: '#065F46', bg: '#D1FAE5', border: '#6EE7B7', icon: 'ti-circle-check' },
    { key: 'rejected', label: 'مرفوض',          color: '#991B1B', bg: '#FEE2E2', border: '#FCA5A5', icon: 'ti-circle-x' },
];

const PRICE_TYPE_LABEL = { usd: '$', syp: 'ل.س' };

export default function Services({ services }) {
    const [tab,    setTab]    = useState('all');
    const [search, setSearch] = useState('');

    const allServices = services ?? [];

    const filtered = allServices
        .filter(s => tab === 'all' || s.status === tab)
        .filter(s =>
            `${s.name ?? ''} ${s.user?.first_name ?? ''} ${s.user?.last_name ?? ''} ${s.category?.name_ar ?? s.category?.name_en ?? ''} ${s.city?.name_ar ?? s.city?.name_en ?? ''}`
                .toLowerCase().includes(search.toLowerCase())
        );

    const counts = TABS.reduce((acc, t) => {
        acc[t.key] = t.key === 'all' ? allServices.length : allServices.filter(s => s.status === t.key).length;
        return acc;
    }, {});

    const patch   = (id, action) => router.patch(`/super-admin/services/${id}/${action}`, {}, { preserveScroll: true });
    const destroy = (id) => { if (!confirm('حذف هذه الخدمة نهائياً؟')) return; router.delete(`/super-admin/services/${id}`, { preserveScroll: true }); };

    return (
        <SuperAdminLayout title="الخدمات">
            <Head title="الخدمات — Skillify" />

            {/* ─── Header ─── */}
            <div style={{ display: 'flex', alignItems: 'flex-start', justifyContent: 'space-between', flexWrap: 'wrap', gap: 12 }}>
                <div>
                    <h1 style={{ fontSize: 22, fontWeight: 800, color: '#1E1B4B', margin: 0, letterSpacing: -0.5 }}>جميع الخدمات</h1>
                    <p style={{ fontSize: 12, color: '#94A3B8', marginTop: 4 }}>{filtered.length} خدمة</p>
                </div>

                {/* Search */}
                <div style={{ position: 'relative', minWidth: 260 }}>
                    <i className="ti ti-search" style={{ position: 'absolute', top: '50%', right: 12, transform: 'translateY(-50%)', color: '#94A3B8', fontSize: 15, pointerEvents: 'none' }} />
                    <input value={search} onChange={e => setSearch(e.target.value)}
                        placeholder="بحث بالاسم، المزود، الفئة، المدينة..."
                        style={{ width: '100%', padding: '9px 38px 9px 13px', border: '1px solid rgba(0,0,0,0.11)', borderRadius: 9, fontSize: 13, outline: 'none', boxSizing: 'border-box', fontFamily: "'Cairo','Inter',sans-serif", background: '#FAFAFA' }} />
                </div>
            </div>

            {/* ─── Status tabs ─── */}
            <div style={{ display: 'flex', gap: 8, flexWrap: 'wrap' }}>
                {TABS.map(t => (
                    <button key={t.key} onClick={() => setTab(t.key)} style={{
                        display: 'inline-flex', alignItems: 'center', gap: 6, padding: '7px 16px', borderRadius: 24,
                        border: `1px solid ${tab === t.key ? t.color : 'rgba(0,0,0,0.10)'}`,
                        background: tab === t.key ? t.bg : '#fff',
                        color: tab === t.key ? t.color : '#64748B',
                        fontSize: 12.5, fontWeight: tab === t.key ? 700 : 500, cursor: 'pointer',
                        fontFamily: "'Cairo','Inter',sans-serif", transition: 'all 0.13s',
                    }}>
                        <i className={`ti ${t.icon}`} style={{ fontSize: 13 }} />
                        {t.label}
                        <span style={{ background: tab === t.key ? 'rgba(0,0,0,0.10)' : '#F1F5F9', color: tab === t.key ? t.color : '#64748B', borderRadius: 20, padding: '0 7px', fontSize: 11, fontWeight: 700 }}>
                            {counts[t.key]}
                        </span>
                    </button>
                ))}
            </div>

            {/* ─── Table ─── */}
            <div style={{ background: '#fff', border: '1px solid rgba(0,0,0,0.07)', borderRadius: 16, overflow: 'hidden', boxShadow: '0 2px 12px rgba(0,0,0,0.04)' }}>
                <table style={{ width: '100%', borderCollapse: 'collapse', fontSize: 13 }}>
                    <thead>
                        <tr style={{ background: 'linear-gradient(135deg,#F8FAFC,#F1F5F9)', borderBottom: '1px solid rgba(0,0,0,0.07)' }}>
                            {[
                                { label: 'الخدمة',    w: '28%' },
                                { label: 'الفئة',     w: '15%' },
                                { label: 'المزود',    w: '15%' },
                                { label: 'السعر',     w: '12%' },
                                { label: 'الحالة',   w: '13%' },
                                { label: 'إجراءات',  w: '17%' },
                            ].map(h => (
                                <th key={h.label} style={{ padding: '12px 16px', textAlign: 'right', fontWeight: 700, color: '#374151', whiteSpace: 'nowrap', width: h.w, fontSize: 12 }}>
                                    {h.label}
                                </th>
                            ))}
                        </tr>
                    </thead>
                    <tbody>
                        {!filtered.length ? (
                            <tr>
                                <td colSpan={6} style={{ padding: '64px 24px', textAlign: 'center', color: '#94A3B8' }}>
                                    <i className="ti ti-tool" style={{ fontSize: 48, display: 'block', marginBottom: 12, opacity: 0.12 }} />
                                    <div style={{ fontSize: 14, fontWeight: 600, color: '#64748B', marginBottom: 6 }}>لا توجد خدمات</div>
                                    <p style={{ fontSize: 13, margin: 0 }}>جرّب تغيير الفلتر أو كلمة البحث.</p>
                                </td>
                            </tr>
                        ) : filtered.map((s, i) => {
                            const st    = STATUS_CFG[s.status] ?? DEFAULT_ST;
                            const price = Number(s.price ?? 0).toLocaleString('ar');
                            const unit  = s.price_type === 'usd' ? '$' : 'ل.س';
                            const catName  = s.category?.name_ar ?? s.category?.name_en ?? '—';
                            const subName  = s.subcategory?.name_ar ?? s.subcategory?.name_en ?? '';
                            const cityName = s.city?.name_ar ?? s.city?.name_en ?? '';
                            const av = AV[i % AV.length];

                            return (
                                <tr key={s.id}
                                    style={{ borderBottom: '0.5px solid rgba(0,0,0,0.05)', transition: 'background 0.12s' }}
                                    onMouseEnter={e => e.currentTarget.style.background = '#FAFAFF'}
                                    onMouseLeave={e => e.currentTarget.style.background = 'transparent'}
                                >
                                    {/* Service name + city */}
                                    <td style={{ padding: '13px 16px' }}>
                                        <div style={{ display: 'flex', alignItems: 'center', gap: 10 }}>
                                            <div style={{
                                                width: 36, height: 36, borderRadius: 10, flexShrink: 0,
                                                background: `linear-gradient(135deg,${av},${AV[(i + 2) % AV.length]})`,
                                                display: 'flex', alignItems: 'center', justifyContent: 'center',
                                                fontSize: 15, color: '#fff',
                                            }}>
                                                <i className="ti ti-tool" />
                                            </div>
                                            <div style={{ minWidth: 0 }}>
                                                <div style={{ fontWeight: 700, color: '#0F172A', overflow: 'hidden', textOverflow: 'ellipsis', whiteSpace: 'nowrap', maxWidth: 200 }}>
                                                    {s.name}
                                                </div>
                                                {cityName && (
                                                    <div style={{ fontSize: 11, color: '#94A3B8', marginTop: 2, display: 'flex', alignItems: 'center', gap: 3 }}>
                                                        <i className="ti ti-map-pin" style={{ fontSize: 11 }} />{cityName}
                                                    </div>
                                                )}
                                            </div>
                                        </div>
                                    </td>

                                    {/* Category */}
                                    <td style={{ padding: '13px 16px' }}>
                                        <div style={{ fontSize: 12.5, fontWeight: 600, color: '#334155' }}>{catName}</div>
                                        {subName && <div style={{ fontSize: 11, color: '#94A3B8', marginTop: 2 }}>{subName}</div>}
                                    </td>

                                    {/* Provider */}
                                    <td style={{ padding: '13px 16px' }}>
                                        <div style={{ display: 'flex', alignItems: 'center', gap: 8 }}>
                                            <div style={{
                                                width: 28, height: 28, borderRadius: '50%', flexShrink: 0,
                                                background: `linear-gradient(135deg,${AV[(i + 1) % AV.length]},${AV[(i + 4) % AV.length]})`,
                                                display: 'flex', alignItems: 'center', justifyContent: 'center',
                                                fontSize: 11, fontWeight: 700, color: '#fff',
                                            }}>
                                                {(s.user?.first_name?.[0] ?? 'م').toUpperCase()}
                                            </div>
                                            <div>
                                                <div style={{ fontSize: 12.5, fontWeight: 600, color: '#0F172A', whiteSpace: 'nowrap' }}>
                                                    {s.user?.first_name} {s.user?.last_name}
                                                </div>
                                            </div>
                                        </div>
                                    </td>

                                    {/* Price */}
                                    <td style={{ padding: '13px 16px', whiteSpace: 'nowrap' }}>
                                        <div style={{ fontSize: 14, fontWeight: 800, color: '#0F172A' }}>
                                            {s.price_type === 'usd' ? `$${price}` : price}
                                        </div>
                                        <div style={{ fontSize: 10, color: '#94A3B8', marginTop: 1 }}>
                                            {s.price_type === 'usd' ? 'دولار' : 'ل.س'}
                                        </div>
                                    </td>

                                    {/* Status */}
                                    <td style={{ padding: '13px 16px' }}>
                                        <span style={{
                                            display: 'inline-flex', alignItems: 'center', gap: 5,
                                            fontSize: 11, fontWeight: 700, padding: '5px 11px', borderRadius: 20,
                                            background: st.bg, color: st.color,
                                            border: `1px solid ${st.border}`,
                                        }}>
                                            <i className={`ti ${st.icon}`} style={{ fontSize: 11 }} />
                                            {st.label}
                                        </span>
                                    </td>

                                    {/* Actions */}
                                    <td style={{ padding: '13px 16px' }}>
                                        <div style={{ display: 'flex', gap: 5, alignItems: 'center' }}>
                                            {s.status !== 'approved' && (
                                                <button onClick={() => patch(s.id, 'approve')} title="قبول" style={{
                                                    display: 'inline-flex', alignItems: 'center', gap: 4,
                                                    padding: '5px 10px', borderRadius: 7,
                                                    border: '1px solid #6EE7B7', background: '#D1FAE5', color: '#065F46',
                                                    fontSize: 11, fontWeight: 600, cursor: 'pointer',
                                                    fontFamily: "'Cairo','Inter',sans-serif", transition: 'all 0.13s',
                                                }}
                                                    onMouseEnter={e => e.currentTarget.style.background = '#A7F3D0'}
                                                    onMouseLeave={e => e.currentTarget.style.background = '#D1FAE5'}
                                                >
                                                    <i className="ti ti-check" style={{ fontSize: 12 }} /> قبول
                                                </button>
                                            )}
                                            {s.status !== 'rejected' && (
                                                <button onClick={() => patch(s.id, 'reject')} title="رفض" style={{
                                                    display: 'inline-flex', alignItems: 'center', gap: 4,
                                                    padding: '5px 10px', borderRadius: 7,
                                                    border: '1px solid #FCA5A5', background: '#FEE2E2', color: '#991B1B',
                                                    fontSize: 11, fontWeight: 600, cursor: 'pointer',
                                                    fontFamily: "'Cairo','Inter',sans-serif", transition: 'all 0.13s',
                                                }}
                                                    onMouseEnter={e => e.currentTarget.style.background = '#FECACA'}
                                                    onMouseLeave={e => e.currentTarget.style.background = '#FEE2E2'}
                                                >
                                                    <i className="ti ti-x" style={{ fontSize: 12 }} /> رفض
                                                </button>
                                            )}
                                            {s.status !== 'pending' && (
                                                <button onClick={() => patch(s.id, 'pending')} title="قيد المراجعة" style={{
                                                    width: 30, height: 30, borderRadius: 7,
                                                    border: '1px solid rgba(0,0,0,0.11)', background: '#F8FAFC', color: '#64748B',
                                                    fontSize: 13, cursor: 'pointer', display: 'flex', alignItems: 'center', justifyContent: 'center',
                                                    transition: 'all 0.13s',
                                                }}
                                                    onMouseEnter={e => e.currentTarget.style.background = '#FEF3C7'}
                                                    onMouseLeave={e => e.currentTarget.style.background = '#F8FAFC'}
                                                >
                                                    <i className="ti ti-clock" />
                                                </button>
                                            )}
                                            <button onClick={() => destroy(s.id)} title="حذف" style={{
                                                width: 30, height: 30, borderRadius: 7,
                                                border: '1px solid #FCA5A5', background: '#FEF2F2', color: '#DC2626',
                                                fontSize: 13, cursor: 'pointer', display: 'flex', alignItems: 'center', justifyContent: 'center',
                                                transition: 'all 0.13s',
                                            }}
                                                onMouseEnter={e => { e.currentTarget.style.background = '#FEE2E2'; e.currentTarget.style.transform = 'scale(1.1)'; }}
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
        </SuperAdminLayout>
    );
}
