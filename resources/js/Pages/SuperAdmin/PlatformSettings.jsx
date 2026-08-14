import { Head, useForm } from '@inertiajs/react';
import SuperAdminLayout from '../../Layouts/SuperAdminLayout';

const input = { width: '100%', padding: '11px 13px', border: '1px solid rgba(124,58,237,.2)', borderRadius: 10, fontFamily: "'Cairo','Inter',sans-serif", fontSize: 13, outline: 'none', boxSizing: 'border-box', background: '#fff' };

export default function PlatformSettings({ settings }) {
    const { data, setData, put, processing, errors } = useForm({
        whatsapp_number: settings?.whatsapp_number ?? '+963995227120', contact_phone: settings?.contact_phone ?? '', contact_email: settings?.contact_email ?? '', about_title: settings?.about_title ?? 'عن منصة Skillify', about_body: settings?.about_body ?? '',
    });
    const submit = event => { event.preventDefault(); put('/super-admin/platform-settings'); };

    return <SuperAdminLayout title="إعدادات المنصة"><Head title="إعدادات المنصة — Skillify" />
        <div><h1 style={{ margin: 0, fontSize: 22, fontWeight: 800, color: '#1E1B4B' }}>إعدادات المنصة</h1><p style={{ margin: '5px 0 0', color: '#94A3B8', fontSize: 12 }}>تحكم بمعلومات صفحة من نحن، وسائل التواصل وروابط واتساب.</p></div>
        <form onSubmit={submit} style={{ background: '#fff', border: '1px solid rgba(124,58,237,.15)', borderRadius: 16, padding: '24px', boxShadow: '0 8px 24px rgba(15,23,42,.04)' }}>
            <section><div style={{ display: 'flex', gap: 9, alignItems: 'center', fontWeight: 800, color: '#1E1B4B', marginBottom: 18 }}><i className="ti ti-brand-whatsapp" style={{ color: '#16A34A', fontSize: 20 }} /> واتساب والتواصل</div><div style={{ display: 'grid', gridTemplateColumns: 'repeat(auto-fit,minmax(220px,1fr))', gap: 14 }}>
                <Field label="رقم واتساب" error={errors.whatsapp_number}><input dir="ltr" style={input} value={data.whatsapp_number} onChange={e => setData('whatsapp_number', e.target.value)} /></Field>
                <Field label="رقم التواصل" error={errors.contact_phone}><input dir="ltr" style={input} value={data.contact_phone} onChange={e => setData('contact_phone', e.target.value)} /></Field>
                <Field label="البريد الإلكتروني" error={errors.contact_email}><input type="email" dir="ltr" style={input} value={data.contact_email} onChange={e => setData('contact_email', e.target.value)} /></Field>
            </div></section>
            <div style={{ height: 1, background: '#EDE9FE', margin: '24px 0' }} />
            <section><div style={{ display: 'flex', gap: 9, alignItems: 'center', fontWeight: 800, color: '#1E1B4B', marginBottom: 18 }}><i className="ti ti-info-circle" style={{ color: '#7C3AED', fontSize: 20 }} /> صفحة من نحن</div><div style={{ display: 'grid', gap: 14 }}>
                <Field label="العنوان" error={errors.about_title}><input style={input} value={data.about_title} onChange={e => setData('about_title', e.target.value)} /></Field>
                <Field label="النص التعريفي" error={errors.about_body}><textarea rows="8" style={{ ...input, resize: 'vertical', lineHeight: 1.7 }} value={data.about_body} onChange={e => setData('about_body', e.target.value)} /></Field>
            </div></section>
            <div style={{ display: 'flex', justifyContent: 'flex-end', marginTop: 24 }}><button disabled={processing} type="submit" style={{ padding: '10px 20px', border: 0, borderRadius: 10, background: 'linear-gradient(135deg,#7C3AED,#6D28D9)', color: '#fff', fontFamily: "'Cairo','Inter',sans-serif", fontSize: 13, fontWeight: 700, cursor: processing ? 'wait' : 'pointer' }}>{processing ? 'جارٍ الحفظ...' : 'حفظ الإعدادات'}</button></div>
        </form>
    </SuperAdminLayout>;
}

function Field({ label, error, children }) { return <label style={{ display: 'block', fontSize: 12, fontWeight: 700, color: '#475569' }}>{label}<div style={{ marginTop: 6 }}>{children}</div>{error && <div style={{ color: '#DC2626', fontSize: 11, marginTop: 4 }}>{error}</div>}</label>; }
