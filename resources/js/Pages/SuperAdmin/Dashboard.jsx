import { Head, Link } from '@inertiajs/react';
import SuperAdminLayout from '../../Layouts/SuperAdminLayout';

const AV_COLORS = ['#7C3AED','#0D9488','#3B82F6','#F59E0B','#EF4444','#EC4899','#0F766E'];

const STATS = (p) => [
    {
        icon: 'ti-users',
        label: 'إجمالي المستخدمين',
        value: p.totalUsers ?? 0,
        sub: `+${p.newUsersThisWeek ?? 0} هذا الأسبوع`,
        color: '#0D9488',
        bg: 'linear-gradient(135deg,#0D9488,#0F766E)',
        light: '#F0FDFA', border: '#9FE1CB',
    },
    {
        icon: 'ti-briefcase',
        label: 'حسابات الأعمال',
        value: p.totalBiz ?? 0,
        sub: `${p.activeWorkers ?? 0} نشط · ${p.pendingWorkers ?? 0} قيد المراجعة`,
        color: '#3B82F6',
        bg: 'linear-gradient(135deg,#3B82F6,#2563EB)',
        light: '#EFF6FF', border: '#BFDBFE',
    },
    {
        icon: 'ti-file-text',
        label: 'المنشورات',
        value: p.totalPosts ?? 0,
        sub: `${p.postsThisMonth ?? 0} هذا الشهر`,
        color: '#8B5CF6',
        bg: 'linear-gradient(135deg,#8B5CF6,#7C3AED)',
        light: '#F5F3FF', border: '#DDD6FE',
    },
    {
        icon: 'ti-flag',
        label: 'البلاغات',
        value: p.pendingReports ?? 0,
        sub: 'بانتظار المراجعة',
        color: '#EF4444',
        bg: 'linear-gradient(135deg,#EF4444,#DC2626)',
        light: '#FEF2F2', border: '#FECACA',
    },
    {
        icon: 'ti-speakerphone',
        label: 'الإعلانات النشطة',
        value: p.activeAds ?? 0,
        sub: 'إعلان مفعّل',
        color: '#F59E0B',
        bg: 'linear-gradient(135deg,#F59E0B,#D97706)',
        light: '#FFFBEB', border: '#FDE68A',
    },
    {
        icon: 'ti-key',
        label: 'الأدوار',
        value: p.totalRoles ?? 0,
        sub: `${p.totalPermissions ?? 0} صلاحية`,
        color: '#A78BFA',
        bg: 'linear-gradient(135deg,#A78BFA,#7C3AED)',
        light: '#F5F3FF', border: '#C4B5FD',
    },
    {
        icon: 'ti-user-shield',
        label: 'المشرفون',
        value: (p.admins ?? []).length,
        sub: 'مشرف نشط',
        color: '#0F766E',
        bg: 'linear-gradient(135deg,#0F766E,#134E4A)',
        light: '#F0FDFA', border: '#9FE1CB',
    },
];

function StatCard({ icon, label, value, sub, color, bg, light, border }) {
    return (
        <div style={{
            background: '#fff',
            border: `0.5px solid ${border}`,
            borderRadius: 16, padding: '20px 18px',
            display: 'flex', alignItems: 'flex-start', gap: 14,
            position: 'relative', overflow: 'hidden',
        }}>
            <div style={{ position: 'absolute', top: 0, left: 0, right: 0, height: 3, background: bg, opacity: 0.7 }} />
            <div style={{
                width: 48, height: 48, borderRadius: 13, flexShrink: 0,
                background: bg,
                display: 'flex', alignItems: 'center', justifyContent: 'center',
                fontSize: 22, color: '#fff',
                boxShadow: `0 4px 14px ${color}33`,
            }}>
                <i className={`ti ${icon}`} />
            </div>
            <div style={{ flex: 1, minWidth: 0 }}>
                <div style={{ fontSize: 28, fontWeight: 800, color: '#0F172A', lineHeight: 1, letterSpacing: -0.5 }}>
                    {Number(value).toLocaleString()}
                </div>
                <div style={{ fontSize: 12, color: '#64748B', marginTop: 4, fontWeight: 500 }}>{label}</div>
                {sub && (
                    <div style={{
                        fontSize: 10.5, color, marginTop: 6, background: light,
                        display: 'inline-block', padding: '2px 8px', borderRadius: 20,
                        border: `0.5px solid ${border}`, fontWeight: 700,
                    }}>
                        {sub}
                    </div>
                )}
            </div>
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
    const { admins = [], recentUsers = [] } = props;
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
            <div style={{ display: 'grid', gridTemplateColumns: 'repeat(auto-fill,minmax(195px,1fr))', gap: 14 }}>
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
            </div>
        </SuperAdminLayout>
    );
}
