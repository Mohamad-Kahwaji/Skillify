import { Head, Link, router } from '@inertiajs/react';
import { useState } from 'react';
import AdminLayout from '../../Layouts/AdminLayout';

const AV_COLORS = ['#0D9488','#3B82F6','#8B5CF6','#F59E0B','#EF4444','#EC4899'];

export default function Users({ users, flash }) {
    const [search, setSearch] = useState('');

    const filtered = (users ?? []).filter(u =>
        `${u.first_name} ${u.last_name} ${u.email} ${u.phone ?? ''}`.toLowerCase().includes(search.toLowerCase())
    );

    const toggle = (u) => {
        const route = u.status === 'active' ? `/admin/users/${u.id}/deactivate` : `/admin/users/${u.id}/activate`;
        router.patch(route, {}, { preserveScroll: true });
    };

    const destroy = (u) => {
        if (!confirm(`حذف ${u.first_name} ${u.last_name}؟`)) return;
        router.delete(`/admin/users/${u.id}`, { preserveScroll: true });
    };

    return (
        <AdminLayout title="المستخدمون">
            <Head title="المستخدمون — Skillify" />

            <div style={{ display: 'flex', alignItems: 'center', justifyContent: 'space-between', flexWrap: 'wrap', gap: 12 }}>
                <div>
                    <div style={{ fontSize: 18, fontWeight: 700, color: '#0F172A' }}>المستخدمون</div>
                    <div style={{ fontSize: 12, color: '#475569' }}>{filtered.length} من {(users ?? []).length} مستخدم</div>
                </div>
                <input
                    value={search} onChange={e => setSearch(e.target.value)}
                    placeholder="بحث عن مستخدم..."
                    style={{ padding: '8px 14px', border: '0.5px solid rgba(0,0,0,0.12)', borderRadius: 8, fontSize: 12, outline: 'none', width: 220 }}
                />
            </div>

            <div style={{ background: '#fff', border: '0.5px solid rgba(0,0,0,0.07)', borderRadius: 14, overflow: 'hidden' }}>
                <table style={{ width: '100%', borderCollapse: 'collapse', fontSize: 12 }}>
                    <thead>
                        <tr style={{ background: '#F8FAFC', borderBottom: '0.5px solid rgba(0,0,0,0.07)' }}>
                            {['المستخدم','البريد','الهاتف','المدينة','الحالة','تاريخ التسجيل','إجراءات'].map(h => (
                                <th key={h} style={{ padding: '10px 14px', textAlign: 'right', fontWeight: 600, color: '#475569', whiteSpace: 'nowrap' }}>{h}</th>
                            ))}
                        </tr>
                    </thead>
                    <tbody>
                        {!filtered.length ? (
                            <tr><td colSpan={7} style={{ padding: '40px', textAlign: 'center', color: '#94A3B8' }}>لا يوجد مستخدمون</td></tr>
                        ) : filtered.map((u, i) => (
                            <tr key={u.id} style={{ borderBottom: '0.5px solid rgba(0,0,0,0.05)' }}
                                onMouseEnter={e => e.currentTarget.style.background = '#F8FAFC'}
                                onMouseLeave={e => e.currentTarget.style.background = 'transparent'}
                            >
                                <td style={{ padding: '10px 14px' }}>
                                    <div style={{ display: 'flex', alignItems: 'center', gap: 8 }}>
                                        <div style={{ width: 30, height: 30, borderRadius: '50%', background: AV_COLORS[i % 6], color: '#fff', display: 'flex', alignItems: 'center', justifyContent: 'center', fontSize: 11, fontWeight: 600, flexShrink: 0 }}>
                                            {(u.first_name ?? 'U')[0].toUpperCase()}
                                        </div>
                                        <span style={{ fontWeight: 600, color: '#0F172A' }}>{u.first_name} {u.last_name}</span>
                                    </div>
                                </td>
                                <td style={{ padding: '10px 14px', color: '#475569' }}>{u.email}</td>
                                <td style={{ padding: '10px 14px', color: '#475569' }}>{u.phone ?? '—'}</td>
                                <td style={{ padding: '10px 14px', color: '#475569' }}>{u.city ?? '—'}</td>
                                <td style={{ padding: '10px 14px' }}>
                                    <span style={{ fontSize: 10, fontWeight: 600, padding: '2px 8px', borderRadius: 20, background: u.status === 'inactive' ? '#FEF2F2' : '#F0FDF4', color: u.status === 'inactive' ? '#B91C1C' : '#134E4A' }}>
                                        {u.status ?? 'active'}
                                    </span>
                                </td>
                                <td style={{ padding: '10px 14px', color: '#94A3B8', whiteSpace: 'nowrap' }}>
                                    {new Date(u.created_at).toLocaleDateString('ar', { month: 'short', day: 'numeric', year: 'numeric' })}
                                </td>
                                <td style={{ padding: '10px 14px' }}>
                                    <div style={{ display: 'flex', gap: 5 }}>
                                        <button onClick={() => toggle(u)} style={{ padding: '4px 10px', borderRadius: 6, border: '0.5px solid rgba(0,0,0,0.12)', background: 'none', fontSize: 11, cursor: 'pointer', color: u.status === 'active' ? '#F59E0B' : '#0D9488', whiteSpace: 'nowrap' }}>
                                            {u.status === 'active' ? 'تعطيل' : 'تفعيل'}
                                        </button>
                                        <button onClick={() => destroy(u)} style={{ padding: '4px 8px', borderRadius: 6, border: '0.5px solid rgba(0,0,0,0.12)', background: 'none', fontSize: 11, cursor: 'pointer', color: '#EF4444' }}>
                                            <i className="ti ti-trash" />
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        ))}
                    </tbody>
                </table>
            </div>
        </AdminLayout>
    );
}
