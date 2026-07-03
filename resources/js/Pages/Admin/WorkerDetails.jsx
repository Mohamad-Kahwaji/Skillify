import { Head, Link, router } from '@inertiajs/react';
import AdminLayout from '../../Layouts/AdminLayout';

const STATUS = {
    active:   { bg: '#F0FDF4', color: '#134E4A' },
    pending:  { bg: '#FEF3C7', color: '#92400E' },
    rejected: { bg: '#FEF2F2', color: '#B91C1C' },
};

function InfoRow({ label, value }) {
    return (
        <div style={{ display: 'flex', gap: 12, padding: '9px 0', borderBottom: '0.5px solid rgba(0,0,0,0.06)', alignItems: 'flex-start' }}>
            <span style={{ fontSize: 11, color: '#94A3B8', width: 130, flexShrink: 0 }}>{label}</span>
            <span style={{ fontSize: 12, color: '#0F172A', fontWeight: 500 }}>{value ?? '—'}</span>
        </div>
    );
}

export default function WorkerDetails({ business }) {
    const s = STATUS[business.status ?? 'pending'] ?? STATUS.pending;
    const approve = () => router.patch(`/admin/workers/${business.id}/approve`, {}, { preserveScroll: true });
    const reject  = () => router.patch(`/admin/workers/${business.id}/reject`,  {}, { preserveScroll: true });

    return (
        <AdminLayout title="تفاصيل الحرفي">
            <Head title={`${business.name} — Skillify`} />

            <div style={{ display: 'flex', alignItems: 'center', gap: 10 }}>
                <Link href="/admin/workers" style={{ fontSize: 12, color: '#94A3B8', textDecoration: 'none', display: 'flex', alignItems: 'center', gap: 4 }}>
                    <i className="ti ti-arrow-right" /> الحرفيون
                </Link>
                <i className="ti ti-chevron-right" style={{ fontSize: 11, color: '#CBD5E1' }} />
                <span style={{ fontSize: 12, color: '#0F172A' }}>{business.name}</span>
            </div>

            <div className="grid-cols-1 lg:grid-cols-[1fr_320px]" style={{ display: 'grid', gap: 18, alignItems: 'start' }}>
                <div style={{ background: '#fff', border: '0.5px solid rgba(0,0,0,0.07)', borderRadius: 14, overflow: 'hidden' }}>
                    <div style={{ display: 'flex', alignItems: 'center', gap: 14, padding: '18px 20px', borderBottom: '0.5px solid rgba(0,0,0,0.07)' }}>
                        <div style={{ width: 60, height: 60, borderRadius: 12, background: '#F1F5F9', overflow: 'hidden', display: 'flex', alignItems: 'center', justifyContent: 'center', fontSize: 24, color: '#94A3B8', flexShrink: 0 }}>
                            {business.image ? <img src={`/storage/${business.image}`} style={{ width: '100%', height: '100%', objectFit: 'cover' }} alt={business.name} /> : <i className="ti ti-briefcase" />}
                        </div>
                        <div>
                            <div style={{ fontSize: 17, fontWeight: 700, color: '#0F172A' }}>{business.name}</div>
                            <div style={{ fontSize: 13, color: '#0D9488' }}>{business.name_job}</div>
                        </div>
                        <span style={{ fontSize: 11, fontWeight: 600, padding: '3px 10px', borderRadius: 20, marginLeft: 'auto', ...s }}>{business.status ?? 'pending'}</span>
                    </div>
                    <div style={{ padding: '16px 20px' }}>
                        <InfoRow label="النشاط"       value={business.activity} />
                        <InfoRow label="الهاتف"       value={business.number} />
                        <InfoRow label="الوصف"        value={business.description} />
                        <InfoRow label="خط العرض"     value={business.latitude} />
                        <InfoRow label="خط الطول"     value={business.longitude} />
                        <InfoRow label="تاريخ الإنشاء" value={new Date(business.created_at).toLocaleString('ar')} />
                    </div>
                </div>

                <div style={{ display: 'flex', flexDirection: 'column', gap: 14 }}>
                    {/* Owner card */}
                    <div style={{ background: '#fff', border: '0.5px solid rgba(0,0,0,0.07)', borderRadius: 14, padding: '16px 18px' }}>
                        <div style={{ fontSize: 12, fontWeight: 600, color: '#475569', marginBottom: 12, textTransform: 'uppercase', letterSpacing: 0.5 }}>المالك</div>
                        <InfoRow label="الاسم"  value={`${business.user?.first_name ?? ''} ${business.user?.last_name ?? ''}`} />
                        <InfoRow label="البريد" value={business.user?.email} />
                        <InfoRow label="الهاتف" value={business.user?.phone} />
                        <InfoRow label="المدينة" value={business.user?.city} />
                    </div>

                    {/* Actions */}
                    {business.status === 'pending' && (
                        <div style={{ background: '#fff', border: '0.5px solid rgba(0,0,0,0.07)', borderRadius: 14, padding: 16, display: 'flex', flexDirection: 'column', gap: 8 }}>
                            <button onClick={approve} style={{ width: '100%', padding: '10px', borderRadius: 9, border: 'none', background: '#0D9488', color: '#fff', fontSize: 13, fontWeight: 700, cursor: 'pointer', display: 'flex', alignItems: 'center', justifyContent: 'center', gap: 6 }}>
                                <i className="ti ti-check" /> قبول
                            </button>
                            <button onClick={reject} style={{ width: '100%', padding: '10px', borderRadius: 9, border: '0.5px solid #EF4444', background: 'none', color: '#EF4444', fontSize: 13, fontWeight: 700, cursor: 'pointer', display: 'flex', alignItems: 'center', justifyContent: 'center', gap: 6 }}>
                                <i className="ti ti-x" /> رفض
                            </button>
                        </div>
                    )}
                </div>
            </div>
        </AdminLayout>
    );
}
