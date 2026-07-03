import { Head, router, useForm } from '@inertiajs/react';
import { useState } from 'react';
import AdminLayout, { C } from '../../Layouts/AdminLayout';
import { PageHeader, INPUT_STYLE } from './Users';
import { FormCard, FormActions } from './Ads';

export default function Blocked({ blocked, users }) {
    const [showForm, setShowForm] = useState(false);
    const { data, setData, post, processing, reset } = useForm({ user_id: '', reason: '' });

    const submit = (e) => {
        e.preventDefault();
        post('/admin/blocked', { onSuccess: () => { reset(); setShowForm(false); } });
    };

    const unblock = (id) => {
        if (!confirm('إلغاء حظر هذا المستخدم؟')) return;
        router.delete(`/admin/blocked/${id}`, { preserveScroll: true });
    };

    return (
        <AdminLayout title="المستخدمون المحظورون">
            <Head title="المحظورون — Skillify" />

            <PageHeader title="المستخدمون المحظورون" sub={`${(blocked ?? []).length} محظور`}>
                <button onClick={() => setShowForm(v => !v)} style={{ display: 'inline-flex', alignItems: 'center', gap: 6, padding: '8px 18px', background: '#EF4444', color: '#fff', border: 'none', borderRadius: 10, fontSize: 12, fontWeight: 700, cursor: 'pointer', boxShadow: '0 2px 8px rgba(239,68,68,0.35)' }}>
                    <i className="ti ti-ban" /> حظر مستخدم
                </button>
            </PageHeader>

            {showForm && (
                <FormCard title="حظر مستخدم جديد">
                    <form onSubmit={submit}>
                        <div className="grid-cols-1 sm:grid-cols-2" style={{ display: 'grid', gap: 14 }}>
                            <div>
                                <label style={{ fontSize: 11, fontWeight: 600, color: C.textMuted, display: 'block', marginBottom: 5 }}>المستخدم *</label>
                                <select style={INPUT_STYLE} value={data.user_id} onChange={e => setData('user_id', e.target.value)} required>
                                    <option value="">— اختر مستخدماً —</option>
                                    {(users ?? []).map(u => <option key={u.id} value={u.id}>{u.first_name} {u.last_name} ({u.email})</option>)}
                                </select>
                            </div>
                            <div>
                                <label style={{ fontSize: 11, fontWeight: 600, color: C.textMuted, display: 'block', marginBottom: 5 }}>السبب</label>
                                <input style={INPUT_STYLE} value={data.reason} onChange={e => setData('reason', e.target.value)} placeholder="سبب الحظر..." />
                            </div>
                        </div>
                        <FormActions onCancel={() => setShowForm(false)} processing={processing} submitLabel="حظر" />
                    </form>
                </FormCard>
            )}

            <div style={{ background: C.cardBg, borderRadius: 16, boxShadow: C.cardShadow, border: C.cardBorder, overflow: 'hidden' }}>
                {!(blocked ?? []).length ? (
                    <div style={{ padding: '56px', textAlign: 'center', color: C.textFaint }}>
                        <i className="ti ti-ban" style={{ fontSize: 40, display: 'block', marginBottom: 10, opacity: 0.25 }} />
                        <div style={{ fontSize: 13 }}>لا يوجد مستخدمون محظورون</div>
                    </div>
                ) : (blocked ?? []).map((b, i) => (
                    <div key={b.id} style={{ display: 'flex', alignItems: 'center', gap: 14, padding: '14px 20px', borderBottom: i < blocked.length - 1 ? '1px solid rgba(15,23,42,0.06)' : 'none' }}>
                        <div style={{ width: 38, height: 38, borderRadius: '50%', background: C.dangerBg, border: `1px solid ${C.dangerBorder}`, display: 'flex', alignItems: 'center', justifyContent: 'center', fontSize: 16, color: C.dangerText, flexShrink: 0 }}>
                            <i className="ti ti-ban" />
                        </div>
                        <div style={{ flex: 1 }}>
                            <div style={{ fontSize: 13, fontWeight: 600, color: C.textDark }}>{b.user?.first_name} {b.user?.last_name}</div>
                            <div style={{ fontSize: 11, color: C.textFaint, marginTop: 2 }}>{b.user?.email} · {b.reason ?? 'لم يُذكر سبب'}</div>
                        </div>
                        <button onClick={() => unblock(b.id)} style={{ padding: '6px 14px', borderRadius: 8, border: `1px solid ${C.successBorder}`, background: C.successBg, color: C.successText, fontSize: 11, cursor: 'pointer', fontWeight: 600, display: 'flex', alignItems: 'center', gap: 5 }}>
                            <i className="ti ti-lock-open" /> إلغاء الحظر
                        </button>
                    </div>
                ))}
            </div>
        </AdminLayout>
    );
}
