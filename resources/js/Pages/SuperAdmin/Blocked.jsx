import { Head, router, useForm } from '@inertiajs/react';
import { useState } from 'react';
import SuperAdminLayout from '../../Layouts/SuperAdminLayout';

export default function Blocked({ blocked, users }) {
    const [showForm, setShowForm] = useState(false);
    const { data, setData, post, processing, reset } = useForm({ user_id: '', reason: '' });

    const submit = (e) => {
        e.preventDefault();
        post('/super-admin/blocked', { onSuccess: () => { reset(); setShowForm(false); } });
    };

    const unblock = (id) => {
        if (!confirm('إلغاء حظر هذا المستخدم؟')) return;
        router.delete(`/super-admin/blocked/${id}`, { preserveScroll: true });
    };

    const all = blocked ?? [];

    return (
        <SuperAdminLayout title="المحظورون">
            <Head title="المحظورون — Skillify" />

            <div style={{ display: 'flex', alignItems: 'flex-start', justifyContent: 'space-between', flexWrap: 'wrap', gap: 12 }}>
                <div>
                    <h1 style={{ fontSize: 22, fontWeight: 800, color: '#1E1B4B', margin: 0 }}>المستخدمون المحظورون</h1>
                    <p style={{ fontSize: 12, color: '#94A3B8', marginTop: 4 }}>{all.length} محظور</p>
                </div>
                <button onClick={() => setShowForm(v => !v)} style={{ display: 'inline-flex', alignItems: 'center', gap: 7, padding: '10px 20px', background: 'linear-gradient(135deg,#EF4444,#DC2626)', color: '#fff', border: 'none', borderRadius: 11, fontSize: 13, fontWeight: 700, cursor: 'pointer', fontFamily: "'Cairo','Inter',sans-serif", boxShadow: '0 4px 14px rgba(239,68,68,0.32)' }}>
                    <i className="ti ti-ban" /> حظر مستخدم
                </button>
            </div>

            {/* Form */}
            {showForm && (
                <div style={{ background: '#fff', border: '0.5px solid rgba(0,0,0,0.09)', borderRadius: 16, padding: '22px 24px', boxShadow: '0 2px 16px rgba(0,0,0,0.06)' }}>
                    <div style={{ fontSize: 14, fontWeight: 700, color: '#1E1B4B', marginBottom: 16, display: 'flex', alignItems: 'center', gap: 8 }}>
                        <div style={{ width: 28, height: 28, borderRadius: 8, background: '#FEE2E2', display: 'flex', alignItems: 'center', justifyContent: 'center', color: '#DC2626', fontSize: 13 }}>
                            <i className="ti ti-ban" />
                        </div>
                        حظر مستخدم جديد
                    </div>
                    <form onSubmit={submit}>
                        <div className="grid grid-cols-1 sm:grid-cols-2" style={{ display: 'grid', gap: 16, marginBottom: 16 }}>
                            <div>
                                <label style={{ fontSize: 11, fontWeight: 600, color: '#475569', display: 'block', marginBottom: 6 }}>المستخدم *</label>
                                <select value={data.user_id} onChange={e => setData('user_id', e.target.value)} required
                                    style={{ width: '100%', padding: '9px 13px', border: '1px solid rgba(0,0,0,0.12)', borderRadius: 9, fontSize: 13, outline: 'none', background: '#FAFAFA', fontFamily: "'Cairo','Inter',sans-serif", boxSizing: 'border-box' }}>
                                    <option value="">— اختر مستخدماً —</option>
                                    {(users ?? []).map(u => <option key={u.id} value={u.id}>{u.first_name} {u.last_name} ({u.email})</option>)}
                                </select>
                            </div>
                            <div>
                                <label style={{ fontSize: 11, fontWeight: 600, color: '#475569', display: 'block', marginBottom: 6 }}>السبب *</label>
                                <input value={data.reason} onChange={e => setData('reason', e.target.value)} required placeholder="سبب الحظر..."
                                    style={{ width: '100%', padding: '9px 13px', border: '1px solid rgba(0,0,0,0.12)', borderRadius: 9, fontSize: 13, outline: 'none', background: '#FAFAFA', fontFamily: "'Cairo','Inter',sans-serif", boxSizing: 'border-box' }} />
                            </div>
                        </div>
                        <div style={{ display: 'flex', gap: 10, justifyContent: 'flex-end' }}>
                            <button type="button" onClick={() => setShowForm(false)} style={{ padding: '8px 18px', borderRadius: 9, border: '1px solid rgba(0,0,0,0.12)', background: 'none', fontSize: 13, cursor: 'pointer', fontFamily: "'Cairo','Inter',sans-serif" }}>إلغاء</button>
                            <button type="submit" disabled={processing} style={{ padding: '8px 22px', borderRadius: 9, background: '#DC2626', color: '#fff', border: 'none', fontSize: 13, fontWeight: 700, cursor: 'pointer', fontFamily: "'Cairo','Inter',sans-serif", opacity: processing ? 0.7 : 1 }}>
                                <i className="ti ti-ban" style={{ marginLeft: 6 }} />{processing ? 'جارٍ الحظر...' : 'تأكيد الحظر'}
                            </button>
                        </div>
                    </form>
                </div>
            )}

            {/* List */}
            <div style={{ background: '#fff', border: '0.5px solid rgba(0,0,0,0.07)', borderRadius: 16, overflow: 'hidden', boxShadow: '0 2px 12px rgba(0,0,0,0.04)' }}>
                {!all.length ? (
                    <div style={{ padding: '72px 24px', textAlign: 'center', color: '#94A3B8' }}>
                        <i className="ti ti-ban" style={{ fontSize: 48, display: 'block', opacity: 0.1, marginBottom: 14 }} />
                        <div style={{ fontSize: 15, fontWeight: 600, color: '#64748B', marginBottom: 6 }}>لا يوجد مستخدمون محظورون</div>
                    </div>
                ) : all.map((b, i) => (
                    <div key={b.id} style={{ display: 'flex', alignItems: 'center', gap: 14, padding: '14px 22px', borderBottom: i < all.length - 1 ? '0.5px solid rgba(0,0,0,0.06)' : 'none', transition: 'background 0.12s' }}
                        onMouseEnter={e => e.currentTarget.style.background = '#FAFAFE'}
                        onMouseLeave={e => e.currentTarget.style.background = 'transparent'}>
                        <div style={{ width: 40, height: 40, borderRadius: '50%', background: 'linear-gradient(135deg,#EF4444,#DC2626)', display: 'flex', alignItems: 'center', justifyContent: 'center', fontSize: 15, color: '#fff', fontWeight: 700, flexShrink: 0 }}>
                            {(b.user?.first_name ?? 'U')[0].toUpperCase()}
                        </div>
                        <div style={{ flex: 1, minWidth: 0 }}>
                            <div style={{ fontSize: 13, fontWeight: 700, color: '#0F172A' }}>{b.user?.first_name} {b.user?.last_name}</div>
                            <div style={{ fontSize: 11, color: '#94A3B8', marginTop: 2, display: 'flex', alignItems: 'center', gap: 10, flexWrap: 'wrap' }}>
                                <span>{b.user?.email}</span>
                                {b.reason && <span style={{ color: '#DC2626' }}>· {b.reason}</span>}
                            </div>
                        </div>
                        <button onClick={() => unblock(b.id)} style={{ display: 'inline-flex', alignItems: 'center', gap: 6, padding: '7px 16px', borderRadius: 9, border: '1px solid #6EE7B7', background: '#D1FAE5', color: '#065F46', fontSize: 12, fontWeight: 600, cursor: 'pointer', fontFamily: "'Cairo','Inter',sans-serif", transition: 'background 0.13s' }}
                            onMouseEnter={e => e.currentTarget.style.background = '#A7F3D0'}
                            onMouseLeave={e => e.currentTarget.style.background = '#D1FAE5'}>
                            <i className="ti ti-lock-open" /> إلغاء الحظر
                        </button>
                    </div>
                ))}
            </div>
        </SuperAdminLayout>
    );
}
