import { Head, Link, router } from '@inertiajs/react';
import { useState } from 'react';
import AdminLayout from '../../Layouts/AdminLayout';

const STATUS = {
    approved: { bg: '#F0FDF4', color: '#134E4A' },
    pending:  { bg: '#FEF3C7', color: '#92400E' },
    rejected: { bg: '#FEF2F2', color: '#B91C1C' },
};

export default function Services({ services, flash }) {
    const [search, setSearch] = useState('');

    const filtered = (services ?? []).filter(s =>
        `${s.name} ${s.category?.name_en ?? ''} ${s.city?.name_en ?? ''}`.toLowerCase().includes(search.toLowerCase())
    );

    const toggle = (id) => router.patch(`/admin/services/${id}/toggle`, {}, { preserveScroll: true });
    const destroy = (id) => {
        if (!confirm('حذف هذه الخدمة؟')) return;
        router.delete(`/admin/services/${id}`, { preserveScroll: true });
    };

    return (
        <AdminLayout title="الخدمات">
            <Head title="الخدمات — Skillify" />

            <div style={{ display: 'flex', alignItems: 'center', justifyContent: 'space-between', flexWrap: 'wrap', gap: 12 }}>
                <div>
                    <div style={{ fontSize: 18, fontWeight: 700, color: '#0F172A' }}>الخدمات</div>
                    <div style={{ fontSize: 12, color: '#475569' }}>{filtered.length} خدمة</div>
                </div>
                <input value={search} onChange={e => setSearch(e.target.value)} placeholder="بحث..."
                    style={{ padding: '8px 14px', border: '0.5px solid rgba(0,0,0,0.12)', borderRadius: 8, fontSize: 12, outline: 'none', width: 220 }} />
            </div>

            <div style={{ background: '#fff', border: '0.5px solid rgba(0,0,0,0.07)', borderRadius: 14, overflow: 'hidden' }}>
                <table style={{ width: '100%', borderCollapse: 'collapse', fontSize: 12 }}>
                    <thead>
                        <tr style={{ background: '#F8FAFC', borderBottom: '0.5px solid rgba(0,0,0,0.07)' }}>
                            {['الخدمة','الفئة','المدينة','السعر','الحالة','نشط','إجراءات'].map(h => (
                                <th key={h} style={{ padding: '10px 14px', textAlign: 'right', fontWeight: 600, color: '#475569' }}>{h}</th>
                            ))}
                        </tr>
                    </thead>
                    <tbody>
                        {!filtered.length ? (
                            <tr><td colSpan={7} style={{ padding: '40px', textAlign: 'center', color: '#94A3B8' }}>لا توجد خدمات</td></tr>
                        ) : filtered.map(s => {
                            const badge = STATUS[s.status ?? 'pending'] ?? STATUS.pending;
                            return (
                                <tr key={s.id} style={{ borderBottom: '0.5px solid rgba(0,0,0,0.05)' }}
                                    onMouseEnter={e => e.currentTarget.style.background = '#F8FAFC'}
                                    onMouseLeave={e => e.currentTarget.style.background = 'transparent'}
                                >
                                    <td style={{ padding: '10px 14px' }}>
                                        <div style={{ fontWeight: 600, color: '#0F172A' }}>{s.name}</div>
                                        <div style={{ fontSize: 10, color: '#94A3B8' }}>{s.user?.first_name} {s.user?.last_name}</div>
                                    </td>
                                    <td style={{ padding: '10px 14px', color: '#475569' }}>{s.category?.name_en ?? '—'}</td>
                                    <td style={{ padding: '10px 14px', color: '#475569' }}>{s.city?.name_en ?? '—'}</td>
                                    <td style={{ padding: '10px 14px', color: '#0D9488', fontWeight: 700 }}>
                                        {Number(s.price).toLocaleString()} <small style={{ color: '#94A3B8', fontWeight: 400 }}>{s.price_type?.toUpperCase()}</small>
                                    </td>
                                    <td style={{ padding: '10px 14px' }}>
                                        <span style={{ fontSize: 10, fontWeight: 600, padding: '2px 8px', borderRadius: 20, ...badge }}>{s.status ?? 'pending'}</span>
                                    </td>
                                    <td style={{ padding: '10px 14px' }}>
                                        <span style={{ fontSize: 10, fontWeight: 600, padding: '2px 8px', borderRadius: 20, background: s.is_active ? '#F0FDF4' : '#F3F4F6', color: s.is_active ? '#134E4A' : '#6B7280' }}>
                                            {s.is_active ? 'نعم' : 'لا'}
                                        </span>
                                    </td>
                                    <td style={{ padding: '10px 14px' }}>
                                        <div style={{ display: 'flex', gap: 5 }}>
                                            <Link href={`/admin/services/${s.id}`} style={{ padding: '4px 8px', borderRadius: 6, border: '0.5px solid rgba(0,0,0,0.12)', color: '#0F172A', textDecoration: 'none', fontSize: 11 }}>
                                                <i className="ti ti-eye" />
                                            </Link>
                                            <button onClick={() => toggle(s.id)} style={{ padding: '4px 8px', borderRadius: 6, border: '0.5px solid rgba(0,0,0,0.12)', background: 'none', color: '#F59E0B', fontSize: 11, cursor: 'pointer' }}>
                                                <i className={s.is_active ? 'ti ti-toggle-right' : 'ti ti-toggle-left'} />
                                            </button>
                                            <button onClick={() => destroy(s.id)} style={{ padding: '4px 8px', borderRadius: 6, border: 'none', background: '#FEF2F2', color: '#EF4444', fontSize: 11, cursor: 'pointer' }}>
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
        </AdminLayout>
    );
}
