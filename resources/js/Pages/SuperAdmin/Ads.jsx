import { Head, router } from '@inertiajs/react';
import { useState } from 'react';
import SuperAdminLayout from '../../Layouts/SuperAdminLayout';

export default function Ads({ ads }) {
    const [search, setSearch] = useState('');

    const filtered = (ads ?? []).filter(a =>
        `${a.title} ${a.company_name} ${a.user?.first_name ?? ''}`.toLowerCase().includes(search.toLowerCase())
    );

    const toggle  = (id) => router.patch(`/super-admin/ads/${id}/toggle`, {}, { preserveScroll: true });
    const destroy = (id) => { if (!confirm('حذف هذا الإعلان نهائياً؟')) return; router.delete(`/super-admin/ads/${id}`, { preserveScroll: true }); };

    return (
        <SuperAdminLayout title="الإعلانات">
            <Head title="الإعلانات — Skillify" />

            <div style={{ display: 'flex', alignItems: 'center', justifyContent: 'space-between', flexWrap: 'wrap', gap: 12 }}>
                <div>
                    <div style={{ fontSize: 18, fontWeight: 700, color: '#0F172A' }}>جميع الإعلانات</div>
                    <div style={{ fontSize: 12, color: '#475569' }}>{filtered.length} إعلان</div>
                </div>
                <input value={search} onChange={e => setSearch(e.target.value)} placeholder="بحث..."
                    style={{ padding: '8px 14px', border: '0.5px solid rgba(0,0,0,0.12)', borderRadius: 8, fontSize: 12, outline: 'none', width: 220 }} />
            </div>

            <div style={{ display: 'grid', gridTemplateColumns: 'repeat(auto-fill,minmax(300px,1fr))', gap: 14 }}>
                {!filtered.length ? (
                    <div style={{ gridColumn: '1/-1', textAlign: 'center', padding: '56px', color: '#94A3B8' }}>
                        <i className="ti ti-speakerphone" style={{ fontSize: 40, display: 'block', marginBottom: 10, opacity: 0.25 }} />
                        لا توجد إعلانات
                    </div>
                ) : filtered.map(a => {
                    const img = a.image ? (a.image.startsWith('http') ? a.image : `/storage/${a.image}`) : null;
                    const isActive = a.status === 'approved';
                    return (
                        <div key={a.id} style={{ background: '#fff', border: '0.5px solid rgba(0,0,0,0.07)', borderRadius: 14, overflow: 'hidden', display: 'flex', flexDirection: 'column' }}>
                            {img
                                ? <img src={img} alt={a.title} style={{ width: '100%', height: 130, objectFit: 'cover' }} />
                                : <div style={{ width: '100%', height: 130, background: 'linear-gradient(135deg,#1E1B4B,#4C1D95)', display: 'flex', alignItems: 'center', justifyContent: 'center', color: '#fff', fontSize: 32 }}>📢</div>
                            }
                            <div style={{ padding: '12px 14px', flex: 1 }}>
                                <div style={{ display: 'flex', alignItems: 'center', justifyContent: 'space-between', marginBottom: 6 }}>
                                    <span style={{ fontSize: 10, fontWeight: 600, padding: '2px 8px', borderRadius: 20, background: isActive ? '#F0FDF4' : '#FEF3C7', color: isActive ? '#134E4A' : '#92400E' }}>
                                        {isActive ? 'نشط' : (a.status === 'pending' ? 'قيد المراجعة' : a.status ?? 'قيد المراجعة')}
                                    </span>
                                    <span style={{ fontSize: 11, color: '#94A3B8' }}>{a.company_name}</span>
                                </div>
                                <div style={{ fontSize: 13, fontWeight: 700, color: '#0F172A', marginBottom: 4, display: '-webkit-box', WebkitLineClamp: 1, WebkitBoxOrient: 'vertical', overflow: 'hidden' }}>{a.title}</div>
                                <div style={{ fontSize: 11, color: '#475569', lineHeight: 1.5, display: '-webkit-box', WebkitLineClamp: 2, WebkitBoxOrient: 'vertical', overflow: 'hidden' }}>{a.description}</div>
                                {(a.start_date || a.end_date) && (
                                    <div style={{ fontSize: 10, color: '#94A3B8', marginTop: 6 }}>
                                        {a.start_date} → {a.end_date}
                                    </div>
                                )}
                                <div style={{ fontSize: 10, color: '#94A3B8', marginTop: 4 }}>من: {a.user?.first_name} {a.user?.last_name}</div>
                            </div>
                            <div style={{ padding: '10px 14px', borderTop: '0.5px solid rgba(0,0,0,0.07)', display: 'flex', gap: 6 }}>
                                <button onClick={() => toggle(a.id)} style={{
                                    flex: 1, padding: '6px', borderRadius: 7, border: 'none', fontSize: 11, cursor: 'pointer', fontWeight: 600,
                                    background: isActive ? '#FEF3C7' : '#F0FDF4',
                                    color: isActive ? '#92400E' : '#134E4A',
                                }}>
                                    <i className={`ti ${isActive ? 'ti-eye-off' : 'ti-eye'}`} /> {isActive ? 'تعطيل' : 'تفعيل'}
                                </button>
                                <button onClick={() => destroy(a.id)} style={{ padding: '6px 10px', borderRadius: 7, border: 'none', background: '#FEF2F2', color: '#EF4444', fontSize: 11, cursor: 'pointer' }}>
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
