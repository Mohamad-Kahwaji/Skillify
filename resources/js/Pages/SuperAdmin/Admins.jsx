import { Head, useForm, router } from '@inertiajs/react';
import { useState, useMemo } from 'react';
import SuperAdminLayout from '../../Layouts/SuperAdminLayout';

const AV_COLORS = ['#6D28D9','#0D9488','#2563EB','#D97706','#DC2626','#0891B2','#7C3AED','#059669'];

const STATUS_CFG = {
    active:   { label: 'نشط',   color: '#065F46', bg: '#D1FAE5', border: '#6EE7B7', dot: '#10B981' },
    inactive: { label: 'موقوف', color: '#991B1B', bg: '#FEE2E2', border: '#FCA5A5', dot: '#EF4444' },
};

const INPUT = {
    width: '100%', padding: '9px 12px',
    border: '1px solid rgba(0,0,0,0.11)', borderRadius: 9,
    fontSize: 13, outline: 'none', boxSizing: 'border-box',
    fontFamily: "'Cairo','Inter',sans-serif", background: '#FAFAFA',
};

const ACTION_COLOR = {
    view: '#2563EB', create: '#059669', edit: '#D97706', update: '#D97706',
    delete: '#DC2626', approve: '#059669', reject: '#DC2626',
    toggle: '#D97706', show: '#0891B2', activate: '#059669', deactivate: '#6B7280',
    view_all: '#7C3AED', view_no_services: '#6D28D9',
};
const ACTION_BG = {
    view: '#DBEAFE', create: '#D1FAE5', edit: '#FEF3C7', update: '#FEF3C7',
    delete: '#FEE2E2', approve: '#D1FAE5', reject: '#FEE2E2',
    toggle: '#FEF3C7', show: '#CFFAFE', activate: '#D1FAE5', deactivate: '#F3F4F6',
    view_all: '#EDE9FE', view_no_services: '#EDE9FE',
};

function parseAction(name) {
    const dot = name.indexOf('.');
    return dot === -1 ? name : name.slice(dot + 1);
}

function avColor(i)  { return AV_COLORS[i % AV_COLORS.length]; }
function avColor2(i) { return AV_COLORS[(i + 3) % AV_COLORS.length]; }
function initials(a) { return `${a.first_name?.[0] ?? ''}${a.last_name?.[0] ?? ''}`.toUpperCase() || 'A'; }

/* ── Roles + Permissions Picker ──────────────────────────────── */
function RolePicker({ roles, roleId, permIds, onRoleChange, onPermToggle, onSelectAllPerms, onClearAllPerms }) {
    const selectedRole = (roles ?? []).find(r => String(r.id) === String(roleId));
    const rolePerms    = selectedRole?.permissions ?? [];

    return (
        <div>
            {/* Role cards */}
            <div style={{ display: 'grid', gridTemplateColumns: 'repeat(auto-fill,minmax(150px,1fr))', gap: 8, marginBottom: 16 }}>
                {/* No role option */}
                <div onClick={() => onRoleChange('')} style={{
                    padding: '11px 14px', borderRadius: 10, cursor: 'pointer', transition: 'all 0.13s',
                    border: `2px solid ${roleId === '' ? '#94A3B8' : 'rgba(0,0,0,0.09)'}`,
                    background: roleId === '' ? '#F8FAFC' : '#FAFAFA',
                    display: 'flex', alignItems: 'center', gap: 8,
                }}>
                    <div style={{ width: 28, height: 28, borderRadius: 8, background: '#F1F5F9', display: 'flex', alignItems: 'center', justifyContent: 'center', fontSize: 13, color: '#94A3B8', flexShrink: 0 }}>
                        <i className="ti ti-ban" />
                    </div>
                    <div>
                        <div style={{ fontSize: 12, fontWeight: 700, color: '#64748B' }}>بدون دور</div>
                        <div style={{ fontSize: 10, color: '#94A3B8' }}>0 صلاحية</div>
                    </div>
                    {roleId === '' && <i className="ti ti-check" style={{ marginRight: 'auto', color: '#94A3B8', fontSize: 13 }} />}
                </div>

                {(roles ?? []).map(r => {
                    const isSelected = String(roleId) === String(r.id);
                    const selCount = isSelected ? permIds.length : (r.permissions?.length ?? 0);
                    const total = r.permissions?.length ?? 0;
                    const pct = total ? Math.round((selCount / total) * 100) : 0;
                    return (
                        <div key={r.id} onClick={() => onRoleChange(r.id)} style={{
                            padding: '11px 14px', borderRadius: 10, cursor: 'pointer', transition: 'all 0.13s',
                            border: `2px solid ${isSelected ? '#7C3AED' : 'rgba(0,0,0,0.09)'}`,
                            background: isSelected ? '#F5F3FF' : '#FAFAFA',
                            display: 'flex', alignItems: 'center', gap: 8, position: 'relative',
                        }}>
                            <div style={{ width: 28, height: 28, borderRadius: 8, background: isSelected ? 'linear-gradient(135deg,#7C3AED,#A78BFA)' : '#EDE9FE', display: 'flex', alignItems: 'center', justifyContent: 'center', fontSize: 13, color: isSelected ? '#fff' : '#7C3AED', flexShrink: 0 }}>
                                <i className="ti ti-key" />
                            </div>
                            <div style={{ flex: 1, minWidth: 0 }}>
                                <div style={{ fontSize: 12, fontWeight: 700, color: isSelected ? '#6D28D9' : '#374151', overflow: 'hidden', textOverflow: 'ellipsis', whiteSpace: 'nowrap' }}>{r.name}</div>
                                <div style={{ fontSize: 10, color: isSelected ? '#A78BFA' : '#94A3B8' }}>
                                    {isSelected ? `${selCount}/${total}` : total} صلاحية
                                </div>
                            </div>
                            {isSelected && <i className="ti ti-check" style={{ color: '#7C3AED', fontSize: 14, flexShrink: 0 }} />}
                            {/* Progress bar */}
                            {isSelected && (
                                <div style={{ position: 'absolute', bottom: 0, left: 0, right: 0, height: 3, borderRadius: '0 0 8px 8px', background: '#DDD6FE', overflow: 'hidden' }}>
                                    <div style={{ height: '100%', width: `${pct}%`, background: pct === 0 ? '#EF4444' : pct === 100 ? '#7C3AED' : '#A78BFA', transition: 'width 0.2s' }} />
                                </div>
                            )}
                        </div>
                    );
                })}
            </div>

            {/* Permissions panel */}
            {selectedRole && (
                <div style={{ background: '#F8FAFC', border: '1px solid rgba(0,0,0,0.08)', borderRadius: 12, overflow: 'hidden' }}>
                    {/* Panel header */}
                    <div style={{ padding: '10px 16px', background: 'linear-gradient(135deg,#EDE9FE,#F5F3FF)', borderBottom: '1px solid rgba(124,58,237,0.12)', display: 'flex', alignItems: 'center', justifyContent: 'space-between' }}>
                        <div style={{ display: 'flex', alignItems: 'center', gap: 7 }}>
                            <i className="ti ti-shield-check" style={{ color: '#7C3AED', fontSize: 14 }} />
                            <span style={{ fontSize: 12, fontWeight: 700, color: '#4C1D95' }}>
                                صلاحيات "{selectedRole.name}" —
                                <span style={{ color: permIds.length === 0 ? '#DC2626' : permIds.length === rolePerms.length ? '#059669' : '#D97706', marginRight: 4 }}>
                                    {permIds.length} / {rolePerms.length} محدّد
                                </span>
                            </span>
                        </div>
                        <div style={{ display: 'flex', gap: 6 }}>
                            <button onClick={onSelectAllPerms} type="button" style={{ padding: '3px 10px', borderRadius: 6, border: '1px solid #6EE7B7', background: '#D1FAE5', color: '#065F46', fontSize: 11, fontWeight: 600, cursor: 'pointer', fontFamily: "'Cairo','Inter',sans-serif" }}>
                                تحديد الكل
                            </button>
                            <button onClick={onClearAllPerms} type="button" style={{ padding: '3px 10px', borderRadius: 6, border: '1px solid #FCA5A5', background: '#FEE2E2', color: '#991B1B', fontSize: 11, fontWeight: 600, cursor: 'pointer', fontFamily: "'Cairo','Inter',sans-serif" }}>
                                إلغاء الكل
                            </button>
                        </div>
                    </div>

                    {/* Warning: 0 selected */}
                    {permIds.length === 0 && (
                        <div style={{ padding: '8px 16px', background: '#FEF2F2', borderBottom: '1px solid #FECACA', display: 'flex', alignItems: 'center', gap: 7, fontSize: 11, color: '#991B1B' }}>
                            <i className="ti ti-alert-triangle" />
                            لا توجد صلاحيات محددة — لن يُعيَّن أي دور للمشرف
                        </div>
                    )}

                    {/* Permissions grouped by module */}
                    <div style={{ padding: '12px 16px', maxHeight: 280, overflowY: 'auto' }}>
                        {(() => {
                            const grouped = rolePerms.reduce((acc, p) => {
                                const mod = p.name.includes('.') ? p.name.split('.')[0] : 'أخرى';
                                if (!acc[mod]) acc[mod] = [];
                                acc[mod].push(p);
                                return acc;
                            }, {});

                            return Object.entries(grouped).sort(([a],[b]) => a.localeCompare(b)).map(([mod, perms]) => (
                                <div key={mod} style={{ marginBottom: 12 }}>
                                    <div style={{ fontSize: 10, fontWeight: 700, color: '#94A3B8', textTransform: 'uppercase', letterSpacing: 0.8, marginBottom: 6, display: 'flex', alignItems: 'center', gap: 5 }}>
                                        <span>{mod}</span>
                                        <span style={{ fontSize: 9, color: '#C4B5FD', fontWeight: 500 }}>({perms.filter(p => permIds.includes(p.id)).length}/{perms.length})</span>
                                    </div>
                                    <div style={{ display: 'flex', flexWrap: 'wrap', gap: 6 }}>
                                        {perms.map(p => {
                                            const action = parseAction(p.name);
                                            const isOn   = permIds.includes(p.id);
                                            const c = ACTION_COLOR[action] ?? '#475569';
                                            const bg = isOn ? (ACTION_BG[action] ?? '#F1F5F9') : '#F1F5F9';
                                            return (
                                                <button key={p.id} type="button" onClick={() => onPermToggle(p.id)} style={{
                                                    display: 'inline-flex', alignItems: 'center', gap: 5,
                                                    padding: '4px 10px', borderRadius: 20,
                                                    border: `1px solid ${isOn ? c + '44' : 'rgba(0,0,0,0.09)'}`,
                                                    background: bg,
                                                    color: isOn ? c : '#94A3B8',
                                                    fontSize: 11, fontWeight: isOn ? 700 : 400, cursor: 'pointer',
                                                    fontFamily: "'Cairo','Inter',sans-serif",
                                                    transition: 'all 0.13s',
                                                    textDecoration: isOn ? 'none' : 'line-through',
                                                    opacity: isOn ? 1 : 0.55,
                                                }}>
                                                    {isOn
                                                        ? <i className="ti ti-check" style={{ fontSize: 10 }} />
                                                        : <i className="ti ti-x" style={{ fontSize: 10 }} />
                                                    }
                                                    {action}
                                                </button>
                                            );
                                        })}
                                    </div>
                                </div>
                            ));
                        })()}
                    </div>
                </div>
            )}
        </div>
    );
}

/* ── Inline role selector in table ──────────────────────────── */
function RoleSelector({ admin, roles, onAssign, onRevoke }) {
    const [open, setOpen] = useState(false);
    const currentRole = admin.roles?.[0]?.name ?? null;

    if (!open) return (
        <div style={{ display: 'flex', alignItems: 'center', gap: 7 }}>
            {currentRole
                ? <span style={{ display: 'inline-flex', alignItems: 'center', gap: 4, fontSize: 11, fontWeight: 700, padding: '3px 9px', borderRadius: 20, background: '#EDE9FE', color: '#6D28D9', border: '1px solid #DDD6FE' }}>
                    <i className="ti ti-key" style={{ fontSize: 10 }} />{currentRole}
                  </span>
                : <span style={{ fontSize: 11, color: '#94A3B8' }}>بدون دور</span>
            }
            <button onClick={() => setOpen(true)} style={{ display: 'inline-flex', alignItems: 'center', gap: 3, padding: '3px 8px', borderRadius: 6, border: '1px dashed #DDD6FE', background: 'transparent', color: '#7C3AED', fontSize: 10, cursor: 'pointer', fontFamily: "'Cairo','Inter',sans-serif" }}>
                <i className="ti ti-edit" style={{ fontSize: 10 }} /> تغيير
            </button>
        </div>
    );

    return (
        <div style={{ display: 'flex', gap: 5, alignItems: 'center', flexWrap: 'wrap' }}>
            <select defaultValue="" onChange={e => { if (e.target.value) { onAssign(admin.id, e.target.value); setOpen(false); } }}
                style={{ ...INPUT, width: 'auto', padding: '4px 8px', fontSize: 12 }} autoFocus>
                <option value="" disabled>اختر دوراً...</option>
                {(roles ?? []).map(r => <option key={r.id} value={r.id}>{r.name}</option>)}
            </select>
            {currentRole && (
                <button onClick={() => { onRevoke(admin.id); setOpen(false); }} style={{ padding: '4px 8px', borderRadius: 6, border: '1px solid #FCA5A5', background: '#FEF2F2', color: '#DC2626', fontSize: 11, cursor: 'pointer', fontFamily: "'Cairo','Inter',sans-serif" }}>
                    سحب الدور
                </button>
            )}
            <button onClick={() => setOpen(false)} style={{ width: 24, height: 24, borderRadius: 6, border: '1px solid rgba(0,0,0,0.10)', background: '#F8FAFC', color: '#64748B', fontSize: 13, cursor: 'pointer', display: 'flex', alignItems: 'center', justifyContent: 'center' }}>
                <i className="ti ti-x" />
            </button>
        </div>
    );
}

/* ═══════════════════════════════════════════════════════════════ */
export default function Admins({ admins, roles }) {
    const [showForm, setShowForm] = useState(false);
    const [selectedRoleId, setSelectedRoleId] = useState('');
    const [permIds, setPermIds] = useState([]);

    const { data, setData, post, processing, errors, reset } = useForm({
        first_name: '', last_name: '', email: '', phone: '',
        password: '', password_confirmation: '',
        role_id: '', permission_ids: [],
    });

    const selectedRole = useMemo(() => (roles ?? []).find(r => String(r.id) === String(selectedRoleId)), [roles, selectedRoleId]);

    const handleRoleChange = (id) => {
        setSelectedRoleId(id);
        setData('role_id', id);
        if (id) {
            const role = (roles ?? []).find(r => String(r.id) === String(id));
            const ids = (role?.permissions ?? []).map(p => p.id);
            setPermIds(ids);
            setData('permission_ids', ids);
        } else {
            setPermIds([]);
            setData('permission_ids', []);
        }
    };

    const handlePermToggle = (permId) => {
        setPermIds(prev => {
            const next = prev.includes(permId) ? prev.filter(id => id !== permId) : [...prev, permId];
            setData('permission_ids', next);
            return next;
        });
    };

    const handleSelectAll = () => {
        const ids = (selectedRole?.permissions ?? []).map(p => p.id);
        setPermIds(ids);
        setData('permission_ids', ids);
    };

    const handleClearAll = () => {
        setPermIds([]);
        setData('permission_ids', []);
    };

    const submit = (e) => {
        e.preventDefault();
        post('/super-admin/admins', {
            onSuccess: () => {
                reset(); setShowForm(false);
                setSelectedRoleId(''); setPermIds([]);
            }
        });
    };

    const destroy      = (id) => { if (!confirm('حذف هذا المشرف نهائياً؟')) return; router.delete(`/super-admin/admins/${id}`, { preserveScroll: true }); };
    const toggleStatus = (a)  => { const r = a.status === 'active' ? `/super-admin/admins/${a.id}/deactivate` : `/super-admin/admins/${a.id}/activate`; router.patch(r, {}, { preserveScroll: true }); };
    const assignRole   = (adminId, roleId) => router.patch(`/super-admin/admins/${adminId}/assign-role`, { role_id: roleId }, { preserveScroll: true });
    const revokeRole   = (adminId)         => router.patch(`/super-admin/admins/${adminId}/revoke-roles`, {}, { preserveScroll: true });

    const allAdmins = admins ?? [];

    return (
        <SuperAdminLayout title="المشرفون">
            <Head title="المشرفون — Skillify" />

            {/* Header */}
            <div style={{ display: 'flex', alignItems: 'flex-start', justifyContent: 'space-between', flexWrap: 'wrap', gap: 12 }}>
                <div>
                    <h1 style={{ fontSize: 22, fontWeight: 800, color: '#1E1B4B', margin: 0, letterSpacing: -0.5 }}>حسابات المشرفين</h1>
                    <p style={{ fontSize: 12, color: '#94A3B8', marginTop: 4 }}>{allAdmins.length} مشرف</p>
                </div>
                <button onClick={() => { setShowForm(v => !v); if (showForm) { reset(); setSelectedRoleId(''); setPermIds([]); } }} style={{
                    display: 'inline-flex', alignItems: 'center', gap: 7, padding: '9px 18px',
                    background: showForm ? '#EDE9FE' : 'linear-gradient(135deg,#7C3AED,#6D28D9)',
                    color: showForm ? '#6D28D9' : '#fff',
                    border: showForm ? '1px solid #DDD6FE' : 'none',
                    borderRadius: 10, fontSize: 13, fontWeight: 700, cursor: 'pointer',
                    boxShadow: showForm ? 'none' : '0 4px 14px rgba(124,58,237,0.30)',
                    fontFamily: "'Cairo','Inter',sans-serif", transition: 'all 0.15s',
                }}>
                    <i className={`ti ${showForm ? 'ti-x' : 'ti-plus'}`} />
                    {showForm ? 'إلغاء' : 'مشرف جديد'}
                </button>
            </div>

            {/* ─── Create Form ─── */}
            {showForm && (
                <div style={{ background: '#fff', border: '1px solid rgba(124,58,237,0.18)', borderRadius: 16, padding: 24, boxShadow: '0 4px 20px rgba(124,58,237,0.07)' }}>
                    <div style={{ display: 'flex', alignItems: 'center', gap: 10, marginBottom: 22 }}>
                        <div style={{ width: 36, height: 36, borderRadius: 10, background: 'linear-gradient(135deg,#7C3AED,#A78BFA)', display: 'flex', alignItems: 'center', justifyContent: 'center', color: '#fff', fontSize: 16 }}>
                            <i className="ti ti-user-plus" />
                        </div>
                        <div>
                            <div style={{ fontSize: 15, fontWeight: 700, color: '#1E1B4B' }}>إنشاء حساب مشرف جديد</div>
                            <div style={{ fontSize: 11, color: '#94A3B8' }}>رقم الهوية يُولَّد تلقائياً</div>
                        </div>
                    </div>

                    <form onSubmit={submit}>
                        {/* Personal info */}
                        <div className="grid grid-cols-1 sm:grid-cols-2" style={{ display: 'grid', gap: 14, marginBottom: 20 }}>
                            <div>
                                <label style={{ fontSize: 12, fontWeight: 600, color: '#374151', display: 'block', marginBottom: 5 }}>الاسم الأول *</label>
                                <input style={INPUT} value={data.first_name} onChange={e => setData('first_name', e.target.value)} placeholder="محمد" required />
                                {errors.first_name && <p style={{ fontSize: 11, color: '#EF4444', marginTop: 3 }}>{errors.first_name}</p>}
                            </div>
                            <div>
                                <label style={{ fontSize: 12, fontWeight: 600, color: '#374151', display: 'block', marginBottom: 5 }}>الاسم الأخير *</label>
                                <input style={INPUT} value={data.last_name} onChange={e => setData('last_name', e.target.value)} placeholder="الأحمد" required />
                            </div>
                            <div>
                                <label style={{ fontSize: 12, fontWeight: 600, color: '#374151', display: 'block', marginBottom: 5 }}>البريد الإلكتروني *</label>
                                <input type="email" style={INPUT} value={data.email} onChange={e => setData('email', e.target.value)} placeholder="admin@skillify.sy" required />
                                {errors.email && <p style={{ fontSize: 11, color: '#EF4444', marginTop: 3 }}>{errors.email}</p>}
                            </div>
                            <div>
                                <label style={{ fontSize: 12, fontWeight: 600, color: '#374151', display: 'block', marginBottom: 5 }}>رقم الهاتف</label>
                                <input style={INPUT} value={data.phone} onChange={e => setData('phone', e.target.value)} placeholder="09xxxxxxxx" />
                            </div>
                            <div>
                                <label style={{ fontSize: 12, fontWeight: 600, color: '#374151', display: 'block', marginBottom: 5 }}>كلمة المرور *</label>
                                <input type="password" style={INPUT} value={data.password} onChange={e => setData('password', e.target.value)} placeholder="8 أحرف على الأقل" required />
                                {errors.password && <p style={{ fontSize: 11, color: '#EF4444', marginTop: 3 }}>{errors.password}</p>}
                            </div>
                            <div>
                                <label style={{ fontSize: 12, fontWeight: 600, color: '#374151', display: 'block', marginBottom: 5 }}>تأكيد كلمة المرور *</label>
                                <input type="password" style={INPUT} value={data.password_confirmation} onChange={e => setData('password_confirmation', e.target.value)} placeholder="••••••••" required />
                            </div>
                        </div>

                        {/* Role + Permissions */}
                        <div style={{ borderTop: '1px solid rgba(0,0,0,0.07)', paddingTop: 18, marginBottom: 20 }}>
                            <div style={{ display: 'flex', alignItems: 'center', gap: 7, marginBottom: 14 }}>
                                <i className="ti ti-shield-check" style={{ color: '#7C3AED', fontSize: 16 }} />
                                <span style={{ fontSize: 13, fontWeight: 700, color: '#1E1B4B' }}>الدور والصلاحيات</span>
                                <span style={{ fontSize: 11, color: '#94A3B8' }}>— يمكنك تعديل الصلاحيات بعد اختيار الدور</span>
                            </div>
                            {!(roles ?? []).length ? (
                                <div style={{ padding: '12px 16px', background: '#FFFBEB', border: '1px solid #FDE68A', borderRadius: 10, fontSize: 12, color: '#92400E', display: 'flex', alignItems: 'center', gap: 8 }}>
                                    <i className="ti ti-alert-triangle" />
                                    لا توجد أدوار معرّفة — أضف أدواراً من صفحة <strong style={{ marginRight: 3 }}>الأدوار</strong> أولاً
                                </div>
                            ) : (
                                <RolePicker
                                    roles={roles}
                                    roleId={selectedRoleId}
                                    permIds={permIds}
                                    onRoleChange={handleRoleChange}
                                    onPermToggle={handlePermToggle}
                                    onSelectAllPerms={handleSelectAll}
                                    onClearAllPerms={handleClearAll}
                                />
                            )}
                        </div>

                        <div style={{ display: 'flex', gap: 10, justifyContent: 'flex-end' }}>
                            <button type="button" onClick={() => { setShowForm(false); reset(); setSelectedRoleId(''); setPermIds([]); }}
                                style={{ padding: '9px 18px', borderRadius: 9, border: '1px solid rgba(0,0,0,0.12)', background: 'none', fontSize: 13, cursor: 'pointer', fontFamily: "'Cairo','Inter',sans-serif" }}>
                                إلغاء
                            </button>
                            <button type="submit" disabled={processing} style={{ padding: '9px 22px', borderRadius: 9, background: 'linear-gradient(135deg,#7C3AED,#6D28D9)', color: '#fff', border: 'none', fontSize: 13, fontWeight: 700, cursor: 'pointer', opacity: processing ? 0.7 : 1, fontFamily: "'Cairo','Inter',sans-serif", boxShadow: '0 3px 10px rgba(124,58,237,0.28)', display: 'flex', alignItems: 'center', gap: 7 }}>
                                <i className="ti ti-user-check" />{processing ? 'جارٍ الإنشاء...' : 'إنشاء مشرف'}
                            </button>
                        </div>
                    </form>
                </div>
            )}

            {/* ─── Table ─── */}
            <div style={{ background: '#fff', border: '1px solid rgba(0,0,0,0.07)', borderRadius: 16, overflow: 'hidden', boxShadow: '0 2px 12px rgba(0,0,0,0.04)' }}>
                <div style={{ overflowX: 'auto' }}>
                <table style={{ width: '100%', borderCollapse: 'collapse', fontSize: 13 }}>
                    <thead>
                        <tr style={{ background: 'linear-gradient(135deg,#F8FAFC,#F1F5F9)', borderBottom: '1px solid rgba(0,0,0,0.07)' }}>
                            {['المشرف','البريد','الدور والصلاحيات','الحالة','تاريخ الإنشاء','إجراءات'].map(h => (
                                <th key={h} style={{ padding: '12px 16px', textAlign: 'right', fontWeight: 700, color: '#374151', fontSize: 12, whiteSpace: 'nowrap' }}>{h}</th>
                            ))}
                        </tr>
                    </thead>
                    <tbody>
                        {!allAdmins.length ? (
                            <tr>
                                <td colSpan={6} style={{ padding: '64px 24px', textAlign: 'center', color: '#94A3B8' }}>
                                    <i className="ti ti-user-shield" style={{ fontSize: 52, display: 'block', opacity: 0.10, marginBottom: 14 }} />
                                    <div style={{ fontSize: 14, fontWeight: 600, color: '#64748B', marginBottom: 6 }}>لا يوجد مشرفون بعد</div>
                                    <p style={{ fontSize: 13, margin: 0 }}>أنشئ أول مشرف من الأعلى.</p>
                                </td>
                            </tr>
                        ) : allAdmins.map((a, i) => {
                            const sc = STATUS_CFG[a.status] ?? STATUS_CFG.active;
                            return (
                                <tr key={a.id}
                                    style={{ borderBottom: '0.5px solid rgba(0,0,0,0.05)', transition: 'background 0.12s' }}
                                    onMouseEnter={e => e.currentTarget.style.background = '#FAFAFF'}
                                    onMouseLeave={e => e.currentTarget.style.background = 'transparent'}
                                >
                                    <td style={{ padding: '13px 16px' }}>
                                        <div style={{ display: 'flex', alignItems: 'center', gap: 10 }}>
                                            <div style={{ width: 38, height: 38, borderRadius: '50%', background: `linear-gradient(135deg,${avColor(i)},${avColor2(i)})`, display: 'flex', alignItems: 'center', justifyContent: 'center', fontSize: 13, fontWeight: 700, color: '#fff', flexShrink: 0 }}>
                                                {initials(a)}
                                            </div>
                                            <div>
                                                <div style={{ fontSize: 13, fontWeight: 700, color: '#0F172A' }}>{a.first_name} {a.last_name}</div>
                                                {a.phone && <div style={{ fontSize: 11, color: '#94A3B8', marginTop: 1 }}>{a.phone}</div>}
                                            </div>
                                        </div>
                                    </td>
                                    <td style={{ padding: '13px 16px', color: '#475569', fontSize: 12 }}>{a.email}</td>
                                    <td style={{ padding: '13px 16px', maxWidth: 240 }}>
                                        <RoleSelector admin={a} roles={roles} onAssign={assignRole} onRevoke={revokeRole} />
                                        {/* Show permissions count */}
                                        {a.roles?.[0] && (
                                            <div style={{ fontSize: 10, color: '#94A3B8', marginTop: 4 }}>
                                                {a.roles[0].permissions?.length ?? 0} صلاحية
                                            </div>
                                        )}
                                    </td>
                                    <td style={{ padding: '13px 16px' }}>
                                        <span style={{ display: 'inline-flex', alignItems: 'center', gap: 5, fontSize: 11, fontWeight: 700, padding: '4px 10px', borderRadius: 20, background: sc.bg, color: sc.color, border: `1px solid ${sc.border}` }}>
                                            <span style={{ width: 5, height: 5, borderRadius: '50%', background: sc.dot }} />
                                            {sc.label}
                                        </span>
                                    </td>
                                    <td style={{ padding: '13px 16px', color: '#94A3B8', fontSize: 11, whiteSpace: 'nowrap' }}>
                                        {new Date(a.created_at).toLocaleDateString('ar', { day: 'numeric', month: 'short', year: 'numeric' })}
                                    </td>
                                    <td style={{ padding: '13px 16px' }}>
                                        <div style={{ display: 'flex', gap: 6 }}>
                                            <button onClick={() => toggleStatus(a)} style={{ display: 'inline-flex', alignItems: 'center', gap: 4, padding: '5px 10px', borderRadius: 7, fontSize: 11, fontWeight: 600, cursor: 'pointer', border: `1px solid ${a.status === 'active' ? '#FDE68A' : '#6EE7B7'}`, background: a.status === 'active' ? '#FFFBEB' : '#D1FAE5', color: a.status === 'active' ? '#92400E' : '#065F46', fontFamily: "'Cairo','Inter',sans-serif", transition: 'all 0.13s' }}>
                                                <i className={`ti ${a.status === 'active' ? 'ti-player-pause' : 'ti-player-play'}`} style={{ fontSize: 12 }} />
                                                {a.status === 'active' ? 'تعطيل' : 'تفعيل'}
                                            </button>
                                            <button onClick={() => destroy(a.id)} style={{ width: 30, height: 30, borderRadius: 7, border: '1px solid #FCA5A5', background: '#FEF2F2', color: '#DC2626', fontSize: 13, cursor: 'pointer', display: 'flex', alignItems: 'center', justifyContent: 'center', transition: 'all 0.13s' }}
                                                onMouseEnter={e => { e.currentTarget.style.background = '#FEE2E2'; e.currentTarget.style.transform = 'scale(1.1)'; }}
                                                onMouseLeave={e => { e.currentTarget.style.background = '#FEF2F2'; e.currentTarget.style.transform = 'scale(1)'; }}>
                                                <i className="ti ti-trash" />
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            );
                        })}
                    </tbody>
                </table>
                </div>
            </div>
        </SuperAdminLayout>
    );
}
