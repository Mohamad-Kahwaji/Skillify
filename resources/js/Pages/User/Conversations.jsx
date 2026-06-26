import { Head, Link } from '@inertiajs/react';
import UserLayout from '../../Layouts/UserLayout';

const AV_COLORS = ['#0D9488','#3B82F6','#8B5CF6','#F59E0B','#EF4444','#EC4899','#0F766E'];

export default function Conversations({ conversations, authId }) {
    return (
        <UserLayout title="الرسائل">
            <Head title="الرسائل — Skillify" />

            <div>
                <div style={{ fontSize: 20, fontWeight: 600, color: '#0F172A' }}>الرسائل</div>
                <div style={{ fontSize: 13, color: '#475569', marginTop: 2 }}>{conversations?.length ?? 0} محادثة</div>
            </div>

            <div style={{ background: '#fff', border: '1px solid rgba(0,0,0,0.07)', borderRadius: 14, overflow: 'hidden' }}>
                {!conversations?.length ? (
                    <div style={{ textAlign: 'center', padding: '64px 24px', color: '#94A3B8' }}>
                        <i className="ti ti-message-off" style={{ fontSize: 48, display: 'block', marginBottom: 12, opacity: 0.3 }} />
                        <p style={{ fontSize: 14, marginBottom: 16 }}>لا توجد محادثات بعد.</p>
                        <Link href="/user/explore" style={{
                            display: 'inline-flex', alignItems: 'center', gap: 6,
                            padding: '8px 18px', borderRadius: 8,
                            background: '#0D9488', color: '#fff', fontSize: 13, fontWeight: 500, textDecoration: 'none',
                        }}>
                            <i className="ti ti-search" /> استكشاف الحرفيين
                        </Link>
                    </div>
                ) : (
                    conversations.map((conv, idx) => {
                        const other   = conv.user_id_1 == authId ? conv.user_two : conv.user_one;
                        const initial = (other?.first_name ?? 'U')[0].toUpperCase();
                        const color   = AV_COLORS[(other?.id ?? 0) % 7];
                        const lastAt  = conv.last_message_at ?? conv.created_at;
                        const date    = new Date(lastAt);
                        const now     = new Date();
                        const isToday = date.toDateString() === now.toDateString();
                        const timeStr = isToday
                            ? date.toLocaleTimeString('ar', { hour: '2-digit', minute: '2-digit' })
                            : date.toLocaleDateString('ar', { month: 'short', day: 'numeric' });

                        return (
                            <Link key={conv.id} href={`/user/chat/${conv.id}`} style={{
                                display: 'flex', alignItems: 'center', gap: 14,
                                padding: '16px 20px',
                                borderBottom: idx < conversations.length - 1 ? '0.5px solid rgba(0,0,0,0.07)' : 'none',
                                textDecoration: 'none', color: 'inherit',
                                transition: 'background 0.1s',
                            }}
                                onMouseEnter={e => e.currentTarget.style.background = '#F8FAFC'}
                                onMouseLeave={e => e.currentTarget.style.background = 'transparent'}
                            >
                                <div style={{ width: 46, height: 46, borderRadius: '50%', background: color, color: '#fff', display: 'flex', alignItems: 'center', justifyContent: 'center', fontSize: 17, fontWeight: 700, flexShrink: 0 }}>
                                    {initial}
                                </div>
                                <div style={{ flex: 1, minWidth: 0 }}>
                                    <div style={{ fontSize: 14, fontWeight: 600, color: '#0F172A' }}>
                                        {other?.first_name} {other?.last_name}
                                    </div>
                                    <div style={{ fontSize: 12, color: '#94A3B8', marginTop: 2, overflow: 'hidden', textOverflow: 'ellipsis', whiteSpace: 'nowrap' }}>
                                        {conv.last_message ?? 'ابدأ المحادثة'}
                                    </div>
                                </div>
                                <div style={{ flexShrink: 0, textAlign: 'right' }}>
                                    <div style={{ fontSize: 11, color: '#94A3B8' }}>{timeStr}</div>
                                </div>
                                <i className="ti ti-chevron-right" style={{ fontSize: 14, color: '#94A3B8', flexShrink: 0 }} />
                            </Link>
                        );
                    })
                )}
            </div>
        </UserLayout>
    );
}
