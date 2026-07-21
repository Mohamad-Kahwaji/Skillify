import { Head, Link, router } from '@inertiajs/react';
import { useState } from 'react';
import SuperAdminLayout from '../../Layouts/SuperAdminLayout';

const FONT = "'Cairo','Inter',sans-serif";
const P = '#7C3AED';
const P2 = '#6D28D9';

const STATUS = {
    active:   { bg: '#ECFDF5', color: '#065F46', border: '#6EE7B7', dot: '#10B981', label: 'مقبول',         icon: 'ti-circle-check' },
    pending:  { bg: '#FFFBEB', color: '#92400E', border: '#FCD34D', dot: '#F59E0B', label: 'قيد المراجعة', icon: 'ti-clock' },
    rejected: { bg: '#FEF2F2', color: '#991B1B', border: '#FCA5A5', dot: '#EF4444', label: 'مرفوض',         icon: 'ti-circle-x' },
};

const TABS = [
    { key: 'all',      label: 'الكل',         icon: 'ti-layout-grid', color: '#1E1B4B' },
    { key: 'pending',  label: 'قيد المراجعة', icon: 'ti-clock',       color: '#B45309' },
    { key: 'active',   label: 'مقبول',         icon: 'ti-circle-check',color: '#065F46' },
    { key: 'rejected', label: 'مرفوض',         icon: 'ti-circle-x',   color: '#991B1B' },
];

function OwnerAvatar({ user }) {
    const initials = `${user?.first_name?.[0] ?? ''}${user?.last_name?.[0] ?? ''}`.toUpperCase() || '?';
    return (
        <div style={{
            width: 32, height: 32, borderRadius: '50%', flexShrink: 0,
            background: `linear-gradient(135deg,${P},${P2})`,
            display: 'flex', alignItems: 'center', justifyContent: 'center',
            fontSize: 11, fontWeight: 800, color: '#fff',
            boxShadow: `0 2px 8px ${P}44`,
        }}>{initials}</div>
    );
}

function StatChip({ icon, val }) {
    if (!val) return null;
    return (
        <span style={{ display: 'inline-flex', alignItems: 'center', gap: 4, fontSize: 11, color: '#64748B', background: '#F8FAFC', border: '1px solid #F1F5F9', borderRadius: 8, padding: '3px 8px' }}>
            <i className={`ti ${icon}`} style={{ fontSize: 11, color: '#94A3B8' }} />{val}
        </span>
    );
}

export default function Businesses({ businesses }) {
    const [tab, setTab]       = useState('all');
    const [search, setSearch] = useState('');
    const [imgErrors, setImgErrors] = useState({});

    const all = businesses ?? [];

    const filtered = all
        .filter(b => tab === 'all' || b.status === tab)
        .filter(b => `${b.name} ${b.name_job} ${b.user?.first_name ?? ''} ${b.user?.last_name ?? ''} ${b.city ?? ''}`.toLowerCase().includes(search.toLowerCase()));

    const counts = TABS.reduce((acc, t) => {
        acc[t.key] = t.key === 'all' ? all.length : all.filter(b => b.status === t.key).length;
        return acc;
    }, {});

    const patch   = (id, action) => router.patch(`/super-admin/businesses/${id}/${action}`, {}, { preserveScroll: true });
    const destroy = (id) => { if (!confirm('حذف هذا النشاط نهائياً؟')) return; router.delete(`/super-admin/businesses/${id}`, { preserveScroll: true }); };

    return (
        <SuperAdminLayout title="الأعمال">
            <Head title="الأعمال — Skillify" />

            <style>{`
                .biz-card { transition: box-shadow .2s, transform .2s; }
                .biz-card:hover { box-shadow: 0 12px 36px rgba(0,0,0,0.13) !important; transform: translateY(-3px); }
                .act-btn { transition: filter .15s, transform .1s; }
                .act-btn:hover { filter: brightness(0.93); transform: scale(0.97); }
            `}</style>

            {/* ── Header ── */}
            <div style={{ display: 'flex', alignItems: 'center', justifyContent: 'space-between', flexWrap: 'wrap', gap: 14 }}>
                <div style={{ display: 'flex', alignItems: 'center', gap: 14 }}>
                    <div style={{ width: 48, height: 48, borderRadius: 14, background: `linear-gradient(135deg,${P},${P2})`, display: 'flex', alignItems: 'center', justifyContent: 'center', boxShadow: `0 6px 18px ${P}44` }}>
                        <i className="ti ti-building-store" style={{ color: '#fff', fontSize: 22 }} />
                    </div>
                    <div>
                        <h1 style={{ fontSize: 22, fontWeight: 800, color: '#1E1B4B', margin: 0 }}>حسابات الأعمال</h1>
                        <p style={{ fontSize: 12, color: '#94A3B8', margin: 0 }}>{all.length} سجل مسجّل</p>
                    </div>
                </div>
                <div style={{ position: 'relative' }}>
                    <i className="ti ti-search" style={{ position: 'absolute', right: 12, top: '50%', transform: 'translateY(-50%)', color: '#94A3B8', fontSize: 14, pointerEvents: 'none' }} />
                    <input value={search} onChange={e => setSearch(e.target.value)} placeholder="بحث بالاسم أو المالك..."
                        style={{ padding: '10px 38px 10px 14px', border: '1.5px solid #E2E8F0', borderRadius: 12, fontSize: 12.5, outline: 'none', width: 250, fontFamily: FONT, background: '#fff', direction: 'rtl', color: '#0F172A' }}
                        onFocus={e => e.target.style.borderColor = P}
                        onBlur={e => e.target.style.borderColor = '#E2E8F0'} />
                </div>
            </div>

            {/* ── Tabs ── */}
            <div style={{ display: 'flex', gap: 8, flexWrap: 'wrap', background: '#fff', padding: '6px 8px', borderRadius: 14, border: '1.5px solid #F1F5F9', boxShadow: '0 2px 8px rgba(0,0,0,0.04)' }}>
                {TABS.map(t => {
                    const active = tab === t.key;
                    return (
                        <button key={t.key} onClick={() => setTab(t.key)} style={{
                            display: 'inline-flex', alignItems: 'center', gap: 7,
                            padding: '9px 18px', borderRadius: 10, border: 'none',
                            fontSize: 12.5, cursor: 'pointer', fontWeight: active ? 700 : 500,
                            fontFamily: FONT, transition: 'all 0.15s',
                            background: active ? `linear-gradient(135deg,#1E1B4B,#312E81)` : 'transparent',
                            color: active ? '#fff' : '#64748B',
                            boxShadow: active ? '0 4px 12px rgba(30,27,75,0.25)' : 'none',
                        }}>
                            <i className={`ti ${t.icon}`} style={{ fontSize: 14 }} />
                            {t.label}
                            <span style={{
                                padding: '2px 8px', borderRadius: 20, fontSize: 11, fontWeight: 700, lineHeight: 1.5,
                                background: active ? 'rgba(255,255,255,0.22)' : '#F1F5F9',
                                color: active ? '#fff' : '#94A3B8',
                            }}>{counts[t.key]}</span>
                        </button>
                    );
                })}
            </div>

            {/* ── Cards ── */}
            {!filtered.length ? (
                <div style={{ textAlign: 'center', padding: '80px 20px', color: '#94A3B8', background: '#fff', borderRadius: 20, border: '1.5px solid #F1F5F9', boxShadow: '0 2px 12px rgba(0,0,0,0.04)' }}>
                    <div style={{ width: 72, height: 72, borderRadius: 20, background: `${P}10`, display: 'flex', alignItems: 'center', justifyContent: 'center', margin: '0 auto 16px' }}>
                        <i className="ti ti-building-store" style={{ fontSize: 32, color: `${P}44` }} />
                    </div>
                    <div style={{ fontSize: 15, fontWeight: 700, color: '#475569', marginBottom: 6 }}>لا توجد أعمال</div>
                    <div style={{ fontSize: 12, color: '#94A3B8' }}>لا يوجد ما يطابق البحث أو الفلتر الحالي</div>
                </div>
            ) : (
                <div style={{ display: 'grid', gridTemplateColumns: 'repeat(auto-fill,minmax(310px,1fr))', gap: 18 }}>
                    {filtered.map(b => {
                        const s = STATUS[b.status ?? 'pending'] ?? STATUS.pending;
                        const ownerName = `${b.user?.first_name ?? ''} ${b.user?.last_name ?? ''}`.trim();
                        const hasImg = b.image && !imgErrors[b.id];
                        const gradientColors = ['135deg,#7C3AED,#6D28D9', '135deg,#0D9488,#0F766E', '135deg,#DC2626,#B91C1C', '135deg,#D97706,#B45309', '135deg,#2563EB,#1D4ED8'];
                        const grad = gradientColors[b.id % gradientColors.length];

                        return (
                            <div key={b.id} className="biz-card" style={{
                                background: '#fff', border: '1.5px solid #F1F5F9',
                                borderRadius: 20, overflow: 'hidden',
                                display: 'flex', flexDirection: 'column',
                                boxShadow: '0 2px 12px rgba(0,0,0,0.05)',
                            }}>
                                {/* ── Cover Image ── */}
                                <div style={{ position: 'relative', height: 130, overflow: 'hidden', flexShrink: 0 }}>
                                    {hasImg ? (
                                        <img
                                            src={`/storage/${b.image}`}
                                            alt={b.name}
                                            onError={() => setImgErrors(prev => ({ ...prev, [b.id]: true }))}
                                            style={{ width: '100%', height: '100%', objectFit: 'cover', display: 'block' }}
                                        />
                                    ) : (
                                        <div style={{ width: '100%', height: '100%', background: `linear-gradient(${grad})`, position: 'relative', overflow: 'hidden' }}>
                                            <div style={{ position: 'absolute', inset: 0, backgroundImage: 'linear-gradient(rgba(255,255,255,0.06) 1px,transparent 1px),linear-gradient(90deg,rgba(255,255,255,0.06) 1px,transparent 1px)', backgroundSize: '28px 28px' }} />
                                            <div style={{ position: 'absolute', top: '50%', left: '50%', transform: 'translate(-50%,-50%)', fontSize: 44, fontWeight: 900, color: 'rgba(255,255,255,0.18)', letterSpacing: -1 }}>
                                                {b.name?.[0]?.toUpperCase()}
                                            </div>
                                        </div>
                                    )}

                                    {/* Dark gradient overlay at bottom */}
                                    <div style={{ position: 'absolute', bottom: 0, left: 0, right: 0, height: 70, background: 'linear-gradient(transparent, rgba(0,0,0,0.72))' }} />

                                    {/* Business name + activity over image */}
                                    <div style={{ position: 'absolute', bottom: 10, right: 14, left: 14 }}>
                                        <div style={{ fontSize: 15, fontWeight: 800, color: '#fff', lineHeight: 1.2, textShadow: '0 1px 4px rgba(0,0,0,0.4)', overflow: 'hidden', textOverflow: 'ellipsis', whiteSpace: 'nowrap' }}>{b.name}</div>
                                        {b.name_job && <div style={{ fontSize: 11.5, color: 'rgba(255,255,255,0.82)', marginTop: 2, fontWeight: 600 }}>{b.name_job}</div>}
                                    </div>

                                    {/* Status badge */}
                                    <span style={{
                                        position: 'absolute', top: 10, left: 10,
                                        display: 'inline-flex', alignItems: 'center', gap: 4,
                                        fontSize: 10.5, fontWeight: 700, padding: '4px 10px', borderRadius: 20,
                                        background: s.bg, color: s.color, border: `1px solid ${s.border}`,
                                        boxShadow: '0 2px 8px rgba(0,0,0,0.12)', backdropFilter: 'blur(4px)',
                                    }}>
                                        <span style={{ width: 5, height: 5, borderRadius: '50%', background: s.dot, display: 'inline-block', flexShrink: 0 }} />
                                        {s.label}
                                    </span>
                                </div>

                                {/* ── Body ── */}
                                <div style={{ padding: '14px 16px', flex: 1, display: 'flex', flexDirection: 'column', gap: 10 }}>
                                    {/* Owner row */}
                                    <div style={{ display: 'flex', alignItems: 'center', gap: 9 }}>
                                        <OwnerAvatar user={b.user} />
                                        <div style={{ minWidth: 0 }}>
                                            <div style={{ fontSize: 12.5, fontWeight: 700, color: '#0F172A', overflow: 'hidden', textOverflow: 'ellipsis', whiteSpace: 'nowrap' }}>{ownerName || '—'}</div>
                                            <div style={{ fontSize: 10.5, color: '#94A3B8', fontWeight: 500 }}>صاحب النشاط</div>
                                        </div>
                                    </div>

                                    {/* Meta chips */}
                                    {(b.city || b.number) && (
                                        <div style={{ display: 'flex', gap: 6, flexWrap: 'wrap' }}>
                                            <StatChip icon="ti-map-pin" val={b.city} />
                                            <StatChip icon="ti-phone"   val={b.number} />
                                        </div>
                                    )}

                                    {/* Description */}
                                    {b.description && (
                                        <p style={{ fontSize: 11.5, color: '#64748B', margin: 0, lineHeight: 1.65, display: '-webkit-box', WebkitLineClamp: 2, WebkitBoxOrient: 'vertical', overflow: 'hidden' }}>
                                            {b.description}
                                        </p>
                                    )}
                                </div>

                                {/* ── Actions ── */}
                                <div style={{ padding: '11px 14px', borderTop: '1.5px solid #F8FAFC', display: 'flex', gap: 7, alignItems: 'center' }}>
                                    {b.status !== 'active' && (
                                        <button className="act-btn" onClick={() => patch(b.id, 'approve')} style={{
                                            flex: 1, padding: '8px 6px', borderRadius: 10, border: 'none',
                                            background: 'linear-gradient(135deg,#10B981,#059669)', color: '#fff',
                                            fontSize: 11.5, cursor: 'pointer', fontWeight: 700, fontFamily: FONT,
                                            display: 'inline-flex', alignItems: 'center', justifyContent: 'center', gap: 5,
                                            boxShadow: '0 3px 10px rgba(16,185,129,0.32)',
                                        }}>
                                            <i className="ti ti-check" style={{ fontSize: 13 }} /> قبول
                                        </button>
                                    )}
                                    {b.status !== 'rejected' && (
                                        <button className="act-btn" onClick={() => patch(b.id, 'reject')} style={{
                                            flex: 1, padding: '8px 6px', borderRadius: 10, border: 'none',
                                            background: 'linear-gradient(135deg,#EF4444,#DC2626)', color: '#fff',
                                            fontSize: 11.5, cursor: 'pointer', fontWeight: 700, fontFamily: FONT,
                                            display: 'inline-flex', alignItems: 'center', justifyContent: 'center', gap: 5,
                                            boxShadow: '0 3px 10px rgba(239,68,68,0.32)',
                                        }}>
                                            <i className="ti ti-x" style={{ fontSize: 13 }} /> رفض
                                        </button>
                                    )}
                                    {b.status !== 'pending' && (
                                        <button className="act-btn" onClick={() => patch(b.id, 'pending')} style={{
                                            flex: 1, padding: '8px 6px', borderRadius: 10, border: '1.5px solid #E2E8F0',
                                            background: '#F8FAFC', color: '#475569',
                                            fontSize: 11.5, cursor: 'pointer', fontWeight: 700, fontFamily: FONT,
                                            display: 'inline-flex', alignItems: 'center', justifyContent: 'center', gap: 5,
                                        }}>
                                            <i className="ti ti-clock" style={{ fontSize: 13 }} /> مراجعة
                                        </button>
                                    )}
                                    <Link href={`/super-admin/businesses/${b.id}`} className="act-btn" style={{
                                        width: 36, height: 36, borderRadius: 10, border: '1.5px solid #E2E8F0',
                                        background: '#F8FAFC', color: '#475569',
                                        fontSize: 15, display: 'inline-flex', alignItems: 'center', justifyContent: 'center',
                                        flexShrink: 0, textDecoration: 'none',
                                    }}>
                                        <i className="ti ti-eye" />
                                    </Link>
                                    <button className="act-btn" onClick={() => destroy(b.id)} style={{
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
        </SuperAdminLayout>
    );
}
