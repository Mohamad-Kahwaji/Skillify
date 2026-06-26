import { Head, router } from '@inertiajs/react';
import { useState } from 'react';
import SuperAdminLayout from '../../Layouts/SuperAdminLayout';

const STATUS_STYLE = {
    approved: { bg: '#F0FDF4', color: '#134E4A', label: 'مقبول' },
    pending:  { bg: '#FEF3C7', color: '#92400E', label: 'قيد المراجعة' },
    rejected: { bg: '#FEF2F2', color: '#B91C1C', label: 'مرفوض' },
};

const TABS = ['all', 'pending', 'approved', 'rejected'];

export default function Businesses({ businesses }) {
    const [tab, setTab]     = useState('all');
    const [search, setSearch] = useState('');

    const filtered = (businesses ?? [])
        .filter(b => tab === 'all' || b.status === tab)
        .filter(b => `${b.name} ${b.name_job} ${b.user?.first_name ?? ''} ${b.user?.last_name ?? ''}`.toLowerCase().includes(search.toLowerCase()));

    const counts = TABS.reduce((acc, t) => {
        acc[t] = t === 'all' ? (businesses ?? []).length : (businesses ?? []).filter(b => b.status === t).length;
        return acc;
    }, {});

    const patch = (id, action) => router.patch(`/super-admin/businesses/${id}/${action}`, {}, { preserveScroll: true });
    const destroy = (id) => { if (!confirm('حذف هذا النشاط التجاري نهائياً؟')) return; router.delete(`/super-admin/businesses/${id}`, { preserveScroll: true }); };

    return (
        <SuperAdminLayout title="الأعمال">
            <Head title="الأعمال — Skillify" />

            <div style={{ display: 'flex', alignItems: 'center', justifyContent: 'space-between', flexWrap: 'wrap', gap: 12 }}>
                <div>
                    <div style={{ fontSize: 18, fontWeight: 700, color: '#0F172A' }}>حسابات الأعمال</div>
                    <div style={{ fontSize: 12, color: '#475569' }}>{filtered.length} سجل</div>
                </div>
                <input value={search} onChange={e => setSearch(e.target.value)} placeholder="بحث..."
                    style={{ padding: '8px 14px', border: '0.5px solid rgba(0,0,0,0.12)', borderRadius: 8, fontSize: 12, outline: 'none', width: 220 }} />
            </div>

            {/* Tabs */}
            <div style={{ display: 'flex', gap: 6, flexWrap: 'wrap' }}>
                {TABS.map(t => {
                    const TAB_LABELS = { all: 'الكل', pending: 'قيد المراجعة', approved: 'مقبول', rejected: 'مرفوض' };
                    return (
                        <button key={t} onClick={() => setTab(t)} style={{
                            padding: '6px 14px', borderRadius: 20, border: 'none', fontSize: 12, cursor: 'pointer', fontWeight: tab === t ? 600 : 400,
                            background: tab === t ? '#1E1B4B' : '#F1F5F9',
                            color: tab === t ? '#fff' : '#475569',
                        }}>
                            {TAB_LABELS[t]} <span style={{ opacity: 0.7 }}>({counts[t]})</span>
                        </button>
                    );
                })}
            </div>

            <div style={{ display: 'grid', gridTemplateColumns: 'repeat(auto-fill,minmax(300px,1fr))', gap: 14 }}>
                {!filtered.length ? (
                    <div style={{ gridColumn: '1/-1', textAlign: 'center', padding: '56px', color: '#94A3B8' }}>
                        <i className="ti ti-briefcase" style={{ fontSize: 40, display: 'block', marginBottom: 10, opacity: 0.25 }} />
                        لا توجد أعمال
                    </div>
                ) : filtered.map(b => {
                    const s = STATUS_STYLE[b.status ?? 'pending'] ?? STATUS_STYLE.pending;
                    return (
                        <div key={b.id} style={{ background: '#fff', border: '0.5px solid rgba(0,0,0,0.07)', borderRadius: 14, overflow: 'hidden', display: 'flex', flexDirection: 'column' }}>
                            <div style={{ display: 'flex', alignItems: 'center', gap: 12, padding: '14px 16px', borderBottom: '0.5px solid rgba(0,0,0,0.07)' }}>
                                <div style={{ width: 44, height: 44, borderRadius: 10, background: '#F1F5F9', overflow: 'hidden', display: 'flex', alignItems: 'center', justifyContent: 'center', fontSize: 18, color: '#94A3B8', flexShrink: 0 }}>
                                    {b.image ? <img src={`/storage/${b.image}`} style={{ width: '100%', height: '100%', objectFit: 'cover' }} alt={b.name} /> : <i className="ti ti-briefcase" />}
                                </div>
                                <div style={{ flex: 1, minWidth: 0 }}>
                                    <div style={{ fontSize: 13, fontWeight: 700, color: '#0F172A', overflow: 'hidden', textOverflow: 'ellipsis', whiteSpace: 'nowrap' }}>{b.name}</div>
                                    <div style={{ fontSize: 11, color: '#A78BFA' }}>{b.name_job}</div>
                                </div>
                                <span style={{ fontSize: 10, fontWeight: 600, padding: '2px 8px', borderRadius: 20, background: s.bg, color: s.color, flexShrink: 0 }}>{s.label}</span>
                            </div>
                            <div style={{ padding: '10px 16px', flex: 1, fontSize: 11, color: '#475569' }}>
                                <div>المالك: <span style={{ color: '#0F172A', fontWeight: 500 }}>{b.user?.first_name} {b.user?.last_name}</span></div>
                                {b.description && <div style={{ marginTop: 4, display: '-webkit-box', WebkitLineClamp: 2, WebkitBoxOrient: 'vertical', overflow: 'hidden', lineHeight: 1.5 }}>{b.description}</div>}
                            </div>
                            <div style={{ padding: '10px 16px', borderTop: '0.5px solid rgba(0,0,0,0.07)', display: 'flex', gap: 5, flexWrap: 'wrap' }}>
                                {b.status !== 'approved' && (
                                    <button onClick={() => patch(b.id, 'approve')} style={{ flex: 1, padding: '6px', borderRadius: 7, border: 'none', background: '#F0FDF4', color: '#134E4A', fontSize: 11, cursor: 'pointer', fontWeight: 600 }}>
                                        <i className="ti ti-check" /> قبول
                                    </button>
                                )}
                                {b.status !== 'rejected' && (
                                    <button onClick={() => patch(b.id, 'reject')} style={{ flex: 1, padding: '6px', borderRadius: 7, border: 'none', background: '#FEF2F2', color: '#B91C1C', fontSize: 11, cursor: 'pointer', fontWeight: 600 }}>
                                        <i className="ti ti-x" /> رفض
                                    </button>
                                )}
                                {b.status !== 'pending' && (
                                    <button onClick={() => patch(b.id, 'pending')} style={{ flex: 1, padding: '6px', borderRadius: 7, border: '0.5px solid rgba(0,0,0,0.12)', background: 'none', color: '#475569', fontSize: 11, cursor: 'pointer' }}>
                                        <i className="ti ti-clock" /> قيد المراجعة
                                    </button>
                                )}
                                <button onClick={() => destroy(b.id)} style={{ padding: '6px 10px', borderRadius: 7, border: 'none', background: '#FEF2F2', color: '#EF4444', fontSize: 11, cursor: 'pointer' }}>
                                    <i className="ti ti-trash" />
                                </button>
                            </div>
                        </div>
                    );
                })}
            </div>
        </SuperAdminLayout>
    );
}
