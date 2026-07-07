import { Head, Link, router } from '@inertiajs/react';
import { useState } from 'react';
import SuperAdminLayout from '../../Layouts/SuperAdminLayout';

const AV_COLORS = ['#6D28D9','#0D9488','#2563EB','#D97706','#DC2626','#0891B2','#7C3AED','#059669'];

function CommentAvatar({ user, size = 28 }) {
    const [err, setErr] = useState(false);
    const initial = (user?.first_name ?? 'U')[0].toUpperCase();
    const color = AV_COLORS[(user?.id ?? 0) % AV_COLORS.length];
    const avatarPath = user?.businesses?.image;
    return (
        <div style={{ width: size, height: size, borderRadius: '50%', background: color, color: '#fff', display: 'flex', alignItems: 'center', justifyContent: 'center', fontSize: size * 0.4, fontWeight: 600, flexShrink: 0, overflow: 'hidden' }}>
            {(avatarPath && !err)
                ? <img src={`/storage/${avatarPath}`} alt="" onError={() => setErr(true)} style={{ width: '100%', height: '100%', objectFit: 'cover' }} />
                : initial
            }
        </div>
    );
}

function CommentRow({ comment, onDelete, depth = 0 }) {
    return (
        <div style={{ display: 'flex', gap: 8, alignItems: 'flex-start' }}>
            <Link href={comment.user?.id ? `/super-admin/users/${comment.user.id}/profile` : '#'} style={{ flexShrink: 0, textDecoration: 'none' }}>
                <CommentAvatar user={comment.user} size={depth ? 24 : 28} />
            </Link>
            <div style={{ flex: 1, minWidth: 0 }}>
                <div style={{ background: '#F8FAFC', borderRadius: 8, padding: '6px 10px', fontSize: 12, color: '#0F172A', display: 'flex', alignItems: 'flex-start', gap: 8 }}>
                    <div style={{ flex: 1 }}>
                        <Link href={comment.user?.id ? `/super-admin/users/${comment.user.id}/profile` : '#'} style={{ fontWeight: 700, color: '#7C3AED', textDecoration: 'none', marginLeft: 4 }}>
                            {comment.user?.first_name} {comment.user?.last_name}
                        </Link>
                        {comment.content}
                    </div>
                    <button onClick={() => onDelete(comment.id)} title="حذف التعليق" style={{
                        background: 'none', border: 'none', color: '#DC2626', cursor: 'pointer',
                        fontSize: 12, padding: 2, flexShrink: 0, opacity: 0.6,
                    }}
                        onMouseEnter={e => e.currentTarget.style.opacity = 1}
                        onMouseLeave={e => e.currentTarget.style.opacity = 0.6}
                    >
                        <i className="ti ti-trash" />
                    </button>
                </div>
                {comment.replies?.length > 0 && (
                    <div style={{ marginTop: 8, display: 'flex', flexDirection: 'column', gap: 8, borderInlineStart: '2px solid #E2E8F0', paddingInlineStart: 12, marginInlineStart: 4 }}>
                        {comment.replies.map(r => (
                            <CommentRow key={r.id} comment={r} onDelete={onDelete} depth={depth + 1} />
                        ))}
                    </div>
                )}
            </div>
        </div>
    );
}

const STATUS_CFG = {
    published: { label: 'منشور',    color: '#059669', bg: '#D1FAE5', icon: 'ti-circle-check' },
    draft:     { label: 'مسودة',    color: '#D97706', bg: '#FEF3C7', icon: 'ti-pencil' },
    archived:  { label: 'مؤرشف',   color: '#6B7280', bg: '#F3F4F6', icon: 'ti-archive' },
};
const DEFAULT_STATUS = { label: 'منشور', color: '#059669', bg: '#D1FAE5', icon: 'ti-circle-check' };

function timeAgo(dateStr) {
    if (!dateStr) return '';
    const diff = Math.floor((Date.now() - new Date(dateStr)) / 1000);
    if (diff < 60)     return 'الآن';
    if (diff < 3600)   return `منذ ${Math.floor(diff / 60)} دقيقة`;
    if (diff < 86400)  return `منذ ${Math.floor(diff / 3600)} ساعة`;
    if (diff < 604800) return `منذ ${Math.floor(diff / 86400)} يوم`;
    return new Date(dateStr).toLocaleDateString('ar', { day: 'numeric', month: 'short', year: 'numeric' });
}

function PostCard({ post, index, onDelete, onDeleteComment }) {
    const [expanded, setExpanded]   = useState(false);
    const [imgError, setImgError]   = useState(false);
    const [showComments, setShowComments] = useState(false);
    const sc  = STATUS_CFG[post.status] ?? DEFAULT_STATUS;
    const av  = AV_COLORS[index % AV_COLORS.length];
    const av2 = AV_COLORS[(index + 2) % AV_COLORS.length];
    const desc = post.description ?? '';
    const isLong = desc.length > 200;

    const imgSrc = post.image
        ? (post.image.startsWith('http') ? post.image : `/storage/${post.image}`)
        : null;
    const showImage = imgSrc && !imgError;

    return (
        <div style={{
            background: '#fff',
            border: '1px solid rgba(0,0,0,0.07)',
            borderRadius: 18,
            overflow: 'hidden',
            boxShadow: '0 2px 12px rgba(0,0,0,0.05)',
            transition: 'box-shadow 0.2s, transform 0.2s',
        }}
            onMouseEnter={e => { e.currentTarget.style.boxShadow = '0 8px 28px rgba(0,0,0,0.1)'; e.currentTarget.style.transform = 'translateY(-2px)'; }}
            onMouseLeave={e => { e.currentTarget.style.boxShadow = '0 2px 12px rgba(0,0,0,0.05)'; e.currentTarget.style.transform = 'translateY(0)'; }}
        >
            {/* Post image */}
            {showImage ? (
                <div style={{ position: 'relative', width: '100%', height: 200, overflow: 'hidden', background: '#F1F5F9' }}>
                    <img src={imgSrc} alt={post.title ?? 'صورة المنشور'}
                        onError={() => setImgError(true)}
                        style={{ width: '100%', height: '100%', objectFit: 'cover', display: 'block' }} />
                    {/* gradient overlay */}
                    <div style={{ position: 'absolute', inset: 0, background: 'linear-gradient(to top, rgba(0,0,0,0.45) 0%, transparent 55%)', pointerEvents: 'none' }} />
                    {/* title on image if exists */}
                    {post.title && (
                        <div style={{ position: 'absolute', bottom: 12, right: 16, left: 16, fontSize: 14, fontWeight: 800, color: '#fff', textShadow: '0 1px 4px rgba(0,0,0,0.5)', lineHeight: 1.4 }}>
                            {post.title}
                        </div>
                    )}
                </div>
            ) : (
                /* Placeholder when no image */
                <div style={{ width: '100%', height: 90, background: `linear-gradient(135deg,${av}22,${av2}22)`, borderBottom: '1px solid rgba(0,0,0,0.05)', display: 'flex', alignItems: 'center', justifyContent: 'center' }}>
                    <i className="ti ti-photo-off" style={{ fontSize: 28, color: `${av}66` }} />
                </div>
            )}

            <div style={{ padding: '16px 18px' }}>
                {/* Author row */}
                <div style={{ display: 'flex', alignItems: 'center', gap: 11, marginBottom: 12 }}>
                    <Link href={post.user?.id ? `/super-admin/users/${post.user.id}/profile` : '#'} style={{
                        width: 38, height: 38, borderRadius: '50%', flexShrink: 0,
                        background: `linear-gradient(135deg,${av},${AV_COLORS[(index + 2) % AV_COLORS.length]})`,
                        display: 'flex', alignItems: 'center', justifyContent: 'center',
                        fontSize: 14, fontWeight: 700, color: '#fff', textDecoration: 'none',
                    }}>
                        {(post.user?.first_name?.[0] ?? 'U').toUpperCase()}
                    </Link>
                    <div style={{ flex: 1, minWidth: 0 }}>
                        <Link href={post.user?.id ? `/super-admin/users/${post.user.id}/profile` : '#'} style={{ fontSize: 13.5, fontWeight: 700, color: '#0F172A', textDecoration: 'none' }}>
                            {post.user?.first_name} {post.user?.last_name}
                        </Link>
                        <div style={{ fontSize: 11, color: '#94A3B8', marginTop: 1 }}>
                            {post.user?.email ?? ''}
                        </div>
                    </div>
                    {/* Status + time */}
                    <div style={{ display: 'flex', alignItems: 'center', gap: 8, flexShrink: 0 }}>
                        <span style={{ fontSize: 10, fontWeight: 700, padding: '3px 9px', borderRadius: 20, background: sc.bg, color: sc.color, display: 'flex', alignItems: 'center', gap: 4 }}>
                            <i className={`ti ${sc.icon}`} style={{ fontSize: 10 }} />{sc.label}
                        </span>
                        <span style={{ fontSize: 11, color: '#94A3B8', whiteSpace: 'nowrap' }}>
                            {timeAgo(post.created_at)}
                        </span>
                    </div>
                    {/* Delete */}
                    <button onClick={() => onDelete(post.id)} title="حذف المنشور" style={{
                        width: 32, height: 32, borderRadius: 8, border: '1px solid #FEE2E2',
                        background: '#FFF5F5', color: '#DC2626', fontSize: 14,
                        display: 'flex', alignItems: 'center', justifyContent: 'center',
                        cursor: 'pointer', flexShrink: 0, transition: 'all 0.13s',
                    }}
                        onMouseEnter={e => { e.currentTarget.style.background = '#FEE2E2'; e.currentTarget.style.transform = 'scale(1.08)'; }}
                        onMouseLeave={e => { e.currentTarget.style.background = '#FFF5F5'; e.currentTarget.style.transform = 'scale(1)'; }}
                    >
                        <i className="ti ti-trash" />
                    </button>
                </div>

                {/* Title — يظهر هنا فقط إذا ما في صورة */}
                {post.title && !showImage && (
                    <div style={{ fontSize: 15, fontWeight: 800, color: '#0F172A', marginBottom: 8, letterSpacing: -0.2 }}>
                        {post.title}
                    </div>
                )}

                {/* Description */}
                {desc && (
                    <div style={{ marginBottom: 12 }}>
                        <p style={{
                            fontSize: 13.5, color: '#334155', lineHeight: 1.75, margin: 0,
                            display: expanded ? 'block' : '-webkit-box',
                            WebkitLineClamp: expanded ? 'unset' : 3,
                            WebkitBoxOrient: 'vertical',
                            overflow: expanded ? 'visible' : 'hidden',
                        }}>
                            {desc}
                        </p>
                        {isLong && (
                            <button onClick={() => setExpanded(v => !v)} style={{
                                background: 'none', border: 'none', color: '#7C3AED',
                                fontSize: 12, fontWeight: 600, cursor: 'pointer', marginTop: 4, padding: 0,
                                fontFamily: "'Cairo','Inter',sans-serif",
                            }}>
                                {expanded ? 'عرض أقل ↑' : 'عرض المزيد ↓'}
                            </button>
                        )}
                    </div>
                )}

                {/* Stats row */}
                <div style={{ display: 'flex', alignItems: 'center', gap: 16, paddingTop: 10, borderTop: '0.5px solid rgba(0,0,0,0.06)', flexWrap: 'wrap' }}>
                    <div style={{ display: 'flex', alignItems: 'center', gap: 5, fontSize: 12, color: '#64748B' }}>
                        <i className="ti ti-heart-filled" style={{ color: '#EF4444', fontSize: 14 }} />
                        <span><strong style={{ color: '#0F172A' }}>{post.likes_count ?? 0}</strong> إعجاب</span>
                    </div>
                    <button onClick={() => setShowComments(v => !v)} style={{
                        display: 'flex', alignItems: 'center', gap: 5, fontSize: 12, color: showComments ? '#7C3AED' : '#64748B',
                        background: 'none', border: 'none', cursor: post.comments?.length ? 'pointer' : 'default', padding: 0,
                        fontFamily: "'Cairo','Inter',sans-serif",
                    }}>
                        <i className="ti ti-message-circle" style={{ color: '#3B82F6', fontSize: 14 }} />
                        <span><strong style={{ color: '#0F172A' }}>{post.comments_count ?? 0}</strong> تعليق</span>
                        {post.comments?.length > 0 && <i className={`ti ti-chevron-${showComments ? 'up' : 'down'}`} style={{ fontSize: 12 }} />}
                    </button>
                    {post.views != null && (
                        <div style={{ display: 'flex', alignItems: 'center', gap: 5, fontSize: 12, color: '#64748B' }}>
                            <i className="ti ti-eye" style={{ color: '#7C3AED', fontSize: 14 }} />
                            <span><strong style={{ color: '#0F172A' }}>{Number(post.views).toLocaleString()}</strong> مشاهدة</span>
                        </div>
                    )}
                    {post.post_date && (
                        <div style={{ marginRight: 'auto', display: 'flex', alignItems: 'center', gap: 5, fontSize: 11, color: '#94A3B8' }}>
                            <i className="ti ti-calendar" style={{ fontSize: 13 }} />
                            {new Date(post.post_date).toLocaleDateString('ar', { day: 'numeric', month: 'long', year: 'numeric' })}
                        </div>
                    )}
                </div>

                {/* Comments list */}
                {showComments && post.comments?.length > 0 && (
                    <div style={{ marginTop: 12, paddingTop: 12, borderTop: '0.5px solid rgba(0,0,0,0.06)', display: 'flex', flexDirection: 'column', gap: 10 }}>
                        {post.comments.map(c => (
                            <CommentRow key={c.id} comment={c} onDelete={onDeleteComment} />
                        ))}
                    </div>
                )}
            </div>
        </div>
    );
}

/* ═══════════════════════════════════════════════════════════ */
export default function Posts({ posts }) {
    const [search, setSearch] = useState('');
    const [statusFilter, setStatusFilter] = useState('all');

    const filtered = (posts ?? []).filter(p => {
        const matchSearch = `${p.title ?? ''} ${p.description ?? ''} ${p.user?.first_name ?? ''} ${p.user?.last_name ?? ''}`.toLowerCase().includes(search.toLowerCase());
        const matchStatus = statusFilter === 'all' || (p.status ?? 'published') === statusFilter;
        return matchSearch && matchStatus;
    });

    const counts = {
        all:       (posts ?? []).length,
        published: (posts ?? []).filter(p => !p.status || p.status === 'published').length,
        draft:     (posts ?? []).filter(p => p.status === 'draft').length,
        archived:  (posts ?? []).filter(p => p.status === 'archived').length,
    };

    const destroy = (id) => {
        if (!confirm('حذف هذا المنشور نهائياً؟')) return;
        router.delete(`/super-admin/posts/${id}`, { preserveScroll: true });
    };

    const destroyComment = (id) => {
        if (!confirm('حذف هذا التعليق نهائياً؟')) return;
        router.delete(`/super-admin/comments/${id}`, { preserveScroll: true });
    };

    const TABS = [
        { key: 'all',       label: 'الكل',    color: '#1E1B4B', bg: '#EEF2FF', border: '#C7D2FE', icon: 'ti-apps' },
        { key: 'published', label: 'منشور',   color: '#059669', bg: '#D1FAE5', border: '#A7F3D0', icon: 'ti-circle-check' },
        { key: 'draft',     label: 'مسودة',   color: '#D97706', bg: '#FEF3C7', border: '#FDE68A', icon: 'ti-pencil' },
        { key: 'archived',  label: 'مؤرشف',  color: '#6B7280', bg: '#F3F4F6', border: '#E5E7EB', icon: 'ti-archive' },
    ];

    return (
        <SuperAdminLayout title="المنشورات">
            <Head title="المنشورات — Skillify" />

            {/* Header */}
            <div>
                <h1 style={{ fontSize: 22, fontWeight: 800, color: '#1E1B4B', margin: 0, letterSpacing: -0.5 }}>جميع المنشورات</h1>
                <p style={{ fontSize: 12, color: '#94A3B8', marginTop: 4 }}>{filtered.length} منشور</p>
            </div>

            {/* Filters */}
            <div style={{ display: 'flex', alignItems: 'center', gap: 10, flexWrap: 'wrap' }}>
                <div style={{ position: 'relative', flex: 1, minWidth: 200 }}>
                    <i className="ti ti-search" style={{ position: 'absolute', top: '50%', right: 12, transform: 'translateY(-50%)', color: '#94A3B8', fontSize: 15, pointerEvents: 'none' }} />
                    <input value={search} onChange={e => setSearch(e.target.value)} placeholder="بحث في العنوان أو المحتوى أو الكاتب..."
                        style={{ width: '100%', padding: '9px 38px 9px 13px', border: '1px solid rgba(0,0,0,0.11)', borderRadius: 9, fontSize: 13, outline: 'none', boxSizing: 'border-box', fontFamily: "'Cairo','Inter',sans-serif", background: '#FAFAFA' }} />
                </div>
                {TABS.map(tab => (
                    <button key={tab.key} onClick={() => setStatusFilter(tab.key)} style={{
                        display: 'inline-flex', alignItems: 'center', gap: 5, padding: '7px 14px', borderRadius: 24,
                        border: `1px solid ${statusFilter === tab.key ? tab.color : 'rgba(0,0,0,0.10)'}`,
                        background: statusFilter === tab.key ? tab.bg : '#fff',
                        color: statusFilter === tab.key ? tab.color : '#64748B',
                        fontSize: 12, fontWeight: statusFilter === tab.key ? 700 : 500, cursor: 'pointer',
                        fontFamily: "'Cairo','Inter',sans-serif", transition: 'all 0.13s',
                    }}>
                        <i className={`ti ${tab.icon}`} style={{ fontSize: 13 }} />
                        {tab.label} ({counts[tab.key]})
                    </button>
                ))}
            </div>

            {/* Posts grid */}
            {!filtered.length ? (
                <div style={{ background: '#fff', border: '0.5px solid rgba(0,0,0,0.07)', borderRadius: 16, padding: '72px 24px', textAlign: 'center', color: '#94A3B8' }}>
                    <i className="ti ti-file-off" style={{ fontSize: 52, display: 'block', opacity: 0.12, marginBottom: 14 }} />
                    <div style={{ fontSize: 15, fontWeight: 600, color: '#64748B', marginBottom: 6 }}>لا توجد منشورات</div>
                    <p style={{ fontSize: 13 }}>جرّب تغيير كلمة البحث أو الفلتر.</p>
                </div>
            ) : (
                <div style={{ display: 'grid', gridTemplateColumns: 'repeat(auto-fill,minmax(420px,1fr))', gap: 16 }}>
                    {filtered.map((p, i) => (
                        <PostCard key={p.id} post={p} index={i} onDelete={destroy} onDeleteComment={destroyComment} />
                    ))}
                </div>
            )}
        </SuperAdminLayout>
    );
}
