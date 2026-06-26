import { Head, Link, router } from '@inertiajs/react';
import { useState } from 'react';
import AdminLayout from '../../Layouts/AdminLayout';

const STATUS = {
    active:   { bg: '#F0FDF4', color: '#134E4A' },
    pending:  { bg: '#FEF3C7', color: '#92400E' },
    rejected: { bg: '#FEF2F2', color: '#B91C1C' },
};

export default function Workers({ businesses, flash }) {
    const [search, setSearch] = useState('');

    const filtered = (businesses ?? []).filter(b =>
        `${b.name} ${b.name_job} ${b.activity ?? ''}`.toLowerCase().includes(search.toLowerCase())
    );

    const destroy = (id) => {
        if (!confirm('حذف حساب العمل هذا؟')) return;
        router.delete(`/admin/workers/${id}`, { preserveScroll: true });
    };

    return (
        <AdminLayout title="الحرفيون">
            <Head title="الحرفيون — Skillify" />

            <div style={{ display: 'flex', alignItems: 'center', justifyContent: 'space-between', flexWrap: 'wrap', gap: 12 }}>
                <div>
                    <div style={{ fontSize: 18, fontWeight: 700, color: '#0F172A' }}>الحرفيون / الأعمال</div>
                    <div style={{ fontSize: 12, color: '#475569' }}>{filtered.length} سجل</div>
                </div>
                <input value={search} onChange={e => setSearch(e.target.value)} placeholder="بحث..."
                    style={{ padding: '8px 14px', border: '0.5px solid rgba(0,0,0,0.12)', borderRadius: 8, fontSize: 12, outline: 'none', width: 220 }} />
            </div>

            <div style={{ display: 'grid', gridTemplateColumns: 'repeat(auto-fill,minmax(280px,1fr))', gap: 14 }}>
                {!filtered.length ? (
                    <div style={{ gridColumn: '1/-1', textAlign: 'center', padding: '48px', color: '#94A3B8' }}>لا توجد أعمال</div>
                ) : filtered.map(b => {
                    const s = STATUS[b.status ?? 'pending'] ?? STATUS.pending;
                    return (
                        <div key={b.id} style={{ background: '#fff', border: '0.5px solid rgba(0,0,0,0.07)', borderRadius: 14, overflow: 'hidden', display: 'flex', flexDirection: 'column' }}>
                            <div style={{ display: 'flex', alignItems: 'center', gap: 12, padding: '14px 16px', borderBottom: '0.5px solid rgba(0,0,0,0.07)' }}>
                                <div style={{ width: 44, height: 44, borderRadius: 10, background: '#F1F5F9', overflow: 'hidden', display: 'flex', alignItems: 'center', justifyContent: 'center', flexShrink: 0, fontSize: 18, color: '#94A3B8' }}>
                                    {b.image ? <img src={`/storage/${b.image}`} style={{ width: '100%', height: '100%', objectFit: 'cover' }} alt={b.name} /> : <i className="ti ti-briefcase" />}
                                </div>
                                <div style={{ flex: 1, minWidth: 0 }}>
                                    <div style={{ fontSize: 13, fontWeight: 700, color: '#0F172A' }}>{b.name}</div>
                                    <div style={{ fontSize: 11, color: '#0D9488' }}>{b.name_job}</div>
                                </div>
                                <span style={{ fontSize: 10, fontWeight: 600, padding: '2px 8px', borderRadius: 20, ...s }}>{b.status ?? 'pending'}</span>
                            </div>
                            <div style={{ padding: '10px 16px', flex: 1 }}>
                                {b.description && <p style={{ fontSize: 11, color: '#475569', lineHeight: 1.5, display: '-webkit-box', WebkitLineClamp: 2, WebkitBoxOrient: 'vertical', overflow: 'hidden', marginBottom: 6 }}>{b.description}</p>}
                                <div style={{ fontSize: 10, color: '#94A3B8' }}>المالك: {b.user?.first_name} {b.user?.last_name}</div>
                            </div>
                            <div style={{ padding: '10px 16px', borderTop: '0.5px solid rgba(0,0,0,0.07)', display: 'flex', gap: 6 }}>
                                <Link href={`/admin/workers/${b.id}`} style={{ flex: 1, padding: '6px', textAlign: 'center', borderRadius: 7, border: '0.5px solid rgba(0,0,0,0.12)', fontSize: 11, color: '#0F172A', textDecoration: 'none' }}>
                                    <i className="ti ti-eye" /> عرض
                                </Link>
                                <button onClick={() => destroy(b.id)} style={{ padding: '6px 12px', borderRadius: 7, border: 'none', background: '#FEF2F2', color: '#EF4444', fontSize: 11, cursor: 'pointer' }}>
                                    <i className="ti ti-trash" />
                                </button>
                            </div>
                        </div>
                    );
                })}
            </div>
        </AdminLayout>
    );
}
