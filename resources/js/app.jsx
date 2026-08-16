import './bootstrap';
import { createInertiaApp, router } from '@inertiajs/react';
import { createRoot } from 'react-dom/client';
import { useEffect, useState } from 'react';
import PasswordConfirmModal from './Components/PasswordConfirmModal';
import '../css/app.css';

const inertiaDelete = router.delete.bind(router);
router.delete = (url, options = {}) => {
    const isAdminDelete = url.startsWith('/admin/') || url.startsWith('/super-admin/');
    if (!isAdminDelete) {
        return inertiaDelete(url, options);
    }

    if (options.data?.current_password) {
        return inertiaDelete(url, options);
    }

    if (window.__openAdminDelete) {
        return window.__openAdminDelete(url, options);
    }

    const password = window.prompt('أدخل كلمة المرور الحالية لتأكيد الحذف:');
    if (!password) return;

    return inertiaDelete(url, {
        ...options,
        data: {
            ...(options.data ?? {}),
            current_password: password,
        },
    });
};

function AdminDeletePrompt() {
    const [request, setRequest] = useState(null);

    useEffect(() => {
        window.__openAdminDelete = (url, options) => setRequest({ url, options });
        return () => { delete window.__openAdminDelete; };
    }, []);

    const close = () => setRequest(null);
    const confirm = password => new Promise(resolve => {
        if (!request) return resolve();

        const { url, options } = request;
        inertiaDelete(url, {
            ...options,
            data: {
                ...(options.data ?? {}),
                current_password: password,
            },
            onSuccess: visit => {
                options.onSuccess?.(visit);
                setRequest(null);
            },
            onError: errors => {
                options.onError?.(errors);
                setRequest(current => ({ ...current, error: errors?.current_password ?? 'كلمة المرور غير صحيحة.' }));
            },
            onFinish: visit => {
                options.onFinish?.(visit);
                resolve();
            },
        });
    });

    return <PasswordConfirmModal
        open={!!request}
        title="تأكيد حذف العنصر"
        description="سيُحذف هذا العنصر نهائياً. أدخل كلمة المرور الحالية للمتابعة."
        error={request?.error}
        onClose={close}
        onConfirm={confirm}
    />;
}

createInertiaApp({
    resolve: name => {
        const pages = import.meta.glob('./Pages/**/*.jsx', { eager: true });
        return pages[`./Pages/${name}.jsx`];
    },
    setup({ el, App, props }) {
        createRoot(el).render(<><App {...props} /><AdminDeletePrompt /></>);
    },
    progress: {
        color: '#6366f1',
    },
});
