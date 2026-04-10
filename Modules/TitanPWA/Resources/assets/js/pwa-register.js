/**
 * TitanPWA — Client-side registration & UI
 *
 * Responsibilities:
 *   1. Register the service worker
 *   2. Detect SW updates and prompt user to refresh
 *   3. Intercept beforeinstallprompt and show custom install banner
 *   4. Maintain online/offline indicator and queue-depth counter
 *   5. Expose titanPWAQueue helper (IndexedDB sync queue)
 *   6. Expose titanPWAPush helper (Web Push subscription)
 */
(function (global) {
    'use strict';

    const SW_URL    = '/titanpwa-sw.js';
    const SW_SCOPE  = '/';
    const IDB_NAME  = 'titanpwa-db';
    const IDB_VER   = 1;

    let deferredInstallPrompt = null;
    let swRegistration        = null;

    /* ───────────────────────────────────────────────────────────
     * 1. Service Worker Registration
     * ─────────────────────────────────────────────────────────── */
    if ('serviceWorker' in navigator) {
        global.addEventListener('load', function () {
            navigator.serviceWorker
                .register(SW_URL, { scope: SW_SCOPE })
                .then(function (registration) {
                    swRegistration = registration;
                    listenForUpdates(registration);
                })
                .catch(function (err) {
                    console.error('[TitanPWA] SW registration failed:', err);
                });

            navigator.serviceWorker.addEventListener('message', onSwMessage);

            // When a new SW takes control, reload to get the fresh shell
            navigator.serviceWorker.addEventListener('controllerchange', function () {
                if (global._titanPWAReloading) return;
                global._titanPWAReloading = true;
                global.location.reload();
            });
        });
    }

    /* ───────────────────────────────────────────────────────────
     * 2. Update detection
     * ─────────────────────────────────────────────────────────── */
    function listenForUpdates(registration) {
        registration.addEventListener('updatefound', function () {
            var newWorker = registration.installing;
            if (!newWorker) return;

            newWorker.addEventListener('statechange', function () {
                if (newWorker.state === 'installed' && navigator.serviceWorker.controller) {
                    showUpdateBanner(registration);
                }
            });
        });
    }

    function showUpdateBanner(registration) {
        var banner = document.getElementById('titanpwa-update-banner');
        if (!banner) return;
        banner.classList.remove('titanpwa-hidden');

        var btn = banner.querySelector('.titanpwa-update-refresh');
        if (btn) {
            btn.addEventListener('click', function () {
                if (registration.waiting) {
                    registration.waiting.postMessage({ type: 'SKIP_WAITING' });
                }
            });
        }
    }

    /* ───────────────────────────────────────────────────────────
     * 3. Install Prompt (Add to Home Screen)
     * ─────────────────────────────────────────────────────────── */
    global.addEventListener('beforeinstallprompt', function (e) {
        e.preventDefault();
        deferredInstallPrompt = e;

        if (!localStorage.getItem('titanpwa-installed') && !localStorage.getItem('titanpwa-dismiss-install')) {
            // Delay banner by 3 s to avoid showing it before the user has
            // had any meaningful interaction with the app.
            setTimeout(showInstallBanner, 3000);
        }
    });

    global.addEventListener('appinstalled', function () {
        deferredInstallPrompt = null;
        localStorage.setItem('titanpwa-installed', '1');
        hideInstallBanner();
    });

    function showInstallBanner() {
        var banner = document.getElementById('titanpwa-install-banner');
        if (banner) banner.classList.remove('titanpwa-hidden');
    }

    function hideInstallBanner() {
        var banner = document.getElementById('titanpwa-install-banner');
        if (banner) banner.classList.add('titanpwa-hidden');
    }

    /** Public: call from your own "Install" button */
    global.titanPWAInstall = function () {
        if (!deferredInstallPrompt) return;
        deferredInstallPrompt.prompt();
        deferredInstallPrompt.userChoice.then(function (choice) {
            if (choice.outcome === 'accepted') {
                localStorage.setItem('titanpwa-installed', '1');
            }
            deferredInstallPrompt = null;
            hideInstallBanner();
        });
    };

    /* ───────────────────────────────────────────────────────────
     * 4. Online / Offline indicator
     * ─────────────────────────────────────────────────────────── */
    function updateConnectionUI() {
        var offlineBanner = document.getElementById('titanpwa-offline-banner');
        var statusDot     = document.getElementById('titanpwa-online-indicator');

        if (navigator.onLine) {
            if (offlineBanner) offlineBanner.classList.add('titanpwa-hidden');
            if (statusDot) {
                statusDot.classList.remove('titanpwa-offline');
                statusDot.classList.add('titanpwa-online');
                statusDot.setAttribute('title', 'Online');
            }
            // Attempt to flush queue when connectivity is restored
            flushSyncQueue();
        } else {
            if (offlineBanner) offlineBanner.classList.remove('titanpwa-hidden');
            if (statusDot) {
                statusDot.classList.remove('titanpwa-online');
                statusDot.classList.add('titanpwa-offline');
                statusDot.setAttribute('title', 'Offline');
            }
        }

        refreshQueueDepthUI();
    }

    global.addEventListener('online',  updateConnectionUI);
    global.addEventListener('offline', updateConnectionUI);
    document.addEventListener('DOMContentLoaded', updateConnectionUI);

    /* ───────────────────────────────────────────────────────────
     * Queue depth counter
     * ─────────────────────────────────────────────────────────── */
    function refreshQueueDepthUI() {
        if (!global.indexedDB) return;

        openIDB().then(function (db) {
            if (!db.objectStoreNames.contains('sync-queue')) return;
            var tx    = db.transaction('sync-queue', 'readonly');
            var store = tx.objectStore('sync-queue');
            var req   = store.count();
            req.onsuccess = function () {
                var count = req.result;

                var depthEl = document.getElementById('titanpwa-queue-depth');
                if (depthEl) depthEl.textContent = count;

                var banner = document.getElementById('titanpwa-offline-banner');
                if (banner) {
                    var qEl = banner.querySelector('.titanpwa-queue-count');
                    if (qEl) qEl.textContent = count;

                    // Show/hide queue badge based on count
                    var badge = banner.querySelector('.titanpwa-queue-badge');
                    if (badge) {
                        badge.style.display = count > 0 ? '' : 'none';
                    }
                }
            };
        }).catch(function () { /* ignore */ });
    }

    /* ───────────────────────────────────────────────────────────
     * SW message handler
     * ─────────────────────────────────────────────────────────── */
    function onSwMessage(event) {
        if (!event.data) return;
        switch (event.data.type) {
            case 'SW_ACTIVATED':
                break;
            case 'SYNC_COMPLETED':
                refreshQueueDepthUI();
                break;
        }
    }

    /* ───────────────────────────────────────────────────────────
     * 5. Background Sync Queue helper
     * ─────────────────────────────────────────────────────────── */
    global.titanPWAQueue = {
        /**
         * Add an offline mutation to the queue.
         *
         * @param {Object} item  { url, method, headers, body, type }
         */
        add: function (item) {
            return openIDB().then(function (db) {
                return new Promise(function (resolve, reject) {
                    var tx    = db.transaction('sync-queue', 'readwrite');
                    var store = tx.objectStore('sync-queue');
                    var req   = store.add(Object.assign({}, item, {
                        status:    'pending',
                        createdAt: Date.now(),
                    }));
                    req.onsuccess = function () {
                        resolve(req.result);
                        refreshQueueDepthUI();

                        // Register background sync if supported
                        if ('serviceWorker' in navigator && 'SyncManager' in global) {
                            navigator.serviceWorker.ready.then(function (reg) {
                                reg.sync.register('titanpwa-sync-queue').catch(function () { /* ignore */ });
                            });
                        }
                    };
                    req.onerror = function () { reject(req.error); };
                });
            });
        },

        /** Returns the count of pending items */
        count: function () {
            return openIDB().then(function (db) {
                return new Promise(function (resolve, reject) {
                    var tx    = db.transaction('sync-queue', 'readonly');
                    var store = tx.objectStore('sync-queue');
                    var req   = store.count();
                    req.onsuccess = function () { resolve(req.result); };
                    req.onerror   = function () { reject(req.error); };
                });
            });
        },

        /** Attempt to flush the queue by POSTing to the server */
        flush: flushSyncQueue,
    };

    function flushSyncQueue() {
        if (!navigator.onLine) return;
        if (!global.indexedDB) return;

        openIDB().then(function (db) {
            var tx    = db.transaction('sync-queue', 'readonly');
            var store = tx.objectStore('sync-queue');
            var req   = store.getAll();

            req.onsuccess = function () {
                var items = req.result || [];
                items.forEach(function (item) {
                    var fetchOpts = {
                        method:  item.method || 'POST',
                        headers: Object.assign({ 'Content-Type': 'application/json' }, item.headers || {}),
                    };
                    if (item.body) {
                        fetchOpts.body = typeof item.body === 'string' ? item.body : JSON.stringify(item.body);
                    }

                    fetch(item.url, fetchOpts)
                        .then(function (resp) {
                            if (resp.ok) {
                                openIDB().then(function (db2) {
                                    var tx2 = db2.transaction('sync-queue', 'readwrite');
                                    tx2.objectStore('sync-queue').delete(item.id);
                                    refreshQueueDepthUI();
                                });
                            }
                        })
                        .catch(function () { /* will retry */ });
                });
            };
        }).catch(function () { /* ignore */ });
    }

    /* ───────────────────────────────────────────────────────────
     * Job Card offline caching helpers
     * ─────────────────────────────────────────────────────────── */
    global.titanPWAJobs = {
        /**
         * Cache a job card in IndexedDB for offline access.
         * @param {Object} jobCard  { id, date, status, checklist, address, notes, supplies, ... }
         */
        cache: function (jobCard) {
            return openIDB().then(function (db) {
                return new Promise(function (resolve, reject) {
                    var tx  = db.transaction('job-cards', 'readwrite');
                    var req = tx.objectStore('job-cards').put(jobCard);
                    req.onsuccess = function () { resolve(req.result); };
                    req.onerror   = function () { reject(req.error); };
                });
            });
        },

        /** Retrieve a job card by id from the local cache */
        get: function (id) {
            return openIDB().then(function (db) {
                return new Promise(function (resolve, reject) {
                    var tx  = db.transaction('job-cards', 'readonly');
                    var req = tx.objectStore('job-cards').get(id);
                    req.onsuccess = function () { resolve(req.result || null); };
                    req.onerror   = function () { reject(req.error); };
                });
            });
        },

        /** List all cached job cards */
        all: function () {
            return openIDB().then(function (db) {
                return new Promise(function (resolve, reject) {
                    var tx  = db.transaction('job-cards', 'readonly');
                    var req = tx.objectStore('job-cards').getAll();
                    req.onsuccess = function () { resolve(req.result || []); };
                    req.onerror   = function () { reject(req.error); };
                });
            });
        },
    };

    /* ───────────────────────────────────────────────────────────
     * 6. Web Push subscription helper
     * ─────────────────────────────────────────────────────────── */
    global.titanPWAPush = {
        /**
         * Subscribe to push notifications.
         * Requests notification permission, then sends the subscription to
         * /api/titanpwa/push/subscribe.
         *
         * @param {string} vapidPublicKey  Base64url-encoded VAPID public key
         * @returns {Promise<PushSubscription|null>}
         */
        subscribe: function (vapidPublicKey) {
            if (!('PushManager' in global)) {
                console.warn('[TitanPWA] Push notifications not supported in this browser.');
                return Promise.resolve(null);
            }

            return Notification.requestPermission().then(function (permission) {
                if (permission !== 'granted') {
                    console.warn('[TitanPWA] Push permission denied.');
                    return null;
                }

                return navigator.serviceWorker.ready;
            }).then(function (registration) {
                if (!registration) return null;

                return registration.pushManager.subscribe({
                    userVisibleOnly:      true,
                    applicationServerKey: urlBase64ToUint8Array(vapidPublicKey),
                });
            }).then(function (subscription) {
                if (!subscription) return null;

                var csrfToken = (document.querySelector('meta[name="csrf-token"]') || {}).content || '';

                return fetch('/api/titanpwa/push/subscribe', {
                    method:  'POST',
                    headers: {
                        'Content-Type':  'application/json',
                        'X-CSRF-TOKEN':  csrfToken,
                    },
                    body: JSON.stringify({ subscription: subscription }),
                }).then(function () { return subscription; });
            }).catch(function (err) {
                console.error('[TitanPWA] Push subscription failed:', err);
                return null;
            });
        },

        /**
         * Unsubscribe from push notifications.
         */
        unsubscribe: function () {
            return navigator.serviceWorker.ready
                .then(function (reg) { return reg.pushManager.getSubscription(); })
                .then(function (subscription) {
                    if (!subscription) return;

                    var csrfToken = (document.querySelector('meta[name="csrf-token"]') || {}).content || '';
                    var endpoint  = subscription.endpoint;

                    return subscription.unsubscribe().then(function () {
                        return fetch('/api/titanpwa/push/unsubscribe', {
                            method:  'DELETE',
                            headers: {
                                'Content-Type':  'application/json',
                                'X-CSRF-TOKEN':  csrfToken,
                            },
                            body: JSON.stringify({ endpoint: endpoint }),
                        });
                    });
                });
        },
    };

    /* ───────────────────────────────────────────────────────────
     * IndexedDB helper
     * ─────────────────────────────────────────────────────────── */
    function openIDB() {
        return new Promise(function (resolve, reject) {
            if (!global.indexedDB) return reject(new Error('IndexedDB not supported'));

            var req = global.indexedDB.open(IDB_NAME, IDB_VER);

            req.onupgradeneeded = function (e) {
                var db = e.target.result;

                if (!db.objectStoreNames.contains('sync-queue')) {
                    var sq = db.createObjectStore('sync-queue', { keyPath: 'id', autoIncrement: true });
                    sq.createIndex('type',   'type',   { unique: false });
                    sq.createIndex('status', 'status', { unique: false });
                }

                if (!db.objectStoreNames.contains('job-cards')) {
                    var jc = db.createObjectStore('job-cards', { keyPath: 'id' });
                    jc.createIndex('date',   'date',   { unique: false });
                    jc.createIndex('status', 'status', { unique: false });
                }
            };

            req.onsuccess = function (e) { resolve(e.target.result); };
            req.onerror   = function (e) { reject(e.target.error); };
        });
    }

    /* ───────────────────────────────────────────────────────────
     * Utility — convert VAPID public key to Uint8Array
     * ─────────────────────────────────────────────────────────── */
    function urlBase64ToUint8Array(base64String) {
        var padding = '='.repeat((4 - (base64String.length % 4)) % 4);
        var base64  = (base64String + padding).replace(/-/g, '+').replace(/_/g, '/');
        var rawData = atob(base64);
        var output  = new Uint8Array(rawData.length);
        for (var i = 0; i < rawData.length; ++i) {
            output[i] = rawData.charCodeAt(i);
        }
        return output;
    }

}(window));
