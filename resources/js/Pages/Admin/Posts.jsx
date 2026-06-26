import { Head, router } from '@inertiajs/react';
import { useState } from 'react';
import AdminLayout from '../../Layouts/AdminLayout';

const AV_COLORS = ['#0D9488','#3B82F6','#8B5CF6','#F59E0B','#EF4444','#EC4899'];

export default function Posts({ posts, flash }) {
    const [search, setSearch] = useState('');

    const filtered = (posts ?? []).filter(p =>
        `${p.title} ${p.user?.first_name ?? ''} ${p.user?.last_name ?? ''}`.toLowerCase().includes(search.toLowerCase())
    );

    const destroy = (id) => {
        if (!confirm('حذف هذا المنشور نهائياً؟')) return;
        router.delete(`/admin/posts/${id}`, { preserveScroll: true });
    };

    return (
        <AdminLayout title="المنشورات">
            <Head title="المنشورات — Skillify" />

            <div style={{ display: 'flex', alignItems: 'center', justifyContent: 'space-between', flexWrap: 'wrap', gap: 12 }}>
                <div>
                    <div style={{ fontSize: 18, fontWeight: 700, color: '#0F172A' }}>المنشورات</div>
                    <div style={{ fontSize: 12, color: '#475569' }}>{filtered.length} منشور</div>
                </div>
                <input value={search} onChange={e => setSearch(e.target.value)} placeholder="بحث في المنشورات..."
                    style={{ padding: '8px 14px', border: '0.5px solid rgba(0,0,0,0.12)', borderRadius: 8, fontSize: 12, outline: 'none', width: 220 }} />
            </div>

            <div style={{ background: '#fff', border: '0.5px solid rgba(0,0,0,0.07)', borderRadius: 14, overflow: 'hidden' }}>
                <table style={{ width: '100%', borderCollapse: 'collapse', fontSize: 12 }}>
                    <thead>
                        <tr style={{ background: '#F8FAFC', borderBottom: '0.5px solid rgba(0,0,0,0.07)' }}>
                            {['العنوان','الكاتب','المشاهدات','التاريخ','إجراء'].map(h => (
                                <th key={h} style={{ padding: '10px 14px', textAlign: 'right', fontWeight: 600, color: '#475569' }}>{h}</th>
                            ))}
                        </tr>
                    </thead>
                    <tbody>
                        {!filtered.length ? (
                            <tr><td colSpan={5} style={{ padding: '40px', textAlign: 'center', color: '#94A3B8' }}>لا توجد منشورات</td></tr>
                        ) : filtered.map((p, i) => (
                            <tr key={p.id} style={{ borderBottom: '0.5px solid rgba(0,0,0,0.05)' }}
                                onMouseEnter={e => e.currentTarget.style.background = '#F8FAFC'}
                                onMouseLeave={e => e.currentTarget.style.background = 'transparent'}
                            >
                                <td style={{ padding: '10px 14px', maxWidth: 300 }}>
                                    <div style={{ fontWeight: 600, color: '#0F172A', overflow: 'hidden', textOverflow: 'ellipsis', whiteSpace: 'nowrap' }}>{p.title}</div>
                                    <div style={{ color: '#94A3B8', fontSize: 11, marginTop: 2, overflow: 'hidden', textOverflow: 'ellipsis', whiteSpace: 'nowrap' }}>{p.description}</div>
                                </td>
                                <td style={{ padding: '10px 14px' }}>
                                    <div style={{ display: 'flex', alignItems: 'center', gap: 6 }}>
                                        <div style={{ width: 24, height: 24, borderRadius: '50%', background: AV_COLORS[i % 6], color: '#fff', display: 'flex', alignItems: 'center', justifyContent: 'center', fontSize: 9, fontWeight: 600, flexShrink: 0 }}>
                                            {(p.user?.first_name ?? 'U')[0].toUpperCase()}
                                        </div>
                                        <span style={{ color: '#475569' }}>{p.user?.first_name} {p.user?.last_name}</span>
                                    </div>
                                </td>
                                <td style={{ padding: '10px 14px', color: '#94A3B8' }}>{p.views ?? 0}</td>
                                <td style={{ padding: '10px 14px', color: '#94A3B8', whiteSpace: 'nowrap' }}>
                                    {new Date(p.created_at).toLocaleDateString('ar', { month: 'short', day: 'numeric', year: 'numeric' })}
                                </td>
                                <td style={{ padding: '10px 14px' }}>
                                    <button onClick={() => destroy(p.id)} style={{ padding: '4px 10px', borderRadius: 6, border: 'none', background: '#FEF2F2', color: '#EF4444', fontSize: 11, cursor: 'pointer', display: 'inline-flex', alignItems: 'center', gap: 4 }}>
                                        <i className="ti ti-trash" /> حذف
                                    </button>
                                </td>
                            </tr>
                        ))}
                    </tbody>
                </table>
            </div>
        </AdminLayout>
    );
}
