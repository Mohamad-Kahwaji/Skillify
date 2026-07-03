import { Head, Link, router } from '@inertiajs/react';
import { useState } from 'react';
import AdminLayout, { C } from '../../Layouts/AdminLayout';

const FONT = "'Cairo','Inter',sans-serif";

const STATUS_CFG = {
    active:   { label: 'نشط',           color: '#065F46', bg: '#ECFDF5', border: '#6EE7B7', dot: '#10B981', bar: '#10B981', icon: 'ti-circle-check' },
    pending:  { label: 'قيد المراجعة', color: '#92400E', bg: '#FFFBEB', border: '#FCD34D', dot: '#F59E0B', bar: '#F59E0B', icon: 'ti-clock' },
    rejected: { label: 'مرفوض',         color: '#991B1B', bg: '#FEF2F2', border: '#FCA5A5', dot: '#EF4444', bar: '#EF4444', icon: 'ti-circle-x' },
};

const GRAD_POOL = [
    '135deg,#0D9488,#0F766E',
    '135deg,#2563EB,#1D4ED8',
    '135deg,#7C3AED,#6D28D9',
    '135deg,#D97706,#B45309',
    '135deg,#DC2626,#B91C1C',
    '135deg,#0891B2,#0E7490',
];

function timeAgo(d) {
    if (!d) return '';
    const s = Math.floor((Date.now() - new Date(d)) / 1000);
    if (s < 60)    return 'الآن';
    if (s < 3600)  return `منذ ${Math.floor(s / 60)} دقيقة`;
    if (s < 86400) return `منذ ${Math.floor(s / 3600)} ساعة`;
    return `منذ ${Math.floor(s / 86400)} يوم`;
}

function OwnerAvatar({ user }) {
    const initials = `${user?.first_name?.[0] ?? ''}${user?.last_name?.[0] ?? ''}`.toUpperCase() || '?';
    return (
        <div style={{
            width: 32, height: 32, borderRadius: '50%', flexShrink: 0,
            background: `linear-gradient(135deg,${C.primary},${C.teal})`,
            display: 'flex', alignItems: 'center', justifyContent: 'center',
            fontSize: 11, fontWeight: 800, color: '#fff',
            boxShadow: `0 2px 8px ${C.primary}44`,
        }}>{initials}</div>
    );
}

export default function Workers({ businesses }) {
    const [search,    setSearch]    = useState('');
    const [tab,       setTab]       = useState('all');
    const [imgErrors, setImgErrors] = useState({});

    const all = businesses ?? [];

    const filtered = all
        .filter(b => tab === 'all' || b.status === tab)
        .filter(b => `${b.name ?? ''} ${b.name_job ?? ''} ${b.activity ?? ''} ${b.user?.first_name ?? ''} ${b.user?.last_name ?? ''} ${b.city ?? ''}`.toLowerCase().includes(search.toLowerCase()));

    const counts = {
        all:      all.length,
        pending:  all.filter(b => b.status === 'pending').length,
        active:   all.filter(b => b.status === 'active').length,
        rejected: all.filter(b => b.status === 'rejected').length,
    };

    const patch   = (id, action) => router.patch(`/admin/workers/${id}/${action}`, {}, { preserveScroll: true });
    const destroy = (id) => {
        if (!confirm('حذف حساب العمل هذا نهائياً؟')) return;
        router.delete(`/admin/workers/${id}`, { preserveScroll: true });
    };

    const TABS = [
        { key: 'all',      label: 'الكل',         icon: 'ti-briefcase',    count: counts.all },
        { key: 'pending',  label: 'قيد المراجعة', icon: 'ti-clock',        count: counts.pending },
        { key: 'active',   label: 'نشط',           icon: 'ti-circle-check', count: counts.active },
        { key: 'rejected', label: 'مرفوض',         icon: 'ti-circle-x',    count: counts.rejected },
    ];

    return (
        <AdminLayout title="الحرفيون">
            <Head title="الحرفيون — Skillify" />

            <style>{`
                .w-card { transition: box-shadow .2s, transform .2s; }
                .w-card:hover { box-shadow: 0 12px 36px rgba(0,0,0,0.12) !important; transform: translateY(-3px); }
                .w-act { transition: filter .13s, transform .1s; }
                .w-act:hover { filter: brightness(0.92); transform: scale(0.97); }
            `}</style>

            {/* ── Header ── */}
            <div style={{ display: 'flex', alignItems: 'center', justifyContent: 'space-between', flexWrap: 'wrap', gap: 14 }}>
                <div style={{ display: 'flex', alignItems: 'center', gap: 14 }}>
                    <div style={{ width: 48, height: 48, borderRadius: 14, background: `linear-gradient(135deg,${C.primary},${C.teal})`, display: 'flex', alignItems: 'center', justifyContent: 'center', boxShadow: `0 6px 18px ${C.primary}44` }}>
                        <i className="ti ti-briefcase" style={{ color: '#fff', fontSize: 22 }} />
                    </div>
                    <div>
                        <h1 style={{ fontSize: 22, fontWeight: 800, color: C.textDark, margin: 0 }}>الحرفيون / الأعمال</h1>
                        <p style={{ fontSize: 12, color: C.textFaint, margin: 0 }}>
                            {counts.pending > 0 && <span style={{ color: '#D97706', fontWeight: 700 }}>{counts.pending} بانتظار المراجعة · </span>}
                            {all.length} سجل إجمالاً
                        </p>
                    </div>
                </div>

                {/* Summary chips */}
                <div style={{ display: 'flex', gap: 8, flexWrap: 'wrap' }}>
                    {[
                        { label: 'نشط',   count: counts.active,   color: '#065F46', bg: '#ECFDF5', border: '#6EE7B7' },
                        { label: 'معلق',  count: counts.pending,  color: '#92400E', bg: '#FFFBEB', border: '#FDE68A' },
                        { label: 'مرفوض', count: counts.rejected, color: '#991B1B', bg: '#FEF2F2', border: '#FCA5A5' },
                    ].map(s => (
                        <div key={s.label} style={{ display: 'inline-flex', alignItems: 'center', gap: 6, padding: '6px 14px', borderRadius: 22, background: s.bg, border: `1px solid ${s.border}`, color: s.color, fontSize: 12, fontWeight: 700 }}>
                            <span style={{ fontSize: 16, fontWeight: 800 }}>{s.count}</span>{s.label}
                        </div>
                    ))}
                </div>
            </div>

            {/* ── Search + Tabs ── */}
            <div style={{ display: 'flex', gap: 10, flexWrap: 'wrap', alignItems: 'center' }}>
                <div style={{ position: 'relative', flex: 1, minWidth: 220 }}>
                    <i className="ti ti-search" style={{ position: 'absolute', top: '50%', right: 12, transform: 'translateY(-50%)', color: C.textFaint, fontSize: 14, pointerEvents: 'none' }} />
                    <input value={search} onChange={e => setSearch(e.target.value)} placeholder="بحث بالاسم أو التخصص أو صاحب العمل..."
                        style={{ width: '100%', padding: '10px 38px 10px 13px', border: C.cardBorder, borderRadius: 12, fontSize: 13, outline: 'none', fontFamily: FONT, background: '#fff', boxSizing: 'border-box', boxShadow: '0 1px 4px rgba(15,23,42,0.06)' }}
                        onFocus={e => e.target.style.borderColor = C.primary}
                        onBlur={e => e.target.style.borderColor = ''} />
                </div>
                <div style={{ display: 'flex', gap: 6, flexWrap: 'wrap', background: '#fff', padding: '5px 6px', borderRadius: 12, border: C.cardBorder, boxShadow: '0 1px 4px rgba(15,23,42,0.04)' }}>
                    {TABS.map(t => {
                        const active = tab === t.key;
                        return (
                            <button key={t.key} onClick={() => setTab(t.key)} style={{
                                display: 'inline-flex', alignItems: 'center', gap: 6,
                                padding: '8px 16px', borderRadius: 9, border: 'none',
                                fontSize: 12.5, cursor: 'pointer', fontWeight: active ? 700 : 500,
                                fontFamily: FONT, transition: 'all .15s',
                                background: active ? `linear-gradient(135deg,${C.primary},${C.teal})` : 'transparent',
                                color: active ? '#fff' : C.textMuted,
                                boxShadow: active ? `0 3px 10px ${C.primary}33` : 'none',
                            }}>
                                <i className={`ti ${t.icon}`} style={{ fontSize: 13 }} />
                                {t.label}
                                <span style={{
                                    padding: '1px 7px', borderRadius: 20, fontSize: 11, fontWeight: 700, lineHeight: 1.5,
                                    background: active ? 'rgba(255,255,255,0.22)' : '#F1F5F9',
                                    color: active ? '#fff' : '#94A3B8',
                                }}>{t.count}</span>
                            </button>
                        );
                    })}
                </div>
            </div>

            {/* ── Cards Grid ── */}
            {!filtered.length ? (
                <div style={{ background: '#fff', border: C.cardBorder, borderRadius: 20, boxShadow: C.cardShadow, padding: '80px 24px', textAlign: 'center', color: C.textFaint }}>
                    <div style={{ width: 72, height: 72, borderRadius: 20, background: `${C.primary}10`, display: 'flex', alignItems: 'center', justifyContent: 'center', margin: '0 auto 16px' }}>
                        <i className="ti ti-briefcase" style={{ fontSize: 32, color: `${C.primary}44` }} />
                    </div>
                    <div style={{ fontSize: 15, fontWeight: 700, color: '#475569', marginBottom: 6 }}>لا توجد سجلات</div>
                    <p style={{ fontSize: 13, margin: 0, color: C.textFaint }}>جرّب تغيير الفلتر أو كلمة البحث.</p>
                </div>
            ) : (
                <div style={{ display: 'grid', gridTemplateColumns: 'repeat(auto-fill,minmax(300px,1fr))', gap: 18 }}>
                    {filtered.map(b => {
                        const sc = STATUS_CFG[b.status ?? 'pending'] ?? STATUS_CFG.pending;
                        const hasImg = b.image && !imgErrors[b.id];
                        const imgSrc = b.image?.startsWith('http') ? b.image : `/storage/${b.image}`;
                        const grad = GRAD_POOL[b.id % GRAD_POOL.length];
                        const ownerName = `${b.user?.first_name ?? ''} ${b.user?.last_name ?? ''}`.trim();

                        return (
                            <div key={b.id} className="w-card" style={{
                                background: '#fff',
                                border: `1.5px solid ${b.status === 'pending' ? 'rgba(245,158,11,0.25)' : '#F1F5F9'}`,
                                borderRadius: 20, overflow: 'hidden',
                                display: 'flex', flexDirection: 'column',
                                boxShadow: b.status === 'pending' ? '0 2px 14px rgba(245,158,11,0.1)' : C.cardShadow,
                            }}>
                                {/* ── Cover ── */}
                                <div style={{ position: 'relative', height: 130, overflow: 'hidden', flexShrink: 0 }}>
                                    {hasImg ? (
                                        <img
                                            src={imgSrc}
                                            alt={b.name}
                                            onError={() => setImgErrors(prev => ({ ...prev, [b.id]: true }))}
                                            style={{ width: '100%', height: '100%', objectFit: 'cover', display: 'block' }}
                                        />
                                    ) : (
                                        <div style={{ width: '100%', height: '100%', background: `linear-gradient(${grad})`, position: 'relative', overflow: 'hidden' }}>
                                            <div style={{ position: 'absolute', inset: 0, backgroundImage: 'linear-gradient(rgba(255,255,255,0.06) 1px,transparent 1px),linear-gradient(90deg,rgba(255,255,255,0.06) 1px,transparent 1px)', backgroundSize: '28px 28px' }} />
                                            <div style={{ position: 'absolute', top: '50%', left: '50%', transform: 'translate(-50%,-50%)', fontSize: 44, fontWeight: 900, color: 'rgba(255,255,255,0.18)' }}>
                                                {b.name?.[0]?.toUpperCase()}
                                            </div>
                                        </div>
                                    )}

                                    {/* Dark overlay */}
                                    <div style={{ position: 'absolute', bottom: 0, left: 0, right: 0, height: 75, background: 'linear-gradient(transparent, rgba(0,0,0,0.72))' }} />

                                    {/* Name + activity */}
                                    <div style={{ position: 'absolute', bottom: 10, right: 14, left: 14 }}>
                                        <div style={{ fontSize: 15, fontWeight: 800, color: '#fff', lineHeight: 1.2, textShadow: '0 1px 4px rgba(0,0,0,0.4)', overflow: 'hidden', textOverflow: 'ellipsis', whiteSpace: 'nowrap' }}>{b.name}</div>
                                        {(b.name_job || b.activity) && (
                                            <div style={{ fontSize: 11.5, color: 'rgba(255,255,255,0.82)', marginTop: 2, fontWeight: 600 }}>{b.name_job ?? b.activity}</div>
                                        )}
                                    </div>

                                    {/* Status badge */}
                                    <span style={{
                                        position: 'absolute', top: 10, left: 10,
                                        display: 'inline-flex', alignItems: 'center', gap: 4,
                                        fontSize: 10.5, fontWeight: 700, padding: '4px 10px', borderRadius: 20,
                                        background: sc.bg, color: sc.color, border: `1px solid ${sc.border}`,
                                        boxShadow: '0 2px 8px rgba(0,0,0,0.12)',
                                    }}>
                                        <span style={{ width: 5, height: 5, borderRadius: '50%', background: sc.dot, display: 'inline-block', flexShrink: 0 }} />
                                        {sc.label}
                                    </span>

                                    {/* Time badge */}
                                    <span style={{ position: 'absolute', top: 10, right: 10, fontSize: 10, color: 'rgba(255,255,255,0.75)', background: 'rgba(0,0,0,0.35)', padding: '3px 8px', borderRadius: 20, backdropFilter: 'blur(4px)' }}>
                                        <i className="ti ti-clock" style={{ fontSize: 9, marginLeft: 3 }} />{timeAgo(b.created_at)}
                                    </span>
                                </div>

                                {/* ── Body ── */}
                                <div style={{ padding: '14px 16px', flex: 1, display: 'flex', flexDirection: 'column', gap: 10 }}>
                                    {/* Owner */}
                                    <div style={{ display: 'flex', alignItems: 'center', gap: 9 }}>
                                        <OwnerAvatar user={b.user} />
                                        <div style={{ minWidth: 0 }}>
                                            <div style={{ fontSize: 12.5, fontWeight: 700, color: C.textDark, overflow: 'hidden', textOverflow: 'ellipsis', whiteSpace: 'nowrap' }}>{ownerName || '—'}</div>
                                            <div style={{ fontSize: 10.5, color: C.textFaint }}>{b.user?.email}</div>
                                        </div>
                                    </div>

                                    {/* Meta */}
                                    <div style={{ display: 'flex', gap: 6, flexWrap: 'wrap' }}>
                                        {b.city && (
                                            <span style={{ display: 'inline-flex', alignItems: 'center', gap: 4, fontSize: 11, color: '#64748B', background: '#F8FAFC', border: '1px solid #F1F5F9', borderRadius: 8, padding: '3px 8px' }}>
                                                <i className="ti ti-map-pin" style={{ fontSize: 11, color: '#94A3B8' }} />{b.city}
                                            </span>
                                        )}
                                        {b.number && (
                                            <span style={{ display: 'inline-flex', alignItems: 'center', gap: 4, fontSize: 11, color: '#64748B', background: '#F8FAFC', border: '1px solid #F1F5F9', borderRadius: 8, padding: '3px 8px' }}>
                                                <i className="ti ti-phone" style={{ fontSize: 11, color: '#94A3B8' }} />{b.number}
                                            </span>
                                        )}
                                    </div>

                                    {/* Description */}
                                    {b.description && (
                                        <p style={{ fontSize: 11.5, color: C.textMuted, margin: 0, lineHeight: 1.65, display: '-webkit-box', WebkitLineClamp: 2, WebkitBoxOrient: 'vertical', overflow: 'hidden' }}>
                                            {b.description}
                                        </p>
                                    )}
                                </div>

                                {/* ── Actions ── */}
                                <div style={{ padding: '11px 14px', borderTop: `1px solid ${b.status === 'pending' ? 'rgba(245,158,11,0.15)' : 'rgba(15,23,42,0.06)'}`, display: 'flex', gap: 7, alignItems: 'center' }}>
                                    {b.status !== 'active' && (
                                        <button className="w-act" onClick={() => patch(b.id, 'approve')} style={{
                                            flex: 1, padding: '8px 6px', borderRadius: 10, border: 'none',
                                            background: 'linear-gradient(135deg,#10B981,#059669)', color: '#fff',
                                            fontSize: 11.5, cursor: 'pointer', fontWeight: 700, fontFamily: FONT,
                                            display: 'inline-flex', alignItems: 'center', justifyContent: 'center', gap: 5,
                                            boxShadow: '0 3px 10px rgba(16,185,129,0.32)',
                                        }}>
                                            <i className="ti ti-circle-check" style={{ fontSize: 13 }} /> قبول
                                        </button>
                                    )}
                                    {b.status !== 'rejected' && (
                                        <button className="w-act" onClick={() => patch(b.id, 'reject')} style={{
                                            flex: 1, padding: '8px 6px', borderRadius: 10, border: 'none',
                                            background: 'linear-gradient(135deg,#EF4444,#DC2626)', color: '#fff',
                                            fontSize: 11.5, cursor: 'pointer', fontWeight: 700, fontFamily: FONT,
                                            display: 'inline-flex', alignItems: 'center', justifyContent: 'center', gap: 5,
                                            boxShadow: '0 3px 10px rgba(239,68,68,0.32)',
                                        }}>
                                            <i className="ti ti-circle-x" style={{ fontSize: 13 }} /> رفض
                                        </button>
                                    )}
                                    {b.status !== 'pending' && (
                                        <button className="w-act" onClick={() => patch(b.id, 'pending')} style={{
                                            flex: 1, padding: '8px 6px', borderRadius: 10, border: '1.5px solid #E2E8F0',
                                            background: '#F8FAFC', color: C.textMed,
                                            fontSize: 11.5, cursor: 'pointer', fontWeight: 700, fontFamily: FONT,
                                            display: 'inline-flex', alignItems: 'center', justifyContent: 'center', gap: 5,
                                        }}>
                                            <i className="ti ti-refresh" style={{ fontSize: 13 }} /> إعادة
                                        </button>
                                    )}
                                    <Link href={`/admin/workers/${b.id}`} className="w-act" style={{
                                        width: 36, height: 36, borderRadius: 10, border: C.cardBorder,
                                        background: '#F8FAFC', color: C.textMed,
                                        fontSize: 15, display: 'inline-flex', alignItems: 'center', justifyContent: 'center',
                                        flexShrink: 0, textDecoration: 'none',
                                    }}>
                                        <i className="ti ti-eye" />
                                    </Link>
                                    <button className="w-act" onClick={() => destroy(b.id)} style={{
                                        width: 36, height: 36, borderRadius: 10, border: '1.5px solid #FECACA',
                                        background: '#FEF2F2', color: '#EF4444',
                                        fontSize: 15, cursor: 'pointer', fontFamily: FONT,
                                        display: 'inline-flex', alignItems: 'center', justifyContent: 'center',
                                        flexShrink: 0,
                                    }}>
                                        <i className="ti ti-trash" />
                                    </button>
                                </div>
                            </div>
                        );
                    })}
                </div>
            )}

            {tab === 'active' && filtered.length > 0 && (
                <div style={{ textAlign: 'center', fontSize: 12, color: C.textFaint, paddingTop: 4 }}>
                    <i className="ti ti-circle-check" style={{ color: '#10B981', marginLeft: 5 }} />
                    {filtered.length} حساب أعمال نشط ومعتمد
                </div>
            )}
        </AdminLayout>
    );
}
