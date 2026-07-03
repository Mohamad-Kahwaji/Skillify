import { Head, router, useForm } from '@inertiajs/react';
import { useState } from 'react';
import SuperAdminLayout from '../../Layouts/SuperAdminLayout';

const P = '#7C3AED';
const INPUT = { width: '100%', padding: '9px 13px', border: '1px solid rgba(124,58,237,0.2)', borderRadius: 9, fontSize: 13, outline: 'none', background: '#fff', fontFamily: "'Cairo','Inter',sans-serif", boxSizing: 'border-box', color: '#1E1B4B' };

export default function ActiveTypes({ types }) {
    const [showCreate, setShowCreate] = useState(false);
    const [editingId,  setEditingId]  = useState(null);
    const [editName,   setEditName]   = useState('');
    const [saving,     setSaving]     = useState(false);

    const { data, setData, post, processing, errors, reset } = useForm({ name: '' });

    const submit = (e) => {
        e.preventDefault();
        post('/super-admin/active-types', { onSuccess: () => { reset(); setShowCreate(false); } });
    };

    const startEdit = (t) => { setShowCreate(false); setEditingId(t.id); setEditName(t.name); };
    const cancelEdit = () => { setEditingId(null); setEditName(''); };
    const saveEdit = (id) => {
        if (!editName.trim()) return;
        setSaving(true);
        router.patch(`/super-admin/active-types/${id}`, { name: editName.trim() }, {
            preserveScroll: true,
            onSuccess: () => { setEditingId(null); setEditName(''); },
            onFinish:  () => setSaving(false),
        });
    };

    const destroy = (id) => {
        if (!confirm('حذف نوع النشاط هذا؟')) return;
        router.delete(`/super-admin/active-types/${id}`, { preserveScroll: true });
    };

    const all = types ?? [];

    return (
        <SuperAdminLayout title="أنواع النشاط">
            <Head title="أنواع النشاط — Skillify" />

            <div style={{ display: 'flex', alignItems: 'center', justifyContent: 'space-between', flexWrap: 'wrap', gap: 12 }}>
                <div>
                    <h1 style={{ fontSize: 22, fontWeight: 800, color: '#1E1B4B', margin: 0, display: 'flex', alignItems: 'center', gap: 10 }}>
                        <span style={{ width: 36, height: 36, borderRadius: 10, background: 'linear-gradient(135deg,#7C3AED,#6D28D9)', display: 'inline-flex', alignItems: 'center', justifyContent: 'center', boxShadow: '0 4px 12px rgba(124,58,237,0.3)' }}>
                            <i className="ti ti-activity" style={{ color: '#fff', fontSize: 17 }} />
                        </span>
                        أنواع النشاط
                    </h1>
                    <p style={{ fontSize: 12, color: '#94A3B8', marginTop: 5, marginRight: 46 }}>{all.length} نوع مسجّل</p>
                </div>
                <button onClick={() => { setShowCreate(v => !v); setEditingId(null); }}
                    style={{ display: 'inline-flex', alignItems: 'center', gap: 8, padding: '11px 22px', background: 'linear-gradient(135deg,#7C3AED,#6D28D9)', color: '#fff', border: 'none', borderRadius: 12, fontSize: 13, fontWeight: 700, cursor: 'pointer', fontFamily: "'Cairo','Inter',sans-serif", boxShadow: '0 4px 16px rgba(124,58,237,0.35)', transition: 'transform 0.15s' }}
                    onMouseEnter={e => e.currentTarget.style.transform = 'translateY(-1px)'}
                    onMouseLeave={e => e.currentTarget.style.transform = 'translateY(0)'}>
                    <i className="ti ti-plus" style={{ fontSize: 15 }} /> إضافة نوع
                </button>
            </div>

            {showCreate && (
                <div style={{ background: '#fff', border: '1px solid rgba(124,58,237,0.15)', borderRadius: 16, padding: '22px 24px', boxShadow: '0 4px 20px rgba(124,58,237,0.08)' }}>
                    <div style={{ fontSize: 14, fontWeight: 700, color: '#1E1B4B', marginBottom: 16 }}>نوع نشاط جديد</div>
                    <form onSubmit={submit}>
                        <div style={{ marginBottom: 16 }}>
                            <label style={{ fontSize: 11, fontWeight: 700, color: '#6B7280', display: 'block', marginBottom: 6 }}>اسم النوع *</label>
                            <input dir="rtl" autoFocus style={{ ...INPUT, maxWidth: 320 }} placeholder="مثال: عرض خدمة" value={data.name} onChange={e => setData('name', e.target.value)} required />
                            {errors.name && <p style={{ fontSize: 11, color: '#DC2626', marginTop: 4 }}>{errors.name}</p>}
                        </div>
                        <div style={{ display: 'flex', gap: 10 }}>
                            <button type="button" onClick={() => setShowCreate(false)} style={{ padding: '8px 18px', borderRadius: 9, border: '1px solid rgba(0,0,0,0.12)', background: 'none', fontSize: 13, cursor: 'pointer', fontFamily: "'Cairo','Inter',sans-serif", color: '#4B5563' }}>إلغاء</button>
                            <button type="submit" disabled={processing} style={{ padding: '8px 22px', borderRadius: 9, background: 'linear-gradient(135deg,#7C3AED,#6D28D9)', color: '#fff', border: 'none', fontSize: 13, fontWeight: 700, cursor: 'pointer', fontFamily: "'Cairo','Inter',sans-serif", opacity: processing ? 0.7 : 1 }}>إضافة</button>
                        </div>
                    </form>
                </div>
            )}

            <div style={{ background: '#fff', border: '1px solid rgba(0,0,0,0.06)', borderRadius: 18, overflow: 'hidden', boxShadow: '0 2px 16px rgba(0,0,0,0.05)' }}>
                <div style={{ overflowX: 'auto' }}>
                <div style={{ padding: '13px 20px', background: 'linear-gradient(135deg,#F8F7FF,#F3F0FF)', borderBottom: '1px solid rgba(124,58,237,0.1)', display: 'grid', gridTemplateColumns: '50px 1fr 110px', gap: 8, minWidth: 420 }}>
                    {['#', 'النوع', 'إجراء'].map((h, idx) => (
                        <div key={h} style={{ fontSize: 11, fontWeight: 800, color: '#6B21A8', textTransform: 'uppercase', letterSpacing: '0.06em', textAlign: idx === 2 ? 'center' : 'right' }}>{h}</div>
                    ))}
                </div>

                {!all.length ? (
                    <div style={{ padding: '56px', textAlign: 'center', color: '#94A3B8' }}>
                        <i className="ti ti-activity" style={{ fontSize: 40, display: 'block', marginBottom: 10, opacity: 0.15 }} />
                        <div style={{ fontSize: 13 }}>لا توجد أنواع نشاط</div>
                    </div>
                ) : all.map((t, i) => (
                    <div key={t.id}
                        style={{ padding: '12px 20px', borderBottom: '1px solid rgba(0,0,0,0.04)', display: 'grid', gridTemplateColumns: '50px 1fr 110px', alignItems: 'center', gap: 8, minWidth: 420, transition: 'background 0.15s' }}
                        onMouseEnter={e => e.currentTarget.style.background = '#F9F7FF'}
                        onMouseLeave={e => e.currentTarget.style.background = 'transparent'}>

                        <span style={{ fontSize: 12, color: '#C4B5FD', fontWeight: 700, background: '#F5F3FF', width: 28, height: 28, borderRadius: 8, display: 'inline-flex', alignItems: 'center', justifyContent: 'center' }}>
                            {i + 1}
                        </span>

                        {editingId === t.id ? (
                            <input dir="rtl" autoFocus value={editName} onChange={e => setEditName(e.target.value)}
                                onKeyDown={e => { if (e.key === 'Enter') saveEdit(t.id); if (e.key === 'Escape') cancelEdit(); }}
                                style={{ ...INPUT, maxWidth: 280, padding: '6px 10px', fontSize: 13 }} />
                        ) : (
                            <div style={{ display: 'flex', alignItems: 'center', gap: 10 }}>
                                <span style={{ width: 32, height: 32, borderRadius: 9, background: 'linear-gradient(135deg,#EDE9FE,#DDD6FE)', display: 'inline-flex', alignItems: 'center', justifyContent: 'center' }}>
                                    <i className="ti ti-activity" style={{ color: P, fontSize: 14 }} />
                                </span>
                                <span style={{ fontWeight: 700, color: '#1E1B4B', fontSize: 14 }}>{t.name}</span>
                            </div>
                        )}

                        <div style={{ display: 'flex', justifyContent: 'center', gap: 6 }}>
                            {editingId === t.id ? (
                                <>
                                    <button onClick={() => saveEdit(t.id)} disabled={saving} title="حفظ"
                                        style={{ width: 32, height: 32, borderRadius: 8, border: '1px solid #6EE7B7', background: '#ECFDF5', color: '#065F46', fontSize: 14, cursor: 'pointer', display: 'inline-flex', alignItems: 'center', justifyContent: 'center', opacity: saving ? 0.6 : 1 }}>
                                        <i className="ti ti-check" />
                                    </button>
                                    <button onClick={cancelEdit} title="إلغاء"
                                        style={{ width: 32, height: 32, borderRadius: 8, border: '1px solid rgba(0,0,0,0.1)', background: '#F8FAFC', color: '#64748B', fontSize: 14, cursor: 'pointer', display: 'inline-flex', alignItems: 'center', justifyContent: 'center' }}>
                                        <i className="ti ti-x" />
                                    </button>
                                </>
                            ) : (
                                <>
                                    <button onClick={() => startEdit(t)} title="تعديل"
                                        style={{ width: 32, height: 32, borderRadius: 8, border: '1px solid rgba(124,58,237,0.2)', background: '#F5F3FF', color: P, fontSize: 14, cursor: 'pointer', display: 'inline-flex', alignItems: 'center', justifyContent: 'center', transition: 'background 0.15s' }}
                                        onMouseEnter={e => e.currentTarget.style.background = '#EDE9FE'}
                                        onMouseLeave={e => e.currentTarget.style.background = '#F5F3FF'}>
                                        <i className="ti ti-pencil" />
                                    </button>
                                    <button onClick={() => destroy(t.id)} title="حذف"
                                        style={{ width: 32, height: 32, borderRadius: 8, border: '1px solid #FECACA', background: '#FEF2F2', color: '#DC2626', fontSize: 14, cursor: 'pointer', display: 'inline-flex', alignItems: 'center', justifyContent: 'center', transition: 'background 0.15s' }}
                                        onMouseEnter={e => e.currentTarget.style.background = '#FEE2E2'}
                                        onMouseLeave={e => e.currentTarget.style.background = '#FEF2F2'}>
                                        <i className="ti ti-trash" />
                                    </button>
                                </>
                            )}
                        </div>
                    </div>
                ))}
                </div>
            </div>
        </SuperAdminLayout>
    );
}
