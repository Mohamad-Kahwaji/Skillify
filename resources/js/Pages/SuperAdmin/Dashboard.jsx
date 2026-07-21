import { Head, Link } from '@inertiajs/react';
import SuperAdminLayout from '../../Layouts/SuperAdminLayout';

const AV_COLORS = ['#7C3AED','#0D9488','#3B82F6','#F59E0B','#EF4444','#EC4899','#0F766E'];

const STATS = (p) => [
    {
        icon: 'ti-users',
        label: 'إجمالي المستخدمين',
        value: p.totalUsers ?? 0,
        sub: `+${p.newUsersThisWeek ?? 0} هذا الأسبوع`,
        color: '#0D9488', tint: '#F0FDFA',
    },
    {
        icon: 'ti-briefcase',
        label: 'حسابات الأعمال',
        value: p.totalBiz ?? 0,
        sub: `${p.pendingWorkers ?? 0} قيد المراجعة`,
        color: '#3B82F6', tint: '#EFF6FF',
    },
    {
        icon: 'ti-file-text',
        label: 'المنشورات',
        value: p.totalPosts ?? 0,
        sub: `${p.postsThisMonth ?? 0} هذا الشهر`,
        color: '#8B5CF6', tint: '#F5F3FF',
    },
    {
        icon: 'ti-flag',
        label: 'البلاغات',
        value: p.pendingReports ?? 0,
        sub: p.pendingReports > 0 ? 'بانتظار المراجعة' : 'لا يوجد بلاغات',
        color: '#EF4444', tint: '#FEF2F2',
    },
    {
        icon: 'ti-speakerphone',
        label: 'الإعلانات النشطة',
        value: p.activeAds ?? 0,
        sub: 'إعلان مفعّل',
        color: '#F59E0B', tint: '#FFFBEB',
    },
    {
        icon: 'ti-key',
        label: 'الأدوار والصلاحيات',
        value: p.totalRoles ?? 0,
        sub: `${p.totalPermissions ?? 0} صلاحية`,
        color: '#6366F1', tint: '#EEF2FF',
    },
    {
        icon: 'ti-user-shield',
        label: 'المشرفون',
        value: (p.admins ?? []).length,
        sub: 'مشرف نشط',
        color: '#7C3AED', tint: '#F5F3FF',
    },
];

function StatCard({ icon, label, value, sub, color, tint }) {
    return (
        <div
            style={{
                background: '#fff', border: '0.5px solid rgba(15,23,42,0.07)',
                borderRadius: 16, padding: '18px 20px',
                transition: 'transform .15s ease, box-shadow .15s ease',
            }}
            onMouseEnter={e => { e.currentTarget.style.transform = 'translateY(-2px)'; e.currentTarget.style.boxShadow = '0 14px 28px rgba(15,23,42,0.10)'; }}
            onMouseLeave={e => { e.currentTarget.style.transform = 'none'; e.currentTarget.style.boxShadow = 'none'; }}
        >
            <div style={{ display: 'flex', alignItems: 'center', justifyContent: 'space-between', marginBottom: 14 }}>
                <div style={{ width: 42, height: 42, borderRadius: 12, background: tint, display: 'flex', alignItems: 'center', justifyContent: 'center', fontSize: 19, color }}>
                    <i className={`ti ${icon}`} />
                </div>
                {sub && (
                    <span style={{ fontSize: 10.5, fontWeight: 700, color, background: tint, padding: '3px 9px', borderRadius: 20 }}>
                        {sub}
                    </span>
                )}
            </div>
            <div style={{ fontSize: 26, fontWeight: 800, color: '#0F172A', lineHeight: 1, letterSpacing: -0.3 }}>
                {Number(value).toLocaleString()}
            </div>
            <div style={{ fontSize: 12, color: '#64748B', marginTop: 5, fontWeight: 500 }}>{label}</div>
        </div>
    );
}

function SectionHeader({ title, link }) {
    return (
        <div style={{ display: 'flex', alignItems: 'center', justifyContent: 'space-between' }}>
            <div style={{ display: 'flex', alignItems: 'center', gap: 9 }}>
                <div style={{ width: 3, height: 16, borderRadius: 2, background: 'linear-gradient(180deg,#7C3AED,#A78BFA)' }} />
                <span style={{ fontSize: 14, fontWeight: 700, color: '#1E1B4B' }}>{title}</span>
            </div>
            {link && (
                <Link href={link} style={{ fontSize: 11, fontWeight: 600, color: '#A78BFA', textDecoration: 'none', display: 'flex', alignItems: 'center', gap: 3 }}>
                    عرض الكل <i className="ti ti-chevron-left" style={{ fontSize: 12 }} />
                </Link>
            )}
        </div>
    );
}

export default function SuperAdminDashboard(props) {
    const { admins = [], recentUsers = [], pendingVerifications = [], recentReports = [] } = props;
    const stats = STATS(props);

    return (
        <SuperAdminLayout title="لوحة التحكم">
            <Head title="لوحة التحكم — Skillify" />

            {/* Heading */}
            <div>
                <h1 style={{ fontSize: 22, fontWeight: 800, color: '#1E1B4B', margin: 0, letterSpacing: -0.5 }}>
                    نظرة عامة على المنصة
                </h1>
                <p style={{ fontSize: 12, color: '#94A3B8', marginTop: 4 }}>
                    إحصاءات حية لجميع أقسام Skillify
                </p>
            </div>

            {/* Stats */}
            <div className="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4" style={{ gap: 14 }}>
                {stats.map((s, i) => <StatCard key={i} {...s} />)}
            </div>

            {/* Bottom panels */}
            <div className="grid grid-cols-1 lg:grid-cols-2" style={{ display: 'grid', gap: 18 }}>

                {/* Admins */}
                <div style={{ background: '#fff', border: '0.5px solid rgba(0,0,0,0.07)', borderRadius: 16, overflow: 'hidden' }}>
                    <div style={{ padding: '16px 20px', borderBottom: '0.5px solid rgba(0,0,0,0.06)' }}>
                        <SectionHeader title="المشرفون" link="/super-admin/admins" />
                    </div>
                    {!(admins ?? []).length ? (
                        <div style={{ padding: '48px 20px', textAlign: 'center', color: '#94A3B8' }}>
                            <i className="ti ti-user-shield" style={{ fontSize: 40, display: 'block', opacity: 0.15, marginBottom: 10 }} />
                            لا يوجد مشرفون بعد
                        </div>
                    ) : (admins ?? []).slice(0, 5).map((a, i) => (
                        <div key={a.id} style={{
                            display: 'flex', alignItems: 'center', gap: 12,
                            padding: '12px 20px',
                            borderBottom: i < Math.min((admins ?? []).length, 5) - 1 ? '0.5px solid rgba(0,0,0,0.05)' : 'none',
                        }}>
                            <div style={{
                                width: 36, height: 36, borderRadius: '50%', flexShrink: 0,
                                background: `linear-gradient(135deg,${AV_COLORS[i % AV_COLORS.length]},${AV_COLORS[(i + 2) % AV_COLORS.length]})`,
                                display: 'flex', alignItems: 'center', justifyContent: 'center',
                                fontSize: 13, fontWeight: 700, color: '#fff',
                            }}>
                                {(a.first_name ?? 'A')[0].toUpperCase()}
                            </div>
                            <div style={{ flex: 1, minWidth: 0 }}>
                                <div style={{ fontSize: 13, fontWeight: 600, color: '#0F172A' }}>{a.first_name} {a.last_name}</div>
                                <div style={{ fontSize: 11, color: '#94A3B8', overflow: 'hidden', textOverflow: 'ellipsis', whiteSpace: 'nowrap' }}>{a.email}</div>
                            </div>
                            <span style={{
                                fontSize: 10, fontWeight: 700, padding: '3px 9px', borderRadius: 20,
                                background: a.status === 'inactive' ? '#F3F4F6' : '#EDE9FE',
                                color: a.status === 'inactive' ? '#6B7280' : '#6D28D9',
                            }}>
                                {a.role ?? 'مشرف'}
                            </span>
                        </div>
                    ))}
                </div>

                {/* Recent Users */}
                <div style={{ background: '#fff', border: '0.5px solid rgba(0,0,0,0.07)', borderRadius: 16, overflow: 'hidden' }}>
                    <div style={{ padding: '16px 20px', borderBottom: '0.5px solid rgba(0,0,0,0.06)' }}>
                        <SectionHeader title="المستخدمون الجدد" link="/super-admin/users" />
                    </div>
                    {!(recentUsers ?? []).length ? (
                        <div style={{ padding: '48px 20px', textAlign: 'center', color: '#94A3B8' }}>
                            <i className="ti ti-users" style={{ fontSize: 40, display: 'block', opacity: 0.15, marginBottom: 10 }} />
                            لا يوجد مستخدمون بعد
                        </div>
                    ) : (recentUsers ?? []).map((u, i) => (
                        <div key={u.id} style={{
                            display: 'flex', alignItems: 'center', gap: 12,
                            padding: '12px 20px',
                            borderBottom: i < (recentUsers ?? []).length - 1 ? '0.5px solid rgba(0,0,0,0.05)' : 'none',
                        }}>
                            <div style={{
                                width: 36, height: 36, borderRadius: '50%', flexShrink: 0,
                                background: `linear-gradient(135deg,${AV_COLORS[i % AV_COLORS.length]},${AV_COLORS[(i + 3) % AV_COLORS.length]})`,
                                display: 'flex', alignItems: 'center', justifyContent: 'center',
                                fontSize: 13, fontWeight: 700, color: '#fff',
                            }}>
                                {(u.first_name ?? 'U')[0].toUpperCase()}
                            </div>
                            <div style={{ flex: 1, minWidth: 0 }}>
                                <div style={{ fontSize: 13, fontWeight: 600, color: '#0F172A' }}>{u.first_name} {u.last_name}</div>
                                <div style={{ fontSize: 11, color: '#94A3B8', overflow: 'hidden', textOverflow: 'ellipsis', whiteSpace: 'nowrap' }}>{u.email}</div>
                            </div>
                            <div style={{ width: 8, height: 8, borderRadius: '50%', background: '#10B981', boxShadow: '0 0 6px rgba(16,185,129,0.5)', flexShrink: 0 }} />
                        </div>
                    ))}
                </div>

                {/* Pending Verifications */}
                <div style={{ background: '#fff', border: '0.5px solid rgba(0,0,0,0.07)', borderRadius: 16, overflow: 'hidden' }}>
                    <div style={{ padding: '16px 20px', borderBottom: '0.5px solid rgba(0,0,0,0.06)' }}>
                        <SectionHeader title="طلبات قيد المراجعة" link="/super-admin/businesses" />
                    </div>
                    {!(pendingVerifications ?? []).length ? (
                        <div style={{ padding: '48px 20px', textAlign: 'center', color: '#94A3B8' }}>
                            <i className="ti ti-shield-check" style={{ fontSize: 40, display: 'block', opacity: 0.15, marginBottom: 10 }} />
                            لا توجد طلبات معلقة
                        </div>
                    ) : (pendingVerifications ?? []).map((b, i) => (
                        <div key={b.id} style={{
                            display: 'flex', alignItems: 'center', gap: 12,
                            padding: '12px 20px',
                            borderBottom: i < (pendingVerifications ?? []).length - 1 ? '0.5px solid rgba(0,0,0,0.05)' : 'none',
                        }}>
                            <div style={{
                                width: 36, height: 36, borderRadius: 10, flexShrink: 0,
                                background: '#FFFBEB', display: 'flex', alignItems: 'center', justifyContent: 'center',
                                fontSize: 15, color: '#F59E0B',
                            }}>
                                <i className="ti ti-briefcase" />
                            </div>
                            <div style={{ flex: 1, minWidth: 0 }}>
                                <div style={{ fontSize: 13, fontWeight: 600, color: '#0F172A', overflow: 'hidden', textOverflow: 'ellipsis', whiteSpace: 'nowrap' }}>{b.name}</div>
                                <div style={{ fontSize: 11, color: '#94A3B8' }}>{b.user?.first_name} {b.user?.last_name}</div>
                            </div>
                            <Link href="/super-admin/businesses" style={{ fontSize: 11, color: '#7C3AED', textDecoration: 'none', fontWeight: 600, background: '#F5F3FF', padding: '3px 9px', borderRadius: 6, flexShrink: 0 }}>
                                مراجعة
                            </Link>
                        </div>
                    ))}
                </div>

                {/* Recent Reports */}
                <div style={{ background: '#fff', border: '0.5px solid rgba(0,0,0,0.07)', borderRadius: 16, overflow: 'hidden' }}>
                    <div style={{ padding: '16px 20px', borderBottom: '0.5px solid rgba(0,0,0,0.06)' }}>
                        <SectionHeader title="أحدث البلاغات" link="/super-admin/reports" />
                    </div>
                    {!(recentReports ?? []).length ? (
                        <div style={{ padding: '48px 20px', textAlign: 'center', color: '#94A3B8' }}>
                            <i className="ti ti-flag" style={{ fontSize: 40, display: 'block', opacity: 0.15, marginBottom: 10 }} />
                            لا توجد بلاغات
                        </div>
                    ) : (recentReports ?? []).map((r, i) => (
                        <div key={r.id} style={{
                            display: 'flex', alignItems: 'center', gap: 12,
                            padding: '12px 20px',
                            borderBottom: i < (recentReports ?? []).length - 1 ? '0.5px solid rgba(0,0,0,0.05)' : 'none',
                        }}>
                            <div style={{
                                width: 36, height: 36, borderRadius: '50%', flexShrink: 0,
                                background: '#FEF2F2', display: 'flex', alignItems: 'center', justifyContent: 'center',
                                fontSize: 14, color: '#EF4444',
                            }}>
                                <i className="ti ti-flag" />
                            </div>
                            <div style={{ flex: 1, minWidth: 0 }}>
                                <div style={{ fontSize: 13, fontWeight: 600, color: '#0F172A', overflow: 'hidden', textOverflow: 'ellipsis', whiteSpace: 'nowrap' }}>{r.reason ?? r.body ?? 'بلاغ'}</div>
                                <div style={{ fontSize: 11, color: '#94A3B8' }}>من: {r.user?.first_name} {r.user?.last_name}</div>
                            </div>
                        </div>
                    ))}
                </div>
            </div>
        </SuperAdminLayout>
    );
}
