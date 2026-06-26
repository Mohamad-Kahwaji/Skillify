import { Head, router, useForm } from '@inertiajs/react';
import { useState } from 'react';
import UserLayout from '../../Layouts/UserLayout';

const AV_COLORS = ['#0D9488','#3B82F6','#8B5CF6','#F59E0B','#EF4444','#EC4899','#0F766E'];

function PostCard({ post, authId }) {
    const author  = post.user;
    const initial = (author?.first_name ?? 'U')[0].toUpperCase();
    const color   = AV_COLORS[(author?.id ?? 0) % 7];
    const liked   = post.likes?.some(l => l.user_id == authId);
    const [likes, setLikes]       = useState(post.likes?.length ?? 0);
    const [isLiked, setIsLiked]   = useState(liked);
    const [showComments, setShowComments] = useState(false);
    const { data, setData, post: submitComment, processing } = useForm({ body: '' });

    const toggleLike = () => {
        router.post(`/user/posts/${post.id}/like`, {}, {
            preserveScroll: true,
            onSuccess: () => { setIsLiked(v => !v); setLikes(v => isLiked ? v - 1 : v + 1); },
        });
    };

    const addComment = (e) => {
        e.preventDefault();
        submitComment(`/user/posts/${post.id}/comments`, {
            preserveScroll: true,
            onSuccess: () => setData('body', ''),
        });
    };

    return (
        <div style={{ background: '#fff', border: '0.5px solid rgba(0,0,0,0.07)', borderRadius: 14, overflow: 'hidden' }}>
            <div style={{ display: 'flex', alignItems: 'center', justifyContent: 'space-between', padding: '14px 20px', borderBottom: '0.5px solid rgba(0,0,0,0.07)', gap: 12 }}>
                <div style={{ display: 'flex', alignItems: 'center', gap: 10 }}>
                    <div style={{ width: 36, height: 36, borderRadius: '50%', background: color, display: 'flex', alignItems: 'center', justifyContent: 'center', fontSize: 13, fontWeight: 600, color: '#fff', flexShrink: 0 }}>
                        {initial}
                    </div>
                    <div>
                        <div style={{ fontSize: 13, fontWeight: 600, color: '#0F172A' }}>{author?.first_name} {author?.last_name}</div>
                        <div style={{ fontSize: 11, color: '#94A3B8' }}>{new Date(post.created_at).toLocaleDateString('ar', { month: 'short', day: 'numeric', year: 'numeric' })}</div>
                    </div>
                </div>
                <div style={{ display: 'flex', gap: 12 }}>
                    <span style={{ fontSize: 11, color: '#94A3B8', display: 'inline-flex', alignItems: 'center', gap: 4 }}><i className="ti ti-eye" /> {post.views ?? 0}</span>
                    <span style={{ fontSize: 11, color: '#94A3B8', display: 'inline-flex', alignItems: 'center', gap: 4 }}><i className="ti ti-message-circle" /> {post.comments?.length ?? 0}</span>
                </div>
            </div>

            <div style={{ padding: '16px 20px' }}>
                <div style={{ fontSize: 15, fontWeight: 700, marginBottom: 8, color: '#0F172A' }}>{post.title}</div>
                <div style={{ fontSize: 13, color: '#475569', lineHeight: 1.65, display: '-webkit-box', WebkitLineClamp: 3, WebkitBoxOrient: 'vertical', overflow: 'hidden' }}>
                    {post.description}
                </div>
            </div>

            {post.image && (
                <img src={post.image.startsWith('http') ? post.image : `/storage/${post.image}`}
                    alt={post.title} style={{ width: '100%', maxHeight: 280, objectFit: 'cover', display: 'block', borderTop: '0.5px solid rgba(0,0,0,0.07)' }} />
            )}

            <div style={{ display: 'flex', alignItems: 'center', gap: 8, padding: '12px 20px', borderTop: '0.5px solid rgba(0,0,0,0.07)' }}>
                <button onClick={toggleLike} style={{
                    display: 'inline-flex', alignItems: 'center', gap: 5, padding: '6px 14px', borderRadius: 8,
                    border: `1px solid ${isLiked ? '#0D9488' : 'rgba(0,0,0,0.12)'}`,
                    background: isLiked ? '#F0FDFA' : 'none', color: isLiked ? '#0D9488' : '#475569', fontSize: 12, fontWeight: 500, cursor: 'pointer',
                }}>
                    <i className={isLiked ? 'ti ti-heart-filled' : 'ti ti-heart'} /> {likes}
                </button>
                <button onClick={() => setShowComments(v => !v)} style={{
                    display: 'inline-flex', alignItems: 'center', gap: 5, padding: '6px 14px', borderRadius: 8,
                    border: '1px solid rgba(0,0,0,0.12)', background: 'none', color: '#475569', fontSize: 12, fontWeight: 500, cursor: 'pointer',
                }}>
                    <i className="ti ti-message-circle" /> تعليق
                </button>
            </div>

            {showComments && (
                <div style={{ padding: '0 20px 16px', display: 'flex', flexDirection: 'column', gap: 10 }}>
                    {(post.comments ?? []).map(c => (
                        <div key={c.id} style={{ display: 'flex', gap: 8, alignItems: 'flex-start' }}>
                            <div style={{ width: 28, height: 28, borderRadius: '50%', background: '#0D9488', color: '#fff', display: 'flex', alignItems: 'center', justifyContent: 'center', fontSize: 11, fontWeight: 600, flexShrink: 0 }}>
                                {(c.user?.first_name ?? 'U')[0].toUpperCase()}
                            </div>
                            <div style={{ background: '#F8FAFC', borderRadius: 8, padding: '6px 10px', fontSize: 12, color: '#0F172A', flex: 1 }}>
                                <span style={{ fontWeight: 600 }}>{c.user?.first_name} </span>{c.body}
                            </div>
                        </div>
                    ))}
                    <form onSubmit={addComment} style={{ display: 'flex', gap: 8 }}>
                        <input value={data.body} onChange={e => setData('body', e.target.value)} placeholder="اكتب تعليقاً..."
                            style={{ flex: 1, padding: '7px 12px', border: '1px solid rgba(0,0,0,0.12)', borderRadius: 8, fontSize: 12, outline: 'none' }} />
                        <button type="submit" disabled={processing || !data.body} style={{ padding: '7px 14px', background: '#0D9488', color: '#fff', border: 'none', borderRadius: 8, fontSize: 12, cursor: 'pointer', opacity: (!data.body || processing) ? 0.5 : 1 }}>
                            إرسال
                        </button>
                    </form>
                </div>
            )}
        </div>
    );
}

export default function AllPosts({ posts, authId }) {
    return (
        <UserLayout title="المجتمع">
            <Head title="جميع المنشورات — Skillify" />
            <div>
                <div style={{ fontSize: 20, fontWeight: 600, color: '#0F172A' }}>جميع المنشورات</div>
                <div style={{ fontSize: 13, color: '#475569', marginTop: 2 }}>تصفح منشورات المجتمع</div>
            </div>
            {!posts?.length ? (
                <div style={{ textAlign: 'center', padding: '60px 24px', color: '#94A3B8' }}>
                    <i className="ti ti-news-off" style={{ fontSize: 48, display: 'block', marginBottom: 12, opacity: 0.3 }} />
                    <p style={{ fontSize: 14 }}>لا توجد منشورات بعد.</p>
                </div>
            ) : (
                <div style={{ display: 'flex', flexDirection: 'column', gap: 14 }}>
                    {posts.map(p => <PostCard key={p.id} post={p} authId={authId} />)}
                </div>
            )}
        </UserLayout>
    );
}
