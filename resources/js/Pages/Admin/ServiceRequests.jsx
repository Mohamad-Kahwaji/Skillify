import { Head, router } from '@inertiajs/react';
import { useState } from 'react';
import AdminLayout, { C } from '../../Layouts/AdminLayout';

const AV = ['#6D28D9','#0D9488','#2563EB','#D97706','#DC2626','#0891B2','#7C3AED','#059669'];

const STATUS = {
    approved: { bg: '#ECFDF5', color: '#065F46', border: '#6EE7B7', dot: '#10B981', label: 'مقبول',         icon: 'ti-circle-check' },
    pending:  { bg: '#FEF3C7', color: '#92400E', border: '#FDE68A', dot: '#F59E0B', label: 'قيد المراجعة', icon: 'ti-clock' },
    rejected: { bg: '#FEF2F2', color: '#991B1B', border: '#FCA5A5', dot: '#EF4444', label: 'مرفوض',         icon: 'ti-circle-x' },
};

const TABS = [
    { key: 'all',      label: 'الكل',          icon: 'ti-apps',         color: '#1E40AF', bg: '#EFF6FF', border: '#BFDBFE' },
    { key: 'pending',  label: 'قيد المراجعة', icon: 'ti-clock',         color: '#92400E', bg: '#FEF3C7', border: '#FDE68A' },
    { key: 'approved', label: 'مقبول',          icon: 'ti-circle-check', color: '#065F46', bg: '#ECFDF5', border: '#6EE7B7' },
    { key: 'rejected', label: 'مرفوض',         icon: 'ti-circle-x',     color: '#991B1B', bg: '#FEF2F2', border: '#FCA5A5' },
];

function ServiceThumb({ service, idx }) {
    const [err, setErr] = useState(false);
    const img = (!err && service.image)
        ? (service.image.startsWith('http') ? service.image : `/storage/${service.image}`)
        : null;
    const c1 = AV[idx % AV.length], c2 = AV[(idx + 2) % AV.length];
    if (img) return <img src={img} onError={() => setErr(true)} alt="" style={{ width: 48, height: 48, borderRadius: 12, objectFit: 'cover', flexShrink: 0, border: '1px solid rgba(0,0,0,0.07)' }} />;
    return (
        <div style={{ width: 48, height: 48, borderRadius: 12, flexShrink: 0, background: `linear-gradient(135deg,${c1},${c2})`, display: 'flex', alignItems: 'center', justifyContent: 'center', fontSize: 20, color: '#fff' }}>
            <i className="ti ti-tool" />
        </div>
    );
}

function timeAgo(d) {
    if (!d) return '';
    const s = Math.floor((Date.now() - new Date(d)) / 1000);
    if (s < 60) return 'الآن';
    if (s < 3600) return `منذ ${Math.floor(s/60)} د`;
    if (s < 86400) return `منذ ${Math.floor(s/3600)} س`;
    return `منذ ${Math.floor(s/86400)} ي`;
}

export default function ServiceRequests({ services = [] }) {
    const [tab,    setTab]    = useState('pending');
    const [search, setSearch] = useState('');

    const counts = TABS.reduce((a, t) => {
        a[t.key] = t.key === 'all' ? services.length : services.filter(s => s.status === t.key).length;
        return a;
    }, {});

    const filtered = services
        .filter(s => tab === 'all' || s.status === tab)
        .filter(s => `${s.name} ${s.user?.first_name} ${s.user?.last_name} ${s.category?.name} ${s.city?.name}`.toLowerCase().includes(search.toLowerCase()));

    const approve = (id) => router.patch(`/admin/service-requests/${id}/approve`, {}, { preserveScroll: true });
    const reject  = (id) => router.patch(`/admin/service-requests/${id}/reject`,  {}, { preserveScroll: true });
    const pending = (id) => router.patch(`/admin/service-requests/${id}/pending`, {}, { preserveScroll: true });

    return (
        <AdminLayout title="طلبات الخدمات">
            <Head title="طلبات الخدمات — Skillify" />

            {/* Header */}
            <div style={{ display: 'flex', alignItems: 'flex-start', justifyContent: 'space-between', flexWrap: 'wrap', gap: 12 }}>
                <div>
                    <h1 style={{ fontSize: 22, fontWeight: 800, color: C.textDark, margin: 0, letterSpacing: -0.5 }}>طلبات الخدمات</h1>
                    <p style={{ fontSize: 12, color: C.textFaint, marginTop: 4 }}>
                        {counts.pending > 0 && <span style={{ background: '#FEF3C7', color: '#92400E', border: '1px solid #FDE68A', borderRadius: 20, padding: '1px 8px', fontSize: 11, fontWeight: 700, marginLeft: 8 }}>{counts.pending} بانتظار المراجعة</span>}
                        {services.length} طلب إجمالاً
                    </p>
                </div>
                <div style={{ position: 'relative' }}>
                    <i className="ti ti-search" style={{ position: 'absolute', top: '50%', right: 12, transform: 'translateY(-50%)', color: C.textFaint, fontSize: 14, pointerEvents: 'none' }} />
                    <input value={search} onChange={e => setSearch(e.target.value)}
                        placeholder="بحث بالاسم أو المزود..."
                        style={{ width: 250, padding: '9px 38px 9px 14px', border: C.cardBorder, borderRadius: 10, fontSize: 13, outline: 'none', boxSizing: 'border-box', fontFamily: "'Cairo','Inter',sans-serif", background: '#FAFAFA', direction: 'rtl' }}
                        onFocus={e => e.target.style.borderColor = C.primary}
                        onBlur={e => e.target.style.borderColor = 'rgba(15,23,42,0.06)'} />
                </div>
            </div>

            {/* Stats */}
            <div style={{ display: 'grid', gridTemplateColumns: 'repeat(auto-fill,minmax(150px,1fr))', gap: 12 }}>
                {[
                    { label: 'إجمالي الطلبات', val: counts.all,      icon: 'ti-file-text',    c: C.primary,  bg: '#EFF6FF' },
                    { label: 'قيد المراجعة',   val: counts.pending,  icon: 'ti-clock',         c: '#D97706',  bg: '#FEF3C7' },
                    { label: 'مقبول',           val: counts.approved, icon: 'ti-circle-check', c: '#059669',  bg: '#ECFDF5' },
                    { label: 'مرفوض',          val: counts.rejected, icon: 'ti-circle-x',     c: '#DC2626',  bg: '#FEF2F2' },
                ].map(s => (
                    <div key={s.label} style={{ background: '#fff', border: C.cardBorder, borderRadius: 12, padding: '14px 16px', boxShadow: C.cardShadow, display: 'flex', alignItems: 'center', gap: 10 }}>
                        <div style={{ width: 36, height: 36, borderRadius: 9, background: s.bg, display: 'flex', alignItems: 'center', justifyContent: 'center', flexShrink: 0 }}>
                            <i className={`ti ${s.icon}`} style={{ fontSize: 16, color: s.c }} />
                        </div>
                        <div>
                            <div style={{ fontSize: 20, fontWeight: 800, color: C.textDark, lineHeight: 1 }}>{s.val}</div>
                            <div style={{ fontSize: 11, color: C.textFaint, marginTop: 2 }}>{s.label}</div>
                        </div>
                    </div>
                ))}
            </div>

            {/* Tabs */}
            <div style={{ display: 'flex', gap: 7, flexWrap: 'wrap' }}>
                {TABS.map(t => (
                    <button key={t.key} onClick={() => setTab(t.key)} style={{
                        display: 'inline-flex', alignItems: 'center', gap: 6, padding: '7px 16px', borderRadius: 24,
                        border: `1.5px solid ${tab === t.key ? t.color : 'rgba(0,0,0,0.08)'}`,
                        background: tab === t.key ? t.bg : '#fff',
                        color: tab === t.key ? t.color : '#64748B',
                        fontSize: 12.5, fontWeight: tab === t.key ? 700 : 500, cursor: 'pointer',
                        fontFamily: "'Cairo','Inter',sans-serif", transition: 'all .13s',
                        boxShadow: tab === t.key ? '0 2px 8px rgba(0,0,0,0.08)' : 'none',
                    }}>
                        <i className={`ti ${t.icon}`} style={{ fontSize: 13 }} />
                        {t.label}
                        <span style={{ background: tab === t.key ? 'rgba(0,0,0,0.1)' : '#F1F5F9', color: tab === t.key ? t.color : '#64748B', borderRadius: 20, padding: '1px 8px', fontSize: 11, fontWeight: 700 }}>
                            {counts[t.key]}
                        </span>
                    </button>
                ))}
            </div>

            {/* Cards */}
            {!filtered.length ? (
                <div style={{ padding: '72px 24px', textAlign: 'center', background: '#fff', border: C.cardBorder, borderRadius: 16, boxShadow: C.cardShadow }}>
                    <div style={{ width: 64, height: 64, borderRadius: '50%', background: '#F1F5F9', display: 'flex', alignItems: 'center', justifyContent: 'center', margin: '0 auto 16px' }}>
                        <i className="ti ti-tool" style={{ fontSize: 28, color: '#CBD5E1' }} />
                    </div>
                    <div style={{ fontSize: 15, fontWeight: 700, color: C.textMuted, marginBottom: 6 }}>
                        {tab === 'pending' ? 'لا توجد طلبات معلقة' : 'لا توجد نتائج'}
                    </div>
                    <p style={{ fontSize: 13, color: C.textFaint, margin: 0 }}>
                        {tab === 'pending' ? 'رائع! تمت معالجة جميع طلبات الخدمات.' : 'جرّب تغيير الفلتر.'}
                    </p>
                </div>
            ) : (
                <div style={{ display: 'flex', flexDirection: 'column', gap: 10 }}>
                    {filtered.map((s, i) => {
                        const st = STATUS[s.status] ?? STATUS.pending;
                        return (
                            <div key={s.id} style={{
                                background: '#fff', border: C.cardBorder, borderRadius: 16,
                                boxShadow: C.cardShadow, padding: '16px 20px',
                                display: 'flex', alignItems: 'flex-start', gap: 14, flexWrap: 'wrap',
                                borderRight: s.status === 'pending' ? `3px solid ${C.teal}` : `3px solid transparent`,
                                transition: 'box-shadow .15s',
                            }}
                                onMouseEnter={e => e.currentTarget.style.boxShadow = '0 4px 20px rgba(0,0,0,0.09)'}
                                onMouseLeave={e => e.currentTarget.style.boxShadow = C.cardShadow}
                            >
                                <ServiceThumb service={s} idx={i} />

                                {/* Info */}
                                <div style={{ flex: 1, minWidth: 200 }}>
                                    <div style={{ display: 'flex', alignItems: 'center', gap: 8, flexWrap: 'wrap', marginBottom: 4 }}>
                                        <span style={{ fontSize: 14, fontWeight: 700, color: C.textDark }}>{s.name}</span>
                                        <span style={{
                                            display: 'inline-flex', alignItems: 'center', gap: 4, fontSize: 11, fontWeight: 700,
                                            padding: '2px 9px', borderRadius: 20, background: st.bg, color: st.color, border: `1px solid ${st.border}`,
                                        }}>
                                            <span style={{ width: 5, height: 5, borderRadius: '50%', background: st.dot }} />
                                            {st.label}
                                        </span>
                                    </div>

                                    <div style={{ display: 'flex', flexWrap: 'wrap', gap: 6, marginBottom: 6 }}>
                                        {s.category?.name    && <Chip icon="ti-tag"     text={s.category.name} />}
                                        {s.subcategory?.name && <Chip icon="ti-category" text={s.subcategory.name} />}
                                        {s.city?.name        && <Chip icon="ti-map-pin"  text={s.city.name} />}
                                    </div>

                                    <div style={{ display: 'flex', alignItems: 'center', gap: 12, flexWrap: 'wrap' }}>
                                        <div style={{ display: 'flex', alignItems: 'center', gap: 6 }}>
                                            <div style={{ width: 22, height: 22, borderRadius: '50%', background: `linear-gradient(135deg,${AV[(i+1)%AV.length]},${AV[(i+3)%AV.length]})`, display: 'flex', alignItems: 'center', justifyContent: 'center', fontSize: 9, fontWeight: 700, color: '#fff' }}>
                                                {(s.user?.first_name?.[0] ?? 'م').toUpperCase()}
                                            </div>
                                            <span style={{ fontSize: 12, color: C.textMuted, fontWeight: 600 }}>{s.user?.first_name} {s.user?.last_name}</span>
                                        </div>
                                        <span style={{ fontSize: 16, fontWeight: 800, color: C.teal }}>
                                            {s.price_type === 'usd' ? `$${Number(s.price).toLocaleString()}` : `${Number(s.price).toLocaleString()} ل.س`}
                                        </span>
                                        <span style={{ fontSize: 11, color: C.textFaint, display: 'flex', alignItems: 'center', gap: 3 }}>
                                            <i className="ti ti-clock" style={{ fontSize: 11 }} />{timeAgo(s.created_at)}
                                        </span>
                                    </div>

                                    {s.description && (
                                        <p style={{ fontSize: 12, color: C.textMuted, lineHeight: 1.55, marginTop: 6, marginBottom: 0, display: '-webkit-box', WebkitLineClamp: 2, WebkitBoxOrient: 'vertical', overflow: 'hidden' }}>
                                            {s.description}
                                        </p>
                                    )}
                                </div>

                                {/* Actions */}
                                <div style={{ display: 'flex', gap: 7, alignItems: 'center', flexShrink: 0 }}>
                                    {s.status !== 'approved' && (
                                        <button onClick={() => approve(s.id)} style={btnStyle('#065F46', '#ECFDF5', '#6EE7B7')}>
                                            <i className="ti ti-check" /> قبول
                                        </button>
                                    )}
                                    {s.status !== 'rejected' && (
                                        <button onClick={() => reject(s.id)} style={btnStyle('#991B1B', '#FEF2F2', '#FCA5A5')}>
                                            <i className="ti ti-x" /> رفض
                                        </button>
                                    )}
                                    {s.status !== 'pending' && (
                                        <button onClick={() => pending(s.id)} title="إعادة للمراجعة" style={{
                                            width: 32, height: 32, borderRadius: 8, cursor: 'pointer',
                                            border: '1px solid #E2E8F0', background: '#F8FAFC', color: '#64748B',
                                            fontSize: 14, display: 'flex', alignItems: 'center', justifyContent: 'center',
                                        }}>
                                            <i className="ti ti-clock" />
                                        </button>
                                    )}
                                </div>
                            </div>
                        );
                    })}
                </div>
            )}
        </AdminLayout>
    );
}

function Chip({ icon, text }) {
    return (
        <span style={{ display: 'inline-flex', alignItems: 'center', gap: 4, fontSize: 11, color: '#64748B', background: '#F1F5F9', padding: '2px 8px', borderRadius: 20, border: '0.5px solid #E2E8F0' }}>
            <i className={`ti ${icon}`} style={{ fontSize: 11 }} />{text}
        </span>
    );
}

function btnStyle(color, bg, border) {
    return {
        display: 'inline-flex', alignItems: 'center', gap: 5,
        padding: '7px 14px', borderRadius: 9, cursor: 'pointer',
        border: `1px solid ${border}`, background: bg, color,
        fontSize: 12, fontWeight: 700, fontFamily: "'Cairo','Inter',sans-serif", transition: 'filter .13s',
        onMouseEnter: undefined,
    };
}
