import { Head, Link } from '@inertiajs/react';
import AdminLayout, { C } from '../../Layouts/AdminLayout';

const AV = ['#0EA5E9','#8B5CF6','#F59E0B','#10B981','#EF4444','#EC4899'];

export default function Reports({ reports }) {
    return (
        <AdminLayout title="البلاغات">
            <Head title="البلاغات — Skillify" />

            <div>
                <div style={{ fontSize: 20, fontWeight: 800, color: C.textDark }}>البلاغات</div>
                <div style={{ fontSize: 12, color: C.textMuted, marginTop: 2 }}>{(reports ?? []).length} بلاغ</div>
            </div>

            <div style={{ background: C.cardBg, borderRadius: 16, boxShadow: C.cardShadow, border: C.cardBorder, overflow: 'hidden' }}>
                {!(reports ?? []).length ? (
                    <div style={{ padding: '56px', textAlign: 'center', color: C.textFaint }}>
                        <i className="ti ti-flag" style={{ fontSize: 40, display: 'block', marginBottom: 10, opacity: 0.25 }} />
                        <div style={{ fontSize: 13 }}>لا توجد بلاغات</div>
                    </div>
                ) : (reports ?? []).map((r, i) => (
                    <div key={r.id} style={{ display: 'flex', alignItems: 'flex-start', gap: 14, padding: '16px 20px', borderBottom: i < reports.length - 1 ? '1px solid rgba(15,23,42,0.06)' : 'none' }}>
                        <div style={{ width: 38, height: 38, borderRadius: '50%', background: AV[i % 6], color: '#fff', display: 'flex', alignItems: 'center', justifyContent: 'center', fontSize: 14, fontWeight: 700, flexShrink: 0 }}>
                            {(r.user?.first_name ?? 'U')[0].toUpperCase()}
                        </div>
                        <div style={{ flex: 1, minWidth: 0 }}>
                            <div style={{ fontSize: 13, fontWeight: 600, color: C.textDark }}>{r.reason ?? r.body ?? 'Report'}</div>
                            <div style={{ fontSize: 11, color: C.textFaint, marginTop: 3 }}>
                                من: {r.user?.first_name} {r.user?.last_name} ·{' '}
                                {new Date(r.created_at).toLocaleDateString('ar', { month: 'short', day: 'numeric', year: 'numeric' })}
                            </div>
                            {r.post_id && (
                                <Link href={`/admin/reports/post/${r.post_id}`} style={{ fontSize: 11, color: C.primary, textDecoration: 'none', display: 'inline-flex', alignItems: 'center', gap: 4, marginTop: 6, background: C.infoBg, padding: '3px 9px', borderRadius: 6, fontWeight: 500 }}>
                                    <i className="ti ti-eye" /> عرض المنشور المرتبط
                                </Link>
                            )}
                        </div>
                        <span style={{ fontSize: 10, fontWeight: 700, padding: '3px 10px', borderRadius: 20, background: C.dangerBg, color: C.dangerText, border: `1px solid ${C.dangerBorder}`, display: 'inline-flex', alignItems: 'center', gap: 4, flexShrink: 0 }}>
                            <i className="ti ti-flag" style={{ fontSize: 10 }} /> بلاغ
                        </span>
                    </div>
                ))}
            </div>
        </AdminLayout>
    );
}
