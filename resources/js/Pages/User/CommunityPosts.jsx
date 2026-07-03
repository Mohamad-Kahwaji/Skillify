import { Head, Link } from '@inertiajs/react';
import { useState } from 'react';
import UserLayout from '../../Layouts/UserLayout';

const AV_COLORS = ['#0D9488','#3B82F6','#8B5CF6','#F59E0B','#EF4444','#EC4899','#0F766E'];

function CommentAvatar({ user, size = 28 }) {
    const [err, setErr] = useState(false);
    const initial = (user?.first_name ?? 'U')[0].toUpperCase();
    const color = AV_COLORS[(user?.id ?? 0) % 7];
    const avatarPath = user?.businesses?.image || user?.profile_photo;
    return (
        <div style={{ width: size, height: size, borderRadius: '50%', background: color, color: '#fff', display: 'flex', alignItems: 'center', justifyContent: 'center', fontSize: size * 0.4, fontWeight: 600, flexShrink: 0, overflow: 'hidden' }}>
            {(avatarPath && !err)
                ? <img src={`/storage/${avatarPath}`} alt="" onError={() => setErr(true)} style={{ width: '100%', height: '100%', objectFit: 'cover' }} />
                : initial
            }
        </div>
    );
}

function PostCard({ post, authId }) {
    const author  = post.user;
    const initial = (author?.first_name ?? 'U')[0].toUpperCase();
    const color   = AV_COLORS[(author?.id ?? 0) % 7];
    const liked   = post.likes?.some(l => l.user_id == authId);
    const [likes, setLikes]             = useState(post.likes?.length ?? 0);
    const [isLiked, setIsLiked]         = useState(liked);
    const [showComments, setShowComments] = useState(false);
    const [comments, setComments]       = useState(post.comments ?? []);
    const [commentCount, setCommentCount] = useState(post.comments?.length ?? 0);
    const [commentText, setCommentText] = useState('');
    const [submitting, setSubmitting]   = useState(false);

    const csrf = () => document.querySelector('meta[name="csrf-token"]')?.content ?? '';

    const toggleLike = () => {
        const next = !isLiked;
        setIsLiked(next);
        setLikes(v => next ? v + 1 : v - 1);
        fetch(`/user/posts/${post.id}/like`, {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': csrf(), 'Accept': 'application/json', 'Content-Type': 'application/json' },
        })
        .then(r => r.json())
        .then(d => { setIsLiked(d.liked); setLikes(d.count); })
        .catch(() => { setIsLiked(!next); setLikes(v => next ? v - 1 : v + 1); });
    };

    const addComment = (e) => {
        e.preventDefault();
        const text = commentText.trim();
        if (!text || submitting) return;
        setSubmitting(true);
        setCommentText('');
        fetch(`/user/posts/${post.id}/comments`, {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': csrf(), 'Accept': 'application/json', 'Content-Type': 'application/json' },
            body: JSON.stringify({ content: text }),
        })
        .then(r => { if (!r.ok) throw new Error(); return r.json(); })
        .then(newComment => {
            setComments(prev => [...prev, newComment]);
            setCommentCount(v => v + 1);
        })
        .catch(() => setCommentText(text))
        .finally(() => setSubmitting(false));
    };

    return (
        <div style={{ background: '#fff', border: '0.5px solid rgba(0,0,0,0.07)', borderRadius: 14, overflow: 'hidden' }}>
            {/* Header */}
            <div style={{ display: 'flex', alignItems: 'center', justifyContent: 'space-between', padding: '14px 20px', borderBottom: '0.5px solid rgba(0,0,0,0.07)', gap: 12 }}>
                <Link href={author?.id ? `/user/users/${author.id}` : '#'} style={{ display: 'flex', alignItems: 'center', gap: 10, textDecoration: 'none' }}>
                    <CommentAvatar user={author} size={34} />
                    <div>
                        <div style={{ fontSize: 13, fontWeight: 600, color: '#0F172A' }}
                            onMouseEnter={e => e.currentTarget.style.color = '#0D9488'}
                            onMouseLeave={e => e.currentTarget.style.color = '#0F172A'}
                        >{author?.first_name} {author?.last_name}</div>
                        <div style={{ fontSize: 11, color: '#94A3B8' }}>{new Date(post.created_at).toLocaleDateString('ar', { month: 'short', day: 'numeric', year: 'numeric' })}</div>
                    </div>
                </Link>
                <div style={{ display: 'flex', alignItems: 'center', gap: 8 }}>
                    <span style={{ display: 'inline-flex', alignItems: 'center', gap: 4, fontSize: 11, color: '#94A3B8' }}>
                        <i className="ti ti-eye" /> {post.views ?? 0}
                    </span>
                    <span style={{ display: 'inline-flex', alignItems: 'center', gap: 4, fontSize: 11, color: '#94A3B8' }}>
                        <i className="ti ti-message-circle" /> {commentCount}
                    </span>
                </div>
            </div>

            {/* Body */}
            <div style={{ padding: '16px 20px' }}>
                <div style={{ fontSize: 15, fontWeight: 700, marginBottom: 8, color: '#0F172A' }}>{post.title}</div>
                <div style={{ fontSize: 13, color: '#475569', lineHeight: 1.65, display: '-webkit-box', WebkitLineClamp: 3, WebkitBoxOrient: 'vertical', overflow: 'hidden' }}>
                    {post.description}
                </div>
            </div>

            {post.image && (
                <img src={post.image.startsWith('http') ? post.image : `/storage/${post.image}`}
                    alt={post.title}
                    style={{ width: '100%', maxHeight: 280, objectFit: 'cover', display: 'block', borderTop: '0.5px solid rgba(0,0,0,0.07)' }}
                />
            )}

            {/* Actions */}
            <div style={{ display: 'flex', alignItems: 'center', gap: 8, padding: '12px 20px', borderTop: '0.5px solid rgba(0,0,0,0.07)' }}>
                <button onClick={toggleLike} style={{
                    display: 'inline-flex', alignItems: 'center', gap: 5,
                    padding: '6px 14px', borderRadius: 8,
                    border: `1px solid ${isLiked ? '#0D9488' : 'rgba(0,0,0,0.12)'}`,
                    background: isLiked ? '#F0FDFA' : 'none',
                    color: isLiked ? '#0D9488' : '#475569',
                    fontSize: 12, fontWeight: 500, cursor: 'pointer',
                }}>
                    <i className={isLiked ? 'ti ti-heart-filled' : 'ti ti-heart'} /> {likes}
                </button>
                <button onClick={() => setShowComments(v => !v)} style={{
                    display: 'inline-flex', alignItems: 'center', gap: 5,
                    padding: '6px 14px', borderRadius: 8, border: '1px solid rgba(0,0,0,0.12)',
                    background: showComments ? '#F0FDFA' : 'none',
                    color: showComments ? '#0D9488' : '#475569',
                    fontSize: 12, fontWeight: 500, cursor: 'pointer',
                }}>
                    <i className="ti ti-message-circle" /> {showComments ? 'إخفاء التعليقات' : 'تعليق'}
                </button>
            </div>

            {/* Comments */}
            {showComments && (
                <div style={{ padding: '0 20px 16px', display: 'flex', flexDirection: 'column', gap: 10 }}>
                    {comments.length === 0 && (
                        <div style={{ fontSize: 12, color: '#94A3B8', textAlign: 'center', padding: '8px 0' }}>لا توجد تعليقات بعد. كن أول من يعلّق!</div>
                    )}
                    {comments.map(c => (
                        <div key={c.id} style={{ display: 'flex', gap: 8, alignItems: 'flex-start' }}>
                            <Link href={c.user?.id ? `/user/users/${c.user.id}` : '#'} style={{ flexShrink: 0, textDecoration: 'none' }}>
                                <CommentAvatar user={c.user} />
                            </Link>
                            <div style={{ background: '#F8FAFC', borderRadius: 8, padding: '6px 10px', fontSize: 12, color: '#0F172A', flex: 1 }}>
                                <Link href={c.user?.id ? `/user/users/${c.user.id}` : '#'} style={{ fontWeight: 700, color: '#0D9488', textDecoration: 'none', marginLeft: 4 }}
                                    onMouseEnter={e => e.currentTarget.style.textDecoration = 'underline'}
                                    onMouseLeave={e => e.currentTarget.style.textDecoration = 'none'}
                                >
                                    {c.user?.first_name} {c.user?.last_name}
                                </Link>
                                {c.content}
                            </div>
                        </div>
                    ))}
                    <form onSubmit={addComment} style={{ display: 'flex', gap: 8 }}>
                        <input
                            value={commentText}
                            onChange={e => setCommentText(e.target.value)}
                            placeholder="اكتب تعليقاً..."
                            disabled={submitting}
                            style={{ flex: 1, padding: '7px 12px', border: '1px solid rgba(0,0,0,0.12)', borderRadius: 8, fontSize: 12, outline: 'none', opacity: submitting ? 0.6 : 1 }}
                        />
                        <button type="submit" disabled={submitting || !commentText.trim()} style={{ padding: '7px 14px', background: '#0D9488', color: '#fff', border: 'none', borderRadius: 8, fontSize: 12, cursor: 'pointer', opacity: (!commentText.trim() || submitting) ? 0.5 : 1 }}>
                            {submitting ? '...' : 'إرسال'}
                        </button>
                    </form>
                </div>
            )}
        </div>
    );
}

export default function CommunityPosts({ posts, authId }) {
    return (
        <UserLayout title="المجتمع">
            <Head title="منشورات المجتمع — Skillify" />
            <div>
                <div style={{ fontSize: 20, fontWeight: 600, color: '#0F172A' }}>منشورات المجتمع</div>
                <div style={{ fontSize: 13, color: '#475569', marginTop: 2 }}>اطلع على ما يشاركه المجتمع</div>
            </div>
            {!posts?.length ? (
                <div style={{ textAlign: 'center', padding: '60px 24px', color: '#94A3B8' }}>
                    <i className="ti ti-news-off" style={{ fontSize: 48, display: 'block', marginBottom: 12, opacity: 0.3 }} />
                    <p style={{ fontSize: 14 }}>لا توجد منشورات في المجتمع بعد.</p>
                </div>
            ) : (
                <div style={{ display: 'flex', flexDirection: 'column', gap: 14 }}>
                    {posts.map(p => <PostCard key={p.id} post={p} authId={authId} />)}
                </div>
            )}
        </UserLayout>
    );
}
