import { Head, Link } from '@inertiajs/react';

function whatsappUrl(number, message = '') {
    return `https://wa.me/${String(number ?? '+963995227120').replace(/\D/g, '')}${message ? `?text=${encodeURIComponent(message)}` : ''}`;
}

export default function About({ platform }) {
    const whatsapp = whatsappUrl(platform?.whatsapp_number, 'مرحباً، أود الاستفسار عن منصة Skillify.');

    return (
        <div dir="rtl" style={{ minHeight: '100vh', background: '#F8FAFC', fontFamily: "'Cairo','Inter',sans-serif" }}>
            <Head title={`${platform?.about_title ?? 'عن Skillify'} — Skillify`} />
            <header style={{ height: 68, padding: '0 clamp(18px,5vw,72px)', display: 'flex', alignItems: 'center', justifyContent: 'space-between', background: '#fff', borderBottom: '1px solid rgba(15,23,42,.07)' }}>
                <Link href="/" style={{ display: 'inline-flex', alignItems: 'center' }}><img src="/images/logo-dark-text.jpg" alt="Skillify" style={{ height: 40, width: 'auto' }} /></Link>
                <Link href="/" style={{ color: '#0F766E', textDecoration: 'none', fontSize: 13, fontWeight: 700, display: 'inline-flex', gap: 6, alignItems: 'center' }}><i className="ti ti-arrow-right" /> العودة للرئيسية</Link>
            </header>
            <main style={{ maxWidth: 980, margin: '0 auto', padding: '64px 20px' }}>
                <section style={{ background: 'linear-gradient(120deg,#0F766E,#134E4A 60%,#0B2F2A)', color: '#fff', borderRadius: 20, padding: 'clamp(28px,6vw,62px)', boxShadow: '0 20px 44px rgba(15,118,110,.18)' }}>
                    <div style={{ width: 48, height: 48, borderRadius: 14, background: 'rgba(255,255,255,.15)', display: 'flex', alignItems: 'center', justifyContent: 'center', marginBottom: 20 }}><i className="ti ti-sparkles" style={{ fontSize: 24 }} /></div>
                    <h1 style={{ margin: 0, fontSize: 'clamp(28px,5vw,46px)', lineHeight: 1.2, fontWeight: 800 }}>{platform?.about_title ?? 'عن منصة Skillify'}</h1>
                    <p style={{ margin: '18px 0 0', maxWidth: 720, fontSize: 16, lineHeight: 1.9, color: 'rgba(255,255,255,.82)', whiteSpace: 'pre-line' }}>{platform?.about_body}</p>
                </section>
                <section style={{ display: 'grid', gridTemplateColumns: 'repeat(auto-fit,minmax(220px,1fr))', gap: 14, marginTop: 22 }}>
                    {[
                        { icon: 'ti-briefcase', title: 'خدمات موثوقة', text: 'اكتشف خدمات ومزودين في مكان واحد.' },
                        { icon: 'ti-message-circle', title: 'تواصل مباشر', text: 'ابدأ المحادثة مع مزود الخدمة بسهولة.' },
                        { icon: 'ti-map-pin', title: 'نتائج أقرب', text: 'اعثر على الخدمات الأقرب إلى موقعك.' },
                    ].map(item => <div key={item.title} style={{ background: '#fff', border: '1px solid rgba(15,23,42,.08)', borderRadius: 14, padding: 22 }}><i className={`ti ${item.icon}`} style={{ color: '#0D9488', fontSize: 24 }} /><h2 style={{ fontSize: 15, color: '#0F172A', margin: '12px 0 6px' }}>{item.title}</h2><p style={{ margin: 0, color: '#64748B', fontSize: 13, lineHeight: 1.6 }}>{item.text}</p></div>)}
                </section>
                <section style={{ marginTop: 22, padding: 24, background: '#fff', border: '1px solid rgba(15,23,42,.08)', borderRadius: 14, display: 'flex', gap: 18, alignItems: 'center', justifyContent: 'space-between', flexWrap: 'wrap' }}>
                    <div><div style={{ fontWeight: 800, color: '#0F172A', fontSize: 16 }}>تواصل مع فريق Skillify</div><div style={{ color: '#64748B', fontSize: 13, marginTop: 5 }}>{platform?.contact_email ?? 'support@skillify.sy'} · {platform?.contact_phone ?? platform?.whatsapp_number}</div></div>
                    <a href={whatsapp} target="_blank" rel="noreferrer" style={{ padding: '11px 18px', borderRadius: 10, background: '#16A34A', color: '#fff', fontSize: 13, fontWeight: 700, textDecoration: 'none', display: 'inline-flex', alignItems: 'center', gap: 7 }}><i className="ti ti-brand-whatsapp" style={{ fontSize: 18 }} /> تواصل عبر واتساب</a>
                </section>
            </main>
        </div>
    );
}
