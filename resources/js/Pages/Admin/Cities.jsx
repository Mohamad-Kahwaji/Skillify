import { Head, router, useForm } from '@inertiajs/react';
import { useState } from 'react';
import AdminLayout, { C } from '../../Layouts/AdminLayout';
import { PageHeader, PrimaryBtn, EmptyState, INPUT_STYLE } from './Users';

const BLANK = { name: '', governorate: '', latitude: '', longitude: '' };

function GpsButton({ status, onDetect, color }) {
    const cfg = {
        idle:    { bg: `linear-gradient(135deg,${color},${color}CC)`, text: 'تحديد الموقع من GPS', icon: 'ti-current-location', shadow: `0 3px 10px ${color}55` },
        loading: { bg: 'linear-gradient(135deg,#94A3B8,#64748B)',     text: 'جاري التحديد...',          icon: 'ti-loader-2',          shadow: 'none' },
        ok:      { bg: 'linear-gradient(135deg,#059669,#047857)',     text: 'تم تحديد الموقع ✓',       icon: 'ti-check',             shadow: '0 3px 10px rgba(5,150,105,0.3)' },
        err:     { bg: 'linear-gradient(135deg,#DC2626,#B91C1C)',     text: 'تعذر التحديد — أعد المحاولة', icon: 'ti-alert-circle', shadow: 'none' },
    }[status];
    return (
        <button type="button" onClick={onDetect} disabled={status === 'loading'}
            style={{ display: 'inline-flex', alignItems: 'center', gap: 8, padding: '10px 18px', background: cfg.bg, color: '#fff', border: 'none', borderRadius: 10, fontSize: 12, fontWeight: 700, cursor: status === 'loading' ? 'default' : 'pointer', fontFamily: "'Cairo','Inter',sans-serif", boxShadow: cfg.shadow, transition: 'all 0.2s' }}>
            <i className={`ti ${cfg.icon}${status === 'loading' ? ' spin' : ''}`} style={{ fontSize: 14 }} />
            {cfg.text}
        </button>
    );
}

export default function Cities({ cities }) {
    const [showCreate, setShowCreate] = useState(false);
    const [editId, setEditId]         = useState(null);
    const [createGps, setCreateGps]   = useState('idle');
    const [editGps, setEditGps]       = useState('idle');

    const createForm = useForm({ ...BLANK });
    const editForm   = useForm({ ...BLANK });

    const detectGps = (form, setStatus) => {
        if (!navigator.geolocation) { setStatus('err'); return; }
        setStatus('loading');
        navigator.geolocation.getCurrentPosition(
            async (pos) => {
                const { latitude, longitude } = pos.coords;
                try {
                    const res  = await fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${latitude}&lon=${longitude}&accept-language=ar`);
                    const data = await res.json();
                    const city = data.address?.city || data.address?.town || data.address?.village || data.address?.municipality || '';
                    const gov  = data.address?.state || data.address?.province || '';
                    form.setData({ name: city, governorate: gov, latitude: String(latitude), longitude: String(longitude) });
                } catch {
                    form.setData({ ...form.data, latitude: String(latitude), longitude: String(longitude) });
                }
                setStatus('ok');
            },
            () => setStatus('err'),
            { enableHighAccuracy: true, timeout: 10000 }
        );
    };

    const startEdit = (c) => {
        setEditId(c.id);
        setEditGps(c.latitude != null ? 'ok' : 'idle');
        editForm.setData({ name: c.name ?? '', governorate: c.governorate ?? '', latitude: c.latitude ?? '', longitude: c.longitude ?? '' });
    };

    const submitCreate = (e) => {
        e.preventDefault();
        createForm.post('/admin/cities', { onSuccess: () => { createForm.reset(); setShowCreate(false); setCreateGps('idle'); } });
    };

    const submitEdit = (e) => {
        e.preventDefault();
        editForm.put(`/admin/cities/${editId}`, { onSuccess: () => { setEditId(null); setEditGps('idle'); } });
    };

    const destroy = (id) => {
        if (!confirm('حذف هذه المدينة نهائياً؟')) return;
        router.delete(`/admin/cities/${id}`, { preserveScroll: true });
    };

    const all = cities ?? [];

    return (
        <AdminLayout title="المدن">
            <Head title="المدن — Skillify" />
            <style>{`
                .spin { animation: spin 1s linear infinite; }
                @keyframes spin { to { transform: rotate(360deg); } }
                .city-row-a:hover { background: ${C.pageBg} !important; }
                .city-row-a:hover .cname { color: ${C.teal} !important; }
            `}</style>

            <PageHeader title="المدن" sub={`${all.length} مدينة مسجّلة`}>
                <PrimaryBtn icon="ti-plus" onClick={() => setShowCreate(v => !v)} color={C.teal}>إضافة مدينة</PrimaryBtn>
            </PageHeader>

            {/* ── Create Form ── */}
            {showCreate && (
                <div style={{ background: C.cardBg, border: C.cardBorder, borderRadius: 18, padding: '24px 28px', boxShadow: C.cardShadow }}>
                    <div style={{ display: 'flex', alignItems: 'center', gap: 10, marginBottom: 20 }}>
                        <span style={{ width: 30, height: 30, borderRadius: 8, background: '#F0FDFA', display: 'inline-flex', alignItems: 'center', justifyContent: 'center' }}>
                            <i className="ti ti-building-community" style={{ color: C.teal, fontSize: 15 }} />
                        </span>
                        <span style={{ fontSize: 15, fontWeight: 700, color: C.textDark }}>إضافة مدينة جديدة</span>
                    </div>
                    <form onSubmit={submitCreate}>
                        <div className="grid-cols-1 sm:grid-cols-2" style={{ display: 'grid', gap: 16, marginBottom: 20 }}>
                            <div>
                                <label style={{ fontSize: 11, fontWeight: 700, color: C.textMuted, display: 'block', marginBottom: 6 }}>اسم المدينة *</label>
                                <input dir="rtl" style={INPUT_STYLE} placeholder="مثال: دمشق" value={createForm.data.name} onChange={e => createForm.setData('name', e.target.value)} required />
                                {createForm.errors.name && <p style={{ fontSize: 11, color: C.dangerText, marginTop: 4 }}>{createForm.errors.name}</p>}
                            </div>
                            <div>
                                <label style={{ fontSize: 11, fontWeight: 700, color: C.textMuted, display: 'block', marginBottom: 6 }}>المحافظة *</label>
                                <input dir="rtl" style={INPUT_STYLE} placeholder="مثال: ريف دمشق" value={createForm.data.governorate} onChange={e => createForm.setData('governorate', e.target.value)} required />
                                {createForm.errors.governorate && <p style={{ fontSize: 11, color: C.dangerText, marginTop: 4 }}>{createForm.errors.governorate}</p>}
                            </div>
                        </div>

                        <div style={{ background: '#F0FAFA', border: `1px dashed ${C.teal}44`, borderRadius: 12, padding: '14px 18px', marginBottom: 20, display: 'flex', alignItems: 'center', justifyContent: 'space-between', flexWrap: 'wrap', gap: 12 }}>
                            <div>
                                <div style={{ fontSize: 13, fontWeight: 600, color: C.textDark, marginBottom: 2 }}>الموقع الجغرافي</div>
                                <div style={{ fontSize: 11, color: C.textMuted }}>اضغط لتحديد الإحداثيات تلقائياً من موقعك الحالي</div>
                            </div>
                            <GpsButton status={createGps} onDetect={() => detectGps(createForm, setCreateGps)} color={C.teal} />
                        </div>

                        <div style={{ display: 'flex', gap: 10, justifyContent: 'flex-end' }}>
                            <button type="button" onClick={() => { setShowCreate(false); setCreateGps('idle'); }}
                                style={{ padding: '9px 20px', borderRadius: 10, border: C.cardBorder, background: '#fff', fontSize: 13, cursor: 'pointer', fontFamily: "'Cairo','Inter',sans-serif", color: C.textMuted, fontWeight: 600 }}>
                                إلغاء
                            </button>
                            <button type="submit" disabled={createForm.processing}
                                style={{ padding: '9px 24px', borderRadius: 10, background: C.teal, color: '#fff', border: 'none', fontSize: 13, fontWeight: 700, cursor: 'pointer', fontFamily: "'Cairo','Inter',sans-serif", opacity: createForm.processing ? 0.7 : 1 }}>
                                <i className="ti ti-plus" style={{ marginLeft: 6 }} /> إضافة
                            </button>
                        </div>
                    </form>
                </div>
            )}

            {/* ── Table ── */}
            <div style={{ background: C.cardBg, border: C.cardBorder, borderRadius: 18, overflow: 'hidden', boxShadow: C.cardShadow }}>
                <div style={{ overflowX: 'auto' }}>
                {/* Header */}
                <div style={{ padding: '13px 20px', background: C.pageBg, borderBottom: C.cardBorder, display: 'grid', gridTemplateColumns: '50px 1fr 1fr 120px', gap: 8 }}>
                    {['#', 'المدينة', 'المحافظة', 'إجراءات'].map((h, idx) => (
                        <div key={h} style={{ fontSize: 11, fontWeight: 700, color: C.textMuted, textTransform: 'uppercase', letterSpacing: '0.05em', textAlign: idx === 3 ? 'center' : 'right' }}>{h}</div>
                    ))}
                </div>

                {/* Rows */}
                {!all.length ? (
                    <div style={{ padding: 60, textAlign: 'center' }}>
                        <EmptyState icon="ti-map-2" text="لا توجد مدن مضافة بعد" />
                    </div>
                ) : all.map((c, i) => (
                    editId === c.id ? (
                        <div key={c.id} style={{ padding: '14px 20px', background: '#F0FDFA', borderBottom: C.cardBorder, display: 'grid', gridTemplateColumns: '50px 1fr 1fr 120px', alignItems: 'center', gap: 8 }}>
                            <span style={{ fontSize: 12, color: C.textFaint, fontWeight: 600 }}>{i + 1}</span>
                            <input style={{ ...INPUT_STYLE, fontSize: 13 }} dir="rtl" value={editForm.data.name} onChange={e => editForm.setData('name', e.target.value)} placeholder="اسم المدينة" />
                            <input style={{ ...INPUT_STYLE, fontSize: 13 }} dir="rtl" value={editForm.data.governorate} onChange={e => editForm.setData('governorate', e.target.value)} placeholder="المحافظة" />
                            <div style={{ display: 'flex', flexDirection: 'column', gap: 6 }}>
                                <GpsButton status={editGps} onDetect={() => detectGps(editForm, setEditGps)} color={C.teal} />
                                <div style={{ display: 'flex', gap: 6 }}>
                                    <button onClick={submitEdit} style={{ flex: 1, padding: '6px 0', borderRadius: 8, background: C.teal, color: '#fff', border: 'none', fontSize: 12, cursor: 'pointer', fontWeight: 700, fontFamily: "'Cairo','Inter',sans-serif" }}>حفظ</button>
                                    <button onClick={() => { setEditId(null); setEditGps('idle'); }} style={{ flex: 1, padding: '6px 0', borderRadius: 8, border: C.cardBorder, background: '#fff', fontSize: 12, cursor: 'pointer', fontFamily: "'Cairo','Inter',sans-serif", color: C.textMuted }}>إلغاء</button>
                                </div>
                            </div>
                        </div>
                    ) : (
                        <div key={c.id} className="city-row-a" style={{ padding: '14px 20px', borderBottom: `1px solid rgba(15,23,42,0.04)`, display: 'grid', gridTemplateColumns: '50px 1fr 1fr 120px', alignItems: 'center', gap: 8, transition: 'background 0.15s' }}>
                            <span style={{ fontSize: 12, color: C.textFaint, fontWeight: 700, background: C.pageBg, width: 28, height: 28, borderRadius: 8, display: 'inline-flex', alignItems: 'center', justifyContent: 'center' }}>{i + 1}</span>
                            <div style={{ display: 'flex', alignItems: 'center', gap: 10 }}>
                                <span style={{ width: 32, height: 32, borderRadius: 9, background: '#E6F9F6', display: 'inline-flex', alignItems: 'center', justifyContent: 'center', flexShrink: 0 }}>
                                    <i className="ti ti-building" style={{ color: C.teal, fontSize: 14 }} />
                                </span>
                                <span className="cname" style={{ fontWeight: 700, color: C.textDark, fontSize: 14, transition: 'color 0.15s' }}>{c.name}</span>
                            </div>
                            <div>
                                {c.governorate ? (
                                    <span style={{ display: 'inline-flex', alignItems: 'center', gap: 5, padding: '4px 10px', background: '#F3F4F6', borderRadius: 20, fontSize: 12, color: '#4B5563', fontWeight: 600 }}>
                                        <i className="ti ti-map" style={{ fontSize: 11, color: '#9CA3AF' }} />
                                        {c.governorate}
                                    </span>
                                ) : <span style={{ color: '#CBD5E1', fontSize: 12 }}>—</span>}
                            </div>
                            <div style={{ display: 'flex', gap: 6, justifyContent: 'center' }}>
                                <button onClick={() => startEdit(c)} title="تعديل"
                                    style={{ width: 34, height: 34, borderRadius: 9, border: C.cardBorder, background: '#fff', color: C.textMed, fontSize: 14, cursor: 'pointer', display: 'inline-flex', alignItems: 'center', justifyContent: 'center', transition: 'all 0.15s' }}
                                    onMouseEnter={e => { e.currentTarget.style.borderColor = C.teal; e.currentTarget.style.color = C.teal; }}
                                    onMouseLeave={e => { e.currentTarget.style.borderColor = ''; e.currentTarget.style.color = C.textMed; }}>
                                    <i className="ti ti-pencil" />
                                </button>
                                <button onClick={() => destroy(c.id)} title="حذف"
                                    style={{ width: 34, height: 34, borderRadius: 9, border: `1px solid ${C.dangerBorder}`, background: C.dangerBg, color: C.dangerText, fontSize: 14, cursor: 'pointer', display: 'inline-flex', alignItems: 'center', justifyContent: 'center', transition: 'all 0.15s' }}
                                    onMouseEnter={e => e.currentTarget.style.background = '#FEE2E2'}
                                    onMouseLeave={e => e.currentTarget.style.background = C.dangerBg}>
                                    <i className="ti ti-trash" />
                                </button>
                            </div>
                        </div>
                    )
                ))}
                </div>
            </div>
        </AdminLayout>
    );
}
