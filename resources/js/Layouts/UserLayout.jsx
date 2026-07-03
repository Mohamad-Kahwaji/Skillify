import { Link, usePage, router } from '@inertiajs/react';
import { useState, useEffect, useCallback } from 'react';

const NAV = [
    { href: '/user/dashboard',       icon: 'ti-home',           label: 'الرئيسية' },
    { href: '/user/explore',         icon: 'ti-search',         label: 'استكشاف' },
    { href: '/user/services',        icon: 'ti-briefcase',      label: 'الخدمات' },
    { href: '/user/my-services',     icon: 'ti-tool',           label: 'خدماتي' },
    { href: '/user/community-posts', icon: 'ti-users',          label: 'المجتمع' },
    { href: '/user/conversations',   icon: 'ti-message-circle', label: 'الرسائل' },
    { href: '/user/notifications',   icon: 'ti-bell',           label: 'الإشعارات' },
    { href: '/user/posts',           icon: 'ti-file-text',      label: 'منشوراتي' },
    { href: '/user/profile',         icon: 'ti-user-edit',      label: 'ملفي الشخصي' },
];

function LiveToast({ payload, onClose }) {
    if (!payload) return null;
    return (
        <div className="left-4 right-4 sm:left-0 sm:right-0 sm:mx-auto sm:w-fit sm:min-w-[300px]" style={{
            position: 'fixed', top: 20,
            zIndex: 99999, maxWidth: 400,
            background: '#0F172A', borderRadius: 14, padding: '14px 18px',
            display: 'flex', alignItems: 'flex-start', gap: 12,
            boxShadow: '0 8px 40px rgba(0,0,0,0.3)',
            animation: 'userToastIn 0.35s cubic-bezier(.22,.68,0,1.2)',
            fontFamily: "'Cairo','Inter',sans-serif", direction: 'rtl',
            border: '1px solid rgba(13,148,136,0.25)',
        }}>
            <style>{`@keyframes userToastIn{from{opacity:0;transform:translateY(-18px) scale(.93)}to{opacity:1;transform:translateY(0) scale(1)}}`}</style>
            <div style={{ width: 34, height: 34, borderRadius: 10, background: 'linear-gradient(135deg,#0D9488,#14B8A6)', display: 'flex', alignItems: 'center', justifyContent: 'center', flexShrink: 0 }}>
                <i className="ti ti-bell-ringing" style={{ fontSize: 16, color: '#fff' }} />
            </div>
            <div style={{ flex: 1, minWidth: 0 }}>
                <div style={{ fontSize: 12, fontWeight: 700, color: '#14B8A6', marginBottom: 2 }}>إشعار جديد</div>
                <div style={{ fontSize: 13, fontWeight: 600, color: '#F1F5F9', lineHeight: 1.35 }}>{payload.title || payload.message}</div>
                {payload.title && payload.message && (
                    <div style={{ fontSize: 11.5, color: '#94A3B8', marginTop: 3 }}>{payload.message}</div>
                )}
            </div>
            <button onClick={onClose} style={{ background: 'none', border: 'none', cursor: 'pointer', color: '#475569', fontSize: 15, padding: 2, lineHeight: 1, flexShrink: 0 }}>
                <i className="ti ti-x" />
            </button>
        </div>
    );
}

export default function UserLayout({ children, title = 'الرئيسية' }) {
    const { auth, flash, badges } = usePage().props;
    const user = auth?.user;
    const [menuOpen,    setMenuOpen]    = useState(false);
    const [liveToast,   setLiveToast]   = useState(null);
    const [unreadNotif, setUnreadNotif] = useState(badges?.unread_notifications ?? 0);
    const [navOpen,     setNavOpen]     = useState(false);
    const currentPath = window.location.pathname;

    useEffect(() => { setUnreadNotif(badges?.unread_notifications ?? 0); }, [badges?.unread_notifications]);

    // Close the mobile nav whenever the route changes
    useEffect(() => {
        const close = () => setNavOpen(false);
        return router.on('navigate', close);
    }, []);

    const logout = (e) => {
        e.preventDefault();
        router.post('/user/logout');
    };

    const initials = user
        ? `${user.first_name?.[0] ?? ''}${user.last_name?.[0] ?? ''}`.toUpperCase()
        : '?';

    // Request browser notification permission + register service worker for FCM
    useEffect(() => {
        if (!('Notification' in window)) return;
        if (Notification.permission === 'default') {
            Notification.requestPermission().then(perm => {
                if (perm === 'granted') registerServiceWorker();
            });
        } else if (Notification.permission === 'granted') {
            registerServiceWorker();
        }
    }, []);

    function registerServiceWorker() {
        if (!('serviceWorker' in navigator)) return;
        navigator.serviceWorker.register('/sw.js').catch(() => {});
    }

    function saveFcmToken(token) {
        const csrf = document.querySelector('meta[name="csrf-token"]')?.content ?? '';
        fetch('/user/fcm-token', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf },
            body: JSON.stringify({ token }),
        }).catch(() => {});
    }

    // Real-time WebSocket listener
    useEffect(() => {
        if (!user?.id || typeof window.Echo === 'undefined') return;

        const pusher = window.Echo.connector?.pusher;
        const channel = window.Echo.private(`users.${user.id}.notifications`);

        channel.notification((payload) => {
            setLiveToast({ title: payload.title, message: payload.message ?? '' });
            setUnreadNotif(v => v + 1);
            setTimeout(() => setLiveToast(null), 6000);

            if ('Notification' in window && Notification.permission === 'granted') {
                new Notification(payload.title ?? 'Skillify', {
                    body: payload.message ?? '',
                    icon: '/favicon.ico',
                    tag:  'skillify-user',
                });
            }
        });

        return () => {
            window.Echo.leave(`users.${user.id}.notifications`);
        };
    }, [user?.id]);

    // Poll badge count every 30s as fallback when WebSocket is unavailable
    useEffect(() => {
        const id = setInterval(() => {
            router.reload({ only: ['badges'], preserveScroll: true, preserveState: true });
        }, 30000);
        return () => clearInterval(id);
    }, []);

    return (
        <div dir="rtl" style={{ fontFamily: "'Cairo', 'Inter', sans-serif", background: '#F8FAFC', minHeight: '100vh' }}>

            <LiveToast payload={liveToast} onClose={() => setLiveToast(null)} />

            {/* Header */}
            <header className="px-4 sm:px-6" style={{
                height: 60, background: '#fff',
                borderBottom: '0.5px solid rgba(0,0,0,0.07)',
                display: 'flex', alignItems: 'center', justifyContent: 'space-between',
                position: 'sticky', top: 0, zIndex: 90,
            }}>
                {/* Brand */}
                <div style={{ display: 'flex', alignItems: 'center', gap: 10, flexShrink: 0 }}>
                    <button onClick={() => setNavOpen(v => !v)} className="flex lg:hidden items-center justify-center" style={{ width: 32, height: 32, borderRadius: 8, border: 'none', background: '#F8FAFC', color: '#475569', fontSize: 16, cursor: 'pointer', flexShrink: 0 }}>
                        <i className={`ti ${navOpen ? 'ti-x' : 'ti-menu-2'}`} />
                    </button>
                    <div style={{
                        width: 34, height: 34, borderRadius: 10,
                        background: '#0D9488', display: 'flex', alignItems: 'center',
                        justifyContent: 'center', color: '#fff', fontSize: 18, flexShrink: 0,
                    }}>
                        <i className="ti ti-sparkles" />
                    </div>
                    <span className="hidden sm:inline" style={{ fontSize: 16, fontWeight: 700, letterSpacing: '0px' }}>Skillify</span>
                </div>

                {/* Nav — desktop */}
                <nav style={{ alignItems: 'center', gap: 2, overflowX: 'auto', flexShrink: 1 }} className="hidden lg:flex">
                    {NAV.map(({ href, icon, label }) => {
                        const active = currentPath === href || currentPath.startsWith(href + '/');
                        const isBell = href === '/user/notifications';
                        return (
                            <Link key={href} href={href} style={{
                                display: 'flex', alignItems: 'center', gap: 5,
                                padding: '7px 10px', borderRadius: 6,
                                fontSize: 12.5, whiteSpace: 'nowrap',
                                color: active ? '#134E4A' : '#475569',
                                background: active ? '#F0FDFA' : 'transparent',
                                fontWeight: active ? 600 : 400,
                                transition: 'background 0.12s, color 0.12s',
                                textDecoration: 'none', flexShrink: 0, position: 'relative',
                            }}>
                                <i className={`ti ${isBell && unreadNotif > 0 ? 'ti-bell-ringing' : icon}`} style={{ fontSize: 15, color: isBell && unreadNotif > 0 ? '#EA580C' : undefined }} />
                                {label}
                                {isBell && unreadNotif > 0 && (
                                    <span style={{ position: 'absolute', top: 2, right: 2, minWidth: 16, height: 16, borderRadius: 8, background: '#EF4444', color: '#fff', fontSize: 9, fontWeight: 700, display: 'flex', alignItems: 'center', justifyContent: 'center', padding: '0 3px' }}>
                                        {unreadNotif > 99 ? '99+' : unreadNotif}
                                    </span>
                                )}
                            </Link>
                        );
                    })}
                </nav>

                {/* User area */}
                <div style={{ display: 'flex', alignItems: 'center', gap: 12, flexShrink: 0 }}>
                    <div style={{ display: 'flex', alignItems: 'center', gap: 8 }}>
                        <div style={{
                            width: 34, height: 34, borderRadius: '50%',
                            background: '#0D9488', display: 'flex', alignItems: 'center',
                            justifyContent: 'center', fontSize: 13, fontWeight: 600, color: '#fff',
                            overflow: 'hidden', flexShrink: 0,
                        }}>
                            {user?.profile_photo
                                ? <img src={`/storage/${user.profile_photo}`} alt="avatar" style={{ width: '100%', height: '100%', objectFit: 'cover' }} />
                                : initials
                            }
                        </div>
                        <div className="hidden lg:block">
                            <div style={{ fontSize: 13, fontWeight: 500 }}>
                                {user?.first_name} {user?.last_name}
                            </div>
                            <div style={{ fontSize: 11, color: '#94A3B8' }}>{user?.email}</div>
                        </div>
                    </div>
                    <button onClick={logout} style={{
                        display: 'flex', alignItems: 'center', gap: 6,
                        padding: '7px 12px', borderRadius: 6,
                        fontSize: 13, color: '#475569',
                        background: 'none', border: 'none', cursor: 'pointer',
                        transition: 'background 0.12s',
                    }}
                        onMouseEnter={e => e.currentTarget.style.background = '#FEF2F2'}
                        onMouseLeave={e => e.currentTarget.style.background = 'none'}
                    >
                        <i className="ti ti-logout" style={{ fontSize: 16 }} />
                        <span className="hidden lg:inline">خروج</span>
                    </button>
                </div>
            </header>

            {/* Nav — mobile drawer */}
            {navOpen && (
                <>
                    <div onClick={() => setNavOpen(false)} className="lg:hidden fixed inset-0 z-[80] bg-black/30" style={{ top: 60 }} />
                    <nav className="lg:hidden fixed inset-x-0 z-[85] bg-white overflow-y-auto" style={{ top: 60, maxHeight: 'calc(100vh - 60px)', borderBottom: '0.5px solid rgba(0,0,0,0.07)', boxShadow: '0 12px 24px rgba(0,0,0,0.08)' }}>
                        <div style={{ padding: '8px 12px', display: 'flex', flexDirection: 'column', gap: 2 }}>
                            {NAV.map(({ href, icon, label }) => {
                                const active = currentPath === href || currentPath.startsWith(href + '/');
                                const isBell = href === '/user/notifications';
                                return (
                                    <Link key={href} href={href} style={{
                                        display: 'flex', alignItems: 'center', gap: 10,
                                        padding: '11px 14px', borderRadius: 8,
                                        fontSize: 14, position: 'relative',
                                        color: active ? '#134E4A' : '#475569',
                                        background: active ? '#F0FDFA' : 'transparent',
                                        fontWeight: active ? 600 : 400,
                                        textDecoration: 'none',
                                    }}>
                                        <i className={`ti ${isBell && unreadNotif > 0 ? 'ti-bell-ringing' : icon}`} style={{ fontSize: 17, color: isBell && unreadNotif > 0 ? '#EA580C' : undefined }} />
                                        {label}
                                        {isBell && unreadNotif > 0 && (
                                            <span style={{ marginRight: 'auto', minWidth: 18, height: 18, borderRadius: 9, background: '#EF4444', color: '#fff', fontSize: 10, fontWeight: 700, display: 'flex', alignItems: 'center', justifyContent: 'center', padding: '0 4px' }}>
                                                {unreadNotif > 99 ? '99+' : unreadNotif}
                                            </span>
                                        )}
                                    </Link>
                                );
                            })}
                            <div style={{ borderTop: '0.5px solid rgba(0,0,0,0.07)', margin: '6px 4px' }} />
                            <div style={{ padding: '8px 14px', fontSize: 13, color: '#475569' }}>
                                {user?.first_name} {user?.last_name} — {user?.email}
                            </div>
                            <button onClick={logout} style={{
                                display: 'flex', alignItems: 'center', gap: 10,
                                padding: '11px 14px', borderRadius: 8,
                                fontSize: 14, color: '#DC2626',
                                background: 'none', border: 'none', cursor: 'pointer', textAlign: 'right',
                            }}>
                                <i className="ti ti-logout" style={{ fontSize: 17 }} />
                                خروج
                            </button>
                        </div>
                    </nav>
                </>
            )}

            {/* Main */}
            <main className="px-4 py-5 sm:px-6 sm:py-6 lg:px-8" style={{ maxWidth: 1100, margin: '0 auto', display: 'flex', flexDirection: 'column', gap: 20 }}>
                {flash?.success && (
                    <div style={{ background: '#F0FDF4', border: '1px solid #9FE1CB', borderRadius: 10, padding: '11px 16px', color: '#134E4A', fontSize: 13, display: 'flex', alignItems: 'center', gap: 8 }}>
                        <i className="ti ti-circle-check" style={{ fontSize: 16, flexShrink: 0 }} />{flash.success}
                    </div>
                )}
                {flash?.error && (
                    <div style={{ background: '#FEF2F2', border: '1px solid #FECACA', borderRadius: 10, padding: '11px 16px', color: '#B91C1C', fontSize: 13, display: 'flex', alignItems: 'center', gap: 8 }}>
                        <i className="ti ti-alert-circle" style={{ fontSize: 16, flexShrink: 0 }} />{flash.error}
                    </div>
                )}
                {children}
            </main>
        </div>
    );
}
