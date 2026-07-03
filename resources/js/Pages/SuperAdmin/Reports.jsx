import { Head, Link } from '@inertiajs/react';
import SuperAdminLayout from '../../Layouts/SuperAdminLayout';

const AV = ['#7C3AED','#0D9488','#2563EB','#D97706','#DC2626','#0891B2'];

export default function Reports({ reports }) {
    const all = reports ?? [];
    return (
        <SuperAdminLayout title="البلاغات">
            <Head title="البلاغات — Skillify" />

            <div style={{ display: 'flex', alignItems: 'flex-start', justifyContent: 'space-between', flexWrap: 'wrap', gap: 12 }}>
                <div>
                    <h1 style={{ fontSize: 22, fontWeight: 800, color: '#1E1B4B', margin: 0 }}>البلاغات</h1>
                    <p style={{ fontSize: 12, color: '#94A3B8', marginTop: 4 }}>{all.length} بلاغ</p>
                </div>
            </div>

            <div style={{ background: '#fff', border: '0.5px solid rgba(0,0,0,0.07)', borderRadius: 16, overflow: 'hidden', boxShadow: '0 2px 12px rgba(0,0,0,0.04)' }}>
                {!all.length ? (
                    <div style={{ padding: '72px 24px', textAlign: 'center', color: '#94A3B8' }}>
                        <i className="ti ti-flag" style={{ fontSize: 48, display: 'block', opacity: 0.1, marginBottom: 14 }} />
                        <div style={{ fontSize: 15, fontWeight: 600, color: '#64748B', marginBottom: 6 }}>لا توجد بلاغات</div>
                        <p style={{ fontSize: 13, margin: 0 }}>ستظهر هنا البلاغات الواردة من المستخدمين.</p>
                    </div>
                ) : all.map((r, i) => (
                    <div key={r.id} style={{ display: 'flex', alignItems: 'flex-start', gap: 14, padding: '16px 22px', borderBottom: i < all.length - 1 ? '0.5px solid rgba(0,0,0,0.06)' : 'none', transition: 'background 0.12s' }}
                        onMouseEnter={e => e.currentTarget.style.background = '#FAFAFE'}
                        onMouseLeave={e => e.currentTarget.style.background = 'transparent'}>
                        <div style={{ width: 40, height: 40, borderRadius: '50%', background: `linear-gradient(135deg,${AV[i%6]},${AV[(i+2)%6]})`, color: '#fff', display: 'flex', alignItems: 'center', justifyContent: 'center', fontSize: 14, fontWeight: 700, flexShrink: 0 }}>
                            {(r.user?.first_name ?? 'U')[0].toUpperCase()}
                        </div>
                        <div style={{ flex: 1, minWidth: 0 }}>
                            <div style={{ fontSize: 13, fontWeight: 600, color: '#0F172A', lineHeight: 1.5 }}>{r.reason ?? r.body ?? 'بلاغ'}</div>
                            <div style={{ fontSize: 11, color: '#94A3B8', marginTop: 4, display: 'flex', alignItems: 'center', gap: 10, flexWrap: 'wrap' }}>
                                <span><i className="ti ti-user" style={{ marginLeft: 4 }} />{r.user?.first_name} {r.user?.last_name}</span>
                                <span><i className="ti ti-mail" style={{ marginLeft: 4 }} />{r.user?.email}</span>
                                <span><i className="ti ti-clock" style={{ marginLeft: 4 }} />{new Date(r.created_at).toLocaleDateString('ar', { month: 'short', day: 'numeric', year: 'numeric' })}</span>
                            </div>
                            {r.post_id && (
                                <Link href={`/admin/reports/post/${r.post_id}`} style={{ marginTop: 8, display: 'inline-flex', alignItems: 'center', gap: 5, fontSize: 11, color: '#7C3AED', background: '#F5F3FF', border: '1px solid #DDD6FE', padding: '3px 10px', borderRadius: 8, textDecoration: 'none', fontWeight: 600 }}>
                                    <i className="ti ti-eye" style={{ fontSize: 11 }} /> عرض المنشور المرتبط
                                </Link>
                            )}
                        </div>
                        <span style={{ fontSize: 10, fontWeight: 700, padding: '4px 11px', borderRadius: 20, background: '#FEF2F2', color: '#B91C1C', border: '1px solid #FECACA', display: 'inline-flex', alignItems: 'center', gap: 4, flexShrink: 0 }}>
                            <i className="ti ti-flag" style={{ fontSize: 10 }} /> بلاغ
                        </span>
                    </div>
                ))}
            </div>
        </SuperAdminLayout>
    );
}
