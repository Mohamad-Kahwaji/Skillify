import { Head, Link, router } from '@inertiajs/react';
import { useState } from 'react';
import UserLayout from '../../Layouts/UserLayout';

const COLORS = ['#0D9488','#3B82F6','#8B5CF6','#F59E0B','#EF4444','#EC4899','#0F766E','#DC2626','#7C3AED'];

function initial(name) { return (name ?? '?')[0].toUpperCase(); }
function color(id)     { return COLORS[id % COLORS.length]; }

function imgSrc(path) {
    if (!path) return null;
    return path.startsWith('http') ? path : `/storage/${path}`;
}

function VerifiedBadge({ status }) {
    if (status === 'approved') return (
        <span style={{ display:'inline-flex', alignItems:'center', gap:3, fontSize:10, fontWeight:700, padding:'2px 8px', borderRadius:20, background:'#ECFDF5', color:'#065F46', border:'1px solid #6EE7B7', flexShrink:0 }}>
            <i className="ti ti-shield-check" style={{ fontSize:11 }} /> موثّق
        </span>
    );
    if (status === 'pending') return (
        <span style={{ display:'inline-flex', alignItems:'center', gap:3, fontSize:10, fontWeight:700, padding:'2px 8px', borderRadius:20, background:'#FFFBEB', color:'#92400E', border:'1px solid #FCD34D', flexShrink:0 }}>
            <i className="ti ti-clock" style={{ fontSize:11 }} /> قيد التحقق
        </span>
    );
    return null;
}

function BusinessCard({ business }) {
    const [imgErr, setImgErr]       = useState(false);
    const [avatarErr, setAvatarErr] = useState(false);
    const [hover, setHover]         = useState(false);

    const bannerImg = imgSrc(!imgErr ? business.image : null);
    const clr       = color(business.id);

    const chatHref  = business.conversationId ? `/user/chat/${business.conversationId}` : null;
    const startChat = (e) => { e.preventDefault(); router.post('/user/chat/start', { business_user_id: business.user_id }); };

    return (
        <div
            onMouseEnter={() => setHover(true)}
            onMouseLeave={() => setHover(false)}
            style={{
                background: '#fff', borderRadius: 20, overflow: 'hidden',
                border: `1px solid ${hover ? '#99F6E4' : '#F0F4F8'}`,
                boxShadow: hover ? '0 12px 40px rgba(13,148,136,.13)' : '0 2px 8px rgba(0,0,0,0.04)',
                transition: 'border-color .22s, box-shadow .22s',
                display: 'flex', flexDirection: 'column',
            }}>

            {/* Banner */}
            <div style={{ position: 'relative', height: 110, flexShrink: 0 }}>
                {bannerImg ? (
                    <img src={bannerImg} alt={business.name} onError={() => setImgErr(true)}
                        style={{ width: '100%', height: '100%', objectFit: 'cover', display: 'block', transition: 'transform .4s', transform: hover ? 'scale(1.04)' : 'scale(1)' }} />
                ) : (
                    <div style={{ width: '100%', height: '100%', background: `linear-gradient(135deg,${clr}cc,${clr}88)` }} />
                )}
                {/* overlay gradient */}
                <div style={{ position: 'absolute', inset: 0, background: 'linear-gradient(to bottom,rgba(0,0,0,0) 40%,rgba(0,0,0,0.35) 100%)' }} />

                {/* Status pill */}
                <div style={{ position: 'absolute', top: 10, left: 10 }}>
                    <span style={{ display:'inline-flex', alignItems:'center', gap:4, fontSize:10, fontWeight:700, padding:'3px 10px', borderRadius:20, background: business.status === 'active' ? 'rgba(5,150,105,0.9)' : 'rgba(217,119,6,0.9)', color:'#fff', backdropFilter:'blur(4px)' }}>
                        <span style={{ width:5, height:5, borderRadius:'50%', background:'#fff', flexShrink:0 }} />
                        {business.status === 'active' ? 'نشط' : 'قيد المراجعة'}
                    </span>
                </div>
            </div>

            {/* Avatar row */}
            <div style={{ position: 'relative', paddingTop: 0, display: 'flex', alignItems: 'flex-end', justifyContent: 'space-between', padding: '0 18px', marginTop: -28, marginBottom: 12, zIndex: 1 }}>
                <div style={{ width: 56, height: 56, borderRadius: 16, border: '3px solid #fff', overflow: 'hidden', flexShrink: 0, background: clr, display: 'flex', alignItems: 'center', justifyContent: 'center', boxShadow: '0 2px 10px rgba(0,0,0,0.15)' }}>
                    {!avatarErr && business.image ? (
                        <img src={imgSrc(business.image)} alt={business.name} onError={() => setAvatarErr(true)}
                            style={{ width: '100%', height: '100%', objectFit: 'cover' }} />
                    ) : (
                        <span style={{ fontSize: 22, fontWeight: 800, color: '#fff' }}>{initial(business.name)}</span>
                    )}
                </div>
                <VerifiedBadge status={business.identity_status} />
            </div>

            {/* Body */}
            <div style={{ padding: '0 18px 16px', flex: 1, display: 'flex', flexDirection: 'column', gap: 10 }}>
                <div>
                    <div style={{ fontSize: 15, fontWeight: 700, color: '#0F172A', lineHeight: 1.3 }}>{business.name}</div>
                    {business.name_job && <div style={{ fontSize: 12, color: '#64748B', marginTop: 3 }}>{business.name_job}</div>}
                </div>

                {business.description && (
                    <p style={{ fontSize: 12, color: '#64748B', lineHeight: 1.65, margin: 0, display: '-webkit-box', WebkitLineClamp: 2, WebkitBoxOrient: 'vertical', overflow: 'hidden' }}>
                        {business.description}
                    </p>
                )}

                {/* Meta chips */}
                <div style={{ display: 'flex', gap: 6, flexWrap: 'wrap', alignItems: 'center' }}>
                    {business.activity && (
                        <span style={{ display:'inline-flex', alignItems:'center', gap:4, fontSize:11, color:'#475569', background:'#F8FAFC', padding:'3px 9px', borderRadius:20, border:'0.5px solid #E2E8F0' }}>
                            <i className="ti ti-tag" style={{ fontSize:11, color:'#94A3B8' }} />{business.activity}
                        </span>
                    )}
                    {business.number && (
                        <span style={{ display:'inline-flex', alignItems:'center', gap:4, fontSize:11, color:'#475569', background:'#F8FAFC', padding:'3px 9px', borderRadius:20, border:'0.5px solid #E2E8F0' }}>
                            <i className="ti ti-phone" style={{ fontSize:11, color:'#94A3B8' }} />{business.number}
                        </span>
                    )}
                    {business.city && (
                        <span style={{ display:'inline-flex', alignItems:'center', gap:4, fontSize:11, color:'#475569', background:'#F8FAFC', padding:'3px 9px', borderRadius:20, border:'0.5px solid #E2E8F0' }}>
                            <i className="ti ti-map-pin" style={{ fontSize:11, color:'#94A3B8' }} />{business.city}
                        </span>
                    )}
                </div>

                {/* Footer actions */}
                <div style={{ display:'flex', gap:8, marginTop:'auto', paddingTop:10, borderTop:'1px solid #F1F5F9' }}>
                    {chatHref ? (
                        <Link href={chatHref} style={chatBtn}>
                            <i className="ti ti-message-circle" style={{ fontSize:14 }} /> مراسلة
                        </Link>
                    ) : (
                        <button onClick={startChat} style={chatBtn}>
                            <i className="ti ti-message-circle" style={{ fontSize:14 }} /> مراسلة
                        </button>
                    )}
                    <Link href={`/user/users/${business.user_id}`} style={profileBtn}>
                        <i className="ti ti-user" style={{ fontSize:14 }} /> عرض الملف
                    </Link>
                </div>
            </div>
        </div>
    );
}

const chatBtn = {
    display:'inline-flex', alignItems:'center', gap:6, flex:1, justifyContent:'center',
    padding:'9px 14px', borderRadius:10, border:'none', cursor:'pointer',
    background:'linear-gradient(135deg,#0D9488,#0F766E)', color:'#fff',
    fontSize:12.5, fontWeight:700, fontFamily:'inherit', textDecoration:'none',
    boxShadow:'0 2px 8px rgba(13,148,136,0.3)',
};
const profileBtn = {
    display:'inline-flex', alignItems:'center', gap:6, flex:1, justifyContent:'center',
    padding:'9px 14px', borderRadius:10, border:'1.5px solid #E2E8F0', cursor:'pointer',
    background:'#F8FAFC', color:'#475569',
    fontSize:12.5, fontWeight:600, fontFamily:'inherit', textDecoration:'none',
};

export default function Explore({ businesses, activities, filters }) {
    const [q, setQ] = useState(filters?.q ?? '');

    const applyFilter = (params) => {
        router.get('/user/explore', { ...filters, ...params }, { preserveState: true, replace: true });
    };

    const submit = (e) => { e.preventDefault(); applyFilter({ q }); };

    const items    = businesses?.data  ?? [];
    const total    = businesses?.total ?? 0;
    const links    = businesses?.links ?? [];
    const activity = filters?.activity ?? '';

    return (
        <UserLayout title="استكشاف">
            <Head title="استكشاف — Skillify" />

            {/* Header */}
            <div style={{ display:'flex', alignItems:'center', gap:14 }}>
                <div style={{ width:44, height:44, borderRadius:13, background:'linear-gradient(135deg,#0D9488,#0F766E)', display:'flex', alignItems:'center', justifyContent:'center', boxShadow:'0 4px 14px rgba(13,148,136,0.3)', flexShrink:0 }}>
                    <i className="ti ti-compass" style={{ color:'#fff', fontSize:20 }} />
                </div>
                <div>
                    <h1 style={{ fontSize:21, fontWeight:800, color:'#0F172A', margin:0, letterSpacing:-0.3 }}>استكشاف الحرفيين</h1>
                    <p style={{ fontSize:13, color:'#94A3B8', margin:0, marginTop:2 }}>تصفح الأعمال والمزودين المتاحين على المنصة</p>
                </div>
            </div>

            {/* Search */}
            <form onSubmit={submit}>
                <div style={{ position:'relative' }}>
                    <i className="ti ti-search" style={{ position:'absolute', top:'50%', transform:'translateY(-50%)', right:14, fontSize:16, color:'#94A3B8', pointerEvents:'none' }} />
                    <input
                        type="text" value={q} onChange={e => setQ(e.target.value)}
                        placeholder="ابحث بالاسم أو النشاط..."
                        style={{ width:'100%', boxSizing:'border-box', padding:'11px 42px 11px 14px', border:'1.5px solid #E2E8F0', borderRadius:12, background:'#fff', fontSize:13, color:'#0F172A', outline:'none', fontFamily:'inherit', direction:'rtl' }}
                        onFocus={e  => e.target.style.borderColor = '#0D9488'}
                        onBlur={e   => e.target.style.borderColor = '#E2E8F0'}
                    />
                </div>

                {/* Category chips */}
                {activities?.length > 0 && (
                    <div style={{ display:'flex', gap:8, overflowX:'auto', paddingBottom:4, marginTop:12 }}>
                        <div style={{ display:'flex', gap:8, width:'max-content' }}>
                            {['', ...activities].map(act => {
                                const isActive = act === activity;
                                return (
                                    <button key={act || '__all'} type="button" onClick={() => applyFilter({ activity: act, q })}
                                        style={{ padding:'6px 16px', borderRadius:24, fontSize:12, fontWeight:600, cursor:'pointer', fontFamily:'inherit', whiteSpace:'nowrap', transition:'all .15s', border:`1.5px solid ${isActive ? '#0D9488' : '#E2E8F0'}`, background: isActive ? '#0D9488' : '#fff', color: isActive ? '#fff' : '#64748B', boxShadow: isActive ? '0 2px 8px rgba(13,148,136,0.25)' : 'none' }}>
                                        {act || 'الكل'}
                                    </button>
                                );
                            })}
                        </div>
                    </div>
                )}
            </form>

            {/* Result count */}
            <div style={{ fontSize:12.5, color:'#94A3B8', fontWeight:500 }}>
                {total > 0 ? <><span style={{ color:'#0D9488', fontWeight:700 }}>{total}</span> نتيجة</> : 'لا توجد نتائج'}
            </div>

            {/* Grid */}
            {items.length === 0 ? (
                <div style={{ textAlign:'center', padding:'70px 24px', background:'#fff', borderRadius:18, border:'1.5px dashed #E2E8F0' }}>
                    <div style={{ width:70, height:70, borderRadius:'50%', background:'#F0FDFA', display:'flex', alignItems:'center', justifyContent:'center', margin:'0 auto 16px', boxShadow:'0 4px 16px rgba(13,148,136,.1)' }}>
                        <i className="ti ti-users-off" style={{ fontSize:30, color:'#0D9488' }} />
                    </div>
                    <div style={{ fontSize:15, fontWeight:700, color:'#0F172A', marginBottom:8 }}>لا يوجد حرفيون مطابقون</div>
                    <div style={{ fontSize:13, color:'#94A3B8' }}>جرّب تغيير كلمة البحث أو اختر نشاطاً مختلفاً</div>
                </div>
            ) : (
                <div style={{ display:'grid', gridTemplateColumns:'repeat(auto-fill,minmax(270px,1fr))', gap:18 }}>
                    {items.map(b => <BusinessCard key={b.id} business={b} />)}
                </div>
            )}

            {/* Pagination */}
            {links.length > 3 && (
                <div style={{ display:'flex', justifyContent:'center', gap:6, flexWrap:'wrap' }}>
                    {links.map((link, i) => (
                        link.url ? (
                            <button key={i} onClick={() => router.get(link.url)}
                                style={{ padding:'7px 14px', borderRadius:9, fontSize:12, fontWeight:600, cursor:'pointer', fontFamily:'inherit', border:`1.5px solid ${link.active ? '#0D9488' : '#E2E8F0'}`, background: link.active ? '#0D9488' : '#fff', color: link.active ? '#fff' : '#64748B', boxShadow: link.active ? '0 2px 8px rgba(13,148,136,0.25)' : 'none' }}
                                dangerouslySetInnerHTML={{ __html: link.label }}
                            />
                        ) : (
                            <span key={i} style={{ padding:'7px 12px', fontSize:12, color:'#CBD5E1' }}
                                dangerouslySetInnerHTML={{ __html: link.label }} />
                        )
                    ))}
                </div>
            )}
        </UserLayout>
    );
}
