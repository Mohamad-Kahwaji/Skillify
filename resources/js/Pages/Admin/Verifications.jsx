import { Head, router } from '@inertiajs/react';
import AdminLayout, { C } from '../../Layouts/AdminLayout';

const STATUS_STYLE = {
    pending:  { bg: C.warningBg, color: C.warningText, border: C.warningBorder },
    active:   { bg: C.successBg, color: C.successText, border: C.successBorder },
    rejected: { bg: C.dangerBg,  color: C.dangerText,  border: C.dangerBorder  },
};

export default function Verifications({ pending }) {
    const approve = (id) => router.patch(`/admin/verifications/${id}/approve`, {}, { preserveScroll: true });
    const reject  = (id) => router.patch(`/admin/verifications/${id}/reject`,  {}, { preserveScroll: true });

    return (
        <AdminLayout title="التحقق من الأعمال">
            <Head title="التحقق من الأعمال — Skillify" />

            <div>
                <div style={{ fontSize: 20, fontWeight: 800, color: C.textDark }}>التحقق من حسابات الأعمال</div>
                <div style={{ fontSize: 12, color: C.textMuted, marginTop: 2 }}>{(pending ?? []).length} طلب معلق</div>
            </div>

            {!(pending ?? []).length ? (
                <div style={{ background: C.cardBg, borderRadius: 16, boxShadow: C.cardShadow, border: C.cardBorder, padding: '64px 24px', textAlign: 'center', color: C.textFaint }}>
                    <i className="ti ti-shield-check" style={{ fontSize: 44, display: 'block', marginBottom: 12, opacity: 0.25 }} />
                    <div style={{ fontSize: 14, fontWeight: 500 }}>لا توجد طلبات تحقق معلقة</div>
                    <div style={{ fontSize: 12, marginTop: 4 }}>ستظهر هنا طلبات التحقق الجديدة</div>
                </div>
            ) : (
                <div style={{ display: 'flex', flexDirection: 'column', gap: 12 }}>
                    {(pending ?? []).map(b => {
                        const s = STATUS_STYLE[b.status ?? 'pending'];
                        return (
                            <div key={b.id} style={{ background: C.cardBg, borderRadius: 16, boxShadow: C.cardShadow, border: C.cardBorder, padding: '18px 22px', display: 'flex', alignItems: 'center', gap: 16, flexWrap: 'wrap' }}>
                                <div style={{ width: 52, height: 52, borderRadius: 14, background: C.warningBg, border: `1px solid ${C.warningBorder}`, display: 'flex', alignItems: 'center', justifyContent: 'center', fontSize: 22, color: '#F59E0B', flexShrink: 0, overflow: 'hidden' }}>
                                    {b.image
                                        ? <img src={`/storage/${b.image}`} style={{ width: '100%', height: '100%', objectFit: 'cover', borderRadius: 14 }} alt={b.name} />
                                        : <i className="ti ti-briefcase" />}
                                </div>
                                <div style={{ flex: 1, minWidth: 180 }}>
                                    <div style={{ fontSize: 15, fontWeight: 700, color: C.textDark }}>{b.name}</div>
                                    <div style={{ fontSize: 12, color: C.teal, marginTop: 1 }}>{b.name_job}</div>
                                    <div style={{ fontSize: 11, color: C.textFaint, marginTop: 3, display: 'flex', alignItems: 'center', gap: 4 }}>
                                        <i className="ti ti-user" style={{ fontSize: 11 }} />
                                        {b.user?.first_name} {b.user?.last_name} · {b.user?.email}
                                    </div>
                                    {b.description && <div style={{ fontSize: 12, color: C.textMuted, marginTop: 5, lineHeight: 1.5 }}>{b.description}</div>}
                                </div>
                                <div style={{ display: 'flex', gap: 8, alignItems: 'center', flexShrink: 0, flexWrap: 'wrap' }}>
                                    <span style={{ fontSize: 11, fontWeight: 700, padding: '4px 12px', borderRadius: 20, background: s.bg, color: s.color, border: `1px solid ${s.border}` }}>
                                        {b.status ?? 'pending'}
                                    </span>
                                    <button onClick={() => approve(b.id)} style={{ padding: '7px 16px', borderRadius: 9, border: 'none', background: 'linear-gradient(135deg,#10B981,#059669)', color: '#fff', fontSize: 12, fontWeight: 600, cursor: 'pointer', display: 'flex', alignItems: 'center', gap: 5, boxShadow: '0 2px 8px rgba(16,185,129,0.3)' }}>
                                        <i className="ti ti-check" /> قبول
                                    </button>
                                    <button onClick={() => reject(b.id)} style={{ padding: '7px 16px', borderRadius: 9, border: `1px solid ${C.dangerBorder}`, background: C.dangerBg, color: C.dangerText, fontSize: 12, fontWeight: 600, cursor: 'pointer', display: 'flex', alignItems: 'center', gap: 5 }}>
                                        <i className="ti ti-x" /> رفض
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
