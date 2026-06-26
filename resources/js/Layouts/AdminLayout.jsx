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
    { href: '/admin/dashboard',               icon: 'ti-layout-dashboard', label: 'لوحة التحكم' },
    { href: '/admin/users',                   icon: 'ti-users',            label: 'المستخدمون' },
    { href: '/admin/workers',                 icon: 'ti-briefcase',        label: 'المزودون' },
    { href: '/admin/verifications',           icon: 'ti-briefcase',        label: 'تحقق الأعمال' },
    { href: '/admin/identity-verifications',  icon: 'ti-id-badge',         label: 'توثيق الهوية' },
    { href: '/admin/services',                icon: 'ti-tool',             label: 'الخدمات' },
    { href: '/admin/posts',                   icon: 'ti-file-text',        label: 'المنشورات' },
    { href: '/admin/reports',                 icon: 'ti-flag',             label: 'البلاغات' },
    { href: '/admin/ads',                     icon: 'ti-speakerphone',     label: 'الإعلانات' },
    { href: '/admin/categories',              icon: 'ti-tag',              label: 'الفئات' },
    { href: '/admin/subcategories',           icon: 'ti-tags',             label: 'الفئات الفرعية' },
    { href: '/admin/active-types',            icon: 'ti-activity',         label: 'أنواع النشاط' },
    { href: '/admin/active-type-businesses',  icon: 'ti-building',         label: 'أنواع الأعمال' },
    { href: '/admin/cities',                  icon: 'ti-map-pin',          label: 'المدن' },
    { href: '/admin/blocked',                 icon: 'ti-ban',              label: 'المحظورون' },
];

export default function AdminLayout({ children, title }) {
    const { auth, flash } = usePage().props;
    const current = typeof window !== 'undefined' ? window.location.pathname : '';
    const admin = auth?.admin;

    return (
        <div dir="rtl" style={{ display: 'flex', minHeight: '100vh', background: '#F1F5F9', fontFamily: "'Cairo', 'Inter', sans-serif" }}>
            {/* Sidebar — على اليمين */}
            <aside style={{
                width: 220, background: '#0F172A', color: '#CBD5E1',
                display: 'flex', flexDirection: 'column', position: 'fixed', top: 0, right: 0, bottom: 0, zIndex: 40,
            }}>
                {/* Logo */}
                <div style={{ padding: '20px 18px 14px', borderBottom: '0.5px solid rgba(255,255,255,0.08)' }}>
                    <div style={{ fontSize: 18, fontWeight: 800, color: '#fff', letterSpacing: -0.5 }}>
                        <span style={{ color: '#0D9488' }}>Skill</span>ify
                        <span style={{ fontSize: 9, fontWeight: 500, color: '#475569', marginRight: 8, letterSpacing: 0 }}>إدارة</span>
                    </div>
                </div>

                {/* Nav */}
                <nav style={{ flex: 1, overflowY: 'auto', padding: '10px 10px' }}>
                    {NAV.map(({ href, icon, label }) => {
                        const active = current === href || current.startsWith(href + '/');
                        return (
                            <Link key={href} href={href} style={{
                                display: 'flex', alignItems: 'center', gap: 10,
                                padding: '8px 10px', borderRadius: 8, marginBottom: 2,
                                fontSize: 12, fontWeight: active ? 600 : 400,
                                background: active ? 'rgba(13,148,136,0.18)' : 'none',
                                color: active ? '#2DD4BF' : '#94A3B8',
                                textDecoration: 'none', transition: 'all .1s',
                            }}
                                onMouseEnter={e => { if (!active) { e.currentTarget.style.background = 'rgba(255,255,255,0.05)'; e.currentTarget.style.color = '#CBD5E1'; } }}
                                onMouseLeave={e => { if (!active) { e.currentTarget.style.background = 'none'; e.currentTarget.style.color = '#94A3B8'; } }}
                            >
                                <i className={`ti ${icon}`} style={{ fontSize: 16, width: 18, textAlign: 'center' }} />
                                {label}
                            </Link>
                        );
                    })}
                </nav>

                {/* Footer */}
                <div style={{ padding: '12px 14px', borderTop: '0.5px solid rgba(255,255,255,0.08)' }}>
                    <div style={{ fontSize: 11, color: '#475569', marginBottom: 6 }}>{admin?.email ?? 'admin'}</div>
                    <form method="POST" action="/admin/logout" style={{ margin: 0 }}>
                        <input type="hidden" name="_token" value={document.querySelector('meta[name="csrf-token"]')?.content ?? ''} />
                        <button type="submit" style={{ display: 'flex', alignItems: 'center', gap: 6, color: '#EF4444', background: 'none', border: 'none', cursor: 'pointer', fontSize: 12, padding: 0 }}>
                            <i className="ti ti-logout" /> تسجيل الخروج
                        </button>
                    </form>
                </div>
            </aside>

            {/* Main */}
            <div style={{ marginRight: 220, flex: 1, display: 'flex', flexDirection: 'column', minHeight: '100vh' }}>
                {/* Topbar */}
                <header style={{ background: '#fff', borderBottom: '0.5px solid rgba(0,0,0,0.07)', padding: '0 24px', height: 54, display: 'flex', alignItems: 'center', justifyContent: 'space-between', position: 'sticky', top: 0, zIndex: 30 }}>
                    <div style={{ fontSize: 14, fontWeight: 600, color: '#0F172A' }}>{title ?? 'لوحة الإدارة'}</div>
                    <div style={{ fontSize: 12, color: '#94A3B8' }}>
                        <i className="ti ti-user-circle" style={{ marginLeft: 4 }} />
                        {admin?.first_name ?? 'المشرف'}
                    </div>
                </header>

                {/* Content */}
                <main style={{ flex: 1, padding: '24px' }}>
                    <Toast flash={flash} />
                    <div style={{ display: 'flex', flexDirection: 'column', gap: 18 }}>
                        {children}
                    </div>
                </main>
            </div>
        </div>
    );
}
