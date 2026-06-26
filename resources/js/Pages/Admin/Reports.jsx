import { Head, Link } from '@inertiajs/react';
import AdminLayout from '../../Layouts/AdminLayout';

const AV_COLORS = ['#0D9488','#3B82F6','#8B5CF6','#F59E0B','#EF4444','#EC4899'];

export default function Reports({ reports, flash }) {
    return (
        <AdminLayout title="البلاغات">
            <Head title="البلاغات — Skillify" />

            <div>
                <div style={{ fontSize: 18, fontWeight: 700, color: '#0F172A' }}>البلاغات</div>
                <div style={{ fontSize: 12, color: '#475569' }}>{(reports ?? []).length} بلاغ</div>
            </div>

            <div style={{ background: '#fff', border: '0.5px solid rgba(0,0,0,0.07)', borderRadius: 14, overflow: 'hidden' }}>
                {!(reports ?? []).length ? (
                    <div style={{ padding: '48px', textAlign: 'center', color: '#94A3B8' }}>
                        <i className="ti ti-flag" style={{ fontSize: 38, display: 'block', marginBottom: 10, opacity: 0.3 }} />
                        <p>لا توجد بلاغات</p>
                    </div>
                ) : (reports ?? []).map((r, i) => (
                    <div key={r.id} style={{ display: 'flex', alignItems: 'flex-start', gap: 14, padding: '14px 18px', borderBottom: i < reports.length - 1 ? '0.5px solid rgba(0,0,0,0.07)' : 'none' }}>
                        <div style={{ width: 36, height: 36, borderRadius: '50%', background: AV_COLORS[i % 6], color: '#fff', display: 'flex', alignItems: 'center', justifyContent: 'center', fontSize: 13, fontWeight: 600, flexShrink: 0 }}>
                            {(r.user?.first_name ?? 'U')[0].toUpperCase()}
                        </div>
                        <div style={{ flex: 1, minWidth: 0 }}>
                            <div style={{ fontSize: 13, fontWeight: 600, color: '#0F172A' }}>{r.reason ?? r.body ?? 'Report'}</div>
                            <div style={{ fontSize: 11, color: '#94A3B8', marginTop: 2 }}>
                                من: {r.user?.first_name} {r.user?.last_name} ·{' '}
                                {new Date(r.created_at).toLocaleDateString('ar', { month: 'short', day: 'numeric', year: 'numeric' })}
                            </div>
                            {r.post_id && (
                                <Link href={`/admin/reports/post/${r.post_id}`} style={{ fontSize: 11, color: '#0D9488', textDecoration: 'none', display: 'inline-flex', alignItems: 'center', gap: 4, marginTop: 4 }}>
                                    <i className="ti ti-eye" /> عرض المنشور المرتبط
                                </Link>
                            )}
                        </div>
                        <div style={{ flexShrink: 0 }}>
                            <span style={{ fontSize: 9, fontWeight: 600, padding: '2px 8px', borderRadius: 20, background: '#FEF2F2', color: '#B91C1C', display: 'inline-flex', alignItems: 'center', gap: 4 }}>
                                <i className="ti ti-flag" style={{ fontSize: 10 }} /> بلاغ
                            </span>
                        </div>
                    </div>
                ))}
            </div>
        </AdminLayout>
    );
}
