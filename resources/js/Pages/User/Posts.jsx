import { Head, router, useForm } from '@inertiajs/react';
import { useState, useRef } from 'react';
import UserLayout from '../../Layouts/UserLayout';

const T = '#0D9488';

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
        <div style={{
            background: '#fff', border: '0.5px solid rgba(0,0,0,0.08)', borderRadius: 14,
            overflow: 'hidden', transition: 'box-shadow .15s',
        }}
            onMouseEnter={e => e.currentTarget.style.boxShadow = '0 4px 18px rgba(0,0,0,.07)'}
            onMouseLeave={e => e.currentTarget.style.boxShadow = 'none'}
        >
            <div style={{ display: 'flex', alignItems: 'flex-start', gap: 14, padding: '16px 20px' }}>
                <div style={{
                    width: 40, height: 40, borderRadius: 11, background: '#F0FDFA',
                    display: 'flex', alignItems: 'center', justifyContent: 'center',
                    fontSize: 19, color: T, flexShrink: 0, marginTop: 1,
                }}>
                    <i className="ti ti-file-text" />
                </div>

                <div style={{ flex: 1, minWidth: 0 }}>
                    <div style={{ display: 'flex', alignItems: 'center', gap: 8, marginBottom: 4, flexWrap: 'wrap' }}>
                        <span style={{ fontSize: 14, fontWeight: 700, color: '#0F172A' }}>{post.title}</span>
                        {post.active_type?.name && (
                            <span style={{ fontSize: 10, fontWeight: 600, color: T, background: '#F0FDFA', border: `1px solid rgba(13,148,136,0.2)`, borderRadius: 6, padding: '2px 8px' }}>
                                {post.active_type.name}
                            </span>
                        )}
                    </div>
                    <div style={{ fontSize: 12, color: '#475569', lineHeight: 1.65, display: '-webkit-box', WebkitLineClamp: 2, WebkitBoxOrient: 'vertical', overflow: 'hidden', marginBottom: 8 }}>
                        {post.description}
                    </div>
                    <div style={{ display: 'flex', gap: 14, flexWrap: 'wrap' }}>
                        <span style={{ fontSize: 11, color: '#94A3B8', display: 'inline-flex', alignItems: 'center', gap: 4 }}>
                            <i className="ti ti-calendar" />
                            {new Date(post.created_at).toLocaleDateString('ar', { month: 'short', day: 'numeric', year: 'numeric' })}
                        </span>
                        <span style={{ fontSize: 11, color: '#94A3B8', display: 'inline-flex', alignItems: 'center', gap: 4 }}>
                            <i className="ti ti-eye" /> {post.views ?? 0} مشاهدة
                        </span>
                    </div>
                </div>

                <button onClick={handleDelete} style={{
                    padding: '6px 12px', borderRadius: 8, fontSize: 12, fontWeight: 500, cursor: 'pointer',
                    background: confirmDel ? '#FEF2F2' : 'transparent',
                    border: `1px solid ${confirmDel ? '#F87171' : 'rgba(0,0,0,0.1)'}`,
                    color: confirmDel ? '#B91C1C' : '#94A3B8',
                    flexShrink: 0, transition: 'all .15s',
                }}>
                    {confirmDel ? 'تأكيد الحذف؟' : <i className="ti ti-trash" style={{ fontSize: 15 }} />}
                </button>
            </div>
        </div>
    );
}

export default function Posts({ posts, activeTypes }) {
    const [showForm, setShowForm]     = useState(false);
    const [imgPreview, setImgPreview] = useState(null);
    const fileRef                     = useRef(null);

    const { data, setData, post, processing, errors, reset } = useForm({
        title: '', description: '', active_type_id: '', image: null,
    });

    const handleImageChange = (e) => {
        const file = e.target.files[0];
        if (!file) return;
        setData('image', file);
        const reader = new FileReader();
        reader.onload = (ev) => setImgPreview(ev.target.result);
        reader.readAsDataURL(file);
    };

    const removeImage = () => {
        setData('image', null);
        setImgPreview(null);
        if (fileRef.current) fileRef.current.value = '';
    };

    const submit = (e) => {
        e.preventDefault();
        post('/user/posts', {
            forceFormData: true,
            onSuccess: () => { reset(); setShowForm(false); setImgPreview(null); },
        });
    };

    const inputStyle = {
        width: '100%', padding: '10px 13px',
        border: '1px solid rgba(0,0,0,0.1)', borderRadius: 9,
        fontSize: 13, outline: 'none', background: '#FAFAFA',
        fontFamily: 'inherit', transition: 'border-color .15s',
    };

    return (
        <UserLayout title="منشوراتي">
            <Head title="منشوراتي — Skillify" />

            {/* Header */}
            <div style={{ display: 'flex', alignItems: 'center', justifyContent: 'space-between', flexWrap: 'wrap', gap: 12 }}>
                <div>
                    <div style={{ fontSize: 20, fontWeight: 700, color: '#0F172A' }}>منشوراتي</div>
                    <div style={{ fontSize: 13, color: '#64748B', marginTop: 2 }}>{posts?.length ?? 0} منشور</div>
                </div>
                <button onClick={() => setShowForm(v => !v)} style={{
                    display: 'inline-flex', alignItems: 'center', gap: 8,
                    padding: '10px 20px', background: showForm ? '#0F766E' : T,
                    color: '#fff', border: 'none', borderRadius: 10,
                    fontSize: 13, fontWeight: 600, cursor: 'pointer', transition: 'background .15s',
                }}>
                    <i className={`ti ${showForm ? 'ti-x' : 'ti-plus'}`} />
                    {showForm ? 'إلغاء' : 'منشور جديد'}
                </button>
            </div>

            {/* Create form */}
            {showForm && (
                <div style={{
                    background: '#fff', border: '1px solid rgba(13,148,136,0.15)',
                    borderRadius: 16, padding: 24,
                    boxShadow: '0 4px 24px rgba(13,148,136,0.07)',
                }}>
                    <div style={{ display: 'flex', alignItems: 'center', gap: 10, marginBottom: 20 }}>
                        <div style={{ width: 36, height: 36, borderRadius: 10, background: '#F0FDFA', display: 'flex', alignItems: 'center', justifyContent: 'center', color: T, fontSize: 18 }}>
                            <i className="ti ti-pencil-plus" />
                        </div>
                        <div style={{ fontSize: 15, fontWeight: 700, color: '#0F172A' }}>إنشاء منشور جديد</div>
                    </div>

                    <form onSubmit={submit} style={{ display: 'flex', flexDirection: 'column', gap: 14 }}>
                        {/* Post type */}
                        <div>
                            <label style={{ fontSize: 12, fontWeight: 600, color: '#475569', display: 'block', marginBottom: 5 }}>
                                نوع المنشور <span style={{ color: '#EF4444' }}>*</span>
                            </label>
                            <select
                                value={data.active_type_id}
                                onChange={e => setData('active_type_id', e.target.value)}
                                style={{ ...inputStyle, cursor: 'pointer' }}
                            >
                                <option value="">— اختر نوع المنشور —</option>
                                {(activeTypes ?? []).map(t => (
                                    <option key={t.id} value={t.id}>{t.name}</option>
                                ))}
                            </select>
                            {errors.active_type_id && <p style={{ fontSize: 11, color: '#EF4444', marginTop: 4 }}>{errors.active_type_id}</p>}
                        </div>

                        {/* Title */}
                        <div>
                            <label style={{ fontSize: 12, fontWeight: 600, color: '#475569', display: 'block', marginBottom: 5 }}>
                                العنوان <span style={{ color: '#EF4444' }}>*</span>
                            </label>
                            <input
                                value={data.title}
                                onChange={e => setData('title', e.target.value)}
                                placeholder="اكتب عنواناً واضحاً..."
                                style={inputStyle}
                                onFocus={e => e.target.style.borderColor = T}
                                onBlur={e => e.target.style.borderColor = 'rgba(0,0,0,0.1)'}
                            />
                            {errors.title && <p style={{ fontSize: 11, color: '#EF4444', marginTop: 4 }}>{errors.title}</p>}
                        </div>

                        {/* Description */}
                        <div>
                            <label style={{ fontSize: 12, fontWeight: 600, color: '#475569', display: 'block', marginBottom: 5 }}>
                                التفاصيل <span style={{ color: '#EF4444' }}>*</span>
                            </label>
                            <textarea
                                value={data.description}
                                onChange={e => setData('description', e.target.value)}
                                rows={4}
                                placeholder="اكتب تفاصيل منشورك هنا..."
                                style={{ ...inputStyle, resize: 'vertical', lineHeight: 1.6 }}
                                onFocus={e => e.target.style.borderColor = T}
                                onBlur={e => e.target.style.borderColor = 'rgba(0,0,0,0.1)'}
                            />
                            {errors.description && <p style={{ fontSize: 11, color: '#EF4444', marginTop: 4 }}>{errors.description}</p>}
                        </div>

                        {/* Image */}
                        <div>
                            <label style={{ fontSize: 12, fontWeight: 600, color: '#475569', display: 'block', marginBottom: 5 }}>
                                صورة المنشور <span style={{ fontSize: 11, color: '#94A3B8', fontWeight: 400 }}>(اختياري)</span>
                            </label>

                            {imgPreview ? (
                                <div style={{ position: 'relative', borderRadius: 10, overflow: 'hidden', border: '1px solid rgba(0,0,0,0.08)' }}>
                                    <img src={imgPreview} alt="preview" style={{ width: '100%', maxHeight: 220, objectFit: 'cover', display: 'block' }} />
                                    <button type="button" onClick={removeImage} style={{
                                        position: 'absolute', top: 8, left: 8,
                                        width: 30, height: 30, borderRadius: '50%',
                                        background: 'rgba(0,0,0,0.55)', color: '#fff',
                                        border: 'none', cursor: 'pointer', fontSize: 15,
                                        display: 'flex', alignItems: 'center', justifyContent: 'center',
                                    }}>
                                        <i className="ti ti-x" />
                                    </button>
                                </div>
                            ) : (
                                <div
                                    onClick={() => fileRef.current?.click()}
                                    style={{
                                        border: '1.5px dashed rgba(13,148,136,0.35)', borderRadius: 10,
                                        padding: '28px 20px', textAlign: 'center', cursor: 'pointer',
                                        background: '#FAFFFE', transition: 'border-color .15s, background .15s',
                                    }}
                                    onMouseEnter={e => { e.currentTarget.style.borderColor = T; e.currentTarget.style.background = '#F0FDFA'; }}
                                    onMouseLeave={e => { e.currentTarget.style.borderColor = 'rgba(13,148,136,0.35)'; e.currentTarget.style.background = '#FAFFFE'; }}
                                >
                                    <i className="ti ti-photo-up" style={{ fontSize: 28, color: T, display: 'block', marginBottom: 8 }} />
                                    <div style={{ fontSize: 13, fontWeight: 600, color: '#334155' }}>اضغط لرفع صورة</div>
                                    <div style={{ fontSize: 11, color: '#94A3B8', marginTop: 3 }}>PNG, JPG, WEBP — حتى 4 ميغابايت</div>
                                </div>
                            )}
                            <input ref={fileRef} type="file" accept="image/*" onChange={handleImageChange} style={{ display: 'none' }} />
                            {errors.image && <p style={{ fontSize: 11, color: '#EF4444', marginTop: 4 }}>{errors.image}</p>}
                        </div>

                        <div style={{ display: 'flex', gap: 10, justifyContent: 'flex-end', paddingTop: 4 }}>
                            <button type="button" onClick={() => { reset(); setShowForm(false); setImgPreview(null); }} style={{
                                padding: '9px 20px', borderRadius: 9, border: '1px solid rgba(0,0,0,0.1)',
                                background: 'none', fontSize: 13, cursor: 'pointer', color: '#475569',
                            }}>
                                إلغاء
                            </button>
                            <button type="submit" disabled={processing || !data.title || !data.description || !data.active_type_id} style={{
                                padding: '9px 24px', borderRadius: 9, background: T, color: '#fff',
                                border: 'none', fontSize: 13, fontWeight: 600, cursor: 'pointer',
                                opacity: (processing || !data.title || !data.description || !data.active_type_id) ? 0.55 : 1,
                                display: 'inline-flex', alignItems: 'center', gap: 7,
                            }}>
                                <i className="ti ti-send" />
                                {processing ? 'جارٍ النشر...' : 'نشر'}
                            </button>
                        </div>
                    </form>
                </div>
            )}

            {/* Posts list */}
            {!posts?.length ? (
                <div style={{ display: 'flex', flexDirection: 'column', alignItems: 'center', justifyContent: 'center', padding: '72px 24px', textAlign: 'center' }}>
                    <div style={{ width: 68, height: 68, borderRadius: 20, background: '#F0FDFA', display: 'flex', alignItems: 'center', justifyContent: 'center', fontSize: 30, color: T, marginBottom: 18 }}>
                        <i className="ti ti-file-text" />
                    </div>
                    <div style={{ fontSize: 16, fontWeight: 700, color: '#0F172A', marginBottom: 6 }}>لا توجد منشورات بعد</div>
                    <div style={{ fontSize: 13, color: '#94A3B8', marginBottom: 22 }}>شارك منشورك الأول مع مجتمع Skillify</div>
                    <button onClick={() => setShowForm(true)} style={{
                        display: 'inline-flex', alignItems: 'center', gap: 8,
                        padding: '10px 22px', background: T, color: '#fff',
                        border: 'none', borderRadius: 10, fontSize: 13, fontWeight: 600, cursor: 'pointer',
                    }}>
                        <i className="ti ti-plus" /> ابدأ بمنشور جديد
                    </button>
                </div>
            ) : (
                <div style={{ display: 'flex', flexDirection: 'column', gap: 12 }}>
                    {posts.map(p => <PostCard key={p.id} post={p} />)}
                </div>
            )}
        </UserLayout>
    );
}
