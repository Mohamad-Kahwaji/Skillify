// Skillify Service Worker — handles FCM background push notifications

self.addEventListener('install', () => self.skipWaiting());
self.addEventListener('activate', e => e.waitUntil(self.clients.claim()));

// Handle push events sent by FCM
self.addEventListener('push', function (event) {
    if (!event.data) return;

    let payload;
    try { payload = event.data.json(); } catch { payload = { notification: { title: 'Skillify', body: event.data.text() } }; }

    const { title, body, icon, click_action } = payload.notification ?? {};

    event.waitUntil(
        self.registration.showNotification(title ?? 'Skillify', {
            body:  body  ?? '',
            icon:  icon  ?? '/favicon.ico',
            badge: '/favicon.ico',
            data:  { url: click_action ?? '/user/notifications' },
            tag:   'skillify-push',
        })
    );
});

// Open the notification URL when the user clicks
self.addEventListener('notificationclick', function (event) {
    event.notification.close();
    const url = event.notification.data?.url ?? '/user/notifications';
    event.waitUntil(
        self.clients.matchAll({ type: 'window', includeUncontrolled: true }).then(clients => {
            const existing = clients.find(c => c.url.includes(new URL(url, self.location.origin).pathname));
            if (existing) return existing.focus();
            return self.clients.openWindow(url);
        })
    );
});
