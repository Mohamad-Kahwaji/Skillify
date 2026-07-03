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

function CatBadge({ name }) {
    if (!name) return <span style={{ color: '#94A3B8', fontSize: 12 }}>—</span>;
    return (
        <span style={{ display: 'inline-flex', alignItems: 'center', gap: 5, fontSize: 11, fontWeight: 700, padding: '4px 10px', borderRadius: 20, background: `${P}18`, color: P, border: `1px solid ${P}33` }}>
            <i className="ti ti-tag" style={{ fontSize: 10 }} />{name}
        </span>
    );
}

function BtBadge({ name, idx }) {
    if (!name) return null;
    const col = BT_COLORS[idx % BT_COLORS.length];
    return (
        <span style={{ display: 'inline-flex', alignItems: 'center', gap: 4, fontSize: 10, fontWeight: 700, padding: '3px 8px', borderRadius: 20, background: col + '18', color: col, border: `1px solid ${col}33` }}>
            <i className="ti ti-briefcase" style={{ fontSize: 9 }} />{name}
        </span>
    );
}

export default function Subcategories({ subcategories, categories, businessTypes }) {
    const [showCreate, setShowCreate] = useState(false);
    const [editId, setEditId]         = useState(null);
    const [search, setSearch]         = useState('');
    const [filterBt, setFilterBt]     = useState('');
    const [createBt, setCreateBt]     = useState('');
    const [editBt,   setEditBt]       = useState('');

    const createForm = useForm({ name: '', category_id: '' });
    const editForm   = useForm({ name: '', category_id: '' });

    const allCats = categories ?? [];
    const allBts  = businessTypes ?? [];
    const all     = subcategories ?? [];

    const btMap = {};
    allBts.forEach((t, i) => { btMap[t.id] = { name: t.name, idx: i }; });

    const filteredCatsForCreate = createBt
        ? allCats.filter(c => String(c.active_typebusiness_id) === String(createBt))
        : allCats;
    const filteredCatsForEdit = editBt
        ? allCats.filter(c => String(c.active_typebusiness_id) === String(editBt))
        : allCats;

    const filtered = all
        .filter(s => !filterBt || String(s.category?.active_typebusiness_id) === String(filterBt))
        .filter(s => `${s.name} ${s.category?.name ?? ''}`.toLowerCase().includes(search.toLowerCase()));

    const startEdit = (s) => {
        const catBt = s.category?.active_typebusiness_id ?? '';
        setEditBt(String(catBt));
        setEditId(s.id);
        editForm.setData({ name: s.name ?? '', category_id: s.category_id ?? '' });
    };

    const submitCreate = (e) => {
        e.preventDefault();
        createForm.post('/super-admin/subcategories', {
            onSuccess: () => { createForm.reset(); setShowCreate(false); setCreateBt(''); },
        });
    };

    const submitEdit = (e) => {
        e.preventDefault();
        editForm.put(`/super-admin/subcategories/${editId}`, {
            onSuccess: () => { setEditId(null); setEditBt(''); },
        });
    };

    const destroy = (id) => {
        if (!confirm('حذف هذه الفئة الفرعية؟')) return;
        router.delete(`/super-admin/subcategories/${id}`, { preserveScroll: true });
    };

    const COLS = ['50px', '1fr', '1fr', '1fr', '110px'];

    return (
        <SuperAdminLayout title="الفئات الفرعية">
            <Head title="الفئات الفرعية — Skillify" />

            {/* Header */}
            <div style={{ display: 'flex', alignItems: 'center', justifyContent: 'space-between', flexWrap: 'wrap', gap: 14 }}>
                <div>
                    <h1 style={{ fontSize: 22, fontWeight: 800, color: '#1E1B4B', margin: 0, display: 'flex', alignItems: 'center', gap: 10 }}>
                        <span style={{ width: 36, height: 36, borderRadius: 10, background: `linear-gradient(135deg,${P},${P2})`, display: 'inline-flex', alignItems: 'center', justifyContent: 'center', boxShadow: `0 4px 12px ${P}44` }}>
                            <i className="ti ti-tags" style={{ color: '#fff', fontSize: 17 }} />
                        </span>
                        الفئات الفرعية
                    </h1>
                    <p style={{ fontSize: 12, color: '#94A3B8', marginTop: 5, marginRight: 46 }}>{all.length} فئة فرعية مسجّلة</p>
                </div>
                <div style={{ display: 'flex', gap: 10, alignItems: 'center', flexWrap: 'wrap' }}>
                    <select dir="rtl" value={filterBt} onChange={e => setFilterBt(e.target.value)}
                        style={{ ...INPUT, width: 160, fontSize: 12, color: filterBt ? '#0F172A' : '#94A3B8' }}>
                        <option value="">كل أنواع النشاط</option>
                        {allBts.map(t => <option key={t.id} value={t.id}>{t.name}</option>)}
                    </select>
                    <div style={{ position: 'relative' }}>
                        <i className="ti ti-search" style={{ position: 'absolute', right: 11, top: '50%', transform: 'translateY(-50%)', color: '#94A3B8', fontSize: 13, pointerEvents: 'none' }} />
                        <input value={search} onChange={e => setSearch(e.target.value)} placeholder="بحث..."
                            style={{ ...INPUT, width: 180, paddingRight: 34 }} />
                    </div>
                    <button onClick={() => { setShowCreate(v => !v); setEditId(null); }} style={{
                        display: 'inline-flex', alignItems: 'center', gap: 7, padding: '10px 20px',
                        background: `linear-gradient(135deg,${P},${P2})`, color: '#fff', border: 'none', borderRadius: 11,
                        fontSize: 13, fontWeight: 700, cursor: 'pointer', fontFamily: "'Cairo','Inter',sans-serif",
                        boxShadow: `0 4px 14px ${P}44`,
                    }}>
                        <i className="ti ti-plus" style={{ fontSize: 14 }} /> إضافة
                    </button>
                </div>
            </div>

            {/* Create form */}
            {showCreate && (
                <div style={{ background: '#fff', border: `1px solid ${P}22`, borderRadius: 16, padding: '20px 24px', boxShadow: `0 4px 20px ${P}12` }}>
                    <div style={{ fontSize: 14, fontWeight: 700, color: '#1E1B4B', marginBottom: 16, display: 'flex', alignItems: 'center', gap: 8 }}>
                        <i className="ti ti-plus" style={{ color: P }} /> فئة فرعية جديدة
                    </div>
                    <form onSubmit={submitCreate}>
                        <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3" style={{ display: 'grid', gap: 14, marginBottom: 16 }}>
                            <div>
                                <label style={{ fontSize: 11, fontWeight: 700, color: '#475569', display: 'block', marginBottom: 6 }}>الاسم *</label>
                                <input dir="rtl" style={INPUT} placeholder="مثال: جراحة عامة" value={createForm.data.name} onChange={e => createForm.setData('name', e.target.value)} required />
                                {createForm.errors.name && <p style={{ fontSize: 11, color: '#DC2626', marginTop: 4 }}>{createForm.errors.name}</p>}
                            </div>
                            <div>
                                <label style={{ fontSize: 11, fontWeight: 700, color: '#475569', display: 'block', marginBottom: 6 }}>نوع النشاط</label>
                                <select dir="rtl" style={INPUT} value={createBt} onChange={e => { setCreateBt(e.target.value); createForm.setData('category_id', ''); }}>
                                    <option value="">— اختر نوع النشاط أولاً —</option>
                                    {allBts.map(t => <option key={t.id} value={t.id}>{t.name}</option>)}
                                </select>
                            </div>
                            <div>
                                <label style={{ fontSize: 11, fontWeight: 700, color: '#475569', display: 'block', marginBottom: 6 }}>الفئة *</label>
                                <select dir="rtl" style={{ ...INPUT, opacity: !createBt ? 0.6 : 1 }} value={createForm.data.category_id} onChange={e => createForm.setData('category_id', e.target.value)} required>
                                    <option value="">{createBt ? '— اختر الفئة —' : '— اختر نوع النشاط أولاً —'}</option>
                                    {filteredCatsForCreate.map(c => <option key={c.id} value={c.id}>{c.name}</option>)}
                                </select>
                                {createForm.errors.category_id && <p style={{ fontSize: 11, color: '#DC2626', marginTop: 4 }}>{createForm.errors.category_id}</p>}
                            </div>
                        </div>
                        <div style={{ display: 'flex', gap: 8 }}>
                            <button type="button" onClick={() => { setShowCreate(false); setCreateBt(''); }} style={{ padding: '8px 18px', borderRadius: 9, border: '1px solid rgba(0,0,0,0.12)', background: 'none', fontSize: 13, cursor: 'pointer', fontFamily: "'Cairo','Inter',sans-serif", color: '#475569' }}>إلغاء</button>
                            <button type="submit" disabled={createForm.processing} style={{ padding: '8px 22px', borderRadius: 9, background: `linear-gradient(135deg,${P},${P2})`, color: '#fff', border: 'none', fontSize: 13, fontWeight: 700, cursor: 'pointer', fontFamily: "'Cairo','Inter',sans-serif", opacity: createForm.processing ? 0.7 : 1 }}>إضافة</button>
                        </div>
                    </form>
                </div>
            )}

            {/* Table */}
            <div style={{ background: '#fff', border: '0.5px solid rgba(0,0,0,0.07)', borderRadius: 18, overflow: 'hidden', boxShadow: '0 2px 16px rgba(0,0,0,0.05)' }}>
                <div style={{ overflowX: 'auto' }}>
                <div style={{ display: 'grid', gridTemplateColumns: COLS.join(' '), padding: '12px 20px', background: 'linear-gradient(135deg,#F8F7FF,#F3F0FF)', borderBottom: `1px solid ${P}18`, minWidth: 680 }}>
                    {['#', 'الفئة الفرعية', 'الفئة', 'نوع النشاط', 'إجراءات'].map((h, idx) => (
                        <div key={h} style={{ fontSize: 11, fontWeight: 800, color: '#6B21A8', textTransform: 'uppercase', letterSpacing: '0.06em', textAlign: idx === 4 ? 'center' : 'right' }}>{h}</div>
                    ))}
                </div>

                {!filtered.length ? (
                    <div style={{ padding: '56px', textAlign: 'center', color: '#94A3B8' }}>
                        <i className="ti ti-tags" style={{ fontSize: 40, display: 'block', marginBottom: 10, opacity: 0.12 }} />
                        <div style={{ fontSize: 13 }}>لا توجد فئات فرعية</div>
                    </div>
                ) : filtered.map((s, i) => {
                    const catBtId = s.category?.active_typebusiness_id;
                    const bt = btMap[catBtId];
                    return editId === s.id ? (
                        <div key={s.id} style={{ display: 'grid', gridTemplateColumns: COLS.join(' '), padding: '10px 20px', alignItems: 'center', gap: 8, background: '#F5F3FF', borderBottom: `1px solid ${P}18`, minWidth: 680 }}>
                            <span style={{ fontSize: 12, color: '#C4B5FD', fontWeight: 700 }}>{i + 1}</span>
                            <input dir="rtl" style={{ ...INPUT, fontSize: 12 }} value={editForm.data.name} onChange={e => editForm.setData('name', e.target.value)} />
                            <select dir="rtl" style={{ ...INPUT, fontSize: 12 }} value={editBt} onChange={e => { setEditBt(e.target.value); editForm.setData('category_id', ''); }}>
                                <option value="">— نوع النشاط —</option>
                                {allBts.map(t => <option key={t.id} value={t.id}>{t.name}</option>)}
                            </select>
                            <select dir="rtl" style={{ ...INPUT, fontSize: 12 }} value={editForm.data.category_id} onChange={e => editForm.setData('category_id', e.target.value)}>
                                <option value="">— الفئة —</option>
                                {filteredCatsForEdit.map(c => <option key={c.id} value={c.id}>{c.name}</option>)}
                            </select>
                            <div style={{ display: 'flex', gap: 6, justifyContent: 'center' }}>
                                <button onClick={submitEdit} style={{ padding: '6px 14px', borderRadius: 8, background: `linear-gradient(135deg,${P},${P2})`, color: '#fff', border: 'none', fontSize: 12, cursor: 'pointer', fontWeight: 700, fontFamily: "'Cairo','Inter',sans-serif" }}>حفظ</button>
                                <button onClick={() => { setEditId(null); setEditBt(''); }} style={{ padding: '6px 10px', borderRadius: 8, border: '1px solid rgba(0,0,0,0.12)', background: '#fff', fontSize: 12, cursor: 'pointer', fontFamily: "'Cairo','Inter',sans-serif" }}>إلغاء</button>
                            </div>
                        </div>
                    ) : (
                        <div key={s.id}
                            style={{ display: 'grid', gridTemplateColumns: COLS.join(' '), padding: '13px 20px', borderBottom: '1px solid rgba(0,0,0,0.04)', alignItems: 'center', gap: 8, minWidth: 680, transition: 'background 0.15s' }}
                            onMouseEnter={e => e.currentTarget.style.background = '#F9F7FF'}
                            onMouseLeave={e => e.currentTarget.style.background = 'transparent'}
                        >
                            <span style={{ fontSize: 12, color: '#C4B5FD', fontWeight: 700, background: '#F5F3FF', width: 28, height: 28, borderRadius: 8, display: 'inline-flex', alignItems: 'center', justifyContent: 'center' }}>{i + 1}</span>
                            <div style={{ display: 'flex', alignItems: 'center', gap: 10 }}>
                                <span style={{ width: 30, height: 30, borderRadius: 8, background: `${P}18`, display: 'inline-flex', alignItems: 'center', justifyContent: 'center', flexShrink: 0 }}>
                                    <i className="ti ti-tags" style={{ color: P, fontSize: 13 }} />
                                </span>
                                <span style={{ fontWeight: 700, color: '#0F172A', fontSize: 13.5 }}>{s.name}</span>
                            </div>
                            <div><CatBadge name={s.category?.name} /></div>
                            <div>{bt ? <BtBadge name={bt.name} idx={bt.idx} /> : <span style={{ color: '#94A3B8', fontSize: 12 }}>—</span>}</div>
                            <div style={{ display: 'flex', gap: 6, justifyContent: 'center' }}>
                                <button onClick={() => { startEdit(s); setShowCreate(false); }} title="تعديل" style={{
                                    width: 32, height: 32, borderRadius: 8, border: `1px solid ${P}33`, background: '#F5F3FF', color: P,
                                    fontSize: 14, cursor: 'pointer', display: 'inline-flex', alignItems: 'center', justifyContent: 'center', transition: 'all 0.13s',
                                }}
                                    onMouseEnter={e => e.currentTarget.style.background = '#EDE9FE'}
                                    onMouseLeave={e => e.currentTarget.style.background = '#F5F3FF'}
                                >
                                    <i className="ti ti-pencil" />
                                </button>
                                <button onClick={() => destroy(s.id)} title="حذف" style={{
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
