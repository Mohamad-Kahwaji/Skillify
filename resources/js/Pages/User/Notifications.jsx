import { Head, router } from '@inertiajs/react';
import { useState } from 'react';
import UserLayout from '../../Layouts/UserLayout';

const TYPE_MAP = {
    'PostLikedNotification':              { icon: 'ti-heart-filled',     color: '#EF4444', bg: '#FEF2F2', label: 'إعجاب'       },
    'PostCommentedNotification':          { icon: 'ti-message-circle',   color: '#3B82F6', bg: '#EFF6FF', label: 'تعليق'       },
    'NewRequestNotification':             { icon: 'ti-briefcase',        color: '#8B5CF6', bg: '#F5F3FF', label: 'طلب خدمة'    },
    'ServiceStatusNotification':          { icon: 'ti-check-circle',     color: '#0D9488', bg: '#F0FDFA', label: 'حالة خدمة'   },
    'BusinessStatusNotification':         { icon: 'ti-building-store',   color: '#F59E0B', bg: '#FFFBEB', label: 'حساب عمل'    },
    'UserBlockedNotification':            { icon: 'ti-lock',             color: '#DC2626', bg: '#FEF2F2', label: 'حظر'         },
    'SystemAnnouncementNotification':     { icon: 'ti-speakerphone',     color: '#0F172A', bg: '#F1F5F9', label: 'إعلان'       },
    'AdminAlertNotification':             { icon: 'ti-alert-triangle',   color: '#D97706', bg: '#FFFBEB', label: 'تنبيه'       },
    'IdentityVerificationNotification':   { icon: 'ti-id-badge',         color: '#7C3AED', bg: '#F5F3FF', label: 'توثيق الهوية' },
};

function typeInfo(fullType) {
    const short = fullType?.split('\\').pop();
    return TYPE_MAP[short] ?? { icon: 'ti-bell', color: '#64748B', bg: '#F8FAFC', label: 'إشعار' };
}

function timeAgo(dateStr) {
    const diff = Math.floor((Date.now() - new Date(dateStr)) / 1000);
    if (diff < 60)   return 'الآن';
    if (diff < 3600) return `${Math.floor(diff / 60)} د`;
    if (diff < 86400) return `${Math.floor(diff / 3600)} س`;
    return `${Math.floor(diff / 86400)} ي`;
}

function NotifCard({ n, onRead, onDelete }) {
    const info   = typeInfo(n.type);
    const isRead = !!n.read_at;
    const data   = n.data ?? {};

    return (
        <div style={{
            display: 'flex', alignItems: 'flex-start', gap: 14,
            background: isRead ? '#fff' : '#F0FDFA',
            border: `1px solid ${isRead ? 'rgba(0,0,0,0.07)' : 'rgba(13,148,136,0.18)'}`,
            borderRadius: 14, padding: '14px 18px',
            transition: 'background 0.2s',
            position: 'relative',
        }}>
            {/* Unread dot */}
            {!isRead && (
                <div style={{ position: 'absolute', top: 14, left: 14, width: 7, height: 7, borderRadius: '50%', background: '#0D9488' }} />
            )}

            {/* Icon */}
            <div style={{
                width: 42, height: 42, borderRadius: 12, background: info.bg,
                display: 'flex', alignItems: 'center', justifyContent: 'center', flexShrink: 0,
            }}>
                <i className={`ti ${info.icon}`} style={{ fontSize: 20, color: info.color }} />
            </div>

            {/* Content */}
            <div style={{ flex: 1, minWidth: 0 }}>
                <div style={{ display: 'flex', alignItems: 'center', gap: 8, marginBottom: 3 }}>
                    <span style={{ fontSize: 11, fontWeight: 600, color: info.color, background: info.bg, padding: '2px 8px', borderRadius: 6 }}>
                        {info.label}
                    </span>
                    <span style={{ fontSize: 11, color: '#94A3B8' }}>{timeAgo(n.created_at)}</span>
                </div>
                <div style={{ fontSize: 14, fontWeight: isRead ? 400 : 600, color: '#0F172A', lineHeight: 1.5 }}>
                    {data.title || data.message || 'إشعار جديد'}
                </div>
                {data.title && data.message && (
                    <div style={{ fontSize: 12, color: '#64748B', marginTop: 3 }}>{data.message}</div>
                )}
            </div>

            {/* Actions */}
            <div style={{ display: 'flex', alignItems: 'center', gap: 6, flexShrink: 0 }}>
                {!isRead && (
                    <button onClick={() => onRead(n.id)} title="تعيين كمقروء" style={{
                        width: 30, height: 30, borderRadius: 8, border: '1px solid rgba(13,148,136,0.3)',
                        background: '#F0FDFA', color: '#0D9488', cursor: 'pointer', display: 'flex', alignItems: 'center', justifyContent: 'center',
                    }}>
                        <i className="ti ti-check" style={{ fontSize: 14 }} />
                    </button>
                )}
                <button onClick={() => onDelete(n.id)} title="حذف" style={{
                    width: 30, height: 30, borderRadius: 8, border: '1px solid rgba(0,0,0,0.08)',
                    background: 'none', color: '#94A3B8', cursor: 'pointer', display: 'flex', alignItems: 'center', justifyContent: 'center',
                }}
                    onMouseEnter={e => { e.currentTarget.style.background = '#FEF2F2'; e.currentTarget.style.color = '#EF4444'; }}
                    onMouseLeave={e => { e.currentTarget.style.background = 'none'; e.currentTarget.style.color = '#94A3B8'; }}
                >
                    <i className="ti ti-trash" style={{ fontSize: 14 }} />
                </button>
            </div>
        </div>
    );
}

export default function Notifications({ notifications, unreadCount }) {
    const [items, setItems]       = useState(notifications?.data ?? []);
    const [unread, setUnread]     = useState(unreadCount ?? 0);
    const [filter, setFilter]     = useState('all');

    const csrf = () => document.querySelector('meta[name="csrf-token"]')?.content ?? '';

    const markRead = (id) => {
        fetch(`/user/notifications/${id}/read`, {
            method: 'PATCH',
            headers: { 'X-CSRF-TOKEN': csrf(), 'Accept': 'application/json' },
        }).then(() => {
            setItems(prev => prev.map(n => n.id === id ? { ...n, read_at: new Date().toISOString() } : n));
            setUnread(v => Math.max(0, v - 1));
        });
    };

    const markAllRead = () => {
        fetch('/user/notifications/read-all', {
            method: 'PATCH',
            headers: { 'X-CSRF-TOKEN': csrf(), 'Accept': 'application/json' },
        }).then(() => {
            setItems(prev => prev.map(n => ({ ...n, read_at: n.read_at || new Date().toISOString() })));
            setUnread(0);
        });
    };

    const deleteNotif = (id) => {
        fetch(`/user/notifications/${id}`, {
            method: 'DELETE',
            headers: { 'X-CSRF-TOKEN': csrf(), 'Accept': 'application/json' },
        }).then(() => {
            const n = items.find(x => x.id === id);
            setItems(prev => prev.filter(x => x.id !== id));
            if (n && !n.read_at) setUnread(v => Math.max(0, v - 1));
        });
    };

    const filtered = filter === 'unread' ? items.filter(n => !n.read_at) : items;

    return (
        <UserLayout title="الإشعارات">
            <Head title="الإشعارات — Skillify" />

            {/* Header */}
            <div style={{ display: 'flex', alignItems: 'center', justifyContent: 'space-between', flexWrap: 'wrap', gap: 12 }}>
                <div>
                    <div style={{ fontSize: 20, fontWeight: 700, color: '#0F172A' }}>الإشعارات</div>
                    <div style={{ fontSize: 13, color: '#64748B', marginTop: 2 }}>
                        {unread > 0 ? `${unread} إشعار غير مقروء` : 'جميع الإشعارات مقروءة'}
                    </div>
                </div>
                <div style={{ display: 'flex', gap: 8 }}>
                    {/* Filter tabs */}
                    {['all', 'unread'].map(f => (
                        <button key={f} onClick={() => setFilter(f)} style={{
                            padding: '7px 16px', borderRadius: 8, fontSize: 12, fontWeight: 500, cursor: 'pointer',
                            border: `1px solid ${filter === f ? '#0D9488' : 'rgba(0,0,0,0.12)'}`,
                            background: filter === f ? '#F0FDFA' : 'none',
                            color: filter === f ? '#0D9488' : '#64748B',
                        }}>
                            {f === 'all' ? 'الكل' : 'غير مقروء'}
                            {f === 'unread' && unread > 0 && (
                                <span style={{ marginRight: 6, background: '#0D9488', color: '#fff', borderRadius: 10, padding: '1px 6px', fontSize: 10 }}>{unread}</span>
                            )}
                        </button>
                    ))}
                    {unread > 0 && (
                        <button onClick={markAllRead} style={{
                            padding: '7px 16px', borderRadius: 8, fontSize: 12, fontWeight: 500, cursor: 'pointer',
                            border: '1px solid rgba(0,0,0,0.12)', background: 'none', color: '#64748B',
                            display: 'flex', alignItems: 'center', gap: 6,
                        }}>
                            <i className="ti ti-checks" style={{ fontSize: 14 }} /> قراءة الكل
                        </button>
                    )}
                </div>
            </div>

            {/* List */}
            {filtered.length === 0 ? (
                <div style={{ textAlign: 'center', padding: '60px 24px', color: '#94A3B8' }}>
                    <i className="ti ti-bell-off" style={{ fontSize: 52, display: 'block', marginBottom: 14, opacity: 0.25 }} />
                    <div style={{ fontSize: 15, fontWeight: 600, marginBottom: 6 }}>
                        {filter === 'unread' ? 'لا توجد إشعارات غير مقروءة' : 'لا توجد إشعارات بعد'}
                    </div>
                    <div style={{ fontSize: 13 }}>ستظهر هنا إشعارات الإعجابات والتعليقات والطلبات</div>
                </div>
            ) : (
                <div style={{ display: 'flex', flexDirection: 'column', gap: 10 }}>
                    {filtered.map(n => (
                        <NotifCard key={n.id} n={n} onRead={markRead} onDelete={deleteNotif} />
                    ))}
                </div>
            )}

            {/* Pagination info */}
            {notifications?.total > 30 && (
                <div style={{ textAlign: 'center', fontSize: 12, color: '#94A3B8', paddingBottom: 8 }}>
                    يتم عرض أحدث {notifications.data?.length} من {notifications.total} إشعار
                </div>
            )}
        </UserLayout>
    );
}
