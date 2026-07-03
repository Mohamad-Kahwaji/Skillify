import { Head } from '@inertiajs/react';
import { useState, useEffect, useRef } from 'react';
import UserLayout from '../../Layouts/UserLayout';

const AV_COLORS = ['#0D9488','#3B82F6','#8B5CF6','#F59E0B','#EF4444','#EC4899','#0F766E'];

export default function Chat({ conversation, messages: initialMessages, conversations, otherUser, authId }) {
    const [messages, setMessages]   = useState(initialMessages ?? []);
    const [body, setBody]           = useState('');
    const [sending, setSending]     = useState(false);
    const bottomRef                 = useRef(null);
    const inputRef                  = useRef(null);

    const myId    = authId;
    const initial = (otherUser?.first_name ?? 'U')[0].toUpperCase();
    const color   = AV_COLORS[(otherUser?.id ?? 0) % 7];

    // Auto-scroll to bottom
    useEffect(() => {
        bottomRef.current?.scrollIntoView({ behavior: 'smooth' });
    }, [messages]);

    // Real-time via Echo (if available)
    useEffect(() => {
        if (!window.Echo) return;
        const channel = window.Echo.private(`conversation.${conversation.id}`)
            .listen('.MessageSent', (e) => {
                if (e.message) setMessages(prev => [...prev, e.message]);
            });
        return () => channel.stopListening('.MessageSent');
    }, [conversation.id]);

    // Mark as read on open (fire-and-forget AJAX, not Inertia)
    useEffect(() => {
        fetch(`/user/messages/${conversation.id}/mark-read`, {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content ?? '' },
        });
    }, [conversation.id]);

    const send = async (e) => {
        e.preventDefault();
        if (!body.trim() || sending) return;
        setSending(true);
        const text = body;
        setBody('');
        try {
            const res = await fetch('/user/messages', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content ?? '',
                    'Accept': 'application/json',
                },
                body: JSON.stringify({ conversation_id: conversation.id, message_text: text }),
            });
            if (res.ok) {
                const msg = await res.json();
                setMessages(prev => [...prev, msg]);
            }
        } finally {
            setSending(false);
        }
    };

    const formatTime = (ts) => {
        const d = new Date(ts);
        return d.toLocaleTimeString('ar', { hour: '2-digit', minute: '2-digit' });
    };

    const formatDay = (ts) => {
        const d  = new Date(ts);
        const now = new Date();
        if (d.toDateString() === now.toDateString()) return 'اليوم';
        const yd = new Date(now); yd.setDate(yd.getDate() - 1);
        if (d.toDateString() === yd.toDateString()) return 'أمس';
        return d.toLocaleDateString('ar', { month: 'short', day: 'numeric' });
    };

    // Group messages by day
    const grouped = messages.reduce((acc, msg) => {
        const day = new Date(msg.created_at).toDateString();
        if (!acc[day]) acc[day] = [];
        acc[day].push(msg);
        return acc;
    }, {});

    return (
        <UserLayout title="الرسائل">
            <Head title={`محادثة مع ${otherUser?.first_name ?? 'مستخدم'} — Skillify`} />

            <div className="grid grid-cols-1 md:grid-cols-[280px_1fr]" style={{ gap: 16, minHeight: 520 }}>
                {/* Sidebar: conversation list — hidden on mobile, shown from md+ */}
                <div className="hidden md:flex" style={{ background: '#fff', border: '0.5px solid rgba(0,0,0,0.07)', borderRadius: 14, overflow: 'hidden', flexDirection: 'column' }}>
                    <div style={{ padding: '14px 16px', borderBottom: '0.5px solid rgba(0,0,0,0.07)', fontSize: 13, fontWeight: 700 }}>
                        المحادثات
                    </div>
                    <div style={{ flex: 1, overflowY: 'auto' }}>
                        {(conversations ?? []).map((conv) => {
                            const other   = conv.user_id_1 == myId ? conv.user_two : conv.user_one;
                            const isActive = conv.id === conversation.id;
                            const ini = (other?.first_name ?? 'U')[0].toUpperCase();
                            const col = AV_COLORS[(other?.id ?? 0) % 7];
                            return (
                                <a key={conv.id} href={`/user/chat/${conv.id}`} style={{
                                    display: 'flex', alignItems: 'center', gap: 10, padding: '10px 14px', textDecoration: 'none',
                                    background: isActive ? '#F0FDFA' : 'transparent', color: 'inherit',
                                    borderLeft: isActive ? '3px solid #0D9488' : '3px solid transparent',
                                }}>
                                    <div style={{ width: 36, height: 36, borderRadius: '50%', background: col, color: '#fff', display: 'flex', alignItems: 'center', justifyContent: 'center', fontSize: 13, fontWeight: 700, flexShrink: 0 }}>
                                        {ini}
                                    </div>
                                    <div style={{ minWidth: 0 }}>
                                        <div style={{ fontSize: 12, fontWeight: 600, color: isActive ? '#0D9488' : '#0F172A' }}>
                                            {other?.first_name} {other?.last_name}
                                        </div>
                                        <div style={{ fontSize: 11, color: '#94A3B8', overflow: 'hidden', textOverflow: 'ellipsis', whiteSpace: 'nowrap' }}>
                                            {conv.last_message ?? '...'}
                                        </div>
                                    </div>
                                </a>
                            );
                        })}
                    </div>
                </div>

                {/* Main chat area */}
                <div style={{ background: '#fff', border: '0.5px solid rgba(0,0,0,0.07)', borderRadius: 14, display: 'flex', flexDirection: 'column', overflow: 'hidden' }}>
                    {/* Header */}
                    <div style={{ display: 'flex', alignItems: 'center', gap: 12, padding: '14px 18px', borderBottom: '0.5px solid rgba(0,0,0,0.07)' }}>
                        <a href="/user/conversations" className="flex md:hidden" style={{
                            width: 32, height: 32, borderRadius: 8, background: '#F1F5F9', color: '#475569',
                            alignItems: 'center', justifyContent: 'center', flexShrink: 0, textDecoration: 'none',
                        }}>
                            <i className="ti ti-arrow-right" />
                        </a>
                        <div style={{ width: 40, height: 40, borderRadius: '50%', background: color, color: '#fff', display: 'flex', alignItems: 'center', justifyContent: 'center', fontSize: 15, fontWeight: 700, flexShrink: 0 }}>
                            {initial}
                        </div>
                        <div>
                            <div style={{ fontSize: 14, fontWeight: 700, color: '#0F172A' }}>
                                {otherUser?.first_name} {otherUser?.last_name}
                            </div>
                            <div style={{ fontSize: 11, color: '#94A3B8', marginTop: 1 }}>متصل</div>
                        </div>
                    </div>

                    {/* Messages */}
                    <div style={{ flex: 1, overflowY: 'auto', padding: '16px 18px', display: 'flex', flexDirection: 'column', gap: 4 }}>
                        {Object.entries(grouped).map(([day, msgs]) => (
                            <div key={day}>
                                <div style={{ textAlign: 'center', marginBottom: 10 }}>
                                    <span style={{ fontSize: 10, color: '#94A3B8', background: '#F1F5F9', padding: '3px 10px', borderRadius: 20 }}>
                                        {formatDay(msgs[0].created_at)}
                                    </span>
                                </div>
                                {msgs.map((msg) => {
                                    const isMe = msg.user_id == myId;
                                    return (
                                        <div key={msg.id} style={{ display: 'flex', justifyContent: isMe ? 'flex-end' : 'flex-start', marginBottom: 6 }}>
                                            {!isMe && (
                                                <div style={{ width: 28, height: 28, borderRadius: '50%', background: color, color: '#fff', display: 'flex', alignItems: 'center', justifyContent: 'center', fontSize: 10, fontWeight: 700, flexShrink: 0, marginRight: 8, alignSelf: 'flex-end' }}>
                                                    {initial}
                                                </div>
                                            )}
                                            <div style={{ maxWidth: '68%' }}>
                                                <div style={{
                                                    padding: '8px 14px', borderRadius: isMe ? '16px 16px 4px 16px' : '16px 16px 16px 4px',
                                                    background: isMe ? '#0D9488' : '#F1F5F9',
                                                    color: isMe ? '#fff' : '#0F172A',
                                                    fontSize: 13, lineHeight: 1.5,
                                                }}>
                                                    {msg.message_text ?? msg.body}
                                                </div>
                                                <div style={{ fontSize: 10, color: '#94A3B8', marginTop: 2, textAlign: isMe ? 'right' : 'left' }}>
                                                    {formatTime(msg.created_at)}
                                                </div>
                                            </div>
                                        </div>
                                    );
                                })}
                            </div>
                        ))}
                        {!messages.length && (
                            <div style={{ flex: 1, display: 'flex', flexDirection: 'column', alignItems: 'center', justifyContent: 'center', color: '#94A3B8', textAlign: 'center', padding: '40px 24px' }}>
                                <i className="ti ti-messages" style={{ fontSize: 38, opacity: 0.3, display: 'block', marginBottom: 10 }} />
                                <p style={{ fontSize: 13 }}>لا توجد رسائل بعد. ابدأ المحادثة!</p>
                            </div>
                        )}
                        <div ref={bottomRef} />
                    </div>

                    {/* Input */}
                    <form onSubmit={send} style={{ display: 'flex', gap: 8, padding: '12px 18px', borderTop: '0.5px solid rgba(0,0,0,0.07)', alignItems: 'center' }}>
                        <input
                            ref={inputRef}
                            value={body}
                            onChange={e => setBody(e.target.value)}
                            placeholder="اكتب رسالة..."
                            disabled={sending}
                            style={{ flex: 1, padding: '9px 14px', border: '0.5px solid rgba(0,0,0,0.12)', borderRadius: 24, fontSize: 13, outline: 'none', background: '#F8FAFC' }}
                            onKeyDown={e => { if (e.key === 'Enter' && !e.shiftKey) { send(e); } }}
                        />
                        <button type="submit" disabled={!body.trim() || sending} style={{
                            width: 40, height: 40, borderRadius: '50%', border: 'none',
                            background: body.trim() ? '#0D9488' : '#E2E8F0',
                            color: '#fff', cursor: body.trim() ? 'pointer' : 'default',
                            display: 'flex', alignItems: 'center', justifyContent: 'center', fontSize: 16, flexShrink: 0,
                            transition: 'background .15s',
                        }}>
                            <i className="ti ti-send" />
                        </button>
                    </form>
                </div>
            </div>
        </UserLayout>
    );
}
