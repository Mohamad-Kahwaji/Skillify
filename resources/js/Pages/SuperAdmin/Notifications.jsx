import { Head, router } from '@inertiajs/react';
import SuperAdminLayout from '../../Layouts/SuperAdminLayout';

const TYPE_CONFIG = {
    'App\\Notifications\\SystemAnnouncementNotification': {
        icon: 'ti-speakerphone', color: '#7C3AED', bg: '#F5F3FF', border: '#DDD6FE', label: 'إعلان عام',
    },
    'App\\Notifications\\AdminBlockedNotification': {
        icon: 'ti-shield-off', color: '#EF4444', bg: '#FEF2F2', border: '#FECACA', label: 'حظر مشرف',
    },
    'App\\Notifications\\UserBlockedNotification': {
        icon: 'ti-user-off', color: '#F59E0B', bg: '#FFFBEB', border: '#FDE68A', label: 'حظر مستخدم',
    },
    'App\\Notifications\\NewRequestNotification': {
        icon: 'ti-briefcase', color: '#0D9488', bg: '#F0FDFA', border: '#99F6E4', label: 'طلب أعمال جديد',
    },
    'App\\Notifications\\NewVerificationRequestNotification': {
        icon: 'ti-id-badge-2', color: '#7C3AED', bg: '#F5F3FF', border: '#DDD6FE', label: 'طلب توثيق هوية',
    },
    'App\\Notifications\\NewServiceRequestNotification': {
        icon: 'ti-tool', color: '#0891B2', bg: '#E0F2FE', border: '#BAE6FD', label: 'طلب خدمة جديد',
    },
    'App\\Notifications\\AdminAlertNotification': {
        icon: 'ti-alert-triangle', color: '#EF4444', bg: '#FEF2F2', border: '#FECACA', label: 'تنبيه',
    },
};

const DEFAULT_TYPE = {
    icon: 'ti-bell', color: '#64748B', bg: '#F8FAFC', border: '#E2E8F0', label: 'إشعار',
};

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

    const markRead   = (id) => router.patch(`/super-admin/notifications/${id}/read`,  {}, { preserveScroll: true });
    const markAllRead = ()  => router.patch('/super-admin/notifications/read-all',     {}, { preserveScroll: true });

    return (
        <SuperAdminLayout title="الإشعارات">
            <Head title="الإشعارات — Skillify" />

            {/* Header */}
            <div style={{ display: 'flex', alignItems: 'center', justifyContent: 'space-between', flexWrap: 'wrap', gap: 12 }}>
                <div>
                    <h1 style={{ fontSize: 22, fontWeight: 800, color: '#1E1B4B', margin: 0, letterSpacing: -0.5 }}>
                        الإشعارات
                    </h1>
                    <p style={{ fontSize: 12, color: '#94A3B8', marginTop: 4 }}>
                        {total} إشعار
                        {unread_count > 0 && (
                            <span style={{ marginRight: 8, background: '#7C3AED', color: '#fff', fontSize: 10, fontWeight: 700, padding: '1px 7px', borderRadius: 20 }}>
                                {unread_count} غير مقروء
                            </span>
                        )}
                    </p>
                </div>
                {unread_count > 0 && (
                    <button onClick={markAllRead} style={{
                        display: 'inline-flex', alignItems: 'center', gap: 6,
                        padding: '8px 16px', background: '#EDE9FE', color: '#6D28D9',
                        border: '0.5px solid #DDD6FE', borderRadius: 9,
                        fontSize: 12, fontWeight: 600, cursor: 'pointer',
                    }}>
                        <i className="ti ti-checks" /> تعيين الكل كمقروء
                    </button>
                )}
            </div>

            {/* List */}
            <div style={{ background: '#fff', border: '0.5px solid rgba(0,0,0,0.07)', borderRadius: 16, overflow: 'hidden' }}>
                {!items.length ? (
                    <div style={{ padding: '72px 24px', textAlign: 'center', color: '#94A3B8' }}>
                        <i className="ti ti-bell-off" style={{ fontSize: 52, display: 'block', opacity: 0.12, marginBottom: 14 }} />
                        <div style={{ fontSize: 15, fontWeight: 600, color: '#64748B', marginBottom: 6 }}>لا توجد إشعارات</div>
                        <p style={{ fontSize: 13 }}>ستظهر هنا إشعارات النظام والأحداث المهمة.</p>
                    </div>
                ) : items.map((n, i) => {
                    const cfg  = TYPE_CONFIG[n.type] ?? DEFAULT_TYPE;
                    const data = n.data ?? {};
                    const isUnread = !n.read_at;

                    return (
                        <div key={n.id} style={{
                            display: 'flex', alignItems: 'flex-start', gap: 14,
                            padding: '16px 20px',
                            borderBottom: i < items.length - 1 ? '0.5px solid rgba(0,0,0,0.05)' : 'none',
                            background: isUnread ? '#FAFAFF' : '#fff',
                            transition: 'background 0.15s',
                        }}>
                            {/* Icon */}
                            <div style={{
                                width: 42, height: 42, borderRadius: 11, flexShrink: 0,
                                background: cfg.bg, border: `0.5px solid ${cfg.border}`,
                                display: 'flex', alignItems: 'center', justifyContent: 'center',
                                fontSize: 18, color: cfg.color,
                            }}>
                                <i className={`ti ${cfg.icon}`} />
                            </div>

                            {/* Content */}
                            <div style={{ flex: 1, minWidth: 0 }}>
                                <div style={{ display: 'flex', alignItems: 'center', gap: 8, flexWrap: 'wrap', marginBottom: 4 }}>
                                    <span style={{
                                        fontSize: 10, fontWeight: 700, padding: '2px 8px', borderRadius: 20,
                                        background: cfg.bg, color: cfg.color, border: `0.5px solid ${cfg.border}`,
                                    }}>
                                        {cfg.label}
                                    </span>
                                    {isUnread && (
                                        <span style={{ width: 7, height: 7, borderRadius: '50%', background: '#7C3AED', display: 'inline-block', boxShadow: '0 0 5px rgba(124,58,237,0.45)' }} />
                                    )}
                                </div>
                                {data.title && (
                                    <div style={{ fontSize: 14, fontWeight: 700, color: '#0F172A', marginBottom: 3 }}>
                                        {data.title}
                                    </div>
                                )}
                                {data.message && (
                                    <div style={{ fontSize: 12.5, color: '#475569', lineHeight: 1.6 }}>
                                        {data.message}
                                    </div>
                                )}
                                {data.reason && (
                                    <div style={{ fontSize: 12.5, color: '#475569', lineHeight: 1.6 }}>
                                        {data.reason}
                                    </div>
                                )}
                                <div style={{ fontSize: 11, color: '#94A3B8', marginTop: 6 }}>
                                    {timeAgo(n.created_at)}
                                </div>
                            </div>

                            {/* Mark read */}
                            {isUnread && (
                                <button onClick={() => markRead(n.id)} title="تعيين كمقروء" style={{
                                    padding: '6px', borderRadius: 8, border: '0.5px solid #DDD6FE',
                                    background: '#F5F3FF', color: '#7C3AED',
                                    fontSize: 14, cursor: 'pointer', flexShrink: 0, lineHeight: 1,
                                }}>
                                    <i className="ti ti-check" />
                                </button>
                            )}
                        </div>
                    );
                })}
            </div>

            {/* Pagination */}
            {(notifications?.last_page ?? 1) > 1 && (
                <div style={{ display: 'flex', justifyContent: 'center', gap: 6, flexWrap: 'wrap' }}>
                    {notifications.links?.filter(l => l.url).map((link, i) => (
                        <button key={i} onClick={() => router.get(link.url, {}, { preserveScroll: true })}
                            style={{
                                padding: '6px 12px', borderRadius: 8, border: '0.5px solid rgba(0,0,0,0.12)',
                                background: link.active ? '#7C3AED' : '#fff',
                                color: link.active ? '#fff' : '#475569',
                                fontSize: 12, fontWeight: link.active ? 700 : 400, cursor: 'pointer',
                            }}
                            dangerouslySetInnerHTML={{ __html: link.label }}
                        />
                    ))}
                </div>
            )}
        </SuperAdminLayout>
    );
}
