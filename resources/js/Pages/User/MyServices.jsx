import { Head, router, useForm, usePage } from '@inertiajs/react';
import { useState, useRef, useEffect } from 'react';
import { createPortal } from 'react-dom';
import UserLayout from '../../Layouts/UserLayout';

const STATUS = {
    approved: { bg: '#ECFDF5', color: '#065F46', border: '#6EE7B7', dot: '#10B981', label: 'مقبول' },
    pending:  { bg: '#FFFBEB', color: '#92400E', border: '#FCD34D', dot: '#F59E0B', label: 'قيد المراجعة' },
    rejected: { bg: '#FEF2F2', color: '#991B1B', border: '#FCA5A5', dot: '#EF4444', label: 'مرفوض' },
    inactive: { bg: '#F1F5F9', color: '#475569', border: '#CBD5E1', dot: '#94A3B8', label: 'غير نشط' },
};

const inp = (err) => ({
    width: '100%', boxSizing: 'border-box', padding: '10px 13px',
    borderRadius: 10, fontSize: 13, fontFamily: 'inherit', outline: 'none',
    border: `1.5px solid ${err ? '#FCA5A5' : '#E2E8F0'}`,
    background: err ? '#FFF5F5' : '#F8FAFC',
    color: '#0F172A', direction: 'rtl', transition: 'border-color .15s',
});

const focus = { onFocus: e => e.target.style.borderColor = '#0D9488', onBlur: e => e.target.style.borderColor = '#E2E8F0' };

function StatCard({ icon, label, count, color, bg }) {
    return (
        <div style={{ background: '#fff', borderRadius: 14, padding: '16px 20px', border: '1px solid #F1F5F9', boxShadow: '0 1px 6px rgba(0,0,0,0.04)', display: 'flex', alignItems: 'center', gap: 14 }}>
            <div style={{ width: 44, height: 44, borderRadius: 12, background: bg, display: 'flex', alignItems: 'center', justifyContent: 'center', flexShrink: 0 }}>
                <i className={`ti ${icon}`} style={{ fontSize: 20, color }} />
            </div>
            <div>
                <div style={{ fontSize: 22, fontWeight: 800, color: '#0F172A', lineHeight: 1 }}>{count}</div>
                <div style={{ fontSize: 11.5, color: '#94A3B8', marginTop: 3, fontWeight: 500 }}>{label}</div>
            </div>
        </div>
    );
}

function Chip({ icon, text }) {
    return (
        <span style={{ display: 'inline-flex', alignItems: 'center', gap: 4, fontSize: 11, color: '#64748B', background: '#F8FAFC', padding: '3px 9px', borderRadius: 20, border: '0.5px solid #E2E8F0' }}>
            <i className={`ti ${icon}`} style={{ fontSize: 10 }} />{text}
        </span>
    );
}

function Label({ text, required }) {
    return (
        <div style={{ fontSize: 12, fontWeight: 600, color: '#374151', marginBottom: 6 }}>
            {text}{required && <span style={{ color: '#EF4444', marginRight: 3 }}>*</span>}
        </div>
    );
}

function FieldErr({ errors, k }) {
    return errors[k] ? (
        <p style={{ color: '#EF4444', fontSize: 11, marginTop: 4, display: 'flex', alignItems: 'center', gap: 4, margin: '4px 0 0' }}>
            <i className="ti ti-alert-circle" style={{ fontSize: 11 }} />{errors[k]}
        </p>
    ) : null;
}

function ImageDropzone({ preview, onPick, dragOver, setDragOver, fileRef }) {
    return (
        <div>
            <Label text="صورة الخدمة" />
            <div
                onClick={() => fileRef.current.click()}
                onDragOver={e => { e.preventDefault(); setDragOver(true); }}
                onDragLeave={() => setDragOver(false)}
                onDrop={e => { e.preventDefault(); setDragOver(false); onPick(e.dataTransfer.files[0]); }}
                style={{
                    border: `2px dashed ${dragOver ? '#0D9488' : preview ? '#6EE7B7' : '#E2E8F0'}`,
                    borderRadius: 14, cursor: 'pointer', overflow: 'hidden', transition: 'all .2s',
                    background: dragOver ? '#F0FDFA' : preview ? '#F0FDFA' : '#FAFAFA',
                    minHeight: 140, display: 'flex', alignItems: 'center', justifyContent: 'center',
                }}>
                {preview ? (
                    <div style={{ position: 'relative', width: '100%' }}>
                        <img src={preview} alt="preview" style={{ width: '100%', maxHeight: 200, objectFit: 'cover', display: 'block' }} />
                        <div style={{ position: 'absolute', inset: 0, background: 'rgba(0,0,0,0.4)', display: 'flex', alignItems: 'center', justifyContent: 'center', opacity: 0, transition: 'opacity .2s' }}
                            onMouseEnter={e => e.currentTarget.style.opacity = 1}
                            onMouseLeave={e => e.currentTarget.style.opacity = 0}>
                            <span style={{ color: '#fff', fontSize: 13, fontWeight: 700, display: 'flex', alignItems: 'center', gap: 7, background: 'rgba(0,0,0,0.4)', padding: '8px 16px', borderRadius: 10 }}>
                                <i className="ti ti-pencil" /> تغيير الصورة
                            </span>
                        </div>
                    </div>
                ) : (
                    <div style={{ textAlign: 'center', padding: '28px 20px' }}>
                        <div style={{ width: 48, height: 48, borderRadius: '50%', background: '#E0F2FE', display: 'flex', alignItems: 'center', justifyContent: 'center', margin: '0 auto 10px' }}>
                            <i className="ti ti-cloud-upload" style={{ fontSize: 22, color: '#0D9488' }} />
                        </div>
                        <div style={{ fontSize: 13, fontWeight: 700, color: '#374151' }}>اسحب صورة هنا</div>
                        <div style={{ fontSize: 11.5, color: '#9CA3AF', marginTop: 4 }}>أو <span style={{ color: '#0D9488', fontWeight: 600 }}>اختر من جهازك</span> — PNG, JPG حتى 2 ميغابايت</div>
                    </div>
                )}
            </div>
            <input ref={fileRef} type="file" accept="image/*" style={{ display: 'none' }} onChange={e => onPick(e.target.files[0])} />
        </div>
    );
}

function ServiceForm({ data, setData, errors, categories, subcategories, cities, children }) {
    const filteredSubs = subcategories.filter(s => String(s.category_id) === String(data.category_id));
    return (
        <div style={{ display: 'flex', flexDirection: 'column', gap: 18 }}>
            <div>
                <Label text="اسم الخدمة" required />
                <input value={data.name} onChange={e => setData('name', e.target.value)} placeholder="مثال: تصميم شعار احترافي" style={inp(errors.name)} {...focus} />
                <FieldErr errors={errors} k="name" />
            </div>

            <div className="grid grid-cols-1 sm:grid-cols-2" style={{ gap: 14 }}>
                <div>
                    <Label text="التصنيف" required />
                    <select value={data.category_id} onChange={e => setData(d => ({ ...d, category_id: e.target.value, subcategory_id: '' }))} style={inp(errors.category_id)} {...focus}>
                        <option value="">اختر التصنيف</option>
                        {categories.map(c => <option key={c.id} value={c.id}>{c.name}</option>)}
                    </select>
                    <FieldErr errors={errors} k="category_id" />
                </div>
                <div>
                    <Label text="التخصص" required />
                    <select value={data.subcategory_id} onChange={e => setData('subcategory_id', e.target.value)} disabled={!data.category_id} style={{ ...inp(errors.subcategory_id), opacity: !data.category_id ? 0.55 : 1 }} {...focus}>
                        <option value="">{data.category_id ? 'اختر التخصص' : 'اختر التصنيف أولاً'}</option>
                        {filteredSubs.map(s => <option key={s.id} value={s.id}>{s.name}</option>)}
                    </select>
                    <FieldErr errors={errors} k="subcategory_id" />
                </div>
            </div>

            <div className="grid grid-cols-1 sm:grid-cols-2" style={{ gap: 14 }}>
                <div>
                    <Label text="المدينة" required />
                    <select value={data.city_id} onChange={e => setData('city_id', e.target.value)} style={inp(errors.city_id)} {...focus}>
                        <option value="">اختر المدينة</option>
                        {cities.map(c => <option key={c.id} value={c.id}>{c.name}</option>)}
                    </select>
                    <FieldErr errors={errors} k="city_id" />
                </div>
                <div>
                    <Label text="السعر" required />
                    <div style={{ display: 'flex', gap: 6 }}>
                        <input type="number" min="0" value={data.price} onChange={e => setData('price', e.target.value)} placeholder="0" style={{ ...inp(errors.price), flex: 1 }} {...focus} />
                        <select value={data.price_type} onChange={e => setData('price_type', e.target.value)} style={{ ...inp(false), width: 82, padding: '10px 8px', fontWeight: 700 }}>
                            <option value="syp">ل.س</option>
                            <option value="usd">USD</option>
                        </select>
                    </div>
                    <FieldErr errors={errors} k="price" />
                </div>
            </div>

            <div>
                <Label text="وصف الخدمة" />
                <textarea value={data.description} onChange={e => setData('description', e.target.value)} placeholder="اكتب وصفاً مختصراً يساعد العملاء على فهم خدمتك..." rows={3} style={{ ...inp(errors.description), resize: 'vertical', fontFamily: 'inherit', lineHeight: 1.65 }} onFocus={e => e.target.style.borderColor = '#0D9488'} onBlur={e => e.target.style.borderColor = '#E2E8F0'} />
            </div>

            {children}
        </div>
    );
}

function ModalShell({ onClose, headerGradient, icon, title, subtitle, children }) {
    return createPortal(
        <div style={{ position: 'fixed', inset: 0, zIndex: 9999, display: 'flex', alignItems: 'center', justifyContent: 'center', padding: 16, background: 'rgba(15,23,42,0.65)', backdropFilter: 'blur(6px)' }}
            onClick={e => e.target === e.currentTarget && onClose()}>
            <div style={{ background: '#fff', borderRadius: 22, width: '100%', maxWidth: 600, maxHeight: '94vh', overflowY: 'auto', display: 'flex', flexDirection: 'column', boxShadow: '0 32px 80px rgba(0,0,0,0.3)', animation: 'modalIn .25s cubic-bezier(.22,.68,0,1.2)' }}>
                <div style={{ background: headerGradient, padding: '22px 26px', borderRadius: '22px 22px 0 0', flexShrink: 0, position: 'relative', overflow: 'hidden' }}>
                    <div style={{ position: 'absolute', top: -20, left: -20, width: 120, height: 120, borderRadius: '50%', background: 'rgba(255,255,255,0.06)' }} />
                    <div style={{ display: 'flex', alignItems: 'center', justifyContent: 'space-between', position: 'relative' }}>
                        <div style={{ display: 'flex', alignItems: 'center', gap: 14 }}>
                            <div style={{ width: 44, height: 44, borderRadius: 13, background: 'rgba(255,255,255,0.18)', display: 'flex', alignItems: 'center', justifyContent: 'center' }}>
                                <i className={`ti ti-${icon}`} style={{ color: '#fff', fontSize: 22 }} />
                            </div>
                            <div>
                                <div style={{ fontSize: 17, fontWeight: 800, color: '#fff', letterSpacing: -0.2 }}>{title}</div>
                                <div style={{ fontSize: 12, color: 'rgba(255,255,255,0.65)', marginTop: 2 }}>{subtitle}</div>
                            </div>
                        </div>
                        <button onClick={onClose} style={{ width: 36, height: 36, borderRadius: 10, border: '1.5px solid rgba(255,255,255,0.22)', background: 'rgba(255,255,255,0.1)', color: '#fff', cursor: 'pointer', display: 'flex', alignItems: 'center', justifyContent: 'center', fontSize: 16 }}>
                            <i className="ti ti-x" />
                        </button>
                    </div>
                </div>
                <div style={{ padding: '24px 26px' }}>{children}</div>
            </div>
        </div>,
        document.body
    );
}

function CreateModal({ categories, subcategories, cities, onClose }) {
    const fileRef = useRef(null);
    const [preview, setPreview] = useState(null);
    const [dragOver, setDragOver] = useState(false);
    const { data, setData, post, processing, errors, reset } = useForm({
        name: '', category_id: '', subcategory_id: '', city_id: '',
        price: '', price_type: 'syp', description: '', image: null,
    });

    const pickFile = (file) => {
        if (!file) return;
        setData('image', file);
        const r = new FileReader();
        r.onload = e => setPreview(e.target.result);
        r.readAsDataURL(file);
    };

    const handleSubmit = (e) => {
        e.preventDefault();
        post('/user/my-services', { forceFormData: true, onSuccess: () => { reset(); setPreview(null); onClose(); } });
    };

    return (
        <ModalShell onClose={onClose} headerGradient="linear-gradient(135deg,#0D9488,#0F766E)" icon="sparkles" title="إضافة خدمة جديدة" subtitle="ستُراجع خدمتك قبل نشرها للعموم">
            <form onSubmit={handleSubmit} style={{ display: 'flex', flexDirection: 'column', gap: 20 }}>
                <ImageDropzone preview={preview} onPick={pickFile} dragOver={dragOver} setDragOver={setDragOver} fileRef={fileRef} />
                <ServiceForm data={data} setData={setData} errors={errors} categories={categories} subcategories={subcategories} cities={cities}>
                    <div style={{ display: 'flex', gap: 10, justifyContent: 'flex-end', paddingTop: 8, borderTop: '1px solid #F1F5F9' }}>
                        <button type="button" onClick={onClose} style={{ padding: '10px 22px', borderRadius: 11, border: '1.5px solid #E2E8F0', background: '#F8FAFC', fontSize: 13, cursor: 'pointer', color: '#475569', fontWeight: 600, fontFamily: 'inherit' }}>إلغاء</button>
                        <button type="submit" disabled={processing} style={{ padding: '10px 26px', borderRadius: 11, border: 'none', fontFamily: 'inherit', background: processing ? '#94A3B8' : 'linear-gradient(135deg,#0D9488,#0F766E)', color: '#fff', fontSize: 13, fontWeight: 700, cursor: processing ? 'not-allowed' : 'pointer', display: 'inline-flex', alignItems: 'center', gap: 8, boxShadow: processing ? 'none' : '0 4px 16px rgba(13,148,136,.35)' }}>
                            {processing ? <><i className="ti ti-loader-2" style={{ fontSize: 14, animation: 'spin .8s linear infinite' }} /> جاري الحفظ...</> : <><i className="ti ti-check" style={{ fontSize: 14 }} /> نشر الخدمة</>}
                        </button>
                    </div>
                </ServiceForm>
            </form>
        </ModalShell>
    );
}

function EditModal({ service, categories, subcategories, cities, onClose }) {
    const fileRef = useRef(null);
    const existingImg = service.image ? (service.image.startsWith('http') ? service.image : `/storage/${service.image}`) : null;
    const [preview, setPreview] = useState(existingImg);
    const [dragOver, setDragOver] = useState(false);
    const { data, setData, post, processing, errors } = useForm({
        _method:        'PUT',
        name:           service.name ?? '',
        category_id:    String(service.category_id ?? ''),
        subcategory_id: String(service.subcategory_id ?? ''),
        city_id:        String(service.city_id ?? ''),
        price:          service.price ?? '',
        price_type:     service.price_type ?? 'syp',
        description:    service.description ?? '',
        image:          null,
    });

    const pickFile = (file) => {
        if (!file) return;
        setData('image', file);
        const r = new FileReader();
        r.onload = e => setPreview(e.target.result);
        r.readAsDataURL(file);
    };

    const handleSubmit = (e) => {
        e.preventDefault();
        post(`/user/my-services/${service.id}`, { forceFormData: true, onSuccess: onClose });
    };

    return (
        <ModalShell onClose={onClose} headerGradient="linear-gradient(135deg,#0F766E,#134E4A)" icon="pencil" title="تعديل الخدمة" subtitle={service.name}>
            <form onSubmit={handleSubmit} style={{ display: 'flex', flexDirection: 'column', gap: 20 }}>
                <ImageDropzone preview={preview} onPick={pickFile} dragOver={dragOver} setDragOver={setDragOver} fileRef={fileRef} />
                <ServiceForm data={data} setData={setData} errors={errors} categories={categories} subcategories={subcategories} cities={cities}>
                    <div style={{ display: 'flex', gap: 10, justifyContent: 'flex-end', paddingTop: 8, borderTop: '1px solid #F1F5F9' }}>
                        <button type="button" onClick={onClose} style={{ padding: '10px 22px', borderRadius: 11, border: '1.5px solid #E2E8F0', background: '#F8FAFC', fontSize: 13, cursor: 'pointer', color: '#475569', fontWeight: 600, fontFamily: 'inherit' }}>إلغاء</button>
                        <button type="submit" disabled={processing} style={{ padding: '10px 26px', borderRadius: 11, border: 'none', fontFamily: 'inherit', background: processing ? '#94A3B8' : 'linear-gradient(135deg,#0F766E,#134E4A)', color: '#fff', fontSize: 13, fontWeight: 700, cursor: processing ? 'not-allowed' : 'pointer', display: 'inline-flex', alignItems: 'center', gap: 8, boxShadow: processing ? 'none' : '0 4px 16px rgba(13,148,136,.35)' }}>
                            {processing ? <><i className="ti ti-loader-2" style={{ fontSize: 14, animation: 'spin .8s linear infinite' }} /> جاري الحفظ...</> : <><i className="ti ti-check" style={{ fontSize: 14 }} /> حفظ التعديلات</>}
                        </button>
                    </div>
                </ServiceForm>
            </form>
        </ModalShell>
    );
}

function ServiceCard({ service, onEdit }) {
    const [hover, setHover]    = useState(false);
    const [confirmDel, setDel] = useState(false);
    const [imgErr, setImgErr]  = useState(false);

    const rawStatus = !service.is_active ? 'inactive' : (service.status ?? 'approved');
    const st  = STATUS[rawStatus] ?? STATUS.pending;
    const img = (!imgErr && service.image)
        ? (service.image.startsWith('http') ? service.image : `/storage/${service.image}`)
        : null;

    const del = () => {
        if (confirmDel) router.delete(`/user/my-services/${service.id}`, { preserveScroll: true });
        else { setDel(true); setTimeout(() => setDel(false), 3000); }
    };

    return (
        <div
            onMouseEnter={() => setHover(true)}
            onMouseLeave={() => setHover(false)}
            style={{
                background: '#fff', borderRadius: 18, overflow: 'hidden',
                display: 'flex', flexDirection: 'column',
                border: `1px solid ${hover ? '#99F6E4' : '#F0F4F8'}`,
                boxShadow: hover ? '0 12px 36px rgba(13,148,136,.12)' : '0 2px 8px rgba(0,0,0,0.04)',
                transition: 'border-color .22s ease, box-shadow .22s ease',
            }}>

            {/* Image */}
            <div style={{ width: '100%', height: 168, position: 'relative', overflow: 'hidden', flexShrink: 0 }}>
                {img ? (
                    <img src={img} alt={service.name} onError={() => setImgErr(true)}
                        style={{ width: '100%', height: '100%', objectFit: 'cover', display: 'block', transition: 'transform .4s ease', transform: hover ? 'scale(1.05)' : 'scale(1)' }} />
                ) : (
                    <div style={{ width: '100%', height: '100%', background: 'linear-gradient(135deg,#F0FDFA 0%,#CCFBF1 60%,#A7F3D0 100%)', display: 'flex', flexDirection: 'column', alignItems: 'center', justifyContent: 'center', gap: 8 }}>
                        <div style={{ width: 54, height: 54, borderRadius: '50%', background: 'rgba(13,148,136,.1)', display: 'flex', alignItems: 'center', justifyContent: 'center' }}>
                            <i className="ti ti-tool" style={{ fontSize: 26, color: '#0D9488' }} />
                        </div>
                        <span style={{ fontSize: 11, color: '#5EEAD4', fontWeight: 500 }}>لا توجد صورة</span>
                    </div>
                )}
                <div style={{ position: 'absolute', bottom: 0, insetInline: 0, height: 60, background: 'linear-gradient(to top,rgba(0,0,0,0.4),transparent)' }} />
                <div style={{ position: 'absolute', top: 10, right: 10 }}>
                    <span style={{ display: 'inline-flex', alignItems: 'center', gap: 5, fontSize: 11, fontWeight: 700, padding: '4px 11px', borderRadius: 20, background: st.bg, color: st.color, border: `1px solid ${st.border}` }}>
                        <span style={{ width: 6, height: 6, borderRadius: '50%', background: st.dot, flexShrink: 0 }} />
                        {st.label}
                    </span>
                </div>
                <div style={{ position: 'absolute', bottom: 10, left: 10 }}>
                    <span style={{ display: 'inline-flex', alignItems: 'baseline', gap: 3, padding: '4px 11px', borderRadius: 20, background: 'rgba(0,0,0,0.55)', backdropFilter: 'blur(6px)' }}>
                        <span style={{ fontSize: 14, fontWeight: 800, color: '#fff' }}>{Number(service.price).toLocaleString()}</span>
                        <span style={{ fontSize: 10, color: 'rgba(255,255,255,0.75)', fontWeight: 500 }}>{service.price_type === 'syp' ? 'ل.س' : 'USD'}</span>
                    </span>
                </div>
            </div>

            {/* Body */}
            <div style={{ padding: '14px 16px', flex: 1, display: 'flex', flexDirection: 'column', gap: 9 }}>
                <div style={{ fontSize: 14, fontWeight: 700, color: '#0F172A', lineHeight: 1.4, display: '-webkit-box', WebkitLineClamp: 2, WebkitBoxOrient: 'vertical', overflow: 'hidden' }}>
                    {service.name}
                </div>
                <div style={{ display: 'flex', flexWrap: 'wrap', gap: 5 }}>
                    {service.category?.name    && <Chip icon="ti-tag"     text={service.category.name} />}
                    {service.subcategory?.name && <Chip icon="ti-category" text={service.subcategory.name} />}
                    {service.city?.name        && <Chip icon="ti-map-pin"  text={service.city.name} />}
                </div>
                {service.description && (
                    <p style={{ fontSize: 12, color: '#64748B', lineHeight: 1.65, margin: 0, display: '-webkit-box', WebkitLineClamp: 2, WebkitBoxOrient: 'vertical', overflow: 'hidden' }}>
                        {service.description}
                    </p>
                )}
            </div>

            {/* Footer */}
            <div style={{ padding: '10px 14px', borderTop: '1px solid #F8FAFC', display: 'flex', alignItems: 'center', justifyContent: 'space-between', gap: 8 }}>
                <button onClick={() => onEdit(service)} style={{ display: 'inline-flex', alignItems: 'center', gap: 5, padding: '6px 14px', borderRadius: 8, cursor: 'pointer', fontFamily: 'inherit', fontSize: 12, fontWeight: 600, transition: 'all .15s', border: '1px solid rgba(13,148,136,0.3)', background: '#F0FDFA', color: '#0D9488' }}
                    onMouseEnter={e => { e.currentTarget.style.background = '#CCFBF1'; e.currentTarget.style.borderColor = '#0D9488'; }}
                    onMouseLeave={e => { e.currentTarget.style.background = '#F0FDFA'; e.currentTarget.style.borderColor = 'rgba(13,148,136,0.3)'; }}>
                    <i className="ti ti-pencil" style={{ fontSize: 13 }} /> تعديل
                </button>
                <button onClick={del} title={confirmDel ? 'اضغط مجدداً للتأكيد' : 'حذف الخدمة'}
                    style={{ display: 'inline-flex', alignItems: 'center', gap: 5, padding: '6px 12px', borderRadius: 8, cursor: 'pointer', fontFamily: 'inherit', fontSize: 12, fontWeight: 600, transition: 'all .15s', border: `1px solid ${confirmDel ? '#FCA5A5' : '#E2E8F0'}`, background: confirmDel ? '#FEF2F2' : '#F8FAFC', color: confirmDel ? '#B91C1C' : '#94A3B8' }}>
                    <i className="ti ti-trash" style={{ fontSize: 13 }} />
                    {confirmDel ? 'تأكيد الحذف؟' : 'حذف'}
                </button>
            </div>
        </div>
    );
}

function EmptyState({ filter, onAdd }) {
    const isFiltered = filter !== 'all';
    return (
        <div style={{ padding: '64px 24px', textAlign: 'center', background: '#fff', border: '1.5px dashed #E2E8F0', borderRadius: 20 }}>
            <div style={{ width: 76, height: 76, borderRadius: '50%', background: 'linear-gradient(135deg,#F0FDFA,#CCFBF1)', display: 'flex', alignItems: 'center', justifyContent: 'center', margin: '0 auto 18px', boxShadow: '0 4px 18px rgba(13,148,136,.12)' }}>
                <i className={`ti ${isFiltered ? 'ti-filter-off' : 'ti-tool'}`} style={{ fontSize: 32, color: '#0D9488' }} />
            </div>
            <div style={{ fontSize: 16, fontWeight: 800, color: '#0F172A', marginBottom: 8 }}>
                {isFiltered ? 'لا توجد خدمات بهذه الفئة' : 'لا توجد خدمات بعد'}
            </div>
            <div style={{ fontSize: 13, color: '#64748B', marginBottom: 24, maxWidth: 320, margin: '0 auto 24px' }}>
                {isFiltered ? 'جرّب تصفية مختلفة لعرض خدماتك.' : 'أضف خدمتك الأولى وابدأ في استقبال العملاء من المنصة.'}
            </div>
            {!isFiltered && (
                <button onClick={onAdd} style={{ display: 'inline-flex', alignItems: 'center', gap: 8, padding: '12px 28px', border: 'none', borderRadius: 12, cursor: 'pointer', background: 'linear-gradient(135deg,#0D9488,#0F766E)', color: '#fff', fontSize: 13, fontWeight: 700, fontFamily: 'inherit', boxShadow: '0 4px 16px rgba(13,148,136,.35)' }}>
                    <i className="ti ti-plus" style={{ fontSize: 15 }} /> أضف خدمتك الأولى
                </button>
            )}
        </div>
    );
}

export default function MyServices({ services = [], categories = [], subcategories = [], cities = [] }) {
    const { flash } = usePage().props;
    const [filter, setFilter]         = useState('all');
    const [showCreate, setShowCreate] = useState(false);
    const [editService, setEditService] = useState(null);
    const [toast, setToast]           = useState(flash?.success || flash?.error || null);
    const toastType                   = flash?.success ? 'success' : 'error';

    useEffect(() => {
        if (!toast) return;
        const t = setTimeout(() => setToast(null), 4000);
        return () => clearTimeout(t);
    }, [toast]);

    const counts = {
        all:      services.length,
        approved: services.filter(s => s.status === 'approved' && s.is_active).length,
        pending:  services.filter(s => s.status === 'pending').length,
        rejected: services.filter(s => s.status === 'rejected').length,
    };

    const filtered = filter === 'all' ? services : services.filter(s =>
        filter === 'approved' ? (s.status === 'approved' && s.is_active) : s.status === filter
    );

    const TABS = [
        { key: 'all',      icon: 'ti-layout-grid',  label: 'الكل',          color: '#0D9488', cnt: counts.all },
        { key: 'approved', icon: 'ti-circle-check',  label: 'مقبول',        color: '#10B981', cnt: counts.approved },
        { key: 'pending',  icon: 'ti-clock',          label: 'قيد المراجعة', color: '#F59E0B', cnt: counts.pending },
        { key: 'rejected', icon: 'ti-circle-x',       label: 'مرفوض',       color: '#EF4444', cnt: counts.rejected },
    ];

    return (
        <UserLayout title="خدماتي">
            <Head title="خدماتي — Skillify" />
            <style>{`
                @keyframes spin     { to { transform: rotate(360deg); } }
                @keyframes modalIn  { from { opacity:0; transform:scale(.95) translateY(10px); } to { opacity:1; transform:scale(1) translateY(0); } }
                @keyframes slideDown{ from { opacity:0; transform:translate(-50%,-12px); } to { opacity:1; transform:translate(-50%,0); } }
            `}</style>

            {/* Toast */}
            {toast && (
                <div style={{ position: 'fixed', top: 80, left: '50%', transform: 'translateX(-50%)', zIndex: 9998, minWidth: 280, maxWidth: 400, background: toastType === 'success' ? '#ECFDF5' : '#FEF2F2', border: `1px solid ${toastType === 'success' ? '#6EE7B7' : '#FCA5A5'}`, borderRadius: 14, padding: '13px 18px', display: 'flex', alignItems: 'center', gap: 10, boxShadow: '0 8px 32px rgba(0,0,0,0.12)', animation: 'slideDown .3s cubic-bezier(.22,.68,0,1.2)', direction: 'rtl', color: toastType === 'success' ? '#065F46' : '#991B1B', fontSize: 13, fontWeight: 600 }}>
                    <i className={`ti ${toastType === 'success' ? 'ti-circle-check' : 'ti-alert-circle'}`} style={{ fontSize: 17, flexShrink: 0 }} />
                    <span style={{ flex: 1 }}>{toast}</span>
                    <button onClick={() => setToast(null)} style={{ background: 'none', border: 'none', cursor: 'pointer', color: 'inherit', opacity: 0.6, fontSize: 15, padding: 0, display: 'flex' }}><i className="ti ti-x" /></button>
                </div>
            )}

            {/* Header */}
            <div style={{ display: 'flex', alignItems: 'flex-start', justifyContent: 'space-between', flexWrap: 'wrap', gap: 14 }}>
                <div>
                    <div style={{ display: 'flex', alignItems: 'center', gap: 12, marginBottom: 4 }}>
                        <div style={{ width: 40, height: 40, borderRadius: 12, background: 'linear-gradient(135deg,#0D9488,#0F766E)', display: 'flex', alignItems: 'center', justifyContent: 'center', boxShadow: '0 4px 12px rgba(13,148,136,0.3)' }}>
                            <i className="ti ti-tool" style={{ color: '#fff', fontSize: 18 }} />
                        </div>
                        <h1 style={{ fontSize: 22, fontWeight: 800, color: '#0F172A', margin: 0 }}>خدماتي</h1>
                    </div>
                    <p style={{ fontSize: 13, color: '#94A3B8', margin: 0, marginRight: 52 }}>
                        {counts.all === 0 ? 'لم تضف أي خدمات بعد' : `إجمالي ${counts.all} خدمة مدرجة في ملفك`}
                    </p>
                </div>
                <button onClick={() => setShowCreate(true)} style={{ display: 'inline-flex', alignItems: 'center', gap: 9, padding: '12px 24px', border: 'none', borderRadius: 13, cursor: 'pointer', background: 'linear-gradient(135deg,#0D9488,#0F766E)', color: '#fff', fontSize: 13.5, fontWeight: 700, fontFamily: 'inherit', boxShadow: '0 4px 16px rgba(13,148,136,.35)', transition: 'all .18s' }}
                    onMouseEnter={e => { e.currentTarget.style.transform = 'translateY(-2px)'; e.currentTarget.style.boxShadow = '0 8px 24px rgba(13,148,136,.45)'; }}
                    onMouseLeave={e => { e.currentTarget.style.transform = 'translateY(0)'; e.currentTarget.style.boxShadow = '0 4px 16px rgba(13,148,136,.35)'; }}>
                    <i className="ti ti-plus" style={{ fontSize: 16 }} /> إضافة خدمة
                </button>
            </div>

            {/* Stats */}
            {counts.all > 0 && (
                <div style={{ display: 'grid', gridTemplateColumns: 'repeat(auto-fill,minmax(160px,1fr))', gap: 12 }}>
                    <StatCard icon="ti-layout-grid"  label="إجمالي الخدمات" count={counts.all}      color="#0D9488" bg="#F0FDFA" />
                    <StatCard icon="ti-circle-check" label="مقبولة"          count={counts.approved} color="#10B981" bg="#ECFDF5" />
                    <StatCard icon="ti-clock"        label="قيد المراجعة"    count={counts.pending}  color="#F59E0B" bg="#FFFBEB" />
                    <StatCard icon="ti-circle-x"     label="مرفوضة"          count={counts.rejected} color="#EF4444" bg="#FEF2F2" />
                </div>
            )}

            {/* Banners */}
            {counts.pending > 0 && (
                <div style={{ display: 'flex', alignItems: 'center', gap: 10, padding: '13px 18px', borderRadius: 13, background: '#FFFBEB', border: '1px solid #FCD34D', color: '#78350F', fontSize: 13 }}>
                    <i className="ti ti-clock" style={{ fontSize: 18, color: '#D97706', flexShrink: 0 }} />
                    <span><strong>{counts.pending} خدمة قيد المراجعة</strong> — سيقوم المشرف بمعالجتها قريباً.</span>
                </div>
            )}
            {counts.rejected > 0 && (
                <div style={{ display: 'flex', alignItems: 'center', gap: 10, padding: '13px 18px', borderRadius: 13, background: '#FEF2F2', border: '1px solid #FCA5A5', color: '#991B1B', fontSize: 13 }}>
                    <i className="ti ti-circle-x" style={{ fontSize: 18, color: '#EF4444', flexShrink: 0 }} />
                    <span><strong>{counts.rejected} خدمة مرفوضة</strong> — يرجى التواصل مع الدعم للاستفسار.</span>
                </div>
            )}

            {/* Filter Tabs */}
            {counts.all > 0 && (
                <div style={{ display: 'flex', gap: 7, flexWrap: 'wrap' }}>
                    {TABS.map(({ key, icon, label, color, cnt }) => {
                        const active = filter === key;
                        return (
                            <button key={key} onClick={() => setFilter(key)} style={{ display: 'inline-flex', alignItems: 'center', gap: 7, padding: '8px 18px', borderRadius: 24, fontSize: 12.5, fontWeight: 600, cursor: 'pointer', fontFamily: 'inherit', transition: 'all .15s', border: `1.5px solid ${active ? color : '#E2E8F0'}`, background: active ? color : '#fff', color: active ? '#fff' : '#64748B', boxShadow: active ? `0 3px 10px ${color}40` : 'none' }}>
                                <i className={`ti ${icon}`} style={{ fontSize: 13 }} />
                                {label}
                                <span style={{ fontSize: 10.5, fontWeight: 700, padding: '1px 7px', borderRadius: 20, lineHeight: 1.6, background: active ? 'rgba(255,255,255,0.25)' : '#F1F5F9', color: active ? '#fff' : '#64748B' }}>{cnt}</span>
                            </button>
                        );
                    })}
                </div>
            )}

            {/* Grid */}
            {!filtered.length
                ? <EmptyState filter={filter} onAdd={() => setShowCreate(true)} />
                : (
                    <div style={{ display: 'grid', gridTemplateColumns: 'repeat(auto-fill,minmax(272px,1fr))', gap: 18 }}>
                        {filtered.map(s => <ServiceCard key={s.id} service={s} onEdit={setEditService} />)}
                    </div>
                )
            }

            {/* Modals — rendered at page level via createPortal inside ModalShell */}
            {showCreate && (
                <CreateModal categories={categories} subcategories={subcategories} cities={cities} onClose={() => setShowCreate(false)} />
            )}
            {editService && (
                <EditModal service={editService} categories={categories} subcategories={subcategories} cities={cities} onClose={() => setEditService(null)} />
            )}
        </UserLayout>
    );
}
