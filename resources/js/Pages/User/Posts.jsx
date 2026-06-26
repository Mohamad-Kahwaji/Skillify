import { Head, router, useForm } from '@inertiajs/react';
import { useState } from 'react';
import UserLayout from '../../Layouts/UserLayout';

function PostCard({ post, onDelete }) {
    const [confirmDel, setConfirmDel] = useState(false);

    const handleDelete = () => {
        if (confirmDel) {
            router.delete(`/user/posts/${post.id}`, { preserveScroll: true });
        } else {
            setConfirmDel(true);
            setTimeout(() => setConfirmDel(false), 3000);
        }
    };

    return (
        <div style={{ background: '#fff', border: '0.5px solid rgba(0,0,0,0.08)', borderRadius: 14, overflow: 'hidden', transition: 'box-shadow .15s' }}
            onMouseEnter={e => e.currentTarget.style.boxShadow = '0 4px 18px rgba(0,0,0,.07)'}
            onMouseLeave={e => e.currentTarget.style.boxShadow = 'none'}
        >
            <div style={{ display: 'flex', alignItems: 'flex-start', gap: 14, padding: '18px 20px' }}>
                <div style={{ width: 38, height: 38, borderRadius: 10, background: '#F0FDFA', display: 'flex', alignItems: 'center', justifyContent: 'center', fontSize: 18, color: '#0D9488', flexShrink: 0, marginTop: 2 }}>
                    <i className="ti ti-file-text" />
                </div>
                <div style={{ flex: 1, minWidth: 0 }}>
                    <div style={{ fontSize: 14, fontWeight: 700, color: '#0F172A', marginBottom: 4 }}>{post.title}</div>
                    <div style={{ fontSize: 12, color: '#475569', lineHeight: 1.6, display: '-webkit-box', WebkitLineClamp: 2, WebkitBoxOrient: 'vertical', overflow: 'hidden' }}>
                        {post.description}
                    </div>
                    <div style={{ display: 'flex', gap: 12, marginTop: 8, flexWrap: 'wrap' }}>
                        <span style={{ fontSize: 11, color: '#94A3B8', display: 'inline-flex', alignItems: 'center', gap: 4 }}>
                            <i className="ti ti-calendar" /> {new Date(post.created_at).toLocaleDateString('ar', { month: 'short', day: 'numeric', year: 'numeric' })}
                        </span>
                        <span style={{ fontSize: 11, color: '#94A3B8', display: 'inline-flex', alignItems: 'center', gap: 4 }}>
                            <i className="ti ti-eye" /> {post.views ?? 0} مشاهدة
                        </span>
                    </div>
                </div>
                <button onClick={handleDelete} style={{
                    padding: '6px 12px', borderRadius: 8, fontSize: 12, fontWeight: 500, cursor: 'pointer',
                    background: confirmDel ? '#FEF2F2' : 'transparent',
                    border: `1px solid ${confirmDel ? '#F87171' : 'rgba(0,0,0,0.12)'}`,
                    color: confirmDel ? '#B91C1C' : '#475569',
                    flexShrink: 0,
                }}>
                    {confirmDel ? 'تأكيد؟' : <i className="ti ti-trash" />}
                </button>
            </div>
            {post.image && (
                <img src={post.image.startsWith('http') ? post.image : `/storage/${post.image}`}
                    alt={post.title}
                    style={{ width: '100%', maxHeight: 200, objectFit: 'cover', borderTop: '0.5px solid rgba(0,0,0,0.07)', display: 'block' }}
                />
            )}
        </div>
    );
}

export default function Posts({ posts, flash }) {
    const [showForm, setShowForm] = useState(false);
    const { data, setData, post, processing, errors, reset } = useForm({
        title: '', description: '',
    });

    const submit = (e) => {
        e.preventDefault();
        post('/user/posts', {
            onSuccess: () => { reset(); setShowForm(false); },
        });
    };

    return (
        <UserLayout title="منشوراتي">
            <Head title="منشوراتي — Skillify" />

            {flash?.success && (
                <div style={{ background: '#F0FDF4', border: '1px solid #9FE1CB', borderRadius: 10, padding: '10px 16px', color: '#134E4A', fontSize: 13 }}>
                    <i className="ti ti-circle-check" style={{ marginRight: 6 }} />{flash.success}
                </div>
            )}

            <div style={{ display: 'flex', alignItems: 'center', justifyContent: 'space-between' }}>
                <div>
                    <div style={{ fontSize: 20, fontWeight: 600, color: '#0F172A' }}>منشوراتي</div>
                    <div style={{ fontSize: 13, color: '#475569', marginTop: 2 }}>{posts?.length ?? 0} منشور</div>
                </div>
                <button onClick={() => setShowForm(v => !v)} style={{
                    display: 'inline-flex', alignItems: 'center', gap: 8,
                    padding: '10px 18px', background: '#0D9488', color: '#fff',
                    border: 'none', borderRadius: 10, fontSize: 13, fontWeight: 600, cursor: 'pointer',
                }}>
                    <i className="ti ti-plus" /> منشور جديد
                </button>
            </div>

            {/* Create form */}
            {showForm && (
                <div style={{ background: '#fff', border: '1px solid rgba(0,0,0,0.07)', borderRadius: 14, padding: 20 }}>
                    <div style={{ fontSize: 15, fontWeight: 600, marginBottom: 16 }}>إنشاء منشور جديد</div>
                    <form onSubmit={submit} style={{ display: 'flex', flexDirection: 'column', gap: 12 }}>
                        <div>
                            <label style={{ fontSize: 12, fontWeight: 500, color: '#475569', display: 'block', marginBottom: 4 }}>العنوان</label>
                            <input value={data.title} onChange={e => setData('title', e.target.value)}
                                style={{ width: '100%', padding: '9px 12px', border: '0.5px solid rgba(0,0,0,0.12)', borderRadius: 8, fontSize: 13, outline: 'none' }}
                                placeholder="عنوان المنشور..." />
                            {errors.title && <p style={{ fontSize: 11, color: '#EF4444', marginTop: 3 }}>{errors.title}</p>}
                        </div>
                        <div>
                            <label style={{ fontSize: 12, fontWeight: 500, color: '#475569', display: 'block', marginBottom: 4 }}>الوصف</label>
                            <textarea value={data.description} onChange={e => setData('description', e.target.value)} rows={4}
                                style={{ width: '100%', padding: '9px 12px', border: '0.5px solid rgba(0,0,0,0.12)', borderRadius: 8, fontSize: 13, outline: 'none', resize: 'vertical' }}
                                placeholder="ما الذي يدور في ذهنك؟" />
                            {errors.description && <p style={{ fontSize: 11, color: '#EF4444', marginTop: 3 }}>{errors.description}</p>}
                        </div>
                        <div style={{ display: 'flex', gap: 8, justifyContent: 'flex-end' }}>
                            <button type="button" onClick={() => setShowForm(false)} style={{ padding: '8px 16px', borderRadius: 8, border: '1px solid rgba(0,0,0,0.12)', background: 'none', fontSize: 13, cursor: 'pointer' }}>
                                إلغاء
                            </button>
                            <button type="submit" disabled={processing} style={{ padding: '8px 18px', borderRadius: 8, background: '#0D9488', color: '#fff', border: 'none', fontSize: 13, fontWeight: 600, cursor: 'pointer', opacity: processing ? 0.6 : 1 }}>
                                {processing ? 'جارٍ النشر...' : 'نشر'}
                            </button>
                        </div>
                    </form>
                </div>
            )}

            {/* Posts list */}
            {!posts?.length ? (
                <div style={{ display: 'flex', flexDirection: 'column', alignItems: 'center', justifyContent: 'center', padding: '72px 24px', textAlign: 'center' }}>
                    <div style={{ width: 64, height: 64, borderRadius: 18, background: '#F0FDFA', display: 'flex', alignItems: 'center', justifyContent: 'center', fontSize: 28, color: '#0D9488', marginBottom: 18 }}>
                        <i className="ti ti-file-text" />
                    </div>
                    <div style={{ fontSize: 16, fontWeight: 600, marginBottom: 6 }}>لا توجد منشورات بعد</div>
                    <div style={{ fontSize: 13, color: '#94A3B8', marginBottom: 22 }}>شارك منشورك الأول مع المجتمع</div>
                </div>
            ) : (
                <div style={{ display: 'flex', flexDirection: 'column', gap: 12 }}>
                    {posts.map(p => <PostCard key={p.id} post={p} />)}
                </div>
            )}
        </UserLayout>
    );
}
