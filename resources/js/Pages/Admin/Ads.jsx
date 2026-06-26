import { Head, router, useForm } from '@inertiajs/react';
import { useState } from 'react';
import AdminLayout from '../../Layouts/AdminLayout';

const INPUT = { width: '100%', padding: '8px 12px', border: '0.5px solid rgba(0,0,0,0.12)', borderRadius: 7, fontSize: 12, outline: 'none', boxSizing: 'border-box' };

export default function Ads({ advertisements, adminId, flash }) {
    const [showForm, setShowForm] = useState(false);
    const { data, setData, post, processing, errors, reset } = useForm({
        admin_id: adminId ?? '',
        title: '', description: '', company_name: '',
        start_date: '', end_date: '', status: 'active',
    });

    const submit = (e) => {
        e.preventDefault();
        post('/admin/ads', { onSuccess: () => { reset(); setShowForm(false); } });
    };

    const destroy = (id) => {
        if (!confirm('حذف هذا الإعلان؟')) return;
        router.delete(`/admin/ads/${id}`, { preserveScroll: true });
    };

    return (
        <AdminLayout title="الإعلانات">
            <Head title="الإعلانات — Skillify" />

            <div style={{ display: 'flex', alignItems: 'center', justifyContent: 'space-between' }}>
                <div>
                    <div style={{ fontSize: 18, fontWeight: 700, color: '#0F172A' }}>الإعلانات</div>
                    <div style={{ fontSize: 12, color: '#475569' }}>{(advertisements ?? []).length} إعلان</div>
                </div>
                <button onClick={() => setShowForm(v => !v)} style={{ display: 'inline-flex', alignItems: 'center', gap: 6, padding: '8px 16px', background: '#0D9488', color: '#fff', border: 'none', borderRadius: 9, fontSize: 12, fontWeight: 600, cursor: 'pointer' }}>
                    <i className="ti ti-plus" /> إعلان جديد
                </button>
            </div>

            {/* Create form */}
            {showForm && (
                <div style={{ background: '#fff', border: '1px solid rgba(0,0,0,0.07)', borderRadius: 14, padding: 20 }}>
                    <div style={{ fontSize: 14, fontWeight: 700, marginBottom: 14 }}>إنشاء إعلان</div>
                    <form onSubmit={submit}>
                        <div style={{ display: 'grid', gridTemplateColumns: '1fr 1fr', gap: 12 }}>
                            <div><label style={{ fontSize: 11, color: '#475569', display: 'block', marginBottom: 3 }}>العنوان *</label><input style={INPUT} value={data.title} onChange={e => setData('title', e.target.value)} required /></div>
                            <div><label style={{ fontSize: 11, color: '#475569', display: 'block', marginBottom: 3 }}>اسم الشركة</label><input style={INPUT} value={data.company_name} onChange={e => setData('company_name', e.target.value)} /></div>
                            <div style={{ gridColumn: '1/-1' }}><label style={{ fontSize: 11, color: '#475569', display: 'block', marginBottom: 3 }}>الوصف</label><textarea style={{ ...INPUT, resize: 'vertical' }} rows={3} value={data.description} onChange={e => setData('description', e.target.value)} /></div>
                            <div><label style={{ fontSize: 11, color: '#475569', display: 'block', marginBottom: 3 }}>تاريخ البداية</label><input type="date" style={INPUT} value={data.start_date} onChange={e => setData('start_date', e.target.value)} /></div>
                            <div><label style={{ fontSize: 11, color: '#475569', display: 'block', marginBottom: 3 }}>تاريخ الانتهاء</label><input type="date" style={INPUT} value={data.end_date} onChange={e => setData('end_date', e.target.value)} /></div>
                            <div><label style={{ fontSize: 11, color: '#475569', display: 'block', marginBottom: 3 }}>الحالة</label>
                                <select style={INPUT} value={data.status} onChange={e => setData('status', e.target.value)}>
                                    <option value="active">نشط</option>
                                    <option value="inactive">غير نشط</option>
                                    <option value="approved">مقبول</option>
                                </select>
                            </div>
                        </div>
                        <div style={{ display: 'flex', gap: 8, justifyContent: 'flex-end', marginTop: 14 }}>
                            <button type="button" onClick={() => setShowForm(false)} style={{ padding: '7px 14px', borderRadius: 7, border: '0.5px solid rgba(0,0,0,0.12)', background: 'none', fontSize: 12, cursor: 'pointer' }}>إلغاء</button>
                            <button type="submit" disabled={processing} style={{ padding: '7px 16px', borderRadius: 7, background: '#0D9488', color: '#fff', border: 'none', fontSize: 12, fontWeight: 600, cursor: 'pointer' }}>
                                {processing ? 'جارٍ الإنشاء...' : 'إنشاء'}
                            </button>
                        </div>
                    </form>
                </div>
            )}

            {/* Ads grid */}
            {!(advertisements ?? []).length ? (
                <div style={{ textAlign: 'center', padding: '48px', background: '#fff', border: '0.5px solid rgba(0,0,0,0.07)', borderRadius: 14, color: '#94A3B8' }}>
                    <i className="ti ti-speakerphone" style={{ fontSize: 38, display: 'block', marginBottom: 10, opacity: 0.3 }} />
                    <p>لا توجد إعلانات بعد</p>
                </div>
            ) : (
                <div style={{ display: 'grid', gridTemplateColumns: 'repeat(auto-fill,minmax(280px,1fr))', gap: 14 }}>
                    {(advertisements ?? []).map(ad => (
                        <div key={ad.id} style={{ background: '#fff', border: '0.5px solid rgba(0,0,0,0.07)', borderRadius: 14, overflow: 'hidden', display: 'flex', flexDirection: 'column' }}>
                            <div style={{ padding: '14px 16px', flex: 1 }}>
                                <div style={{ display: 'flex', alignItems: 'flex-start', justifyContent: 'space-between', gap: 8, marginBottom: 8 }}>
                                    <div style={{ fontSize: 13, fontWeight: 700, color: '#0F172A' }}>{ad.title}</div>
                                    <span style={{ fontSize: 10, fontWeight: 600, padding: '2px 8px', borderRadius: 20, background: ad.status === 'active' || ad.status === 'approved' ? '#F0FDF4' : '#F3F4F6', color: ad.status === 'active' || ad.status === 'approved' ? '#134E4A' : '#6B7280', flexShrink: 0 }}>
                                        {ad.status}
                                    </span>
                                </div>
                                {ad.company_name && <div style={{ fontSize: 11, color: '#0D9488', marginBottom: 6 }}>{ad.company_name}</div>}
                                {ad.description && <p style={{ fontSize: 11, color: '#475569', lineHeight: 1.5, display: '-webkit-box', WebkitLineClamp: 2, WebkitBoxOrient: 'vertical', overflow: 'hidden' }}>{ad.description}</p>}
                                {(ad.start_date || ad.end_date) && (
                                    <div style={{ fontSize: 10, color: '#94A3B8', marginTop: 8, display: 'flex', gap: 8 }}>
                                        {ad.start_date && <span><i className="ti ti-calendar" /> {ad.start_date}</span>}
                                        {ad.end_date   && <span>→ {ad.end_date}</span>}
                                    </div>
                                )}
                            </div>
                            <div style={{ padding: '10px 16px', borderTop: '0.5px solid rgba(0,0,0,0.07)', display: 'flex', gap: 6 }}>
                                <button onClick={() => destroy(ad.id)} style={{ padding: '5px 10px', borderRadius: 6, border: 'none', background: '#FEF2F2', color: '#EF4444', fontSize: 11, cursor: 'pointer', display: 'flex', alignItems: 'center', gap: 4 }}>
                                    <i className="ti ti-trash" /> حذف
                                </button>
                            </div>
                        </div>
                    ))}
                </div>
            )}
        </AdminLayout>
    );
}
