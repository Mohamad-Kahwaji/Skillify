import { Head, Link, router } from '@inertiajs/react';
import AdminLayout from '../../Layouts/AdminLayout';

const STATUS = {
    approved: { bg: '#F0FDF4', color: '#134E4A' },
    pending:  { bg: '#FEF3C7', color: '#92400E' },
    rejected: { bg: '#FEF2F2', color: '#B91C1C' },
};

function InfoRow({ label, value }) {
    return (
        <div style={{ display: 'flex', gap: 12, padding: '10px 0', borderBottom: '0.5px solid rgba(0,0,0,0.06)', alignItems: 'flex-start' }}>
            <div style={{ width: 130, fontSize: 11, fontWeight: 600, color: '#94A3B8', textTransform: 'uppercase', letterSpacing: 0.4, flexShrink: 0, paddingTop: 1 }}>{label}</div>
            <div style={{ fontSize: 13, color: '#0F172A', flex: 1 }}>{value ?? '—'}</div>
        </div>
    );
}

export default function ServiceDetails({ service }) {
    const img    = service.image ? (service.image.startsWith('http') ? service.image : `/storage/${service.image}`) : null;
    const badge  = STATUS[service.status ?? 'pending'] ?? STATUS.pending;
    const price  = Number(service.price).toLocaleString();
    const owner  = service.user;
    const biz    = service.business;

    const toggle = () => router.patch(`/admin/services/${service.id}/toggle`, {}, { preserveScroll: true });
    const destroy = () => {
        if (!confirm('حذف هذه الخدمة نهائياً؟')) return;
        router.delete(`/admin/services/${service.id}`, { onSuccess: () => router.visit('/admin/services') });
    };

    return (
        <AdminLayout title="تفاصيل الخدمة">
            <Head title={`${service.name} — Skillify`} />

            {/* Breadcrumb */}
            <div style={{ display: 'flex', alignItems: 'center', gap: 6, fontSize: 12, color: '#94A3B8' }}>
                <Link href="/admin/services" style={{ color: '#0D9488', textDecoration: 'none' }}>الخدمات</Link>
                <i className="ti ti-chevron-right" style={{ fontSize: 11 }} />
                <span>{service.name}</span>
            </div>

            <div className="grid-cols-1 lg:grid-cols-[1fr_320px]" style={{ display: 'grid', gap: 20, alignItems: 'start' }}>
                {/* Left */}
                <div style={{ display: 'flex', flexDirection: 'column', gap: 16 }}>
                    {/* Image */}
                    <div style={{ width: '100%', height: 280, background: '#F1F5F9', borderRadius: 14, overflow: 'hidden', display: 'flex', alignItems: 'center', justifyContent: 'center', fontSize: 56, color: '#94A3B8' }}>
                        {img ? <img src={img} alt={service.name} style={{ width: '100%', height: '100%', objectFit: 'cover' }} /> : <i className="ti ti-tool" />}
                    </div>

                    {/* Details */}
                    <div style={{ background: '#fff', border: '0.5px solid rgba(0,0,0,0.07)', borderRadius: 14, padding: '18px 20px' }}>
                        <div style={{ fontSize: 16, fontWeight: 700, marginBottom: 14, display: 'flex', alignItems: 'center', gap: 10 }}>
                            {service.name}
                            <span style={{ fontSize: 10, fontWeight: 600, padding: '3px 10px', borderRadius: 20, ...badge }}>
                                {service.status ?? 'pending'}
                            </span>
                            <span style={{ fontSize: 10, fontWeight: 600, padding: '3px 10px', borderRadius: 20, background: service.is_active ? '#F0FDF4' : '#F3F4F6', color: service.is_active ? '#134E4A' : '#6B7280' }}>
                                {service.is_active ? 'نشط' : 'غير نشط'}
                            </span>
                        </div>

                        <InfoRow label="الفئة"        value={service.category?.name} />
                        <InfoRow label="الفئة الفرعية" value={service.subcategory?.name} />
                        <InfoRow label="المدينة"      value={service.city?.name} />
                        <InfoRow label="السعر"        value={`${price} ${service.price_type?.toUpperCase()}`} />
                        {service.description && (
                            <div style={{ marginTop: 14 }}>
                                <div style={{ fontSize: 11, fontWeight: 600, color: '#94A3B8', textTransform: 'uppercase', letterSpacing: 0.4, marginBottom: 6 }}>الوصف</div>
                                <p style={{ fontSize: 13, color: '#475569', lineHeight: 1.7 }}>{service.description}</p>
                            </div>
                        )}
                    </div>
                </div>

                {/* Right */}
                <div style={{ display: 'flex', flexDirection: 'column', gap: 14 }}>
                    {/* Provider */}
                    <div style={{ background: '#fff', border: '0.5px solid rgba(0,0,0,0.07)', borderRadius: 14, padding: 18 }}>
                        <div style={{ fontSize: 11, fontWeight: 600, color: '#94A3B8', textTransform: 'uppercase', letterSpacing: 0.4, marginBottom: 14 }}>مقدم الخدمة</div>
                        {owner && (
                            <div style={{ display: 'flex', alignItems: 'center', gap: 10, marginBottom: 14 }}>
                                <div style={{ width: 42, height: 42, borderRadius: '50%', background: '#0D9488', color: '#fff', display: 'flex', alignItems: 'center', justifyContent: 'center', fontSize: 16, fontWeight: 700, flexShrink: 0 }}>
                                    {(owner.first_name ?? 'U')[0].toUpperCase()}
                                </div>
                                <div>
                                    <div style={{ fontSize: 13, fontWeight: 700, color: '#0F172A' }}>{owner.first_name} {owner.last_name}</div>
                                    <div style={{ fontSize: 11, color: '#94A3B8' }}>{owner.email}</div>
                                </div>
                            </div>
                        )}
                        {biz && (
                            <div style={{ background: '#F8FAFC', borderRadius: 8, padding: '10px 12px', fontSize: 12, color: '#475569' }}>
                                <div style={{ fontWeight: 600, color: '#0F172A', marginBottom: 2 }}>{biz.name}</div>
                                {biz.name_job && <div style={{ color: '#0D9488' }}>{biz.name_job}</div>}
                            </div>
                        )}
                    </div>

                    {/* Actions */}
                    <div style={{ background: '#fff', border: '0.5px solid rgba(0,0,0,0.07)', borderRadius: 14, padding: 18, display: 'flex', flexDirection: 'column', gap: 8 }}>
                        <div style={{ fontSize: 11, fontWeight: 600, color: '#94A3B8', textTransform: 'uppercase', letterSpacing: 0.4, marginBottom: 4 }}>إجراءات</div>
                        <button onClick={toggle} style={{ width: '100%', padding: '9px', borderRadius: 9, border: '0.5px solid rgba(0,0,0,0.12)', background: '#fff', fontSize: 13, fontWeight: 500, cursor: 'pointer', display: 'flex', alignItems: 'center', justifyContent: 'center', gap: 6, color: service.is_active ? '#F59E0B' : '#0D9488' }}>
                            <i className={service.is_active ? 'ti ti-toggle-right' : 'ti ti-toggle-left'} />
                            {service.is_active ? 'تعطيل الخدمة' : 'تفعيل الخدمة'}
                        </button>
                        <button onClick={destroy} style={{ width: '100%', padding: '9px', borderRadius: 9, border: 'none', background: '#FEF2F2', color: '#EF4444', fontSize: 13, fontWeight: 500, cursor: 'pointer', display: 'flex', alignItems: 'center', justifyContent: 'center', gap: 6 }}>
                            <i className="ti ti-trash" /> حذف الخدمة
                        </button>
                    </div>
                </div>
            </div>
        </AdminLayout>
    );
}
