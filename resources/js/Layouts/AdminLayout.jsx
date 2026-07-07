import { Link, usePage, router } from '@inertiajs/react';
import { useEffect, useState } from 'react';

// ── Design tokens ─────────────────────────────────────────────────────────────
export const C = {
    // Sidebar
    sidebarBg:      '#0F172A',
    sidebarText:    '#94A3B8',
    sidebarActive:  '#38BDF8',
    sidebarActiveBg:'rgba(56,189,248,0.10)',
    sidebarHover:   'rgba(255,255,255,0.05)',
    sidebarBorder:  'rgba(255,255,255,0.06)',

    // Brand / primary
    primary:        '#0EA5E9',
    primaryDark:    '#0284C7',
    primaryMuted:   'rgba(14,165,233,0.12)',

    // Accent (teal for save/approve)
    teal:           '#0D9488',
    tealLight:      '#14B8A6',

    // Page background
    pageBg:         '#F0F9FF',

    // Cards
    cardBg:         '#FFFFFF',
    cardShadow:     '0 1px 3px rgba(15,23,42,0.07), 0 4px 20px rgba(15,23,42,0.04)',
    cardRadius:     '14px',
    cardBorder:     '1px solid rgba(15,23,42,0.06)',

    // Topbar
    topbarBg:       '#FFFFFF',
    topbarShadow:   '0 1px 0 rgba(15,23,42,0.06), 0 2px 8px rgba(15,23,42,0.04)',

    // Typography
    textDark:       '#0F172A',
    textMed:        '#374151',
    textMuted:      '#6B7280',
    textFaint:      '#94A3B8',

    // Status
    successBg:      '#ECFDF5', successText: '#065F46', successBorder: '#A7F3D0',
    warningBg:      '#FFFBEB', warningText: '#78350F', warningBorder: '#FDE68A',
    dangerBg:       '#FEF2F2', dangerText:  '#991B1B', dangerBorder:  '#FECACA',
    infoBg:         '#EFF6FF', infoText:    '#1E40AF', infoBorder:    '#BFDBFE',

    // Accents palette
    blue:   '#3B82F6', purple: '#8B5CF6', amber: '#F59E0B',
    red:    '#EF4444', pink:   '#EC4899',
};

// ── Toast ─────────────────────────────────────────────────────────────────────
function Toast({ flash }) {
    const [visible, setVisible] = useState(false);
    const [msg, setMsg]         = useState(null);
    const [type, setType]       = useState('success');

    useEffect(() => {
        if (flash?.success) { setMsg(flash.success); setType('success'); setVisible(true); }
        else if (flash?.error) { setMsg(flash.error); setType('error');  setVisible(true); }
        else { setVisible(false); return; }
        const t = setTimeout(() => setVisible(false), 5000);
        return () => clearTimeout(t);
    }, [flash?.success, flash?.error]);

    if (!visible || !msg) return null;

    const cfg = type === 'success'
        ? { bg: C.successBg, border: C.successBorder, color: C.successText, icon: 'ti-circle-check' }
        : { bg: C.dangerBg,  border: C.dangerBorder,  color: C.dangerText,  icon: 'ti-alert-circle' };

    return (
        <div className="left-4 right-4 sm:left-0 sm:right-0 sm:mx-auto sm:w-fit sm:min-w-[320px]" style={{
            position: 'fixed', bottom: 28,
            zIndex: 9999, maxWidth: 500,
            background: cfg.bg, border: `1px solid ${cfg.border}`,
            borderRadius: 14, padding: '13px 20px',
            display: 'flex', alignItems: 'center', gap: 10,
            boxShadow: '0 8px 32px rgba(0,0,0,0.12), 0 2px 8px rgba(0,0,0,0.08)',
            animation: 'toastIn 0.3s cubic-bezier(.22,.68,0,1.2)',
            fontFamily: "'Cairo','Inter',sans-serif",
        }}>
            <style>{`@keyframes toastIn{from{opacity:0;transform:translateY(20px) scale(.95)}to{opacity:1;transform:translateY(0) scale(1)}}`}</style>
            <div style={{ width: 30, height: 30, borderRadius: '50%', background: `${cfg.color}18`, display: 'flex', alignItems: 'center', justifyContent: 'center', flexShrink: 0 }}>
                <i className={`ti ${cfg.icon}`} style={{ fontSize: 16, color: cfg.color }} />
            </div>
            <span style={{ fontSize: 13, fontWeight: 600, color: cfg.color, flex: 1, lineHeight: 1.4 }}>{msg}</span>
            <button onClick={() => setVisible(false)} style={{ background: 'none', border: 'none', cursor: 'pointer', color: cfg.color, padding: 4, fontSize: 14, lineHeight: 1, opacity: 0.5, borderRadius: 6 }}>
                <i className="ti ti-x" />
            </button>
        </div>
    );
}

// ── Live notification toast (WebSocket) ───────────────────────────────────────
function LiveToast({ payload, onClose }) {
    if (!payload) return null;
    return (
        <div className="left-4 right-4 sm:right-auto sm:left-5" style={{
            position: 'fixed', top: 20, zIndex: 99999,
            maxWidth: 420,
            background: '#0F172A', borderRadius: 14, padding: '14px 18px',
            display: 'flex', alignItems: 'flex-start', gap: 12,
            boxShadow: '0 8px 40px rgba(0,0,0,0.35), 0 2px 10px rgba(0,0,0,0.2)',
            animation: 'liveToastIn 0.35s cubic-bezier(.22,.68,0,1.2)',
            fontFamily: "'Cairo','Inter',sans-serif", direction: 'rtl',
            border: '1px solid rgba(56,189,248,0.2)',
        }}>
            <style>{`@keyframes liveToastIn{from{opacity:0;transform:translateY(-18px) scale(.93)}to{opacity:1;transform:translateY(0) scale(1)}}`}</style>
            <div style={{ width: 34, height: 34, borderRadius: 10, background: 'linear-gradient(135deg,#0EA5E9,#0D9488)', display: 'flex', alignItems: 'center', justifyContent: 'center', flexShrink: 0 }}>
                <i className="ti ti-bell-ringing" style={{ fontSize: 16, color: '#fff' }} />
            </div>
            <div style={{ flex: 1, minWidth: 0 }}>
                <div style={{ fontSize: 12, fontWeight: 700, color: '#38BDF8', marginBottom: 2 }}>إشعار جديد</div>
                <div style={{ fontSize: 13, fontWeight: 600, color: '#F1F5F9', lineHeight: 1.35 }}>{payload.title}</div>
                {payload.message && <div style={{ fontSize: 11.5, color: '#94A3B8', marginTop: 3 }}>{payload.message}</div>}
            </div>
            <button onClick={onClose} style={{ background: 'none', border: 'none', cursor: 'pointer', color: '#475569', fontSize: 15, padding: 2, lineHeight: 1, flexShrink: 0 }}>
                <i className="ti ti-x" />
            </button>
        </div>
    );
}

// ── Nav items ──────────────────────────────────────────────────────────────────
const NAV_GROUPS = [
    {
        label: 'الرئيسية',
        items: [
            { href: '/admin/dashboard', icon: 'ti-layout-dashboard', label: 'لوحة التحكم' },
        ],
    },
    {
        label: 'إدارة المستخدمين',
        items: [
            { href: '/admin/users',                  icon: 'ti-users',        label: 'المستخدمون' },
            { href: '/admin/workers',                icon: 'ti-briefcase',    label: 'المزودون' },
            { href: '/admin/verifications',          icon: 'ti-shield-check', label: 'تحقق الأعمال' },
            { href: '/admin/identity-verifications', icon: 'ti-id-badge',     label: 'توثيق الهوية' },
            { href: '/admin/blocked',                icon: 'ti-ban',          label: 'المحظورون' },
        ],
    },
    {
        label: 'الطلبات',
        items: [
            { href: '/admin/business-requests', icon: 'ti-briefcase',    label: 'طلبات الأعمال',  pendingBadge: true },
            { href: '/admin/service-requests',  icon: 'ti-tool',         label: 'طلبات الخدمات' },
        ],
    },
    {
        label: 'المحتوى',
        items: [
            { href: '/admin/services',  icon: 'ti-layout-grid',  label: 'الخدمات' },
            { href: '/admin/posts',     icon: 'ti-file-text',    label: 'المنشورات' },
            { href: '/admin/reports',   icon: 'ti-flag',         label: 'البلاغات' },
            { href: '/admin/ads',       icon: 'ti-speakerphone', label: 'الإعلانات' },
        ],
    },
    {
        label: 'الإعدادات',
        items: [
            { href: '/admin/categories',             icon: 'ti-tag',      label: 'الفئات' },
            { href: '/admin/subcategories',          icon: 'ti-tags',     label: 'الفئات الفرعية' },
            { href: '/admin/active-types',           icon: 'ti-activity', label: 'أنواع النشاط' },
            { href: '/admin/active-type-businesses', icon: 'ti-building', label: 'أنواع الأعمال' },
            { href: '/admin/cities',                 icon: 'ti-map-pin',  label: 'المدن' },
        ],
    },
    {
        label: 'النظام',
        items: [
            { href: '/admin/notifications', icon: 'ti-bell', label: 'الإشعارات', notifBadge: true },
        ],
    },
];

// ── Layout ─────────────────────────────────────────────────────────────────────
export default function AdminLayout({ children, title }) {
    const { auth, flash, badges } = usePage().props;
    const current = typeof window !== 'undefined' ? window.location.pathname : '';
    const admin   = auth?.admin;

    const [pendingBiz,  setPendingBiz]  = useState(badges?.pending_businesses   ?? 0);
    const [unreadNotif, setUnreadNotif] = useState(badges?.unread_notifications  ?? 0);
    const [liveToast,   setLiveToast]   = useState(null);
    const [wsStatus,    setWsStatus]    = useState('connecting'); // connecting | connected | disconnected
    const [notifPerm,   setNotifPerm]   = useState(() =>
        typeof Notification !== 'undefined' ? Notification.permission : 'unsupported'
    );
    const [sidebarOpen, setSidebarOpen] = useState(false);

    // Close the mobile drawer whenever the route changes
    useEffect(() => {
        const close = () => setSidebarOpen(false);
        return router.on('navigate', close);
    }, []);

    useEffect(() => { setPendingBiz(badges?.pending_businesses   ?? 0); }, [badges?.pending_businesses]);
    useEffect(() => { setUnreadNotif(badges?.unread_notifications ?? 0); }, [badges?.unread_notifications]);

    // WebSocket connection + real-time notifications
    useEffect(() => {
        if (!admin?.id || typeof window.Echo === 'undefined') {
            setWsStatus('disconnected');
            return;
        }

        // Track Pusher connection state
        const pusher = window.Echo.connector?.pusher;
        const onConnected    = () => setWsStatus('connected');
        const onDisconnected = () => setWsStatus('disconnected');
        if (pusher) {
            pusher.connection.bind('connected',     onConnected);
            pusher.connection.bind('disconnected',  onDisconnected);
            pusher.connection.bind('failed',        onDisconnected);
            pusher.connection.bind('unavailable',   onDisconnected);
            // Already connected by the time this runs
            if (pusher.connection.state === 'connected') setWsStatus('connected');
        }

        const channel = window.Echo.private(`admins.${admin.id}.notifications`);

        const handleNotification = (payload) => {
            setUnreadNotif(prev => prev + 1);
            setLiveToast({ title: payload.title ?? 'إشعار جديد', message: payload.message ?? '' });
            setTimeout(() => setLiveToast(null), 6000);

            if (typeof Notification !== 'undefined' && Notification.permission === 'granted') {
                new Notification(payload.title ?? 'Skillify', {
                    body: payload.message ?? '',
                    icon: '/favicon.ico',
                    tag:  'skillify-admin',
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

    // Request browser notification permission — MUST be from user gesture in modern Chrome
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
        <div dir="rtl" style={{ display: 'flex', minHeight: '100vh', background: C.pageBg, fontFamily: "'Cairo', 'Inter', sans-serif" }}>

            {/* ── Mobile overlay ── */}
            {sidebarOpen && (
                <div onClick={() => setSidebarOpen(false)} className="lg:hidden fixed inset-0 bg-black/40 z-30" />
            )}

            {/* ── Sidebar ── */}
            <aside
                className={`w-[230px] max-w-[80vw] fixed top-0 bottom-0 right-0 z-40 flex flex-col transition-transform duration-300 ease-in-out lg:translate-x-0 ${sidebarOpen ? 'translate-x-0' : 'translate-x-full'}`}
                style={{
                background: C.sidebarBg,
                boxShadow: '-2px 0 20px rgba(0,0,0,0.15)',
            }}>
                {/* Logo */}
                <div style={{ padding: '20px 18px 16px', borderBottom: `1px solid ${C.sidebarBorder}` }}>
                    <div style={{ display: 'flex', alignItems: 'center', gap: 10 }}>
                        <div style={{ width: 34, height: 34, borderRadius: 10, background: 'linear-gradient(135deg,#0EA5E9,#0D9488)', display: 'flex', alignItems: 'center', justifyContent: 'center', fontSize: 16, color: '#fff', fontWeight: 800, flexShrink: 0 }}>
                            S
                        </div>
                        <div>
                            <div style={{ fontSize: 15, fontWeight: 800, color: '#F1F5F9', letterSpacing: -0.3 }}>
                                <span style={{ color: C.sidebarActive }}>Skill</span>ify
                            </div>
                            <div style={{ fontSize: 9, color: '#475569', fontWeight: 500, letterSpacing: 0.5 }}>لوحة الإدارة</div>
                        </div>
                    </div>
                </div>

                {/* Nav */}
                <nav style={{ flex: 1, overflowY: 'auto', padding: '10px 10px', scrollbarWidth: 'none' }}>
                    {NAV_GROUPS.map((group) => (
                        <div key={group.label} style={{ marginBottom: 6 }}>
                            <div style={{ fontSize: 9, fontWeight: 700, color: '#475569', letterSpacing: 1, textTransform: 'uppercase', padding: '10px 10px 4px' }}>
                                {group.label}
                            </div>
                            {group.items.map(({ href, icon, label, notifBadge, pendingBadge }) => {
                                const active  = current === href || current.startsWith(href + '/');
                                const hasDot  = (href === '/admin/verifications' || (pendingBadge && pendingBiz > 0)) && pendingBiz > 0;
                                const hasNotif = notifBadge && unreadNotif > 0;
                                return (
                                    <Link key={href} href={href} style={{
                                        display: 'flex', alignItems: 'center', gap: 9,
                                        padding: '7px 10px', borderRadius: 9, marginBottom: 1,
                                        fontSize: 12.5, fontWeight: active ? 600 : 400,
                                        background: active ? C.sidebarActiveBg : 'none',
                                        color: active ? C.sidebarActive : C.sidebarText,
                                        textDecoration: 'none', transition: 'all .15s',
                                        borderRight: active ? `3px solid ${C.sidebarActive}` : '3px solid transparent',
                                    }}
                                        onMouseEnter={e => { if (!active) { e.currentTarget.style.background = C.sidebarHover; e.currentTarget.style.color = '#CBD5E1'; } }}
                                        onMouseLeave={e => { if (!active) { e.currentTarget.style.background = 'none'; e.currentTarget.style.color = C.sidebarText; } }}
                                    >
                                        <div style={{ position: 'relative', width: 28, height: 28, flexShrink: 0 }}>
                                            <div style={{ width: 28, height: 28, borderRadius: 7, background: active ? `${C.sidebarActive}18` : 'rgba(255,255,255,0.04)', display: 'flex', alignItems: 'center', justifyContent: 'center' }}>
                                                <i className={`ti ${icon}`} style={{ fontSize: 14 }} />
                                            </div>
                                            {(hasDot || hasNotif) && <span style={{
                                                position: 'absolute', top: -2, left: -2,
                                                width: 8, height: 8, borderRadius: '50%',
                                                background: '#EF4444', border: `1.5px solid ${C.sidebarBg}`,
                                            }} />}
                                        </div>
                                        <span style={{ flex: 1 }}>{label}</span>
                                        {hasDot && (
                                            <span style={{ fontSize: 10, fontWeight: 700, padding: '2px 6px', borderRadius: 10, background: '#EF4444', color: '#fff' }}>{pendingBiz}</span>
                                        )}
                                        {hasNotif && (
                                            <span style={{ fontSize: 10, fontWeight: 700, padding: '2px 6px', borderRadius: 10, background: '#EF4444', color: '#fff' }}>{unreadNotif}</span>
                                        )}
                                    </Link>
                                );
                            })}
                        </div>
                    ))}
                </nav>

                {/* Footer */}
                <div style={{ padding: '12px 14px', borderTop: `1px solid ${C.sidebarBorder}` }}>
                    <div style={{ display: 'flex', alignItems: 'center', gap: 9, marginBottom: 8 }}>
                        <div style={{ width: 30, height: 30, borderRadius: 8, background: 'linear-gradient(135deg,#0EA5E9,#0D9488)', color: '#fff', display: 'flex', alignItems: 'center', justifyContent: 'center', fontSize: 12, fontWeight: 700, flexShrink: 0 }}>
                            {(admin?.first_name ?? 'A')[0].toUpperCase()}
                        </div>
                        <div style={{ flex: 1, minWidth: 0 }}>
                            <div style={{ fontSize: 12, fontWeight: 600, color: '#CBD5E1' }}>{admin?.first_name ?? 'المشرف'}</div>
                            <div style={{ fontSize: 10, color: '#475569', overflow: 'hidden', textOverflow: 'ellipsis', whiteSpace: 'nowrap' }}>{admin?.email ?? ''}</div>
                        </div>
                    </div>
                    <form method="POST" action="/admin/logout" style={{ margin: 0 }}>
                        <input type="hidden" name="_token" value={document.querySelector('meta[name="csrf-token"]')?.content ?? ''} />
                        <button type="submit" style={{ display: 'flex', alignItems: 'center', gap: 6, color: '#EF4444', background: 'rgba(239,68,68,0.08)', border: 'none', cursor: 'pointer', fontSize: 11, padding: '5px 10px', borderRadius: 6, width: '100%', fontWeight: 500 }}>
                            <i className="ti ti-logout" style={{ fontSize: 13 }} /> تسجيل الخروج
                        </button>
                    </form>
                </div>
            </aside>

            {/* ── Main ── */}
            <div className="mr-0 lg:mr-[230px]" style={{ flex: 1, display: 'flex', flexDirection: 'column', minHeight: '100vh', minWidth: 0 }}>

                {/* Topbar */}
                <header className="px-4 sm:px-6 lg:px-7" style={{
                    background: C.topbarBg, boxShadow: C.topbarShadow,
                    height: 58,
                    display: 'flex', alignItems: 'center', justifyContent: 'space-between',
                    position: 'sticky', top: 0, zIndex: 30,
                }}>
                    <div style={{ display: 'flex', alignItems: 'center', gap: 8, minWidth: 0 }}>
                        <button onClick={() => setSidebarOpen(v => !v)} className="flex lg:hidden items-center justify-center" style={{ width: 32, height: 32, borderRadius: 8, border: 'none', background: C.pageBg, color: C.textMed, fontSize: 16, cursor: 'pointer', flexShrink: 0 }}>
                            <i className="ti ti-menu-2" />
                        </button>
                        <div style={{ width: 3, height: 18, borderRadius: 4, background: 'linear-gradient(180deg,#0EA5E9,#0D9488)', flexShrink: 0 }} className="hidden sm:block" />
                        <div style={{ fontSize: 15, fontWeight: 700, color: C.textDark, overflow: 'hidden', textOverflow: 'ellipsis', whiteSpace: 'nowrap' }}>{title ?? 'لوحة الإدارة'}</div>
                    </div>
                    <div style={{ display: 'flex', alignItems: 'center', gap: 8, flexShrink: 0 }}>

                        {/* WebSocket status dot */}
                        <div title={wsStatus === 'connected' ? 'متصل بالإشعارات الفورية' : 'غير متصل — تشغيل: php artisan reverb:start'}
                            className="hidden sm:flex"
                            style={{ alignItems: 'center', gap: 5, padding: '4px 8px', borderRadius: 20, background: wsStatus === 'connected' ? '#ECFDF5' : '#FEF2F2', border: `1px solid ${wsStatus === 'connected' ? '#6EE7B7' : '#FCA5A5'}`, cursor: 'default' }}>
                            <span style={{ width: 6, height: 6, borderRadius: '50%', background: wsStatus === 'connected' ? '#10B981' : '#EF4444', boxShadow: wsStatus === 'connected' ? '0 0 0 2px rgba(16,185,129,0.3)' : 'none', display: 'block',
                                animation: wsStatus === 'connected' ? 'wsPulse 2s infinite' : 'none' }} />
                            <span style={{ fontSize: 10, fontWeight: 600, color: wsStatus === 'connected' ? '#065F46' : '#991B1B' }}>
                                {wsStatus === 'connected' ? 'Live' : 'Offline'}
                            </span>
                        </div>
                        <style>{`@keyframes wsPulse{0%,100%{opacity:1}50%{opacity:.5}}`}</style>

                        {/* Notification permission button */}
                        {notifPerm !== 'granted' && (
                            <button onClick={requestNotifPermission} title={notifPerm === 'denied' ? 'الإشعارات محظورة — انقر للمساعدة' : 'تفعيل إشعارات المتصفح'}
                                style={{ width: 32, height: 32, borderRadius: 9, cursor: 'pointer', border: `1px solid ${notifPerm === 'denied' ? '#FCA5A5' : '#FDE68A'}`, background: notifPerm === 'denied' ? '#FEF2F2' : '#FFFBEB', color: notifPerm === 'denied' ? '#DC2626' : '#D97706', fontSize: 14, display: 'flex', alignItems: 'center', justifyContent: 'center' }}>
                                <i className={`ti ${notifPerm === 'denied' ? 'ti-bell-off' : 'ti-bell-plus'}`} />
                            </button>
                        )}

                        {/* Bell — notifications page */}
                        <Link href="/admin/notifications" style={{
                            position: 'relative', width: 34, height: 34, borderRadius: 9,
                            border: C.cardBorder, background: unreadNotif > 0 ? '#FFF7ED' : '#F8FAFC',
                            display: 'flex', alignItems: 'center', justifyContent: 'center',
                            color: unreadNotif > 0 ? '#EA580C' : C.textMuted, fontSize: 15, textDecoration: 'none',
                        }}>
                            <i className={`ti ${unreadNotif > 0 ? 'ti-bell-ringing' : 'ti-bell'}`} />
                            {unreadNotif > 0 && (
                                <span style={{ position: 'absolute', top: -4, left: -4, minWidth: 16, height: 16, borderRadius: 10, background: '#EF4444', color: '#fff', fontSize: 9, fontWeight: 800, display: 'flex', alignItems: 'center', justifyContent: 'center', border: '1.5px solid #fff', padding: '0 3px' }}>
                                    {unreadNotif > 99 ? '99+' : unreadNotif}
                                </span>
                            )}
                        </Link>

                        <div style={{ fontSize: 12, color: C.textMuted, display: 'flex', alignItems: 'center', gap: 6 }}>
                            <div style={{ width: 26, height: 26, borderRadius: 8, background: 'linear-gradient(135deg,#0EA5E9,#0D9488)', color: '#fff', display: 'flex', alignItems: 'center', justifyContent: 'center', fontSize: 11, fontWeight: 700, flexShrink: 0 }}>
                                {(admin?.first_name ?? 'A')[0].toUpperCase()}
                            </div>
                            <span className="hidden md:inline">{admin?.first_name ?? 'المشرف'}</span>
                        </div>
                    </div>
                </header>

                {/* Content */}
                <main className="px-4 py-5 sm:px-6 lg:px-7 lg:py-6" style={{ flex: 1, minWidth: 0 }}>
                    <Toast flash={flash} />
                    <LiveToast payload={liveToast} onClose={() => setLiveToast(null)} />
                    <div style={{ display: 'flex', flexDirection: 'column', gap: 20, minWidth: 0 }}>
                        {children}
                    </div>
                </main>
            </div>
        </div>
    );
}
