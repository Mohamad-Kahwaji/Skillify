import { useState } from 'react';

export default function WarningButton({ type = 'post', id, actionPrefix = '/moderation', compact = true }) {
    const [open, setOpen] = useState(false);
    const [message, setMessage] = useState('');
    const [sending, setSending] = useState(false);
    const [sent, setSent] = useState(false);

    const submit = async event => {
        event.preventDefault();
        if (!message.trim() || sending) return;
        setSending(true);
        const response = await fetch(`${actionPrefix}/${type}/${id}/warn`, {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content ?? '', 'Accept': 'application/json', 'Content-Type': 'application/json' },
            body: JSON.stringify({ message: message.trim() }),
        });
        setSending(false);
        if (response.ok) { setSent(true); setMessage(''); }
    };

    return <>
        <button type="button" title="إرسال تحذير" onClick={() => { setOpen(true); setSent(false); }} style={{ width: compact ? 30 : 'auto', height: 30, padding: compact ? 0 : '0 11px', borderRadius: 8, border: '1px solid #FDE68A', background: '#FFFBEB', color: '#B45309', display: 'inline-flex', alignItems: 'center', justifyContent: 'center', gap: 5, cursor: 'pointer', fontFamily: 'inherit', fontSize: 12 }}><i className="ti ti-alert-triangle" />{!compact && 'إرسال تحذير'}</button>
        {open && <div onClick={() => setOpen(false)} style={{ position: 'fixed', inset: 0, zIndex: 1000, background: 'rgba(15,23,42,.58)', display: 'flex', alignItems: 'center', justifyContent: 'center', padding: 18 }}><div onClick={event => event.stopPropagation()} style={{ width: '100%', maxWidth: 440, background: '#fff', borderRadius: 18, padding: 22, boxShadow: '0 24px 70px rgba(15,23,42,.28)', direction: 'rtl' }}>{sent ? <div style={{ textAlign: 'center', padding: 18 }}><i className="ti ti-circle-check" style={{ color: '#16A34A', fontSize: 38 }} /><div style={{ fontWeight: 800, color: '#166534', marginTop: 8 }}>تم إرسال التحذير</div><button type="button" onClick={() => setOpen(false)} style={{ marginTop: 16, padding: '8px 16px', border: 0, borderRadius: 9, background: '#0D9488', color: '#fff', cursor: 'pointer', fontFamily: 'inherit' }}>إغلاق</button></div> : <form onSubmit={submit}><div style={{ fontSize: 17, fontWeight: 900, color: '#1E1B4B', marginBottom: 6 }}>إرسال تحذير لصاحب المنشور</div><div style={{ fontSize: 12, color: '#64748B', marginBottom: 14 }}>ستصل الرسالة إلى خانة الإشعارات لديه.</div><textarea value={message} onChange={event => setMessage(event.target.value)} required rows={5} placeholder="اكتب مضمون التحذير بوضوح..." style={{ width: '100%', boxSizing: 'border-box', padding: 11, border: '1px solid #CBD5E1', borderRadius: 10, resize: 'vertical', fontFamily: 'inherit', fontSize: 12 }} /><div style={{ display: 'flex', justifyContent: 'flex-end', gap: 8, marginTop: 14 }}><button type="button" onClick={() => setOpen(false)} style={{ padding: '9px 14px', border: '1px solid #CBD5E1', background: '#fff', borderRadius: 9, cursor: 'pointer', fontFamily: 'inherit' }}>إلغاء</button><button type="submit" disabled={!message.trim() || sending} style={{ padding: '9px 16px', border: 0, background: '#D97706', color: '#fff', borderRadius: 9, cursor: 'pointer', fontFamily: 'inherit', fontWeight: 800, opacity: !message.trim() || sending ? .55 : 1 }}>{sending ? 'جارٍ الإرسال...' : 'إرسال التحذير'}</button></div></form>}</div></div>}
    </>;
}
