import { Head, Link } from '@inertiajs/react';
import AdminLayout, { C } from '../../Layouts/AdminLayout';

const AV = ['#0EA5E9','#8B5CF6','#F59E0B','#10B981','#EF4444','#EC4899'];

function Stat({ icon, label, value, sub, color, tint }) {
    return (
        <div
            style={{
                background: '#fff', borderRadius: 16,
                boxShadow: C.cardShadow, border: C.cardBorder,
                padding: '18px 20px',
                transition: 'transform .15s ease, box-shadow .15s ease',
            }}
            onMouseEnter={e => { e.currentTarget.style.transform = 'translateY(-2px)'; e.currentTarget.style.boxShadow = '0 14px 28px rgba(15,23,42,0.10)'; }}
            onMouseLeave={e => { e.currentTarget.style.transform = 'none'; e.currentTarget.style.boxShadow = C.cardShadow; }}
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
            <div style={{ fontSize: 26, fontWeight: 800, color: C.textDark, lineHeight: 1 }}>{Number(value ?? 0).toLocaleString()}</div>
            <div style={{ fontSize: 12, color: C.textMuted, marginTop: 5, fontWeight: 500 }}>{label}</div>
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

            <div style={{ display: 'flex', alignItems: 'center', justifyContent: 'space-between' }}>
                <div>
                    <div style={{ fontSize: 22, fontWeight: 800, color: C.textDark }}>نظرة عامة</div>
                    <div style={{ fontSize: 12, color: C.textMuted, marginTop: 2 }}>مرحباً بك في لوحة إدارة Skillify</div>
                </div>
            </div>

            {/* Stats */}
            <div style={{ display: 'grid', gridTemplateColumns: 'repeat(auto-fill,minmax(200px,1fr))', gap: 14 }}>
                <Stat icon="ti-users"        label="إجمالي المستخدمين"  value={totalUsers}     sub={`+${newUsersThisWeek ?? 0} هذا الأسبوع`}                         color="#0EA5E9" tint="#EFF6FF" />
                <Stat icon="ti-briefcase"    label="الحرفيون النشطون"   value={activeWorkers}  sub={`${pendingWorkers ?? 0} قيد المراجعة`}                           color="#0D9488" tint="#F0FDFA" />
                <Stat icon="ti-file-text"    label="منشورات هذا الشهر" value={postsThisMonth} sub={`${totalPosts ?? 0} إجمالاً`}                                     color="#8B5CF6" tint="#F5F3FF" />
                <Stat icon="ti-flag"         label="بلاغات مفتوحة"     value={pendingReports} sub={pendingReports > 0 ? 'بحاجة لإجراء' : 'لا يوجد بلاغات'}          color="#EF4444" tint="#FEF2F2" />
                <Stat icon="ti-speakerphone" label="إعلانات نشطة"      value={activeAds}      sub="إعلان معتمد"                                                      color="#F59E0B" tint="#FFFBEB" />
            </div>

            {/* Quick Links */}
            <div style={{ display: 'flex', gap: 8, flexWrap: 'wrap' }}>
                {[
                    { href: '/admin/workers', label: 'التحقق',      icon: 'ti-shield-check', badge: pendingWorkers,  color: '#8B5CF6' },
                    { href: '/admin/reports',        label: 'البلاغات',   icon: 'ti-flag',          badge: pendingReports, color: '#EF4444' },
                    { href: '/admin/users',          label: 'المستخدمون', icon: 'ti-users',          color: '#0EA5E9' },
                    { href: '/admin/services',       label: 'الخدمات',    icon: 'ti-tool',           color: '#10B981' },
                ].map(({ href, label, icon, badge, color }) => (
                    <Link key={href} href={href} style={{
                        display: 'inline-flex', alignItems: 'center', gap: 7,
                        padding: '8px 16px', background: '#fff',
                        border: C.cardBorder, borderRadius: 10,
                        fontSize: 12, fontWeight: 500, textDecoration: 'none', color: C.textDark,
                        boxShadow: '0 1px 3px rgba(15,23,42,0.06)',
                        transition: 'transform .1s, box-shadow .1s',
                    }}>
                        <i className={`ti ${icon}`} style={{ color, fontSize: 14 }} />
                        {label}
                        {badge > 0 && <span style={{ background: color, color: '#fff', borderRadius: 20, fontSize: 9, fontWeight: 700, padding: '2px 7px' }}>{badge}</span>}
                    </Link>
                ))}
            </div>

            {/* Panels */}
            <div className="grid-cols-1 md:grid-cols-2" style={{ display: 'grid', gap: 18 }}>

                {/* Recent Users */}
                <Panel title="أحدث المستخدمين" href="/admin/users" linkColor={C.primary}>
                    {(recentUsers ?? []).map((u, i) => (
                        <Row key={u.id} last={i === (recentUsers.length - 1)}>
                            <Avatar letter={(u.first_name ?? 'U')[0]} color={AV[i % 6]} />
                            <div style={{ flex: 1, minWidth: 0 }}>
                                <div style={{ fontSize: 13, fontWeight: 600, color: C.textDark }}>{u.first_name} {u.last_name}</div>
                                <div style={{ fontSize: 11, color: C.textFaint, overflow: 'hidden', textOverflow: 'ellipsis', whiteSpace: 'nowrap' }}>{u.email}</div>
                            </div>
                            <StatusBadge value={u.status ?? 'active'} />
                        </Row>
                    ))}
                </Panel>

                {/* Pending Verifications */}
                <Panel title="طلبات التحقق المعلقة" href="/admin/workers" linkColor={C.primary}>
                    {!(pendingVerifications ?? []).length
                        ? <Empty icon="ti-shield-check" text="لا توجد طلبات معلقة" />
                        : (pendingVerifications ?? []).map((b, i) => (
                            <Row key={b.id} last={i === (pendingVerifications.length - 1)}>
                                <div style={{ width: 34, height: 34, borderRadius: 9, background: '#FFFBEB', display: 'flex', alignItems: 'center', justifyContent: 'center', fontSize: 15, color: '#F59E0B', flexShrink: 0 }}>
                                    <i className="ti ti-briefcase" />
                                </div>
                                <div style={{ flex: 1, minWidth: 0 }}>
                                    <div style={{ fontSize: 13, fontWeight: 600, color: C.textDark }}>{b.name}</div>
                                    <div style={{ fontSize: 11, color: C.textFaint }}>{b.user?.first_name} {b.user?.last_name}</div>
                                </div>
                                <Link href="/admin/workers" style={{ fontSize: 11, color: C.primary, textDecoration: 'none', fontWeight: 600, background: C.infoBg, padding: '3px 9px', borderRadius: 6 }}>مراجعة</Link>
                            </Row>
                        ))}
                </Panel>

                {/* Recent Posts */}
                <Panel title="أحدث المنشورات" href="/admin/posts" linkColor={C.primary}>
                    {(recentPosts ?? []).map((p, i) => (
                        <Row key={p.id} last={i === (recentPosts.length - 1)}>
                            <div style={{ width: 34, height: 34, borderRadius: 9, background: '#F5F3FF', display: 'flex', alignItems: 'center', justifyContent: 'center', fontSize: 14, color: '#8B5CF6', flexShrink: 0 }}>
                                <i className="ti ti-file-text" />
                            </div>
                            <div style={{ flex: 1, minWidth: 0 }}>
                                <div style={{ fontSize: 13, fontWeight: 600, color: C.textDark, overflow: 'hidden', textOverflow: 'ellipsis', whiteSpace: 'nowrap' }}>{p.title}</div>
                                <div style={{ fontSize: 11, color: C.textFaint }}>{p.user?.first_name} {p.user?.last_name}</div>
                            </div>
                        </Row>
                    ))}
                </Panel>

                {/* Recent Reports */}
                <Panel title="أحدث البلاغات" href="/admin/reports" linkColor="#EF4444">
                    {!(recentReports ?? []).length
                        ? <Empty icon="ti-flag" text="لا توجد بلاغات" />
                        : (recentReports ?? []).map((r, i) => (
                            <Row key={r.id} last={i === (recentReports.length - 1)}>
                                <div style={{ width: 34, height: 34, borderRadius: '50%', background: '#FEF2F2', display: 'flex', alignItems: 'center', justifyContent: 'center', fontSize: 14, color: '#EF4444', flexShrink: 0 }}>
                                    <i className="ti ti-flag" />
                                </div>
                                <div style={{ flex: 1, minWidth: 0 }}>
                                    <div style={{ fontSize: 13, fontWeight: 600, color: C.textDark, overflow: 'hidden', textOverflow: 'ellipsis', whiteSpace: 'nowrap' }}>{r.reason ?? r.body ?? 'Report'}</div>
                                    <div style={{ fontSize: 11, color: C.textFaint }}>من: {r.user?.first_name} {r.user?.last_name}</div>
                                </div>
                            </Row>
                        ))}
                </Panel>
            </div>
        </AdminLayout>
    );
}

// ── Shared sub-components ─────────────────────────────────────────────────────
function Panel({ title, href, linkColor, children }) {
    return (
        <div style={{ background: C.cardBg, borderRadius: 16, boxShadow: C.cardShadow, border: C.cardBorder, overflow: 'hidden' }}>
            <div style={{ padding: '14px 20px', borderBottom: '1px solid rgba(15,23,42,0.06)', display: 'flex', justifyContent: 'space-between', alignItems: 'center' }}>
                <span style={{ fontSize: 13, fontWeight: 700, color: C.textDark }}>{title}</span>
                <Link href={href} style={{ fontSize: 11, color: linkColor, textDecoration: 'none', fontWeight: 600 }}>عرض الكل</Link>
            </div>
            {children}
        </div>
    );
}

function Row({ children, last }) {
    return (
        <div style={{ display: 'flex', alignItems: 'center', gap: 10, padding: '11px 20px', borderBottom: last ? 'none' : '1px solid rgba(15,23,42,0.05)' }}>
            {children}
        </div>
    );
}

function Avatar({ letter, color }) {
    return (
        <div style={{ width: 34, height: 34, borderRadius: '50%', background: color, color: '#fff', display: 'flex', alignItems: 'center', justifyContent: 'center', fontSize: 13, fontWeight: 700, flexShrink: 0 }}>
            {letter}
        </div>
    );
}

function StatusBadge({ value }) {
    const active = value === 'active';
    return (
        <span style={{ fontSize: 10, fontWeight: 600, padding: '3px 9px', borderRadius: 20, background: active ? C.successBg : C.dangerBg, color: active ? C.successText : C.dangerText }}>
            {value}
        </span>
    );
}

function Empty({ icon, text }) {
    return (
        <div style={{ padding: '36px', textAlign: 'center', color: C.textFaint }}>
            <i className={`ti ${icon}`} style={{ fontSize: 32, display: 'block', marginBottom: 8, opacity: 0.3 }} />
            <div style={{ fontSize: 12 }}>{text}</div>
        </div>
    );
}
