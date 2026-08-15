import { useState } from 'react';

const REASONS = ['محتوى غير مناسب', 'معلومات مضللة أو مزيفة', 'احتيال أو طلب مشبوه', 'إساءة أو مضايقة', 'مخالفة لسياسة المنصة', 'سبب آخر'];

export default function ReportButton({ type, id, label = 'إبلاغ', compact = false }) {
    const [open, setOpen] = useState(false);
    const [reason, setReason] = useState('');
    const [sending, setSending] = useState(false);
    const [sent, setSent] = useState(false);
    const [error, setError] = useState('');

    const submit = async event => {
        event.preventDefault();
        if (!reason.trim() || sending) return;
        setSending(true);
        setError('');
        try {
            const response = await fetch('/user/reports', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content ?? '',
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ type, id, reason: reason.trim() }),
            });
            const payload = await response.json();
            if (!response.ok) throw new Error(payload.message || 'تعذر إرسال البلاغ.');
            setSent(true);
            setReason('');
        } catch (submitError) {
            setError(submitError.message || 'تعذر إرسال البلاغ.');
        } finally {
            setSending(false);
        }
    };

    return (
        <>
            <button type="button" onClick={() => { setOpen(true); setSent(false); setError(''); }} title={label} style={{ display: 'inline-flex', alignItems: 'center', justifyContent: 'center', gap: compact ? 0 : 5, width: compact ? 34 : 'auto', height: compact ? 34 : 36, padding: compact ? 0 : '0 11px', borderRadius: 10, border: '1px solid #FECACA', background: '#FFF7F7', color: '#DC2626', fontSize: 11.5, fontWeight: 700, cursor: 'pointer' }}>
                <i className="ti ti-flag" />{!compact && label}
            </button>
            {open && (
                <div onClick={() => setOpen(false)} style={{ position: 'fixed', inset: 0, zIndex: 1000, display: 'flex', alignItems: 'center', justifyContent: 'center', padding: 18, background: 'rgba(15,23,42,.58)', backdropFilter: 'blur(5px)' }}>
                    <div onClick={event => event.stopPropagation()} dir="rtl" style={{ width: '100%', maxWidth: 440, background: '#fff', borderRadius: 18, boxShadow: '0 24px 70px rgba(15,23,42,.28)', overflow: 'hidden' }}>
                        <div style={{ display: 'flex', alignItems: 'center', justifyContent: 'space-between', padding: '16px 18px', borderBottom: '1px solid #F1F5F9' }}>
                            <div style={{ display: 'flex', alignItems: 'center', gap: 9 }}><span style={{ width: 34, height: 34, borderRadius: 10, background: '#FEF2F2', color: '#DC2626', display: 'inline-flex', alignItems: 'center', justifyContent: 'center' }}><i className="ti ti-flag" /></span><div><div style={{ fontWeight: 800, color: '#0F172A' }}>إبلاغ عن المحتوى</div><div style={{ fontSize: 11, color: '#94A3B8' }}>سيصل البلاغ إلى فريق الإدارة للمراجعة</div></div></div>
                            <button type="button" onClick={() => setOpen(false)} style={{ width: 30, height: 30, border: 0, borderRadius: 8, background: '#F8FAFC', color: '#64748B', cursor: 'pointer' }}><i className="ti ti-x" /></button>
                        </div>
                        {sent ? (
                            <div style={{ padding: '36px 24px 40px', textAlign: 'center' }}><div style={{ width: 52, height: 52, borderRadius: '50%', background: '#DCFCE7', color: '#16A34A', display: 'inline-flex', alignItems: 'center', justifyContent: 'center', fontSize: 25, marginBottom: 12 }}><i className="ti ti-check" /></div><div style={{ fontWeight: 800, color: '#166534', marginBottom: 6 }}>تم إرسال البلاغ</div><div style={{ fontSize: 12, color: '#64748B' }}>شكراً لمساعدتك في الحفاظ على جودة المنصة.</div></div>
                        ) : (
                            <form onSubmit={submit} style={{ padding: 18 }}>
                                <label style={{ display: 'block', fontSize: 12, fontWeight: 700, color: '#334155', marginBottom: 8 }}>سبب البلاغ</label>
                                <select value={REASONS.includes(reason) ? reason : ''} onChange={event => setReason(event.target.value)} style={{ width: '100%', height: 42, padding: '0 11px', border: '1px solid #CBD5E1', borderRadius: 10, background: '#F8FAFC', fontFamily: 'inherit', fontSize: 12, marginBottom: 10 }}><option value="">اختر السبب...</option>{REASONS.map(item => <option key={item} value={item}>{item}</option>)}</select>
                                <textarea value={REASONS.includes(reason) ? '' : reason} onChange={event => setReason(event.target.value)} placeholder="اكتب تفاصيل إضافية (مطلوب)..." rows={4} style={{ width: '100%', boxSizing: 'border-box', resize: 'vertical', padding: 11, border: '1px solid #CBD5E1', borderRadius: 10, background: '#fff', fontFamily: 'inherit', fontSize: 12, outline: 'none' }} />
                                {error && <div style={{ marginTop: 8, padding: '8px 10px', borderRadius: 8, background: '#FEF2F2', color: '#B91C1C', fontSize: 11 }}>{error}</div>}
                                <div style={{ display: 'flex', justifyContent: 'flex-end', gap: 8, marginTop: 14 }}><button type="button" onClick={() => setOpen(false)} style={{ padding: '9px 15px', borderRadius: 9, border: '1px solid #CBD5E1', background: '#fff', color: '#475569', cursor: 'pointer', fontFamily: 'inherit', fontSize: 12 }}>إلغاء</button><button type="submit" disabled={!reason.trim() || sending} style={{ padding: '9px 17px', borderRadius: 9, border: 0, background: '#DC2626', color: '#fff', cursor: 'pointer', fontFamily: 'inherit', fontSize: 12, fontWeight: 800, opacity: !reason.trim() || sending ? .55 : 1 }}>{sending ? 'جارٍ الإرسال...' : 'إرسال البلاغ'}</button></div>
                            </form>
                        )}
                    </div>
                </div>
            )}
        </>
    );
}
