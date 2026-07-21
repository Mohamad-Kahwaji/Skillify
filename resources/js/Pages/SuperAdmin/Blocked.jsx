import { Head, router } from '@inertiajs/react';
import SuperAdminLayout from '../../Layouts/SuperAdminLayout';

export default function Blocked({ users }) {
    const unblock = (id) => {
        if (!confirm('إلغاء حظر هذا المستخدم؟')) return;
        router.patch(`/super-admin/users/${id}/unblock`, {}, { preserveScroll: true });
    };

    const all = users ?? [];

    return (
        <SuperAdminLayout title="المحظورون">
            <Head title="المحظورون — Skillify" />

            <div style={{ display: 'flex', alignItems: 'flex-start', justifyContent: 'space-between', flexWrap: 'wrap', gap: 12 }}>
                <div>
                    <h1 style={{ fontSize: 22, fontWeight: 800, color: '#1E1B4B', margin: 0 }}>المستخدمون المحظورون</h1>
                    <p style={{ fontSize: 12, color: '#94A3B8', marginTop: 4 }}>{all.length} محظور</p>
                </div>
            </div>

            {/* List */}
            <div style={{ background: '#fff', border: '0.5px solid rgba(0,0,0,0.07)', borderRadius: 16, overflow: 'hidden', boxShadow: '0 2px 12px rgba(0,0,0,0.04)' }}>
                {!all.length ? (
                    <div style={{ padding: '72px 24px', textAlign: 'center', color: '#94A3B8' }}>
                        <i className="ti ti-ban" style={{ fontSize: 48, display: 'block', opacity: 0.1, marginBottom: 14 }} />
                        <div style={{ fontSize: 15, fontWeight: 600, color: '#64748B', marginBottom: 6 }}>لا يوجد مستخدمون محظورون</div>
                        <p style={{ fontSize: 13, margin: 0 }}>يمكنك حظر مستخدم من صفحة المستخدمين.</p>
                    </div>
                ) : all.map((u, i) => (
                    <div key={u.id} style={{ display: 'flex', alignItems: 'center', gap: 14, padding: '14px 22px', borderBottom: i < all.length - 1 ? '0.5px solid rgba(0,0,0,0.06)' : 'none', transition: 'background 0.12s' }}
                        onMouseEnter={e => e.currentTarget.style.background = '#FAFAFE'}
                        onMouseLeave={e => e.currentTarget.style.background = 'transparent'}>
                        <div style={{ width: 40, height: 40, borderRadius: '50%', background: 'linear-gradient(135deg,#EF4444,#DC2626)', display: 'flex', alignItems: 'center', justifyContent: 'center', fontSize: 15, color: '#fff', fontWeight: 700, flexShrink: 0 }}>
                            {(u.first_name ?? 'U')[0].toUpperCase()}
                        </div>
                        <div style={{ flex: 1, minWidth: 0 }}>
                            <div style={{ fontSize: 13, fontWeight: 700, color: '#0F172A' }}>{u.first_name} {u.last_name}</div>
                            <div style={{ fontSize: 11, color: '#94A3B8', marginTop: 2, display: 'flex', alignItems: 'center', gap: 10, flexWrap: 'wrap' }}>
                                <span>{u.email}</span>
                                {u.phone && <span>· {u.phone}</span>}
                            </div>
                        </div>
                        <button onClick={() => unblock(u.id)} style={{ display: 'inline-flex', alignItems: 'center', gap: 6, padding: '7px 16px', borderRadius: 9, border: '1px solid #6EE7B7', background: '#D1FAE5', color: '#065F46', fontSize: 12, fontWeight: 600, cursor: 'pointer', fontFamily: "'Cairo','Inter',sans-serif", transition: 'background 0.13s' }}
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
