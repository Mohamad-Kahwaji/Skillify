import { Head, router, useForm } from '@inertiajs/react';
import { useState } from 'react';
import SuperAdminLayout from '../../Layouts/SuperAdminLayout';

const P  = '#7C3AED';
const P2 = '#6D28D9';
const BT_COLORS = ['#7C3AED','#0D9488','#2563EB','#D97706','#DC2626','#0891B2'];

const INPUT = {
    width: '100%', padding: '9px 13px', border: '1px solid rgba(0,0,0,0.12)', borderRadius: 9,
    fontSize: 13, outline: 'none', background: '#fff', fontFamily: "'Cairo','Inter',sans-serif",
    boxSizing: 'border-box', color: '#0F172A',
};

function BusinessBadge({ name, idx }) {
    if (!name) return <span style={{ color: '#94A3B8', fontSize: 12 }}>—</span>;
    const col = BT_COLORS[idx % BT_COLORS.length];
    return (
        <span style={{ display: 'inline-flex', alignItems: 'center', gap: 5, fontSize: 11, fontWeight: 700, padding: '4px 10px', borderRadius: 20, background: col + '18', color: col, border: `1px solid ${col}33` }}>
            <i className="ti ti-briefcase" style={{ fontSize: 10 }} />{name}
        </span>
    );
}

export default function Categories({ categories, businessTypes }) {
    const [editId, setEditId]         = useState(null);
    const [showCreate, setShowCreate] = useState(false);
    const [search, setSearch]         = useState('');

    const createForm = useForm({ name: '', active_typebusiness_id: '' });
    const editForm   = useForm({ name: '', active_typebusiness_id: '' });

    const all = categories ?? [];
    const allBts = businessTypes ?? [];

    const btMap = {};
    allBts.forEach((t, i) => { btMap[t.id] = { name: t.name, idx: i }; });

    const filtered = all.filter(c =>
        `${c.name} ${c.activeTypebusiness?.name ?? ''}`.toLowerCase().includes(search.toLowerCase())
    );

    const startEdit = (c) => {
        setEditId(c.id);
        editForm.setData({ name: c.name ?? '', active_typebusiness_id: c.active_typebusiness_id ?? '' });
    };

    const submitCreate = (e) => {
        e.preventDefault();
        createForm.post('/super-admin/categories', { onSuccess: () => { createForm.reset(); setShowCreate(false); } });
    };

    const submitEdit = (e) => {
        e.preventDefault();
        editForm.put(`/super-admin/categories/${editId}`, { onSuccess: () => setEditId(null) });
    };

    const destroy = (id) => {
        if (!confirm('حذف هذه الفئة؟')) return;
        router.delete(`/super-admin/categories/${id}`, { preserveScroll: true });
    };

    const COLS = ['50px', '1fr', '1fr', '120px'];

    return (
        <SuperAdminLayout title="الفئات">
            <Head title="الفئات — Skillify" />

            {/* Header */}
            <div style={{ display: 'flex', alignItems: 'center', justifyContent: 'space-between', flexWrap: 'wrap', gap: 14 }}>
                <div>
                    <h1 style={{ fontSize: 22, fontWeight: 800, color: '#1E1B4B', margin: 0, display: 'flex', alignItems: 'center', gap: 10 }}>
                        <span style={{ width: 36, height: 36, borderRadius: 10, background: `linear-gradient(135deg,${P},${P2})`, display: 'inline-flex', alignItems: 'center', justifyContent: 'center', boxShadow: `0 4px 12px ${P}44` }}>
                            <i className="ti ti-tag" style={{ color: '#fff', fontSize: 17 }} />
                        </span>
                        الفئات
                    </h1>
                    <p style={{ fontSize: 12, color: '#94A3B8', marginTop: 5, marginRight: 46 }}>{all.length} فئة مسجّلة</p>
                </div>
                <div style={{ display: 'flex', gap: 10, alignItems: 'center' }}>
                    <div style={{ position: 'relative' }}>
                        <i className="ti ti-search" style={{ position: 'absolute', right: 11, top: '50%', transform: 'translateY(-50%)', color: '#94A3B8', fontSize: 13, pointerEvents: 'none' }} />
                        <input value={search} onChange={e => setSearch(e.target.value)} placeholder="بحث..."
                            style={{ ...INPUT, width: 200, paddingRight: 34 }} />
                    </div>
                    <button onClick={() => { setShowCreate(v => !v); setEditId(null); }} style={{
                        display: 'inline-flex', alignItems: 'center', gap: 7, padding: '10px 20px',
                        background: `linear-gradient(135deg,${P},${P2})`, color: '#fff', border: 'none', borderRadius: 11,
                        fontSize: 13, fontWeight: 700, cursor: 'pointer', fontFamily: "'Cairo','Inter',sans-serif",
                        boxShadow: `0 4px 14px ${P}44`,
                    }}>
                        <i className="ti ti-plus" style={{ fontSize: 14 }} /> إضافة فئة
                    </button>
                </div>
            </div>

            {/* Create form */}
            {showCreate && (
                <div style={{ background: '#fff', border: `1px solid ${P}22`, borderRadius: 16, padding: '20px 24px', boxShadow: `0 4px 20px ${P}12` }}>
                    <div style={{ fontSize: 14, fontWeight: 700, color: '#1E1B4B', marginBottom: 16, display: 'flex', alignItems: 'center', gap: 8 }}>
                        <i className="ti ti-plus" style={{ color: P }} /> فئة جديدة
                    </div>
                    <form onSubmit={submitCreate}>
                        <div className="grid grid-cols-1 sm:grid-cols-2" style={{ display: 'grid', gap: 14, marginBottom: 16 }}>
                            <div>
                                <label style={{ fontSize: 11, fontWeight: 700, color: '#475569', display: 'block', marginBottom: 6 }}>اسم الفئة *</label>
                                <input dir="rtl" style={INPUT} placeholder="مثال: الصحة والطب" value={createForm.data.name} onChange={e => createForm.setData('name', e.target.value)} required />
                                {createForm.errors.name && <p style={{ fontSize: 11, color: '#DC2626', marginTop: 4 }}>{createForm.errors.name}</p>}
                            </div>
                            <div>
                                <label style={{ fontSize: 11, fontWeight: 700, color: '#475569', display: 'block', marginBottom: 6 }}>نوع النشاط *</label>
                                <select dir="rtl" style={INPUT} value={createForm.data.active_typebusiness_id} onChange={e => createForm.setData('active_typebusiness_id', e.target.value)} required>
                                    <option value="">— اختر نوع النشاط —</option>
                                    {allBts.map(t => <option key={t.id} value={t.id}>{t.name}</option>)}
                                </select>
                                {createForm.errors.active_typebusiness_id && <p style={{ fontSize: 11, color: '#DC2626', marginTop: 4 }}>{createForm.errors.active_typebusiness_id}</p>}
                            </div>
                        </div>
                        <div style={{ display: 'flex', gap: 8 }}>
                            <button type="button" onClick={() => setShowCreate(false)} style={{ padding: '8px 18px', borderRadius: 9, border: '1px solid rgba(0,0,0,0.12)', background: 'none', fontSize: 13, cursor: 'pointer', fontFamily: "'Cairo','Inter',sans-serif", color: '#475569' }}>إلغاء</button>
                            <button type="submit" disabled={createForm.processing} style={{ padding: '8px 22px', borderRadius: 9, background: `linear-gradient(135deg,${P},${P2})`, color: '#fff', border: 'none', fontSize: 13, fontWeight: 700, cursor: 'pointer', fontFamily: "'Cairo','Inter',sans-serif", opacity: createForm.processing ? 0.7 : 1 }}>إضافة</button>
                        </div>
                    </form>
                </div>
            )}

            {/* Table */}
            <div style={{ background: '#fff', border: '0.5px solid rgba(0,0,0,0.07)', borderRadius: 18, overflow: 'hidden', boxShadow: '0 2px 16px rgba(0,0,0,0.05)' }}>
                <div style={{ overflowX: 'auto' }}>
                <div style={{ display: 'grid', gridTemplateColumns: COLS.join(' '), padding: '12px 20px', background: 'linear-gradient(135deg,#F8F7FF,#F3F0FF)', borderBottom: `1px solid ${P}18`, minWidth: 560 }}>
                    {['#', 'الفئة', 'نوع النشاط', 'إجراءات'].map((h, idx) => (
                        <div key={h} style={{ fontSize: 11, fontWeight: 800, color: '#6B21A8', textTransform: 'uppercase', letterSpacing: '0.06em', textAlign: idx === 3 ? 'center' : 'right' }}>{h}</div>
                    ))}
                </div>

                {!filtered.length ? (
                    <div style={{ padding: '56px', textAlign: 'center', color: '#94A3B8' }}>
                        <i className="ti ti-tag" style={{ fontSize: 40, display: 'block', marginBottom: 10, opacity: 0.12 }} />
                        <div style={{ fontSize: 13 }}>لا توجد فئات</div>
                    </div>
                ) : filtered.map((c, i) => {
                    const bt = btMap[c.active_typebusiness_id];
                    return editId === c.id ? (
                        <div key={c.id} style={{ display: 'grid', gridTemplateColumns: COLS.join(' '), padding: '10px 20px', alignItems: 'center', gap: 8, background: '#F5F3FF', borderBottom: `1px solid ${P}18`, minWidth: 560 }}>
                            <span style={{ fontSize: 12, color: '#C4B5FD', fontWeight: 700 }}>{i + 1}</span>
                            <input dir="rtl" style={{ ...INPUT, fontSize: 12 }} value={editForm.data.name} onChange={e => editForm.setData('name', e.target.value)} />
                            <select dir="rtl" style={{ ...INPUT, fontSize: 12 }} value={editForm.data.active_typebusiness_id} onChange={e => editForm.setData('active_typebusiness_id', e.target.value)}>
                                <option value="">— اختر —</option>
                                {allBts.map(t => <option key={t.id} value={t.id}>{t.name}</option>)}
                            </select>
                            <div style={{ display: 'flex', gap: 6, justifyContent: 'center' }}>
                                <button onClick={submitEdit} style={{ padding: '6px 14px', borderRadius: 8, background: `linear-gradient(135deg,${P},${P2})`, color: '#fff', border: 'none', fontSize: 12, cursor: 'pointer', fontWeight: 700, fontFamily: "'Cairo','Inter',sans-serif" }}>حفظ</button>
                                <button onClick={() => setEditId(null)} style={{ padding: '6px 10px', borderRadius: 8, border: '1px solid rgba(0,0,0,0.12)', background: '#fff', fontSize: 12, cursor: 'pointer', fontFamily: "'Cairo','Inter',sans-serif" }}>إلغاء</button>
                            </div>
                        </div>
                    ) : (
                        <div key={c.id}
                            style={{ display: 'grid', gridTemplateColumns: COLS.join(' '), padding: '13px 20px', borderBottom: '1px solid rgba(0,0,0,0.04)', alignItems: 'center', gap: 8, minWidth: 560, transition: 'background 0.15s' }}
                            onMouseEnter={e => e.currentTarget.style.background = '#F9F7FF'}
                            onMouseLeave={e => e.currentTarget.style.background = 'transparent'}
                        >
                            <span style={{ fontSize: 12, color: '#C4B5FD', fontWeight: 700, background: '#F5F3FF', width: 28, height: 28, borderRadius: 8, display: 'inline-flex', alignItems: 'center', justifyContent: 'center' }}>{i + 1}</span>
                            <div style={{ display: 'flex', alignItems: 'center', gap: 10 }}>
                                <span style={{ width: 32, height: 32, borderRadius: 9, background: `linear-gradient(135deg,${P}22,${P}44)`, display: 'inline-flex', alignItems: 'center', justifyContent: 'center' }}>
                                    <i className="ti ti-tag" style={{ color: P, fontSize: 14 }} />
                                </span>
                                <span style={{ fontWeight: 700, color: '#0F172A', fontSize: 14 }}>{c.name}</span>
                            </div>
                            <div>
                                <BusinessBadge name={bt?.name ?? c.activeTypebusiness?.name} idx={bt?.idx ?? i} />
                            </div>
                            <div style={{ display: 'flex', gap: 6, justifyContent: 'center' }}>
                                <button onClick={() => { startEdit(c); setShowCreate(false); }} title="تعديل" style={{
                                    width: 32, height: 32, borderRadius: 8, border: `1px solid ${P}33`, background: '#F5F3FF', color: P,
                                    fontSize: 14, cursor: 'pointer', display: 'inline-flex', alignItems: 'center', justifyContent: 'center', transition: 'all 0.13s',
                                }}
                                    onMouseEnter={e => e.currentTarget.style.background = '#EDE9FE'}
                                    onMouseLeave={e => e.currentTarget.style.background = '#F5F3FF'}
                                >
                                    <i className="ti ti-pencil" />
                                </button>
                                <button onClick={() => destroy(c.id)} title="حذف" style={{
                                    width: 32, height: 32, borderRadius: 8, border: '1px solid #FCA5A5', background: '#FEF2F2', color: '#DC2626',
                                    fontSize: 14, cursor: 'pointer', display: 'inline-flex', alignItems: 'center', justifyContent: 'center', transition: 'all 0.13s',
                                }}
                                    onMouseEnter={e => { e.currentTarget.style.background = '#FEE2E2'; e.currentTarget.style.transform = 'scale(1.08)'; }}
                                    onMouseLeave={e => { e.currentTarget.style.background = '#FEF2F2'; e.currentTarget.style.transform = 'scale(1)'; }}
                                >
                                    <i className="ti ti-trash" />
                                </button>
                            </div>
                        </div>
                    );
                })}
                </div>
            </div>
        </SuperAdminLayout>
    );
}
