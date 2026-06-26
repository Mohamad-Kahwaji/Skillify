import { Link, usePage } from '@inertiajs/react';
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
        <div style={{
            position: 'fixed', bottom: 24, left: '50%', transform: 'translateX(-50%)',
            zIndex: 9999, minWidth: 300, maxWidth: 480,
            background: cfg.bg, border: `1px solid ${cfg.border}`,
            borderRadius: 12, padding: '12px 18px',
            display: 'flex', alignItems: 'center', gap: 10,
            boxShadow: '0 8px 32px rgba(0,0,0,0.14)',
            animation: 'slideUp 0.25s ease',
            fontFamily: "'Cairo','Inter',sans-serif",
        }}>
            <style>{`@keyframes slideUp{from{opacity:0;transform:translate(-50%,16px)}to{opacity:1;transform:translate(-50%,0)}}`}</style>
            <i className={`ti ${cfg.icon}`} style={{ fontSize: 18, color: cfg.color, flexShrink: 0 }} />
            <span style={{ fontSize: 13, fontWeight: 600, color: cfg.color, flex: 1 }}>{msg}</span>
            <button onClick={() => setVisible(false)} style={{ background: 'none', border: 'none', cursor: 'pointer', color: cfg.color, padding: 0, fontSize: 16, lineHeight: 1, opacity: 0.6 }}>
                <i className="ti ti-x" />
            </button>
        </div>
    );
}

const NAV = [
    { href: '/super-admin/dashboard',    icon: 'ti-layout-dashboard', label: 'لوحة التحكم' },
    { href: '/super-admin/admins',       icon: 'ti-user-shield',      label: 'المشرفون' },
    { href: '/super-admin/users',        icon: 'ti-users',            label: 'المستخدمون' },
    { href: '/super-admin/businesses',   icon: 'ti-briefcase',        label: 'حسابات الأعمال' },
    { href: '/super-admin/services',     icon: 'ti-tool',             label: 'الخدمات' },
    { href: '/super-admin/ads',          icon: 'ti-speakerphone',     label: 'الإعلانات' },
    { href: '/super-admin/posts',        icon: 'ti-file-text',        label: 'المنشورات' },
    { href: '/super-admin/roles',        icon: 'ti-key',              label: 'الأدوار' },
    { href: '/super-admin/permissions',  icon: 'ti-lock',             label: 'الصلاحيات' },
    { href: '/super-admin/identity-verifications', icon: 'ti-id-badge', label: 'توثيق الهوية' },
    { href: '/super-admin/notifications',icon: 'ti-bell',             label: 'الإشعارات' },
];

export default function SuperAdminLayout({ children, title }) {
    const { auth, flash } = usePage().props;
    const page = usePage();
    const current = typeof window !== 'undefined' ? window.location.pathname : '';
    const admin = auth?.admin;
    const initials = `${(admin?.first_name ?? 'S')[0]}${(admin?.last_name ?? 'A')[0]}`.toUpperCase();

    return (
        <div dir="rtl" style={{ display: 'flex', minHeight: '100vh', background: '#F0F2F8', fontFamily: "'Cairo', 'Inter', sans-serif" }}>

            {/* ══════════════ Sidebar ══════════════ */}
            <aside style={{
                width: 234, background: 'linear-gradient(180deg, #16124A 0%, #1E1B4B 40%, #1A1845 100%)',
                display: 'flex', flexDirection: 'column',
                position: 'fixed', top: 0, right: 0, bottom: 0, zIndex: 40,
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
                <nav style={{ flex: 1, overflowY: 'auto', padding: '12px 12px' }}>
                    {NAV.map(({ href, icon, label }) => {
                        const active = current === href || current.startsWith(href + '/');
                        return (
                            <Link key={href} href={href} style={{
                                display: 'flex', alignItems: 'center', gap: 11,
                                padding: '9px 12px', borderRadius: 9, marginBottom: 3,
                                fontSize: 13, fontWeight: active ? 700 : 400,
                                background: active ? 'rgba(167,139,250,0.18)' : 'transparent',
                                color: active ? '#C4B5FD' : 'rgba(148,163,184,0.75)',
                                textDecoration: 'none',
                                borderRight: active ? '3px solid #A78BFA' : '3px solid transparent',
                                transition: 'all 0.15s',
                            }}
                                onMouseEnter={e => { if (!active) { e.currentTarget.style.background = 'rgba(255,255,255,0.05)'; e.currentTarget.style.color = '#C4B5FD'; } }}
                                onMouseLeave={e => { if (!active) { e.currentTarget.style.background = 'transparent'; e.currentTarget.style.color = 'rgba(148,163,184,0.75)'; } }}
                            >
                                <div style={{
                                    width: 30, height: 30, borderRadius: 7, flexShrink: 0,
                                    display: 'flex', alignItems: 'center', justifyContent: 'center',
                                    background: active ? 'rgba(167,139,250,0.20)' : 'rgba(255,255,255,0.04)',
                                    color: active ? '#A78BFA' : 'inherit',
                                    fontSize: 15,
                                }}>
                                    <i className={`ti ${icon}`} />
                                </div>
                                {label}
                            </Link>
                        );
                    })}
                </nav>

                {/* User profile */}
                <div style={{ padding: '14px 16px', borderTop: '0.5px solid rgba(167,139,250,0.12)' }}>
                    <div style={{ display: 'flex', alignItems: 'center', gap: 10, marginBottom: 12 }}>
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
                    </div>
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
            <div style={{ marginRight: 234, flex: 1, display: 'flex', flexDirection: 'column', minWidth: 0 }}>

                {/* Header */}
                <header style={{
                    background: '#fff',
                    borderBottom: '0.5px solid rgba(0,0,0,0.07)',
                    padding: '0 28px', height: 58,
                    display: 'flex', alignItems: 'center', justifyContent: 'space-between',
                    position: 'sticky', top: 0, zIndex: 30,
                    boxShadow: '0 1px 8px rgba(0,0,0,0.05)',
                }}>
                    <div style={{ display: 'flex', alignItems: 'center', gap: 8 }}>
                        <div style={{ width: 3, height: 18, borderRadius: 2, background: 'linear-gradient(180deg,#7C3AED,#A78BFA)' }} />
                        <span style={{ fontSize: 15, fontWeight: 700, color: '#1E1B4B' }}>
                            {title ?? 'لوحة التحكم'}
                        </span>
                    </div>
                    <div style={{ display: 'flex', alignItems: 'center', gap: 14 }}>
                        <div style={{
                            display: 'flex', alignItems: 'center', gap: 9,
                            padding: '6px 12px', borderRadius: 10,
                            background: '#F5F3FF', border: '0.5px solid rgba(167,139,250,0.25)',
                        }}>
                            <div style={{
                                width: 26, height: 26, borderRadius: '50%',
                                background: 'linear-gradient(135deg,#7C3AED,#A78BFA)',
                                display: 'flex', alignItems: 'center', justifyContent: 'center',
                                fontSize: 10, fontWeight: 700, color: '#fff', flexShrink: 0,
                            }}>
                                {initials}
                            </div>
                            <span style={{ fontSize: 12, fontWeight: 600, color: '#4C1D95' }}>
                                {admin?.first_name ?? 'المدير'} {admin?.last_name ?? 'العام'}
                            </span>
                        </div>
                    </div>
                </header>

                <main style={{ flex: 1, padding: '26px 28px' }}>
                    <Toast flash={flash} />
                    <div style={{ display: 'flex', flexDirection: 'column', gap: 22 }}>
                        {children}
                    </div>
                </main>
            </div>
        </div>
    );
}
