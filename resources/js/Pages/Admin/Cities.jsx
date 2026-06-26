import { Head, router, useForm } from '@inertiajs/react';
import { useState } from 'react';
import AdminLayout from '../../Layouts/AdminLayout';

const INPUT = { width: '100%', padding: '8px 12px', border: '0.5px solid rgba(0,0,0,0.12)', borderRadius: 7, fontSize: 12, outline: 'none', boxSizing: 'border-box' };

const BLANK = { name_ar: '', name_en: '', governorate_ar: '', governorate_en: '', latitude: '', longitude: '' };

export default function Cities({ cities }) {
    const [showCreate, setShowCreate] = useState(false);
    const [editId, setEditId]         = useState(null);
    const createForm = useForm({ ...BLANK });
    const editForm   = useForm({ ...BLANK });

    const startEdit = (c) => {
        setEditId(c.id);
        editForm.setData({
            name_ar: c.name_ar ?? '', name_en: c.name_en ?? '',
            governorate_ar: c.governorate_ar ?? '', governorate_en: c.governorate_en ?? '',
            latitude: c.latitude ?? '', longitude: c.longitude ?? '',
        });
    };

    const submitCreate = (e) => {
        e.preventDefault();
        createForm.post('/admin/cities', { onSuccess: () => { createForm.reset(); setShowCreate(false); } });
    };

    const submitEdit = (e) => {
        e.preventDefault();
        editForm.put(`/admin/cities/${editId}`, { onSuccess: () => setEditId(null) });
    };

    const destroy = (id) => {
        if (!confirm('حذف هذه المدينة نهائياً؟')) return;
        router.delete(`/admin/cities/${id}`, { preserveScroll: true });
    };

    const Field = ({ label, field, form, type = 'text', dir }) => (
        <div>
            <label style={{ fontSize: 11, color: '#475569', display: 'block', marginBottom: 3 }}>{label}</label>
            <input type={type} dir={dir}
                style={INPUT}
                value={form.data[field]}
                onChange={e => form.setData(field, e.target.value)}
                required
            />
            {form.errors[field] && <p style={{ fontSize: 11, color: '#EF4444', marginTop: 3 }}>{form.errors[field]}</p>}
        </div>
    );

    return (
        <AdminLayout title="المدن">
            <Head title="المدن — Skillify" />

            <div style={{ display: 'flex', alignItems: 'center', justifyContent: 'space-between' }}>
                <div>
                    <div style={{ fontSize: 18, fontWeight: 700, color: '#0F172A' }}>المدن</div>
                    <div style={{ fontSize: 12, color: '#475569' }}>{(cities ?? []).length} مدينة</div>
                </div>
                <button onClick={() => setShowCreate(v => !v)} style={{ display: 'inline-flex', alignItems: 'center', gap: 6, padding: '8px 16px', background: '#0D9488', color: '#fff', border: 'none', borderRadius: 9, fontSize: 12, fontWeight: 600, cursor: 'pointer' }}>
                    <i className="ti ti-plus" /> إضافة مدينة
                </button>
            </div>

            {showCreate && (
                <div style={{ background: '#fff', border: '1px solid rgba(0,0,0,0.07)', borderRadius: 12, padding: 18 }}>
                    <div style={{ fontSize: 13, fontWeight: 700, marginBottom: 14 }}>مدينة جديدة</div>
                    <form onSubmit={submitCreate}>
                        <div style={{ display: 'grid', gridTemplateColumns: '1fr 1fr', gap: 12 }}>
                            <Field label="الاسم بالعربية *"       field="name_ar"        form={createForm} dir="rtl" />
                            <Field label="الاسم بالإنجليزية *"    field="name_en"        form={createForm} />
                            <Field label="المحافظة (عربي) *"      field="governorate_ar" form={createForm} dir="rtl" />
                            <Field label="المحافظة (إنجليزي) *"   field="governorate_en" form={createForm} />
                            <Field label="خط العرض *"             field="latitude"       form={createForm} type="number" />
                            <Field label="خط الطول *"             field="longitude"      form={createForm} type="number" />
                        </div>
                        <div style={{ display: 'flex', gap: 8, justifyContent: 'flex-end', marginTop: 14 }}>
                            <button type="button" onClick={() => setShowCreate(false)} style={{ padding: '6px 12px', borderRadius: 6, border: '0.5px solid rgba(0,0,0,0.12)', background: 'none', fontSize: 12, cursor: 'pointer' }}>إلغاء</button>
                            <button type="submit" disabled={createForm.processing} style={{ padding: '6px 14px', borderRadius: 6, background: '#0D9488', color: '#fff', border: 'none', fontSize: 12, fontWeight: 600, cursor: 'pointer', opacity: createForm.processing ? 0.7 : 1 }}>
                                {createForm.processing ? 'جارٍ الحفظ...' : 'حفظ'}
                            </button>
                        </div>
                    </form>
                </div>
            )}

            <div style={{ background: '#fff', border: '0.5px solid rgba(0,0,0,0.07)', borderRadius: 14, overflow: 'hidden' }}>
                <table style={{ width: '100%', borderCollapse: 'collapse', fontSize: 12 }}>
                    <thead>
                        <tr style={{ background: '#F8FAFC', borderBottom: '0.5px solid rgba(0,0,0,0.07)' }}>
                            {['#', 'الاسم (عربي)', 'الاسم (إنجليزي)', 'المحافظة', 'الإحداثيات', 'إجراءات'].map(h => (
                                <th key={h} style={{ padding: '10px 14px', textAlign: 'right', fontWeight: 600, color: '#475569', whiteSpace: 'nowrap' }}>{h}</th>
                            ))}
                        </tr>
                    </thead>
                    <tbody>
                        {!(cities ?? []).length ? (
                            <tr><td colSpan={6} style={{ padding: '40px', textAlign: 'center', color: '#94A3B8' }}>
                                <i className="ti ti-map-pin" style={{ fontSize: 32, display: 'block', marginBottom: 8, opacity: 0.3 }} />
                                لا توجد مدن مضافة بعد
                            </td></tr>
                        ) : (cities ?? []).map((c, i) => (
                            editId === c.id ? (
                                <tr key={c.id} style={{ background: '#F0FDFA', borderBottom: '0.5px solid rgba(0,0,0,0.07)' }}>
                                    <td style={{ padding: '8px 14px', color: '#94A3B8' }}>{i + 1}</td>
                                    <td style={{ padding: '8px 14px' }}><input style={{ ...INPUT, width: 110 }} value={editForm.data.name_ar} onChange={e => editForm.setData('name_ar', e.target.value)} dir="rtl" /></td>
                                    <td style={{ padding: '8px 14px' }}><input style={{ ...INPUT, width: 110 }} value={editForm.data.name_en} onChange={e => editForm.setData('name_en', e.target.value)} /></td>
                                    <td style={{ padding: '8px 14px' }}>
                                        <div style={{ display: 'flex', gap: 4, flexDirection: 'column' }}>
                                            <input style={{ ...INPUT, width: 100 }} value={editForm.data.governorate_ar} onChange={e => editForm.setData('governorate_ar', e.target.value)} placeholder="AR" dir="rtl" />
                                            <input style={{ ...INPUT, width: 100 }} value={editForm.data.governorate_en} onChange={e => editForm.setData('governorate_en', e.target.value)} placeholder="EN" />
                                        </div>
                                    </td>
                                    <td style={{ padding: '8px 14px' }}>
                                        <div style={{ display: 'flex', gap: 4, flexDirection: 'column' }}>
                                            <input type="number" style={{ ...INPUT, width: 80 }} value={editForm.data.latitude} onChange={e => editForm.setData('latitude', e.target.value)} placeholder="Lat" />
                                            <input type="number" style={{ ...INPUT, width: 80 }} value={editForm.data.longitude} onChange={e => editForm.setData('longitude', e.target.value)} placeholder="Lng" />
                                        </div>
                                    </td>
                                    <td style={{ padding: '8px 14px' }}>
                                        <div style={{ display: 'flex', gap: 5 }}>
                                            <button onClick={submitEdit} style={{ padding: '4px 10px', borderRadius: 6, background: '#0D9488', color: '#fff', border: 'none', fontSize: 11, cursor: 'pointer' }}>حفظ</button>
                                            <button onClick={() => setEditId(null)} style={{ padding: '4px 8px', borderRadius: 6, border: '0.5px solid rgba(0,0,0,0.12)', background: 'none', fontSize: 11, cursor: 'pointer' }}>إلغاء</button>
                                        </div>
                                    </td>
                                </tr>
                            ) : (
                                <tr key={c.id} style={{ borderBottom: '0.5px solid rgba(0,0,0,0.05)' }}
                                    onMouseEnter={e => e.currentTarget.style.background = '#F8FAFC'}
                                    onMouseLeave={e => e.currentTarget.style.background = 'transparent'}
                                >
                                    <td style={{ padding: '10px 14px', color: '#94A3B8' }}>{i + 1}</td>
                                    <td style={{ padding: '10px 14px', fontWeight: 600, color: '#0F172A' }}>{c.name_ar}</td>
                                    <td style={{ padding: '10px 14px', color: '#475569' }}>{c.name_en}</td>
                                    <td style={{ padding: '10px 14px', color: '#475569' }}>{c.governorate_en}{c.governorate_ar ? ` / ${c.governorate_ar}` : ''}</td>
                                    <td style={{ padding: '10px 14px', color: '#94A3B8', fontSize: 11 }}>
                                        {c.latitude != null ? `${Number(c.latitude).toFixed(4)}, ${Number(c.longitude).toFixed(4)}` : '—'}
                                    </td>
                                    <td style={{ padding: '10px 14px' }}>
                                        <div style={{ display: 'flex', gap: 5 }}>
                                            <button onClick={() => startEdit(c)} style={{ padding: '4px 8px', borderRadius: 6, border: '0.5px solid rgba(0,0,0,0.12)', background: 'none', fontSize: 11, cursor: 'pointer', color: '#475569' }}>
                                                <i className="ti ti-pencil" />
                                            </button>
                                            <button onClick={() => destroy(c.id)} style={{ padding: '4px 8px', borderRadius: 6, border: 'none', background: '#FEF2F2', color: '#EF4444', fontSize: 11, cursor: 'pointer' }}>
                                                <i className="ti ti-trash" />
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            )
                        ))}
                    </tbody>
                </table>
            </div>
        </AdminLayout>
    );
}
