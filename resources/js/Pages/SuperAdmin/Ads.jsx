import { Head, router, useForm } from '@inertiajs/react';
import { useState } from 'react';
import SuperAdminLayout from '../../Layouts/SuperAdminLayout';

const INPUT = { width: '100%', padding: '9px 12px', fontSize: 13, borderRadius: 9, border: '1px solid rgba(0,0,0,0.12)', background: '#F8FAFC', color: '#1E1B4B', outline: 'none', fontFamily: "'Cairo','Inter',sans-serif", boxSizing: 'border-box' };

function creatorName(a) {
    const p = a.admin ?? a.super_admin;
    return p ? `${p.first_name ?? ''} ${p.last_name ?? ''}`.trim() : null;
}

export default function Ads({ ads }) {
    const [search, setSearch] = useState('');
    const [showForm, setShowForm] = useState(false);
    const [editingId, setEditingId] = useState(null);
    const [imgPreview, setImgPreview] = useState(null);
    const { data, setData, post, put, processing, reset } = useForm({
        title: '', description: '', company_name: '', start_date: '', end_date: '', status: 'approved', image: null,
    });

    const filtered = (ads ?? []).filter(a =>
        `${a.title} ${a.company_name} ${creatorName(a) ?? ''}`.toLowerCase().includes(search.toLowerCase())
    );

    const openCreate = () => {
        reset();
        setEditingId(null);
        setImgPreview(null);
        setShowForm(true);
    };

    const openEdit = (a) => {
        setData({
            title: a.title ?? '', description: a.description ?? '', company_name: a.company_name ?? '',
            start_date: a.start_date ?? '', end_date: a.end_date ?? '', status: a.status ?? 'approved', image: null,
        });
        setEditingId(a.id);
        setImgPreview(a.image ? (a.image.startsWith('http') ? a.image : `/storage/${a.image}`) : null);
        setShowForm(true);
    };

    const closeForm = () => { reset(); setEditingId(null); setImgPreview(null); setShowForm(false); };

    const submit = (e) => {
        e.preventDefault();
        const onSuccess = () => { reset(); setEditingId(null); setImgPreview(null); setShowForm(false); };
        if (editingId) {
            put(`/super-admin/ads/${editingId}`, { preserveScroll: true, forceFormData: true, onSuccess });
        } else {
            post('/super-admin/ads', { preserveScroll: true, forceFormData: true, onSuccess });
        }
    };

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
                <div style={{ display: 'flex', alignItems: 'center', gap: 10 }}>
                    <input value={search} onChange={e => setSearch(e.target.value)} placeholder="بحث..."
                        style={{ padding: '8px 14px', border: '0.5px solid rgba(0,0,0,0.12)', borderRadius: 8, fontSize: 12, outline: 'none', width: 220 }} />
                    <button onClick={() => (showForm ? closeForm() : openCreate())} style={{
                        display: 'inline-flex', alignItems: 'center', gap: 6, padding: '9px 18px', borderRadius: 9, border: 'none',
                        background: 'linear-gradient(135deg,#7C3AED,#6D28D9)', color: '#fff', fontSize: 12.5, fontWeight: 700,
                        cursor: 'pointer', fontFamily: "'Cairo','Inter',sans-serif", boxShadow: '0 3px 10px rgba(124,58,237,0.32)',
                    }}>
                        <i className="ti ti-plus" /> إعلان جديد
                    </button>
                </div>
            </div>

            {showForm && (
                <div style={{ background: '#fff', border: '0.5px solid rgba(0,0,0,0.09)', borderRadius: 16, padding: '20px 22px', boxShadow: '0 2px 12px rgba(0,0,0,0.05)' }}>
                    <div style={{ fontSize: 13, fontWeight: 700, color: '#1E1B4B', marginBottom: 14 }}>{editingId ? 'تعديل الإعلان' : 'إنشاء إعلان جديد'}</div>
                    <form onSubmit={submit}>
                        <div className="grid grid-cols-1 sm:grid-cols-2" style={{ gap: 14, marginBottom: 14 }}>
                            <div>
                                <label style={{ fontSize: 11, fontWeight: 600, color: '#475569', display: 'block', marginBottom: 5 }}>العنوان *</label>
                                <input style={INPUT} value={data.title} onChange={e => setData('title', e.target.value)} required />
                            </div>
                            <div>
                                <label style={{ fontSize: 11, fontWeight: 600, color: '#475569', display: 'block', marginBottom: 5 }}>اسم الشركة</label>
                                <input style={INPUT} value={data.company_name} onChange={e => setData('company_name', e.target.value)} />
                            </div>
                            <div style={{ gridColumn: '1/-1' }}>
                                <label style={{ fontSize: 11, fontWeight: 600, color: '#475569', display: 'block', marginBottom: 5 }}>الوصف</label>
                                <textarea style={{ ...INPUT, resize: 'vertical' }} rows={3} value={data.description} onChange={e => setData('description', e.target.value)} />
                            </div>
                            <div>
                                <label style={{ fontSize: 11, fontWeight: 600, color: '#475569', display: 'block', marginBottom: 5 }}>تاريخ البداية</label>
                                <input type="date" style={INPUT} value={data.start_date} onChange={e => setData('start_date', e.target.value)} />
                            </div>
                            <div>
                                <label style={{ fontSize: 11, fontWeight: 600, color: '#475569', display: 'block', marginBottom: 5 }}>تاريخ الانتهاء</label>
                                <input type="date" style={INPUT} value={data.end_date} onChange={e => setData('end_date', e.target.value)} />
                            </div>
                            <div>
                                <label style={{ fontSize: 11, fontWeight: 600, color: '#475569', display: 'block', marginBottom: 5 }}>الحالة</label>
                                <select style={INPUT} value={data.status} onChange={e => setData('status', e.target.value)}>
                                    <option value="approved">نشط</option>
                                    <option value="pending">قيد المراجعة</option>
                                </select>
                            </div>
                            <div style={{ gridColumn: '1/-1' }}>
                                <label style={{ fontSize: 11, fontWeight: 600, color: '#475569', display: 'block', marginBottom: 5 }}>صورة الإعلان</label>
                                <div style={{ display: 'flex', alignItems: 'center', gap: 12 }}>
                                    {imgPreview && (
                                        <img src={imgPreview} alt="" style={{ width: 64, height: 64, borderRadius: 9, objectFit: 'cover', border: '1px solid rgba(0,0,0,0.1)' }} />
                                    )}
                                    <input type="file" accept="image/*" onChange={e => {
                                        const f = e.target.files?.[0];
                                        if (f) { setData('image', f); setImgPreview(URL.createObjectURL(f)); }
                                    }} style={{ fontSize: 12, fontFamily: "'Cairo','Inter',sans-serif" }} />
                                </div>
                            </div>
                        </div>
                        <div style={{ display: 'flex', gap: 10, justifyContent: 'flex-end' }}>
                            <button type="button" onClick={closeForm} style={{ padding: '8px 18px', borderRadius: 9, border: '1px solid rgba(0,0,0,0.12)', background: 'none', fontSize: 13, cursor: 'pointer', fontFamily: "'Cairo','Inter',sans-serif" }}>إلغاء</button>
                            <button type="submit" disabled={processing} style={{ padding: '8px 22px', borderRadius: 9, background: '#7C3AED', color: '#fff', border: 'none', fontSize: 13, fontWeight: 700, cursor: 'pointer', fontFamily: "'Cairo','Inter',sans-serif", opacity: processing ? 0.7 : 1 }}>
                                {processing ? 'جارٍ الحفظ...' : (editingId ? 'حفظ التعديلات' : 'حفظ')}
                            </button>
                        </div>
                    </form>
                </div>
            )}

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
                                {creatorName(a) && <div style={{ fontSize: 10, color: '#94A3B8', marginTop: 4 }}>من: {creatorName(a)}</div>}
                            </div>
                            <div style={{ padding: '10px 14px', borderTop: '0.5px solid rgba(0,0,0,0.07)', display: 'flex', gap: 6 }}>
                                <button onClick={() => openEdit(a)} style={{ padding: '6px 10px', borderRadius: 7, border: 'none', background: '#F5F3FF', color: '#7C3AED', fontSize: 11, cursor: 'pointer' }}>
                                    <i className="ti ti-pencil" />
                                </button>
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
