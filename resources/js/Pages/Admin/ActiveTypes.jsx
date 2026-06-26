import { Head, router, useForm } from '@inertiajs/react';
import { useState } from 'react';
import AdminLayout from '../../Layouts/AdminLayout';

const INPUT = { width: '100%', padding: '8px 12px', border: '0.5px solid rgba(0,0,0,0.12)', borderRadius: 7, fontSize: 12, outline: 'none', boxSizing: 'border-box' };

export default function ActiveTypes({ types }) {
    const [showCreate, setShowCreate] = useState(false);
    const { data, setData, post, processing, errors, reset } = useForm({ name_en: '', name_ar: '' });

    const submit = (e) => {
        e.preventDefault();
        post('/admin/active-types', { onSuccess: () => { reset(); setShowCreate(false); } });
    };

    const destroy = (id) => {
        if (!confirm('حذف نوع النشاط هذا؟')) return;
        router.delete(`/admin/active-types/${id}`, { preserveScroll: true });
    };

    return (
        <AdminLayout title="أنواع النشاط">
            <Head title="أنواع النشاط — Skillify" />

            <div style={{ display: 'flex', alignItems: 'center', justifyContent: 'space-between' }}>
                <div>
                    <div style={{ fontSize: 18, fontWeight: 700, color: '#0F172A' }}>أنواع النشاط</div>
                    <div style={{ fontSize: 12, color: '#475569' }}>{(types ?? []).length} نوع</div>
                </div>
                <button onClick={() => setShowCreate(v => !v)} style={{ display: 'inline-flex', alignItems: 'center', gap: 6, padding: '8px 16px', background: '#0D9488', color: '#fff', border: 'none', borderRadius: 9, fontSize: 12, fontWeight: 600, cursor: 'pointer' }}>
                    <i className="ti ti-plus" /> إضافة نوع
                </button>
            </div>

            {showCreate && (
                <div style={{ background: '#fff', border: '1px solid rgba(0,0,0,0.07)', borderRadius: 12, padding: 18 }}>
                    <div style={{ fontSize: 13, fontWeight: 700, marginBottom: 12 }}>نوع نشاط جديد</div>
                    <form onSubmit={submit}>
                        <div style={{ display: 'grid', gridTemplateColumns: '1fr 1fr', gap: 12 }}>
                            <div>
                                <label style={{ fontSize: 11, color: '#475569', display: 'block', marginBottom: 3 }}>الاسم بالإنجليزية *</label>
                                <input style={INPUT} value={data.name_en} onChange={e => setData('name_en', e.target.value)} required />
                                {errors.name_en && <p style={{ fontSize: 11, color: '#EF4444', marginTop: 3 }}>{errors.name_en}</p>}
                            </div>
                            <div>
                                <label style={{ fontSize: 11, color: '#475569', display: 'block', marginBottom: 3 }}>الاسم بالعربية *</label>
                                <input style={INPUT} value={data.name_ar} onChange={e => setData('name_ar', e.target.value)} required />
                                {errors.name_ar && <p style={{ fontSize: 11, color: '#EF4444', marginTop: 3 }}>{errors.name_ar}</p>}
                            </div>
                        </div>
                        <div style={{ display: 'flex', gap: 8, justifyContent: 'flex-end', marginTop: 10 }}>
                            <button type="button" onClick={() => setShowCreate(false)} style={{ padding: '6px 12px', borderRadius: 6, border: '0.5px solid rgba(0,0,0,0.12)', background: 'none', fontSize: 12, cursor: 'pointer' }}>إلغاء</button>
                            <button type="submit" disabled={processing} style={{ padding: '6px 14px', borderRadius: 6, background: '#0D9488', color: '#fff', border: 'none', fontSize: 12, fontWeight: 600, cursor: 'pointer', opacity: processing ? 0.7 : 1 }}>
                                {processing ? 'جارٍ الإضافة...' : 'إضافة'}
                            </button>
                        </div>
                    </form>
                </div>
            )}

            <div style={{ background: '#fff', border: '0.5px solid rgba(0,0,0,0.07)', borderRadius: 14, overflow: 'hidden' }}>
                <table style={{ width: '100%', borderCollapse: 'collapse', fontSize: 12 }}>
                    <thead>
                        <tr style={{ background: '#F8FAFC', borderBottom: '0.5px solid rgba(0,0,0,0.07)' }}>
                            {['#', 'إنجليزي', 'عربي', 'إجراء'].map(h => (
                                <th key={h} style={{ padding: '10px 14px', textAlign: 'right', fontWeight: 600, color: '#475569' }}>{h}</th>
                            ))}
                        </tr>
                    </thead>
                    <tbody>
                        {!(types ?? []).length ? (
                            <tr><td colSpan={4} style={{ padding: '40px', textAlign: 'center', color: '#94A3B8' }}>
                                <i className="ti ti-tag" style={{ fontSize: 32, display: 'block', marginBottom: 8, opacity: 0.3 }} />
                                لا توجد أنواع نشاط بعد
                            </td></tr>
                        ) : (types ?? []).map((t, i) => (
                            <tr key={t.id} style={{ borderBottom: '0.5px solid rgba(0,0,0,0.05)' }}
                                onMouseEnter={e => e.currentTarget.style.background = '#F8FAFC'}
                                onMouseLeave={e => e.currentTarget.style.background = 'transparent'}
                            >
                                <td style={{ padding: '10px 14px', color: '#94A3B8' }}>{i + 1}</td>
                                <td style={{ padding: '10px 14px', fontWeight: 600, color: '#0F172A' }}>{t.name_en}</td>
                                <td style={{ padding: '10px 14px', color: '#475569' }} dir="rtl">{t.name_ar}</td>
                                <td style={{ padding: '10px 14px' }}>
                                    <button onClick={() => destroy(t.id)} style={{ padding: '4px 10px', borderRadius: 6, border: 'none', background: '#FEF2F2', color: '#EF4444', fontSize: 11, cursor: 'pointer', display: 'inline-flex', alignItems: 'center', gap: 4 }}>
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
