import { Head, useForm, router, usePage } from '@inertiajs/react';
import { useState, useRef, useEffect } from 'react';
import UserLayout from '../../Layouts/UserLayout';

const T  = '#0D9488';
const T2 = '#0F766E';
const FONT = "'Cairo','Inter',sans-serif";

/* ─── Shared input/label styles ─────────────────────────── */
const INPUT_BASE = {
    width: '100%', padding: '11px 14px',
    background: '#fff',
    border: '1.5px solid #E2E8F0',
    borderRadius: 10, color: '#0F172A',
    fontSize: 13.5, fontFamily: FONT,
    outline: 'none', boxSizing: 'border-box',
    transition: 'border-color .15s, box-shadow .15s',
};
const LABEL_S = {
    fontSize: 12, fontWeight: 700, color: '#64748B',
    marginBottom: 6, display: 'flex', alignItems: 'center', gap: 5,
};

/* ─── Primitive components (defined OUTSIDE to avoid focus loss) ──── */
function Field({ label, icon, error, children }) {
    return (
        <div>
            <label style={LABEL_S}>
                {icon && <i className={`ti ${icon}`} style={{ color: T, fontSize: 12 }} />}
                {label}
            </label>
            {children}
            {error && <p style={{ fontSize: 11, color: '#EF4444', marginTop: 4 }}>{error}</p>}
        </div>
    );
}

function StatBox({ val, label, icon }) {
    return (
        <div style={{ display: 'flex', flexDirection: 'column', alignItems: 'center', gap: 2, minWidth: 64 }}>
            <span style={{ fontSize: 22, fontWeight: 800, color: '#fff', lineHeight: 1 }}>{val}</span>
            <span style={{ fontSize: 11, color: 'rgba(255,255,255,0.65)', display: 'flex', alignItems: 'center', gap: 3 }}>
                <i className={`ti ${icon}`} style={{ fontSize: 10 }} />{label}
            </span>
        </div>
    );
}

function InfoCard({ icon, label, val }) {
    return (
        <div style={{ display: 'flex', alignItems: 'center', gap: 12, padding: '12px 16px', background: '#FAFBFC', borderRadius: 12, border: '1.5px solid #F1F5F9' }}>
            <div style={{ width: 36, height: 36, borderRadius: 10, background: `linear-gradient(135deg,${T}18,${T}28)`, display: 'flex', alignItems: 'center', justifyContent: 'center', flexShrink: 0 }}>
                <i className={`ti ${icon}`} style={{ color: T, fontSize: 15 }} />
            </div>
            <div style={{ minWidth: 0 }}>
                <div style={{ fontSize: 10, color: '#94A3B8', fontWeight: 600, marginBottom: 1 }}>{label}</div>
                <div style={{ fontSize: 13, color: '#0F172A', fontWeight: 700, overflow: 'hidden', textOverflow: 'ellipsis', whiteSpace: 'nowrap' }}>{val}</div>
            </div>
        </div>
    );
}

function StatusBadge({ status }) {
    const m = {
        active:   { label: 'نشط',          bg: '#F0FDF4', color: '#15803D', border: '#BBF7D0', icon: 'ti-circle-check' },
        pending:  { label: 'قيد المراجعة', bg: '#FFFBEB', color: '#B45309', border: '#FDE68A', icon: 'ti-clock'        },
        rejected: { label: 'مرفوض',        bg: '#FEF2F2', color: '#DC2626', border: '#FECACA', icon: 'ti-circle-x'    },
        approved: { label: 'مقبول',        bg: '#F0FDF4', color: '#15803D', border: '#BBF7D0', icon: 'ti-circle-check' },
    };
    const s = m[status] ?? { label: status, bg: '#F1F5F9', color: '#64748B', border: '#E2E8F0', icon: 'ti-point' };
    return (
        <span style={{ display: 'inline-flex', alignItems: 'center', gap: 4, fontSize: 11, fontWeight: 700, padding: '4px 11px', borderRadius: 20, background: s.bg, color: s.color, border: `1px solid ${s.border}` }}>
            <i className={`ti ${s.icon}`} style={{ fontSize: 11 }} />{s.label}
        </span>
    );
}

function Avatar({ user, src, size = 80 }) {
    const [err, setErr] = useState(false);
    const initials = [user.first_name?.[0], user.last_name?.[0]].filter(Boolean).join('').toUpperCase();
    const photoSrc = src
        || (user.profile_photo
            ? (user.profile_photo.startsWith('http') ? user.profile_photo : `/storage/${user.profile_photo}`)
            : null);
    return (
        <div style={{
            width: size, height: size, borderRadius: '50%', flexShrink: 0, overflow: 'hidden',
            background: `linear-gradient(135deg,${T},${T2})`,
            display: 'flex', alignItems: 'center', justifyContent: 'center',
            fontSize: size * 0.34, fontWeight: 800, color: '#fff',
        }}>
            {(photoSrc && !err)
                ? <img src={photoSrc} alt="" onError={() => setErr(true)} style={{ width: '100%', height: '100%', objectFit: 'cover' }} />
                : (initials || <i className="ti ti-user" style={{ fontSize: size * 0.42 }} />)
            }
        </div>
    );
}

function BizLogo({ business, size = 80, radius = 18 }) {
    const [err, setErr] = useState(false);
    return (
        <div style={{ width: size, height: size, borderRadius: radius, background: `linear-gradient(135deg,${T},${T2})`, display: 'flex', alignItems: 'center', justifyContent: 'center', fontSize: size * 0.35, fontWeight: 800, color: '#fff', flexShrink: 0, overflow: 'hidden', boxShadow: `0 6px 20px ${T}44` }}>
            {(business.image && !err)
                ? <img src={`/storage/${business.image}`} alt="" onError={() => setErr(true)} style={{ width: '100%', height: '100%', objectFit: 'cover' }} />
                : (business.name?.[0]?.toUpperCase() || <i className="ti ti-building-store" />)
            }
        </div>
    );
}

function GalleryItem({ item, onDelete, onZoom }) {
    const [err, setErr] = useState(false);
    return (
        <div
            style={{ position: 'relative', borderRadius: 16, overflow: 'hidden', aspectRatio: '4/3', border: '1.5px solid #F1F5F9', boxShadow: '0 3px 14px rgba(0,0,0,0.07)', cursor: err ? 'default' : 'zoom-in', background: '#F8FAFC', transition: 'transform .2s, box-shadow .2s' }}
            onMouseEnter={e => { e.currentTarget.style.transform = 'translateY(-3px)'; e.currentTarget.style.boxShadow = '0 10px 28px rgba(0,0,0,0.13)'; }}
            onMouseLeave={e => { e.currentTarget.style.transform = 'translateY(0)'; e.currentTarget.style.boxShadow = '0 3px 14px rgba(0,0,0,0.07)'; }}
            onClick={() => !err && onZoom(`/storage/${item.image}`)}
        >
            {err ? (
                <div style={{ width: '100%', height: '100%', display: 'flex', flexDirection: 'column', alignItems: 'center', justifyContent: 'center', gap: 8, background: 'linear-gradient(135deg,#F8FAFC,#F1F5F9)' }}>
                    <i className="ti ti-photo-off" style={{ fontSize: 32, color: '#CBD5E1' }} />
                    <span style={{ fontSize: 11, color: '#94A3B8', fontWeight: 600 }}>صورة غير متاحة</span>
                </div>
            ) : (
                <img src={`/storage/${item.image}`} alt="" onError={() => setErr(true)} style={{ width: '100%', height: '100%', objectFit: 'cover', display: 'block' }} />
            )}
            <div style={{ position: 'absolute', bottom: 0, inset: 'auto 0 0 0', background: 'linear-gradient(transparent,rgba(0,0,0,0.5))', padding: '28px 10px 10px', display: 'flex', alignItems: 'flex-end', justifyContent: 'space-between' }}>
                {item.date && (
                    <span style={{ fontSize: 11, fontWeight: 600, color: 'rgba(255,255,255,0.85)' }}>
                        {new Date(item.date).toLocaleDateString('ar-SY', { month: 'short', year: 'numeric' })}
                    </span>
                )}
                <button
                    onClick={e => { e.stopPropagation(); if (confirm('حذف هذه الصورة؟')) onDelete(item.id); }}
                    style={{ width: 30, height: 30, borderRadius: '50%', background: 'rgba(239,68,68,0.88)', border: '1.5px solid rgba(255,255,255,0.4)', color: '#fff', cursor: 'pointer', display: 'flex', alignItems: 'center', justifyContent: 'center', backdropFilter: 'blur(4px)' }}>
                    <i className="ti ti-trash" style={{ fontSize: 12 }} />
                </button>
            </div>
        </div>
    );
}

function SectionTitle({ icon, children }) {
    return (
        <div style={{ display: 'flex', alignItems: 'center', gap: 8, marginBottom: 18 }}>
            <div style={{ width: 3, height: 18, borderRadius: 2, background: `linear-gradient(180deg,${T},${T2})` }} />
            <i className={`ti ${icon}`} style={{ color: T, fontSize: 14 }} />
            <span style={{ fontSize: 13.5, fontWeight: 800, color: '#0F172A' }}>{children}</span>
        </div>
    );
}

function Divider({ label }) {
    return (
        <div style={{ display: 'flex', alignItems: 'center', gap: 12, margin: '20px 0' }}>
            <div style={{ flex: 1, height: 1, background: '#F1F5F9' }} />
            {label && <span style={{ fontSize: 11, color: '#94A3B8', fontWeight: 600, whiteSpace: 'nowrap' }}>{label}</span>}
            <div style={{ flex: 1, height: 1, background: '#F1F5F9' }} />
        </div>
    );
}

function VerificationBanner({ verification }) {
    if (!verification) return (
        <div style={{ display: 'flex', alignItems: 'center', justifyContent: 'space-between', gap: 12, padding: '15px 20px', background: 'linear-gradient(135deg,#F0FDFA,#ECFDF5)', border: '1.5px solid #A7F3D0', borderRadius: 14 }}>
            <div style={{ display: 'flex', alignItems: 'center', gap: 14 }}>
                <div style={{ width: 44, height: 44, borderRadius: 13, background: `linear-gradient(135deg,${T},${T2})`, display: 'flex', alignItems: 'center', justifyContent: 'center', boxShadow: `0 4px 14px ${T}33`, flexShrink: 0 }}>
                    <i className="ti ti-id-badge" style={{ fontSize: 20, color: '#fff' }} />
                </div>
                <div>
                    <div style={{ fontSize: 13.5, fontWeight: 700, color: '#0F172A' }}>وثّق هويتك</div>
                    <div style={{ fontSize: 12, color: '#047857', marginTop: 1 }}>زِد ثقة العملاء بحسابك وصداقيتك على المنصة</div>
                </div>
            </div>
            <a href="/user/identity-verification" style={{ padding: '9px 22px', background: `linear-gradient(135deg,${T},${T2})`, color: '#fff', borderRadius: 10, fontSize: 12.5, fontWeight: 700, textDecoration: 'none', whiteSpace: 'nowrap', boxShadow: `0 4px 14px ${T}44`, fontFamily: FONT }}>
                بدء التوثيق
            </a>
        </div>
    );
    if (verification.status === 'approved') return (
        <div style={{ display: 'flex', alignItems: 'center', gap: 14, padding: '15px 20px', background: '#F0FDF4', border: '1.5px solid #BBF7D0', borderRadius: 14 }}>
            <div style={{ width: 44, height: 44, borderRadius: 13, background: '#DCFCE7', display: 'flex', alignItems: 'center', justifyContent: 'center', flexShrink: 0 }}>
                <i className="ti ti-shield-check" style={{ fontSize: 22, color: '#16A34A' }} />
            </div>
            <div>
                <div style={{ fontSize: 13.5, fontWeight: 700, color: '#15803D' }}>هويتك موثّقة</div>
                <div style={{ fontSize: 12, color: '#166534', marginTop: 1 }}>حسابك يظهر بشارة الثقة لجميع المستخدمين</div>
            </div>
        </div>
    );
    if (verification.status === 'pending') return (
        <div style={{ display: 'flex', alignItems: 'center', gap: 14, padding: '15px 20px', background: '#FFFBEB', border: '1.5px solid #FDE68A', borderRadius: 14 }}>
            <div style={{ width: 44, height: 44, borderRadius: 13, background: '#FEF9C3', display: 'flex', alignItems: 'center', justifyContent: 'center', flexShrink: 0 }}>
                <i className="ti ti-clock" style={{ fontSize: 22, color: '#CA8A04' }} />
            </div>
            <div>
                <div style={{ fontSize: 13.5, fontWeight: 700, color: '#A16207' }}>طلب التوثيق قيد المراجعة</div>
                <div style={{ fontSize: 12, color: '#92400E', marginTop: 1 }}>سيتم إشعارك فور مراجعة طلبك</div>
            </div>
        </div>
    );
    if (verification.status === 'rejected') return (
        <div style={{ display: 'flex', alignItems: 'center', justifyContent: 'space-between', gap: 12, padding: '15px 20px', background: '#FEF2F2', border: '1.5px solid #FECACA', borderRadius: 14 }}>
            <div style={{ display: 'flex', alignItems: 'center', gap: 14 }}>
                <div style={{ width: 44, height: 44, borderRadius: 13, background: '#FEE2E2', display: 'flex', alignItems: 'center', justifyContent: 'center', flexShrink: 0 }}>
                    <i className="ti ti-shield-x" style={{ fontSize: 22, color: '#DC2626' }} />
                </div>
                <div>
                    <div style={{ fontSize: 13.5, fontWeight: 700, color: '#B91C1C' }}>تم رفض طلب التوثيق</div>
                    {verification.rejection_reason && <div style={{ fontSize: 12, color: '#991B1B', marginTop: 1 }}>{verification.rejection_reason}</div>}
                </div>
            </div>
            <a href="/user/identity-verification" style={{ padding: '9px 18px', background: '#DC2626', color: '#fff', borderRadius: 10, fontSize: 12, fontWeight: 700, textDecoration: 'none', whiteSpace: 'nowrap', fontFamily: FONT }}>إعادة التقديم</a>
        </div>
    );
    return null;
}

/* ─── Image upload zone — square avatar style ───────────── */
function ImageUploadZone({ preview, inputRef, onChange, onClear }) {
    const [imgErr, setImgErr] = useState(false);
    // reset error when preview changes
    const prevRef = useRef(preview);
    if (prevRef.current !== preview) { prevRef.current = preview; if (imgErr) setImgErr(false); }

    const showImg = preview && !imgErr;
    return (
        <div className="flex flex-col sm:flex-row" style={{ alignItems: 'center', gap: 22 }}>
            {/* Preview square */}
            <div style={{ position: 'relative', width: 180, height: 180, flexShrink: 0 }}>
                {showImg ? (
                    <>
                        <img src={preview} onError={() => setImgErr(true)} style={{ width: 180, height: 180, borderRadius: 20, objectFit: 'cover', display: 'block', border: '2.5px solid #E2E8F0', boxShadow: '0 6px 22px rgba(0,0,0,0.12)' }} />
                        {onClear && (
                            <button type="button" onClick={onClear}
                                style={{ position: 'absolute', top: -10, left: -10, width: 30, height: 30, borderRadius: '50%', background: '#EF4444', border: '2.5px solid #fff', color: '#fff', cursor: 'pointer', display: 'flex', alignItems: 'center', justifyContent: 'center', boxShadow: '0 2px 8px rgba(0,0,0,0.25)' }}>
                                <i className="ti ti-x" style={{ fontSize: 12 }} />
                            </button>
                        )}
                    </>
                ) : (
                    <div style={{ width: 180, height: 180, borderRadius: 20, background: `${T}08`, border: `2px dashed ${T}30`, display: 'flex', flexDirection: 'column', alignItems: 'center', justifyContent: 'center', gap: 8 }}>
                        <i className="ti ti-building-store" style={{ color: T, fontSize: 40 }} />
                        <span style={{ fontSize: 11.5, color: '#94A3B8', fontWeight: 600 }}>{imgErr ? 'اختر صورة جديدة' : 'شعار / صورة العمل'}</span>
                    </div>
                )}
            </div>

            {/* Actions */}
            <div style={{ display: 'flex', flexDirection: 'column', gap: 12 }}>
                <label style={{ display: 'inline-flex', alignItems: 'center', gap: 7, padding: '10px 22px', background: `linear-gradient(135deg,${T},${T2})`, color: '#fff', borderRadius: 11, fontSize: 13, fontWeight: 700, cursor: 'pointer', fontFamily: FONT, boxShadow: `0 4px 12px ${T}44` }}>
                    <i className="ti ti-upload" style={{ fontSize: 14 }} />
                    {preview ? 'تغيير الصورة' : 'اختر صورة'}
                    <input ref={inputRef} type="file" accept="image/*" style={{ display: 'none' }} onChange={onChange} />
                </label>
                <div style={{ fontSize: 12, color: '#94A3B8', lineHeight: 1.7 }}>
                    PNG، JPG — حتى 2 MB<br />
                    <span style={{ color: '#64748B', fontWeight: 600 }}>شعار أو صورة نشاطك التجاري</span>
                </div>
            </div>
        </div>
    );
}

/* ─── Focusable input/select helper ─────────────────────── */
function FInput({ style: extraStyle, ...props }) {
    const [focused, setFocused] = useState(false);
    return (
        <input
            {...props}
            style={{
                ...INPUT_BASE,
                borderColor: focused ? T : '#E2E8F0',
                boxShadow: focused ? `0 0 0 3px ${T}18` : 'none',
                ...extraStyle,
            }}
            onFocus={() => setFocused(true)}
            onBlur={() => setFocused(false)}
        />
    );
}

function FSelect({ style: extraStyle, children, ...props }) {
    const [focused, setFocused] = useState(false);
    return (
        <select
            {...props}
            style={{
                ...INPUT_BASE,
                borderColor: focused ? T : '#E2E8F0',
                boxShadow: focused ? `0 0 0 3px ${T}18` : 'none',
                ...extraStyle,
            }}
            onFocus={() => setFocused(true)}
            onBlur={() => setFocused(false)}
        >
            {children}
        </select>
    );
}

function FTextarea({ style: extraStyle, ...props }) {
    const [focused, setFocused] = useState(false);
    return (
        <textarea
            {...props}
            style={{
                ...INPUT_BASE,
                borderColor: focused ? T : '#E2E8F0',
                boxShadow: focused ? `0 0 0 3px ${T}18` : 'none',
                resize: 'vertical', minHeight: 90,
                ...extraStyle,
            }}
            onFocus={() => setFocused(true)}
            onBlur={() => setFocused(false)}
        />
    );
}

/* ─── Btn ──────────────────────────────────────────────────── */
function Btn({ children, danger = false, sm = false, ...rest }) {
    return (
        <button {...rest} style={{
            display: 'inline-flex', alignItems: 'center', gap: 7,
            background: danger ? '#DC2626' : `linear-gradient(135deg,${T},${T2})`,
            color: '#fff', border: 'none', cursor: rest.disabled ? 'not-allowed' : 'pointer',
            padding: sm ? '8px 18px' : '11px 26px', borderRadius: 10,
            fontSize: sm ? 12.5 : 13.5, fontWeight: 700, fontFamily: FONT,
            boxShadow: `0 4px 14px ${danger ? '#DC262644' : T + '44'}`,
            opacity: rest.disabled ? 0.65 : 1,
            transition: 'opacity .15s, transform .1s',
            ...rest.style,
        }}>{children}</button>
    );
}

/* ─── Panel shell ───────────────────────────────────────────── */
function Panel({ children, noPad = false }) {
    return (
        <div style={{ background: '#fff', border: '1.5px solid #F1F5F9', borderRadius: 20, overflow: 'hidden', boxShadow: '0 2px 20px rgba(0,0,0,0.05)' }}>
            {noPad ? children : <div style={{ padding: 28 }}>{children}</div>}
        </div>
    );
}

function PanelHead({ icon, title, sub, action }) {
    return (
        <div style={{ padding: '18px 24px', borderBottom: '1.5px solid #F1F5F9', display: 'flex', alignItems: 'center', justifyContent: 'space-between', gap: 12, background: 'linear-gradient(135deg,#F8FFFE,#F0FDF8)' }}>
            <div style={{ display: 'flex', alignItems: 'center', gap: 12 }}>
                <div style={{ width: 38, height: 38, borderRadius: 11, background: `linear-gradient(135deg,${T},${T2})`, display: 'flex', alignItems: 'center', justifyContent: 'center', boxShadow: `0 4px 12px ${T}44`, flexShrink: 0 }}>
                    <i className={`ti ${icon}`} style={{ color: '#fff', fontSize: 16 }} />
                </div>
                <div>
                    <div style={{ fontSize: 14.5, fontWeight: 800, color: '#0F172A' }}>{title}</div>
                    {sub && <div style={{ fontSize: 12, color: '#64748B', marginTop: 1 }}>{sub}</div>}
                </div>
            </div>
            {action}
        </div>
    );
}

/* ═══════════════════════════════════════════════════════════ */
export default function Profile({ user, business, gallery, userServices, activeTypes, categories, subcategories, cities, flash, verification }) {

    const [tab, setTab]               = useState('profile');
    const [imgPreview, setImgPreview]         = useState(business?.image ? `/storage/${business.image}` : null);
    const [editImgPreview, setEditImgPreview] = useState(business?.image ? `/storage/${business.image}` : null);
    const [galleryUploading, setGalleryUploading]     = useState(false);
    const [pendingFiles, setPendingFiles]             = useState([]);
    const [createGalleryFiles, setCreateGalleryFiles] = useState([]);
    const [lightbox, setLightbox]                     = useState(null);
    const [bizDot, setBizDot]                         = useState(false);

    const { badges } = usePage().props;
    const unreadBiz = badges?.unread_notifications ?? 0;

    const imgRef     = useRef();
    const eImgRef    = useRef();

    /* ── forms ── */
    const [profilePhotoPreview, setProfilePhotoPreview] = useState(
        user.profile_photo ? (user.profile_photo.startsWith('http') ? user.profile_photo : `/storage/${user.profile_photo}`) : null
    );
    const profilePhotoRef = useRef();

    const profileForm = useForm({
        first_name:    user.first_name  ?? '',
        middle_name:   user.middle_name ?? '',
        last_name:     user.last_name   ?? '',
        phone:         user.phone       ?? '',
        city:          user.city        ?? '',
        gender:        user.gender      ?? '',
        birthdate:     user.birthdate   ?? '',
        profile_photo: null,
    });

    const bizForm = useForm({
        name_job: '', number: user.phone ?? '',
        active_typebusiness_id: '',
        description: '',
        city: user.city ?? '', area: '', street: '',
        image: null,
    });

    const editBizForm = useForm({
        name_job:    business?.name_job    ?? '',
        number:      business?.number      ?? '',
        description: business?.description ?? '',
        city:        business?.city        ?? user.city ?? '',
        area:        business?.area        ?? '',
        street:      business?.street      ?? '',
        image:       null,
    });

    // Poll for notification badge every 6 seconds — skipped while a form is submitting so a
    // slow request (e.g. the AI image check) doesn't race the poll for the flash-session message
    useEffect(() => {
        const id = setInterval(() => {
            if (profileForm.processing || bizForm.processing || editBizForm.processing) return;
            router.reload({ only: ['badges'], preserveScroll: true, preserveState: true });
        }, 6000);
        return () => clearInterval(id);
    }, [profileForm.processing, bizForm.processing, editBizForm.processing]);

    const submitProfile = e => { e.preventDefault(); profileForm.put('/user/profile', { forceFormData: true, preserveScroll: true, onSuccess: () => profileForm.setData('profile_photo', null) }); };
    const submitBiz = e => {
        e.preventDefault();
        bizForm.transform(data => ({ ...data, gallery_images: createGalleryFiles.map(f => f.file) }));
        bizForm.post('/user/business', { forceFormData: true, preserveScroll: true, onSuccess: () => setCreateGalleryFiles([]) });
    };
    const submitEditBiz  = e => { e.preventDefault(); editBizForm.put('/user/business', { forceFormData: true, preserveScroll: true }); };

    const addCreateGalleryFiles = (files) => {
        if (!files?.length) return;
        const items = Array.from(files).map(f => ({
            id: Math.random().toString(36).slice(2), file: f, preview: URL.createObjectURL(f), size: f.size,
        }));
        setCreateGalleryFiles(prev => [...prev, ...items]);
    };
    const removeCreateGalleryFile = (id) => setCreateGalleryFiles(prev => prev.filter(f => f.id !== id));

    const addPendingFiles = (files) => {
        if (!files?.length) return;
        const items = Array.from(files).map(f => ({
            id:      Math.random().toString(36).slice(2),
            file:    f,
            preview: URL.createObjectURL(f),
            name:    f.name,
            size:    f.size,
        }));
        setPendingFiles(prev => [...prev, ...items]);
    };

    const removePending = (id) => setPendingFiles(prev => prev.filter(f => f.id !== id));

    const uploadPending = () => {
        if (!pendingFiles.length || galleryUploading) return;
        setGalleryUploading(true);
        router.post('/user/business/gallery',
            { images: pendingFiles.map(f => f.file) },
            {
                forceFormData: true,
                preserveScroll: true,
                onSuccess: () => setPendingFiles([]),
                onFinish:  () => setGalleryUploading(false),
            }
        );
    };

    const deleteGalleryItem = id => router.delete(`/user/business/gallery/${id}`, { preserveScroll: true });

    /* ── derived ── */
    const svcCount     = userServices?.length ?? 0;
    const galleryCount = gallery?.length ?? 0;
    const fullName     = [user.first_name, user.middle_name, user.last_name].filter(Boolean).join(' ');
    const joinDate     = user.created_at ? new Date(user.created_at).toLocaleDateString('ar-SY', { year: 'numeric', month: 'long' }) : '—';
    const genderMap    = { male: 'ذكر', female: 'أنثى' };

    const TABS = [
        { key: 'profile',  icon: 'ti-user',     label: 'ملفي الشخصي' },
        { key: 'business', icon: 'ti-briefcase', label: 'حساب العمل', badge: business?.status, dot: bizDot || unreadBiz > 0 },
        { key: 'services', icon: 'ti-tool',      label: 'خدماتي', badge: svcCount || null },
    ];

    /* ════════════════════════════════════════════════════════ RENDER */
    return (
        <UserLayout title="الملف الشخصي">
            <Head title="حسابي — Skillify" />

            <div style={{ display: 'flex', flexDirection: 'column', gap: 20 }}>

                {/* flash */}
                {flash?.success && (
                    <div style={{ background: 'linear-gradient(135deg,#F0FDF4,#ECFDF5)', border: '1.5px solid #9FE1CB', borderRadius: 13, padding: '13px 20px', color: '#134E4A', fontSize: 13, display: 'flex', alignItems: 'center', gap: 10, boxShadow: '0 2px 10px #0D948818' }}>
                        <div style={{ width: 30, height: 30, borderRadius: 8, background: '#16A34A22', display: 'flex', alignItems: 'center', justifyContent: 'center', flexShrink: 0 }}>
                            <i className="ti ti-circle-check" style={{ fontSize: 16, color: '#16A34A' }} />
                        </div>
                        <span style={{ fontWeight: 600 }}>{flash.success}</span>
                    </div>
                )}

                {/* ══ HERO CARD ══════════════════════════════════════════════ */}
                <div style={{ background: '#fff', border: '1.5px solid #F1F5F9', borderRadius: 22, overflow: 'hidden', boxShadow: '0 4px 24px rgba(0,0,0,0.07)' }}>

                    {/* ── Cover strip ── */}
                    <div style={{ height: 155, background: `linear-gradient(135deg,#0D9488 0%,#134E4A 55%,#052e16 100%)`, position: 'relative', overflow: 'hidden' }}>
                        {/* business image blurred as cover background */}
                        {editImgPreview && <img src={editImgPreview} alt="" style={{ position: 'absolute', inset: 0, width: '100%', height: '100%', objectFit: 'cover', opacity: 0.18, filter: 'blur(10px)', transform: 'scale(1.12)', pointerEvents: 'none' }} onError={() => {}} />}
                        {/* grid mesh */}
                        <div style={{ position: 'absolute', inset: 0, backgroundImage: 'linear-gradient(rgba(255,255,255,0.04) 1px,transparent 1px),linear-gradient(90deg,rgba(255,255,255,0.04) 1px,transparent 1px)', backgroundSize: '32px 32px' }} />
                        {/* glow blob */}
                        <div style={{ position: 'absolute', top: -40, right: 60, width: 160, height: 160, borderRadius: '50%', background: 'rgba(255,255,255,0.06)', filter: 'blur(34px)', pointerEvents: 'none' }} />
                        {/* join date */}
                        <div style={{ position: 'absolute', top: 16, left: 20, fontSize: 11.5, color: 'rgba(255,255,255,0.5)', display: 'flex', alignItems: 'center', gap: 5, fontFamily: FONT }}>
                            <i className="ti ti-calendar-event" style={{ fontSize: 11 }} /> عضو منذ {joinDate}
                        </div>
                        {/* business status badge top-right */}
                        {business && (
                            <div style={{ position: 'absolute', top: 14, right: 18 }}>
                                <StatusBadge status={business.status} />
                            </div>
                        )}
                    </div>

                    {/* ── White section ── */}
                    <div style={{ padding: '0 26px 22px', position: 'relative' }}>

                        {/* Avatar — overlaps cover, absolutely positioned */}
                        <div style={{ position: 'relative', display: 'inline-block', marginTop: -52, marginBottom: 12 }}>
                            <div style={{ border: '4.5px solid #fff', borderRadius: '50%', boxShadow: '0 6px 24px rgba(0,0,0,0.18)', display: 'inline-block' }}>
                                <Avatar user={user} src={business ? (editImgPreview || null) : null} size={104} />
                            </div>
                            {/* Verification dot on avatar */}
                            {verification?.status === 'approved' ? (
                                <div title="موثّق" style={{ position: 'absolute', bottom: 6, right: 4, width: 28, height: 28, borderRadius: '50%', background: 'linear-gradient(135deg,#16A34A,#15803D)', border: '3px solid #fff', display: 'flex', alignItems: 'center', justifyContent: 'center', boxShadow: '0 3px 10px rgba(22,163,74,0.5)', cursor: 'default' }}>
                                    <i className="ti ti-shield-check" style={{ fontSize: 12, color: '#fff' }} />
                                </div>
                            ) : verification?.status === 'pending' ? (
                                <div title="التوثيق قيد المراجعة" style={{ position: 'absolute', bottom: 6, right: 4, width: 28, height: 28, borderRadius: '50%', background: 'linear-gradient(135deg,#D97706,#B45309)', border: '3px solid #fff', display: 'flex', alignItems: 'center', justifyContent: 'center', boxShadow: '0 3px 10px rgba(217,119,6,0.45)', cursor: 'default' }}>
                                    <i className="ti ti-clock" style={{ fontSize: 12, color: '#fff' }} />
                                </div>
                            ) : (
                                <div title="غير موثّق" style={{ position: 'absolute', bottom: 6, right: 4, width: 28, height: 28, borderRadius: '50%', background: 'linear-gradient(135deg,#94A3B8,#64748B)', border: '3px solid #fff', display: 'flex', alignItems: 'center', justifyContent: 'center', boxShadow: '0 3px 8px rgba(100,116,139,0.35)', cursor: 'default' }}>
                                    <i className="ti ti-shield-x" style={{ fontSize: 12, color: '#fff' }} />
                                </div>
                            )}
                        </div>

                        {/* Name row */}
                        <div style={{ display: 'flex', alignItems: 'flex-start', justifyContent: 'space-between', gap: 12, flexWrap: 'wrap', marginBottom: 6 }}>
                            <div>
                                <div style={{ display: 'flex', alignItems: 'center', gap: 10, flexWrap: 'wrap', marginBottom: 4 }}>
                                    <span style={{ fontSize: 22, fontWeight: 800, color: '#0F172A', lineHeight: 1.2 }}>{fullName}</span>
                                    {verification?.status === 'approved' ? (
                                        <span style={{ display: 'inline-flex', alignItems: 'center', gap: 5, fontSize: 12, fontWeight: 700, padding: '4px 12px', borderRadius: 20, background: 'linear-gradient(135deg,#16A34A,#15803D)', color: '#fff', boxShadow: '0 3px 10px rgba(22,163,74,0.35)', letterSpacing: 0.2 }}>
                                            <i className="ti ti-shield-check" style={{ fontSize: 13 }} /> موثّق
                                        </span>
                                    ) : verification?.status === 'pending' ? (
                                        <span style={{ display: 'inline-flex', alignItems: 'center', gap: 5, fontSize: 12, fontWeight: 700, padding: '4px 12px', borderRadius: 20, background: 'linear-gradient(135deg,#F59E0B,#D97706)', color: '#fff', boxShadow: '0 3px 10px rgba(245,158,11,0.35)', letterSpacing: 0.2 }}>
                                            <i className="ti ti-clock" style={{ fontSize: 13 }} /> قيد التحقق
                                        </span>
                                    ) : (
                                        <span style={{ display: 'inline-flex', alignItems: 'center', gap: 5, fontSize: 12, fontWeight: 700, padding: '4px 12px', borderRadius: 20, background: '#F1F5F9', color: '#64748B', border: '1px solid #E2E8F0', letterSpacing: 0.2 }}>
                                            <i className="ti ti-shield-x" style={{ fontSize: 13 }} /> غير موثّق
                                        </span>
                                    )}
                                </div>
                                {business?.name_job && (
                                    <div style={{ fontSize: 13, color: T, fontWeight: 600, marginBottom: 8, display: 'flex', alignItems: 'center', gap: 5 }}>
                                        <i className="ti ti-briefcase" style={{ fontSize: 12 }} />{business.name_job}
                                    </div>
                                )}
                                <div style={{ display: 'flex', alignItems: 'center', gap: 16, flexWrap: 'wrap' }}>
                                    {user.email && <span style={{ fontSize: 12.5, color: '#64748B', display: 'flex', alignItems: 'center', gap: 4 }}><i className="ti ti-mail" style={{ fontSize: 12, color: T }} />{user.email}</span>}
                                    {user.city  && <span style={{ fontSize: 12.5, color: '#64748B', display: 'flex', alignItems: 'center', gap: 4 }}><i className="ti ti-map-pin" style={{ fontSize: 12, color: T }} />{user.city}</span>}
                                    {user.phone && <span style={{ fontSize: 12.5, color: '#64748B', display: 'flex', alignItems: 'center', gap: 4 }}><i className="ti ti-phone" style={{ fontSize: 12, color: T }} />{user.phone}</span>}
                                </div>
                            </div>

                            {/* Stats chips — top-right of white area */}
                            <div style={{ display: 'flex', gap: 10, flexWrap: 'wrap', paddingTop: 4 }}>
                                {[
                                    { val: svcCount,           label: 'خدمة',      icon: 'ti-tool' },
                                    { val: galleryCount,       label: 'معرض',      icon: 'ti-photo' },
                                    { val: business ? 1 : 0,  label: 'حساب عمل', icon: 'ti-briefcase' },
                                ].map(s => (
                                    <div key={s.label} style={{ display: 'flex', flexDirection: 'column', alignItems: 'center', padding: '8px 16px', background: '#F8FFFE', border: `1.5px solid ${T}18`, borderRadius: 12, minWidth: 62 }}>
                                        <span style={{ fontSize: 20, fontWeight: 800, color: T, lineHeight: 1 }}>{s.val}</span>
                                        <span style={{ fontSize: 10.5, color: '#64748B', marginTop: 3, display: 'flex', alignItems: 'center', gap: 3, whiteSpace: 'nowrap' }}>
                                            <i className={`ti ${s.icon}`} style={{ fontSize: 10 }} />{s.label}
                                        </span>
                                    </div>
                                ))}
                            </div>
                        </div>
                    </div>
                </div>

                {/* ══ VERIFICATION BANNER ══════════════════════════════════ */}
                <VerificationBanner verification={verification} />

                {/* ══ TAB BAR ══════════════════════════════════════════════ */}
                <div style={{ background: '#fff', border: '1.5px solid #F1F5F9', borderRadius: 16, padding: 5, boxShadow: '0 2px 10px rgba(0,0,0,0.04)', display: 'flex', gap: 3 }}>
                    {TABS.map(({ key, icon, label, badge, dot }) => {
                        const active = tab === key;
                        return (
                            <button key={key} onClick={() => { setTab(key); if (key === 'business') setBizDot(false); }} style={{
                                flex: 1, display: 'flex', alignItems: 'center', justifyContent: 'center', gap: 7,
                                padding: '10px 16px', borderRadius: 12, position: 'relative',
                                fontSize: 13, fontWeight: active ? 700 : 600, border: 'none',
                                cursor: 'pointer', fontFamily: FONT, whiteSpace: 'nowrap',
                                background: active ? `linear-gradient(135deg,${T},${T2})` : 'transparent',
                                color: active ? '#fff' : '#64748B',
                                boxShadow: active ? `0 3px 12px ${T}44` : 'none',
                                transition: 'all .2s',
                            }}>
                                <i className={`ti ${icon}`} style={{ fontSize: 14 }} />
                                {label}
                                {dot && !active && (
                                    <span style={{
                                        position: 'absolute', top: 6, right: 6,
                                        width: 8, height: 8, borderRadius: '50%',
                                        background: '#EF4444', border: '1.5px solid #fff',
                                    }} />
                                )}
                                {badge != null && (
                                    <span style={{
                                        fontSize: 10, fontWeight: 700, padding: '2px 7px', borderRadius: 20, lineHeight: 1.5,
                                        background: active ? 'rgba(255,255,255,0.22)' : '#F1F5F9',
                                        color: active ? '#fff' : '#94A3B8',
                                    }}>{badge}</span>
                                )}
                            </button>
                        );
                    })}
                </div>

                {/* ══════════════════════ PROFILE TAB ═══════════════════════ */}
                {tab === 'profile' && (
                    <Panel noPad>
                        <PanelHead icon="ti-user-edit" title="المعلومات الشخصية" sub="بياناتك الأساسية المسجّلة على المنصة" />
                        <div style={{ padding: 28 }}>

                            {/* Info chips */}
                            <div style={{ display: 'grid', gridTemplateColumns: 'repeat(auto-fill,minmax(170px,1fr))', gap: 10, marginBottom: 30 }}>
                                <InfoCard icon="ti-phone"           label="الهاتف"         val={user.phone || '—'} />
                                <InfoCard icon="ti-mail"            label="البريد"         val={user.email || '—'} />
                                <InfoCard icon="ti-map-pin"         label="المدينة"        val={user.city  || '—'} />
                                <InfoCard icon="ti-gender-bigender" label="الجنس"          val={genderMap[user.gender] || '—'} />
                                <InfoCard icon="ti-cake"            label="تاريخ الميلاد"  val={user.birthdate ? new Date(user.birthdate).toLocaleDateString('ar-SY') : '—'} />
                                <InfoCard icon="ti-calendar"        label="تاريخ الانضمام" val={joinDate} />
                            </div>

                            <Divider label="تعديل البيانات" />

                            <form onSubmit={submitProfile}>

                                {/* ── Profile Photo Upload — hidden when user has a business (logo takes over) ── */}
                                {!business && <div style={{ display: 'flex', alignItems: 'center', gap: 20, padding: '18px 20px', background: '#F8FFFE', borderRadius: 14, border: `1.5px solid ${T}18`, marginBottom: 22 }}>
                                    {/* Current avatar preview */}
                                    <div style={{ position: 'relative', flexShrink: 0 }}>
                                        <div style={{ width: 72, height: 72, borderRadius: '50%', overflow: 'hidden', border: `3px solid ${T}44`, boxShadow: `0 4px 14px ${T}22` }}>
                                            {profilePhotoPreview ? (
                                                <img src={profilePhotoPreview} alt="صورتك" style={{ width: '100%', height: '100%', objectFit: 'cover' }} />
                                            ) : (
                                                <div style={{ width: '100%', height: '100%', background: `linear-gradient(135deg,${T},${T2})`, display: 'flex', alignItems: 'center', justifyContent: 'center', fontSize: 24, fontWeight: 800, color: '#fff' }}>
                                                    {[user.first_name?.[0], user.last_name?.[0]].filter(Boolean).join('').toUpperCase() || <i className="ti ti-user" />}
                                                </div>
                                            )}
                                        </div>
                                        {profilePhotoPreview && (
                                            <button type="button"
                                                onClick={() => { setProfilePhotoPreview(null); profileForm.setData('profile_photo', null); }}
                                                style={{ position: 'absolute', top: -4, left: -4, width: 22, height: 22, borderRadius: '50%', background: '#EF4444', border: '2px solid #fff', color: '#fff', cursor: 'pointer', display: 'flex', alignItems: 'center', justifyContent: 'center', boxShadow: '0 2px 6px rgba(0,0,0,0.2)' }}>
                                                <i className="ti ti-x" style={{ fontSize: 10 }} />
                                            </button>
                                        )}
                                    </div>
                                    <div style={{ flex: 1 }}>
                                        <div style={{ fontSize: 13.5, fontWeight: 700, color: '#0F172A', marginBottom: 3 }}>صورتك الشخصية</div>
                                        <div style={{ fontSize: 12, color: '#64748B', marginBottom: 10 }}>تظهر بجانب اسمك في المنصة — اختيارية</div>
                                        <label style={{ display: 'inline-flex', alignItems: 'center', gap: 6, padding: '7px 18px', background: `linear-gradient(135deg,${T},${T2})`, color: '#fff', borderRadius: 9, fontSize: 12.5, fontWeight: 700, cursor: 'pointer', fontFamily: FONT, boxShadow: `0 3px 10px ${T}33` }}>
                                            <i className="ti ti-upload" style={{ fontSize: 13 }} />
                                            {profilePhotoPreview ? 'تغيير الصورة' : 'رفع صورة'}
                                            <input ref={profilePhotoRef} type="file" accept="image/*" style={{ display: 'none' }}
                                                onChange={e => {
                                                    const f = e.target.files?.[0];
                                                    if (!f) return;
                                                    profileForm.setData('profile_photo', f);
                                                    setProfilePhotoPreview(URL.createObjectURL(f));
                                                }} />
                                        </label>
                                    </div>
                                </div>}

                                <div className="grid grid-cols-1 sm:grid-cols-3" style={{ gap: 16, marginBottom: 18 }}>
                                    <Field label="الاسم الأول" icon="ti-user" error={profileForm.errors.first_name}>
                                        <FInput value={profileForm.data.first_name} onChange={e => profileForm.setData('first_name', e.target.value)} required />
                                    </Field>
                                    <Field label="الاسم الأوسط" icon="ti-user" error={profileForm.errors.middle_name}>
                                        <FInput value={profileForm.data.middle_name} onChange={e => profileForm.setData('middle_name', e.target.value)} placeholder="اختياري" />
                                    </Field>
                                    <Field label="الاسم الأخير" icon="ti-user" error={profileForm.errors.last_name}>
                                        <FInput value={profileForm.data.last_name} onChange={e => profileForm.setData('last_name', e.target.value)} required />
                                    </Field>
                                </div>

                                <div className="grid grid-cols-1 sm:grid-cols-2" style={{ gap: 16, marginBottom: 18 }}>
                                    <Field label="رقم الهاتف" icon="ti-phone" error={profileForm.errors.phone}>
                                        <FInput value={profileForm.data.phone} onChange={e => profileForm.setData('phone', e.target.value)} required />
                                    </Field>
                                    <Field label="المدينة" icon="ti-map-pin" error={profileForm.errors.city}>
                                        <FSelect value={profileForm.data.city} onChange={e => profileForm.setData('city', e.target.value)} required>
                                            <option value="">— اختر مدينة —</option>
                                            {(cities ?? []).map(c => <option key={c.id} value={c.name}>{c.name}</option>)}
                                        </FSelect>
                                    </Field>
                                    <Field label="الجنس" icon="ti-gender-bigender" error={profileForm.errors.gender}>
                                        <FSelect value={profileForm.data.gender} onChange={e => profileForm.setData('gender', e.target.value)} required>
                                            <option value="">— اختر —</option>
                                            <option value="male">ذكر</option>
                                            <option value="female">أنثى</option>
                                        </FSelect>
                                    </Field>
                                    <Field label="تاريخ الميلاد" icon="ti-cake" error={profileForm.errors.birthdate}>
                                        <FInput type="date" value={profileForm.data.birthdate} onChange={e => profileForm.setData('birthdate', e.target.value)} />
                                    </Field>
                                </div>

                                {/* Read-only email */}
                                <div style={{ padding: '12px 16px', background: '#F8FAFC', borderRadius: 10, border: '1.5px solid #F1F5F9', display: 'flex', alignItems: 'center', gap: 12, marginBottom: 24 }}>
                                    <i className="ti ti-mail" style={{ color: '#94A3B8', fontSize: 16 }} />
                                    <div>
                                        <div style={{ fontSize: 10.5, color: '#94A3B8', fontWeight: 600 }}>البريد الإلكتروني — غير قابل للتعديل</div>
                                        <div style={{ fontSize: 13.5, color: '#475569', fontWeight: 600, marginTop: 1 }}>{user.email || '—'}</div>
                                    </div>
                                </div>

                                <div style={{ display: 'flex', justifyContent: 'flex-end' }}>
                                    <Btn type="submit" disabled={profileForm.processing}>
                                        <i className="ti ti-device-floppy" style={{ fontSize: 15 }} />
                                        {profileForm.processing ? 'جارٍ الحفظ...' : 'حفظ التغييرات'}
                                    </Btn>
                                </div>
                            </form>
                        </div>
                    </Panel>
                )}

                {/* ══════════════════════ BUSINESS TAB ══════════════════════ */}
                {tab === 'business' && (
                    <Panel noPad>
                        <PanelHead icon="ti-briefcase" title="حساب العمل" sub={business ? 'معلومات نشاطك التجاري على المنصة' : 'أنشئ حساب عملك لعرض خدماتك'} />
                        <div style={{ padding: 28 }}>

                            {business ? (
                                <div style={{ display: 'flex', flexDirection: 'column', gap: 24 }}>
                                    {/* Business hero banner */}
                                    <div style={{ borderRadius: 16, overflow: 'hidden', border: `1.5px solid ${T}18` }}>
                                        {/* coloured top bar */}
                                        <div style={{ height: 6, background: `linear-gradient(90deg,${T},${T2})` }} />
                                        <div style={{ padding: '20px 22px', background: 'linear-gradient(135deg,#F8FFFE,#F0FDF8)', display: 'flex', alignItems: 'center', gap: 20 }}>
                                            <BizLogo business={business} />
                                            <div style={{ flex: 1 }}>
                                                <div style={{ display: 'flex', alignItems: 'center', gap: 10, flexWrap: 'wrap', marginBottom: 6 }}>
                                                    <span style={{ fontSize: 18, fontWeight: 800, color: '#0F172A' }}>{business.name}</span>
                                                    <StatusBadge status={business.status} />
                                                </div>
                                                {business.name_job && <div style={{ fontSize: 13, color: T, fontWeight: 600, marginBottom: 6, display: 'flex', alignItems: 'center', gap: 5 }}><i className="ti ti-briefcase" style={{ fontSize: 12 }} />{business.name_job}</div>}
                                                {business.description && <p style={{ fontSize: 13, color: '#475569', lineHeight: 1.7, margin: 0 }}>{business.description}</p>}
                                            </div>
                                        </div>
                                    </div>

                                    {/* Rejected banner + resubmit */}
                                    {business.status === 'rejected' && (
                                        <div style={{ display: 'flex', alignItems: 'center', justifyContent: 'space-between', gap: 16, padding: '16px 20px', background: '#FEF2F2', border: '1.5px solid #FECACA', borderRadius: 14 }}>
                                            <div style={{ display: 'flex', alignItems: 'center', gap: 12 }}>
                                                <div style={{ width: 38, height: 38, borderRadius: 10, background: '#FEE2E2', display: 'flex', alignItems: 'center', justifyContent: 'center', flexShrink: 0 }}>
                                                    <i className="ti ti-circle-x" style={{ color: '#DC2626', fontSize: 18 }} />
                                                </div>
                                                <div>
                                                    <div style={{ fontSize: 13.5, fontWeight: 700, color: '#991B1B', marginBottom: 2 }}>تم رفض طلبك</div>
                                                    <div style={{ fontSize: 12, color: '#B91C1C' }}>عدّل معلوماتك أدناه ثم أعد إرسال الطلب للمراجعة</div>
                                                </div>
                                            </div>
                                            <button
                                                type="button"
                                                onClick={() => router.patch('/user/business/resubmit', {}, { preserveScroll: true })}
                                                style={{ display: 'inline-flex', alignItems: 'center', gap: 7, padding: '10px 22px', background: 'linear-gradient(135deg,#DC2626,#B91C1C)', color: '#fff', border: 'none', borderRadius: 10, fontSize: 13, fontWeight: 700, cursor: 'pointer', fontFamily: FONT, boxShadow: '0 4px 12px rgba(220,38,38,0.35)', flexShrink: 0 }}
                                            >
                                                <i className="ti ti-send" style={{ fontSize: 14 }} />
                                                إعادة الإرسال
                                            </button>
                                        </div>
                                    )}

                                    {/* Details grid */}
                                    <div style={{ display: 'grid', gridTemplateColumns: 'repeat(auto-fill,minmax(180px,1fr))', gap: 10 }}>
                                        <InfoCard icon="ti-phone"              label="هاتف العمل"     val={business.number || '—'} />
                                        <InfoCard icon="ti-building-community" label="المدينة"        val={business.city || user.city || '—'} />
                                        <InfoCard icon="ti-map"                label="المنطقة / الحي" val={business.area || '—'} />
                                        <InfoCard icon="ti-road"               label="الشارع"         val={business.street || '—'} />
                                        <InfoCard icon="ti-calendar"           label="تاريخ الإنشاء"  val={business.created_at ? new Date(business.created_at).toLocaleDateString('ar-SY') : '—'} />
                                        <InfoCard icon="ti-file-certificate"   label="حالة الحساب"    val={business.status === 'active' ? 'مفعّل' : business.status === 'pending' ? 'قيد المراجعة' : 'مرفوض'} />
                                    </div>

                                    {/* Edit form */}
                                    <div style={{ background: '#FAFBFC', borderRadius: 14, border: '1.5px solid #F1F5F9', padding: '22px 20px' }}>
                                        <SectionTitle icon="ti-pencil">تعديل معلومات العمل</SectionTitle>
                                        <form onSubmit={submitEditBiz} encType="multipart/form-data">
                                            <div className="grid grid-cols-1 sm:grid-cols-2" style={{ gap: 16, marginBottom: 16 }}>
                                                <Field label="المسمى الوظيفي" icon="ti-briefcase" error={editBizForm.errors.name_job}>
                                                    <FInput value={editBizForm.data.name_job} onChange={e => editBizForm.setData('name_job', e.target.value)} required placeholder="مثال: نجّار محترف" />
                                                </Field>
                                                <Field label="رقم الهاتف" icon="ti-phone" error={editBizForm.errors.number}>
                                                    <FInput value={editBizForm.data.number} onChange={e => editBizForm.setData('number', e.target.value)} required />
                                                </Field>
                                            </div>
                                            <Field label="وصف النشاط" icon="ti-align-left" error={editBizForm.errors.description}>
                                                <FTextarea style={{ marginBottom: 16 }} value={editBizForm.data.description} onChange={e => editBizForm.setData('description', e.target.value)} placeholder="اكتب وصفاً مختصراً عن خدماتك وخبراتك..." />
                                            </Field>
                                            <div className="grid grid-cols-1 sm:grid-cols-3" style={{ gap: 14, marginBottom: 16 }}>
                                                <Field label="المدينة" icon="ti-building-community" error={editBizForm.errors.city}>
                                                    <FSelect value={editBizForm.data.city} onChange={e => editBizForm.setData('city', e.target.value)}>
                                                        <option value="">— اختر مدينة —</option>
                                                        {(cities ?? []).map(c => <option key={c.id} value={c.name}>{c.name}</option>)}
                                                    </FSelect>
                                                </Field>
                                                <Field label="المنطقة / الحي" icon="ti-map" error={editBizForm.errors.area}>
                                                    <FInput value={editBizForm.data.area} onChange={e => editBizForm.setData('area', e.target.value)} placeholder="مثال: المزة..." />
                                                </Field>
                                                <Field label="اسم الشارع" icon="ti-road" error={editBizForm.errors.street}>
                                                    <FInput value={editBizForm.data.street} onChange={e => editBizForm.setData('street', e.target.value)} placeholder="مثال: شارع الجلاء..." />
                                                </Field>
                                            </div>
                                            <Field label="صورة الحساب" icon="ti-photo" error={editBizForm.errors.image}>
                                                <div style={{ marginTop: 4, marginBottom: 20 }}>
                                                    <ImageUploadZone
                                                        preview={editImgPreview}
                                                        inputRef={eImgRef}
                                                        onChange={e => { const f = e.target.files?.[0]; if (f) { editBizForm.setData('image', f); setEditImgPreview(URL.createObjectURL(f)); } }}
                                                        onClear={() => { editBizForm.setData('image', null); setEditImgPreview(null); }}
                                                    />
                                                </div>
                                            </Field>
                                            <div style={{ display: 'flex', justifyContent: 'flex-end' }}>
                                                <Btn type="submit" disabled={editBizForm.processing}>
                                                    <i className="ti ti-device-floppy" style={{ fontSize: 15 }} />
                                                    {editBizForm.processing ? 'جارٍ الحفظ...' : 'حفظ التعديلات'}
                                                </Btn>
                                            </div>
                                        </form>
                                    </div>

                                    {/* ══ GALLERY inside business tab ══════════════════════ */}
                                    <div style={{ marginTop: 28, paddingTop: 28, borderTop: '1.5px solid #F1F5F9' }}>
                                    <div style={{ display: 'flex', alignItems: 'center', justifyContent: 'space-between', marginBottom: 20 }}>
                                        <div style={{ display: 'flex', alignItems: 'center', gap: 12 }}>
                                            <div style={{ width: 42, height: 42, borderRadius: 12, background: `linear-gradient(135deg,${T},${T2})`, display: 'flex', alignItems: 'center', justifyContent: 'center', boxShadow: `0 4px 14px ${T}44` }}>
                                                <i className="ti ti-photo" style={{ color: '#fff', fontSize: 19 }} />
                                            </div>
                                            <div>
                                                <div style={{ fontSize: 15, fontWeight: 800, color: '#0F172A' }}>معرض الأعمال</div>
                                                <div style={{ fontSize: 12, color: '#64748B' }}>
                                                    {galleryCount > 0 ? `${galleryCount} صورة منشورة` : 'أضف صور أعمالك لعرضها للعملاء'}
                                                </div>
                                            </div>
                                        </div>
                                        <label style={{ display: 'inline-flex', alignItems: 'center', gap: 7, padding: '10px 22px', background: `linear-gradient(135deg,${T},${T2})`, color: '#fff', borderRadius: 11, fontSize: 13, fontWeight: 700, cursor: 'pointer', fontFamily: FONT, boxShadow: `0 4px 14px ${T}44`, userSelect: 'none' }}>
                                            <i className="ti ti-plus" style={{ fontSize: 15 }} /> إضافة صور
                                            <input type="file" accept="image/*" multiple style={{ display: 'none' }}
                                                onChange={e => { addPendingFiles(e.target.files); e.target.value = ''; }} />
                                        </label>
                                    </div>

                                    {/* pending upload queue */}
                                    {pendingFiles.length > 0 && (
                                        <div style={{ marginBottom: 22, background: '#F8FFFE', border: `1.5px solid ${T}28`, borderRadius: 16, overflow: 'hidden' }}>
                                            <div style={{ padding: '12px 18px', borderBottom: `1.5px solid ${T}18`, display: 'flex', alignItems: 'center', justifyContent: 'space-between' }}>
                                                <span style={{ fontSize: 13, fontWeight: 700, color: '#0F172A', display: 'flex', alignItems: 'center', gap: 6 }}>
                                                    <i className="ti ti-stack-2" style={{ color: T }} />{pendingFiles.length} صورة جاهزة للرفع
                                                </span>
                                                <button onClick={() => setPendingFiles([])} style={{ background: 'none', border: 'none', fontSize: 12, color: '#94A3B8', cursor: 'pointer', fontFamily: FONT, display: 'flex', alignItems: 'center', gap: 3 }}>
                                                    <i className="ti ti-x" /> إلغاء
                                                </button>
                                            </div>
                                            <div style={{ padding: 14, display: 'grid', gridTemplateColumns: 'repeat(auto-fill,minmax(120px,1fr))', gap: 10 }}>
                                                {pendingFiles.map(f => (
                                                    <div key={f.id} style={{ position: 'relative', aspectRatio: '1', borderRadius: 12, overflow: 'hidden', border: `1.5px solid ${T}22` }}>
                                                        <img src={f.preview} alt="" style={{ width: '100%', height: '100%', objectFit: 'cover', display: 'block' }} />
                                                        <button onClick={() => removePending(f.id)} style={{ position: 'absolute', top: 5, left: 5, width: 24, height: 24, borderRadius: '50%', background: 'rgba(0,0,0,0.65)', border: '1.5px solid rgba(255,255,255,0.4)', color: '#fff', cursor: 'pointer', display: 'flex', alignItems: 'center', justifyContent: 'center' }}>
                                                            <i className="ti ti-x" style={{ fontSize: 10 }} />
                                                        </button>
                                                        <div style={{ position: 'absolute', bottom: 0, inset: 'auto 0 0 0', background: 'linear-gradient(transparent,rgba(0,0,0,0.55))', padding: '14px 5px 5px', fontSize: 9.5, color: '#fff', textAlign: 'center' }}>
                                                            {(f.size / 1024).toFixed(0)} KB
                                                        </div>
                                                    </div>
                                                ))}
                                                <label style={{ aspectRatio: '1', borderRadius: 12, border: `1.5px dashed ${T}44`, display: 'flex', flexDirection: 'column', alignItems: 'center', justifyContent: 'center', gap: 5, cursor: 'pointer', background: `${T}07`, color: T }}>
                                                    <i className="ti ti-plus" style={{ fontSize: 20 }} />
                                                    <span style={{ fontSize: 11, fontWeight: 700 }}>إضافة</span>
                                                    <input type="file" accept="image/*" multiple style={{ display: 'none' }}
                                                        onChange={e => { addPendingFiles(e.target.files); e.target.value = ''; }} />
                                                </label>
                                            </div>
                                            <div style={{ padding: '12px 18px', borderTop: `1.5px solid ${T}18`, display: 'flex', alignItems: 'center', justifyContent: 'space-between', background: '#F8FFFE' }}>
                                                <span style={{ fontSize: 12, color: '#64748B' }}>{pendingFiles.length} صورة · {(pendingFiles.reduce((a, f) => a + f.size, 0) / 1024 / 1024).toFixed(1)} MB</span>
                                                <Btn onClick={uploadPending} disabled={galleryUploading} sm>
                                                    <i className={`ti ${galleryUploading ? 'ti-loader-2' : 'ti-cloud-upload'}`} style={{ fontSize: 14 }} />
                                                    {galleryUploading ? 'جارٍ الرفع...' : `رفع ${pendingFiles.length} صورة`}
                                                </Btn>
                                            </div>
                                        </div>
                                    )}

                                    {/* empty state */}
                                    {!galleryCount && !pendingFiles.length && (
                                        <label
                                            style={{ display: 'block', border: `2px dashed ${T}2A`, borderRadius: 18, padding: '52px 24px', textAlign: 'center', cursor: 'pointer', background: `${T}04`, transition: 'background .15s' }}
                                            onMouseEnter={e => e.currentTarget.style.background = `${T}09`}
                                            onMouseLeave={e => e.currentTarget.style.background = `${T}04`}
                                            onDragOver={e => { e.preventDefault(); e.currentTarget.style.background = `${T}0E`; }}
                                            onDragLeave={e => { e.currentTarget.style.background = `${T}04`; }}
                                            onDrop={e => { e.preventDefault(); e.currentTarget.style.background = `${T}04`; addPendingFiles(e.dataTransfer.files); }}
                                        >
                                            <input type="file" accept="image/*" multiple style={{ display: 'none' }}
                                                onChange={e => { addPendingFiles(e.target.files); e.target.value = ''; }} />
                                            <div style={{ width: 76, height: 76, borderRadius: '50%', background: `${T}12`, display: 'flex', alignItems: 'center', justifyContent: 'center', margin: '0 auto 18px' }}>
                                                <i className="ti ti-photo-plus" style={{ fontSize: 34, color: T }} />
                                            </div>
                                            <div style={{ fontSize: 16, fontWeight: 700, color: '#0F172A', marginBottom: 8 }}>ابدأ ببناء معرض أعمالك</div>
                                            <div style={{ fontSize: 13, color: '#64748B', marginBottom: 5 }}>أضف صور مشاريعك وأعمالك ليشاهدها العملاء والزوار</div>
                                            <div style={{ fontSize: 11.5, color: '#CBD5E1' }}>PNG · JPG — حتى 5 MB · يمكن اختيار عدة صور دفعة واحدة</div>
                                        </label>
                                    )}

                                    {/* add more strip */}
                                    {galleryCount > 0 && !pendingFiles.length && (
                                        <label
                                            style={{ display: 'flex', alignItems: 'center', gap: 12, padding: '12px 16px', border: `1.5px dashed ${T}2A`, borderRadius: 12, cursor: 'pointer', background: `${T}05`, marginBottom: 16, transition: 'background .15s' }}
                                            onMouseEnter={e => e.currentTarget.style.background = `${T}0C`}
                                            onMouseLeave={e => e.currentTarget.style.background = `${T}05`}
                                            onDragOver={e => { e.preventDefault(); e.currentTarget.style.background = `${T}0E`; }}
                                            onDragLeave={e => { e.currentTarget.style.background = `${T}05`; }}
                                            onDrop={e => { e.preventDefault(); e.currentTarget.style.background = `${T}05`; addPendingFiles(e.dataTransfer.files); }}
                                        >
                                            <input type="file" accept="image/*" multiple style={{ display: 'none' }}
                                                onChange={e => { addPendingFiles(e.target.files); e.target.value = ''; }} />
                                            <div style={{ width: 38, height: 38, borderRadius: 10, background: `${T}12`, display: 'flex', alignItems: 'center', justifyContent: 'center', flexShrink: 0 }}>
                                                <i className="ti ti-photo-plus" style={{ color: T, fontSize: 18 }} />
                                            </div>
                                            <div>
                                                <div style={{ fontSize: 13, fontWeight: 700, color: T }}>إضافة صور جديدة</div>
                                                <div style={{ fontSize: 11.5, color: '#94A3B8' }}>اسحب أو انقر — يمكن اختيار عدة صور دفعة واحدة</div>
                                            </div>
                                        </label>
                                    )}

                                    {/* gallery grid */}
                                    {galleryCount > 0 && (
                                        <div style={{ display: 'grid', gridTemplateColumns: 'repeat(auto-fill,minmax(220px,1fr))', gap: 14 }}>
                                            {(gallery ?? []).map(item => (
                                                <GalleryItem key={item.id} item={item} onDelete={deleteGalleryItem} onZoom={setLightbox} />
                                            ))}
                                        </div>
                                    )}
                                    </div>
                                    {/* ══ END GALLERY ══ */}
                                </div>

                            ) : (
                                /* ── Create Business ── */
                                <div>
                                    {/* CTA */}
                                    <div style={{ display: 'flex', alignItems: 'center', gap: 18, padding: '20px 22px', background: `linear-gradient(135deg,${T}0C,${T}18)`, borderRadius: 16, border: `1.5px solid ${T}22`, marginBottom: 28 }}>
                                        <div style={{ width: 54, height: 54, borderRadius: 16, background: `linear-gradient(135deg,${T},${T2})`, display: 'flex', alignItems: 'center', justifyContent: 'center', boxShadow: `0 6px 18px ${T}44`, flexShrink: 0 }}>
                                            <i className="ti ti-rocket" style={{ color: '#fff', fontSize: 24 }} />
                                        </div>
                                        <div>
                                            <div style={{ fontSize: 15, fontWeight: 800, color: '#0F172A', marginBottom: 3 }}>أنشئ حساب عملك الآن</div>
                                            <div style={{ fontSize: 12.5, color: '#047857' }}>ابدأ بعرض خدماتك وتوسيع نشاطك التجاري على منصة Skillify</div>
                                        </div>
                                    </div>

                                    <form onSubmit={submitBiz} encType="multipart/form-data">
                                        <div className="grid grid-cols-1 sm:grid-cols-2" style={{ gap: 16, marginBottom: 18 }}>
                                            <Field label="المسمى الوظيفي" icon="ti-briefcase" error={bizForm.errors.name_job}>
                                                <FInput value={bizForm.data.name_job} onChange={e => bizForm.setData('name_job', e.target.value)} required placeholder="مثال: نجّار محترف، مصمم جرافيك..." />
                                            </Field>
                                            <Field label="رقم هاتف العمل" icon="ti-phone" error={bizForm.errors.number}>
                                                <FInput value={bizForm.data.number} onChange={e => bizForm.setData('number', e.target.value)} required />
                                            </Field>
                                        </div>
                                        <div style={{ marginBottom: 18 }}>
                                            <Field label="نوع النشاط التجاري" icon="ti-category" error={bizForm.errors.active_typebusiness_id}>
                                                <FSelect value={bizForm.data.active_typebusiness_id} onChange={e => bizForm.setData('active_typebusiness_id', e.target.value)} required>
                                                    <option value="">— اختر نوع النشاط —</option>
                                                    {(activeTypes ?? []).map(t => <option key={t.id} value={t.id}>{t.name}</option>)}
                                                </FSelect>
                                            </Field>
                                        </div>
                                        <div style={{ marginBottom: 18 }}>
                                            <Field label="وصف النشاط" icon="ti-align-left" error={bizForm.errors.description}>
                                                <FTextarea value={bizForm.data.description} onChange={e => bizForm.setData('description', e.target.value)} placeholder="اكتب وصفاً مختصراً عن خدماتك وخبراتك..." />
                                            </Field>
                                        </div>
                                        <div className="grid grid-cols-1 sm:grid-cols-3" style={{ gap: 14, marginBottom: 18 }}>
                                            <Field label="المدينة" icon="ti-building-community" error={bizForm.errors.city}>
                                                <FSelect value={bizForm.data.city} onChange={e => bizForm.setData('city', e.target.value)}>
                                                    <option value="">— اختر مدينة —</option>
                                                    {(cities ?? []).map(c => <option key={c.id} value={c.name}>{c.name}</option>)}
                                                </FSelect>
                                            </Field>
                                            <Field label="المنطقة / الحي" icon="ti-map" error={bizForm.errors.area}>
                                                <FInput value={bizForm.data.area} onChange={e => bizForm.setData('area', e.target.value)} placeholder="مثال: المزة..." />
                                            </Field>
                                            <Field label="اسم الشارع" icon="ti-road" error={bizForm.errors.street}>
                                                <FInput value={bizForm.data.street} onChange={e => bizForm.setData('street', e.target.value)} placeholder="مثال: شارع الجلاء..." />
                                            </Field>
                                        </div>
                                        {/* Logo — required */}
                                        <div style={{ marginBottom: 26 }}>
                                            <div style={{ display: 'flex', alignItems: 'center', gap: 6, marginBottom: 10 }}>
                                                <div style={{ width: 3, height: 16, borderRadius: 2, background: `linear-gradient(180deg,${T},${T2})` }} />
                                                <i className="ti ti-building-store" style={{ color: T, fontSize: 13 }} />
                                                <span style={{ fontSize: 13, fontWeight: 700, color: '#0F172A' }}>
                                                    شعار النشاط التجاري
                                                    <span style={{ color: '#EF4444', marginRight: 4 }}>*</span>
                                                </span>
                                                <span style={{ fontSize: 11, color: '#94A3B8', background: '#FEF2F2', border: '1px solid #FCA5A5', borderRadius: 6, padding: '2px 8px', fontWeight: 600 }}>إلزامي</span>
                                            </div>
                                            <div style={{ background: bizForm.errors.image ? '#FFF5F5' : '#FAFAFA', border: `1.5px solid ${bizForm.errors.image ? '#FCA5A5' : `${T}22`}`, borderRadius: 16, padding: 20 }}>
                                                <ImageUploadZone
                                                    preview={imgPreview}
                                                    inputRef={imgRef}
                                                    onChange={e => { const f = e.target.files?.[0]; if (f) { bizForm.setData('image', f); setImgPreview(URL.createObjectURL(f)); } }}
                                                    onClear={() => { bizForm.setData('image', null); setImgPreview(null); }}
                                                />
                                                {!imgPreview && (
                                                    <p style={{ fontSize: 11.5, color: '#94A3B8', textAlign: 'center', marginTop: 12, marginBottom: 0 }}>
                                                        <i className="ti ti-info-circle" style={{ fontSize: 12, marginLeft: 4 }} />
                                                        الصورة مطلوبة — ستظهر بجانب اسم عملك على المنصة
                                                    </p>
                                                )}
                                            </div>
                                            {bizForm.errors.image && (
                                                <p style={{ fontSize: 11, color: '#EF4444', marginTop: 6, display: 'flex', alignItems: 'center', gap: 4 }}>
                                                    <i className="ti ti-alert-circle" style={{ fontSize: 11 }} />{bizForm.errors.image}
                                                </p>
                                            )}
                                        </div>

                                        {/* ── معرض الأعمال ── */}
                                        <div style={{ marginBottom: 26, padding: 22, background: `${T}05`, border: `1.5px solid ${T}22`, borderRadius: 18 }}>
                                            <div style={{ display: 'flex', alignItems: 'center', justifyContent: 'space-between', marginBottom: 16 }}>
                                                <div style={{ display: 'flex', alignItems: 'center', gap: 10 }}>
                                                    <div style={{ width: 38, height: 38, borderRadius: 11, background: `linear-gradient(135deg,${T},${T2})`, display: 'flex', alignItems: 'center', justifyContent: 'center', boxShadow: `0 4px 12px ${T}44` }}>
                                                        <i className="ti ti-photo" style={{ color: '#fff', fontSize: 17 }} />
                                                    </div>
                                                    <div>
                                                        <div style={{ fontSize: 14, fontWeight: 800, color: '#0F172A' }}>معرض الأعمال</div>
                                                        <div style={{ fontSize: 11.5, color: '#64748B' }}>
                                                            {createGalleryFiles.length > 0 ? `${createGalleryFiles.length} صورة محددة` : 'أضف صور أعمالك ومشاريعك'}
                                                        </div>
                                                    </div>
                                                </div>
                                                <label style={{ display: 'inline-flex', alignItems: 'center', gap: 6, padding: '9px 18px', background: `linear-gradient(135deg,${T},${T2})`, color: '#fff', borderRadius: 10, fontSize: 12.5, fontWeight: 700, cursor: 'pointer', fontFamily: FONT, boxShadow: `0 4px 12px ${T}44`, userSelect: 'none' }}>
                                                    <i className="ti ti-plus" style={{ fontSize: 14 }} /> إضافة صور
                                                    <input type="file" accept="image/*" multiple style={{ display: 'none' }}
                                                        onChange={e => { addCreateGalleryFiles(e.target.files); e.target.value = ''; }} />
                                                </label>
                                            </div>

                                            {createGalleryFiles.length === 0 ? (
                                                <label
                                                    style={{ display: 'block', border: `2px dashed ${T}2A`, borderRadius: 14, padding: '36px 24px', textAlign: 'center', cursor: 'pointer', background: '#fff', transition: 'background .15s' }}
                                                    onMouseEnter={e => e.currentTarget.style.background = `${T}07`}
                                                    onMouseLeave={e => e.currentTarget.style.background = '#fff'}
                                                    onDragOver={e => { e.preventDefault(); e.currentTarget.style.background = `${T}0D`; }}
                                                    onDragLeave={e => { e.currentTarget.style.background = '#fff'; }}
                                                    onDrop={e => { e.preventDefault(); e.currentTarget.style.background = '#fff'; addCreateGalleryFiles(e.dataTransfer.files); }}
                                                >
                                                    <input type="file" accept="image/*" multiple style={{ display: 'none' }}
                                                        onChange={e => { addCreateGalleryFiles(e.target.files); e.target.value = ''; }} />
                                                    <i className="ti ti-photo-plus" style={{ fontSize: 36, color: T, display: 'block', marginBottom: 10 }} />
                                                    <div style={{ fontSize: 14, fontWeight: 700, color: '#0F172A', marginBottom: 5 }}>اختر صور أعمالك أو اسحبها هنا</div>
                                                    <div style={{ fontSize: 12, color: '#94A3B8' }}>PNG · JPG — حتى 5 MB · يمكن اختيار عدة صور دفعة واحدة</div>
                                                </label>
                                            ) : (
                                                <div>
                                                    <div style={{ display: 'grid', gridTemplateColumns: 'repeat(auto-fill,minmax(130px,1fr))', gap: 10, marginBottom: 12 }}>
                                                        {createGalleryFiles.map(f => (
                                                            <div key={f.id} style={{ position: 'relative', aspectRatio: '4/3', borderRadius: 12, overflow: 'hidden', border: `1.5px solid ${T}22`, background: '#fff' }}>
                                                                <img src={f.preview} alt="" style={{ width: '100%', height: '100%', objectFit: 'cover', display: 'block' }} />
                                                                <button type="button" onClick={() => removeCreateGalleryFile(f.id)}
                                                                    style={{ position: 'absolute', top: 5, left: 5, width: 24, height: 24, borderRadius: '50%', background: 'rgba(220,38,38,0.85)', border: '2px solid #fff', color: '#fff', cursor: 'pointer', display: 'flex', alignItems: 'center', justifyContent: 'center' }}>
                                                                    <i className="ti ti-x" style={{ fontSize: 10 }} />
                                                                </button>
                                                                <div style={{ position: 'absolute', bottom: 0, inset: 'auto 0 0 0', background: 'linear-gradient(transparent,rgba(0,0,0,0.5))', padding: '14px 5px 5px', fontSize: 9.5, color: '#fff', textAlign: 'center' }}>
                                                                    {(f.size / 1024).toFixed(0)} KB
                                                                </div>
                                                            </div>
                                                        ))}
                                                        <label style={{ aspectRatio: '4/3', borderRadius: 12, border: `1.5px dashed ${T}44`, display: 'flex', flexDirection: 'column', alignItems: 'center', justifyContent: 'center', gap: 5, cursor: 'pointer', background: `${T}07`, color: T }}>
                                                            <i className="ti ti-plus" style={{ fontSize: 22 }} />
                                                            <span style={{ fontSize: 11, fontWeight: 700 }}>إضافة</span>
                                                            <input type="file" accept="image/*" multiple style={{ display: 'none' }}
                                                                onChange={e => { addCreateGalleryFiles(e.target.files); e.target.value = ''; }} />
                                                        </label>
                                                    </div>
                                                    <div style={{ fontSize: 12, color: '#64748B', textAlign: 'center' }}>
                                                        {createGalleryFiles.length} صورة · {(createGalleryFiles.reduce((a, f) => a + f.size, 0) / 1024 / 1024).toFixed(1)} MB · ستُرفع عند إنشاء الحساب
                                                    </div>
                                                </div>
                                            )}
                                        </div>
                                        {/* ── end معرض ── */}

                                        <div style={{ display: 'flex', justifyContent: 'flex-end' }}>
                                            <Btn type="submit" disabled={bizForm.processing}>
                                                <i className="ti ti-rocket" style={{ fontSize: 15 }} />
                                                {bizForm.processing ? 'جارٍ الإرسال...' : 'إنشاء حساب العمل'}
                                            </Btn>
                                        </div>
                                    </form>
                                </div>
                            )}
                        </div>
                    </Panel>
                )}

                {/* Lightbox */}
                {lightbox && (
                    <div style={{ position: 'fixed', inset: 0, background: 'rgba(0,0,0,0.90)', zIndex: 9999, display: 'flex', alignItems: 'center', justifyContent: 'center' }} onClick={() => setLightbox(null)}>
                        <img src={lightbox} alt="" style={{ maxWidth: '92vw', maxHeight: '88vh', borderRadius: 14, boxShadow: '0 8px 48px rgba(0,0,0,0.6)', objectFit: 'contain' }} />
                        <button onClick={() => setLightbox(null)} style={{ position: 'absolute', top: 20, right: 20, width: 42, height: 42, borderRadius: '50%', background: 'rgba(255,255,255,0.12)', border: '1.5px solid rgba(255,255,255,0.2)', color: '#fff', cursor: 'pointer', display: 'flex', alignItems: 'center', justifyContent: 'center', backdropFilter: 'blur(8px)' }}>
                            <i className="ti ti-x" style={{ fontSize: 18 }} />
                        </button>
                    </div>
                )}

                {/* ══════════════════════ SERVICES TAB ══════════════════════ */}
                {tab === 'services' && (
                    <Panel noPad>
                        <PanelHead
                            icon="ti-tool"
                            title="خدماتي"
                            sub={`${svcCount} خدمة مدرجة`}
                            action={
                                <a href="/user/my-services" style={{ display: 'inline-flex', alignItems: 'center', gap: 7, padding: '9px 20px', background: `linear-gradient(135deg,${T},${T2})`, color: '#fff', borderRadius: 10, fontSize: 12.5, fontWeight: 700, textDecoration: 'none', boxShadow: `0 4px 12px ${T}33`, fontFamily: FONT }}>
                                    <i className="ti ti-plus" /> إضافة خدمة
                                </a>
                            }
                        />
                        <div style={{ padding: 28 }}>
                            {!svcCount ? (
                                <div style={{ textAlign: 'center', padding: '60px 24px' }}>
                                    <div style={{ width: 76, height: 76, borderRadius: '50%', background: '#F1F5F9', display: 'flex', alignItems: 'center', justifyContent: 'center', margin: '0 auto 18px' }}>
                                        <i className="ti ti-tool" style={{ fontSize: 34, color: '#CBD5E1' }} />
                                    </div>
                                    <div style={{ fontSize: 15.5, fontWeight: 700, color: '#0F172A', marginBottom: 8 }}>لا توجد خدمات بعد</div>
                                    <div style={{ fontSize: 13, color: '#94A3B8', marginBottom: 22 }}>أضف خدمتك الأولى لتظهر للعملاء</div>
                                    <a href="/user/my-services" style={{ display: 'inline-flex', alignItems: 'center', gap: 8, padding: '11px 26px', background: `linear-gradient(135deg,${T},${T2})`, color: '#fff', borderRadius: 12, fontSize: 13.5, fontWeight: 700, textDecoration: 'none', boxShadow: `0 4px 14px ${T}44`, fontFamily: FONT }}>
                                        <i className="ti ti-plus" /> إضافة أول خدمة
                                    </a>
                                </div>
                            ) : (
                                <div style={{ display: 'grid', gridTemplateColumns: 'repeat(auto-fill,minmax(260px,1fr))', gap: 16 }}>
                                    {(userServices ?? []).map(s => {
                                        const imgSrc = s.image ? (s.image.startsWith('http') ? s.image : `/storage/${s.image}`) : null;
                                        const stMap  = {
                                            approved: { label: 'مقبول',         color: '#15803D', bg: '#F0FDF4', border: '#BBF7D0', dot: '#16A34A' },
                                            pending:  { label: 'قيد المراجعة', color: '#B45309', bg: '#FFFBEB', border: '#FDE68A', dot: '#CA8A04' },
                                            rejected: { label: 'مرفوض',         color: '#DC2626', bg: '#FEF2F2', border: '#FECACA', dot: '#DC2626' },
                                        };
                                        const st = stMap[s.status] ?? stMap.pending;
                                        return (
                                            <div key={s.id}
                                                style={{ border: '1.5px solid #F1F5F9', borderRadius: 16, overflow: 'hidden', background: '#fff', boxShadow: '0 2px 12px rgba(0,0,0,0.04)', transition: 'box-shadow .15s, transform .15s' }}
                                                onMouseEnter={e => { e.currentTarget.style.boxShadow = '0 6px 24px rgba(0,0,0,0.09)'; e.currentTarget.style.transform = 'translateY(-2px)'; }}
                                                onMouseLeave={e => { e.currentTarget.style.boxShadow = '0 2px 12px rgba(0,0,0,0.04)'; e.currentTarget.style.transform = 'translateY(0)'; }}
                                            >
                                                {/* Image */}
                                                <div style={{ width: '100%', height: 136, background: imgSrc ? 'none' : `linear-gradient(135deg,${T}14,${T}28)`, display: 'flex', alignItems: 'center', justifyContent: 'center', position: 'relative', overflow: 'hidden' }}>
                                                    {imgSrc
                                                        ? <img src={imgSrc} alt={s.name} style={{ width: '100%', height: '100%', objectFit: 'cover' }} />
                                                        : <i className="ti ti-tool" style={{ fontSize: 38, color: `${T}66` }} />
                                                    }
                                                    <span style={{ position: 'absolute', top: 10, right: 10, display: 'inline-flex', alignItems: 'center', gap: 4, fontSize: 10.5, fontWeight: 700, padding: '3px 10px', borderRadius: 20, background: st.bg, color: st.color, border: `1px solid ${st.border}` }}>
                                                        <span style={{ width: 5, height: 5, borderRadius: '50%', background: st.dot }} />{st.label}
                                                    </span>
                                                </div>
                                                {/* Content */}
                                                <div style={{ padding: '14px 16px' }}>
                                                    <div style={{ fontSize: 14.5, fontWeight: 700, color: '#0F172A', marginBottom: 7, overflow: 'hidden', textOverflow: 'ellipsis', whiteSpace: 'nowrap' }}>{s.name}</div>
                                                    <div style={{ display: 'flex', alignItems: 'center', gap: 7, marginBottom: 12, flexWrap: 'wrap' }}>
                                                        {s.category?.name && <span style={{ fontSize: 11, fontWeight: 700, padding: '3px 9px', borderRadius: 20, background: `${T}14`, color: T }}>{s.category.name}</span>}
                                                        {s.city?.name && <span style={{ fontSize: 11, color: '#94A3B8', display: 'flex', alignItems: 'center', gap: 3 }}><i className="ti ti-map-pin" style={{ fontSize: 10 }} />{s.city.name}</span>}
                                                    </div>
                                                    <div style={{ display: 'flex', alignItems: 'center', justifyContent: 'space-between' }}>
                                                        <div style={{ fontSize: 17, fontWeight: 800, color: T }}>
                                                            {Number(s.price).toLocaleString()}
                                                            <small style={{ fontSize: 11, fontWeight: 500, color: '#94A3B8', marginRight: 4 }}>{s.price_type}</small>
                                                        </div>
                                                        <a href={`/user/services/${s.id}`} style={{ fontSize: 12, color: T, fontWeight: 700, textDecoration: 'none', display: 'flex', alignItems: 'center', gap: 4 }}>
                                                            تفاصيل <i className="ti ti-arrow-left" style={{ fontSize: 11 }} />
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        );
                                    })}
                                </div>
                            )}
                        </div>
                    </Panel>
                )}

            </div>
        </UserLayout>
    );
}
