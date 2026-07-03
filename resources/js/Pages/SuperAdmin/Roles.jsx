import { Head, router, useForm } from '@inertiajs/react';
import { useState, useMemo } from 'react';
import SuperAdminLayout from '../../Layouts/SuperAdminLayout';

/* ── Guard config ─────────────────────────────────────────────── */
const GUARD_CFG = {
    admins:       { color: '#2563EB', bg: '#EFF6FF', border: '#BFDBFE', icon: 'ti-user-shield',  label: 'المشرفون',        gradient: 'linear-gradient(135deg,#2563EB,#1D4ED8)' },
    super_admins: { color: '#7C3AED', bg: '#F5F3FF', border: '#DDD6FE', icon: 'ti-shield-lock',   label: 'المدراء العامون', gradient: 'linear-gradient(135deg,#7C3AED,#6D28D9)' },
    users:        { color: '#0D9488', bg: '#F0FDFA', border: '#9FE1CB', icon: 'ti-users',          label: 'المستخدمون',     gradient: 'linear-gradient(135deg,#0D9488,#0F766E)' },
};
const DEFAULT_CFG = { color: '#64748B', bg: '#F8FAFC', border: '#E2E8F0', icon: 'ti-key', label: 'أخرى', gradient: 'linear-gradient(135deg,#64748B,#475569)' };

/* ── Action badge config ──────────────────────────────────────── */
const ACTION_CFG = {
    view:             { color: '#2563EB', bg: '#DBEAFE', label: 'عرض',            icon: 'ti-eye' },
    view_all:         { color: '#7C3AED', bg: '#EDE9FE', label: 'عرض الكل',       icon: 'ti-eye-check' },
    view_no_services: { color: '#6D28D9', bg: '#EDE9FE', label: 'عرض مقيّد',      icon: 'ti-eye-off' },
    create:           { color: '#059669', bg: '#D1FAE5', label: 'إنشاء',          icon: 'ti-plus' },
    edit:             { color: '#D97706', bg: '#FEF3C7', label: 'تعديل',          icon: 'ti-pencil' },
    update:           { color: '#D97706', bg: '#FEF3C7', label: 'تعديل',          icon: 'ti-pencil' },
    delete:           { color: '#DC2626', bg: '#FEE2E2', label: 'حذف',            icon: 'ti-trash' },
    approve:          { color: '#059669', bg: '#D1FAE5', label: 'قبول',           icon: 'ti-check' },
    reject:           { color: '#DC2626', bg: '#FEE2E2', label: 'رفض',            icon: 'ti-x' },
    toggle:           { color: '#D97706', bg: '#FEF3C7', label: 'تبديل',          icon: 'ti-switch' },
    show:             { color: '#0891B2', bg: '#CFFAFE', label: 'تفاصيل',         icon: 'ti-info-circle' },
    activate:         { color: '#059669', bg: '#D1FAE5', label: 'تفعيل',          icon: 'ti-player-play' },
    deactivate:       { color: '#6B7280', bg: '#F3F4F6', label: 'إيقاف',          icon: 'ti-player-pause' },
};
const DEFAULT_ACTION = { color: '#475569', bg: '#F1F5F9', label: null, icon: 'ti-key' };

/* ── Module Arabic names ─────────────────────────────────────── */
const MODULE_LABELS = {
    users: 'المستخدمون', admins: 'المشرفون', businesses: 'حسابات الأعمال',
    services: 'الخدمات', categories: 'الفئات', subcategories: 'الفئات الفرعية',
    ads: 'الإعلانات', posts: 'المنشورات', reports: 'البلاغات', cities: 'المدن',
    roles: 'الأدوار', permissions: 'الصلاحيات', blocked: 'المحظورون',
    employees: 'الموظفون', active_types: 'أنواع الأنشطة',
    active_type_businesses: 'أنواع أعمال', verifications: 'التحقق',
    notifications: 'الإشعارات',
};

function parsePerm(name) {
    const dot = name.indexOf('.');
    return dot === -1 ? { module: name, action: '' } : { module: name.slice(0, dot), action: name.slice(dot + 1) };
}

function groupByModule(perms) {
    return perms.reduce((acc, p) => {
        const { module } = parsePerm(p.name);
        if (!acc[module]) acc[module] = [];
        acc[module].push(p);
        return acc;
    }, {});
}

/* ── Shared input style ───────────────────────────────────────── */
const INPUT = {
    width: '100%', padding: '9px 13px', border: '1px solid rgba(0,0,0,0.11)',
    borderRadius: 9, fontSize: 13, outline: 'none', boxSizing: 'border-box',
    fontFamily: "'Cairo','Inter',sans-serif", background: '#FAFAFA',
};

/* ── Permission toggle button in forms ───────────────────────── */
function PermToggle({ perm, checked, onClick }) {
    const { action } = parsePerm(perm.name);
    const ac = ACTION_CFG[action] ?? DEFAULT_ACTION;
    return (
        <button type="button" onClick={onClick} style={{
            display: 'inline-flex', alignItems: 'center', gap: 5,
            padding: '5px 11px', borderRadius: 20, fontSize: 11.5, cursor: 'pointer',
            border: `1px solid ${checked ? ac.color : 'rgba(0,0,0,0.10)'}`,
            background: checked ? ac.bg : '#F8FAFC',
            color: checked ? ac.color : '#94A3B8',
            fontWeight: checked ? 700 : 400,
            transition: 'all 0.13s',
            fontFamily: "'Cairo','Inter',sans-serif",
        }}>
            <i className={`ti ${checked ? 'ti-check' : ac.icon}`} style={{ fontSize: 10 }} />
            {perm.name}
        </button>
    );
}

/* ── Role card (view mode) ────────────────────────────────────── */
function RoleCard({ role, onEdit, onDelete }) {
    const [expanded, setExpanded] = useState(true);
    const cfg = GUARD_CFG[role.guard_name] ?? DEFAULT_CFG;
    const perms = role.permissions ?? [];
    const modules = useMemo(() => groupByModule(perms), [perms]);
    const moduleKeys = Object.keys(modules).sort();

    return (
        <div style={{ background: '#fff', border: `1px solid ${cfg.border}`, borderRadius: 18, overflow: 'hidden', boxShadow: '0 2px 12px rgba(0,0,0,0.04)' }}>

            {/* Card header */}
            <div style={{ display: 'flex', alignItems: 'center', justifyContent: 'space-between', padding: '15px 20px', background: cfg.gradient, cursor: 'pointer' }}
                onClick={() => setExpanded(v => !v)}>
                <div style={{ display: 'flex', alignItems: 'center', gap: 12 }}>
                    <div style={{ width: 40, height: 40, borderRadius: 11, background: 'rgba(255,255,255,0.18)', display: 'flex', alignItems: 'center', justifyContent: 'center', fontSize: 18, color: '#fff', flexShrink: 0 }}>
                        <i className={`ti ${cfg.icon}`} />
                    </div>
                    <div>
                        <div style={{ fontSize: 16, fontWeight: 800, color: '#fff', letterSpacing: -0.3 }}>{role.name}</div>
                        <div style={{ fontSize: 11, color: 'rgba(255,255,255,0.6)', marginTop: 1 }}>
                            {cfg.label} · {perms.length} صلاحية · {moduleKeys.length} وحدة
                        </div>
                    </div>
                </div>
                <div style={{ display: 'flex', alignItems: 'center', gap: 8 }}>
                    <button onClick={e => { e.stopPropagation(); onEdit(role); }} style={{
                        width: 32, height: 32, borderRadius: 8, border: '1px solid rgba(255,255,255,0.30)',
                        background: 'rgba(255,255,255,0.15)', color: '#fff', fontSize: 14,
                        display: 'flex', alignItems: 'center', justifyContent: 'center', cursor: 'pointer',
                        transition: 'background 0.13s',
                    }}
                        onMouseEnter={e => e.currentTarget.style.background = 'rgba(255,255,255,0.28)'}
                        onMouseLeave={e => e.currentTarget.style.background = 'rgba(255,255,255,0.15)'}
                        title="تعديل"
                    >
                        <i className="ti ti-pencil" />
                    </button>
                    <button onClick={e => { e.stopPropagation(); onDelete(role.id); }} style={{
                        width: 32, height: 32, borderRadius: 8, border: '1px solid rgba(239,68,68,0.40)',
                        background: 'rgba(239,68,68,0.18)', color: '#FCA5A5', fontSize: 14,
                        display: 'flex', alignItems: 'center', justifyContent: 'center', cursor: 'pointer',
                        transition: 'background 0.13s',
                    }}
                        onMouseEnter={e => e.currentTarget.style.background = 'rgba(239,68,68,0.32)'}
                        onMouseLeave={e => e.currentTarget.style.background = 'rgba(239,68,68,0.18)'}
                        title="حذف"
                    >
                        <i className="ti ti-trash" />
                    </button>
                    <i className={`ti ${expanded ? 'ti-chevron-up' : 'ti-chevron-down'}`} style={{ color: 'rgba(255,255,255,0.6)', fontSize: 17 }} />
                </div>
            </div>

            {/* Permissions table */}
            {expanded && (
                !perms.length ? (
                    <div style={{ padding: '28px 20px', textAlign: 'center', color: '#94A3B8', fontSize: 13 }}>
                        <i className="ti ti-lock-off" style={{ fontSize: 32, display: 'block', opacity: 0.15, marginBottom: 8 }} />
                        لا توجد صلاحيات مُعيّنة لهذا الدور
                    </div>
                ) : (
                    <div style={{ padding: '4px 0', overflowX: 'auto' }}>
                        {moduleKeys.map((mod, mi) => {
                            const modPerms = modules[mod];
                            const modLabel = MODULE_LABELS[mod] ?? mod;
                            return (
                                <div key={mod} style={{
                                    display: 'grid', gridTemplateColumns: '200px 1fr',
                                    alignItems: 'center', gap: 14, padding: '11px 20px',
                                    minWidth: 480,
                                    borderBottom: mi < moduleKeys.length - 1 ? '0.5px solid rgba(0,0,0,0.05)' : 'none',
                                    transition: 'background 0.12s',
                                }}
                                    onMouseEnter={e => e.currentTarget.style.background = '#FAFAFF'}
                                    onMouseLeave={e => e.currentTarget.style.background = 'transparent'}
                                >
                                    {/* Module label */}
                                    <div style={{ display: 'flex', alignItems: 'center', gap: 9, flexShrink: 0 }}>
                                        <div style={{ width: 26, height: 26, borderRadius: 6, background: cfg.bg, border: `1px solid ${cfg.border}`, display: 'flex', alignItems: 'center', justifyContent: 'center', fontSize: 12, color: cfg.color, flexShrink: 0 }}>
                                            <i className="ti ti-layout-grid" />
                                        </div>
                                        <div>
                                            <div style={{ fontSize: 12.5, fontWeight: 700, color: '#0F172A' }}>{modLabel}</div>
                                            <div style={{ fontSize: 10, color: '#94A3B8' }}>{mod}</div>
                                        </div>
                                    </div>

                                    {/* Action badges */}
                                    <div style={{ display: 'flex', flexWrap: 'wrap', gap: 5 }}>
                                        {modPerms.map(p => {
                                            const { action } = parsePerm(p.name);
                                            const ac = ACTION_CFG[action] ?? DEFAULT_ACTION;
                                            return (
                                                <span key={p.id} style={{
                                                    display: 'inline-flex', alignItems: 'center', gap: 4,
                                                    padding: '3px 9px', borderRadius: 20,
                                                    background: ac.bg, color: ac.color,
                                                    fontSize: 11, fontWeight: 700,
                                                }}>
                                                    <i className={`ti ${ac.icon}`} style={{ fontSize: 10 }} />
                                                    {ac.label ?? action}
                                                </span>
                                            );
                                        })}
                                    </div>
                                </div>
                            );
                        })}
                    </div>
                )
            )}
        </div>
    );
}

/* ── Permission picker (grouped by module) in forms ──────────── */
function PermPicker({ availablePerms, selectedIds, onToggle }) {
    const [modSearch, setModSearch] = useState('');
    const modules = useMemo(() => groupByModule(availablePerms), [availablePerms]);
    const moduleKeys = Object.keys(modules).sort().filter(m =>
        !modSearch || m.toLowerCase().includes(modSearch.toLowerCase()) ||
        (MODULE_LABELS[m] ?? '').includes(modSearch)
    );
    const selectedCount = selectedIds.length;
    const totalCount    = availablePerms.length;

    const selectAll   = () => onToggle(availablePerms.map(p => p.id), true);
    const deselectAll = () => onToggle(availablePerms.map(p => p.id), false);

    return (
        <div>
            <div style={{ display: 'flex', alignItems: 'center', justifyContent: 'space-between', marginBottom: 10 }}>
                <label style={{ fontSize: 12, fontWeight: 700, color: '#374151' }}>
                    تعيين الصلاحيات
                    <span style={{ fontSize: 11, color: '#7C3AED', marginRight: 6, background: '#F5F3FF', padding: '1px 8px', borderRadius: 20 }}>
                        {selectedCount} / {totalCount}
                    </span>
                </label>
                <div style={{ display: 'flex', gap: 6 }}>
                    <button type="button" onClick={selectAll} style={{ fontSize: 11, color: '#059669', background: '#D1FAE5', border: 'none', borderRadius: 6, padding: '3px 10px', cursor: 'pointer', fontFamily: "'Cairo','Inter',sans-serif" }}>تحديد الكل</button>
                    <button type="button" onClick={deselectAll} style={{ fontSize: 11, color: '#DC2626', background: '#FEE2E2', border: 'none', borderRadius: 6, padding: '3px 10px', cursor: 'pointer', fontFamily: "'Cairo','Inter',sans-serif" }}>إلغاء الكل</button>
                </div>
            </div>
            <div style={{ position: 'relative', marginBottom: 10 }}>
                <i className="ti ti-search" style={{ position: 'absolute', top: '50%', right: 10, transform: 'translateY(-50%)', color: '#94A3B8', fontSize: 13, pointerEvents: 'none' }} />
                <input value={modSearch} onChange={e => setModSearch(e.target.value)} placeholder="بحث عن وحدة..."
                    style={{ ...INPUT, paddingRight: 32, fontSize: 12 }} />
            </div>
            <div style={{ border: '1px solid rgba(0,0,0,0.08)', borderRadius: 12, overflow: 'hidden', maxHeight: 340, overflowY: 'auto' }}>
                {moduleKeys.map((mod, mi) => {
                    const modPerms = modules[mod];
                    const allChecked = modPerms.every(p => selectedIds.includes(p.id));
                    const someChecked = modPerms.some(p => selectedIds.includes(p.id));
                    return (
                        <div key={mod} style={{ borderBottom: mi < moduleKeys.length - 1 ? '0.5px solid rgba(0,0,0,0.06)' : 'none' }}>
                            {/* Module row */}
                            <div style={{ display: 'flex', alignItems: 'center', justifyContent: 'space-between', padding: '9px 14px', background: '#F8FAFC', cursor: 'pointer' }}
                                onClick={() => onToggle(modPerms.map(p => p.id), !allChecked)}>
                                <div style={{ display: 'flex', alignItems: 'center', gap: 8 }}>
                                    <div style={{
                                        width: 16, height: 16, borderRadius: 4, border: `1.5px solid ${allChecked ? '#7C3AED' : someChecked ? '#7C3AED' : '#CBD5E1'}`,
                                        background: allChecked ? '#7C3AED' : someChecked ? '#EDE9FE' : '#fff',
                                        display: 'flex', alignItems: 'center', justifyContent: 'center', flexShrink: 0,
                                    }}>
                                        {allChecked && <i className="ti ti-check" style={{ fontSize: 9, color: '#fff' }} />}
                                        {!allChecked && someChecked && <div style={{ width: 6, height: 2, background: '#7C3AED', borderRadius: 1 }} />}
                                    </div>
                                    <span style={{ fontSize: 12.5, fontWeight: 700, color: '#0F172A' }}>{MODULE_LABELS[mod] ?? mod}</span>
                                    <span style={{ fontSize: 10, color: '#94A3B8' }}>{mod}</span>
                                </div>
                                <span style={{ fontSize: 10, color: '#94A3B8' }}>{modPerms.filter(p => selectedIds.includes(p.id)).length}/{modPerms.length}</span>
                            </div>
                            {/* Perms */}
                            <div style={{ display: 'flex', flexWrap: 'wrap', gap: 6, padding: '8px 14px 10px' }}>
                                {modPerms.map(p => (
                                    <PermToggle key={p.id} perm={p}
                                        checked={selectedIds.includes(p.id)}
                                        onClick={() => onToggle([p.id], !selectedIds.includes(p.id))}
                                    />
                                ))}
                            </div>
                        </div>
                    );
                })}
            </div>
        </div>
    );
}

/* ═══════════════════════════════════════════════════════════════ */
export default function Roles({ roles, permissions, guards }) {
    const [showCreate, setShowCreate] = useState(false);
    const [editId,     setEditId]     = useState(null);

    const createForm = useForm({ name: '', guard_name: 'admins', permissions: [] });
    const editForm   = useForm({ name: '', permissions: [] });

    const guardPerms = (guard) => (permissions ?? []).filter(p => p.guard_name === guard);

    const togglePerms = (form, ids, add) => {
        const curr = form.data.permissions;
        let next;
        if (add) next = [...new Set([...curr, ...ids])];
        else     next = curr.filter(id => !ids.includes(id));
        form.setData('permissions', next);
    };

    const submitCreate = (e) => {
        e.preventDefault();
        createForm.post('/super-admin/roles', { onSuccess: () => { createForm.reset(); setShowCreate(false); } });
    };

    const submitEdit = (e) => {
        e.preventDefault();
        editForm.put(`/super-admin/roles/${editId}`, { onSuccess: () => setEditId(null) });
    };

    const startEdit = (role) => {
        setEditId(role.id);
        editForm.setData({ name: role.name, permissions: (role.permissions ?? []).map(p => p.id) });
    };

    const destroy = (id) => {
        if (!confirm('حذف هذا الدور نهائياً؟')) return;
        router.delete(`/super-admin/roles/${id}`, { preserveScroll: true });
    };

    /* group by guard for display */
    const byGuard = useMemo(() => (roles ?? []).reduce((acc, r) => {
        if (!acc[r.guard_name]) acc[r.guard_name] = [];
        acc[r.guard_name].push(r);
        return acc;
    }, {}), [roles]);

    const editingRole = editId ? (roles ?? []).find(r => r.id === editId) : null;

    return (
        <SuperAdminLayout title="الأدوار">
            <Head title="الأدوار — Skillify" />

            {/* ─── Header ─── */}
            <div style={{ display: 'flex', alignItems: 'flex-start', justifyContent: 'space-between', flexWrap: 'wrap', gap: 12 }}>
                <div>
                    <h1 style={{ fontSize: 22, fontWeight: 800, color: '#1E1B4B', margin: 0, letterSpacing: -0.5 }}>الأدوار</h1>
                    <p style={{ fontSize: 12, color: '#94A3B8', marginTop: 4 }}>{(roles ?? []).length} دور</p>
                </div>
                <button onClick={() => { setShowCreate(v => !v); setEditId(null); }} style={{
                    display: 'inline-flex', alignItems: 'center', gap: 7, padding: '9px 18px',
                    background: showCreate ? '#EDE9FE' : 'linear-gradient(135deg,#7C3AED,#6D28D9)',
                    color: showCreate ? '#6D28D9' : '#fff',
                    border: showCreate ? '1px solid #DDD6FE' : 'none',
                    borderRadius: 10, fontSize: 13, fontWeight: 700, cursor: 'pointer',
                    boxShadow: showCreate ? 'none' : '0 4px 14px rgba(124,58,237,0.30)',
                    fontFamily: "'Cairo','Inter',sans-serif", transition: 'all 0.15s',
                }}>
                    <i className={`ti ${showCreate ? 'ti-x' : 'ti-plus'}`} />
                    {showCreate ? 'إلغاء' : 'دور جديد'}
                </button>
            </div>

            {/* ─── Create form ─── */}
            {showCreate && (
                <div style={{ background: '#fff', border: '1px solid rgba(124,58,237,0.18)', borderRadius: 16, padding: 22, boxShadow: '0 4px 20px rgba(124,58,237,0.08)' }}>
                    <div style={{ display: 'flex', alignItems: 'center', gap: 10, marginBottom: 18 }}>
                        <div style={{ width: 34, height: 34, borderRadius: 9, background: 'linear-gradient(135deg,#7C3AED,#A78BFA)', display: 'flex', alignItems: 'center', justifyContent: 'center', color: '#fff', fontSize: 15 }}>
                            <i className="ti ti-plus" />
                        </div>
                        <span style={{ fontSize: 15, fontWeight: 700, color: '#1E1B4B' }}>إنشاء دور جديد</span>
                    </div>
                    <form onSubmit={submitCreate}>
                        <div className="grid grid-cols-1 sm:grid-cols-2" style={{ display: 'grid', gap: 14, marginBottom: 18 }}>
                            <div>
                                <label style={{ fontSize: 12, fontWeight: 600, color: '#374151', display: 'block', marginBottom: 6 }}>اسم الدور *</label>
                                <input style={INPUT} value={createForm.data.name} onChange={e => createForm.setData('name', e.target.value)} placeholder="مثال: content_moderator" required />
                                {createForm.errors.name && <p style={{ fontSize: 11, color: '#EF4444', marginTop: 4 }}>{createForm.errors.name}</p>}
                            </div>
                            <div>
                                <label style={{ fontSize: 12, fontWeight: 600, color: '#374151', display: 'block', marginBottom: 6 }}>الحارس *</label>
                                <select style={INPUT} value={createForm.data.guard_name} onChange={e => { createForm.setData('guard_name', e.target.value); createForm.setData('permissions', []); }}>
                                    {(guards ?? []).map(g => <option key={g} value={g}>{g}</option>)}
                                </select>
                            </div>
                        </div>
                        {guardPerms(createForm.data.guard_name).length > 0 && (
                            <PermPicker
                                availablePerms={guardPerms(createForm.data.guard_name)}
                                selectedIds={createForm.data.permissions}
                                onToggle={(ids, add) => togglePerms(createForm, ids, add)}
                            />
                        )}
                        <div style={{ display: 'flex', gap: 9, justifyContent: 'flex-end', marginTop: 16 }}>
                            <button type="button" onClick={() => setShowCreate(false)} style={{ padding: '8px 16px', borderRadius: 9, border: '1px solid rgba(0,0,0,0.12)', background: 'none', fontSize: 13, cursor: 'pointer', fontFamily: "'Cairo','Inter',sans-serif" }}>إلغاء</button>
                            <button type="submit" disabled={createForm.processing} style={{ padding: '8px 20px', borderRadius: 9, background: 'linear-gradient(135deg,#7C3AED,#6D28D9)', color: '#fff', border: 'none', fontSize: 13, fontWeight: 700, cursor: 'pointer', opacity: createForm.processing ? 0.7 : 1, fontFamily: "'Cairo','Inter',sans-serif", boxShadow: '0 3px 10px rgba(124,58,237,0.28)' }}>
                                {createForm.processing ? 'جارٍ الإنشاء...' : 'إنشاء'}
                            </button>
                        </div>
                    </form>
                </div>
            )}

            {/* ─── Edit form ─── */}
            {editId && editingRole && (
                <div style={{ background: '#fff', border: `1px solid ${(GUARD_CFG[editingRole.guard_name] ?? DEFAULT_CFG).border}`, borderRadius: 16, padding: 22, boxShadow: '0 4px 20px rgba(0,0,0,0.06)' }}>
                    <div style={{ display: 'flex', alignItems: 'center', gap: 10, marginBottom: 18 }}>
                        <div style={{ width: 34, height: 34, borderRadius: 9, background: (GUARD_CFG[editingRole.guard_name] ?? DEFAULT_CFG).gradient, display: 'flex', alignItems: 'center', justifyContent: 'center', color: '#fff', fontSize: 15 }}>
                            <i className="ti ti-pencil" />
                        </div>
                        <span style={{ fontSize: 15, fontWeight: 700, color: '#1E1B4B' }}>تعديل الدور: {editingRole.name}</span>
                    </div>
                    <form onSubmit={submitEdit}>
                        <div style={{ marginBottom: 18 }}>
                            <label style={{ fontSize: 12, fontWeight: 600, color: '#374151', display: 'block', marginBottom: 6 }}>اسم الدور *</label>
                            <input style={{ ...INPUT, maxWidth: 320 }} value={editForm.data.name} onChange={e => editForm.setData('name', e.target.value)} required />
                        </div>
                        {guardPerms(editingRole.guard_name).length > 0 && (
                            <PermPicker
                                availablePerms={guardPerms(editingRole.guard_name)}
                                selectedIds={editForm.data.permissions}
                                onToggle={(ids, add) => togglePerms(editForm, ids, add)}
                            />
                        )}
                        <div style={{ display: 'flex', gap: 9, justifyContent: 'flex-end', marginTop: 16 }}>
                            <button type="button" onClick={() => setEditId(null)} style={{ padding: '8px 16px', borderRadius: 9, border: '1px solid rgba(0,0,0,0.12)', background: 'none', fontSize: 13, cursor: 'pointer', fontFamily: "'Cairo','Inter',sans-serif" }}>إلغاء</button>
                            <button type="submit" disabled={editForm.processing} style={{ padding: '8px 20px', borderRadius: 9, background: (GUARD_CFG[editingRole.guard_name] ?? DEFAULT_CFG).gradient, color: '#fff', border: 'none', fontSize: 13, fontWeight: 700, cursor: 'pointer', opacity: editForm.processing ? 0.7 : 1, fontFamily: "'Cairo','Inter',sans-serif" }}>
                                {editForm.processing ? 'جارٍ الحفظ...' : 'حفظ التعديلات'}
                            </button>
                        </div>
                    </form>
                </div>
            )}

            {/* ─── Roles list ─── */}
            {!(roles ?? []).length ? (
                <div style={{ background: '#fff', border: '0.5px solid rgba(0,0,0,0.07)', borderRadius: 16, padding: '72px 24px', textAlign: 'center', color: '#94A3B8' }}>
                    <i className="ti ti-key" style={{ fontSize: 52, display: 'block', opacity: 0.12, marginBottom: 14 }} />
                    <div style={{ fontSize: 15, fontWeight: 600, color: '#64748B', marginBottom: 6 }}>لا توجد أدوار بعد</div>
                    <p style={{ fontSize: 13 }}>أنشئ أول دور من الأعلى.</p>
                </div>
            ) : Object.entries(byGuard).map(([guard, guardRoles]) => {
                const cfg = GUARD_CFG[guard] ?? DEFAULT_CFG;
                return (
                    <div key={guard}>
                        {/* Guard section label */}
                        <div style={{ display: 'flex', alignItems: 'center', gap: 8, marginBottom: 10 }}>
                            <div style={{ width: 24, height: 24, borderRadius: 6, background: cfg.gradient, display: 'flex', alignItems: 'center', justifyContent: 'center', fontSize: 12, color: '#fff' }}>
                                <i className={`ti ${cfg.icon}`} />
                            </div>
                            <span style={{ fontSize: 13, fontWeight: 700, color: cfg.color }}>{cfg.label}</span>
                            <span style={{ fontSize: 11, color: '#94A3B8' }}>({guardRoles.length} دور)</span>
                            <div style={{ flex: 1, height: '0.5px', background: cfg.border }} />
                        </div>

                        <div style={{ display: 'flex', flexDirection: 'column', gap: 12, marginBottom: 22 }}>
                            {guardRoles.map(role => (
                                <RoleCard key={role.id} role={role}
                                    onEdit={startEdit}
                                    onDelete={destroy}
                                />
                            ))}
                        </div>
                    </div>
                );
            })}
        </SuperAdminLayout>
    );
}
