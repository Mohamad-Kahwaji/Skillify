import { Head, router, useForm } from '@inertiajs/react';
import { useState } from 'react';
import AdminLayout from '../../Layouts/AdminLayout';

const INPUT = { width: '100%', padding: '8px 12px', border: '0.5px solid rgba(0,0,0,0.12)', borderRadius: 7, fontSize: 12, outline: 'none', boxSizing: 'border-box' };

export default function Blocked({ blocked, users, flash }) {
    const [showForm, setShowForm] = useState(false);
    const { data, setData, post, processing, errors, reset } = useForm({ user_id: '', reason: '' });

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

            <div style={{ display: 'flex', alignItems: 'center', justifyContent: 'space-between' }}>
                <div>
                    <div style={{ fontSize: 18, fontWeight: 700, color: '#0F172A' }}>المستخدمون المحظورون</div>
                    <div style={{ fontSize: 12, color: '#475569' }}>{(blocked ?? []).length} محظور</div>
                </div>
                <button onClick={() => setShowForm(v => !v)} style={{ display: 'inline-flex', alignItems: 'center', gap: 6, padding: '8px 16px', background: '#EF4444', color: '#fff', border: 'none', borderRadius: 9, fontSize: 12, fontWeight: 600, cursor: 'pointer' }}>
                    <i className="ti ti-ban" /> حظر مستخدم
                </button>
            </div>

            {showForm && (
                <div style={{ background: '#fff', border: '1px solid rgba(0,0,0,0.07)', borderRadius: 12, padding: 18 }}>
                    <form onSubmit={submit}>
                        <div style={{ display: 'grid', gridTemplateColumns: '1fr 1fr', gap: 12 }}>
                            <div>
                                <label style={{ fontSize: 11, color: '#475569', display: 'block', marginBottom: 3 }}>المستخدم *</label>
                                <select style={INPUT} value={data.user_id} onChange={e => setData('user_id', e.target.value)} required>
                                    <option value="">— اختر مستخدماً —</option>
                                    {(users ?? []).map(u => <option key={u.id} value={u.id}>{u.first_name} {u.last_name} ({u.email})</option>)}
                                </select>
                            </div>
                            <div>
                                <label style={{ fontSize: 11, color: '#475569', display: 'block', marginBottom: 3 }}>السبب</label>
                                <input style={INPUT} value={data.reason} onChange={e => setData('reason', e.target.value)} placeholder="سبب الحظر..." />
                            </div>
                        </div>
                        <div style={{ display: 'flex', gap: 8, justifyContent: 'flex-end', marginTop: 12 }}>
                            <button type="button" onClick={() => setShowForm(false)} style={{ padding: '6px 12px', borderRadius: 6, border: '0.5px solid rgba(0,0,0,0.12)', background: 'none', fontSize: 12, cursor: 'pointer' }}>إلغاء</button>
                            <button type="submit" disabled={processing} style={{ padding: '6px 14px', borderRadius: 6, background: '#EF4444', color: '#fff', border: 'none', fontSize: 12, fontWeight: 600, cursor: 'pointer' }}>حظر</button>
                        </div>
                    </form>
                </div>
            )}

            <div style={{ background: '#fff', border: '0.5px solid rgba(0,0,0,0.07)', borderRadius: 14, overflow: 'hidden' }}>
                {!(blocked ?? []).length ? (
                    <div style={{ padding: '48px', textAlign: 'center', color: '#94A3B8' }}>
                        <i className="ti ti-ban" style={{ fontSize: 38, display: 'block', marginBottom: 10, opacity: 0.3 }} />
                        <p>لا يوجد مستخدمون محظورون</p>
                    </div>
                ) : (blocked ?? []).map((b, i) => (
                    <div key={b.id} style={{ display: 'flex', alignItems: 'center', gap: 14, padding: '12px 18px', borderBottom: i < blocked.length - 1 ? '0.5px solid rgba(0,0,0,0.07)' : 'none' }}>
                        <div style={{ width: 36, height: 36, borderRadius: '50%', background: '#FEF2F2', display: 'flex', alignItems: 'center', justifyContent: 'center', fontSize: 14, color: '#EF4444', flexShrink: 0 }}>
                            <i className="ti ti-ban" />
                        </div>
                        <div style={{ flex: 1 }}>
                            <div style={{ fontSize: 13, fontWeight: 600, color: '#0F172A' }}>{b.user?.first_name} {b.user?.last_name}</div>
                            <div style={{ fontSize: 11, color: '#94A3B8' }}>{b.user?.email} · {b.reason ?? 'لم يُذكر سبب'}</div>
                        </div>
                        <button onClick={() => unblock(b.id)} style={{ padding: '5px 12px', borderRadius: 7, border: '0.5px solid #0D9488', background: 'none', color: '#0D9488', fontSize: 11, cursor: 'pointer', fontWeight: 600 }}>
                            إلغاء الحظر
                        </button>
                    </div>
                ))}
            </div>
        </AdminLayout>
    );
}
