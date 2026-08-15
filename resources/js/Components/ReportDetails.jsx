import { Head, Link, router } from '@inertiajs/react';
import { useState } from 'react';
import { avatarUrl, storageUrl } from '../utils/image';
import PasswordConfirmModal from './PasswordConfirmModal';

const TYPE_LABELS = { post: 'منشور', service: 'خدمة', business: 'حساب أعمال', user: 'حساب مستخدم' };

function Avatar({ user, size = 52 }) {
    const src = avatarUrl(user);
    const initials = `${user?.first_name?.[0] ?? ''}${user?.last_name?.[0] ?? ''}`.toUpperCase() || '؟';
    return <div style={{ width: size, height: size, borderRadius: '50%', background: 'linear-gradient(135deg,#7C3AED,#0D9488)', color: '#fff', display: 'flex', alignItems: 'center', justifyContent: 'center', fontWeight: 800, overflow: 'hidden', flexShrink: 0 }}>{src ? <img src={src} alt="" style={{ width: '100%', height: '100%', objectFit: 'cover' }} /> : initials}</div>;
}

function Media({ path, alt }) {
    const src = storageUrl(path);
    return src ? <img src={src} alt={alt || ''} style={{ width: '100%', maxHeight: 340, objectFit: 'cover', display: 'block', borderRadius: 14 }} /> : <div style={{ minHeight: 120, borderRadius: 14, background: '#F8FAFC', border: '1px dashed #CBD5E1', display: 'flex', alignItems: 'center', justifyContent: 'center', color: '#94A3B8' }}><i className="ti ti-photo-off" style={{ fontSize: 32 }} /></div>;
}

function TargetContent({ target, targetType }) {
    if (targetType === 'post') return <>
        <div style={{ fontSize: 22, fontWeight: 900, color: '#0F172A' }}>{target.title || 'منشور بدون عنوان'}</div>
        <div style={{ color: '#475569', lineHeight: 1.9, whiteSpace: 'pre-wrap' }}>{target.description}</div>
        {target.image && <Media path={target.image} alt={target.title} />}
        <div style={{ display: 'flex', gap: 8, flexWrap: 'wrap', color: '#64748B', fontSize: 12 }}><span><i className="ti ti-eye" /> {target.views ?? 0} مشاهدة</span><span><i className="ti ti-heart" /> {target.likes?.length ?? 0} إعجاب</span><span><i className="ti ti-message-circle" /> {target.comments?.length ?? 0} تعليق</span></div>
        {target.comments?.length > 0 && <div style={{ borderTop: '1px solid #F1F5F9', paddingTop: 16 }}><div style={{ fontWeight: 800, marginBottom: 10 }}>التعليقات</div>{target.comments.map(comment => <div key={comment.id} style={{ padding: '9px 11px', background: '#F8FAFC', borderRadius: 10, marginBottom: 7, fontSize: 12 }}><strong>{comment.user?.first_name} {comment.user?.last_name}</strong><div style={{ marginTop: 4, color: '#475569' }}>{comment.content}</div>{comment.replies?.map(reply => <div key={reply.id} style={{ marginTop: 7, marginRight: 14, padding: '6px 9px', background: '#fff', borderRight: '2px solid #CBD5E1' }}><strong>{reply.user?.first_name} {reply.user?.last_name}</strong> {reply.content}</div>)}</div>)}</div>}
    </>;

    if (targetType === 'service') return <>
        <div style={{ fontSize: 22, fontWeight: 900, color: '#0F172A' }}>{target.name}</div>
        <div style={{ color: '#475569', lineHeight: 1.9, whiteSpace: 'pre-wrap' }}>{target.description || 'لا يوجد وصف.'}</div>
        {target.image && <Media path={target.image} alt={target.name} />}
        <div style={{ display: 'flex', gap: 8, flexWrap: 'wrap' }}><span className="report-detail-chip">{target.category?.name || 'بدون فئة'}</span><span className="report-detail-chip">{target.city?.name || 'بدون مدينة'}</span><span className="report-detail-chip">{Number(target.price || 0).toLocaleString()} {target.price_type === 'usd' ? 'USD' : 'SYP'}</span></div>
    </>;

    if (targetType === 'business') return <>
        <div style={{ fontSize: 22, fontWeight: 900, color: '#0F172A' }}>{target.name || target.name_job}</div>
        <div style={{ color: '#0D9488', fontWeight: 800 }}>{target.name_job || target.activity || 'حساب أعمال'}</div>
        <div style={{ color: '#475569', lineHeight: 1.9 }}>{target.description || 'لا يوجد وصف.'}</div>
        {target.image && <Media path={target.image} alt={target.name} />}
        <div style={{ display: 'flex', gap: 8, flexWrap: 'wrap' }}><span className="report-detail-chip">الحالة: {target.status}</span><span className="report-detail-chip">{target.city || target.user?.city || 'بدون مدينة'}</span><span className="report-detail-chip">{target.number || 'بدون هاتف'}</span></div>
    </>;

    return <>
        <div style={{ display: 'flex', alignItems: 'center', gap: 12 }}><Avatar user={target} size={70} /><div><div style={{ fontSize: 22, fontWeight: 900 }}>{target.first_name} {target.last_name}</div><div style={{ color: '#64748B', fontSize: 12 }}>{target.email}</div></div></div>
        <div style={{ display: 'flex', gap: 8, flexWrap: 'wrap' }}><span className="report-detail-chip">الهاتف: {target.phone || 'غير متوفر'}</span><span className="report-detail-chip">المدينة: {target.city || 'غير متوفرة'}</span><span className="report-detail-chip">المنشورات: {target.posts?.length ?? 0}</span><span className="report-detail-chip">الخدمات: {target.services?.length ?? 0}</span></div>
        {target.businesses && <div style={{ padding: 14, background: '#F8FAFC', borderRadius: 12 }}><strong>حساب الأعمال:</strong> {target.businesses.name || target.businesses.name_job} · {target.businesses.status}</div>}
    </>;
}

export default function ReportDetails({ report, target, targetType, warnings = [], Layout, backHref, actionPrefix }) {
    const [warningOpen, setWarningOpen] = useState(false);
    const [warning, setWarning] = useState('');
    const [sending, setSending] = useState(false);
    const [deleteOpen, setDeleteOpen] = useState(false);
    const ownerId = targetType === 'user' ? target.id : target.user_id || target.user?.id;

    const sendWarning = async event => {
        event.preventDefault();
        if (!warning.trim() || sending) return;
        setSending(true);
        await fetch(`${actionPrefix}/${report.id}/warn`, { method: 'POST', headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content ?? '', 'Accept': 'application/json', 'Content-Type': 'application/json' }, body: JSON.stringify({ message: warning.trim() }) });
        setSending(false);
        setWarning('');
        setWarningOpen(false);
    };

    const deleteTarget = () => setDeleteOpen(true);
    const confirmDelete = password => new Promise(resolve => {
        router.delete(`${actionPrefix}/${report.id}/target`, { data: { current_password: password }, onFinish: () => { setDeleteOpen(false); resolve(); } });
    });

    return <Layout title="تفاصيل البلاغ">
        <Head title={`تفاصيل البلاغ — ${TYPE_LABELS[targetType]}`} />
        <style>{`.report-detail-chip{display:inline-flex;align-items:center;padding:7px 10px;border-radius:999px;background:#F8FAFC;border:1px solid #E2E8F0;color:#475569;font-size:11px}`}</style>
        <div style={{ display: 'flex', flexDirection: 'column', gap: 16, maxWidth: 980, margin: '0 auto' }}>
            <div style={{ display: 'flex', alignItems: 'center', justifyContent: 'space-between', gap: 12, flexWrap: 'wrap' }}><div><div style={{ fontSize: 22, fontWeight: 900, color: '#1E1B4B' }}>تفاصيل البلاغ</div><div style={{ fontSize: 12, color: '#94A3B8', marginTop: 4 }}>بلاغ على {TYPE_LABELS[targetType]} رقم #{target.id}</div></div><Link href={backHref} style={{ display: 'inline-flex', gap: 6, alignItems: 'center', padding: '9px 14px', borderRadius: 10, background: '#fff', border: '1px solid #E2E8F0', color: '#475569', textDecoration: 'none', fontSize: 12 }}><i className="ti ti-arrow-right" /> العودة للبلاغات</Link></div>
            <div style={{ display: 'grid', gridTemplateColumns: 'minmax(0,1fr) 280px', gap: 16, alignItems: 'start' }}>
                <section style={{ background: '#fff', border: '1px solid #E2E8F0', borderRadius: 18, padding: 22, display: 'flex', flexDirection: 'column', gap: 16 }}><div style={{ display: 'inline-flex', alignItems: 'center', gap: 7, color: '#B91C1C', background: '#FEF2F2', border: '1px solid #FECACA', borderRadius: 999, padding: '7px 11px', fontSize: 11, fontWeight: 800, width: 'fit-content' }}><i className="ti ti-flag" /> المحتوى المبلّغ عنه: {TYPE_LABELS[targetType]}</div><TargetContent target={target} targetType={targetType} /></section>
                <aside style={{ background: '#fff', border: '1px solid #E2E8F0', borderRadius: 18, padding: 18, display: 'flex', flexDirection: 'column', gap: 14 }}><div style={{ fontWeight: 800, color: '#1E1B4B' }}>بيانات البلاغ</div><div style={{ padding: 12, background: '#FFF7ED', border: '1px solid #FED7AA', borderRadius: 12, color: '#9A3412', fontSize: 13, lineHeight: 1.8 }}>{report.reason}</div><div style={{ borderTop: '1px solid #F1F5F9', paddingTop: 12, fontSize: 11, color: '#64748B' }}>المبلّغ</div><div style={{ display: 'flex', alignItems: 'center', gap: 9 }}><Avatar user={report.user} size={38} /><div><div style={{ fontSize: 12, fontWeight: 800 }}>{report.user?.first_name} {report.user?.last_name}</div><div style={{ fontSize: 10, color: '#94A3B8' }}>{report.user?.email}</div></div></div><div style={{ fontSize: 11, color: '#94A3B8' }}><i className="ti ti-clock" /> {new Date(report.created_at).toLocaleString('ar')}</div><div style={{ padding: '10px 12px', background: '#F8FAFC', borderRadius: 10, border: '1px solid #E2E8F0', color: '#475569', fontSize: 12, fontWeight: 800 }}><i className="ti ti-alert-triangle" style={{ color: '#D97706' }} /> عدد التحذيرات لهذا الحساب: {warnings.length}</div><div style={{ maxHeight: 220, overflowY: 'auto', display: 'flex', flexDirection: 'column', gap: 7 }}>{warnings.map(warning => <div key={warning.id} style={{ padding: '8px 10px', background: '#FFFBEB', border: '1px solid #FDE68A', borderRadius: 9, fontSize: 11, color: '#92400E', lineHeight: 1.6 }}><div style={{ fontWeight: 800 }}>{warning.message}</div><div style={{ color: '#B45309', fontSize: 10, marginTop: 3 }}>{warning.issuer_type === 'SuperAdmin' ? 'السوبر أدمن' : 'الأدمن'} · {new Date(warning.created_at).toLocaleString('ar')}</div></div>)}</div><div style={{ borderTop: '1px solid #F1F5F9', paddingTop: 14, display: 'flex', flexDirection: 'column', gap: 8 }}><button type="button" onClick={() => setWarningOpen(true)} style={{ padding: '10px 12px', borderRadius: 10, border: '1px solid #FDE68A', background: '#FFFBEB', color: '#B45309', fontFamily: 'inherit', fontWeight: 800, cursor: 'pointer' }}><i className="ti ti-alert-triangle" /> إرسال تحذير</button>{ownerId && <Link href={`/user/users/${ownerId}`} style={{ padding: '10px 12px', borderRadius: 10, border: '1px solid #BFDBFE', background: '#EFF6FF', color: '#1D4ED8', textDecoration: 'none', textAlign: 'center', fontSize: 12, fontWeight: 800 }}><i className="ti ti-user" /> فتح ملف المستخدم</Link>}<button type="button" onClick={deleteTarget} style={{ padding: '10px 12px', borderRadius: 10, border: '1px solid #FCA5A5', background: '#FEF2F2', color: '#B91C1C', fontFamily: 'inherit', fontWeight: 800, cursor: 'pointer' }}><i className="ti ti-trash" /> حذف {TYPE_LABELS[targetType]}</button></div></aside>
            </div>
        </div>
        {warningOpen && <div onClick={() => setWarningOpen(false)} style={{ position: 'fixed', inset: 0, zIndex: 1000, background: 'rgba(15,23,42,.58)', display: 'flex', alignItems: 'center', justifyContent: 'center', padding: 18 }}><form onClick={event => event.stopPropagation()} onSubmit={sendWarning} style={{ width: '100%', maxWidth: 460, background: '#fff', borderRadius: 18, padding: 22, boxShadow: '0 24px 70px rgba(15,23,42,.28)' }}><div style={{ fontSize: 17, fontWeight: 900, color: '#1E1B4B', marginBottom: 6 }}>إرسال تحذير للمستخدم</div><div style={{ fontSize: 12, color: '#64748B', marginBottom: 14 }}>ستصل الرسالة إلى خانة الإشعارات لدى صاحب المحتوى.</div><textarea value={warning} onChange={event => setWarning(event.target.value)} required rows={5} placeholder="اكتب مضمون التحذير بوضوح..." style={{ width: '100%', boxSizing: 'border-box', padding: 11, border: '1px solid #CBD5E1', borderRadius: 10, resize: 'vertical', fontFamily: 'inherit', fontSize: 12 }} /><div style={{ display: 'flex', justifyContent: 'flex-end', gap: 8, marginTop: 14 }}><button type="button" onClick={() => setWarningOpen(false)} style={{ padding: '9px 14px', border: '1px solid #CBD5E1', background: '#fff', borderRadius: 9, cursor: 'pointer', fontFamily: 'inherit' }}>إلغاء</button><button type="submit" disabled={!warning.trim() || sending} style={{ padding: '9px 16px', border: 0, background: '#D97706', color: '#fff', borderRadius: 9, cursor: 'pointer', fontFamily: 'inherit', fontWeight: 800, opacity: !warning.trim() || sending ? .55 : 1 }}>{sending ? 'جارٍ الإرسال...' : 'إرسال التحذير'}</button></div></form></div>}
        <PasswordConfirmModal open={deleteOpen} title={`حذف ${TYPE_LABELS[targetType]}`} description="هذا الإجراء نهائي. أدخل كلمة مرور الأدمن أو السوبر أدمن الحالية للمتابعة." onClose={() => setDeleteOpen(false)} onConfirm={confirmDelete} />
    </Layout>;
}
