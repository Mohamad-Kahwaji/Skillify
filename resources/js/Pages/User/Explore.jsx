import { Head, Link, router, usePage } from '@inertiajs/react';
import { useState } from 'react';
import UserLayout from '../../Layouts/UserLayout';

const AV_COLORS = ['#0D9488','#3B82F6','#8B5CF6','#F59E0B','#EF4444','#EC4899','#0F766E'];

function VerifiedBadge({ status }) {
    if (status === 'approved') return (
        <span title="هوية موثّقة" style={{
            display: 'inline-flex', alignItems: 'center', gap: 3,
            fontSize: 10, fontWeight: 600, padding: '2px 7px', borderRadius: 20,
            background: '#F0FDF4', color: '#15803D', border: '1px solid #BBF7D0',
            flexShrink: 0,
        }}>
            <i className="ti ti-shield-check" style={{ fontSize: 12 }} /> موثّق
        </span>
    );
    if (status === 'pending') return (
        <span title="توثيق قيد المراجعة" style={{
            display: 'inline-flex', alignItems: 'center', gap: 3,
            fontSize: 10, fontWeight: 600, padding: '2px 7px', borderRadius: 20,
            background: '#FFFBEB', color: '#B45309', border: '1px solid #FDE68A',
            flexShrink: 0,
        }}>
            <i className="ti ti-clock" style={{ fontSize: 12 }} /> قيد التحقق
        </span>
    );
    return null;
}

function WorkerCard({ business }) {
    const initial  = (business.name ?? 'H')[0].toUpperCase();
    const color    = AV_COLORS[business.id % 7];
    const chatHref = business.conversationId
        ? `/user/chat/${business.conversationId}`
        : null;

    const startChat = (e) => {
        e.preventDefault();
        router.post('/user/chat/start', { business_user_id: business.user_id });
    };

    return (
        <div style={{
            background: '#fff', border: '0.5px solid rgba(0,0,0,0.07)',
            borderRadius: 14, padding: 20,
            display: 'flex', flexDirection: 'column', gap: 12,
            transition: 'border-color 0.15s, box-shadow 0.15s',
        }}
            onMouseEnter={e => { e.currentTarget.style.borderColor = 'rgba(0,0,0,0.12)'; e.currentTarget.style.boxShadow = '0 4px 16px rgba(0,0,0,0.07)'; }}
            onMouseLeave={e => { e.currentTarget.style.borderColor = 'rgba(0,0,0,0.07)'; e.currentTarget.style.boxShadow = 'none'; }}
        >
            {/* Head */}
            <div style={{ display: 'flex', alignItems: 'center', gap: 12 }}>
                <div style={{
                    width: 44, height: 44, borderRadius: 12,
                    background: color, color: '#fff',
                    display: 'flex', alignItems: 'center', justifyContent: 'center',
                    fontSize: 16, fontWeight: 700, flexShrink: 0,
                }}>
                    {initial}
                </div>
                <div style={{ flex: 1, minWidth: 0 }}>
                    <div style={{ display: 'flex', alignItems: 'center', gap: 6, flexWrap: 'wrap' }}>
                        <span style={{ fontSize: 14, fontWeight: 600, color: '#0F172A' }}>{business.name}</span>
                        <VerifiedBadge status={business.identity_status} />
                    </div>
                    <div style={{ fontSize: 12, color: '#475569' }}>{business.name_job ?? '—'}</div>
                </div>
            </div>

            {/* Description */}
            {business.description && (
                <div style={{ fontSize: 12, color: '#475569', lineHeight: 1.55, display: '-webkit-box', WebkitLineClamp: 2, WebkitBoxOrient: 'vertical', overflow: 'hidden' }}>
                    {business.description}
                </div>
            )}

            {/* Meta */}
            <div style={{ display: 'flex', gap: 10, flexWrap: 'wrap', alignItems: 'center' }}>
                {business.activity && (
                    <span style={{ display: 'inline-flex', alignItems: 'center', gap: 4, fontSize: 11, color: '#475569' }}>
                        <i className="ti ti-tag" style={{ fontSize: 13, color: '#94A3B8' }} /> {business.activity}
                    </span>
                )}
                {business.number && (
                    <span style={{ display: 'inline-flex', alignItems: 'center', gap: 4, fontSize: 11, color: '#475569' }}>
                        <i className="ti ti-phone" style={{ fontSize: 13, color: '#94A3B8' }} /> {business.number}
                    </span>
                )}
                <span style={{
                    display: 'inline-flex', alignItems: 'center', gap: 4,
                    fontSize: 11, fontWeight: 600, padding: '2px 8px', borderRadius: 20,
                    background: business.status === 'active' ? '#F0FDF4' : '#FFFBEB',
                    color: business.status === 'active' ? '#15803D' : '#B45309',
                }}>
                    {business.status === 'active' ? 'نشط' : 'قيد المراجعة'}
                </span>
            </div>

            {/* Footer */}
            <div style={{ display: 'flex', alignItems: 'center', justifyContent: 'space-between', paddingTop: 8, borderTop: '0.5px solid rgba(0,0,0,0.07)' }}>
                <span style={{ fontSize: 11, color: '#94A3B8' }}>
                    <i className="ti ti-clock" style={{ verticalAlign: 'middle', marginRight: 2 }} />
                    {business.joined}
                </span>
                {chatHref ? (
                    <Link href={chatHref} style={chatBtnStyle}>
                        <i className="ti ti-message-circle" /> مراسلة
                    </Link>
                ) : (
                    <button onClick={startChat} style={chatBtnStyle}>
                        <i className="ti ti-message-circle" /> مراسلة
                    </button>
                )}
            </div>
        </div>
    );
}

const chatBtnStyle = {
    display: 'inline-flex', alignItems: 'center', gap: 6,
    padding: '7px 14px', borderRadius: 6,
    background: '#0D9488', color: '#fff',
    fontSize: 12, fontWeight: 500, border: 'none', cursor: 'pointer',
    textDecoration: 'none', transition: 'background 0.12s',
};

export default function Explore({ businesses, activities, filters }) {
    const [q, setQ] = useState(filters?.q ?? '');

    const applyFilter = (params) => {
        router.get('/user/explore', { ...filters, ...params }, { preserveState: true, replace: true });
    };

    const submit = (e) => {
        e.preventDefault();
        applyFilter({ q });
    };

    const items    = businesses?.data ?? [];
    const total    = businesses?.total ?? 0;
    const links    = businesses?.links ?? [];
    const activity = filters?.activity ?? '';

    return (
        <UserLayout title="استكشاف">
            <Head title="استكشاف — Skillify" />

            <div>
                <div style={{ fontSize: 20, fontWeight: 600, letterSpacing: '-0.3px', color: '#0F172A' }}>استكشاف الحرفيين</div>
                <div style={{ fontSize: 13, color: '#475569', marginTop: 2 }}>تصفح الأعمال والمزودين المتاحين</div>
            </div>

            {/* Search */}
            <form onSubmit={submit}>
                <div style={{ position: 'relative' }}>
                    <i className="ti ti-search" style={{ position: 'absolute', top: '50%', transform: 'translateY(-50%)', left: 12, fontSize: 16, color: '#94A3B8', pointerEvents: 'none' }} />
                    <input
                        type="text"
                        value={q}
                        onChange={e => setQ(e.target.value)}
                        placeholder="ابحث بالاسم أو النشاط..."
                        style={{
                            width: '100%', padding: '9px 14px 9px 38px',
                            border: '0.5px solid rgba(0,0,0,0.12)', borderRadius: 10,
                            background: '#fff', fontSize: 13, color: '#0F172A', outline: 'none',
                        }}
                    />
                </div>

                {/* Activity chips */}
                {activities?.length > 0 && (
                    <div style={{ display: 'flex', gap: 8, flexWrap: 'wrap', marginTop: 12 }}>
                        {['', ...activities].map((act) => {
                            const isActive = act === activity;
                            return (
                                <button
                                    key={act || '__all'}
                                    type="button"
                                    onClick={() => applyFilter({ activity: act, q })}
                                    style={{
                                        padding: '5px 14px', borderRadius: 20, fontSize: 12, fontWeight: 500,
                                        border: `0.5px solid ${isActive ? '#0D9488' : 'rgba(0,0,0,0.12)'}`,
                                        background: isActive ? '#F0FDFA' : '#fff',
                                        color: isActive ? '#134E4A' : '#475569',
                                        cursor: 'pointer', whiteSpace: 'nowrap',
                                    }}
                                >
                                    {act || 'الكل'}
                                </button>
                            );
                        })}
                    </div>
                )}
            </form>

            <div style={{ fontSize: 12, color: '#94A3B8' }}>{total} نتيجة</div>

            {items.length === 0 ? (
                <div style={{ textAlign: 'center', padding: '60px 24px', color: '#94A3B8' }}>
                    <i className="ti ti-users-off" style={{ fontSize: 48, display: 'block', marginBottom: 12, opacity: 0.35 }} />
                    <p style={{ fontSize: 14 }}>لا يوجد حرفيون مطابقون لبحثك</p>
                </div>
            ) : (
                <div style={{ display: 'grid', gridTemplateColumns: 'repeat(auto-fill,minmax(260px,1fr))', gap: 14 }}>
                    {items.map(b => <WorkerCard key={b.id} business={b} />)}
                </div>
            )}

            {/* Pagination */}
            {links.length > 3 && (
                <div style={{ display: 'flex', justifyContent: 'center', gap: 6, flexWrap: 'wrap' }}>
                    {links.map((link, i) => (
                        link.url ? (
                            <button key={i} onClick={() => router.get(link.url)}
                                style={{
                                    padding: '6px 12px', borderRadius: 8, fontSize: 12,
                                    border: `1px solid ${link.active ? '#0D9488' : 'rgba(0,0,0,0.12)'}`,
                                    background: link.active ? '#0D9488' : '#fff',
                                    color: link.active ? '#fff' : '#475569',
                                    cursor: 'pointer',
                                }}
                                dangerouslySetInnerHTML={{ __html: link.label }}
                            />
                        ) : (
                            <span key={i} style={{ padding: '6px 12px', fontSize: 12, color: '#94A3B8' }}
                                dangerouslySetInnerHTML={{ __html: link.label }}
                            />
                        )
                    ))}
                </div>
            )}
        </UserLayout>
    );
}
