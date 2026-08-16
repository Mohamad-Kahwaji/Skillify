import { useState } from 'react';

export default function PasswordConfirmModal({ open, title = 'تأكيد الحذف', description = 'أدخل كلمة المرور الحالية لتأكيد العملية.', error, onClose, onConfirm }) {
    const [password, setPassword] = useState('');
    const [processing, setProcessing] = useState(false);
    if (!open) return null;

    const submit = async event => {
        event.preventDefault();
        if (!password || processing) return;
        setProcessing(true);
        await onConfirm(password);
        setProcessing(false);
        if (!error) setPassword('');
    };

    return <div onClick={onClose} style={{ position: 'fixed', inset: 0, zIndex: 1100, background: 'rgba(15,23,42,.62)', display: 'flex', alignItems: 'center', justifyContent: 'center', padding: 18, backdropFilter: 'blur(4px)' }}>
        <form onSubmit={submit} onClick={event => event.stopPropagation()} dir="rtl" style={{ width: '100%', maxWidth: 420, background: '#fff', borderRadius: 18, padding: 22, boxShadow: '0 24px 80px rgba(15,23,42,.3)' }}>
            <div style={{ display: 'flex', alignItems: 'center', gap: 10, marginBottom: 8 }}><div style={{ width: 40, height: 40, borderRadius: 12, background: '#FEF2F2', color: '#DC2626', display: 'inline-flex', alignItems: 'center', justifyContent: 'center', fontSize: 19 }}><i className="ti ti-shield-lock" /></div><div><div style={{ fontSize: 16, fontWeight: 900, color: '#1E1B4B' }}>{title}</div><div style={{ fontSize: 11, color: '#94A3B8' }}>إجراء أمني مطلوب</div></div></div>
            <p style={{ fontSize: 12, color: '#64748B', lineHeight: 1.7, margin: '0 0 14px' }}>{description}</p>
            {error && <div role="alert" style={{ marginBottom: 12, padding: '9px 11px', border: '1px solid #FECACA', borderRadius: 9, background: '#FEF2F2', color: '#B91C1C', fontSize: 12, fontWeight: 700 }}>{error}</div>}
            <input type="password" value={password} onChange={event => setPassword(event.target.value)} autoFocus required placeholder="كلمة المرور الحالية" autoComplete="current-password" style={{ width: '100%', boxSizing: 'border-box', padding: '11px 12px', border: '1px solid #CBD5E1', borderRadius: 10, fontFamily: 'inherit', fontSize: 13, outline: 'none' }} />
            <div style={{ display: 'flex', justifyContent: 'flex-end', gap: 8, marginTop: 16 }}><button type="button" onClick={onClose} style={{ padding: '9px 15px', borderRadius: 9, border: '1px solid #CBD5E1', background: '#fff', color: '#475569', cursor: 'pointer', fontFamily: 'inherit' }}>إلغاء</button><button type="submit" disabled={!password || processing} style={{ padding: '9px 17px', borderRadius: 9, border: 0, background: '#DC2626', color: '#fff', cursor: 'pointer', fontFamily: 'inherit', fontWeight: 800, opacity: !password || processing ? .55 : 1 }}>{processing ? 'جارٍ التحقق...' : 'تأكيد الحذف'}</button></div>
        </form>
    </div>;
}
