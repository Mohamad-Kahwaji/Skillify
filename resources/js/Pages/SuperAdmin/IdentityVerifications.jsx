import { Head, router, useForm } from '@inertiajs/react';
import { useState, useCallback } from 'react';
import SuperAdminLayout from '../../Layouts/SuperAdminLayout';

const STATUS_CFG = {
    pending:  { label: 'قيد المراجعة', color: '#92400E', bg: '#FEF3C7', border: '#FDE68A', dot: '#F59E0B', icon: 'ti-clock',        bar: '#F59E0B' },
    approved: { label: 'موثّق',        color: '#065F46', bg: '#D1FAE5', border: '#6EE7B7', dot: '#10B981', icon: 'ti-circle-check', bar: '#10B981' },
    rejected: { label: 'مرفوض',        color: '#991B1B', bg: '#FEE2E2', border: '#FCA5A5', dot: '#EF4444', icon: 'ti-circle-x',    bar: '#EF4444' },
};

const ID_TYPE = {
    national_id: { label: 'هوية وطنية', icon: 'ti-id' },
    passport:    { label: 'جواز سفر',   icon: 'ti-license' },
};

const AV = ['#7C3AED','#6D28D9','#0D9488','#2563EB','#D97706','#DC2626'];
function avColor(i) { return AV[i % AV.length]; }
function avColor2(i) { return AV[(i + 2) % AV.length]; }

function timeAgo(d) {
    if (!d) return '';
    const s = Math.floor((Date.now() - new Date(d)) / 1000);
    if (s < 60)    return 'الآن';
    if (s < 3600)  return `منذ ${Math.floor(s/60)} دقيقة`;
    if (s < 86400) return `منذ ${Math.floor(s/3600)} ساعة`;
    return `منذ ${Math.floor(s/86400)} يوم`;
}

/* ── Image lightbox ─────────────────────────────────────────── */
function Lightbox({ src, onClose }) {
    if (!src) return null;
    return (
        <div onClick={onClose} style={{ position: 'fixed', inset: 0, background: 'rgba(0,0,0,0.85)', zIndex: 9999, display: 'flex', alignItems: 'center', justifyContent: 'center' }}>
            <img src={src} alt="" onClick={e => e.stopPropagation()} style={{ maxWidth: '88vw', maxHeight: '88vh', borderRadius: 14, boxShadow: '0 24px 80px rgba(0,0,0,0.6)', objectFit: 'contain' }} />
            <button onClick={onClose} style={{ position: 'absolute', top: 20, left: 20, width: 40, height: 40, borderRadius: '50%', border: 'none', background: 'rgba(255,255,255,0.12)', color: '#fff', fontSize: 18, cursor: 'pointer', display: 'flex', alignItems: 'center', justifyContent: 'center', backdropFilter: 'blur(4px)' }}>
                <i className="ti ti-x" />
            </button>
        </div>
    );
}

/* ── Reject modal ───────────────────────────────────────────── */
function RejectModal({ verification, basePath, onClose }) {
    const { data, setData, patch, processing } = useForm({ reason: '' });

    const submit = (e) => {
        e.preventDefault();
        patch(`${basePath}/${verification.id}/reject`, { onSuccess: onClose });
    };

    return (
        <div onClick={onClose} style={{ position: 'fixed', inset: 0, background: 'rgba(0,0,0,0.50)', zIndex: 998, display: 'flex', alignItems: 'center', justifyContent: 'center', padding: 20 }}>
            <div onClick={e => e.stopPropagation()} style={{ background: '#fff', borderRadius: 18, padding: 28, width: '100%', maxWidth: 460, boxShadow: '0 24px 80px rgba(0,0,0,0.18)' }}>
                <div style={{ display: 'flex', alignItems: 'center', gap: 12, marginBottom: 18 }}>
                    <div style={{ width: 40, height: 40, borderRadius: 12, background: '#FEE2E2', display: 'flex', alignItems: 'center', justifyContent: 'center', color: '#DC2626', fontSize: 18 }}>
                        <i className="ti ti-circle-x" />
                    </div>
                    <div>
                        <div style={{ fontSize: 15, fontWeight: 700, color: '#0F172A' }}>رفض طلب التوثيق</div>
                        <div style={{ fontSize: 11, color: '#94A3B8' }}>{verification.user?.first_name} {verification.user?.last_name}</div>
                    </div>
                </div>
                <form onSubmit={submit}>
                    <label style={{ fontSize: 12, fontWeight: 600, color: '#374151', display: 'block', marginBottom: 7 }}>سبب الرفض *</label>
                    <textarea value={data.reason} onChange={e => setData('reason', e.target.value)} required rows={4}
                        placeholder="اكتب سبب الرفض ليصل للمستخدم..."
                        style={{ width: '100%', padding: '10px 13px', border: '1px solid rgba(0,0,0,0.13)', borderRadius: 10, fontSize: 13, outline: 'none', resize: 'vertical', fontFamily: "'Cairo','Inter',sans-serif", boxSizing: 'border-box', background: '#FAFAFA' }} />
                    <div style={{ display: 'flex', gap: 10, justifyContent: 'flex-end', marginTop: 16 }}>
                        <button type="button" onClick={onClose} style={{ padding: '8px 18px', borderRadius: 9, border: '1px solid rgba(0,0,0,0.12)', background: 'none', fontSize: 13, cursor: 'pointer', fontFamily: "'Cairo','Inter',sans-serif" }}>
                            إلغاء
                        </button>
                        <button type="submit" disabled={processing} style={{ padding: '8px 20px', borderRadius: 9, background: '#DC2626', color: '#fff', border: 'none', fontSize: 13, fontWeight: 700, cursor: 'pointer', display: 'flex', alignItems: 'center', gap: 6, fontFamily: "'Cairo','Inter',sans-serif", opacity: processing ? 0.7 : 1, boxShadow: '0 3px 10px rgba(220,38,38,0.28)' }}>
                            <i className="ti ti-circle-x" />{processing ? 'جارٍ الرفض...' : 'تأكيد الرفض'}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    );
}

/* ── AI Report panel ────────────────────────────────────────── */
function AiReport({ data }) {
    if (!data) return null;
    const ext = data.extracted ?? {};
    const score = data.match_score ?? 0;
    const scoreColor = score >= 80 ? '#059669' : score >= 60 ? '#D97706' : '#DC2626';
    const scoreBg    = score >= 80 ? '#D1FAE5' : score >= 60 ? '#FEF3C7' : '#FEE2E2';
    const verdictCfg = {
        approved: { label: 'توصية: قبول',          color: '#065F46', bg: '#D1FAE5', icon: 'ti-circle-check' },
        review:   { label: 'توصية: مراجعة يدوية',  color: '#92400E', bg: '#FEF3C7', icon: 'ti-alert-triangle' },
        rejected: { label: 'توصية: رفض',            color: '#991B1B', bg: '#FEE2E2', icon: 'ti-circle-x' },
    };
    const vc = verdictCfg[data.verdict] ?? verdictCfg.review;

    return (
        <div style={{ marginTop: 14, background: 'linear-gradient(135deg,#F5F3FF,#EDE9FE)', border: '1px solid #DDD6FE', borderRadius: 12, overflow: 'hidden' }}>
            {/* Header */}
            <div style={{ padding: '10px 16px', background: 'linear-gradient(135deg,#7C3AED,#6D28D9)', display: 'flex', alignItems: 'center', justifyContent: 'space-between', gap: 10 }}>
                <div style={{ display: 'flex', alignItems: 'center', gap: 7, color: '#fff' }}>
                    <i className="ti ti-robot" style={{ fontSize: 16 }} />
                    <span style={{ fontSize: 13, fontWeight: 700 }}>تقرير الذكاء الاصطناعي (Gemini)</span>
                </div>
                <div style={{ display: 'flex', gap: 8, alignItems: 'center' }}>
                    <div style={{ padding: '3px 10px', borderRadius: 20, background: scoreBg, color: scoreColor, fontSize: 12, fontWeight: 800 }}>
                        {score}%
                    </div>
                    <div style={{ padding: '3px 10px', borderRadius: 20, background: vc.bg, color: vc.color, fontSize: 11, fontWeight: 700, display: 'flex', alignItems: 'center', gap: 4 }}>
                        <i className={`ti ${vc.icon}`} style={{ fontSize: 11 }} />{vc.label}
                    </div>
                </div>
            </div>

            <div style={{ padding: '14px 16px', display: 'grid', gridTemplateColumns: 'repeat(auto-fill,minmax(200px,1fr))', gap: 10 }}>
                {/* Extracted fields */}
                {[
                    { label: 'الاسم المستخرج',   val: ext.full_name },
                    { label: 'رقم الهوية',        val: ext.id_number },
                    { label: 'تاريخ الانتهاء',    val: ext.expiry_date },
                    { label: 'الجنس',             val: ext.gender },
                ].filter(f => f.val).map(f => (
                    <div key={f.label} style={{ background: '#fff', borderRadius: 8, padding: '8px 12px', border: '1px solid rgba(124,58,237,0.12)' }}>
                        <div style={{ fontSize: 10, color: '#94A3B8', marginBottom: 3 }}>{f.label}</div>
                        <div style={{ fontSize: 13, fontWeight: 600, color: '#0F172A' }}>{f.val}</div>
                    </div>
                ))}

                {/* Match checks */}
                {[
                    { label: 'تطابق الاسم',      val: data.name_match },
                    { label: 'تطابق رقم الهوية', val: data.id_match },
                    { label: 'أصالة الوثيقة',    val: data.document_authentic },
                ].map(f => f.val !== null && f.val !== undefined && (
                    <div key={f.label} style={{ background: f.val ? '#D1FAE5' : '#FEE2E2', borderRadius: 8, padding: '8px 12px', border: `1px solid ${f.val ? '#6EE7B7' : '#FCA5A5'}` }}>
                        <div style={{ fontSize: 10, color: f.val ? '#065F46' : '#991B1B', marginBottom: 3 }}>{f.label}</div>
                        <div style={{ fontSize: 13, fontWeight: 700, color: f.val ? '#065F46' : '#991B1B', display: 'flex', alignItems: 'center', gap: 5 }}>
                            <i className={`ti ${f.val ? 'ti-check' : 'ti-x'}`} style={{ fontSize: 13 }} />
                            {f.val ? 'مطابق' : 'غير مطابق'}
                        </div>
                    </div>
                ))}
            </div>

            {/* Notes */}
            {data.notes && (
                <div style={{ padding: '10px 16px', borderTop: '1px solid rgba(124,58,237,0.12)', fontSize: 12, color: '#4C1D95', display: 'flex', gap: 7 }}>
                    <i className="ti ti-info-circle" style={{ flexShrink: 0, marginTop: 1 }} />
                    <span>{data.notes}</span>
                </div>
            )}
        </div>
    );
}

/* ── Single verification card ───────────────────────────────── */
function VerifCard({ v, index, basePath, onLightbox, onReject }) {
    const [aiLoading, setAiLoading] = useState(false);
    const sc  = STATUS_CFG[v.status] ?? STATUS_CFG.pending;
    const idt = ID_TYPE[v.id_type]   ?? ID_TYPE.national_id;

    const approve = () => router.patch(`${basePath}/${v.id}/approve`, {}, { preserveScroll: true });

    const runAi = useCallback(() => {
        setAiLoading(true);
        router.post(`${basePath}/${v.id}/analyse-ai`, {}, {
            preserveScroll: true,
            onFinish: () => setAiLoading(false),
        });
    }, [v.id, basePath]);

    const images = [
        { src: v.front_image, label: 'الوجه الأمامي', icon: 'ti-id' },
        { src: v.back_image,  label: 'الوجه الخلفي',  icon: 'ti-id' },
    ].filter(img => img.src);

    return (
        <div style={{
            background: '#fff',
            border: `1px solid ${v.status === 'pending' ? 'rgba(245,158,11,0.22)' : 'rgba(0,0,0,0.07)'}`,
            borderRadius: 16, overflow: 'hidden',
            boxShadow: v.status === 'pending' ? '0 2px 12px rgba(245,158,11,0.08)' : '0 1px 6px rgba(0,0,0,0.04)',
            transition: 'box-shadow 0.15s',
        }}>
            {/* Top color bar */}
            <div style={{ height: 4, background: sc.bar }} />

            <div style={{ padding: '18px 22px' }}>
                <div style={{ display: 'flex', gap: 16, flexWrap: 'wrap', alignItems: 'flex-start' }}>

                    {/* User info */}
                    <div style={{ display: 'flex', gap: 12, flex: 1, minWidth: 220 }}>
                        <div style={{ width: 50, height: 50, borderRadius: '50%', background: `linear-gradient(135deg,${avColor(index)},${avColor2(index)})`, display: 'flex', alignItems: 'center', justifyContent: 'center', fontSize: 17, fontWeight: 800, color: '#fff', flexShrink: 0 }}>
                            {(v.user?.first_name?.[0] ?? 'U').toUpperCase()}
                        </div>
                        <div style={{ flex: 1 }}>
                            <div style={{ fontSize: 14, fontWeight: 700, color: '#0F172A' }}>
                                {v.user?.first_name} {v.user?.last_name}
                            </div>
                            <div style={{ fontSize: 11, color: '#94A3B8', marginTop: 1 }}>{v.user?.email}</div>
                            <div style={{ marginTop: 8, display: 'flex', flexDirection: 'column', gap: 4 }}>
                                <div style={{ fontSize: 12, color: '#475569', display: 'flex', alignItems: 'center', gap: 6 }}>
                                    <i className={`ti ${idt.icon}`} style={{ color: '#7C3AED', fontSize: 13 }} />
                                    <span>{idt.label} — <strong style={{ color: '#0F172A' }}>{v.id_number}</strong></span>
                                </div>
                                <div style={{ fontSize: 12, color: '#475569', display: 'flex', alignItems: 'center', gap: 6 }}>
                                    <i className="ti ti-user" style={{ color: '#7C3AED', fontSize: 13 }} />
                                    <span>الاسم على الوثيقة: <strong style={{ color: '#0F172A' }}>{v.full_name}</strong></span>
                                </div>
                                {v.match_score != null && (
                                    <div style={{ fontSize: 12, color: '#475569', display: 'flex', alignItems: 'center', gap: 6 }}>
                                        <i className="ti ti-chart-bar" style={{ color: '#7C3AED', fontSize: 13 }} />
                                        <span>نسبة التطابق: <strong style={{ color: v.match_score >= 80 ? '#059669' : v.match_score >= 60 ? '#D97706' : '#DC2626' }}>{v.match_score}%</strong></span>
                                    </div>
                                )}
                            </div>
                        </div>
                    </div>

                    {/* Images */}
                    {images.length > 0 && (
                        <div style={{ display: 'flex', gap: 8, alignItems: 'flex-start', flexShrink: 0 }}>
                            {images.map(img => (
                                <div key={img.label}
                                    onClick={() => onLightbox(`/storage/${img.src}`)}
                                    title={`عرض ${img.label}`}
                                    style={{ cursor: 'zoom-in', width: 86, height: 64, borderRadius: 10, overflow: 'hidden', border: '1px solid rgba(0,0,0,0.10)', position: 'relative', flexShrink: 0 }}>
                                    <img src={`/storage/${img.src}`} alt={img.label} style={{ width: '100%', height: '100%', objectFit: 'cover', transition: 'transform 0.15s' }}
                                        onMouseEnter={e => e.currentTarget.style.transform = 'scale(1.06)'}
                                        onMouseLeave={e => e.currentTarget.style.transform = 'scale(1)'} />
                                    <div style={{ position: 'absolute', bottom: 0, left: 0, right: 0, background: 'rgba(0,0,0,0.52)', color: '#fff', fontSize: 9, fontWeight: 600, padding: '2px 5px', textAlign: 'center', backdropFilter: 'blur(2px)' }}>
                                        {img.label}
                                    </div>
                                </div>
                            ))}
                        </div>
                    )}

                    {/* Status + time */}
                    <div style={{ display: 'flex', flexDirection: 'column', alignItems: 'flex-end', gap: 7, minWidth: 130, flexShrink: 0 }}>
                        <span style={{ display: 'inline-flex', alignItems: 'center', gap: 5, fontSize: 11, fontWeight: 700, padding: '4px 11px', borderRadius: 20, background: sc.bg, color: sc.color, border: `1px solid ${sc.border}` }}>
                            <span style={{ width: 5, height: 5, borderRadius: '50%', background: sc.dot }} />
                            {sc.label}
                        </span>
                        <div style={{ fontSize: 10, color: '#94A3B8' }}>{timeAgo(v.created_at)}</div>
                        {v.reviewed_at && (
                            <div style={{ fontSize: 10, color: '#94A3B8', textAlign: 'left' }}>
                                راجعه: {v.reviewer?.first_name ?? 'مشرف'}
                                <br />{new Date(v.reviewed_at).toLocaleDateString('ar', { day: 'numeric', month: 'short' })}
                            </div>
                        )}
                    </div>
                </div>

                {/* Rejection reason */}
                {v.rejection_reason && (
                    <div style={{ marginTop: 14, padding: '10px 14px', background: '#FEF2F2', border: '1px solid #FECACA', borderRadius: 9, fontSize: 12, color: '#991B1B', display: 'flex', gap: 8, alignItems: 'flex-start' }}>
                        <i className="ti ti-alert-triangle" style={{ flexShrink: 0, marginTop: 1 }} />
                        <span><strong>سبب الرفض:</strong> {v.rejection_reason}</span>
                    </div>
                )}

                {/* AI report */}
                {v.extracted_data && <AiReport data={typeof v.extracted_data === 'string' ? JSON.parse(v.extracted_data) : v.extracted_data} />}

                {/* Actions */}
                <div style={{ display: 'flex', gap: 10, marginTop: 16, paddingTop: 14, borderTop: '0.5px solid rgba(0,0,0,0.06)', flexWrap: 'wrap', alignItems: 'center' }}>
                    {/* AI button — always visible */}
                    <button onClick={runAi} disabled={aiLoading} style={{ display: 'inline-flex', alignItems: 'center', gap: 7, padding: '9px 18px', borderRadius: 10, background: aiLoading ? '#EDE9FE' : 'linear-gradient(135deg,#7C3AED,#6D28D9)', color: aiLoading ? '#7C3AED' : '#fff', border: aiLoading ? '1px solid #DDD6FE' : 'none', fontSize: 13, fontWeight: 700, cursor: aiLoading ? 'wait' : 'pointer', fontFamily: "'Cairo','Inter',sans-serif", transition: 'all 0.15s', boxShadow: aiLoading ? 'none' : '0 3px 12px rgba(124,58,237,0.28)' }}>
                        <i className={`ti ${aiLoading ? 'ti-loader-2' : 'ti-robot'}`} style={{ fontSize: 15, animation: aiLoading ? 'spin 1s linear infinite' : 'none' }} />
                        {aiLoading ? 'جارٍ التحليل...' : (v.extracted_data ? 'إعادة التحليل' : 'تحليل بالذكاء الاصطناعي')}
                    </button>

                    {/* Approve — show when pending or rejected */}
                    {v.status !== 'approved' && (
                        <button onClick={approve} style={{ display: 'inline-flex', alignItems: 'center', gap: 7, padding: '9px 22px', borderRadius: 10, background: 'linear-gradient(135deg,#059669,#10B981)', color: '#fff', border: 'none', fontSize: 13, fontWeight: 700, cursor: 'pointer', fontFamily: "'Cairo','Inter',sans-serif", boxShadow: '0 3px 12px rgba(5,150,105,0.28)', transition: 'opacity 0.13s' }}
                            onMouseEnter={e => e.currentTarget.style.opacity = '0.88'} onMouseLeave={e => e.currentTarget.style.opacity = '1'}>
                            <i className="ti ti-circle-check" style={{ fontSize: 15 }} /> قبول التوثيق
                        </button>
                    )}

                    {/* Reject — show when pending or approved */}
                    {v.status !== 'rejected' && (
                        <button onClick={() => onReject(v)} style={{ display: 'inline-flex', alignItems: 'center', gap: 7, padding: '9px 18px', borderRadius: 10, border: '1px solid #FCA5A5', background: '#FEF2F2', color: '#DC2626', fontSize: 13, fontWeight: 700, cursor: 'pointer', fontFamily: "'Cairo','Inter',sans-serif", transition: 'background 0.13s' }}
                            onMouseEnter={e => e.currentTarget.style.background = '#FEE2E2'} onMouseLeave={e => e.currentTarget.style.background = '#FEF2F2'}>
                            <i className="ti ti-circle-x" style={{ fontSize: 15 }} /> رفض
                        </button>
                    )}

                    {/* Re-review — show when approved or rejected (reset to pending) */}
                    {v.status !== 'pending' && (
                        <button onClick={() => router.patch(`${basePath}/${v.id}/pending`, {}, { preserveScroll: true })}
                            style={{ display: 'inline-flex', alignItems: 'center', gap: 6, padding: '9px 16px', borderRadius: 10, border: '1px solid #C7D2FE', background: '#EEF2FF', color: '#4338CA', fontSize: 12, fontWeight: 600, cursor: 'pointer', fontFamily: "'Cairo','Inter',sans-serif", transition: 'background 0.13s' }}
                            onMouseEnter={e => e.currentTarget.style.background = '#E0E7FF'} onMouseLeave={e => e.currentTarget.style.background = '#EEF2FF'}>
                            <i className="ti ti-refresh" style={{ fontSize: 13 }} /> إعادة للمراجعة
                        </button>
                    )}

                    {v.status === 'approved' && (
                        <div style={{ display: 'inline-flex', alignItems: 'center', gap: 6, padding: '9px 14px', background: '#D1FAE5', border: '1px solid #6EE7B7', borderRadius: 9, fontSize: 12, color: '#065F46', fontWeight: 600 }}>
                            <i className="ti ti-shield-check" /> موثّق
                        </div>
                    )}
                </div>
            </div>
        </div>
    );
}

/* ═══════════════════════════════════════════════════════════════ */
export default function IdentityVerifications({ verifications }) {
    const [filter, setFilter]       = useState('all');
    const [search, setSearch]       = useState('');
    const [lightbox, setLightbox]   = useState(null);
    const [rejectTarget, setRejectTarget] = useState(null);
    const [allLoading, setAllLoading] = useState(false);

    const runAnalyseAll = () => {
        setAllLoading(true);
        router.post('/super-admin/identity-verifications/analyse-all', {}, {
            preserveScroll: true,
            onFinish: () => setAllLoading(false),
        });
    };

    const basePath = '/super-admin/identity-verifications';
    const all      = verifications ?? [];

    const filtered = all.filter(v => {
        const matchStatus = filter === 'all' || v.status === filter;
        const q = `${v.user?.first_name ?? ''} ${v.user?.last_name ?? ''} ${v.full_name ?? ''} ${v.id_number ?? ''}`.toLowerCase();
        return matchStatus && q.includes(search.toLowerCase());
    });

    const counts = {
        all:      all.length,
        pending:  all.filter(v => v.status === 'pending').length,
        approved: all.filter(v => v.status === 'approved').length,
        rejected: all.filter(v => v.status === 'rejected').length,
    };

    const TABS = [
        { key: 'all',      label: 'الكل',         icon: 'ti-list',         color: '#1E1B4B', bg: '#EEF2FF', border: '#C7D2FE' },
        { key: 'pending',  label: 'قيد المراجعة', icon: 'ti-clock',        color: '#92400E', bg: '#FEF3C7', border: '#FDE68A' },
        { key: 'approved', label: 'موثّق',         icon: 'ti-circle-check', color: '#065F46', bg: '#D1FAE5', border: '#6EE7B7' },
        { key: 'rejected', label: 'مرفوض',         icon: 'ti-circle-x',    color: '#991B1B', bg: '#FEE2E2', border: '#FCA5A5' },
    ];

    return (
        <SuperAdminLayout title="توثيق الهوية">
            <Head title="توثيق الهوية — Skillify" />

            <Lightbox src={lightbox} onClose={() => setLightbox(null)} />
            {rejectTarget && (
                <RejectModal
                    verification={rejectTarget}
                    basePath={basePath}
                    onClose={() => setRejectTarget(null)}
                />
            )}

            {/* Header */}
            <div style={{ display: 'flex', alignItems: 'flex-start', justifyContent: 'space-between', flexWrap: 'wrap', gap: 12 }}>
                <div>
                    <h1 style={{ fontSize: 22, fontWeight: 800, color: '#1E1B4B', margin: 0, letterSpacing: -0.5 }}>توثيق الهوية</h1>
                    <p style={{ fontSize: 12, color: '#94A3B8', marginTop: 4 }}>
                        {counts.pending > 0 && (
                            <span style={{ color: '#D97706', fontWeight: 700 }}>{counts.pending} معلق · </span>
                        )}
                        {all.length} طلب إجمالاً
                    </p>
                </div>

                <div style={{ display: 'flex', gap: 10, flexWrap: 'wrap', alignItems: 'center' }}>
                    {/* Stats chips */}
                    <div style={{ display: 'flex', gap: 8, flexWrap: 'wrap' }}>
                        {[
                            { label: 'موثّق', count: counts.approved, color: '#065F46', bg: '#D1FAE5', border: '#6EE7B7' },
                            { label: 'معلق',  count: counts.pending,  color: '#92400E', bg: '#FEF3C7', border: '#FDE68A' },
                            { label: 'مرفوض', count: counts.rejected, color: '#991B1B', bg: '#FEE2E2', border: '#FCA5A5' },
                        ].map(s => (
                            <div key={s.label} style={{ display: 'inline-flex', alignItems: 'center', gap: 6, padding: '6px 14px', borderRadius: 22, background: s.bg, border: `1px solid ${s.border}`, color: s.color, fontSize: 12, fontWeight: 700 }}>
                                <span style={{ fontSize: 15, fontWeight: 800 }}>{s.count}</span> {s.label}
                            </div>
                        ))}
                    </div>

                    {/* Analyse All pending button */}
                    {counts.pending > 0 && (
                        <button onClick={runAnalyseAll} disabled={allLoading} style={{
                            display: 'inline-flex', alignItems: 'center', gap: 8,
                            padding: '10px 20px', borderRadius: 11,
                            background: allLoading ? '#EDE9FE' : 'linear-gradient(135deg,#7C3AED,#5B21B6)',
                            color: allLoading ? '#7C3AED' : '#fff',
                            border: allLoading ? '1px solid #DDD6FE' : 'none',
                            fontSize: 13, fontWeight: 700, cursor: allLoading ? 'wait' : 'pointer',
                            fontFamily: "'Cairo','Inter',sans-serif",
                            boxShadow: allLoading ? 'none' : '0 4px 16px rgba(124,58,237,0.35)',
                            transition: 'all 0.15s',
                        }}>
                            <i className={`ti ${allLoading ? 'ti-loader-2' : 'ti-robot'}`}
                               style={{ fontSize: 16, animation: allLoading ? 'spin 1s linear infinite' : 'none' }} />
                            {allLoading
                                ? 'جارٍ تحليل الكل...'
                                : `تحليل المعلقين بـ AI (${counts.pending})`
                            }
                        </button>
                    )}
                </div>
            </div>

            {/* Filter + Search */}
            <div style={{ display: 'flex', gap: 10, flexWrap: 'wrap', alignItems: 'center' }}>
                <div style={{ position: 'relative', flex: 1, minWidth: 220 }}>
                    <i className="ti ti-search" style={{ position: 'absolute', top: '50%', right: 12, transform: 'translateY(-50%)', color: '#94A3B8', fontSize: 14, pointerEvents: 'none' }} />
                    <input value={search} onChange={e => setSearch(e.target.value)}
                        placeholder="بحث بالاسم أو رقم الهوية أو البريد..."
                        style={{ width: '100%', padding: '9px 36px 9px 13px', border: '1px solid rgba(0,0,0,0.11)', borderRadius: 10, fontSize: 13, outline: 'none', fontFamily: "'Cairo','Inter',sans-serif", background: '#FAFAFA', boxSizing: 'border-box' }} />
                </div>
                {TABS.map(t => (
                    <button key={t.key} onClick={() => setFilter(t.key)} style={{
                        display: 'inline-flex', alignItems: 'center', gap: 5, padding: '7px 15px', borderRadius: 24,
                        border: `1px solid ${filter === t.key ? t.border : 'rgba(0,0,0,0.10)'}`,
                        background: filter === t.key ? t.bg : '#fff',
                        color: filter === t.key ? t.color : '#64748B',
                        fontSize: 12, fontWeight: filter === t.key ? 700 : 500, cursor: 'pointer',
                        fontFamily: "'Cairo','Inter',sans-serif", transition: 'all 0.13s',
                    }}>
                        <i className={`ti ${t.icon}`} style={{ fontSize: 13 }} />
                        {t.label} ({counts[t.key]})
                    </button>
                ))}
            </div>

            {/* Content */}
            {!filtered.length ? (
                <div style={{ background: '#fff', border: '0.5px solid rgba(0,0,0,0.07)', borderRadius: 16, padding: '72px 24px', textAlign: 'center', color: '#94A3B8' }}>
                    <i className="ti ti-id-badge" style={{ fontSize: 52, display: 'block', opacity: 0.09, marginBottom: 14 }} />
                    <div style={{ fontSize: 15, fontWeight: 600, color: '#64748B', marginBottom: 6 }}>لا توجد نتائج</div>
                    <p style={{ fontSize: 13, margin: 0 }}>جرّب تغيير الفلتر أو كلمة البحث.</p>
                </div>
            ) : (
                <div style={{ display: 'flex', flexDirection: 'column', gap: 14 }}>
                    {filtered.map((v, i) => (
                        <VerifCard
                            key={v.id}
                            v={v}
                            index={i}
                            basePath={basePath}
                            onLightbox={setLightbox}
                            onReject={setRejectTarget}
                        />
                    ))}
                </div>
            )}
        </SuperAdminLayout>
    );
}
