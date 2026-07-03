import { Head, router, useForm } from '@inertiajs/react';
import { useState, useMemo } from 'react';
import SuperAdminLayout from '../../Layouts/SuperAdminLayout';

/* ── Guard config ─────────────────────────────────────────────── */
const GUARD_CFG = {
    admins:       { color: '#2563EB', bg: '#EFF6FF', border: '#BFDBFE', icon: 'ti-user-shield', label: 'المشرفون',        gradient: 'linear-gradient(135deg,#2563EB,#1D4ED8)' },
    super_admins: { color: '#7C3AED', bg: '#F5F3FF', border: '#DDD6FE', icon: 'ti-shield-lock',  label: 'المدراء العامون', gradient: 'linear-gradient(135deg,#7C3AED,#6D28D9)' },
    users:        { color: '#0D9488', bg: '#F0FDFA', border: '#9FE1CB', icon: 'ti-users',          label: 'المستخدمون',     gradient: 'linear-gradient(135deg,#0D9488,#0F766E)' },
};
const DEFAULT_CFG = { color: '#64748B', bg: '#F8FAFC', border: '#E2E8F0', icon: 'ti-key', label: 'أخرى', gradient: 'linear-gradient(135deg,#64748B,#475569)' };

/* ── Action config ────────────────────────────────────────────── */
const ACTION_CFG = {
    view:       { color: '#2563EB', bg: '#DBEAFE', label: 'عرض',     icon: 'ti-eye' },
    view_all:   { color: '#7C3AED', bg: '#EDE9FE', label: 'عرض الكل', icon: 'ti-eye-check' },
    view_no_services: { color: '#6D28D9', bg: '#EDE9FE', label: 'عرض (بدون خدمات)', icon: 'ti-eye-off' },
    create:     { color: '#059669', bg: '#D1FAE5', label: 'إنشاء',   icon: 'ti-plus' },
    edit:       { color: '#D97706', bg: '#FEF3C7', label: 'تعديل',   icon: 'ti-pencil' },
    update:     { color: '#D97706', bg: '#FEF3C7', label: 'تعديل',   icon: 'ti-pencil' },
    delete:     { color: '#DC2626', bg: '#FEE2E2', label: 'حذف',     icon: 'ti-trash' },
    approve:    { color: '#059669', bg: '#D1FAE5', label: 'قبول',    icon: 'ti-check' },
    reject:     { color: '#DC2626', bg: '#FEE2E2', label: 'رفض',     icon: 'ti-x' },
    toggle:     { color: '#D97706', bg: '#FEF3C7', label: 'تبديل',   icon: 'ti-switch' },
    show:       { color: '#0891B2', bg: '#CFFAFE', label: 'تفاصيل',  icon: 'ti-info-circle' },
    activate:   { color: '#059669', bg: '#D1FAE5', label: 'تفعيل',   icon: 'ti-player-play' },
    deactivate: { color: '#6B7280', bg: '#F3F4F6', label: 'إيقاف',   icon: 'ti-player-pause' },
};
const DEFAULT_ACTION = { color: '#475569', bg: '#F1F5F9', label: null, icon: 'ti-key' };

/* ── Module Arabic names ─────────────────────────────────────── */
const MODULE_LABELS = {
    users:                'المستخدمون',
    admins:               'المشرفون',
    businesses:           'حسابات الأعمال',
    services:             'الخدمات',
    categories:           'الفئات',
    subcategories:        'الفئات الفرعية',
    ads:                  'الإعلانات',
    posts:                'المنشورات',
    reports:              'البلاغات',
    cities:               'المدن',
    roles:                'الأدوار',
    permissions:          'الصلاحيات',
    blocked:              'المحظورون',
    employees:            'الموظفون',
    active_types:         'أنواع الأنشطة',
    active_type_businesses: 'أنواع أعمال',
    verifications:        'التحقق',
    notifications:        'الإشعارات',
};

/* ── Parse "module.action" ───────────────────────────────────── */
function parsePerm(name) {
    const dot = name.indexOf('.');
    if (dot === -1) return { module: name, action: '' };
    return { module: name.slice(0, dot), action: name.slice(dot + 1) };
}

/* ── Group perms by module ───────────────────────────────────── */
function groupByModule(perms) {
    return perms.reduce((acc, p) => {
        const { module } = parsePerm(p.name);
        if (!acc[module]) acc[module] = [];
        acc[module].push(p);
        return acc;
    }, {});
}

/* ── Input style ──────────────────────────────────────────────── */
const INPUT = {
    width: '100%', padding: '9px 13px',
    border: '1px solid rgba(0,0,0,0.11)', borderRadius: 9,
    fontSize: 13, outline: 'none', boxSizing: 'border-box',
    fontFamily: "'Cairo','Inter',sans-serif", background: '#FAFAFA',
};

/* ═══════════════════════════════════════════════════════════════ */
export default function Permissions({ permissions, guards }) {
    const [showCreate,  setShowCreate]  = useState(false);
    const [search,      setSearch]      = useState('');
    const [activeGuard, setActiveGuard] = useState('all');
    const [expandedGuards, setExpandedGuards] = useState({});
    const { data, setData, post, processing, errors, reset } = useForm({ name: '', guard_name: 'admins' });

    const submit = (e) => {
        e.preventDefault();
        post('/super-admin/permissions', { onSuccess: () => { reset(); setShowCreate(false); } });
    };

    const destroy = (id, name) => {
        if (!confirm(`حذف الصلاحية "${name}" نهائياً؟`)) return;
        router.delete(`/super-admin/permissions/${id}`, { preserveScroll: true });
    };

    const allPerms = permissions ?? [];

    /* group by guard */
    const byGuard = useMemo(() => allPerms.reduce((acc, p) => {
        if (!acc[p.guard_name]) acc[p.guard_name] = [];
        acc[p.guard_name].push(p);
        return acc;
    }, {}), [allPerms]);

    const guardKeys = Object.keys(byGuard);

    /* apply search + guard filter */
    const filtered = useMemo(() => {
        const q = search.trim().toLowerCase();
        return Object.fromEntries(
            Object.entries(byGuard)
                .map(([g, perms]) => [g, perms.filter(p =>
                    (activeGuard === 'all' || g === activeGuard) &&
                    (!q || p.name.toLowerCase().includes(q))
                )])
                .filter(([, perms]) => perms.length > 0)
        );
    }, [byGuard, search, activeGuard]);

    const totalFiltered = Object.values(filtered).reduce((s, p) => s + p.length, 0);

    const toggleGuard = (g) => setExpandedGuards(prev => ({ ...prev, [g]: !prev[g] }));

    return (
        <SuperAdminLayout title="الصلاحيات">
            <Head title="الصلاحيات — Skillify" />

            {/* ─── Page header ──────────────────────────────────── */}
            <div style={{ display: 'flex', alignItems: 'flex-start', justifyContent: 'space-between', flexWrap: 'wrap', gap: 12 }}>
                <div>
                    <h1 style={{ fontSize: 22, fontWeight: 800, color: '#1E1B4B', margin: 0, letterSpacing: -0.5 }}>الصلاحيات</h1>
                    <p style={{ fontSize: 12, color: '#94A3B8', marginTop: 4 }}>
                        {allPerms.length} صلاحية موزّعة على {guardKeys.length} حارس
                    </p>
                </div>
                <button onClick={() => setShowCreate(v => !v)} style={{
                    display: 'inline-flex', alignItems: 'center', gap: 7, padding: '9px 18px',
                    background: showCreate ? '#EDE9FE' : 'linear-gradient(135deg,#7C3AED,#6D28D9)',
                    color: showCreate ? '#6D28D9' : '#fff',
                    border: showCreate ? '1px solid #DDD6FE' : 'none',
                    borderRadius: 10, fontSize: 13, fontWeight: 700, cursor: 'pointer',
                    boxShadow: showCreate ? 'none' : '0 4px 14px rgba(124,58,237,0.30)',
                    fontFamily: "'Cairo','Inter',sans-serif", transition: 'all 0.15s',
                }}>
                    <i className={`ti ${showCreate ? 'ti-x' : 'ti-plus'}`} />
                    {showCreate ? 'إلغاء' : 'صلاحية جديدة'}
                </button>
            </div>

            {/* ─── Create form ──────────────────────────────────── */}
            {showCreate && (
                <div style={{ background: '#fff', border: '1px solid rgba(124,58,237,0.18)', borderRadius: 16, padding: 22, boxShadow: '0 4px 20px rgba(124,58,237,0.08)' }}>
                    <div style={{ display: 'flex', alignItems: 'center', gap: 10, marginBottom: 18 }}>
                        <div style={{ width: 34, height: 34, borderRadius: 9, background: 'linear-gradient(135deg,#7C3AED,#A78BFA)', display: 'flex', alignItems: 'center', justifyContent: 'center', color: '#fff', fontSize: 15 }}>
                            <i className="ti ti-plus" />
                        </div>
                        <span style={{ fontSize: 15, fontWeight: 700, color: '#1E1B4B' }}>إنشاء صلاحية جديدة</span>
                    </div>
                    <form onSubmit={submit}>
                        <div className="grid grid-cols-1 sm:grid-cols-2" style={{ display: 'grid', gap: 14 }}>
                            <div>
                                <label style={{ fontSize: 12, fontWeight: 600, color: '#374151', display: 'block', marginBottom: 6 }}>اسم الصلاحية *</label>
                                <input style={INPUT} value={data.name} onChange={e => setData('name', e.target.value)} placeholder="مثال: users.view" required />
                                {errors.name && <p style={{ fontSize: 11, color: '#EF4444', marginTop: 4 }}>{errors.name}</p>}
                            </div>
                            <div>
                                <label style={{ fontSize: 12, fontWeight: 600, color: '#374151', display: 'block', marginBottom: 6 }}>الحارس *</label>
                                <select style={INPUT} value={data.guard_name} onChange={e => setData('guard_name', e.target.value)}>
                                    {(guards ?? []).map(g => <option key={g} value={g}>{g}</option>)}
                                </select>
                            </div>
                        </div>
                        <div style={{ display: 'flex', gap: 9, justifyContent: 'flex-end', marginTop: 16 }}>
                            <button type="button" onClick={() => setShowCreate(false)} style={{ padding: '8px 16px', borderRadius: 9, border: '1px solid rgba(0,0,0,0.12)', background: 'none', fontSize: 13, cursor: 'pointer', fontFamily: "'Cairo','Inter',sans-serif" }}>إلغاء</button>
                            <button type="submit" disabled={processing} style={{ padding: '8px 20px', borderRadius: 9, background: 'linear-gradient(135deg,#7C3AED,#6D28D9)', color: '#fff', border: 'none', fontSize: 13, fontWeight: 700, cursor: 'pointer', opacity: processing ? 0.7 : 1, fontFamily: "'Cairo','Inter',sans-serif", boxShadow: '0 3px 10px rgba(124,58,237,0.28)' }}>
                                {processing ? 'جارٍ الإنشاء...' : 'إنشاء'}
                            </button>
                        </div>
                    </form>
                </div>
            )}

            {/* ─── Filter bar ───────────────────────────────────── */}
            <div style={{ display: 'flex', alignItems: 'center', gap: 10, flexWrap: 'wrap' }}>
                <div style={{ position: 'relative', flex: 1, minWidth: 200 }}>
                    <i className="ti ti-search" style={{ position: 'absolute', top: '50%', right: 12, transform: 'translateY(-50%)', fontSize: 15, color: '#94A3B8', pointerEvents: 'none' }} />
                    <input value={search} onChange={e => setSearch(e.target.value)} placeholder="بحث في الصلاحيات..." style={{ ...INPUT, paddingRight: 38 }} />
                </div>
                {[{ key: 'all', label: `الكل (${allPerms.length})`, color: '#1E1B4B', bg: '#EEF2FF', border: '#C7D2FE', icon: 'ti-apps' },
                  ...guardKeys.map(g => { const c = GUARD_CFG[g] ?? DEFAULT_CFG; return { key: g, label: `${c.label} (${(byGuard[g] ?? []).length})`, ...c }; })
                ].map(tab => (
                    <button key={tab.key} onClick={() => setActiveGuard(tab.key)} style={{
                        display: 'inline-flex', alignItems: 'center', gap: 5, padding: '7px 14px', borderRadius: 24,
                        border: `1px solid ${activeGuard === tab.key ? tab.color : 'rgba(0,0,0,0.10)'}`,
                        background: activeGuard === tab.key ? tab.bg : '#fff',
                        color: activeGuard === tab.key ? tab.color : '#64748B',
                        fontSize: 12, fontWeight: activeGuard === tab.key ? 700 : 500, cursor: 'pointer',
                        fontFamily: "'Cairo','Inter',sans-serif", transition: 'all 0.13s',
                    }}>
                        <i className={`ti ${tab.icon}`} style={{ fontSize: 13 }} />{tab.label}
                    </button>
                ))}
            </div>

            {(search || activeGuard !== 'all') && (
                <div style={{ fontSize: 12, color: '#94A3B8' }}>
                    عرض <strong style={{ color: '#1E1B4B' }}>{totalFiltered}</strong> صلاحية
                    {search && <> تطابق "<strong style={{ color: '#7C3AED' }}>{search}</strong>"</>}
                </div>
            )}

            {/* ─── Guard sections ───────────────────────────────── */}
            {!Object.keys(filtered).length ? (
                <div style={{ background: '#fff', border: '0.5px solid rgba(0,0,0,0.07)', borderRadius: 16, padding: '72px 24px', textAlign: 'center', color: '#94A3B8' }}>
                    <i className="ti ti-search-off" style={{ fontSize: 52, display: 'block', opacity: 0.12, marginBottom: 14 }} />
                    <div style={{ fontSize: 15, fontWeight: 600, color: '#64748B', marginBottom: 6 }}>لا توجد نتائج</div>
                    <p style={{ fontSize: 13 }}>جرّب تغيير كلمة البحث أو الفلتر.</p>
                </div>
            ) : Object.entries(filtered).map(([guard, perms]) => {
                const cfg = GUARD_CFG[guard] ?? DEFAULT_CFG;
                const modules = groupByModule(perms);
                const moduleKeys = Object.keys(modules).sort();
                const isCollapsed = expandedGuards[guard] === false;

                return (
                    <div key={guard} style={{ background: '#fff', border: `1px solid ${cfg.border}`, borderRadius: 18, overflow: 'hidden', boxShadow: '0 2px 12px rgba(0,0,0,0.04)' }}>

                        {/* Guard header */}
                        <div onClick={() => toggleGuard(guard)} style={{ padding: '16px 22px', background: cfg.gradient, display: 'flex', alignItems: 'center', justifyContent: 'space-between', cursor: 'pointer', userSelect: 'none' }}>
                            <div style={{ display: 'flex', alignItems: 'center', gap: 12 }}>
                                <div style={{ width: 38, height: 38, borderRadius: 10, background: 'rgba(255,255,255,0.18)', display: 'flex', alignItems: 'center', justifyContent: 'center', fontSize: 18, color: '#fff' }}>
                                    <i className={`ti ${cfg.icon}`} />
                                </div>
                                <div>
                                    <div style={{ fontSize: 15, fontWeight: 800, color: '#fff', letterSpacing: -0.3 }}>{cfg.label}</div>
                                    <div style={{ fontSize: 11, color: 'rgba(255,255,255,0.6)', marginTop: 1 }}>{guard} · {perms.length} صلاحية · {moduleKeys.length} وحدة</div>
                                </div>
                            </div>
                            <i className={`ti ${isCollapsed ? 'ti-chevron-down' : 'ti-chevron-up'}`} style={{ color: 'rgba(255,255,255,0.7)', fontSize: 18 }} />
                        </div>

                        {/* Module table */}
                        {!isCollapsed && (
                            <div style={{ padding: '4px 0', overflowX: 'auto' }}>
                                {moduleKeys.map((mod, mi) => {
                                    const modPerms = modules[mod];
                                    const modLabel = MODULE_LABELS[mod] ?? mod;
                                    return (
                                        <div key={mod} style={{
                                            display: 'grid', gridTemplateColumns: '200px 1fr auto',
                                            alignItems: 'center', gap: 16, padding: '13px 22px',
                                            minWidth: 560,
                                            borderBottom: mi < moduleKeys.length - 1 ? '0.5px solid rgba(0,0,0,0.05)' : 'none',
                                            transition: 'background 0.12s',
                                        }}
                                            onMouseEnter={e => e.currentTarget.style.background = '#FAFAFF'}
                                            onMouseLeave={e => e.currentTarget.style.background = 'transparent'}
                                        >
                                            {/* Module name */}
                                            <div style={{ display: 'flex', alignItems: 'center', gap: 9, flexShrink: 0 }}>
                                                <div style={{ width: 28, height: 28, borderRadius: 7, background: cfg.bg, border: `1px solid ${cfg.border}`, display: 'flex', alignItems: 'center', justifyContent: 'center', fontSize: 13, color: cfg.color, flexShrink: 0 }}>
                                                    <i className="ti ti-layout-grid" />
                                                </div>
                                                <div>
                                                    <div style={{ fontSize: 13, fontWeight: 700, color: '#0F172A' }}>{modLabel}</div>
                                                    <div style={{ fontSize: 10, color: '#94A3B8' }}>{mod}</div>
                                                </div>
                                            </div>

                                            {/* Action badges */}
                                            <div style={{ display: 'flex', flexWrap: 'wrap', gap: 6 }}>
                                                {modPerms.map(p => {
                                                    const { action } = parsePerm(p.name);
                                                    const ac = ACTION_CFG[action] ?? DEFAULT_ACTION;
                                                    return (
                                                        <div key={p.id} style={{
                                                            display: 'inline-flex', alignItems: 'center', gap: 4,
                                                            padding: '3px 9px', borderRadius: 20,
                                                            background: ac.bg, color: ac.color,
                                                            fontSize: 11, fontWeight: 700,
                                                            border: `1px solid ${ac.bg}`,
                                                            transition: 'all 0.12s',
                                                        }}>
                                                            <i className={`ti ${ac.icon}`} style={{ fontSize: 11 }} />
                                                            {ac.label ?? action}
                                                        </div>
                                                    );
                                                })}
                                            </div>

                                            {/* Delete buttons */}
                                            <div style={{ display: 'flex', gap: 4, flexShrink: 0 }}>
                                                {modPerms.map(p => {
                                                    const { action } = parsePerm(p.name);
                                                    const ac = ACTION_CFG[action] ?? DEFAULT_ACTION;
                                                    return (
                                                        <button key={p.id} onClick={() => destroy(p.id, p.name)} title={`حذف: ${p.name}`} style={{
                                                            width: 26, height: 26, borderRadius: 7, border: '1px solid #FEE2E2',
                                                            background: '#FFF5F5', color: '#DC2626',
                                                            display: 'flex', alignItems: 'center', justifyContent: 'center',
                                                            cursor: 'pointer', fontSize: 12, flexShrink: 0, transition: 'all 0.12s',
                                                        }}
                                                            onMouseEnter={e => { e.currentTarget.style.background = '#FEE2E2'; e.currentTarget.style.transform = 'scale(1.1)'; }}
                                                            onMouseLeave={e => { e.currentTarget.style.background = '#FFF5F5'; e.currentTarget.style.transform = 'scale(1)'; }}
                                                        >
                                                            <i className="ti ti-trash" />
                                                        </button>
                                                    );
                                                })}
                                            </div>
                                        </div>
                                    );
                                })}
                            </div>
                        )}
                    </div>
                );
            })}
        </SuperAdminLayout>
    );
}
