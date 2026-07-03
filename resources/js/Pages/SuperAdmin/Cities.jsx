import { Head, router, useForm } from '@inertiajs/react';
import { useState } from 'react';
import SuperAdminLayout from '../../Layouts/SuperAdminLayout';

const INPUT = { width: '100%', padding: '9px 13px', border: '1px solid rgba(124,58,237,0.2)', borderRadius: 9, fontSize: 13, outline: 'none', background: '#fff', fontFamily: "'Cairo','Inter',sans-serif", boxSizing: 'border-box', color: '#1E1B4B' };

function GpsButton({ status, onDetect }) {
    const cfg = {
        idle:    { bg: 'linear-gradient(135deg,#7C3AED,#6D28D9)', text: 'تحديد الموقع من GPS', icon: 'ti-current-location', shadow: '0 3px 10px rgba(124,58,237,0.35)' },
        loading: { bg: 'linear-gradient(135deg,#9D71F5,#7C3AED)',  text: 'جاري التحديد...',        icon: 'ti-loader-2',          shadow: 'none' },
        ok:      { bg: 'linear-gradient(135deg,#059669,#047857)',  text: 'تم تحديد الموقع ✓',    icon: 'ti-check',             shadow: '0 3px 10px rgba(5,150,105,0.3)' },
        err:     { bg: 'linear-gradient(135deg,#DC2626,#B91C1C)',  text: 'تعذر التحديد — أعد المحاولة', icon: 'ti-alert-circle', shadow: 'none' },
    }[status];
    return (
        <button type="button" onClick={onDetect} disabled={status === 'loading'}
            style={{ display: 'inline-flex', alignItems: 'center', gap: 8, padding: '10px 20px', background: cfg.bg, color: '#fff', border: 'none', borderRadius: 10, fontSize: 13, fontWeight: 700, cursor: status === 'loading' ? 'default' : 'pointer', fontFamily: "'Cairo','Inter',sans-serif", boxShadow: cfg.shadow, transition: 'all 0.2s' }}>
            <i className={`ti ${cfg.icon}${status === 'loading' ? ' spin' : ''}`} style={{ fontSize: 15 }} />
            {cfg.text}
        </button>
    );
}

export default function Cities({ cities }) {
    const [showCreate, setShowCreate] = useState(false);
    const [editId, setEditId]         = useState(null);
    const [createGps, setCreateGps]   = useState('idle');
    const [editGps, setEditGps]       = useState('idle');

    const createForm = useForm({ name: '', governorate: '', latitude: '', longitude: '' });
    const editForm   = useForm({ name: '', governorate: '', latitude: '', longitude: '' });

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
        createForm.post('/super-admin/cities', { onSuccess: () => { createForm.reset(); setShowCreate(false); setCreateGps('idle'); } });
    };

    const submitEdit = (e) => {
        e.preventDefault();
        editForm.put(`/super-admin/cities/${editId}`, { onSuccess: () => { setEditId(null); setEditGps('idle'); } });
    };

    const destroy = (id) => {
        if (!confirm('حذف هذه المدينة؟')) return;
        router.delete(`/super-admin/cities/${id}`, { preserveScroll: true });
    };

    const all = cities ?? [];

    return (
        <SuperAdminLayout title="المدن">
            <Head title="المدن — Skillify" />
            <style>{`
                .spin { animation: spin 1s linear infinite; }
                @keyframes spin { to { transform: rotate(360deg); } }
                .city-row:hover { background: #F9F7FF !important; }
                .city-row:hover .city-name { color: #7C3AED !important; }
            `}</style>

            {/* ── Header ── */}
            <div style={{ display: 'flex', alignItems: 'center', justifyContent: 'space-between', flexWrap: 'wrap', gap: 12 }}>
                <div>
                    <h1 style={{ fontSize: 22, fontWeight: 800, color: '#1E1B4B', margin: 0, display: 'flex', alignItems: 'center', gap: 10 }}>
                        <span style={{ width: 36, height: 36, borderRadius: 10, background: 'linear-gradient(135deg,#7C3AED,#6D28D9)', display: 'inline-flex', alignItems: 'center', justifyContent: 'center', boxShadow: '0 4px 12px rgba(124,58,237,0.3)' }}>
                            <i className="ti ti-map-pin" style={{ color: '#fff', fontSize: 17 }} />
                        </span>
                        المدن
                    </h1>
                    <p style={{ fontSize: 12, color: '#94A3B8', marginTop: 5, marginRight: 46 }}>{all.length} مدينة مسجّلة</p>
                </div>
                <button onClick={() => setShowCreate(v => !v)}
                    style={{ display: 'inline-flex', alignItems: 'center', gap: 8, padding: '11px 22px', background: 'linear-gradient(135deg,#7C3AED,#6D28D9)', color: '#fff', border: 'none', borderRadius: 12, fontSize: 13, fontWeight: 700, cursor: 'pointer', fontFamily: "'Cairo','Inter',sans-serif", boxShadow: '0 4px 16px rgba(124,58,237,0.35)', transition: 'transform 0.15s' }}
                    onMouseEnter={e => e.currentTarget.style.transform = 'translateY(-1px)'}
                    onMouseLeave={e => e.currentTarget.style.transform = 'translateY(0)'}>
                    <i className="ti ti-plus" style={{ fontSize: 15 }} /> إضافة مدينة
                </button>
            </div>

            {/* ── Create Form ── */}
            {showCreate && (
                <div style={{ background: '#fff', border: '1px solid rgba(124,58,237,0.15)', borderRadius: 18, padding: '24px 28px', boxShadow: '0 4px 24px rgba(124,58,237,0.1)' }}>
                    <div style={{ display: 'flex', alignItems: 'center', gap: 10, marginBottom: 20 }}>
                        <span style={{ width: 30, height: 30, borderRadius: 8, background: '#F5F3FF', display: 'inline-flex', alignItems: 'center', justifyContent: 'center' }}>
                            <i className="ti ti-building-community" style={{ color: '#7C3AED', fontSize: 15 }} />
                        </span>
                        <span style={{ fontSize: 15, fontWeight: 700, color: '#1E1B4B' }}>إضافة مدينة جديدة</span>
                    </div>
                    <form onSubmit={submitCreate}>
                        <div className="grid grid-cols-1 sm:grid-cols-2" style={{ display: 'grid', gap: 16, marginBottom: 20 }}>
                            <div>
                                <label style={{ fontSize: 11, fontWeight: 700, color: '#6B7280', display: 'block', marginBottom: 6, textTransform: 'uppercase', letterSpacing: '0.05em' }}>اسم المدينة *</label>
                                <input style={INPUT} dir="rtl" placeholder="مثال: دمشق" value={createForm.data.name} onChange={e => createForm.setData('name', e.target.value)} required />
                                {createForm.errors.name && <p style={{ fontSize: 11, color: '#DC2626', marginTop: 4 }}>{createForm.errors.name}</p>}
                            </div>
                            <div>
                                <label style={{ fontSize: 11, fontWeight: 700, color: '#6B7280', display: 'block', marginBottom: 6, textTransform: 'uppercase', letterSpacing: '0.05em' }}>المحافظة *</label>
                                <input style={INPUT} dir="rtl" placeholder="مثال: ريف دمشق" value={createForm.data.governorate} onChange={e => createForm.setData('governorate', e.target.value)} required />
                                {createForm.errors.governorate && <p style={{ fontSize: 11, color: '#DC2626', marginTop: 4 }}>{createForm.errors.governorate}</p>}
                            </div>
                        </div>

                        <div style={{ background: '#F9F7FF', border: '1px dashed rgba(124,58,237,0.25)', borderRadius: 12, padding: '16px 20px', marginBottom: 20, display: 'flex', alignItems: 'center', justifyContent: 'space-between', flexWrap: 'wrap', gap: 12 }}>
                            <div>
                                <div style={{ fontSize: 13, fontWeight: 600, color: '#4B5563', marginBottom: 2 }}>الموقع الجغرافي</div>
                                <div style={{ fontSize: 11, color: '#94A3B8' }}>اضغط لتحديد الإحداثيات تلقائياً من موقعك الحالي</div>
                            </div>
                            <GpsButton status={createGps} onDetect={() => detectGps(createForm, setCreateGps)} />
                        </div>

                        <div style={{ display: 'flex', gap: 10, justifyContent: 'flex-end' }}>
                            <button type="button" onClick={() => { setShowCreate(false); setCreateGps('idle'); }}
                                style={{ padding: '9px 20px', borderRadius: 10, border: '1px solid rgba(0,0,0,0.12)', background: '#fff', fontSize: 13, cursor: 'pointer', fontFamily: "'Cairo','Inter',sans-serif", color: '#4B5563', fontWeight: 600 }}>
                                إلغاء
                            </button>
                            <button type="submit" disabled={createForm.processing}
                                style={{ padding: '9px 24px', borderRadius: 10, background: 'linear-gradient(135deg,#7C3AED,#6D28D9)', color: '#fff', border: 'none', fontSize: 13, fontWeight: 700, cursor: 'pointer', fontFamily: "'Cairo','Inter',sans-serif", boxShadow: '0 3px 10px rgba(124,58,237,0.3)', opacity: createForm.processing ? 0.7 : 1 }}>
                                <i className="ti ti-plus" style={{ marginLeft: 6 }} /> إضافة
                            </button>
                        </div>
                    </form>
                </div>
            )}

            {/* ── Table ── */}
            <div style={{ background: '#fff', border: '1px solid rgba(0,0,0,0.06)', borderRadius: 18, overflow: 'hidden', boxShadow: '0 2px 16px rgba(0,0,0,0.05)' }}>
                <div style={{ overflowX: 'auto' }}>
                {/* Table Header */}
                <div style={{ padding: '14px 20px', background: 'linear-gradient(135deg,#F8F7FF,#F3F0FF)', borderBottom: '1px solid rgba(124,58,237,0.1)', display: 'grid', gridTemplateColumns: '50px 1fr 1fr 120px', alignItems: 'center', gap: 8, minWidth: 640 }}>
                    {['#', 'المدينة', 'المحافظة', 'إجراءات'].map((h, idx) => (
                        <div key={h} style={{ fontSize: 11, fontWeight: 800, color: '#6B21A8', textTransform: 'uppercase', letterSpacing: '0.06em', textAlign: idx === 3 ? 'center' : 'right' }}>{h}</div>
                    ))}
                </div>

                {/* Rows */}
                {!all.length ? (
                    <div style={{ padding: '60px 20px', textAlign: 'center', color: '#94A3B8' }}>
                        <i className="ti ti-map-2" style={{ fontSize: 48, display: 'block', marginBottom: 12, opacity: 0.15 }} />
                        <div style={{ fontSize: 14, fontWeight: 600 }}>لا توجد مدن مضافة بعد</div>
                        <div style={{ fontSize: 12, marginTop: 4, opacity: 0.7 }}>اضغط على "إضافة مدينة" للبدء</div>
                    </div>
                ) : all.map((c, i) => (
                    editId === c.id ? (
                        /* ── Edit Row ── */
                        <div key={c.id} style={{ padding: '14px 20px', background: '#F9F7FF', borderBottom: '1px solid rgba(124,58,237,0.1)', display: 'grid', gridTemplateColumns: '50px 1fr 1fr 120px', alignItems: 'center', gap: 8, minWidth: 640 }}>
                            <span style={{ fontSize: 12, color: '#94A3B8', fontWeight: 600 }}>{i + 1}</span>
                            <input style={{ ...INPUT, fontSize: 13 }} dir="rtl" value={editForm.data.name} onChange={e => editForm.setData('name', e.target.value)} placeholder="اسم المدينة" />
                            <input style={{ ...INPUT, fontSize: 13 }} dir="rtl" value={editForm.data.governorate} onChange={e => editForm.setData('governorate', e.target.value)} placeholder="المحافظة" />
                            <div style={{ display: 'flex', flexDirection: 'column', gap: 6 }}>
                                <GpsButton status={editGps} onDetect={() => detectGps(editForm, setEditGps)} />
                                <div style={{ display: 'flex', gap: 6 }}>
                                    <button onClick={submitEdit} style={{ flex: 1, padding: '6px 0', borderRadius: 8, background: '#7C3AED', color: '#fff', border: 'none', fontSize: 12, cursor: 'pointer', fontWeight: 700, fontFamily: "'Cairo','Inter',sans-serif" }}>حفظ</button>
                                    <button onClick={() => { setEditId(null); setEditGps('idle'); }} style={{ flex: 1, padding: '6px 0', borderRadius: 8, border: '1px solid rgba(0,0,0,0.1)', background: '#fff', fontSize: 12, cursor: 'pointer', fontFamily: "'Cairo','Inter',sans-serif", color: '#4B5563' }}>إلغاء</button>
                                </div>
                            </div>
                        </div>
                    ) : (
                        /* ── View Row ── */
                        <div key={c.id} className="city-row" style={{ padding: '14px 20px', borderBottom: '1px solid rgba(0,0,0,0.04)', display: 'grid', gridTemplateColumns: '50px 1fr 1fr 120px', alignItems: 'center', gap: 8, minWidth: 640, transition: 'background 0.15s', cursor: 'default' }}>
                            <span style={{ fontSize: 12, color: '#C4B5FD', fontWeight: 700, background: '#F5F3FF', width: 28, height: 28, borderRadius: 8, display: 'inline-flex', alignItems: 'center', justifyContent: 'center' }}>{i + 1}</span>
                            <div style={{ display: 'flex', alignItems: 'center', gap: 10 }}>
                                <span style={{ width: 32, height: 32, borderRadius: 9, background: 'linear-gradient(135deg,#EDE9FE,#DDD6FE)', display: 'inline-flex', alignItems: 'center', justifyContent: 'center', flexShrink: 0 }}>
                                    <i className="ti ti-building" style={{ color: '#7C3AED', fontSize: 14 }} />
                                </span>
                                <span className="city-name" style={{ fontWeight: 700, color: '#1E1B4B', fontSize: 14, transition: 'color 0.15s' }}>{c.name}</span>
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
                                    style={{ width: 34, height: 34, borderRadius: 9, border: '1px solid rgba(124,58,237,0.2)', background: '#F5F3FF', color: '#7C3AED', fontSize: 14, cursor: 'pointer', display: 'inline-flex', alignItems: 'center', justifyContent: 'center', transition: 'all 0.15s' }}
                                    onMouseEnter={e => { e.currentTarget.style.background = '#EDE9FE'; e.currentTarget.style.borderColor = '#7C3AED'; }}
                                    onMouseLeave={e => { e.currentTarget.style.background = '#F5F3FF'; e.currentTarget.style.borderColor = 'rgba(124,58,237,0.2)'; }}>
                                    <i className="ti ti-pencil" />
                                </button>
                                <button onClick={() => destroy(c.id)} title="حذف"
                                    style={{ width: 34, height: 34, borderRadius: 9, border: '1px solid #FECACA', background: '#FEF2F2', color: '#DC2626', fontSize: 14, cursor: 'pointer', display: 'inline-flex', alignItems: 'center', justifyContent: 'center', transition: 'all 0.15s' }}
                                    onMouseEnter={e => { e.currentTarget.style.background = '#FEE2E2'; e.currentTarget.style.borderColor = '#DC2626'; }}
                                    onMouseLeave={e => { e.currentTarget.style.background = '#FEF2F2'; e.currentTarget.style.borderColor = '#FECACA'; }}>
                                    <i className="ti ti-trash" />
                                </button>
                            </div>
                        </div>
                    )
                ))}
                </div>
            </div>
        </SuperAdminLayout>
    );
}
