/**
 * TitanPWA — Service Worker
 *
 * Strategies:
 *   • Static assets (JS/CSS/images) → Cache-First
 *   • API / dynamic data            → Network-First with Cache fallback
 *   • HTML navigation               → Network-First with Offline fallback
 *
 * Background Sync tags handled:
 *   titanpwa-sync-queue        — generic offline mutations
 *   titanpwa-sync-checkins     — Aegis check-in events
 *   titanpwa-sync-completions  — job completion events
 *   titanpwa-sync-photos       — photo/evidence uploads
 */

'use strict';

/* ─────────────────────────────────────────────────────────────
 * Constants
 * ───────────────────────────────────────────────────────────── */
const SW_VERSION     = '1.0.0';
const STATIC_CACHE   = 'titanpwa-static-v1';
const DYNAMIC_CACHE  = 'titanpwa-dynamic-v1';
const JOB_CACHE      = 'titanpwa-jobs-v1';
const KNOWN_CACHES   = [STATIC_CACHE, DYNAMIC_CACHE, JOB_CACHE];

/** Assets to pre-cache on install (add compiled CSS/JS hashes here in CI). */
const STATIC_PRECACHE = [
    '/titanpwa/offline',
    '/vendor/titanpwa/css/pwa.css',
    '/vendor/titanpwa/js/pwa-register.js',
    '/vendor/titanpwa/icons/icon-192x192.png',
    '/vendor/titanpwa/icons/icon-512x512.png',
];

/* ─────────────────────────────────────────────────────────────
 * Install — pre-cache static shell
 * ───────────────────────────────────────────────────────────── */
self.addEventListener('install', (event) => {
    event.waitUntil(
        caches.open(STATIC_CACHE)
            .then((cache) => {
                // addAll ignores individual failures so a missing icon doesn't
                // abort the entire install.
                return Promise.allSettled(
                    STATIC_PRECACHE.map((url) => cache.add(url).catch(() => null))
                );
            })
            .then(() => self.skipWaiting())
    );
});

/* ─────────────────────────────────────────────────────────────
 * Activate — prune stale caches, claim clients
 * ───────────────────────────────────────────────────────────── */
self.addEventListener('activate', (event) => {
    event.waitUntil(
        caches.keys()
            .then((keys) => Promise.all(
                keys
                    .filter((key) => !KNOWN_CACHES.includes(key))
                    .map((key) => caches.delete(key))
            ))
            .then(() => {
                // Notify all open windows that a new SW is now in control
                return self.clients.matchAll({ includeUncontrolled: true });
            })
            .then((clientList) => {
                clientList.forEach((client) =>
                    client.postMessage({ type: 'SW_ACTIVATED', version: SW_VERSION })
                );
                return self.clients.claim();
            })
    );
});

/* ─────────────────────────────────────────────────────────────
 * Fetch — routing strategies
 * ───────────────────────────────────────────────────────────── */
self.addEventListener('fetch', (event) => {
    const { request } = event;
    const url = new URL(request.url);

    // Ignore non-GET and cross-origin requests
    if (request.method !== 'GET') return;
    if (url.origin !== self.location.origin) return;

    // Ignore browser-extension or chrome-extension requests
    if (!url.protocol.startsWith('http')) return;

    // 1. API / dynamic data → Network-First
    if (url.pathname.startsWith('/api/') || url.pathname.startsWith('/titanpwa/')) {
        event.respondWith(networkFirst(request, DYNAMIC_CACHE));
        return;
    }

    // 2. Job card data paths → Network-First (dedicated cache)
    if (url.pathname.includes('/job-cards') || url.pathname.includes('/jobs/')) {
        event.respondWith(networkFirst(request, JOB_CACHE));
        return;
    }

    // 3. Static assets → Cache-First
    if (/\.(js|css|png|jpg|jpeg|gif|svg|ico|woff2?|ttf|eot|webp)(\?.*)?$/.test(url.pathname)) {
        event.respondWith(cacheFirst(request, STATIC_CACHE));
        return;
    }

    // 4. HTML navigation → Network-First with offline fallback
    if (request.headers.get('Accept')?.includes('text/html')) {
        event.respondWith(networkFirstWithOfflineFallback(request));
        return;
    }

    // 5. Everything else → Network-First
    event.respondWith(networkFirst(request, DYNAMIC_CACHE));
});

/* ─────────────────────────────────────────────────────────────
 * Background Sync
 * ───────────────────────────────────────────────────────────── */
self.addEventListener('sync', (event) => {
    const tagMap = {
        'titanpwa-sync-queue':       null,          // all types
        'titanpwa-sync-checkins':    'check-in',
        'titanpwa-sync-completions': 'completion',
        'titanpwa-sync-photos':      'photo',
    };

    if (event.tag in tagMap) {
        event.waitUntil(processSyncQueue(tagMap[event.tag]));
    }
});

/* ─────────────────────────────────────────────────────────────
 * Push Notifications
 * ───────────────────────────────────────────────────────────── */
self.addEventListener('push', (event) => {
    let data = {};
    try {
        data = event.data ? event.data.json() : {};
    } catch (_) {
        data = { title: 'CleanSmartOS', body: event.data ? event.data.text() : '' };
    }

    const title   = data.title   || 'CleanSmartOS';
    const options = {
        body:    data.body    || 'You have a new notification.',
        icon:    data.icon    || '/vendor/titanpwa/icons/icon-192x192.png',
        badge:   data.badge   || '/vendor/titanpwa/icons/icon-96x96.png',
        data:    data.data    || { url: '/' },
        actions: data.actions || [],
        vibrate: [200, 100, 200],
        tag:     data.tag     || 'titanpwa-notification',
        renotify: true,
    };

    event.waitUntil(self.registration.showNotification(title, options));
});

self.addEventListener('notificationclick', (event) => {
    event.notification.close();

    const targetUrl = (event.notification.data && event.notification.data.url) ? event.notification.data.url : '/';

    event.waitUntil(
        self.clients.matchAll({ type: 'window', includeUncontrolled: true })
            .then((clientList) => {
                for (const client of clientList) {
                    if (client.url === targetUrl && 'focus' in client) {
                        return client.focus();
                    }
                }
                return self.clients.openWindow(targetUrl);
            })
    );
});

/* ─────────────────────────────────────────────────────────────
 * Message — SKIP_WAITING (for update prompt)
 * ───────────────────────────────────────────────────────────── */
self.addEventListener('message', (event) => {
    if (event.data && event.data.type === 'SKIP_WAITING') {
        self.skipWaiting();
    }
});

/* ─────────────────────────────────────────────────────────────
 * Fetch strategy helpers
 * ───────────────────────────────────────────────────────────── */

async function cacheFirst(request, cacheName) {
    const cached = await caches.match(request);
    if (cached) return cached;

    try {
        const response = await fetch(request);
        if (response.ok) {
            const cache = await caches.open(cacheName);
            cache.put(request, response.clone());
        }
        return response;
    } catch (_) {
        return new Response('Offline — asset unavailable', { status: 503 });
    }
}

async function networkFirst(request, cacheName) {
    try {
        const response = await fetch(request);
        if (response.ok) {
            const cache = await caches.open(cacheName);
            cache.put(request, response.clone());
        }
        return response;
    } catch (_) {
        const cached = await caches.match(request);
        if (cached) return cached;
        return new Response(
            JSON.stringify({ error: 'Offline', offline: true }),
            { status: 503, headers: { 'Content-Type': 'application/json' } }
        );
    }
}

async function networkFirstWithOfflineFallback(request) {
    try {
        const response = await fetch(request);
        if (response.ok) {
            const cache = await caches.open(DYNAMIC_CACHE);
            cache.put(request, response.clone());
        }
        return response;
    } catch (_) {
        const cached = await caches.match(request);
        if (cached) return cached;

        // Serve the pre-cached offline page
        const offlinePage = await caches.match('/titanpwa/offline');
        if (offlinePage) return offlinePage;

        return new Response(
            '<!doctype html><html><body><h1>You are offline</h1><p>Please reconnect to use CleanSmartOS.</p></body></html>',
            { status: 503, headers: { 'Content-Type': 'text/html' } }
        );
    }
}

/* ─────────────────────────────────────────────────────────────
 * Background Sync — IndexedDB helpers
 * ───────────────────────────────────────────────────────────── */

async function processSyncQueue(type) {
    let db;
    try {
        db = await openDatabase();
    } catch (_) {
        return; // IDB not available; nothing to do
    }

    const items = await getQueuedItems(db, type);

    for (const item of items) {
        try {
            const fetchOptions = {
                method: item.method || 'POST',
                headers: Object.assign({ 'Content-Type': 'application/json' }, item.headers || {}),
            };
            if (item.body) {
                fetchOptions.body = typeof item.body === 'string' ? item.body : JSON.stringify(item.body);
            }

            const response = await fetch(item.url, fetchOptions);

            if (response.ok) {
                await removeQueuedItem(db, item.id);

                // Notify all open windows
                const clientList = await self.clients.matchAll();
                clientList.forEach((client) =>
                    client.postMessage({ type: 'SYNC_COMPLETED', itemId: item.id, itemType: item.type })
                );
            }
        } catch (_) {
            // Item will be retried on the next sync event
        }
    }
}

function openDatabase() {
    return new Promise((resolve, reject) => {
        const req = indexedDB.open('titanpwa-db', 1);

        req.onupgradeneeded = (e) => {
            const db = e.target.result;

            if (!db.objectStoreNames.contains('sync-queue')) {
                const store = db.createObjectStore('sync-queue', { keyPath: 'id', autoIncrement: true });
                store.createIndex('type',   'type',   { unique: false });
                store.createIndex('status', 'status', { unique: false });
            }

            if (!db.objectStoreNames.contains('job-cards')) {
                const store = db.createObjectStore('job-cards', { keyPath: 'id' });
                store.createIndex('date',   'date',   { unique: false });
                store.createIndex('status', 'status', { unique: false });
            }
        };

        req.onsuccess = (e) => resolve(e.target.result);
        req.onerror   = (e) => reject(e.target.error);
    });
}

function getQueuedItems(db, type) {
    return new Promise((resolve, reject) => {
        const tx    = db.transaction('sync-queue', 'readonly');
        const store = tx.objectStore('sync-queue');
        const req   = type ? store.index('type').getAll(type) : store.getAll();
        req.onsuccess = (e) => resolve(e.target.result || []);
        req.onerror   = (e) => reject(e.target.error);
    });
}

function removeQueuedItem(db, id) {
    return new Promise((resolve, reject) => {
        const tx    = db.transaction('sync-queue', 'readwrite');
        const store = tx.objectStore('sync-queue');
        const req   = store.delete(id);
        req.onsuccess = () => resolve();
        req.onerror   = (e) => reject(e.target.error);
    });
}
