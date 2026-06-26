import { Head, router } from '@inertiajs/react';
import AdminLayout from '../../Layouts/AdminLayout';

const STATUS = {
    pending:  { bg: '#FEF3C7', color: '#92400E' },
    active:   { bg: '#F0FDF4', color: '#134E4A' },
    rejected: { bg: '#FEF2F2', color: '#B91C1C' },
};

export default function Verifications({ pending, flash }) {
    const approve = (id) => router.patch(`/admin/verifications/${id}/approve`, {}, { preserveScroll: true });
    const reject  = (id) => router.patch(`/admin/verifications/${id}/reject`,  {}, { preserveScroll: true });

    return (
        <AdminLayout title="التحقق من الأعمال">
            <Head title="التحقق من الأعمال — Skillify" />

            <div>
                <div style={{ fontSize: 18, fontWeight: 700, color: '#0F172A' }}>التحقق من حسابات الأعمال</div>
                <div style={{ fontSize: 12, color: '#475569' }}>{(pending ?? []).length} طلب معلق</div>
            </div>

            {!(pending ?? []).length ? (
                <div style={{ background: '#fff', border: '0.5px solid rgba(0,0,0,0.07)', borderRadius: 14, padding: '56px 24px', textAlign: 'center', color: '#94A3B8' }}>
                    <i className="ti ti-shield-check" style={{ fontSize: 42, display: 'block', marginBottom: 10, opacity: 0.3 }} />
                    <p>لا توجد طلبات تحقق معلقة</p>
                </div>
            ) : (
                <div style={{ display: 'flex', flexDirection: 'column', gap: 12 }}>
                    {(pending ?? []).map(b => (
                        <div key={b.id} style={{ background: '#fff', border: '0.5px solid rgba(0,0,0,0.07)', borderRadius: 14, padding: '16px 20px', display: 'flex', alignItems: 'center', gap: 16, flexWrap: 'wrap' }}>
                            <div style={{ width: 48, height: 48, borderRadius: 12, background: '#FEF3C7', display: 'flex', alignItems: 'center', justifyContent: 'center', fontSize: 20, color: '#F59E0B', flexShrink: 0 }}>
                                {b.image ? <img src={`/storage/${b.image}`} style={{ width: '100%', height: '100%', objectFit: 'cover', borderRadius: 12 }} alt={b.name} /> : <i className="ti ti-briefcase" />}
                            </div>
                            <div style={{ flex: 1, minWidth: 180 }}>
                                <div style={{ fontSize: 14, fontWeight: 700, color: '#0F172A' }}>{b.name}</div>
                                <div style={{ fontSize: 12, color: '#0D9488' }}>{b.name_job}</div>
                                <div style={{ fontSize: 11, color: '#94A3B8', marginTop: 2 }}>
                                    المالك: {b.user?.first_name} {b.user?.last_name} · {b.user?.email}
                                </div>
                                {b.description && <div style={{ fontSize: 11, color: '#475569', marginTop: 4 }}>{b.description}</div>}
                            </div>
                            <div style={{ display: 'flex', gap: 8, alignItems: 'center', flexShrink: 0 }}>
                                <span style={{ fontSize: 10, fontWeight: 600, padding: '3px 10px', borderRadius: 20, ...STATUS[b.status ?? 'pending'] }}>
                                    {b.status ?? 'pending'}
                                </span>
                                <button onClick={() => approve(b.id)} style={{ padding: '6px 14px', borderRadius: 8, border: 'none', background: '#0D9488', color: '#fff', fontSize: 12, fontWeight: 600, cursor: 'pointer', display: 'flex', alignItems: 'center', gap: 4 }}>
                                    <i className="ti ti-check" /> قبول
                                </button>
                                <button onClick={() => reject(b.id)} style={{ padding: '6px 14px', borderRadius: 8, border: '0.5px solid #EF4444', background: 'none', color: '#EF4444', fontSize: 12, fontWeight: 600, cursor: 'pointer', display: 'flex', alignItems: 'center', gap: 4 }}>
                                    <i className="ti ti-x" /> رفض
                                </button>
                            </div>
                        </div>
                    ))}
                </div>
            )}
        </AdminLayout>
    );
}
