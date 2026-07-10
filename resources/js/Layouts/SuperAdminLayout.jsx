import { Link, router, usePage } from '@inertiajs/react';
import { useEffect, useState } from 'react';

function Toast({ flash }) {
    const [visible, setVisible] = useState(false);
    const [msg, setMsg]         = useState(null);
    const [type, setType]       = useState('success');

    useEffect(() => {
        if (flash?.success) { setMsg(flash.success); setType('success'); setVisible(true); }
        else if (flash?.error) { setMsg(flash.error); setType('error');   setVisible(true); }
        else { setVisible(false); return; }
        const t = setTimeout(() => setVisible(false), 5000);
        return () => clearTimeout(t);
    }, [flash?.success, flash?.error]);

    if (!visible || !msg) return null;

    const cfg = type === 'success'
        ? { bg: '#F0FDF4', border: '#9FE1CB', color: '#134E4A', icon: 'ti-circle-check' }
        : { bg: '#FEF2F2', border: '#FECACA', color: '#B91C1C', icon: 'ti-alert-circle'  };

    return (
        <div className="left-4 right-4 sm:left-0 sm:right-0 sm:mx-auto sm:w-fit sm:min-w-[300px]" style={{
            position: 'fixed', bottom: 24,
            zIndex: 9999, maxWidth: 480,
            background: cfg.bg, border: `1px solid ${cfg.border}`,
            borderRadius: 12, padding: '12px 18px',
            display: 'flex', alignItems: 'center', gap: 10,
            boxShadow: '0 8px 32px rgba(0,0,0,0.14)',
            animation: 'slideUp 0.25s ease',
            fontFamily: "'Cairo','Inter',sans-serif",
        }}>
            <style>{`@keyframes slideUp{from{opacity:0;transform:translateY(16px)}to{opacity:1;transform:translateY(0)}}`}</style>
            <i className={`ti ${cfg.icon}`} style={{ fontSize: 18, color: cfg.color, flexShrink: 0 }} />
            <span style={{ fontSize: 13, fontWeight: 600, color: cfg.color, flex: 1 }}>{msg}</span>
            <button onClick={() => setVisible(false)} style={{ background: 'none', border: 'none', cursor: 'pointer', color: cfg.color, padding: 0, fontSize: 16, lineHeight: 1, opacity: 0.6 }}>
                <i className="ti ti-x" />
            </button>
        </div>
    );
}

// ── Live notification toast ────────────────────────────────────────────────────
function LiveToast({ payload, onClose }) {
    if (!payload) return null;
    return (
        <div className="left-4 right-4 sm:right-auto sm:left-5" style={{
            position: 'fixed', top: 20, zIndex: 99999,
            maxWidth: 420,
            background: '#16124A', borderRadius: 14, padding: '14px 18px',
            display: 'flex', alignItems: 'flex-start', gap: 12,
            boxShadow: '0 8px 40px rgba(0,0,0,0.35)',
            animation: 'liveToastIn 0.35s cubic-bezier(.22,.68,0,1.2)',
            fontFamily: "'Cairo','Inter',sans-serif", direction: 'rtl',
            border: '1px solid rgba(167,139,250,0.2)',
        }}>
            <style>{`@keyframes liveToastIn{from{opacity:0;transform:translateY(-18px) scale(.93)}to{opacity:1;transform:translateY(0) scale(1)}}`}</style>
            <div style={{ width: 34, height: 34, borderRadius: 10, background: 'linear-gradient(135deg,#7C3AED,#A78BFA)', display: 'flex', alignItems: 'center', justifyContent: 'center', flexShrink: 0 }}>
                <i className="ti ti-bell-ringing" style={{ fontSize: 16, color: '#fff' }} />
            </div>
            <div style={{ flex: 1, minWidth: 0 }}>
                <div style={{ fontSize: 12, fontWeight: 700, color: '#A78BFA', marginBottom: 2 }}>إشعار جديد</div>
                <div style={{ fontSize: 13, fontWeight: 600, color: '#F1F5F9', lineHeight: 1.35 }}>{payload.title}</div>
                {payload.message && <div style={{ fontSize: 11.5, color: '#94A3B8', marginTop: 3 }}>{payload.message}</div>}
            </div>
            <button onClick={onClose} style={{ background: 'none', border: 'none', cursor: 'pointer', color: '#475569', fontSize: 15, padding: 2, lineHeight: 1, flexShrink: 0 }}>
                <i className="ti ti-x" />
            </button>
        </div>
    );
}

const NAV_GROUPS = [
    {
        items: [
            { href: '/super-admin/dashboard', icon: 'ti-layout-dashboard', label: 'لوحة التحكم' },
        ],
    },
    {
        group: 'المستخدمون',
        items: [
            { href: '/super-admin/admins',  icon: 'ti-user-shield', label: 'المشرفون' },
            { href: '/super-admin/users',   icon: 'ti-users',       label: 'المستخدمون' },
            { href: '/super-admin/blocked', icon: 'ti-ban',         label: 'المحظورون' },
        ],
    },
    {
        group: 'الطلبات',
        items: [
            { href: '/super-admin/business-requests', icon: 'ti-briefcase',  label: 'طلبات الأعمال',  pendingBadge: true },
            { href: '/super-admin/service-requests',  icon: 'ti-tool',       label: 'طلبات الخدمات' },
        ],
    },
    {
        group: 'الأعمال',
        items: [
            { href: '/super-admin/businesses',            icon: 'ti-building', label: 'حسابات الأعمال' },
            { href: '/super-admin/services',              icon: 'ti-layout-grid', label: 'الخدمات' },
            { href: '/super-admin/identity-verifications',icon: 'ti-id-badge',  label: 'توثيق الهوية' },
        ],
    },
    {
        group: 'المحتوى',
        items: [
            { href: '/super-admin/posts',   icon: 'ti-file-text',    label: 'المنشورات' },
            { href: '/super-admin/ads',     icon: 'ti-speakerphone', label: 'الإعلانات' },
            { href: '/super-admin/reports', icon: 'ti-flag',         label: 'البلاغات' },
        ],
    },
    {
        group: 'الإعدادات',
        items: [
            { href: '/super-admin/categories',             icon: 'ti-tag',      label: 'الفئات' },
            { href: '/super-admin/subcategories',          icon: 'ti-tags',     label: 'الفئات الفرعية' },
            { href: '/super-admin/active-types',           icon: 'ti-activity', label: 'أنواع النشاط' },
            { href: '/super-admin/active-type-businesses', icon: 'ti-building', label: 'أنواع الأعمال' },
            { href: '/super-admin/cities',                 icon: 'ti-map-pin',  label: 'المدن' },
        ],
    },
    {
        group: 'الأذونات',
        items: [
            { href: '/super-admin/roles',        icon: 'ti-key',  label: 'الأدوار' },
            { href: '/super-admin/permissions',  icon: 'ti-lock', label: 'الصلاحيات' },
            { href: '/super-admin/notifications',icon: 'ti-bell', label: 'الإشعارات' },
        ],
    },
];

export default function SuperAdminLayout({ children, title }) {
    const { auth, flash, badges } = usePage().props;
    const current = typeof window !== 'undefined' ? window.location.pathname : '';
    const admin   = auth?.admin;
    const initials = `${(admin?.first_name ?? 'S')[0]}${(admin?.last_name ?? 'A')[0]}`.toUpperCase();

    const [pendingBiz,   setPendingBiz]   = useState(badges?.pending_businesses   ?? 0);
    const [unreadNotif,  setUnreadNotif]  = useState(badges?.unread_notifications  ?? 0);
    const [liveToast,    setLiveToast]    = useState(null);
    const [wsStatus,     setWsStatus]     = useState('connecting');
    const [notifPerm,    setNotifPerm]    = useState(() =>
        typeof Notification !== 'undefined' ? Notification.permission : 'unsupported'
    );
    const [sidebarOpen, setSidebarOpen] = useState(false);

    // Close the mobile drawer whenever the route changes
    useEffect(() => {
        const close = () => setSidebarOpen(false);
        return router.on('navigate', close);
    }, []);

    useEffect(() => { setPendingBiz(badges?.pending_businesses ?? 0); },  [badges?.pending_businesses]);
    useEffect(() => { setUnreadNotif(badges?.unread_notifications ?? 0); }, [badges?.unread_notifications]);

    // WebSocket connection + real-time notifications
    useEffect(() => {
        if (!admin?.id || typeof window.Echo === 'undefined') {
            setWsStatus('disconnected');
            return;
        }

        const pusher = window.Echo.connector?.pusher;
        const onConnected    = () => setWsStatus('connected');
        const onDisconnected = () => setWsStatus('disconnected');
        if (pusher) {
            pusher.connection.bind('connected',    onConnected);
            pusher.connection.bind('disconnected', onDisconnected);
            pusher.connection.bind('failed',       onDisconnected);
            pusher.connection.bind('unavailable',  onDisconnected);
            if (pusher.connection.state === 'connected') setWsStatus('connected');
        }

        const channel = window.Echo.private(`superadmins.${admin.id}.notifications`);

        const handleNotification = (payload) => {
            setUnreadNotif(prev => prev + 1);
            setLiveToast({ title: payload.title ?? 'إشعار جديد', message: payload.message ?? '' });
            setTimeout(() => setLiveToast(null), 6000);

            if (typeof Notification !== 'undefined' && Notification.permission === 'granted') {
                new Notification(payload.title ?? 'Skillify', {
                    body: payload.message ?? '',
                    icon: '/favicon.ico',
                    tag:  'skillify-superadmin',
                });
            }
        };
        channel.notification(handleNotification);

        // Only detach this listener — don't Echo.leave() the channel, since this layout
        // remounts on every Inertia page navigation (no persistent layout) and leaving
        // would tear down the channel a newly-mounted page just (re)subscribed to.
        return () => {
            channel.stopListeningForNotification(handleNotification);
            if (pusher) {
                pusher.connection.unbind('connected',    onConnected);
                pusher.connection.unbind('disconnected', onDisconnected);
                pusher.connection.unbind('failed',       onDisconnected);
                pusher.connection.unbind('unavailable',  onDisconnected);
            }
        };
    }, [admin?.id]);

    // Poll badge fallback every 30 s
    useEffect(() => {
        const id = setInterval(() => {
            router.reload({ only: ['badges'], preserveScroll: true, preserveState: true });
        }, 30000);
        return () => clearInterval(id);
    }, []);

    const requestNotifPermission = async () => {
        if (typeof Notification === 'undefined') return;
        if (Notification.permission === 'denied') {
            alert('الإشعارات محظورة في إعدادات المتصفح. افتح إعدادات الموقع وأعد تفعيلها يدوياً.');
            return;
        }
        const result = await Notification.requestPermission();
        setNotifPerm(result);
    };

    return (
        <div dir="rtl" style={{ display: 'flex', minHeight: '100vh', background: '#F0F2F8', fontFamily: "'Cairo', 'Inter', sans-serif" }}>

            {/* ── Mobile overlay ── */}
            {sidebarOpen && (
                <div onClick={() => setSidebarOpen(false)} className="lg:hidden fixed inset-0 bg-black/40 z-30" />
            )}

            {/* ══════════════ Sidebar ══════════════ */}
            <aside
                className={`w-[234px] max-w-[80vw] fixed top-0 bottom-0 right-0 z-40 flex flex-col transition-transform duration-300 ease-in-out lg:translate-x-0 ${sidebarOpen ? 'translate-x-0' : 'translate-x-full'}`}
                style={{
                background: 'linear-gradient(180deg, #16124A 0%, #1E1B4B 40%, #1A1845 100%)',
                boxShadow: '-4px 0 24px rgba(0,0,0,0.18)',
            }}>
                {/* Brand */}
                <div style={{ padding: '22px 20px 18px', borderBottom: '0.5px solid rgba(167,139,250,0.15)' }}>
                    <div style={{ display: 'flex', alignItems: 'center', gap: 11 }}>
                        <div style={{
                            width: 36, height: 36, borderRadius: 10,
                            background: 'linear-gradient(135deg, #7C3AED 0%, #A78BFA 100%)',
                            display: 'flex', alignItems: 'center', justifyContent: 'center',
                            fontSize: 17, color: '#fff',
                            boxShadow: '0 4px 12px rgba(124,58,237,0.4)',
                        }}>
                            <i className="ti ti-shield-lock" />
                        </div>
                        <div>
                            <div style={{ fontSize: 17, fontWeight: 800, color: '#fff', letterSpacing: -0.4, lineHeight: 1.1 }}>
                                <span style={{ color: '#A78BFA' }}>Skill</span>ify
                            </div>
                            <div style={{ fontSize: 9, fontWeight: 600, color: 'rgba(167,139,250,0.55)', letterSpacing: 1.2, textTransform: 'uppercase' }}>
                                Super Admin
                            </div>
                        </div>
                    </div>
                </div>

                {/* Nav */}
                <nav style={{ flex: 1, overflowY: 'auto', padding: '10px 10px' }}>
                    {NAV_GROUPS.map((grp, gi) => (
                        <div key={gi} style={{ marginBottom: grp.group ? 6 : 0 }}>
                            {grp.group && (
                                <div style={{ fontSize: 9, fontWeight: 700, letterSpacing: 1.1, textTransform: 'uppercase', color: 'rgba(167,139,250,0.35)', padding: '8px 12px 4px', userSelect: 'none' }}>
                                    {grp.group}
                                </div>
                            )}
                            {grp.items.map(({ href, icon, label, pendingBadge }) => {
                                const active  = current === href || current.startsWith(href + '/');
                                const hasDot  = (href === '/super-admin/businesses' || (pendingBadge && pendingBiz > 0)) && pendingBiz > 0;
                                return (
                                    <Link key={href} href={href} style={{
                                        display: 'flex', alignItems: 'center', gap: 10,
                                        padding: '8px 11px', borderRadius: 8, marginBottom: 2,
                                        fontSize: 12.5, fontWeight: active ? 700 : 400,
                                        background: active ? 'rgba(167,139,250,0.18)' : 'transparent',
                                        color: active ? '#C4B5FD' : 'rgba(148,163,184,0.75)',
                                        textDecoration: 'none',
                                        borderRight: active ? '3px solid #A78BFA' : '3px solid transparent',
                                        transition: 'all 0.15s',
                                    }}
                                        onMouseEnter={e => { if (!active) { e.currentTarget.style.background = 'rgba(255,255,255,0.05)'; e.currentTarget.style.color = '#C4B5FD'; } }}
                                        onMouseLeave={e => { if (!active) { e.currentTarget.style.background = 'transparent'; e.currentTarget.style.color = 'rgba(148,163,184,0.75)'; } }}
                                    >
                                        <div style={{ position: 'relative', width: 28, height: 28, flexShrink: 0 }}>
                                            <div style={{
                                                width: 28, height: 28, borderRadius: 7,
                                                display: 'flex', alignItems: 'center', justifyContent: 'center',
                                                background: active ? 'rgba(167,139,250,0.20)' : 'rgba(255,255,255,0.04)',
                                                color: active ? '#A78BFA' : 'inherit',
                                                fontSize: 14,
                                            }}>
                                                <i className={`ti ${icon}`} />
                                            </div>
                                            {hasDot && <span style={{
                                                position: 'absolute', top: -2, left: -2,
                                                width: 8, height: 8, borderRadius: '50%',
                                                background: '#EF4444', border: '1.5px solid #1E1B4B',
                                            }} />}
                                        </div>
                                        <span style={{ flex: 1 }}>{label}</span>
                                        {hasDot && (
                                            <span style={{
                                                fontSize: 10, fontWeight: 700, lineHeight: 1,
                                                padding: '2px 6px', borderRadius: 10,
                                                background: '#EF4444', color: '#fff',
                                            }}>{pendingBiz}</span>
                                        )}
                                    </Link>
                                );
                            })}
                        </div>
                    ))}
                </nav>

                {/* User profile */}
                <div style={{ padding: '14px 16px', borderTop: '0.5px solid rgba(167,139,250,0.12)' }}>
                    <Link href="/super-admin/profile" style={{ display: 'flex', alignItems: 'center', gap: 10, marginBottom: 12, textDecoration: 'none' }}>
                        <div style={{
                            width: 34, height: 34, borderRadius: '50%',
                            background: 'linear-gradient(135deg,#7C3AED,#A78BFA)',
                            display: 'flex', alignItems: 'center', justifyContent: 'center',
                            fontSize: 12, fontWeight: 700, color: '#fff', flexShrink: 0,
                        }}>
                            {initials}
                        </div>
                        <div style={{ flex: 1, minWidth: 0 }}>
                            <div style={{ fontSize: 12, fontWeight: 700, color: '#E2E8F0', overflow: 'hidden', textOverflow: 'ellipsis', whiteSpace: 'nowrap' }}>
                                {admin?.first_name ?? 'المدير'} {admin?.last_name ?? 'العام'}
                            </div>
                            <div style={{ fontSize: 10, color: 'rgba(148,163,184,0.6)', overflow: 'hidden', textOverflow: 'ellipsis', whiteSpace: 'nowrap' }}>
                                {admin?.email ?? ''}
                            </div>
                        </div>
                    </Link>
                    <form method="POST" action="/super-admin/logout" style={{ margin: 0 }}>
                        <input type="hidden" name="_token" value={document.querySelector('meta[name="csrf-token"]')?.content ?? ''} />
                        <button type="submit" style={{
                            display: 'flex', alignItems: 'center', gap: 7,
                            color: 'rgba(239,68,68,0.8)', background: 'rgba(239,68,68,0.08)',
                            border: '0.5px solid rgba(239,68,68,0.18)',
                            borderRadius: 8, cursor: 'pointer', fontSize: 12, fontWeight: 600,
                            padding: '7px 12px', width: '100%', justifyContent: 'center',
                            fontFamily: "'Cairo','Inter',sans-serif",
                            transition: 'all 0.15s',
                        }}
                            onMouseEnter={e => { e.currentTarget.style.background = 'rgba(239,68,68,0.15)'; e.currentTarget.style.color = '#EF4444'; }}
                            onMouseLeave={e => { e.currentTarget.style.background = 'rgba(239,68,68,0.08)'; e.currentTarget.style.color = 'rgba(239,68,68,0.8)'; }}
                        >
                            <i className="ti ti-logout" style={{ fontSize: 14 }} /> تسجيل الخروج
                        </button>
                    </form>
                </div>
            </aside>

            {/* ══════════════ Main ══════════════ */}
            <div className="mr-0 lg:mr-[234px]" style={{ flex: 1, display: 'flex', flexDirection: 'column', minWidth: 0 }}>

                {/* Header */}
                <header className="px-4 sm:px-6 lg:px-7" style={{
                    background: '#fff',
                    borderBottom: '0.5px solid rgba(0,0,0,0.07)',
                    height: 58,
                    display: 'flex', alignItems: 'center', justifyContent: 'space-between',
                    position: 'sticky', top: 0, zIndex: 30,
                    boxShadow: '0 1px 8px rgba(0,0,0,0.05)',
                }}>
                    <div style={{ display: 'flex', alignItems: 'center', gap: 8, minWidth: 0 }}>
                        <button onClick={() => setSidebarOpen(v => !v)} className="flex lg:hidden items-center justify-center" style={{ width: 32, height: 32, borderRadius: 8, border: 'none', background: '#F0F2F8', color: '#4C1D95', fontSize: 16, cursor: 'pointer', flexShrink: 0 }}>
                            <i className="ti ti-menu-2" />
                        </button>
                        <div style={{ width: 3, height: 18, borderRadius: 2, background: 'linear-gradient(180deg,#7C3AED,#A78BFA)', flexShrink: 0 }} className="hidden sm:block" />
                        <span style={{ fontSize: 15, fontWeight: 700, color: '#1E1B4B', overflow: 'hidden', textOverflow: 'ellipsis', whiteSpace: 'nowrap' }}>
                            {title ?? 'لوحة التحكم'}
                        </span>
                    </div>
                    <div style={{ display: 'flex', alignItems: 'center', gap: 8, flexShrink: 0 }}>

                        {/* WebSocket status */}
                        <div title={wsStatus === 'connected' ? 'متصل بالإشعارات الفورية' : 'غير متصل — php artisan reverb:start'}
                            className="hidden sm:flex"
                            style={{ alignItems: 'center', gap: 5, padding: '4px 8px', borderRadius: 20, background: wsStatus === 'connected' ? '#ECFDF5' : '#FEF2F2', border: `1px solid ${wsStatus === 'connected' ? '#6EE7B7' : '#FCA5A5'}` }}>
                            <span style={{ width: 6, height: 6, borderRadius: '50%', background: wsStatus === 'connected' ? '#10B981' : '#EF4444', display: 'block', animation: wsStatus === 'connected' ? 'wsPulse2 2s infinite' : 'none' }} />
                            <span style={{ fontSize: 10, fontWeight: 600, color: wsStatus === 'connected' ? '#065F46' : '#991B1B' }}>
                                {wsStatus === 'connected' ? 'Live' : 'Offline'}
                            </span>
                        </div>
                        <style>{`@keyframes wsPulse2{0%,100%{opacity:1}50%{opacity:.5}}`}</style>

                        {/* Notification permission */}
                        {notifPerm !== 'granted' && (
                            <button onClick={requestNotifPermission}
                                title={notifPerm === 'denied' ? 'الإشعارات محظورة' : 'تفعيل إشعارات المتصفح'}
                                style={{ width: 32, height: 32, borderRadius: 9, cursor: 'pointer', border: `1px solid ${notifPerm === 'denied' ? '#FCA5A5' : '#C4B5FD'}`, background: notifPerm === 'denied' ? '#FEF2F2' : '#F5F3FF', color: notifPerm === 'denied' ? '#DC2626' : '#7C3AED', fontSize: 14, display: 'flex', alignItems: 'center', justifyContent: 'center' }}>
                                <i className={`ti ${notifPerm === 'denied' ? 'ti-bell-off' : 'ti-bell-plus'}`} />
                            </button>
                        )}

                        <div style={{ display: 'flex', alignItems: 'center', gap: 9, padding: '6px 12px', borderRadius: 10, background: '#F5F3FF', border: '0.5px solid rgba(167,139,250,0.25)' }}>
                            <div style={{ width: 26, height: 26, borderRadius: '50%', background: 'linear-gradient(135deg,#7C3AED,#A78BFA)', display: 'flex', alignItems: 'center', justifyContent: 'center', fontSize: 10, fontWeight: 700, color: '#fff', flexShrink: 0 }}>
                                {initials}
                            </div>
                            <span className="hidden md:inline" style={{ fontSize: 12, fontWeight: 600, color: '#4C1D95' }}>
                                {admin?.first_name ?? 'المدير'} {admin?.last_name ?? 'العام'}
                            </span>
                        </div>
                    </div>
                </header>

                <main className="px-4 py-5 sm:px-6 lg:px-7 lg:py-6" style={{ flex: 1, minWidth: 0 }}>
                    <Toast flash={flash} />
                    <LiveToast payload={liveToast} onClose={() => setLiveToast(null)} />
                    <div style={{ display: 'flex', flexDirection: 'column', gap: 22, minWidth: 0 }}>
                        {children}
                    </div>
                </main>
            </div>
        </div>
    );
}
