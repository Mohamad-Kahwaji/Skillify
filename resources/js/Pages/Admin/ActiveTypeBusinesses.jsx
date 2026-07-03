import { Head, router, useForm } from '@inertiajs/react';
import { useState } from 'react';
import AdminLayout, { C } from '../../Layouts/AdminLayout';
import { PageHeader, PrimaryBtn, EmptyState, INPUT_STYLE } from './Users';

export default function ActiveTypeBusinesses({ types }) {
    const [showCreate, setShowCreate] = useState(false);
    const [editingId,  setEditingId]  = useState(null);
    const [editName,   setEditName]   = useState('');
    const [saving,     setSaving]     = useState(false);

    const { data, setData, post, processing, errors, reset } = useForm({ name: '' });

    const submit = (e) => {
        e.preventDefault();
        post('/admin/active-type-businesses', {
            onSuccess: () => { reset(); setShowCreate(false); },
        });
    };

    const startEdit = (t) => {
        setShowCreate(false);
        setEditingId(t.id);
        setEditName(t.name);
    };

    const cancelEdit = () => { setEditingId(null); setEditName(''); };

    const saveEdit = (id) => {
        if (!editName.trim()) return;
        setSaving(true);
        router.patch(`/admin/active-type-businesses/${id}`, { name: editName.trim() }, {
            preserveScroll: true,
            onSuccess: () => { setEditingId(null); setEditName(''); },
            onFinish:  () => setSaving(false),
        });
    };

    const destroy = (id) => {
        if (!confirm('حذف نوع النشاط التجاري هذا؟')) return;
        router.delete(`/admin/active-type-businesses/${id}`, { preserveScroll: true });
    };

    const all = types ?? [];

    return (
        <AdminLayout title="أنواع الأعمال">
            <Head title="أنواع الأعمال — Skillify" />

            <PageHeader title="أنواع الأعمال" sub={`${all.length} نوع`}>
                <PrimaryBtn icon="ti-plus" onClick={() => { setShowCreate(v => !v); setEditingId(null); }} color={C.teal}>
                    إضافة نوع
                </PrimaryBtn>
            </PageHeader>

            {showCreate && (
                <div style={{ background: C.cardBg, border: C.cardBorder, borderRadius: 16, padding: '22px 24px', boxShadow: C.cardShadow }}>
                    <div style={{ fontSize: 14, fontWeight: 700, color: C.textDark, marginBottom: 16 }}>نوع عمل جديد</div>
                    <form onSubmit={submit}>
                        <div style={{ marginBottom: 16 }}>
                            <label style={{ fontSize: 11, fontWeight: 600, color: C.textMuted, display: 'block', marginBottom: 6 }}>اسم النوع *</label>
                            <input
                                dir="rtl"
                                autoFocus
                                style={{ ...INPUT_STYLE, maxWidth: 320 }}
                                placeholder="مثال: مهنة"
                                value={data.name}
                                onChange={e => setData('name', e.target.value)}
                                required
                            />
                            {errors.name && <p style={{ fontSize: 11, color: C.dangerText, marginTop: 4 }}>{errors.name}</p>}
                        </div>
                        <div style={{ display: 'flex', gap: 10 }}>
                            <button type="button" onClick={() => setShowCreate(false)}
                                style={{ padding: '8px 18px', borderRadius: 9, border: C.cardBorder, background: 'none', fontSize: 13, cursor: 'pointer', fontFamily: "'Cairo','Inter',sans-serif", color: C.textMuted }}>
                                إلغاء
                            </button>
                            <button type="submit" disabled={processing}
                                style={{ padding: '8px 22px', borderRadius: 9, background: C.teal, color: '#fff', border: 'none', fontSize: 13, fontWeight: 700, cursor: 'pointer', fontFamily: "'Cairo','Inter',sans-serif", opacity: processing ? 0.7 : 1 }}>
                                إضافة
                            </button>
                        </div>
                    </form>
                </div>
            )}

            <div style={{ background: C.cardBg, borderRadius: 16, boxShadow: C.cardShadow, border: C.cardBorder, overflow: 'hidden' }}>
                <div style={{ padding: '13px 20px', background: C.pageBg, borderBottom: C.cardBorder, display: 'grid', gridTemplateColumns: '50px 1fr 110px', gap: 8 }}>
                    {['#', 'النوع', 'إجراء'].map((h, idx) => (
                        <div key={h} style={{ fontSize: 11, fontWeight: 700, color: C.textMuted, textTransform: 'uppercase', letterSpacing: '0.05em', textAlign: idx === 2 ? 'center' : 'right' }}>{h}</div>
                    ))}
                </div>

                {!all.length ? (
                    <div style={{ padding: 60 }}><EmptyState icon="ti-briefcase" text="لا توجد أنواع أعمال بعد" /></div>
                ) : all.map((t, i) => (
                    <div key={t.id}
                        style={{ padding: '12px 20px', borderBottom: `1px solid rgba(15,23,42,0.04)`, display: 'grid', gridTemplateColumns: '50px 1fr 110px', alignItems: 'center', gap: 8, transition: 'background 0.15s' }}
                        onMouseEnter={e => e.currentTarget.style.background = C.pageBg}
                        onMouseLeave={e => e.currentTarget.style.background = 'transparent'}>

                        <span style={{ fontSize: 12, color: C.textFaint, fontWeight: 700, background: C.pageBg, width: 28, height: 28, borderRadius: 8, display: 'inline-flex', alignItems: 'center', justifyContent: 'center' }}>
                            {i + 1}
                        </span>

                        {editingId === t.id ? (
                            <input
                                dir="rtl"
                                autoFocus
                                value={editName}
                                onChange={e => setEditName(e.target.value)}
                                onKeyDown={e => { if (e.key === 'Enter') saveEdit(t.id); if (e.key === 'Escape') cancelEdit(); }}
                                style={{ ...INPUT_STYLE, maxWidth: 280, padding: '6px 10px', fontSize: 13 }}
                            />
                        ) : (
                            <div style={{ display: 'flex', alignItems: 'center', gap: 10 }}>
                                <span style={{ width: 32, height: 32, borderRadius: 9, background: '#E6F9F6', display: 'inline-flex', alignItems: 'center', justifyContent: 'center' }}>
                                    <i className="ti ti-briefcase" style={{ color: C.teal, fontSize: 14 }} />
                                </span>
                                <span style={{ fontWeight: 700, color: C.textDark, fontSize: 14 }}>{t.name}</span>
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
                                        style={{ width: 32, height: 32, borderRadius: 8, border: C.cardBorder, background: C.pageBg, color: C.textMuted, fontSize: 14, cursor: 'pointer', display: 'inline-flex', alignItems: 'center', justifyContent: 'center' }}>
                                        <i className="ti ti-x" />
                                    </button>
                                </>
                            ) : (
                                <>
                                    <button onClick={() => startEdit(t)} title="تعديل"
                                        style={{ width: 32, height: 32, borderRadius: 8, border: `1px solid rgba(13,148,136,0.25)`, background: '#F0FDFA', color: C.teal, fontSize: 14, cursor: 'pointer', display: 'inline-flex', alignItems: 'center', justifyContent: 'center', transition: 'background 0.15s' }}
                                        onMouseEnter={e => e.currentTarget.style.background = '#CCFBF1'}
                                        onMouseLeave={e => e.currentTarget.style.background = '#F0FDFA'}>
                                        <i className="ti ti-pencil" />
                                    </button>
                                    <button onClick={() => destroy(t.id)} title="حذف"
                                        style={{ width: 32, height: 32, borderRadius: 8, border: `1px solid ${C.dangerBorder}`, background: C.dangerBg, color: C.dangerText, fontSize: 14, cursor: 'pointer', display: 'inline-flex', alignItems: 'center', justifyContent: 'center', transition: 'background 0.15s' }}
                                        onMouseEnter={e => e.currentTarget.style.background = '#FEE2E2'}
                                        onMouseLeave={e => e.currentTarget.style.background = C.dangerBg}>
                                        <i className="ti ti-trash" />
                                    </button>
                                </>
                            )}
                        </div>
                    </div>
                ))}
            </div>
        </AdminLayout>
    );
}
