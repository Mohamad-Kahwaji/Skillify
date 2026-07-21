import { Head, router } from '@inertiajs/react';
import { useState } from 'react';
import SuperAdminLayout from '../../Layouts/SuperAdminLayout';

const AV_COLORS = ['#6D28D9','#0D9488','#2563EB','#D97706','#DC2626','#0891B2','#7C3AED','#059669'];

const STATUS_CFG = {
    active:   { label: 'نشط',   color: '#065F46', bg: '#D1FAE5', border: '#6EE7B7', dot: '#10B981' },
    inactive: { label: 'محظور', color: '#991B1B', bg: '#FEE2E2', border: '#FCA5A5', dot: '#EF4444' },
};
const DEFAULT_STATUS = { label: 'نشط', color: '#065F46', bg: '#D1FAE5', border: '#6EE7B7', dot: '#10B981' };

const GENDER_LABEL = { male: 'ذكر', female: 'أنثى', other: 'آخر' };

const SERVICE_STATUS_CFG = {
    approved: { bg: '#ECFDF5', color: '#065F46', label: 'مقبول' },
    pending:  { bg: '#FEF3C7', color: '#92400E', label: 'قيد المراجعة' },
    rejected: { bg: '#FEF2F2', color: '#991B1B', label: 'مرفوض' },
};
const DEFAULT_SERVICE_STATUS = SERVICE_STATUS_CFG.pending;

function priceLabel(s) {
    if (s.price == null) return null;
    const amount = Number(s.price).toLocaleString();
    return s.price_type === 'usd' ? `$${amount}` : `${amount} ل.س`;
}

function avColor(i) { return AV_COLORS[i % AV_COLORS.length]; }
function avColor2(i) { return AV_COLORS[(i + 3) % AV_COLORS.length]; }

function initials(u) {
    return `${u.first_name?.[0] ?? ''}${u.last_name?.[0] ?? ''}`.toUpperCase() || 'U';
}

function formatDate(d, opts = { day: 'numeric', month: 'short', year: 'numeric' }) {
    if (!d) return '—';
    return new Date(d).toLocaleDateString('ar', opts);
}

/* ════════════════ User Detail Drawer ════════════════ */
function UserDrawer({ user, index, onClose, onDelete, onBlock, onUnblock }) {
    if (!user) return null;
    const sc  = STATUS_CFG[user.status] ?? DEFAULT_STATUS;
    const isBlocked = user.status === 'inactive';
    const biz = user.businesses;
    const bizStatus = biz ? (biz.status === 'active' ? { label: 'نشط', color: '#065F46', bg: '#D1FAE5' } : biz.status === 'pending' ? { label: 'قيد المراجعة', color: '#92400E', bg: '#FEF3C7' } : { label: 'مرفوض', color: '#991B1B', bg: '#FEE2E2' }) : null;

    return (
        <>
            {/* Overlay */}
            <div onClick={onClose} style={{ position: 'fixed', inset: 0, background: 'rgba(15,23,42,0.35)', zIndex: 100, backdropFilter: 'blur(2px)' }} />

            {/* Drawer */}
            <div style={{
                position: 'fixed', top: 0, left: 0, bottom: 0, width: 380, maxWidth: '92vw',
                background: '#fff', zIndex: 101,
                boxShadow: '4px 0 40px rgba(0,0,0,0.18)',
                display: 'flex', flexDirection: 'column',
                animation: 'slideInLeft 0.22s ease',
            }}>
                <style>{`@keyframes slideInLeft { from { transform: translateX(-24px); opacity:0; } to { transform: translateX(0); opacity:1; } }`}</style>

                {/* Drawer header */}
                <div style={{ padding: '20px 22px 16px', borderBottom: '0.5px solid rgba(0,0,0,0.07)', display: 'flex', alignItems: 'center', justifyContent: 'space-between', flexShrink: 0 }}>
                    <span style={{ fontSize: 15, fontWeight: 700, color: '#1E1B4B' }}>تفاصيل المستخدم</span>
                    <button onClick={onClose} style={{ width: 30, height: 30, borderRadius: 8, border: '1px solid rgba(0,0,0,0.10)', background: '#F8FAFC', color: '#64748B', fontSize: 16, cursor: 'pointer', display: 'flex', alignItems: 'center', justifyContent: 'center' }}>
                        <i className="ti ti-x" />
                    </button>
                </div>

                {/* Scrollable body */}
                <div style={{ flex: 1, overflowY: 'auto', padding: '22px' }}>

                    {/* Avatar + name */}
                    <div style={{ display: 'flex', flexDirection: 'column', alignItems: 'center', marginBottom: 24 }}>
                        <div style={{
                            width: 72, height: 72, borderRadius: '50%', marginBottom: 12,
                            background: `linear-gradient(135deg,${avColor(index)},${avColor2(index)})`,
                            display: 'flex', alignItems: 'center', justifyContent: 'center',
                            fontSize: 26, fontWeight: 800, color: '#fff',
                            boxShadow: `0 6px 20px ${avColor(index)}44`,
                        }}>
                            {initials(user)}
                        </div>
                        <div style={{ fontSize: 18, fontWeight: 800, color: '#0F172A', textAlign: 'center' }}>
                            {user.first_name} {user.middle_name ? user.middle_name + ' ' : ''}{user.last_name}
                        </div>
                        <div style={{ fontSize: 12, color: '#94A3B8', marginTop: 4 }}>#{user.id}</div>
                        <span style={{ marginTop: 10, display: 'inline-flex', alignItems: 'center', gap: 5, fontSize: 11, fontWeight: 700, padding: '4px 12px', borderRadius: 20, background: sc.bg, color: sc.color, border: `1px solid ${sc.border}` }}>
                            <span style={{ width: 6, height: 6, borderRadius: '50%', background: sc.dot, display: 'inline-block' }} />
                            {sc.label}
                        </span>
                    </div>

                    {/* Stats row */}
                    <div style={{ display: 'grid', gridTemplateColumns: '1fr 1fr 1fr', gap: 10, marginBottom: 22 }}>
                        {[
                            { icon: 'ti-file-text',  label: 'المنشورات',  value: user.posts_count ?? 0,    color: '#7C3AED', bg: '#F5F3FF' },
                            { icon: 'ti-tool',       label: 'الخدمات',    value: user.services_count ?? 0,  color: '#0D9488', bg: '#F0FDFA' },
                            { icon: 'ti-message',    label: 'التعليقات',  value: user.comments_count ?? 0,  color: '#2563EB', bg: '#EFF6FF' },
                        ].map(stat => (
                            <div key={stat.label} style={{ background: stat.bg, borderRadius: 12, padding: '12px 10px', textAlign: 'center' }}>
                                <i className={`ti ${stat.icon}`} style={{ fontSize: 18, color: stat.color, display: 'block', marginBottom: 4 }} />
                                <div style={{ fontSize: 18, fontWeight: 800, color: '#0F172A' }}>{stat.value}</div>
                                <div style={{ fontSize: 10, color: '#64748B', marginTop: 2 }}>{stat.label}</div>
                            </div>
                        ))}
                    </div>

                    {/* Contact info */}
                    <div style={{ background: '#F8FAFC', borderRadius: 14, padding: '16px', marginBottom: 16 }}>
                        <div style={{ fontSize: 11, fontWeight: 700, color: '#94A3B8', textTransform: 'uppercase', letterSpacing: 0.8, marginBottom: 12 }}>معلومات التواصل</div>
                        {[
                            { icon: 'ti-mail',     label: 'البريد الإلكتروني', value: user.email },
                            { icon: 'ti-phone',    label: 'رقم الهاتف',         value: user.phone ?? '—' },
                            { icon: 'ti-map-pin',  label: 'المدينة',            value: user.city ?? '—' },
                        ].map(row => (
                            <div key={row.label} style={{ display: 'flex', alignItems: 'center', gap: 10, marginBottom: 10 }}>
                                <div style={{ width: 30, height: 30, borderRadius: 8, background: '#EEF2FF', display: 'flex', alignItems: 'center', justifyContent: 'center', fontSize: 14, color: '#4F46E5', flexShrink: 0 }}>
                                    <i className={`ti ${row.icon}`} />
                                </div>
                                <div>
                                    <div style={{ fontSize: 10, color: '#94A3B8', marginBottom: 1 }}>{row.label}</div>
                                    <div style={{ fontSize: 12.5, fontWeight: 600, color: '#0F172A', wordBreak: 'break-all' }}>{row.value}</div>
                                </div>
                            </div>
                        ))}
                    </div>

                    {/* Personal info */}
                    <div style={{ background: '#F8FAFC', borderRadius: 14, padding: '16px', marginBottom: 16 }}>
                        <div style={{ fontSize: 11, fontWeight: 700, color: '#94A3B8', textTransform: 'uppercase', letterSpacing: 0.8, marginBottom: 12 }}>المعلومات الشخصية</div>
                        {[
                            { icon: 'ti-gender-bigender', label: 'الجنس',          value: GENDER_LABEL[user.gender] ?? '—' },
                            { icon: 'ti-cake',            label: 'تاريخ الميلاد', value: formatDate(user.birthdate) },
                            { icon: 'ti-calendar-plus',  label: 'تاريخ التسجيل', value: formatDate(user.created_at) },
                        ].map(row => (
                            <div key={row.label} style={{ display: 'flex', alignItems: 'center', gap: 10, marginBottom: 10 }}>
                                <div style={{ width: 30, height: 30, borderRadius: 8, background: '#F0FDFA', display: 'flex', alignItems: 'center', justifyContent: 'center', fontSize: 14, color: '#0D9488', flexShrink: 0 }}>
                                    <i className={`ti ${row.icon}`} />
                                </div>
                                <div>
                                    <div style={{ fontSize: 10, color: '#94A3B8', marginBottom: 1 }}>{row.label}</div>
                                    <div style={{ fontSize: 12.5, fontWeight: 600, color: '#0F172A' }}>{row.value}</div>
                                </div>
                            </div>
                        ))}
                    </div>

                    {/* Business account */}
                    {biz && (
                        <div style={{ background: '#F5F3FF', border: '1px solid #DDD6FE', borderRadius: 14, padding: '16px', marginBottom: 16 }}>
                            <div style={{ display: 'flex', alignItems: 'center', justifyContent: 'space-between', marginBottom: 12 }}>
                                <div style={{ fontSize: 11, fontWeight: 700, color: '#7C3AED', textTransform: 'uppercase', letterSpacing: 0.8 }}>حساب الأعمال</div>
                                {bizStatus && (
                                    <span style={{ fontSize: 10, fontWeight: 700, padding: '3px 9px', borderRadius: 20, background: bizStatus.bg, color: bizStatus.color }}>
                                        {bizStatus.label}
                                    </span>
                                )}
                            </div>

                            <div style={{ display: 'flex', alignItems: 'center', gap: 10, marginBottom: 12 }}>
                                {biz.image ? (
                                    <img src={`/storage/${biz.image}`} alt={biz.name} style={{ width: 44, height: 44, borderRadius: 10, objectFit: 'cover', flexShrink: 0 }} />
                                ) : (
                                    <div style={{ width: 44, height: 44, borderRadius: 10, background: '#EDE9FE', display: 'flex', alignItems: 'center', justifyContent: 'center', fontSize: 18, color: '#7C3AED', flexShrink: 0 }}>
                                        <i className="ti ti-briefcase" />
                                    </div>
                                )}
                                <div style={{ minWidth: 0 }}>
                                    <div style={{ fontSize: 13, fontWeight: 700, color: '#1E1B4B', overflow: 'hidden', textOverflow: 'ellipsis', whiteSpace: 'nowrap' }}>{biz.name ?? 'حساب أعمال'}</div>
                                    {biz.name_job && <div style={{ fontSize: 11, color: '#6D28D9', marginTop: 1 }}>{biz.name_job}</div>}
                                </div>
                            </div>

                            {[
                                { icon: 'ti-phone',       label: 'رقم التواصل', value: biz.number },
                                { icon: 'ti-category',    label: 'النشاط',       value: biz.activity },
                                { icon: 'ti-map-pin',     label: 'الموقع',       value: [biz.city, biz.area, biz.street].filter(Boolean).join('، ') || null },
                                { icon: 'ti-calendar-plus', label: 'تاريخ الإنشاء', value: formatDate(biz.created_at) },
                            ].filter(r => r.value).map(r => (
                                <div key={r.label} style={{ display: 'flex', alignItems: 'center', gap: 10, marginBottom: 8 }}>
                                    <div style={{ width: 26, height: 26, borderRadius: 7, background: '#EDE9FE', display: 'flex', alignItems: 'center', justifyContent: 'center', fontSize: 12, color: '#7C3AED', flexShrink: 0 }}>
                                        <i className={`ti ${r.icon}`} />
                                    </div>
                                    <div style={{ minWidth: 0 }}>
                                        <div style={{ fontSize: 10, color: '#94A3B8' }}>{r.label}</div>
                                        <div style={{ fontSize: 12, fontWeight: 600, color: '#1E1B4B' }}>{r.value}</div>
                                    </div>
                                </div>
                            ))}

                            {biz.description && (
                                <div style={{ marginTop: 10, paddingTop: 10, borderTop: '1px solid #DDD6FE' }}>
                                    <div style={{ fontSize: 10, color: '#94A3B8', marginBottom: 3 }}>الوصف</div>
                                    <div style={{ fontSize: 12, color: '#374151', lineHeight: 1.6 }}>{biz.description}</div>
                                </div>
                            )}
                        </div>
                    )}

                    {/* Services */}
                    {(user.services ?? []).length > 0 && (
                        <div style={{ background: '#F8FAFC', borderRadius: 14, padding: '16px', marginBottom: 16 }}>
                            <div style={{ fontSize: 11, fontWeight: 700, color: '#94A3B8', textTransform: 'uppercase', letterSpacing: 0.8, marginBottom: 12 }}>
                                الخدمات ({user.services.length})
                            </div>
                            {user.services.map((s, i) => {
                                const ssc = SERVICE_STATUS_CFG[s.status] ?? DEFAULT_SERVICE_STATUS;
                                const price = priceLabel(s);
                                return (
                                    <div key={s.id} style={{
                                        display: 'flex', gap: 10,
                                        padding: i === 0 ? '0 0 12px' : '12px 0',
                                        borderTop: i > 0 ? '1px solid rgba(0,0,0,0.06)' : 'none',
                                    }}>
                                        {s.image ? (
                                            <img src={`/storage/${s.image}`} alt={s.name} style={{ width: 38, height: 38, borderRadius: 9, objectFit: 'cover', flexShrink: 0 }} />
                                        ) : (
                                            <div style={{ width: 38, height: 38, borderRadius: 9, background: '#F0FDFA', display: 'flex', alignItems: 'center', justifyContent: 'center', fontSize: 15, color: '#0D9488', flexShrink: 0 }}>
                                                <i className="ti ti-tool" />
                                            </div>
                                        )}
                                        <div style={{ flex: 1, minWidth: 0 }}>
                                            <div style={{ display: 'flex', alignItems: 'center', justifyContent: 'space-between', gap: 6 }}>
                                                <span style={{ fontSize: 12.5, fontWeight: 700, color: '#0F172A', overflow: 'hidden', textOverflow: 'ellipsis', whiteSpace: 'nowrap' }}>{s.name}</span>
                                                <span style={{ fontSize: 9.5, fontWeight: 700, padding: '2px 8px', borderRadius: 20, background: ssc.bg, color: ssc.color, flexShrink: 0 }}>{ssc.label}</span>
                                            </div>
                                            <div style={{ fontSize: 11, color: '#94A3B8', marginTop: 2, overflow: 'hidden', textOverflow: 'ellipsis', whiteSpace: 'nowrap' }}>
                                                {[s.category?.name, s.subcategory?.name, s.city?.name].filter(Boolean).join(' · ') || '—'}
                                            </div>
                                            <div style={{ display: 'flex', alignItems: 'center', gap: 8, marginTop: 3 }}>
                                                {price && <span style={{ fontSize: 11.5, color: '#0D9488', fontWeight: 700 }}>{price}</span>}
                                                {!s.is_active && (
                                                    <span style={{ fontSize: 9.5, color: '#94A3B8', fontWeight: 600 }}>غير مفعّلة</span>
                                                )}
                                            </div>
                                        </div>
                                    </div>
                                );
                            })}
                        </div>
                    )}
                </div>

                {/* Drawer footer */}
                <div style={{ padding: '16px 22px', borderTop: '0.5px solid rgba(0,0,0,0.07)', display: 'flex', gap: 10, flexShrink: 0 }}>
                    <button onClick={onClose} style={{ flex: 1, padding: '10px', borderRadius: 10, border: '1px solid rgba(0,0,0,0.12)', background: '#F8FAFC', color: '#374151', fontSize: 13, fontWeight: 600, cursor: 'pointer', fontFamily: "'Cairo','Inter',sans-serif" }}>
                        إغلاق
                    </button>
                    {isBlocked ? (
                        <button onClick={() => onUnblock(user.id)} style={{ flex: 1, padding: '10px', borderRadius: 10, border: '1px solid #6EE7B7', background: '#D1FAE5', color: '#065F46', fontSize: 13, fontWeight: 700, cursor: 'pointer', fontFamily: "'Cairo','Inter',sans-serif", display: 'flex', alignItems: 'center', justifyContent: 'center', gap: 6 }}>
                            <i className="ti ti-lock-open" /> إلغاء الحظر
                        </button>
                    ) : (
                        <button onClick={() => onBlock(user.id)} style={{ flex: 1, padding: '10px', borderRadius: 10, border: '1px solid #FED7AA', background: '#FFEDD5', color: '#9A3412', fontSize: 13, fontWeight: 700, cursor: 'pointer', fontFamily: "'Cairo','Inter',sans-serif", display: 'flex', alignItems: 'center', justifyContent: 'center', gap: 6 }}>
                            <i className="ti ti-ban" /> حظر المستخدم
                        </button>
                    )}
                    <button onClick={() => onDelete(user.id)} style={{ flex: 1, padding: '10px', borderRadius: 10, border: '1px solid #FCA5A5', background: '#FEE2E2', color: '#991B1B', fontSize: 13, fontWeight: 700, cursor: 'pointer', fontFamily: "'Cairo','Inter',sans-serif", display: 'flex', alignItems: 'center', justifyContent: 'center', gap: 6 }}>
                        <i className="ti ti-trash" /> حذف المستخدم
                    </button>
                </div>
            </div>
        </>
    );
}

/* ════════════════ Main Page ════════════════ */
export default function Users({ users }) {
    const [search,      setSearch]      = useState('');
    const [statusFilter, setStatusFilter] = useState('all');
    const [selected,    setSelected]    = useState(null);
    const [selectedIdx, setSelectedIdx] = useState(0);

    const allUsers = users ?? [];

    const filtered = allUsers.filter(u => {
        const matchSearch = `${u.first_name} ${u.last_name} ${u.email} ${u.phone ?? ''} ${u.city ?? ''}`.toLowerCase().includes(search.toLowerCase());
        const matchStatus = statusFilter === 'all' || (u.status ?? 'active') === statusFilter;
        return matchSearch && matchStatus;
    });

    const counts = {
        all:      allUsers.length,
        active:   allUsers.filter(u => !u.status || u.status === 'active').length,
        inactive: allUsers.filter(u => u.status === 'inactive').length,
    };

    const destroy = (id) => {
        if (!confirm('حذف هذا المستخدم نهائياً؟')) return;
        setSelected(null);
        router.delete(`/super-admin/users/${id}`, { preserveScroll: true });
    };

    const block = (id) => {
        if (!confirm('حظر هذا المستخدم؟')) return;
        router.patch(`/super-admin/users/${id}/block`, {}, { preserveScroll: true });
    };

    const unblock = (id) => {
        if (!confirm('إلغاء حظر هذا المستخدم؟')) return;
        router.patch(`/super-admin/users/${id}/unblock`, {}, { preserveScroll: true });
    };

    const openUser = (u, idx) => { setSelected(u); setSelectedIdx(idx); };

    const TABS = [
        { key: 'all',      label: 'الكل',  color: '#1E1B4B', bg: '#EEF2FF', border: '#C7D2FE', icon: 'ti-users' },
        { key: 'active',   label: 'نشط',   color: '#065F46', bg: '#D1FAE5', border: '#6EE7B7', icon: 'ti-circle-check' },
        { key: 'inactive', label: 'محظور', color: '#991B1B', bg: '#FEE2E2', border: '#FCA5A5', icon: 'ti-ban' },
    ];

    return (
        <SuperAdminLayout title="المستخدمون">
            <Head title="المستخدمون — Skillify" />

            {selected && (
                <UserDrawer
                    user={selected}
                    index={selectedIdx}
                    onClose={() => setSelected(null)}
                    onDelete={destroy}
                    onBlock={block}
                    onUnblock={unblock}
                />
            )}

            {/* Header */}
            <div style={{ display: 'flex', alignItems: 'flex-start', justifyContent: 'space-between', flexWrap: 'wrap', gap: 12 }}>
                <div>
                    <h1 style={{ fontSize: 22, fontWeight: 800, color: '#1E1B4B', margin: 0, letterSpacing: -0.5 }}>جميع المستخدمين</h1>
                    <p style={{ fontSize: 12, color: '#94A3B8', marginTop: 4 }}>{filtered.length} من {allUsers.length} مستخدم</p>
                </div>
                <div style={{ position: 'relative', minWidth: 280 }}>
                    <i className="ti ti-search" style={{ position: 'absolute', top: '50%', right: 12, transform: 'translateY(-50%)', color: '#94A3B8', fontSize: 15, pointerEvents: 'none' }} />
                    <input value={search} onChange={e => setSearch(e.target.value)} placeholder="بحث بالاسم أو البريد أو الهاتف أو المدينة..."
                        style={{ width: '100%', padding: '9px 38px 9px 13px', border: '1px solid rgba(0,0,0,0.11)', borderRadius: 9, fontSize: 13, outline: 'none', boxSizing: 'border-box', fontFamily: "'Cairo','Inter',sans-serif", background: '#FAFAFA' }} />
                </div>
            </div>

            {/* Status tabs */}
            <div style={{ display: 'flex', gap: 8, flexWrap: 'wrap' }}>
                {TABS.map(t => (
                    <button key={t.key} onClick={() => setStatusFilter(t.key)} style={{
                        display: 'inline-flex', alignItems: 'center', gap: 6, padding: '7px 16px', borderRadius: 24,
                        border: `1px solid ${statusFilter === t.key ? t.color : 'rgba(0,0,0,0.10)'}`,
                        background: statusFilter === t.key ? t.bg : '#fff',
                        color: statusFilter === t.key ? t.color : '#64748B',
                        fontSize: 12.5, fontWeight: statusFilter === t.key ? 700 : 500, cursor: 'pointer',
                        fontFamily: "'Cairo','Inter',sans-serif", transition: 'all 0.13s',
                    }}>
                        <i className={`ti ${t.icon}`} style={{ fontSize: 13 }} />
                        {t.label}
                        <span style={{ background: statusFilter === t.key ? 'rgba(0,0,0,0.10)' : '#F1F5F9', color: statusFilter === t.key ? t.color : '#64748B', borderRadius: 20, padding: '0 7px', fontSize: 11, fontWeight: 700 }}>
                            {counts[t.key]}
                        </span>
                    </button>
                ))}
            </div>

            {/* Table */}
            <div style={{ background: '#fff', border: '1px solid rgba(0,0,0,0.07)', borderRadius: 16, overflow: 'hidden', boxShadow: '0 2px 12px rgba(0,0,0,0.04)' }}>
                <div style={{ overflowX: 'auto' }}>
                <table style={{ width: '100%', borderCollapse: 'collapse', fontSize: 13 }}>
                    <thead>
                        <tr style={{ background: 'linear-gradient(135deg,#F8FAFC,#F1F5F9)', borderBottom: '1px solid rgba(0,0,0,0.07)' }}>
                            {['#', 'المستخدم', 'البريد الإلكتروني', 'الهاتف', 'المدينة', 'الحالة', 'التسجيل', ''].map(h => (
                                <th key={h} style={{ padding: '12px 14px', textAlign: 'right', fontWeight: 700, color: '#374151', whiteSpace: 'nowrap', fontSize: 12 }}>{h}</th>
                            ))}
                        </tr>
                    </thead>
                    <tbody>
                        {!filtered.length ? (
                            <tr>
                                <td colSpan={8} style={{ padding: '64px 24px', textAlign: 'center', color: '#94A3B8' }}>
                                    <i className="ti ti-users-off" style={{ fontSize: 48, display: 'block', opacity: 0.12, marginBottom: 14 }} />
                                    <div style={{ fontSize: 14, fontWeight: 600, color: '#64748B', marginBottom: 6 }}>لا يوجد مستخدمون</div>
                                    <p style={{ fontSize: 13, margin: 0 }}>جرّب تغيير الفلتر أو كلمة البحث.</p>
                                </td>
                            </tr>
                        ) : filtered.map((u, i) => {
                            const sc  = STATUS_CFG[u.status] ?? DEFAULT_STATUS;
                            const av  = avColor(i);
                            const av2 = avColor2(i);
                            return (
                                <tr key={u.id}
                                    style={{ borderBottom: '0.5px solid rgba(0,0,0,0.05)', cursor: 'pointer', transition: 'background 0.12s' }}
                                    onClick={() => openUser(u, i)}
                                    onMouseEnter={e => e.currentTarget.style.background = '#F5F3FF'}
                                    onMouseLeave={e => e.currentTarget.style.background = 'transparent'}
                                >
                                    {/* Index */}
                                    <td style={{ padding: '13px 14px', color: '#94A3B8', fontSize: 12, fontWeight: 500 }}>{i + 1}</td>

                                    {/* User */}
                                    <td style={{ padding: '13px 14px' }}>
                                        <div style={{ display: 'flex', alignItems: 'center', gap: 10 }}>
                                            <div style={{
                                                width: 36, height: 36, borderRadius: '50%', flexShrink: 0,
                                                background: `linear-gradient(135deg,${av},${av2})`,
                                                display: 'flex', alignItems: 'center', justifyContent: 'center',
                                                fontSize: 13, fontWeight: 700, color: '#fff',
                                            }}>
                                                {initials(u)}
                                            </div>
                                            <div>
                                                <div style={{ fontSize: 13, fontWeight: 700, color: '#0F172A', whiteSpace: 'nowrap' }}>
                                                    {u.first_name} {u.last_name}
                                                </div>
                                                {u.businesses && (
                                                    <div style={{ fontSize: 10, color: '#7C3AED', marginTop: 2, display: 'flex', alignItems: 'center', gap: 3 }}>
                                                        <i className="ti ti-briefcase" style={{ fontSize: 10 }} /> حساب أعمال
                                                    </div>
                                                )}
                                            </div>
                                        </div>
                                    </td>

                                    {/* Email */}
                                    <td style={{ padding: '13px 14px', color: '#475569', fontSize: 12, maxWidth: 180, overflow: 'hidden', textOverflow: 'ellipsis', whiteSpace: 'nowrap' }}>
                                        {u.email}
                                    </td>

                                    {/* Phone */}
                                    <td style={{ padding: '13px 14px', color: '#475569', fontSize: 12, whiteSpace: 'nowrap' }}>
                                        {u.phone ?? '—'}
                                    </td>

                                    {/* City */}
                                    <td style={{ padding: '13px 14px', color: '#64748B', fontSize: 12, whiteSpace: 'nowrap' }}>
                                        {u.city ? (
                                            <span style={{ display: 'flex', alignItems: 'center', gap: 4 }}>
                                                <i className="ti ti-map-pin" style={{ fontSize: 12, color: '#94A3B8' }} />{u.city}
                                            </span>
                                        ) : '—'}
                                    </td>

                                    {/* Status */}
                                    <td style={{ padding: '13px 14px' }}>
                                        <span style={{ display: 'inline-flex', alignItems: 'center', gap: 5, fontSize: 11, fontWeight: 700, padding: '4px 10px', borderRadius: 20, background: sc.bg, color: sc.color, border: `1px solid ${sc.border}` }}>
                                            <span style={{ width: 5, height: 5, borderRadius: '50%', background: sc.dot, display: 'inline-block' }} />
                                            {sc.label}
                                        </span>
                                    </td>

                                    {/* Joined */}
                                    <td style={{ padding: '13px 14px', color: '#94A3B8', fontSize: 11, whiteSpace: 'nowrap' }}>
                                        {formatDate(u.created_at)}
                                    </td>

                                    {/* View details */}
                                    <td style={{ padding: '13px 14px' }}>
                                        <div style={{ display: 'flex', alignItems: 'center', gap: 5 }}>
                                            <button
                                                onClick={e => { e.stopPropagation(); openUser(u, i); }}
                                                style={{ display: 'inline-flex', alignItems: 'center', gap: 5, padding: '5px 10px', borderRadius: 7, border: '1px solid #DDD6FE', background: '#F5F3FF', color: '#7C3AED', fontSize: 11, fontWeight: 600, cursor: 'pointer', fontFamily: "'Cairo','Inter',sans-serif", whiteSpace: 'nowrap' }}>
                                                <i className="ti ti-eye" style={{ fontSize: 12 }} /> عرض
                                            </button>
                                            {u.status === 'inactive' ? (
                                                <button
                                                    title="إلغاء الحظر"
                                                    onClick={e => { e.stopPropagation(); unblock(u.id); }}
                                                    style={{ width: 28, height: 28, borderRadius: 7, border: '1px solid #6EE7B7', background: '#ECFDF5', color: '#059669', fontSize: 13, cursor: 'pointer', display: 'flex', alignItems: 'center', justifyContent: 'center', transition: 'all 0.13s' }}
                                                    onMouseEnter={e => { e.currentTarget.style.background = '#D1FAE5'; e.currentTarget.style.transform = 'scale(1.1)'; }}
                                                    onMouseLeave={e => { e.currentTarget.style.background = '#ECFDF5'; e.currentTarget.style.transform = 'scale(1)'; }}>
                                                    <i className="ti ti-lock-open" />
                                                </button>
                                            ) : (
                                                <button
                                                    title="حظر"
                                                    onClick={e => { e.stopPropagation(); block(u.id); }}
                                                    style={{ width: 28, height: 28, borderRadius: 7, border: '1px solid #FED7AA', background: '#FFF7ED', color: '#C2410C', fontSize: 13, cursor: 'pointer', display: 'flex', alignItems: 'center', justifyContent: 'center', transition: 'all 0.13s' }}
                                                    onMouseEnter={e => { e.currentTarget.style.background = '#FFEDD5'; e.currentTarget.style.transform = 'scale(1.1)'; }}
                                                    onMouseLeave={e => { e.currentTarget.style.background = '#FFF7ED'; e.currentTarget.style.transform = 'scale(1)'; }}>
                                                    <i className="ti ti-ban" />
                                                </button>
                                            )}
                                            <button
                                                onClick={e => { e.stopPropagation(); destroy(u.id); }}
                                                style={{ width: 28, height: 28, borderRadius: 7, border: '1px solid #FCA5A5', background: '#FEF2F2', color: '#DC2626', fontSize: 13, cursor: 'pointer', display: 'flex', alignItems: 'center', justifyContent: 'center', transition: 'all 0.13s' }}
                                                onMouseEnter={e => { e.currentTarget.style.background = '#FEE2E2'; e.currentTarget.style.transform = 'scale(1.1)'; }}
                                                onMouseLeave={e => { e.currentTarget.style.background = '#FEF2F2'; e.currentTarget.style.transform = 'scale(1)'; }}>
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
            </div>
        </SuperAdminLayout>
    );
}
