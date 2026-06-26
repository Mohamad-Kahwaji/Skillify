import { Link, usePage, router } from '@inertiajs/react';
import { useState } from 'react';

const NAV = [
    { href: '/user/dashboard',       icon: 'ti-home',           label: 'الرئيسية' },
    { href: '/user/explore',         icon: 'ti-search',         label: 'استكشاف' },
    { href: '/user/services',        icon: 'ti-briefcase',      label: 'الخدمات' },
    { href: '/user/community-posts', icon: 'ti-users',          label: 'المجتمع' },
    { href: '/user/conversations',   icon: 'ti-message-circle', label: 'الرسائل' },
    { href: '/user/posts',           icon: 'ti-file-text',      label: 'منشوراتي' },
    { href: '/user/profile',         icon: 'ti-user-edit',      label: 'ملفي الشخصي' },
];

export default function UserLayout({ children, title = 'الرئيسية' }) {
    const { auth, flash } = usePage().props;
    const user = auth?.user;
    const [menuOpen, setMenuOpen] = useState(false);
    const currentPath = window.location.pathname;

    const logout = (e) => {
        e.preventDefault();
        router.post('/user/logout');
    };

    const initials = user
        ? `${user.first_name?.[0] ?? ''}${user.last_name?.[0] ?? ''}`.toUpperCase()
        : '?';

    return (
        <div dir="rtl" style={{ fontFamily: "'Cairo', 'Inter', sans-serif", background: '#F8FAFC', minHeight: '100vh' }}>

            {/* Header */}
            <header style={{
                height: 60, background: '#fff',
                borderBottom: '0.5px solid rgba(0,0,0,0.07)',
                display: 'flex', alignItems: 'center', justifyContent: 'space-between',
                padding: '0 24px', position: 'sticky', top: 0, zIndex: 90,
            }}>
                {/* Brand */}
                <div style={{ display: 'flex', alignItems: 'center', gap: 10 }}>
                    <div style={{
                        width: 34, height: 34, borderRadius: 10,
                        background: '#0D9488', display: 'flex', alignItems: 'center',
                        justifyContent: 'center', color: '#fff', fontSize: 18,
                    }}>
                        <i className="ti ti-sparkles" />
                    </div>
                    <span style={{ fontSize: 16, fontWeight: 700, letterSpacing: '0px' }}>Skillify</span>
                </div>

                {/* Nav — desktop */}
                <nav style={{ display: 'flex', alignItems: 'center', gap: 4 }} className="hide-mobile">
                    {NAV.map(({ href, icon, label }) => {
                        const active = currentPath === href || currentPath.startsWith(href + '/');
                        return (
                            <Link key={href} href={href} style={{
                                display: 'flex', alignItems: 'center', gap: 6,
                                padding: '7px 12px', borderRadius: 6,
                                fontSize: 13,
                                color: active ? '#134E4A' : '#475569',
                                background: active ? '#F0FDFA' : 'transparent',
                                fontWeight: active ? 600 : 400,
                                transition: 'background 0.12s, color 0.12s',
                                textDecoration: 'none',
                            }}>
                                <i className={`ti ${icon}`} style={{ fontSize: 16 }} />
                                {label}
                            </Link>
                        );
                    })}
                </nav>

                {/* User area */}
                <div style={{ display: 'flex', alignItems: 'center', gap: 12 }}>
                    <div style={{ display: 'flex', alignItems: 'center', gap: 8 }}>
                        <div style={{
                            width: 34, height: 34, borderRadius: '50%',
                            background: '#0D9488', display: 'flex', alignItems: 'center',
                            justifyContent: 'center', fontSize: 13, fontWeight: 600, color: '#fff',
                        }}>
                            {initials}
                        </div>
                        <div className="hide-mobile">
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
                        <span className="hide-mobile">خروج</span>
                    </button>
                </div>
            </header>

            {/* Main */}
            <main style={{ maxWidth: 960, margin: '0 auto', padding: '28px 24px', display: 'flex', flexDirection: 'column', gap: 20 }}>
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

            <style>{`
                .hide-mobile { display: flex !important; }
                @media(max-width: 768px) { .hide-mobile { display: none !important; } }
            `}</style>
        </div>
    );
}
