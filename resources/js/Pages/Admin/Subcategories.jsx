import { Head, router, useForm } from '@inertiajs/react';
import { useState } from 'react';
import AdminLayout from '../../Layouts/AdminLayout';

const INPUT = { width: '100%', padding: '8px 12px', border: '0.5px solid rgba(0,0,0,0.12)', borderRadius: 7, fontSize: 12, outline: 'none', boxSizing: 'border-box' };

export default function Subcategories({ subcategories, categories, flash }) {
    const [showCreate, setShowCreate] = useState(false);
    const [editId, setEditId]         = useState(null);
    const createForm = useForm({ name_ar: '', name_en: '', category_id: '' });
    const editForm   = useForm({ name_ar: '', name_en: '', category_id: '' });

    const startEdit = (s) => { setEditId(s.id); editForm.setData({ name_ar: s.name_ar, name_en: s.name_en, category_id: s.category_id ?? '' }); };

    const submitCreate = (e) => {
        e.preventDefault();
        createForm.post('/admin/subcategories', { onSuccess: () => { createForm.reset(); setShowCreate(false); } });
    };

    const submitEdit = (e) => {
        e.preventDefault();
        editForm.put(`/admin/subcategories/${editId}`, { onSuccess: () => setEditId(null) });
    };

    const destroy = (id) => {
        if (!confirm('حذف هذه الفئة الفرعية؟')) return;
        router.delete(`/admin/subcategories/${id}`, { preserveScroll: true });
    };

    return (
        <AdminLayout title="الفئات الفرعية">
            <Head title="الفئات الفرعية — Skillify" />

            <div style={{ display: 'flex', alignItems: 'center', justifyContent: 'space-between' }}>
                <div>
                    <div style={{ fontSize: 18, fontWeight: 700, color: '#0F172A' }}>الفئات الفرعية</div>
                    <div style={{ fontSize: 12, color: '#475569' }}>{(subcategories ?? []).length} فئة فرعية</div>
                </div>
                <button onClick={() => setShowCreate(v => !v)} style={{ display: 'inline-flex', alignItems: 'center', gap: 6, padding: '8px 16px', background: '#0D9488', color: '#fff', border: 'none', borderRadius: 9, fontSize: 12, fontWeight: 600, cursor: 'pointer' }}>
                    <i className="ti ti-plus" /> إضافة
                </button>
            </div>

            {showCreate && (
                <div style={{ background: '#fff', border: '1px solid rgba(0,0,0,0.07)', borderRadius: 12, padding: 18 }}>
                    <div style={{ fontSize: 13, fontWeight: 700, marginBottom: 12 }}>فئة فرعية جديدة</div>
                    <form onSubmit={submitCreate}>
                        <div style={{ display: 'grid', gridTemplateColumns: '1fr 1fr 1fr', gap: 10 }}>
                            <div><label style={{ fontSize: 11, color: '#475569', display: 'block', marginBottom: 3 }}>عربي *</label><input style={INPUT} value={createForm.data.name_ar} onChange={e => createForm.setData('name_ar', e.target.value)} required /></div>
                            <div><label style={{ fontSize: 11, color: '#475569', display: 'block', marginBottom: 3 }}>إنجليزي *</label><input style={INPUT} value={createForm.data.name_en} onChange={e => createForm.setData('name_en', e.target.value)} required /></div>
                            <div><label style={{ fontSize: 11, color: '#475569', display: 'block', marginBottom: 3 }}>الفئة *</label>
                                <select style={INPUT} value={createForm.data.category_id} onChange={e => createForm.setData('category_id', e.target.value)} required>
                                    <option value="">— اختر —</option>
                                    {(categories ?? []).map(c => <option key={c.id} value={c.id}>{c.name_ar ?? c.name_en}</option>)}
                                </select>
                            </div>
                        </div>
                        <div style={{ display: 'flex', gap: 8, justifyContent: 'flex-end', marginTop: 10 }}>
                            <button type="button" onClick={() => setShowCreate(false)} style={{ padding: '6px 12px', borderRadius: 6, border: '0.5px solid rgba(0,0,0,0.12)', background: 'none', fontSize: 12, cursor: 'pointer' }}>إلغاء</button>
                            <button type="submit" disabled={createForm.processing} style={{ padding: '6px 14px', borderRadius: 6, background: '#0D9488', color: '#fff', border: 'none', fontSize: 12, fontWeight: 600, cursor: 'pointer' }}>حفظ</button>
                        </div>
                    </form>
                </div>
            )}

            <div style={{ background: '#fff', border: '0.5px solid rgba(0,0,0,0.07)', borderRadius: 14, overflow: 'hidden' }}>
                <table style={{ width: '100%', borderCollapse: 'collapse', fontSize: 12 }}>
                    <thead>
                        <tr style={{ background: '#F8FAFC', borderBottom: '0.5px solid rgba(0,0,0,0.07)' }}>
                            {['#','عربي','إنجليزي','الفئة','إجراءات'].map(h => (
                                <th key={h} style={{ padding: '10px 14px', textAlign: 'right', fontWeight: 600, color: '#475569' }}>{h}</th>
                            ))}
                        </tr>
                    </thead>
                    <tbody>
                        {!(subcategories ?? []).length ? (
                            <tr><td colSpan={5} style={{ padding: '36px', textAlign: 'center', color: '#94A3B8' }}>لا توجد فئات فرعية</td></tr>
                        ) : (subcategories ?? []).map((s, i) => (
                            editId === s.id ? (
                                <tr key={s.id} style={{ background: '#F0FDFA', borderBottom: '0.5px solid rgba(0,0,0,0.07)' }}>
                                    <td style={{ padding: '8px 14px', color: '#94A3B8' }}>{i+1}</td>
                                    <td style={{ padding: '8px 14px' }}><input style={{ ...INPUT, width: 'auto' }} value={editForm.data.name_ar} onChange={e => editForm.setData('name_ar', e.target.value)} /></td>
                                    <td style={{ padding: '8px 14px' }}><input style={{ ...INPUT, width: 'auto' }} value={editForm.data.name_en} onChange={e => editForm.setData('name_en', e.target.value)} /></td>
                                    <td style={{ padding: '8px 14px' }}>
                                        <select style={{ ...INPUT, width: 'auto' }} value={editForm.data.category_id} onChange={e => editForm.setData('category_id', e.target.value)}>
                                            <option value="">—</option>
                                            {(categories ?? []).map(c => <option key={c.id} value={c.id}>{c.name_en}</option>)}
                                        </select>
                                    </td>
                                    <td style={{ padding: '8px 14px' }}>
                                        <div style={{ display: 'flex', gap: 5 }}>
                                            <button onClick={submitEdit} style={{ padding: '4px 10px', borderRadius: 6, background: '#0D9488', color: '#fff', border: 'none', fontSize: 11, cursor: 'pointer' }}>حفظ</button>
                                            <button onClick={() => setEditId(null)} style={{ padding: '4px 8px', borderRadius: 6, border: '0.5px solid rgba(0,0,0,0.12)', background: 'none', fontSize: 11, cursor: 'pointer' }}>إلغاء</button>
                                        </div>
                                    </td>
                                </tr>
                            ) : (
                                <tr key={s.id} style={{ borderBottom: '0.5px solid rgba(0,0,0,0.05)' }}
                                    onMouseEnter={e => e.currentTarget.style.background = '#F8FAFC'}
                                    onMouseLeave={e => e.currentTarget.style.background = 'transparent'}
                                >
                                    <td style={{ padding: '10px 14px', color: '#94A3B8' }}>{i+1}</td>
                                    <td style={{ padding: '10px 14px', fontWeight: 600, color: '#0F172A' }}>{s.name_ar}</td>
                                    <td style={{ padding: '10px 14px', color: '#475569' }}>{s.name_en}</td>
                                    <td style={{ padding: '10px 14px', color: '#94A3B8' }}>{s.category?.name_en ?? '—'}</td>
                                    <td style={{ padding: '10px 14px' }}>
                                        <div style={{ display: 'flex', gap: 5 }}>
                                            <button onClick={() => startEdit(s)} style={{ padding: '4px 8px', borderRadius: 6, border: '0.5px solid rgba(0,0,0,0.12)', background: 'none', fontSize: 11, cursor: 'pointer', color: '#475569' }}><i className="ti ti-pencil" /></button>
                                            <button onClick={() => destroy(s.id)} style={{ padding: '4px 8px', borderRadius: 6, border: 'none', background: '#FEF2F2', color: '#EF4444', fontSize: 11, cursor: 'pointer' }}><i className="ti ti-trash" /></button>
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
