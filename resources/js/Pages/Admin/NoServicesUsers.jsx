import { Head, Link } from '@inertiajs/react';
import AdminLayout from '../../Layouts/AdminLayout';

const AV_COLORS = ['#0D9488','#3B82F6','#8B5CF6','#F59E0B','#EF4444','#EC4899'];

export default function NoServicesUsers({ users }) {
    return (
        <AdminLayout title="مستخدمون بدون خدمات">
            <Head title="مستخدمون بدون خدمات — Skillify" />

            <div style={{ display: 'flex', alignItems: 'center', justifyContent: 'space-between', flexWrap: 'wrap', gap: 12 }}>
                <div>
                    <div style={{ fontSize: 18, fontWeight: 700, color: '#0F172A' }}>مستخدمون بدون خدمات</div>
                    <div style={{ fontSize: 12, color: '#475569' }}>{(users ?? []).length} مستخدم لم ينشر أي خدمة</div>
                </div>
                <Link href="/admin/users" style={{ display: 'inline-flex', alignItems: 'center', gap: 6, padding: '7px 14px', background: '#fff', border: '0.5px solid rgba(0,0,0,0.1)', borderRadius: 8, fontSize: 12, fontWeight: 500, textDecoration: 'none', color: '#0F172A' }}>
                    <i className="ti ti-arrow-right" /> جميع المستخدمين
                </Link>
            </div>

            {!(users ?? []).length ? (
                <div style={{ background: '#fff', border: '0.5px solid rgba(0,0,0,0.07)', borderRadius: 14, padding: '64px 24px', textAlign: 'center', color: '#94A3B8' }}>
                    <i className="ti ti-circle-check" style={{ fontSize: 48, display: 'block', marginBottom: 12, opacity: 0.3, color: '#0D9488' }} />
                    <div style={{ fontSize: 15, fontWeight: 600, marginBottom: 6, color: '#0D9488' }}>رائع! الكل على ما يرام</div>
                    <p style={{ fontSize: 13 }}>كل مستخدم مسجل لديه خدمة واحدة على الأقل.</p>
                </div>
            ) : (
                <div style={{ background: '#fff', border: '0.5px solid rgba(0,0,0,0.07)', borderRadius: 14, overflow: 'hidden' }}>
                    <table style={{ width: '100%', borderCollapse: 'collapse', fontSize: 12 }}>
                        <thead>
                            <tr style={{ background: '#F8FAFC', borderBottom: '0.5px solid rgba(0,0,0,0.07)' }}>
                                {['المستخدم', 'البريد', 'الهاتف', 'المدينة', 'تاريخ التسجيل'].map(h => (
                                    <th key={h} style={{ padding: '10px 14px', textAlign: 'right', fontWeight: 600, color: '#475569' }}>{h}</th>
                                ))}
                            </tr>
                        </thead>
                        <tbody>
                            {(users ?? []).map((u, i) => (
                                <tr key={u.id} style={{ borderBottom: '0.5px solid rgba(0,0,0,0.05)' }}
                                    onMouseEnter={e => e.currentTarget.style.background = '#F8FAFC'}
                                    onMouseLeave={e => e.currentTarget.style.background = 'transparent'}
                                >
                                    <td style={{ padding: '10px 14px' }}>
                                        <div style={{ display: 'flex', alignItems: 'center', gap: 8 }}>
                                            <div style={{ width: 30, height: 30, borderRadius: '50%', background: AV_COLORS[i % 6], color: '#fff', display: 'flex', alignItems: 'center', justifyContent: 'center', fontSize: 11, fontWeight: 600, flexShrink: 0 }}>
                                                {(u.first_name ?? 'U')[0].toUpperCase()}
                                            </div>
                                            <span style={{ fontWeight: 600, color: '#0F172A' }}>{u.first_name} {u.last_name}</span>
                                        </div>
                                    </td>
                                    <td style={{ padding: '10px 14px', color: '#475569' }}>{u.email}</td>
                                    <td style={{ padding: '10px 14px', color: '#475569' }}>{u.phone ?? '—'}</td>
                                    <td style={{ padding: '10px 14px', color: '#475569' }}>{u.city ?? '—'}</td>
                                    <td style={{ padding: '10px 14px', color: '#94A3B8', whiteSpace: 'nowrap' }}>
                                        {new Date(u.created_at).toLocaleDateString('ar', { month: 'short', day: 'numeric', year: 'numeric' })}
                                    </td>
                                </tr>
                            ))}
                        </tbody>
                    </table>
                </div>
            )}
        </AdminLayout>
    );
}
