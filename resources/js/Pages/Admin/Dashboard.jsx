import { Head, Link } from '@inertiajs/react';
import AdminLayout from '../../Layouts/AdminLayout';

const AV_COLORS = ['#0D9488','#3B82F6','#8B5CF6','#F59E0B','#EF4444','#EC4899'];

function Stat({ icon, label, value, sub, color }) {
    return (
        <div style={{ background: '#fff', border: '0.5px solid rgba(0,0,0,0.07)', borderRadius: 14, padding: '18px 20px', display: 'flex', alignItems: 'center', gap: 14 }}>
            <div style={{ width: 46, height: 46, borderRadius: 12, background: color + '18', display: 'flex', alignItems: 'center', justifyContent: 'center', fontSize: 20, color, flexShrink: 0 }}>
                <i className={`ti ${icon}`} />
            </div>
            <div>
                <div style={{ fontSize: 22, fontWeight: 800, color: '#0F172A', lineHeight: 1.1 }}>{value}</div>
                <div style={{ fontSize: 12, color: '#475569', marginTop: 2 }}>{label}</div>
                {sub && <div style={{ fontSize: 11, color, marginTop: 2 }}>{sub}</div>}
            </div>
        </div>
    );
}

export default function Dashboard({
    totalUsers, newUsersThisWeek, activeWorkers, pendingWorkers,
    postsThisMonth, totalPosts, pendingReports, activeAds,
    recentUsers, recentPosts, pendingVerifications, recentReports,
}) {
    return (
        <AdminLayout title="لوحة التحكم">
            <Head title="لوحة التحكم — Skillify" />

            <div style={{ fontSize: 20, fontWeight: 700, color: '#0F172A' }}>نظرة عامة</div>

            {/* Stats */}
            <div style={{ display: 'grid', gridTemplateColumns: 'repeat(auto-fill,minmax(200px,1fr))', gap: 14 }}>
                <Stat icon="ti-users"         label="إجمالي المستخدمين"   value={totalUsers}      sub={`+${newUsersThisWeek} هذا الأسبوع`} color="#0D9488" />
                <Stat icon="ti-briefcase"     label="الحرفيون النشطون"    value={activeWorkers}   sub={`${pendingWorkers} قيد المراجعة`}    color="#3B82F6" />
                <Stat icon="ti-file-text"     label="منشورات هذا الشهر"  value={postsThisMonth}  sub={`${totalPosts} إجمالاً`}             color="#8B5CF6" />
                <Stat icon="ti-flag"          label="بلاغات مفتوحة"      value={pendingReports}  color="#EF4444" />
                <Stat icon="ti-speakerphone"  label="إعلانات نشطة"       value={activeAds}       color="#F59E0B" />
            </div>

            {/* Quick Links */}
            <div style={{ display: 'flex', gap: 8, flexWrap: 'wrap' }}>
                {[
                    { href: '/admin/verifications', label: 'التحقق', icon: 'ti-shield-check', badge: pendingWorkers },
                    { href: '/admin/reports',        label: 'البلاغات',  icon: 'ti-flag',        badge: pendingReports },
                    { href: '/admin/users',          label: 'المستخدمون', icon: 'ti-users' },
                    { href: '/admin/services',       label: 'الخدمات',   icon: 'ti-tool' },
                ].map(({ href, label, icon, badge }) => (
                    <Link key={href} href={href} style={{ display: 'inline-flex', alignItems: 'center', gap: 6, padding: '7px 14px', background: '#fff', border: '0.5px solid rgba(0,0,0,0.1)', borderRadius: 8, fontSize: 12, fontWeight: 500, textDecoration: 'none', color: '#0F172A' }}>
                        <i className={`ti ${icon}`} />
                        {label}
                        {badge > 0 && <span style={{ background: '#EF4444', color: '#fff', borderRadius: 20, fontSize: 9, fontWeight: 700, padding: '1px 6px' }}>{badge}</span>}
                    </Link>
                ))}
            </div>

            <div style={{ display: 'grid', gridTemplateColumns: '1fr 1fr', gap: 16 }}>
                {/* Recent Users */}
                <div style={{ background: '#fff', border: '0.5px solid rgba(0,0,0,0.07)', borderRadius: 14, overflow: 'hidden' }}>
                    <div style={{ padding: '14px 18px', borderBottom: '0.5px solid rgba(0,0,0,0.07)', fontSize: 13, fontWeight: 700, display: 'flex', justifyContent: 'space-between', alignItems: 'center' }}>
                        أحدث المستخدمين
                        <Link href="/admin/users" style={{ fontSize: 11, color: '#0D9488', textDecoration: 'none' }}>عرض الكل</Link>
                    </div>
                    {(recentUsers ?? []).map((u, i) => (
                        <div key={u.id} style={{ display: 'flex', alignItems: 'center', gap: 10, padding: '10px 18px', borderBottom: i < recentUsers.length - 1 ? '0.5px solid rgba(0,0,0,0.05)' : 'none' }}>
                            <div style={{ width: 32, height: 32, borderRadius: '50%', background: AV_COLORS[i % 6], color: '#fff', display: 'flex', alignItems: 'center', justifyContent: 'center', fontSize: 12, fontWeight: 600, flexShrink: 0 }}>
                                {(u.first_name ?? 'U')[0].toUpperCase()}
                            </div>
                            <div style={{ flex: 1, minWidth: 0 }}>
                                <div style={{ fontSize: 12, fontWeight: 600, color: '#0F172A' }}>{u.first_name} {u.last_name}</div>
                                <div style={{ fontSize: 10, color: '#94A3B8', overflow: 'hidden', textOverflow: 'ellipsis', whiteSpace: 'nowrap' }}>{u.email}</div>
                            </div>
                            <span style={{ fontSize: 10, padding: '2px 7px', borderRadius: 20, background: u.status === 'active' ? '#F0FDF4' : '#F3F4F6', color: u.status === 'active' ? '#134E4A' : '#6B7280', fontWeight: 600 }}>
                                {u.status ?? 'active'}
                            </span>
                        </div>
                    ))}
                </div>

                {/* Pending Verifications */}
                <div style={{ background: '#fff', border: '0.5px solid rgba(0,0,0,0.07)', borderRadius: 14, overflow: 'hidden' }}>
                    <div style={{ padding: '14px 18px', borderBottom: '0.5px solid rgba(0,0,0,0.07)', fontSize: 13, fontWeight: 700, display: 'flex', justifyContent: 'space-between', alignItems: 'center' }}>
                        طلبات التحقق المعلقة
                        <Link href="/admin/verifications" style={{ fontSize: 11, color: '#0D9488', textDecoration: 'none' }}>عرض الكل</Link>
                    </div>
                    {!(pendingVerifications ?? []).length ? (
                        <div style={{ padding: '24px', textAlign: 'center', color: '#94A3B8', fontSize: 12 }}>لا توجد طلبات معلقة!</div>
                    ) : (pendingVerifications ?? []).map((b, i) => (
                        <div key={b.id} style={{ display: 'flex', alignItems: 'center', gap: 10, padding: '10px 18px', borderBottom: i < pendingVerifications.length - 1 ? '0.5px solid rgba(0,0,0,0.05)' : 'none' }}>
                            <div style={{ width: 32, height: 32, borderRadius: 8, background: '#FEF3C7', display: 'flex', alignItems: 'center', justifyContent: 'center', fontSize: 14, color: '#F59E0B', flexShrink: 0 }}>
                                <i className="ti ti-briefcase" />
                            </div>
                            <div style={{ flex: 1, minWidth: 0 }}>
                                <div style={{ fontSize: 12, fontWeight: 600, color: '#0F172A' }}>{b.name}</div>
                                <div style={{ fontSize: 10, color: '#94A3B8' }}>{b.user?.first_name} {b.user?.last_name}</div>
                            </div>
                            <Link href={`/admin/verifications`} style={{ fontSize: 11, color: '#0D9488', textDecoration: 'none', fontWeight: 500 }}>مراجعة</Link>
                        </div>
                    ))}
                </div>

                {/* Recent Posts */}
                <div style={{ background: '#fff', border: '0.5px solid rgba(0,0,0,0.07)', borderRadius: 14, overflow: 'hidden' }}>
                    <div style={{ padding: '14px 18px', borderBottom: '0.5px solid rgba(0,0,0,0.07)', fontSize: 13, fontWeight: 700, display: 'flex', justifyContent: 'space-between', alignItems: 'center' }}>
                        أحدث المنشورات
                        <Link href="/admin/posts" style={{ fontSize: 11, color: '#0D9488', textDecoration: 'none' }}>عرض الكل</Link>
                    </div>
                    {(recentPosts ?? []).map((p, i) => (
                        <div key={p.id} style={{ display: 'flex', gap: 10, padding: '10px 18px', borderBottom: i < recentPosts.length - 1 ? '0.5px solid rgba(0,0,0,0.05)' : 'none' }}>
                            <div style={{ flex: 1, minWidth: 0 }}>
                                <div style={{ fontSize: 12, fontWeight: 600, color: '#0F172A', overflow: 'hidden', textOverflow: 'ellipsis', whiteSpace: 'nowrap' }}>{p.title}</div>
                                <div style={{ fontSize: 10, color: '#94A3B8' }}>{p.user?.first_name} {p.user?.last_name}</div>
                            </div>
                        </div>
                    ))}
                </div>

                {/* Recent Reports */}
                <div style={{ background: '#fff', border: '0.5px solid rgba(0,0,0,0.07)', borderRadius: 14, overflow: 'hidden' }}>
                    <div style={{ padding: '14px 18px', borderBottom: '0.5px solid rgba(0,0,0,0.07)', fontSize: 13, fontWeight: 700, display: 'flex', justifyContent: 'space-between', alignItems: 'center' }}>
                        أحدث البلاغات
                        <Link href="/admin/reports" style={{ fontSize: 11, color: '#EF4444', textDecoration: 'none' }}>عرض الكل</Link>
                    </div>
                    {!(recentReports ?? []).length ? (
                        <div style={{ padding: '24px', textAlign: 'center', color: '#94A3B8', fontSize: 12 }}>لا توجد بلاغات</div>
                    ) : (recentReports ?? []).map((r, i) => (
                        <div key={r.id} style={{ display: 'flex', gap: 10, padding: '10px 18px', borderBottom: i < recentReports.length - 1 ? '0.5px solid rgba(0,0,0,0.05)' : 'none', alignItems: 'flex-start' }}>
                            <i className="ti ti-flag" style={{ color: '#EF4444', fontSize: 14, marginTop: 1 }} />
                            <div style={{ flex: 1, minWidth: 0 }}>
                                <div style={{ fontSize: 12, fontWeight: 600, color: '#0F172A', overflow: 'hidden', textOverflow: 'ellipsis', whiteSpace: 'nowrap' }}>{r.reason ?? r.body ?? 'Report'}</div>
                                <div style={{ fontSize: 10, color: '#94A3B8' }}>من: {r.user?.first_name} {r.user?.last_name}</div>
                            </div>
                        </div>
                    ))}
                </div>
            </div>
        </AdminLayout>
    );
}
