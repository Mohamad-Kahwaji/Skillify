import { Head, router } from '@inertiajs/react';
import { useState } from 'react';
import AdminLayout, { C } from '../../Layouts/AdminLayout';

const AV_COLORS = ['#6D28D9','#0D9488','#2563EB','#D97706','#DC2626','#0891B2','#7C3AED','#059669'];

const STATUS_CFG = {
    published: { label: 'منشور',  color: '#065F46', bg: '#D1FAE5', border: '#6EE7B7', icon: 'ti-circle-check' },
    draft:     { label: 'مسودة',  color: '#92400E', bg: '#FEF3C7', border: '#FDE68A', icon: 'ti-pencil' },
    archived:  { label: 'مؤرشف', color: '#374151', bg: '#F3F4F6', border: '#E5E7EB', icon: 'ti-archive' },
};

const TABS = [
    { key: 'all',       label: 'الكل',   icon: 'ti-apps' },
    { key: 'published', label: 'منشور',  icon: 'ti-circle-check' },
    { key: 'draft',     label: 'مسودة',  icon: 'ti-pencil' },
    { key: 'archived',  label: 'مؤرشف', icon: 'ti-archive' },
];

function timeAgo(dateStr) {
    if (!dateStr) return '';
    const diff = Math.floor((Date.now() - new Date(dateStr)) / 1000);
    if (diff < 60)     return 'الآن';
    if (diff < 3600)   return `منذ ${Math.floor(diff / 60)} دقيقة`;
    if (diff < 86400)  return `منذ ${Math.floor(diff / 3600)} ساعة`;
    if (diff < 604800) return `منذ ${Math.floor(diff / 86400)} يوم`;
    return new Date(dateStr).toLocaleDateString('ar', { day: 'numeric', month: 'short', year: 'numeric' });
}

function PostCard({ post, index, onDelete }) {
    const [expanded, setExpanded] = useState(false);
    const [imgError, setImgError]  = useState(false);

    const sc   = STATUS_CFG[post.status] ?? STATUS_CFG.published;
    const av   = AV_COLORS[index % AV_COLORS.length];
    const av2  = AV_COLORS[(index + 2) % AV_COLORS.length];
    const desc = post.description ?? '';
    const isLong = desc.length > 180;

    const imgSrc  = post.image ? (post.image.startsWith('http') ? post.image : `/storage/${post.image}`) : null;
    const showImg = imgSrc && !imgError;

    return (
        <div style={{
            background: '#fff', border: C.cardBorder, borderRadius: 18, overflow: 'hidden',
            boxShadow: C.cardShadow, transition: 'box-shadow 0.2s, transform 0.2s',
            display: 'flex', flexDirection: 'column',
        }}
            onMouseEnter={e => { e.currentTarget.style.boxShadow = '0 8px 28px rgba(0,0,0,0.1)'; e.currentTarget.style.transform = 'translateY(-2px)'; }}
            onMouseLeave={e => { e.currentTarget.style.boxShadow = C.cardShadow; e.currentTarget.style.transform = 'translateY(0)'; }}
        >
            {/* Image / placeholder */}
            {showImg ? (
                <div style={{ position: 'relative', width: '100%', height: 190, overflow: 'hidden', background: '#F1F5F9', flexShrink: 0 }}>
                    <img src={imgSrc} alt={post.title ?? ''} onError={() => setImgError(true)}
                        style={{ width: '100%', height: '100%', objectFit: 'cover', display: 'block' }} />
                    <div style={{ position: 'absolute', inset: 0, background: 'linear-gradient(to top, rgba(0,0,0,0.48) 0%, transparent 55%)', pointerEvents: 'none' }} />
                    {post.title && (
                        <div style={{ position: 'absolute', bottom: 12, right: 16, left: 16, fontSize: 14, fontWeight: 800, color: '#fff', textShadow: '0 1px 4px rgba(0,0,0,0.5)', lineHeight: 1.4 }}>
                            {post.title}
                        </div>
                    )}
                </div>
            ) : (
                <div style={{ width: '100%', height: 72, background: `linear-gradient(135deg,${av}18,${av2}18)`, borderBottom: `1px solid ${av}22`, flexShrink: 0, display: 'flex', alignItems: 'center', justifyContent: 'center' }}>
                    <i className="ti ti-photo-off" style={{ fontSize: 22, color: `${av}55` }} />
                </div>
            )}

            <div style={{ padding: '15px 18px', flex: 1, display: 'flex', flexDirection: 'column', gap: 10 }}>

                {/* Author row */}
                <div style={{ display: 'flex', alignItems: 'center', gap: 10 }}>
                    <div style={{
                        width: 36, height: 36, borderRadius: '50%', flexShrink: 0,
                        background: `linear-gradient(135deg,${av},${av2})`,
                        display: 'flex', alignItems: 'center', justifyContent: 'center',
                        fontSize: 13, fontWeight: 800, color: '#fff',
                    }}>
                        {(post.user?.first_name?.[0] ?? 'م').toUpperCase()}
                    </div>
                    <div style={{ flex: 1, minWidth: 0 }}>
                        <div style={{ fontSize: 13, fontWeight: 700, color: C.textDark, lineHeight: 1.2 }}>
                            {post.user?.first_name} {post.user?.last_name}
                        </div>
                        <div style={{ fontSize: 11, color: C.textFaint, marginTop: 1 }}>{post.user?.email ?? ''}</div>
                    </div>
                    {/* Status */}
                    <span style={{ display: 'inline-flex', alignItems: 'center', gap: 4, fontSize: 10, fontWeight: 700, padding: '3px 9px', borderRadius: 20, background: sc.bg, color: sc.color, border: `1px solid ${sc.border}`, flexShrink: 0 }}>
                        <i className={`ti ${sc.icon}`} style={{ fontSize: 10 }} />{sc.label}
                    </span>
                    {/* Time */}
                    <span style={{ fontSize: 10.5, color: C.textFaint, whiteSpace: 'nowrap', flexShrink: 0 }}>{timeAgo(post.created_at)}</span>
                    {/* Delete */}
                    <button onClick={() => onDelete(post.id)} title="حذف" style={{
                        width: 30, height: 30, borderRadius: 8, border: '1px solid #FCA5A5',
                        background: '#FEF2F2', color: '#DC2626', fontSize: 13, cursor: 'pointer',
                        display: 'flex', alignItems: 'center', justifyContent: 'center', flexShrink: 0, transition: 'all 0.13s',
                    }}
                        onMouseEnter={e => { e.currentTarget.style.background = '#FEE2E2'; e.currentTarget.style.transform = 'scale(1.1)'; }}
                        onMouseLeave={e => { e.currentTarget.style.background = '#FEF2F2'; e.currentTarget.style.transform = 'scale(1)'; }}
                    >
                        <i className="ti ti-trash" />
                    </button>
                </div>

                {/* Title (only if no image) */}
                {post.title && !showImg && (
                    <div style={{ fontSize: 14.5, fontWeight: 800, color: C.textDark, lineHeight: 1.4, letterSpacing: -0.2 }}>
                        {post.title}
                    </div>
                )}

                {/* Description */}
                {desc && (
                    <div style={{ flex: 1 }}>
                        <p style={{
                            fontSize: 13, color: '#334155', lineHeight: 1.75, margin: 0,
                            display: expanded ? 'block' : '-webkit-box',
                            WebkitLineClamp: expanded ? 'unset' : 3,
                            WebkitBoxOrient: 'vertical',
                            overflow: expanded ? 'visible' : 'hidden',
                        }}>
                            {desc}
                        </p>
                        {isLong && (
                            <button onClick={() => setExpanded(v => !v)} style={{
                                background: 'none', border: 'none', color: C.teal,
                                fontSize: 11.5, fontWeight: 700, cursor: 'pointer', marginTop: 4, padding: 0,
                                fontFamily: "'Cairo','Inter',sans-serif",
                            }}>
                                {expanded ? 'عرض أقل ↑' : 'عرض المزيد ↓'}
                            </button>
                        )}
                    </div>
                )}

                {/* Stats */}
                <div style={{ display: 'flex', alignItems: 'center', gap: 14, paddingTop: 10, borderTop: '0.5px solid rgba(0,0,0,0.06)', flexWrap: 'wrap' }}>
                    <div style={{ display: 'flex', alignItems: 'center', gap: 4, fontSize: 12, color: '#64748B' }}>
                        <i className="ti ti-heart-filled" style={{ color: '#EF4444', fontSize: 13 }} />
                        <strong style={{ color: C.textDark }}>{post.likes_count ?? 0}</strong> إعجاب
                    </div>
                    <div style={{ display: 'flex', alignItems: 'center', gap: 4, fontSize: 12, color: '#64748B' }}>
                        <i className="ti ti-message-circle" style={{ color: '#3B82F6', fontSize: 13 }} />
                        <strong style={{ color: C.textDark }}>{post.comments_count ?? 0}</strong> تعليق
                    </div>
                    {post.views != null && (
                        <div style={{ display: 'flex', alignItems: 'center', gap: 4, fontSize: 12, color: '#64748B' }}>
                            <i className="ti ti-eye" style={{ color: C.teal, fontSize: 13 }} />
                            <strong style={{ color: C.textDark }}>{Number(post.views).toLocaleString()}</strong> مشاهدة
                        </div>
                    )}
                    {post.post_date && (
                        <div style={{ marginRight: 'auto', display: 'flex', alignItems: 'center', gap: 4, fontSize: 11, color: C.textFaint }}>
                            <i className="ti ti-calendar" style={{ fontSize: 12 }} />
                            {new Date(post.post_date).toLocaleDateString('ar', { day: 'numeric', month: 'long', year: 'numeric' })}
                        </div>
                    )}
                </div>
            </div>
        </div>
    );
}

/* ══════════════════════════════════════════════════════════════ */
export default function Posts({ posts }) {
    const [search, setSearch]       = useState('');
    const [statusFilter, setFilter] = useState('all');

    const all = posts ?? [];

    const filtered = all.filter(p => {
        const matchSearch = `${p.title ?? ''} ${p.description ?? ''} ${p.user?.first_name ?? ''} ${p.user?.last_name ?? ''}`.toLowerCase().includes(search.toLowerCase());
        const matchStatus = statusFilter === 'all' || (p.status ?? 'published') === statusFilter;
        return matchSearch && matchStatus;
    });

    const counts = {
        all:       all.length,
        published: all.filter(p => !p.status || p.status === 'published').length,
        draft:     all.filter(p => p.status === 'draft').length,
        archived:  all.filter(p => p.status === 'archived').length,
    };

    const destroy = (id) => {
        if (!confirm('حذف هذا المنشور نهائياً؟')) return;
        router.delete(`/admin/posts/${id}`, { preserveScroll: true });
    };

    return (
        <AdminLayout title="المنشورات">
            <Head title="المنشورات — Skillify" />

            {/* Header */}
            <div style={{ display: 'flex', alignItems: 'flex-start', justifyContent: 'space-between', flexWrap: 'wrap', gap: 12 }}>
                <div>
                    <h1 style={{ fontSize: 22, fontWeight: 800, color: C.textDark, margin: 0, letterSpacing: -0.5 }}>جميع المنشورات</h1>
                    <p style={{ fontSize: 12, color: C.textFaint, marginTop: 4 }}>{filtered.length} منشور</p>
                </div>
                <div style={{ position: 'relative', minWidth: 260 }}>
                    <i className="ti ti-search" style={{ position: 'absolute', top: '50%', right: 12, transform: 'translateY(-50%)', color: C.textFaint, fontSize: 15, pointerEvents: 'none' }} />
                    <input value={search} onChange={e => setSearch(e.target.value)}
                        placeholder="بحث في العنوان أو المحتوى أو الكاتب..."
                        style={{ width: '100%', padding: '9px 38px 9px 13px', border: C.cardBorder, borderRadius: 9, fontSize: 13, outline: 'none', boxSizing: 'border-box', fontFamily: "'Cairo','Inter',sans-serif", background: '#FAFAFA', direction: 'rtl' }} />
                </div>
            </div>

            {/* Tabs */}
            <div style={{ display: 'flex', gap: 8, flexWrap: 'wrap' }}>
                {TABS.map(t => {
                    const active = statusFilter === t.key;
                    const sc = STATUS_CFG[t.key];
                    return (
                        <button key={t.key} onClick={() => setFilter(t.key)} style={{
                            display: 'inline-flex', alignItems: 'center', gap: 6, padding: '7px 16px', borderRadius: 24,
                            border: `1px solid ${active ? (sc?.color ?? C.teal) : 'rgba(0,0,0,0.10)'}`,
                            background: active ? (sc?.bg ?? '#D1FAE5') : '#fff',
                            color: active ? (sc?.color ?? C.teal) : '#64748B',
                            fontSize: 12.5, fontWeight: active ? 700 : 500, cursor: 'pointer',
                            fontFamily: "'Cairo','Inter',sans-serif", transition: 'all 0.13s',
                        }}>
                            <i className={`ti ${t.icon}`} style={{ fontSize: 13 }} />
                            {t.label}
                            <span style={{ background: active ? 'rgba(0,0,0,0.10)' : '#F1F5F9', color: active ? (sc?.color ?? C.teal) : '#64748B', borderRadius: 20, padding: '0 7px', fontSize: 11, fontWeight: 700 }}>
                                {counts[t.key]}
                            </span>
                        </button>
                    );
                })}
            </div>

            {/* Cards grid */}
            {!filtered.length ? (
                <div style={{ background: '#fff', border: C.cardBorder, borderRadius: 18, padding: '72px 24px', textAlign: 'center', color: C.textFaint, boxShadow: C.cardShadow }}>
                    <i className="ti ti-file-off" style={{ fontSize: 52, display: 'block', opacity: 0.12, marginBottom: 14 }} />
                    <div style={{ fontSize: 15, fontWeight: 600, color: C.textMuted, marginBottom: 6 }}>لا توجد منشورات</div>
                    <p style={{ fontSize: 13 }}>جرّب تغيير كلمة البحث أو الفلتر.</p>
                </div>
            ) : (
                <div className="grid-cols-1 lg:grid-cols-2 xl:grid-cols-3" style={{ display: 'grid', gap: 16 }}>
                    {filtered.map((p, i) => (
                        <PostCard key={p.id} post={p} index={i} onDelete={destroy} />
                    ))}
                </div>
            )}
        </AdminLayout>
    );
}
