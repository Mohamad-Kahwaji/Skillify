import { Head, router } from '@inertiajs/react';
import { useState } from 'react';
import AdminLayout, { C } from '../../Layouts/AdminLayout';
import { PageHeader, UserDrawer } from './Users';

export default function Blocked({ users }) {
    const [selected, setSelected] = useState(null);
    const [selectedIdx, setSelectedIdx] = useState(0);

    const all = users ?? [];

    const unblock = (id) => {
        if (!confirm('إلغاء حظر هذا المستخدم؟')) return;
        setSelected(null);
        router.patch(`/admin/users/${id}/activate`, {}, { preserveScroll: true });
    };

    const destroy = (id) => {
        if (!confirm('حذف هذا المستخدم نهائياً؟')) return;
        setSelected(null);
        router.delete(`/admin/users/${id}`, { preserveScroll: true });
    };

    const openUser = (u, idx) => { setSelected(u); setSelectedIdx(idx); };

    return (
        <AdminLayout title="المستخدمون المحظورون">
            <Head title="المحظورون — Skillify" />

            {selected && (
                <UserDrawer
                    user={selected}
                    index={selectedIdx}
                    onClose={() => setSelected(null)}
                    onDelete={destroy}
                    onToggle={() => unblock(selected.id)}
                />
            )}

            <PageHeader title="المستخدمون المحظورون" sub={`${all.length} محظور`} />

            <div style={{ background: C.cardBg, borderRadius: 16, boxShadow: C.cardShadow, border: C.cardBorder, overflow: 'hidden' }}>
                {!all.length ? (
                    <div style={{ padding: '56px', textAlign: 'center', color: C.textFaint }}>
                        <i className="ti ti-ban" style={{ fontSize: 40, display: 'block', marginBottom: 10, opacity: 0.25 }} />
                        <div style={{ fontSize: 13 }}>لا يوجد مستخدمون محظورون</div>
                    </div>
                ) : all.map((u, i) => (
                    <div key={u.id} style={{ display: 'flex', alignItems: 'center', gap: 14, padding: '14px 20px', borderBottom: i < all.length - 1 ? '1px solid rgba(15,23,42,0.06)' : 'none' }}>
                        <div style={{ width: 38, height: 38, borderRadius: '50%', background: C.dangerBg, border: `1px solid ${C.dangerBorder}`, display: 'flex', alignItems: 'center', justifyContent: 'center', fontSize: 16, color: C.dangerText, flexShrink: 0 }}>
                            <i className="ti ti-ban" />
                        </div>
                        <div style={{ flex: 1, minWidth: 0 }}>
                            <div onClick={() => openUser(u, i)} style={{ fontSize: 13, fontWeight: 600, color: C.textDark, cursor: 'pointer', display: 'inline-block' }}
                                onMouseEnter={e => e.currentTarget.style.textDecoration = 'underline'}
                                onMouseLeave={e => e.currentTarget.style.textDecoration = 'none'}>
                                {u.first_name} {u.last_name}
                            </div>
                            <div style={{ fontSize: 11, color: C.textFaint, marginTop: 2 }}>{u.email}{u.phone ? ` · ${u.phone}` : ''}</div>
                        </div>
                        <button onClick={() => unblock(u.id)} style={{ padding: '6px 14px', borderRadius: 8, border: `1px solid ${C.successBorder}`, background: C.successBg, color: C.successText, fontSize: 11, cursor: 'pointer', fontWeight: 600, display: 'flex', alignItems: 'center', gap: 5 }}>
                            <i className="ti ti-lock-open" /> إلغاء الحظر
                        </button>
                    </div>
                ))}
            </div>
        </AdminLayout>
    );
}
