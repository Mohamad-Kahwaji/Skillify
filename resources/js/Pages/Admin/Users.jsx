import { Head, router } from '@inertiajs/react';
import { useState } from 'react';
import AdminLayout, { C } from '../../Layouts/AdminLayout';
import { storageUrl } from '../../utils/image';

const AV = ['#0EA5E9','#0D9488','#8B5CF6','#F59E0B','#10B981','#EF4444','#EC4899','#2563EB'];
function avColor(i)  { return AV[i % AV.length]; }
function avColor2(i) { return AV[(i + 3) % AV.length]; }

function initials(u) {
    return `${u.first_name?.[0] ?? ''}${u.last_name?.[0] ?? ''}`.toUpperCase() || 'U';
}

function fmtDate(d) {
    if (!d) return '—';
    return new Date(d).toLocaleDateString('ar', { day: 'numeric', month: 'short', year: 'numeric' });
}

const STATUS_CFG = {
    active:   { label: 'نشط',   color: '#065F46', bg: '#D1FAE5', border: '#6EE7B7', dot: '#10B981' },
    inactive: { label: 'موقوف', color: '#991B1B', bg: '#FEE2E2', border: '#FCA5A5', dot: '#EF4444' },
    banned:   { label: 'محظور', color: '#7C2D12', bg: '#FFEDD5', border: '#FED7AA', dot: '#F97316' },
};
const DEFAULT_SC = STATUS_CFG.active;

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

/* ── User Detail Drawer ─────────────────────────────────────── */
export function UserDrawer({ user, index, onClose, onToggle, onDelete }) {
    if (!user) return null;
    const sc  = STATUS_CFG[user.status] ?? DEFAULT_SC;
    const biz = user.businesses;
    const bizSc = biz
        ? biz.status === 'active'
            ? { label: 'نشط',           color: '#065F46', bg: '#D1FAE5' }
            : biz.status === 'pending'
            ? { label: 'قيد المراجعة', color: '#92400E', bg: '#FEF3C7' }
            : { label: 'مرفوض',         color: '#991B1B', bg: '#FEE2E2' }
        : null;

    return (
        <div onClick={onClose} style={{ position: 'fixed', inset: 0, background: 'rgba(15,23,42,0.55)', zIndex: 100, backdropFilter: 'blur(4px)', display: 'flex', alignItems: 'center', justifyContent: 'center', padding: 20 }}>

            <div onClick={e => e.stopPropagation()} style={{
                width: '100%', maxWidth: 700, maxHeight: '92vh',
                background: '#fff', borderRadius: 24, zIndex: 101,
                boxShadow: '0 32px 80px rgba(0,0,0,0.35)',
                display: 'flex', flexDirection: 'column', overflow: 'hidden',
                animation: 'modalPopIn 0.22s cubic-bezier(.22,.68,0,1.2)',
            }}>
                <style>{`@keyframes modalPopIn{from{transform:scale(0.96) translateY(10px);opacity:0}to{transform:scale(1) translateY(0);opacity:1}}`}</style>

                {/* Hero header */}
                <div style={{ position: 'relative', flexShrink: 0, background: `linear-gradient(135deg,${C.teal},#134E4A 60%,#052e16)`, padding: '22px 26px 52px', overflow: 'visible' }}>
                    <div style={{ position: 'absolute', inset: 0, borderRadius: 'inherit', overflow: 'hidden', backgroundImage: 'linear-gradient(rgba(255,255,255,0.05) 1px,transparent 1px),linear-gradient(90deg,rgba(255,255,255,0.05) 1px,transparent 1px)', backgroundSize: '28px 28px' }} />
                    <div style={{ position: 'relative', display: 'flex', alignItems: 'center', justifyContent: 'space-between' }}>
                        <span style={{ fontSize: 15, fontWeight: 700, color: 'rgba(255,255,255,0.9)' }}>تفاصيل المستخدم</span>
                        <button onClick={onClose} style={{ width: 34, height: 34, borderRadius: 10, border: '1.5px solid rgba(255,255,255,0.22)', background: 'rgba(255,255,255,0.1)', color: '#fff', fontSize: 16, cursor: 'pointer', display: 'flex', alignItems: 'center', justifyContent: 'center' }}>
                            <i className="ti ti-x" />
                        </button>
                    </div>

                    {/* Avatar, straddling the hero's bottom edge */}
                    <div style={{
                        position: 'absolute', bottom: -46, left: '50%', transform: 'translateX(-50%)',
                        width: 92, height: 92, borderRadius: '50%',
                        background: `linear-gradient(135deg,${avColor(index)},${avColor2(index)})`,
                        border: '4px solid #fff',
                        display: 'flex', alignItems: 'center', justifyContent: 'center',
                        fontSize: 32, fontWeight: 800, color: '#fff',
                        boxShadow: '0 10px 28px rgba(0,0,0,0.25)',
                    }}>
                        {initials(user)}
                    </div>
                </div>

                {/* Name + status, fixed below the hero (clears the overlapping avatar) */}
                <div style={{ flexShrink: 0, display: 'flex', flexDirection: 'column', alignItems: 'center', paddingTop: 56, paddingBottom: 18 }}>
                    <div style={{ fontSize: 21, fontWeight: 800, color: C.textDark, textAlign: 'center' }}>
                        {user.first_name} {user.middle_name ? user.middle_name + ' ' : ''}{user.last_name}
                    </div>
                    <div style={{ fontSize: 12.5, color: C.textFaint, marginTop: 4 }}>#{user.id}</div>
                    <span style={{ marginTop: 10, display: 'inline-flex', alignItems: 'center', gap: 5, fontSize: 12, fontWeight: 700, padding: '5px 14px', borderRadius: 20, background: sc.bg, color: sc.color, border: `1px solid ${sc.border}` }}>
                        <span style={{ width: 6, height: 6, borderRadius: '50%', background: sc.dot }} />{sc.label}
                    </span>
                </div>

                {/* Body */}
                <div style={{ flex: 1, overflowY: 'auto', padding: '0 30px 26px' }}>

                    {/* Stats */}
                    <div style={{ display: 'grid', gridTemplateColumns: '1fr 1fr 1fr', gap: 12, marginBottom: 24 }}>
                        {[
                            { icon: 'ti-file-text', label: 'المنشورات', value: user.posts_count    ?? 0, color: '#7C3AED', bg: '#F5F3FF' },
                            { icon: 'ti-tool',      label: 'الخدمات',   value: user.services_count ?? 0, color: C.teal,   bg: '#F0FDFA' },
                            { icon: 'ti-message',   label: 'التعليقات', value: user.comments_count ?? 0, color: C.primary, bg: '#EFF6FF' },
                        ].map(s => (
                            <div key={s.label} style={{ background: s.bg, borderRadius: 14, padding: '16px 10px', textAlign: 'center' }}>
                                <i className={`ti ${s.icon}`} style={{ fontSize: 21, color: s.color, display: 'block', marginBottom: 6 }} />
                                <div style={{ fontSize: 22, fontWeight: 800, color: C.textDark }}>{s.value}</div>
                                <div style={{ fontSize: 11.5, color: '#64748B', marginTop: 3 }}>{s.label}</div>
                            </div>
                        ))}
                    </div>

                    {/* Contact + Personal, side by side */}
                    <div style={{ display: 'grid', gridTemplateColumns: '1fr 1fr', gap: 14, marginBottom: 18 }}>
                        <div style={{ background: '#F8FAFC', borderRadius: 14, padding: 16 }}>
                            <div style={{ fontSize: 11.5, fontWeight: 700, color: C.textFaint, letterSpacing: 0.8, marginBottom: 12 }}>معلومات التواصل</div>
                            {[
                                { icon: 'ti-mail',    label: 'البريد الإلكتروني', value: user.email },
                                { icon: 'ti-phone',   label: 'رقم الهاتف',         value: user.phone ?? '—' },
                                { icon: 'ti-map-pin', label: 'المدينة',            value: user.city  ?? '—' },
                            ].map(r => (
                                <div key={r.label} style={{ display: 'flex', alignItems: 'center', gap: 10, marginBottom: 12 }}>
                                    <div style={{ width: 32, height: 32, borderRadius: 9, background: '#EFF6FF', display: 'flex', alignItems: 'center', justifyContent: 'center', fontSize: 15, color: C.primary, flexShrink: 0 }}>
                                        <i className={`ti ${r.icon}`} />
                                    </div>
                                    <div style={{ minWidth: 0 }}>
                                        <div style={{ fontSize: 10.5, color: C.textFaint, marginBottom: 1 }}>{r.label}</div>
                                        <div style={{ fontSize: 13, fontWeight: 600, color: C.textDark, wordBreak: 'break-all' }}>{r.value}</div>
                                    </div>
                                </div>
                            ))}
                        </div>

                        <div style={{ background: '#F8FAFC', borderRadius: 14, padding: 16 }}>
                            <div style={{ fontSize: 11.5, fontWeight: 700, color: C.textFaint, letterSpacing: 0.8, marginBottom: 12 }}>المعلومات الشخصية</div>
                            {[
                                { icon: 'ti-gender-bigender', label: 'الجنس',          value: GENDER_LABEL[user.gender] ?? '—' },
                                { icon: 'ti-cake',            label: 'تاريخ الميلاد', value: fmtDate(user.birthdate) },
                                { icon: 'ti-calendar-plus',   label: 'تاريخ التسجيل', value: fmtDate(user.created_at) },
                            ].map(r => (
                                <div key={r.label} style={{ display: 'flex', alignItems: 'center', gap: 10, marginBottom: 12 }}>
                                    <div style={{ width: 32, height: 32, borderRadius: 9, background: '#F0FDFA', display: 'flex', alignItems: 'center', justifyContent: 'center', fontSize: 15, color: C.teal, flexShrink: 0 }}>
                                        <i className={`ti ${r.icon}`} />
                                    </div>
                                    <div>
                                        <div style={{ fontSize: 10.5, color: C.textFaint, marginBottom: 1 }}>{r.label}</div>
                                        <div style={{ fontSize: 13, fontWeight: 600, color: C.textDark }}>{r.value}</div>
                                    </div>
                                </div>
                            ))}
                        </div>
                    </div>

                    {/* Business */}
                    {!biz && (
                        <div style={{ background: '#F8FAFC', border: '1px dashed rgba(0,0,0,0.10)', borderRadius: 14, padding: '22px 16px', marginBottom: 14, textAlign: 'center' }}>
                            <i className="ti ti-briefcase" style={{ fontSize: 26, color: '#CBD5E1', display: 'block', marginBottom: 8 }} />
                            <div style={{ fontSize: 12.5, fontWeight: 600, color: '#64748B' }}>لا يمتلك حساب أعمال</div>
                        </div>
                    )}
                    {biz && (
                        <div style={{ background: '#F0FDFA', border: `1px solid ${C.successBorder}`, borderRadius: 14, padding: 16, marginBottom: 14 }}>
                            <div style={{ display: 'flex', alignItems: 'center', justifyContent: 'space-between', marginBottom: 12 }}>
                                <div style={{ fontSize: 11, fontWeight: 700, color: C.teal, letterSpacing: 0.8 }}>حساب الأعمال</div>
                                {bizSc && (
                                    <span style={{ fontSize: 10, fontWeight: 700, padding: '3px 9px', borderRadius: 20, background: bizSc.bg, color: bizSc.color }}>
                                        {bizSc.label}
                                    </span>
                                )}
                            </div>

                            <div style={{ display: 'flex', alignItems: 'center', gap: 10, marginBottom: 12 }}>
                                {biz.image ? (
                                    <img src={storageUrl(biz.image)} alt={biz.name} style={{ width: 44, height: 44, borderRadius: 10, objectFit: 'cover', flexShrink: 0 }} />
                                ) : (
                                    <div style={{ width: 44, height: 44, borderRadius: 10, background: '#CCFBF1', display: 'flex', alignItems: 'center', justifyContent: 'center', fontSize: 18, color: C.teal, flexShrink: 0 }}>
                                        <i className="ti ti-briefcase" />
                                    </div>
                                )}
                                <div style={{ minWidth: 0 }}>
                                    <div style={{ fontSize: 13, fontWeight: 700, color: C.textDark, overflow: 'hidden', textOverflow: 'ellipsis', whiteSpace: 'nowrap' }}>{biz.name ?? 'حساب أعمال'}</div>
                                    {biz.name_job && <div style={{ fontSize: 11, color: C.teal, marginTop: 1 }}>{biz.name_job}</div>}
                                </div>
                            </div>

                            {[
                                { icon: 'ti-phone',         label: 'رقم التواصل',   value: biz.number },
                                { icon: 'ti-category',      label: 'النشاط',        value: biz.activity },
                                { icon: 'ti-map-pin',       label: 'الموقع',        value: [biz.city, biz.area, biz.street].filter(Boolean).join('، ') || null },
                                { icon: 'ti-calendar-plus', label: 'تاريخ الإنشاء', value: fmtDate(biz.created_at) },
                            ].filter(r => r.value).map(r => (
                                <div key={r.label} style={{ display: 'flex', alignItems: 'center', gap: 10, marginBottom: 8 }}>
                                    <div style={{ width: 26, height: 26, borderRadius: 7, background: '#CCFBF1', display: 'flex', alignItems: 'center', justifyContent: 'center', fontSize: 12, color: C.teal, flexShrink: 0 }}>
                                        <i className={`ti ${r.icon}`} />
                                    </div>
                                    <div style={{ minWidth: 0 }}>
                                        <div style={{ fontSize: 10, color: C.textFaint }}>{r.label}</div>
                                        <div style={{ fontSize: 12, fontWeight: 600, color: C.textDark }}>{r.value}</div>
                                    </div>
                                </div>
                            ))}

                            {biz.description && (
                                <div style={{ marginTop: 10, paddingTop: 10, borderTop: `1px solid ${C.successBorder}` }}>
                                    <div style={{ fontSize: 10, color: C.textFaint, marginBottom: 3 }}>الوصف</div>
                                    <div style={{ fontSize: 12, color: C.textMed, lineHeight: 1.6 }}>{biz.description}</div>
                                </div>
                            )}
                        </div>
                    )}

                    {/* Services */}
                    {(user.services ?? []).length === 0 && (
                        <div style={{ background: '#F8FAFC', border: '1px dashed rgba(0,0,0,0.10)', borderRadius: 14, padding: '22px 16px', marginBottom: 14, textAlign: 'center' }}>
                            <i className="ti ti-tool" style={{ fontSize: 26, color: '#CBD5E1', display: 'block', marginBottom: 8 }} />
                            <div style={{ fontSize: 12.5, fontWeight: 600, color: '#64748B' }}>لا توجد خدمات منشورة</div>
                        </div>
                    )}
                    {(user.services ?? []).length > 0 && (
                        <div style={{ background: '#F8FAFC', borderRadius: 14, padding: 16, marginBottom: 14 }}>
                            <div style={{ fontSize: 11, fontWeight: 700, color: C.textFaint, letterSpacing: 0.8, marginBottom: 12 }}>
                                الخدمات ({user.services.length})
                            </div>
                            {user.services.map((s, i) => {
                                const ssc = SERVICE_STATUS_CFG[s.status] ?? DEFAULT_SERVICE_STATUS;
                                const price = priceLabel(s);
                                return (
                                    <div key={s.id} style={{
                                        display: 'flex', gap: 10,
                                        padding: i === 0 ? '0 0 12px' : '12px 0',
                                        borderTop: i > 0 ? '1px solid rgba(15,23,42,0.06)' : 'none',
                                    }}>
                                        {s.image ? (
                                            <img src={`/storage/${s.image}`} alt={s.name} style={{ width: 38, height: 38, borderRadius: 9, objectFit: 'cover', flexShrink: 0 }} />
                                        ) : (
                                            <div style={{ width: 38, height: 38, borderRadius: 9, background: '#EFF6FF', display: 'flex', alignItems: 'center', justifyContent: 'center', fontSize: 15, color: C.primary, flexShrink: 0 }}>
                                                <i className="ti ti-tool" />
                                            </div>
                                        )}
                                        <div style={{ flex: 1, minWidth: 0 }}>
                                            <div style={{ display: 'flex', alignItems: 'center', justifyContent: 'space-between', gap: 6 }}>
                                                <span style={{ fontSize: 12.5, fontWeight: 700, color: C.textDark, overflow: 'hidden', textOverflow: 'ellipsis', whiteSpace: 'nowrap' }}>{s.name}</span>
                                                <span style={{ fontSize: 9.5, fontWeight: 700, padding: '2px 8px', borderRadius: 20, background: ssc.bg, color: ssc.color, flexShrink: 0 }}>{ssc.label}</span>
                                            </div>
                                            <div style={{ fontSize: 11, color: C.textFaint, marginTop: 2, overflow: 'hidden', textOverflow: 'ellipsis', whiteSpace: 'nowrap' }}>
                                                {[s.category?.name, s.subcategory?.name, s.city?.name].filter(Boolean).join(' · ') || '—'}
                                            </div>
                                            <div style={{ display: 'flex', alignItems: 'center', gap: 8, marginTop: 3 }}>
                                                {price && <span style={{ fontSize: 11.5, color: C.teal, fontWeight: 700 }}>{price}</span>}
                                                {!s.is_active && (
                                                    <span style={{ fontSize: 9.5, color: C.textFaint, fontWeight: 600 }}>غير مفعّلة</span>
                                                )}
                                            </div>
                                        </div>
                                    </div>
                                );
                            })}
                        </div>
                    )}
                </div>

                {/* Footer actions */}
                <div style={{ padding: '16px 22px', borderTop: '1px solid rgba(15,23,42,0.07)', display: 'flex', gap: 8, flexShrink: 0, flexWrap: 'wrap' }}>
                    <button onClick={onClose} style={{ flex: 1, minWidth: 80, padding: 10, borderRadius: 10, border: C.cardBorder, background: '#F8FAFC', color: C.textMed, fontSize: 13, fontWeight: 600, cursor: 'pointer', fontFamily: "'Cairo','Inter',sans-serif" }}>
                        إغلاق
                    </button>
                    <button onClick={() => onToggle(user)} style={{
                        flex: 1, minWidth: 80, padding: 10, borderRadius: 10, border: `1px solid ${user.status === 'active' ? '#FDE68A' : C.successBorder}`,
                        background: user.status === 'active' ? '#FFFBEB' : C.successBg,
                        color: user.status === 'active' ? '#78350F' : C.successText,
                        fontSize: 13, fontWeight: 700, cursor: 'pointer', fontFamily: "'Cairo','Inter',sans-serif",
                        display: 'flex', alignItems: 'center', justifyContent: 'center', gap: 6,
                    }}>
                        <i className={`ti ${user.status === 'active' ? 'ti-circle-x' : 'ti-circle-check'}`} />
                        {user.status === 'active' ? 'تعطيل' : 'تفعيل'}
                    </button>
                    <button onClick={() => onDelete(user.id)} style={{ flex: 1, minWidth: 80, padding: 10, borderRadius: 10, border: `1px solid ${C.dangerBorder}`, background: C.dangerBg, color: C.dangerText, fontSize: 13, fontWeight: 700, cursor: 'pointer', fontFamily: "'Cairo','Inter',sans-serif", display: 'flex', alignItems: 'center', justifyContent: 'center', gap: 6 }}>
                        <i className="ti ti-trash" /> حذف
                    </button>
                </div>
            </div>
        </div>
    );
}

/* ═══════════════════════════════════════════════════════════════ */
export default function Users({ users }) {
    const [search,       setSearch]       = useState('');
    const [statusFilter, setStatusFilter] = useState('all');
    const [selected,     setSelected]     = useState(null);
    const [selectedIdx,  setSelectedIdx]  = useState(0);

    const allUsers = users ?? [];

    const filtered = allUsers.filter(u => {
        const q = `${u.first_name} ${u.last_name} ${u.email} ${u.phone ?? ''} ${u.city ?? ''}`.toLowerCase();
        const ms = statusFilter === 'all' || (u.status ?? 'active') === statusFilter;
        return q.includes(search.toLowerCase()) && ms;
    });

    const counts = {
        all:      allUsers.length,
        active:   allUsers.filter(u => !u.status || u.status === 'active').length,
        inactive: allUsers.filter(u => u.status === 'inactive').length,
        banned:   allUsers.filter(u => u.status === 'banned').length,
    };

    const toggle = (u) => {
        const url = u.status === 'active' ? `/admin/users/${u.id}/deactivate` : `/admin/users/${u.id}/activate`;
        router.patch(url, {}, { preserveScroll: true, onSuccess: () => setSelected(null) });
    };

    const destroy = (id) => {
        if (!confirm('حذف هذا المستخدم نهائياً؟')) return;
        setSelected(null);
        router.delete(`/admin/users/${id}`, { preserveScroll: true });
    };

    const openUser = (u, idx) => { setSelected(u); setSelectedIdx(idx); };

    const TABS = [
        { key: 'all',      label: 'الكل',   icon: 'ti-users',        color: C.textDark, bg: '#F0F9FF',  border: '#BAE6FD' },
        { key: 'active',   label: 'نشط',    icon: 'ti-circle-check', color: '#065F46',  bg: '#D1FAE5', border: '#6EE7B7' },
        { key: 'inactive', label: 'موقوف',  icon: 'ti-circle-x',     color: '#991B1B',  bg: '#FEE2E2', border: '#FCA5A5' },
        { key: 'banned',   label: 'محظور',  icon: 'ti-ban',          color: '#7C2D12',  bg: '#FFEDD5', border: '#FED7AA' },
    ];

    return (
        <AdminLayout title="المستخدمون">
            <Head title="المستخدمون — Skillify" />

            {selected && (
                <UserDrawer
                    user={selected}
                    index={selectedIdx}
                    onClose={() => setSelected(null)}
                    onToggle={toggle}
                    onDelete={destroy}
                />
            )}

            {/* ── Header ── */}
            <div style={{ display: 'flex', alignItems: 'flex-start', justifyContent: 'space-between', flexWrap: 'wrap', gap: 12 }}>
                <div>
                    <h1 style={{ fontSize: 22, fontWeight: 800, color: C.textDark, margin: 0 }}>جميع المستخدمين</h1>
                    <p style={{ fontSize: 12, color: C.textFaint, marginTop: 4 }}>{filtered.length} من {allUsers.length} مستخدم</p>
                </div>
                <div style={{ position: 'relative', minWidth: 280 }}>
                    <i className="ti ti-search" style={{ position: 'absolute', top: '50%', right: 12, transform: 'translateY(-50%)', color: C.textFaint, fontSize: 15, pointerEvents: 'none' }} />
                    <input value={search} onChange={e => setSearch(e.target.value)} placeholder="بحث بالاسم أو البريد أو الهاتف أو المدينة..."
                        style={{ width: '100%', padding: '9px 38px 9px 13px', border: C.cardBorder, borderRadius: 9, fontSize: 13, outline: 'none', boxSizing: 'border-box', fontFamily: "'Cairo','Inter',sans-serif", background: '#fff', boxShadow: '0 1px 3px rgba(15,23,42,0.05)' }} />
                </div>
            </div>

            {/* ── Status tabs ── */}
            <div style={{ display: 'flex', gap: 8, flexWrap: 'wrap' }}>
                {TABS.map(t => (
                    <button key={t.key} onClick={() => setStatusFilter(t.key)} style={{
                        display: 'inline-flex', alignItems: 'center', gap: 6, padding: '7px 16px', borderRadius: 24,
                        border: `1px solid ${statusFilter === t.key ? t.border : 'rgba(0,0,0,0.10)'}`,
                        background: statusFilter === t.key ? t.bg : '#fff',
                        color: statusFilter === t.key ? t.color : '#64748B',
                        fontSize: 12, fontWeight: statusFilter === t.key ? 700 : 500, cursor: 'pointer',
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

            {/* ── Table ── */}
            <div style={{ background: '#fff', border: C.cardBorder, borderRadius: 16, overflow: 'hidden', boxShadow: C.cardShadow }}>
                <div style={{ overflowX: 'auto' }}>
                <table style={{ width: '100%', borderCollapse: 'collapse', fontSize: 13 }}>
                    <thead>
                        <tr style={{ background: 'linear-gradient(135deg,#F8FAFC,#F0F9FF)', borderBottom: '1px solid rgba(15,23,42,0.07)' }}>
                            {['#','المستخدم','البريد الإلكتروني','الهاتف','المدينة','الحالة','التسجيل',''].map(h => (
                                <th key={h} style={{ padding: '12px 14px', textAlign: 'right', fontWeight: 700, color: C.textMed, whiteSpace: 'nowrap', fontSize: 12 }}>{h}</th>
                            ))}
                        </tr>
                    </thead>
                    <tbody>
                        {!filtered.length ? (
                            <tr>
                                <td colSpan={8} style={{ padding: '72px 24px', textAlign: 'center', color: C.textFaint }}>
                                    <i className="ti ti-users-off" style={{ fontSize: 48, display: 'block', opacity: 0.12, marginBottom: 14 }} />
                                    <div style={{ fontSize: 14, fontWeight: 600, color: '#64748B', marginBottom: 6 }}>لا يوجد مستخدمون</div>
                                    <p style={{ fontSize: 13, margin: 0 }}>جرّب تغيير الفلتر أو كلمة البحث.</p>
                                </td>
                            </tr>
                        ) : filtered.map((u, i) => {
                            const sc = STATUS_CFG[u.status] ?? DEFAULT_SC;
                            return (
                                <tr key={u.id}
                                    style={{ borderBottom: '1px solid rgba(15,23,42,0.05)', cursor: 'pointer', transition: 'background 0.12s' }}
                                    onClick={() => openUser(u, i)}
                                    onMouseEnter={e => e.currentTarget.style.background = '#F0F9FF'}
                                    onMouseLeave={e => e.currentTarget.style.background = 'transparent'}
                                >
                                    <td style={{ padding: '13px 14px', color: C.textFaint, fontSize: 12 }}>{i + 1}</td>

                                    <td style={{ padding: '13px 14px' }}>
                                        <div style={{ display: 'flex', alignItems: 'center', gap: 10 }}>
                                            <div style={{ width: 36, height: 36, borderRadius: '50%', flexShrink: 0, background: `linear-gradient(135deg,${avColor(i)},${avColor2(i)})`, display: 'flex', alignItems: 'center', justifyContent: 'center', fontSize: 13, fontWeight: 700, color: '#fff' }}>
                                                {initials(u)}
                                            </div>
                                            <div>
                                                <div style={{ fontSize: 13, fontWeight: 700, color: C.textDark, whiteSpace: 'nowrap' }}>
                                                    {u.first_name} {u.last_name}
                                                </div>
                                                {u.businesses && (
                                                    <div style={{ fontSize: 10, color: C.teal, marginTop: 2, display: 'flex', alignItems: 'center', gap: 3 }}>
                                                        <i className="ti ti-briefcase" style={{ fontSize: 10 }} /> حساب أعمال
                                                    </div>
                                                )}
                                            </div>
                                        </div>
                                    </td>

                                    <td style={{ padding: '13px 14px', color: C.textMuted, fontSize: 12, maxWidth: 180, overflow: 'hidden', textOverflow: 'ellipsis', whiteSpace: 'nowrap' }}>{u.email}</td>

                                    <td style={{ padding: '13px 14px', color: C.textMuted, fontSize: 12, whiteSpace: 'nowrap' }}>{u.phone ?? '—'}</td>

                                    <td style={{ padding: '13px 14px', color: C.textMuted, fontSize: 12 }}>
                                        {u.city ? (
                                            <span style={{ display: 'flex', alignItems: 'center', gap: 4 }}>
                                                <i className="ti ti-map-pin" style={{ fontSize: 12, color: C.textFaint }} />{u.city}
                                            </span>
                                        ) : '—'}
                                    </td>

                                    <td style={{ padding: '13px 14px' }}>
                                        <span style={{ display: 'inline-flex', alignItems: 'center', gap: 5, fontSize: 11, fontWeight: 700, padding: '4px 10px', borderRadius: 20, background: sc.bg, color: sc.color, border: `1px solid ${sc.border}` }}>
                                            <span style={{ width: 5, height: 5, borderRadius: '50%', background: sc.dot }} />{sc.label}
                                        </span>
                                    </td>

                                    <td style={{ padding: '13px 14px', color: C.textFaint, fontSize: 11, whiteSpace: 'nowrap' }}>
                                        {fmtDate(u.created_at)}
                                    </td>

                                    <td style={{ padding: '13px 14px' }}>
                                        <div style={{ display: 'flex', alignItems: 'center', gap: 5 }}>
                                            <button
                                                onClick={e => { e.stopPropagation(); openUser(u, i); }}
                                                style={{ display: 'inline-flex', alignItems: 'center', gap: 5, padding: '5px 10px', borderRadius: 7, border: `1px solid ${C.successBorder}`, background: C.successBg, color: C.successText, fontSize: 11, fontWeight: 600, cursor: 'pointer', fontFamily: "'Cairo','Inter',sans-serif", whiteSpace: 'nowrap' }}>
                                                <i className="ti ti-eye" style={{ fontSize: 12 }} /> عرض
                                            </button>
                                            <button
                                                onClick={e => { e.stopPropagation(); destroy(u.id); }}
                                                style={{ width: 28, height: 28, borderRadius: 7, border: `1px solid ${C.dangerBorder}`, background: C.dangerBg, color: C.dangerText, fontSize: 13, cursor: 'pointer', display: 'flex', alignItems: 'center', justifyContent: 'center', transition: 'all 0.13s' }}
                                                onMouseEnter={e => { e.currentTarget.style.background = '#FEE2E2'; e.currentTarget.style.transform = 'scale(1.1)'; }}
                                                onMouseLeave={e => { e.currentTarget.style.background = C.dangerBg; e.currentTarget.style.transform = 'scale(1)'; }}>
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
        </AdminLayout>
    );
}

// ── Shared components (used by all admin pages) ───────────────
export function PageHeader({ title, sub, children }) {
    return (
        <div style={{ display: 'flex', alignItems: 'center', justifyContent: 'space-between', flexWrap: 'wrap', gap: 12 }}>
            <div>
                <div style={{ fontSize: 20, fontWeight: 800, color: C.textDark }}>{title}</div>
                {sub && <div style={{ fontSize: 12, color: C.textMuted, marginTop: 2 }}>{sub}</div>}
            </div>
            <div style={{ display: 'flex', gap: 8, alignItems: 'center' }}>{children}</div>
        </div>
    );
}

export function SearchInput({ value, onChange, placeholder }) {
    return (
        <input
            value={value} onChange={e => onChange(e.target.value)}
            placeholder={placeholder}
            style={{ padding: '8px 14px', border: C.cardBorder, borderRadius: 9, fontSize: 12, outline: 'none', width: 230, boxShadow: '0 1px 3px rgba(15,23,42,0.05)', background: '#fff' }}
        />
    );
}

export function THead({ cols }) {
    return (
        <thead>
            <tr style={{ background: '#F8FAFC', borderBottom: '1px solid rgba(15,23,42,0.07)' }}>
                {cols.map(h => (
                    <th key={h} style={{ padding: '11px 16px', textAlign: 'right', fontWeight: 700, color: C.textMed, fontSize: 12, whiteSpace: 'nowrap' }}>{h}</th>
                ))}
            </tr>
        </thead>
    );
}

export function EmptyState({ icon, text }) {
    return (
        <div style={{ padding: '56px', textAlign: 'center', color: C.textFaint }}>
            <i className={`ti ${icon}`} style={{ fontSize: 40, display: 'block', marginBottom: 10, opacity: 0.25 }} />
            <div style={{ fontSize: 13 }}>{text}</div>
        </div>
    );
}

export function StatusPill({ active, label }) {
    return (
        <span style={{ fontSize: 11, fontWeight: 600, padding: '3px 10px', borderRadius: 20, background: active ? C.successBg : C.dangerBg, color: active ? C.successText : C.dangerText, border: `1px solid ${active ? C.successBorder : C.dangerBorder}` }}>
            {label}
        </span>
    );
}

export function PrimaryBtn({ onClick, icon, children, color, ...rest }) {
    const bg = color ?? C.teal;
    return (
        <button onClick={onClick} style={{ display: 'inline-flex', alignItems: 'center', gap: 6, padding: '8px 18px', background: bg, color: '#fff', border: 'none', borderRadius: 10, fontSize: 12, fontWeight: 600, cursor: 'pointer', boxShadow: `0 2px 8px ${bg}40` }} {...rest}>
            {icon && <i className={`ti ${icon}`} />} {children}
        </button>
    );
}

export const INPUT_STYLE = { width: '100%', padding: '9px 13px', border: C.cardBorder, borderRadius: 8, fontSize: 12, outline: 'none', boxSizing: 'border-box', background: '#fff', transition: 'border-color .15s' };
