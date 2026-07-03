import { Head, router, useForm } from '@inertiajs/react';
import { useState } from 'react';
import AdminLayout, { C } from '../../Layouts/AdminLayout';
import { PageHeader, PrimaryBtn, INPUT_STYLE } from './Users';

export default function Ads({ advertisements, adminId }) {
    const [showForm, setShowForm] = useState(false);
    const { data, setData, post, processing, reset } = useForm({
        admin_id: adminId ?? '', title: '', description: '',
        company_name: '', start_date: '', end_date: '', status: 'active',
    });

    const submit = (e) => {
        e.preventDefault();
        post('/admin/ads', { onSuccess: () => { reset(); setShowForm(false); } });
    };

    const destroy = (id) => {
        if (!confirm('حذف هذا الإعلان؟')) return;
        router.delete(`/admin/ads/${id}`, { preserveScroll: true });
    };

    return (
        <AdminLayout title="الإعلانات">
            <Head title="الإعلانات — Skillify" />

            <PageHeader title="الإعلانات" sub={`${(advertisements ?? []).length} إعلان`}>
                <PrimaryBtn icon="ti-plus" onClick={() => setShowForm(v => !v)} color={C.teal}>إعلان جديد</PrimaryBtn>
            </PageHeader>

            {showForm && (
                <FormCard title="إنشاء إعلان جديد">
                    <form onSubmit={submit}>
                        <div className="grid-cols-1 sm:grid-cols-2" style={{ display: 'grid', gap: 14 }}>
                            <Field label="العنوان *"><input style={INPUT_STYLE} value={data.title} onChange={e => setData('title', e.target.value)} required /></Field>
                            <Field label="اسم الشركة"><input style={INPUT_STYLE} value={data.company_name} onChange={e => setData('company_name', e.target.value)} /></Field>
                            <Field label="الوصف" full><textarea style={{ ...INPUT_STYLE, resize: 'vertical' }} rows={3} value={data.description} onChange={e => setData('description', e.target.value)} /></Field>
                            <Field label="تاريخ البداية"><input type="date" style={INPUT_STYLE} value={data.start_date} onChange={e => setData('start_date', e.target.value)} /></Field>
                            <Field label="تاريخ الانتهاء"><input type="date" style={INPUT_STYLE} value={data.end_date} onChange={e => setData('end_date', e.target.value)} /></Field>
                            <Field label="الحالة">
                                <select style={INPUT_STYLE} value={data.status} onChange={e => setData('status', e.target.value)}>
                                    <option value="active">نشط</option>
                                    <option value="inactive">غير نشط</option>
                                    <option value="approved">مقبول</option>
                                </select>
                            </Field>
                        </div>
                        <FormActions onCancel={() => setShowForm(false)} processing={processing} />
                    </form>
                </FormCard>
            )}

            {!(advertisements ?? []).length ? (
                <div style={{ background: C.cardBg, borderRadius: 16, boxShadow: C.cardShadow, border: C.cardBorder, padding: '56px', textAlign: 'center', color: C.textFaint }}>
                    <i className="ti ti-speakerphone" style={{ fontSize: 40, display: 'block', marginBottom: 10, opacity: 0.25 }} />
                    <div style={{ fontSize: 13 }}>لا توجد إعلانات بعد</div>
                </div>
            ) : (
                <div style={{ display: 'grid', gridTemplateColumns: 'repeat(auto-fill,minmax(290px,1fr))', gap: 16 }}>
                    {(advertisements ?? []).map(ad => {
                        const isActive = ad.status === 'active' || ad.status === 'approved';
                        return (
                            <div key={ad.id} style={{ background: C.cardBg, borderRadius: 16, boxShadow: C.cardShadow, border: C.cardBorder, overflow: 'hidden', display: 'flex', flexDirection: 'column' }}>
                                <div style={{ padding: '16px 18px', flex: 1 }}>
                                    <div style={{ display: 'flex', alignItems: 'flex-start', justifyContent: 'space-between', gap: 8, marginBottom: 10 }}>
                                        <div style={{ fontSize: 14, fontWeight: 700, color: C.textDark }}>{ad.title}</div>
                                        <span style={{ fontSize: 10, fontWeight: 700, padding: '3px 9px', borderRadius: 20, background: isActive ? C.successBg : '#F3F4F6', color: isActive ? C.successText : C.textMuted, border: `1px solid ${isActive ? C.successBorder : '#E5E7EB'}`, flexShrink: 0 }}>
                                            {ad.status}
                                        </span>
                                    </div>
                                    {ad.company_name && <div style={{ fontSize: 12, color: C.teal, marginBottom: 6, fontWeight: 500 }}>{ad.company_name}</div>}
                                    {ad.description && <p style={{ fontSize: 12, color: C.textMuted, lineHeight: 1.6, display: '-webkit-box', WebkitLineClamp: 2, WebkitBoxOrient: 'vertical', overflow: 'hidden' }}>{ad.description}</p>}
                                    {(ad.start_date || ad.end_date) && (
                                        <div style={{ fontSize: 11, color: C.textFaint, marginTop: 10, display: 'flex', gap: 8, alignItems: 'center' }}>
                                            <i className="ti ti-calendar" style={{ fontSize: 12 }} />
                                            {ad.start_date && <span>{ad.start_date}</span>}
                                            {ad.end_date && <span>← {ad.end_date}</span>}
                                        </div>
                                    )}
                                </div>
                                <div style={{ padding: '10px 18px', borderTop: '1px solid rgba(15,23,42,0.06)' }}>
                                    <button onClick={() => destroy(ad.id)} style={{ padding: '6px 12px', borderRadius: 8, border: `1px solid ${C.dangerBorder}`, background: C.dangerBg, color: C.dangerText, fontSize: 12, cursor: 'pointer', display: 'inline-flex', alignItems: 'center', gap: 5, fontWeight: 500 }}>
                                        <i className="ti ti-trash" /> حذف
                                    </button>
                                </div>
                            </div>
                        );
                    })}
                </div>
            )}
        </AdminLayout>
    );
}

// ── Shared form helpers ───────────────────────────────────────────────────────
export function FormCard({ title, children }) {
    return (
        <div style={{ background: C.cardBg, border: C.cardBorder, borderRadius: 16, boxShadow: C.cardShadow, padding: '22px 24px' }}>
            {title && <div style={{ fontSize: 14, fontWeight: 700, color: C.textDark, marginBottom: 16, paddingBottom: 12, borderBottom: '1px solid rgba(15,23,42,0.06)' }}>{title}</div>}
            {children}
        </div>
    );
}

export function Field({ label, children, full }) {
    return (
        <div style={full ? { gridColumn: '1/-1' } : {}}>
            <label style={{ fontSize: 11, fontWeight: 600, color: C.textMuted, display: 'block', marginBottom: 5 }}>{label}</label>
            {children}
        </div>
    );
}

export function FormActions({ onCancel, processing, submitLabel = 'حفظ' }) {
    return (
        <div style={{ display: 'flex', gap: 8, justifyContent: 'flex-end', marginTop: 18, paddingTop: 16, borderTop: '1px solid rgba(15,23,42,0.06)' }}>
            <button type="button" onClick={onCancel} style={{ padding: '7px 16px', borderRadius: 8, border: C.cardBorder, background: '#fff', fontSize: 12, cursor: 'pointer', color: C.textMed, fontWeight: 500 }}>إلغاء</button>
            <button type="submit" disabled={processing} style={{ padding: '7px 18px', borderRadius: 8, background: C.teal, color: '#fff', border: 'none', fontSize: 12, fontWeight: 700, cursor: 'pointer', opacity: processing ? 0.7 : 1, boxShadow: '0 2px 8px rgba(13,148,136,0.3)' }}>
                {processing ? 'جارٍ الحفظ...' : submitLabel}
            </button>
        </div>
    );
}
