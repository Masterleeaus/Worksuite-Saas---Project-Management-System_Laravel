/**
 * live-tracking.js
 *
 * Sends periodic GPS pings to the server while a cleaner is checked in to
 * an active job (live tracking / dispatch map feed).
 *
 * Usage:
 *   import LiveTracking from './live-tracking.js';
 *   const tracker = new LiveTracking({ apiBase: '/api/v1/gps', bookingId: '123' });
 *   tracker.start();   // begin periodic pings
 *   tracker.stop();    // stop on check-out or page unload
 */

'use strict';

export default class LiveTracking {
    /**
     * @param {Object}   opts
     * @param {string}   opts.apiBase            – e.g. '/api/v1/gps'
     * @param {string}   [opts.bookingId]        – Active booking / job-card ID
     * @param {number}   [opts.intervalSeconds]  – Override server-configured interval
     * @param {string}   [opts.csrfToken]        – CSRF token for POST requests
     * @param {boolean}  [opts.recordRoute]      – Whether to also buffer route points
     * @param {Function} [opts.onPing]           – Callback after each successful ping
     * @param {Function} [opts.onError]          – Callback on error
     */
    constructor(opts = {}) {
        this.apiBase         = opts.apiBase          || '/api/v1/gps';
        this.bookingId       = opts.bookingId        || null;
        this.intervalSeconds = opts.intervalSeconds  || 60;
        this.csrfToken       = opts.csrfToken        || document.querySelector('meta[name="csrf-token"]')?.content || '';
        this.recordRoute     = opts.recordRoute      !== undefined ? opts.recordRoute : false;
        this.onPing          = opts.onPing           || (() => {});
        this.onError         = opts.onError          || console.error;

        this._timer      = null;
        this._routeBuf   = [];
        this._routeSeq   = 0;

        // Flush route buffer every N pings
        this._ROUTE_FLUSH_EVERY = 5;
        this._pingCount = 0;

        // Consent level stored in localStorage
        this._CONSENT_KEY = 'gps_tracking_consent_v1';
    }

    // ── Public API ──────────────────────────────────────────────────────────

    /**
     * Request background location permission (if needed) then start pinging.
     */
    async start() {
        if (!('geolocation' in navigator)) {
            this.onError('GPS not available.');
            return;
        }

        if (!this._getConsent()) {
            const agreed = await this._showTrackingConsentDialog();
            if (!agreed) {
                this.onError('Live tracking consent denied.');
                return;
            }
            this._saveConsent('background');
        }

        this._scheduleNextPing();
    }

    /**
     * Stop tracking (call on check-out).
     */
    async stop() {
        if (this._timer) {
            clearTimeout(this._timer);
            this._timer = null;
        }

        // Flush any remaining route points
        if (this.recordRoute && this._routeBuf.length) {
            await this._flushRouteBuffer().catch(() => {});
        }
    }

    // ── Private helpers ─────────────────────────────────────────────────────

    _scheduleNextPing() {
        this._timer = setTimeout(() => this._ping(), this.intervalSeconds * 1000);
    }

    async _ping() {
        try {
            const pos = await this._getCurrentPosition();
            const { latitude, longitude, accuracy, speed, heading } = pos.coords;

            const payload = {
                lat:        latitude,
                lng:        longitude,
                accuracy:   accuracy,
                speed:      speed   !== null ? speed   : undefined,
                heading:    heading !== null ? heading : undefined,
                booking_id: this.bookingId,
                recorded_at: new Date().toISOString(),
            };

            const res = await this._post('/location-ping', payload);
            this._pingCount++;

            // Respect server-configured interval override
            if (res.ping_interval_seconds && res.ping_interval_seconds !== this.intervalSeconds) {
                this.intervalSeconds = res.ping_interval_seconds;
            }

            // Optionally buffer for route recording
            if (this.recordRoute) {
                this._routeBuf.push({
                    lat:         latitude,
                    lng:         longitude,
                    accuracy:    accuracy,
                    sequence:    this._routeSeq++,
                    recorded_at: payload.recorded_at,
                });

                if (this._pingCount % this._ROUTE_FLUSH_EVERY === 0) {
                    await this._flushRouteBuffer().catch(() => {});
                }
            }

            this.onPing({ lat: latitude, lng: longitude, accuracy });

        } catch (err) {
            this.onError(err);
        } finally {
            // Always reschedule unless stop() was called
            if (this._timer !== null || this._pingCount === 0) {
                this._scheduleNextPing();
            }
        }
    }

    async _flushRouteBuffer() {
        if (!this._routeBuf.length) return;

        const points = this._routeBuf.splice(0);  // drain buffer

        await this._post('/route-points', {
            booking_id: this.bookingId,
            points,
        });
    }

    _getCurrentPosition() {
        return new Promise((resolve, reject) => {
            navigator.geolocation.getCurrentPosition(resolve, reject, {
                enableHighAccuracy: true,
                maximumAge:         20000,
                timeout:            10000,
            });
        });
    }

    async _post(path, body) {
        const res = await fetch(this.apiBase + path, {
            method:  'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept':       'application/json',
                'X-CSRF-TOKEN': this.csrfToken,
            },
            body: JSON.stringify(body),
        });

        if (!res.ok) {
            const err = await res.json().catch(() => ({}));
            throw new Error(err.message || `HTTP ${res.status}`);
        }

        return res.json();
    }

    // ── Consent helpers ─────────────────────────────────────────────────────

    _getConsent()        { return localStorage.getItem(this._CONSENT_KEY); }
    _saveConsent(level)  { localStorage.setItem(this._CONSENT_KEY, level); }

    async _showTrackingConsentDialog() {
        return new Promise((resolve) => {
            if (typeof HTMLDialogElement === 'undefined') {
                resolve(window.confirm(
                    'This app would like to track your location in the background while you are ' +
                    'checked in to a job, so dispatchers can see your position on the map.\n\n' +
                    'You can disable this at any time in Settings.\n\n' +
                    'Tap OK to allow background tracking.'
                ));
                return;
            }

            const existing = document.getElementById('gps-track-dialog');
            if (existing) existing.remove();

            const dialog = document.createElement('dialog');
            dialog.id = 'gps-track-dialog';
            dialog.setAttribute('aria-modal', 'true');
            dialog.setAttribute('aria-labelledby', 'gps-track-title');
            dialog.style.cssText = 'border:none;border-radius:12px;padding:24px;max-width:380px;width:90%;box-shadow:0 8px 32px rgba(0,0,0,.2);';
            dialog.innerHTML = `
                <h2 id="gps-track-title" style="font-size:1.1rem;margin-bottom:12px;">🗺️ Background Location Tracking</h2>
                <p style="font-size:.9rem;color:#555;margin-bottom:16px;">
                    While you are checked in to a job, this app would like to periodically send your
                    GPS location to your organisation's dispatch system so supervisors can see live
                    cleaner positions.
                </p>
                <p style="font-size:.85rem;color:#777;margin-bottom:20px;">
                    <strong>You can opt out at any time</strong> in the app settings.
                    Location data is retained according to your organisation's privacy policy and
                    in compliance with the Australian Privacy Act.
                </p>
                <div style="display:flex;gap:10px;justify-content:flex-end;">
                    <button id="gps-track-deny"  style="padding:8px 18px;border:1px solid #ccc;background:#fff;border-radius:6px;cursor:pointer;">No thanks</button>
                    <button id="gps-track-allow" style="padding:8px 18px;background:#0d6efd;color:#fff;border:none;border-radius:6px;cursor:pointer;">Allow Tracking</button>
                </div>`;

            document.body.appendChild(dialog);
            dialog.showModal();

            dialog.querySelector('#gps-track-allow').addEventListener('click', () => {
                dialog.close(); dialog.remove(); resolve(true);
            });
            dialog.querySelector('#gps-track-deny').addEventListener('click', () => {
                dialog.close(); dialog.remove(); resolve(false);
            });
        });
    }
}
