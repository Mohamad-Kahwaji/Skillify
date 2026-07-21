import { Head, router } from '@inertiajs/react';
import AdminLayout, { C } from '../../Layouts/AdminLayout';

const TYPE_CONFIG = {
    'App\\Notifications\\NewRequestNotification': {
        icon: 'ti-briefcase', color: '#0D9488', bg: '#F0FDFA', border: '#99F6E4', label: 'طلب أعمال جديد',
    },
    'App\\Notifications\\NewVerificationRequestNotification': {
        icon: 'ti-id-badge-2', color: '#7C3AED', bg: '#F5F3FF', border: '#DDD6FE', label: 'طلب توثيق هوية',
    },
    'App\\Notifications\\NewServiceRequestNotification': {
        icon: 'ti-tool', color: '#0891B2', bg: '#E0F2FE', border: '#BAE6FD', label: 'طلب خدمة جديد',
    },
    'App\\Notifications\\BusinessStatusNotification': {
        icon: 'ti-circle-check', color: '#0891B2', bg: '#E0F2FE', border: '#BAE6FD', label: 'حالة أعمال',
    },
    'App\\Notifications\\SystemAnnouncementNotification': {
        icon: 'ti-speakerphone', color: '#7C3AED', bg: '#F5F3FF', border: '#DDD6FE', label: 'إعلان عام',
    },
    'App\\Notifications\\UserBlockedNotification': {
        icon: 'ti-user-off', color: '#F59E0B', bg: '#FFFBEB', border: '#FDE68A', label: 'حظر مستخدم',
    },
    'App\\Notifications\\AdminAlertNotification': {
        icon: 'ti-alert-triangle', color: '#EF4444', bg: '#FEF2F2', border: '#FECACA', label: 'تنبيه',
    },
    'App\\Notifications\\AdminBlockedNotification': {
        icon: 'ti-lock', color: '#DC2626', bg: '#FEF2F2', border: '#FECACA', label: 'تعليق الحساب',
    },
};

const DEFAULT_TYPE = { icon: 'ti-bell', color: '#64748B', bg: '#F8FAFC', border: '#E2E8F0', label: 'إشعار' };

// Maps a notification's data payload to the admin page it relates to, if any.
function resolveLink(type, data) {
    if (data.verification_id) return '/admin/identity-verifications';
    if (data.request_id)      return '/admin/workers';
    if (type === 'App\\Notifications\\NewServiceRequestNotification') return '/admin/services';
    return null;
}

function timeAgo(dateStr) {
    if (!dateStr) return '';
    const diff = Math.floor((Date.now() - new Date(dateStr)) / 1000);
    if (diff < 60)    return 'الآن';
    if (diff < 3600)  return `منذ ${Math.floor(diff / 60)} دقيقة`;
    if (diff < 86400) return `منذ ${Math.floor(diff / 3600)} ساعة`;
    return `منذ ${Math.floor(diff / 86400)} يوم`;
}

export default function Notifications({ notifications, unread_count }) {
    const items = notifications?.data ?? [];
    const total = notifications?.total ?? 0;

    const markRead    = (id) => router.patch(`/admin/notifications/${id}/read`,  {}, { preserveScroll: true });
    const markAllRead = ()   => router.patch('/admin/notifications/read-all',     {}, { preserveScroll: true });

    return (
        <AdminLayout title="الإشعارات">
            <Head title="الإشعارات — Skillify" />

            {/* Header */}
            <div style={{ display: 'flex', alignItems: 'center', justifyContent: 'space-between', flexWrap: 'wrap', gap: 12 }}>
                <div>
                    <h1 style={{ fontSize: 22, fontWeight: 800, color: C.textDark, margin: 0, letterSpacing: -0.5 }}>الإشعارات</h1>
                    <p style={{ fontSize: 12, color: C.textFaint, marginTop: 4 }}>
                        {total} إشعار
                        {unread_count > 0 && (
                            <span style={{ marginRight: 8, background: '#0EA5E9', color: '#fff', fontSize: 10, fontWeight: 700, padding: '1px 7px', borderRadius: 20 }}>
                                {unread_count} غير مقروء
                            </span>
                        )}
                    </p>
                </div>
                {unread_count > 0 && (
                    <button onClick={markAllRead} style={{
                        display: 'inline-flex', alignItems: 'center', gap: 6,
                        padding: '9px 18px', background: '#EFF6FF', color: '#1D4ED8',
                        border: '1px solid #BFDBFE', borderRadius: 10,
                        fontSize: 12.5, fontWeight: 600, cursor: 'pointer', fontFamily: 'inherit',
                    }}>
                        <i className="ti ti-checks" /> تعيين الكل كمقروء
                    </button>
                )}
            </div>

            {/* Stats row */}
            {total > 0 && (
                <div style={{ display: 'flex', gap: 12, flexWrap: 'wrap' }}>
                    {[
                        { label: 'إجمالي الإشعارات', value: total,         icon: 'ti-bell',    bg: '#EFF6FF', color: '#1D4ED8' },
                        { label: 'غير مقروء',         value: unread_count,  icon: 'ti-bell-ringing', bg: '#FFF7ED', color: '#C2410C' },
                        { label: 'مقروء',             value: total - unread_count, icon: 'ti-circle-check', bg: '#F0FDF4', color: '#15803D' },
                    ].map(s => (
                        <div key={s.label} style={{ display: 'flex', alignItems: 'center', gap: 10, padding: '12px 18px', background: '#fff', border: C.cardBorder, borderRadius: 12, boxShadow: C.cardShadow, flex: '1 1 140px' }}>
                            <div style={{ width: 36, height: 36, borderRadius: 10, background: s.bg, display: 'flex', alignItems: 'center', justifyContent: 'center', flexShrink: 0 }}>
                                <i className={`ti ${s.icon}`} style={{ fontSize: 16, color: s.color }} />
                            </div>
                            <div>
                                <div style={{ fontSize: 18, fontWeight: 800, color: C.textDark }}>{s.value}</div>
                                <div style={{ fontSize: 11, color: C.textFaint }}>{s.label}</div>
                            </div>
                        </div>
                    ))}
                </div>
            )}

            {/* List */}
            <div style={{ background: '#fff', border: C.cardBorder, borderRadius: 16, overflow: 'hidden', boxShadow: C.cardShadow }}>
                {!items.length ? (
                    <div style={{ padding: '80px 24px', textAlign: 'center' }}>
                        <div style={{ width: 72, height: 72, borderRadius: '50%', background: '#F1F5F9', display: 'flex', alignItems: 'center', justifyContent: 'center', margin: '0 auto 16px' }}>
                            <i className="ti ti-bell-off" style={{ fontSize: 32, color: '#CBD5E1' }} />
                        </div>
                        <div style={{ fontSize: 15, fontWeight: 700, color: C.textMuted, marginBottom: 6 }}>لا توجد إشعارات</div>
                        <p style={{ fontSize: 13, color: C.textFaint, margin: 0 }}>ستظهر هنا إشعارات النظام عند وجود طلبات جديدة.</p>
                    </div>
                ) : items.map((n, i) => {
                    const cfg    = TYPE_CONFIG[n.type] ?? DEFAULT_TYPE;
                    const data   = n.data ?? {};
                    const unread = !n.read_at;
                    const link   = resolveLink(n.type, data);

                    const openNotification = () => {
                        if (!link) return;
                        if (unread) markRead(n.id);
                        router.get(link);
                    };

                    return (
                        <div key={n.id} onClick={openNotification} style={{
                            display: 'flex', alignItems: 'flex-start', gap: 14,
                            padding: '16px 20px',
                            borderBottom: i < items.length - 1 ? `1px solid ${C.cardBorder.replace('1px solid ', '')}` : 'none',
                            background: unread ? '#F0F9FF' : '#fff',
                            cursor: link ? 'pointer' : 'default',
                            transition: 'background .15s',
                            borderRight: unread ? `3px solid ${C.primary}` : '3px solid transparent',
                        }}
                            onMouseEnter={e => { if (link) e.currentTarget.style.background = C.primaryMuted ?? '#EFF6FF'; }}
                            onMouseLeave={e => { e.currentTarget.style.background = unread ? '#F0F9FF' : '#fff'; }}
                        >

                            {/* Icon */}
                            <div style={{
                                width: 44, height: 44, borderRadius: 12, flexShrink: 0,
                                background: cfg.bg, border: `1px solid ${cfg.border}`,
                                display: 'flex', alignItems: 'center', justifyContent: 'center',
                                fontSize: 19, color: cfg.color,
                            }}>
                                <i className={`ti ${cfg.icon}`} />
                            </div>

                            {/* Content */}
                            <div style={{ flex: 1, minWidth: 0 }}>
                                <div style={{ display: 'flex', alignItems: 'center', gap: 8, marginBottom: 5, flexWrap: 'wrap' }}>
                                    <span style={{
                                        fontSize: 10, fontWeight: 700, padding: '2px 9px', borderRadius: 20,
                                        background: cfg.bg, color: cfg.color, border: `1px solid ${cfg.border}`,
                                    }}>
                                        {cfg.label}
                                    </span>
                                    {unread && (
                                        <span style={{ width: 7, height: 7, borderRadius: '50%', background: C.primary, display: 'inline-block', boxShadow: `0 0 6px ${C.primary}66` }} />
                                    )}
                                </div>

                                {data.title && (
                                    <div style={{ fontSize: 13.5, fontWeight: 700, color: C.textDark, marginBottom: 3 }}>
                                        {data.title}
                                    </div>
                                )}
                                {(data.message || data.reason) && (
                                    <div style={{ fontSize: 12.5, color: C.textMuted, lineHeight: 1.6 }}>
                                        {data.message || data.reason}
                                    </div>
                                )}
                                <div style={{ fontSize: 11, color: C.textFaint, marginTop: 6, display: 'flex', alignItems: 'center', gap: 4 }}>
                                    <i className="ti ti-clock" style={{ fontSize: 11 }} />{timeAgo(n.created_at)}
                                </div>
                            </div>

                            {/* Action */}
                            {unread && (
                                <button onClick={e => { e.stopPropagation(); markRead(n.id); }} title="تعيين كمقروء" style={{
                                    width: 32, height: 32, borderRadius: 8, flexShrink: 0,
                                    border: `1px solid ${C.cardBorder.replace('1px solid ', '')}`,
                                    background: '#F0F9FF', color: C.primary,
                                    fontSize: 14, cursor: 'pointer', display: 'flex', alignItems: 'center', justifyContent: 'center',
                                    transition: 'all .15s',
                                }}
                                    onMouseEnter={e => e.currentTarget.style.background = C.primaryMuted}
                                    onMouseLeave={e => e.currentTarget.style.background = '#F0F9FF'}
                                >
                                    <i className="ti ti-check" />
                                </button>
                            )}
                            {link && (
                                <i className="ti ti-chevron-left" style={{ color: '#CBD5E1', fontSize: 16, flexShrink: 0, alignSelf: 'center' }} />
                            )}
                        </div>
                    );
                })}
            </div>

            {/* Pagination */}
            {(notifications?.last_page ?? 1) > 1 && (
                <div style={{ display: 'flex', justifyContent: 'center', gap: 6, flexWrap: 'wrap' }}>
                    {notifications.links?.filter(l => l.url).map((link, i) => (
                        <button key={i} onClick={() => router.get(link.url, {}, { preserveScroll: true })} style={{
                            padding: '6px 12px', borderRadius: 8, cursor: 'pointer',
                            border: `1px solid ${link.active ? C.primary : 'rgba(0,0,0,0.1)'}`,
                            background: link.active ? C.primary : '#fff',
                            color: link.active ? '#fff' : C.textMuted,
                            fontSize: 12, fontWeight: link.active ? 700 : 400,
                        }} dangerouslySetInnerHTML={{ __html: link.label }} />
                    ))}
                </div>
            )}
        </AdminLayout>
    );
}
