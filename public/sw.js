const CACHE = 'clubops-v1';
const URLS = [
    '/dashboard',
    '/manifest.json',
    '/icons/icon-192.png',
    '/icons/icon-512.png',
    '/icons/icon.svg',
    '/offline',
];

self.addEventListener('install', (e) => {
    e.waitUntil(caches.open(CACHE).then((c) => c.addAll(URLS)));
    self.skipWaiting();
});

self.addEventListener('activate', (e) => {
    e.waitUntil(
        caches.keys().then((keys) =>
            Promise.all(keys.filter((k) => k !== CACHE).map((k) => caches.delete(k)))
        )
    );
    self.clients.claim();
});

self.addEventListener('fetch', (e) => {
    // Network-first for HTML pages, cache-first for static
    if (e.request.mode === 'navigate') {
        e.respondWith(
            fetch(e.request).catch(() => caches.match('/offline'))
        );
    } else if (e.request.url.match(/\.(png|jpg|svg|ico|json)$/)) {
        e.respondWith(
            caches.match(e.request).then((r) => r || fetch(e.request))
        );
    }
});
