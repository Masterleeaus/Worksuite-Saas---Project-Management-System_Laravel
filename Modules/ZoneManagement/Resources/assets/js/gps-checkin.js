/**
 * gps-checkin.js
 *
 * Handles geofenced GPS check-in / check-out for the PWA field app.
 *
 * Usage:
 *   import GpsCheckIn from './gps-checkin.js';
 *   const checkin = new GpsCheckIn({ apiBase: '/api/v1/gps', zoneId: 'abc', bookingId: '123' });
 *   checkin.init();          // request permission, start distance polling
 *   checkin.checkIn();       // called when user taps the Check-In button
 *   checkin.checkOut();      // called when user taps the Check-Out button
 */

'use strict';

export default class GpsCheckIn {
    /**
     * @param {Object}   opts
     * @param {string}   opts.apiBase         – e.g. '/api/v1/gps'
     * @param {string}   opts.zoneId          – Zone UUID
     * @param {string}   [opts.bookingId]     – Optional booking / job-card ID
     * @param {Function} [opts.onStatusChange]– Callback({canCheckIn, distanceM, radiusM, message})
     * @param {string}   [opts.csrfToken]     – CSRF token for POST requests
     */
    constructor(opts = {}) {
        this.apiBase       = opts.apiBase       || '/api/v1/gps';
        this.zoneId        = opts.zoneId        || null;
        this.bookingId     = opts.bookingId     || null;
        this.onStatusChange= opts.onStatusChange || (() => {});
        this.csrfToken     = opts.csrfToken     || document.querySelector('meta[name="csrf-token"]')?.content || '';

        this._checkInId    = null;
        this._watchId      = null;
        this._position     = null;

        // Consent flags stored in localStorage
        this._CONSENT_KEY  = 'gps_checkin_consent_v1';
    }

    // ── Public API ──────────────────────────────────────────────────────────

    /**
     * Initialise: request location permission and start watching position.
     * Shows a consent explanation before requesting browser permission.
     */
    async init() {
        if (!('geolocation' in navigator)) {
            this.onStatusChange({ canCheckIn: false, message: 'GPS not available on this device.' });
            return;
        }

        const consent = this._getConsent();
        if (!consent) {
            // First-use consent UI — show explanation then prompt
            const agreed = await this._showConsentDialog();
            if (!agreed) {
                this.onStatusChange({ canCheckIn: false, message: 'Location permission denied.' });
                return;
            }
            this._saveConsent('checkin');
        }

        this._startWatchingPosition();
    }

    /**
     * Perform GPS check-in.
     * Sends current coordinates to the server and records the check-in.
     * @returns {Promise<Object>} Server response
     */
    async checkIn() {
        if (!this._position) {
            throw new Error('GPS position not yet available.');
        }

        const { latitude, longitude, accuracy } = this._position.coords;

        const res = await this._post('/check-in', {
            zone_id:    this.zoneId,
            booking_id: this.bookingId,
            lat:        latitude,
            lng:        longitude,
            accuracy:   accuracy,
        });

        if (res.check_in_id) {
            this._checkInId = res.check_in_id;
        }

        return res;
    }

    /**
     * Perform GPS check-out.
     * @returns {Promise<Object>} Server response
     */
    async checkOut() {
        if (!this._checkInId) {
            throw new Error('No active check-in found.');
        }

        if (!this._position) {
            throw new Error('GPS position not yet available.');
        }

        const { latitude, longitude, accuracy } = this._position.coords;

        const res = await this._post('/check-out', {
            check_in_id: this._checkInId,
            lat:         latitude,
            lng:         longitude,
            accuracy:    accuracy,
        });

        this._checkInId = null;
        this._stopWatchingPosition();

        return res;
    }

    /**
     * Stop watching GPS (called on check-out or page unload).
     */
    destroy() {
        this._stopWatchingPosition();
    }

    // ── Private helpers ─────────────────────────────────────────────────────

    _startWatchingPosition() {
        const options = {
            enableHighAccuracy: true,
            maximumAge:         10000,   // accept cached fix up to 10 s
            timeout:            15000,
        };

        this._watchId = navigator.geolocation.watchPosition(
            (pos) => this._onPositionUpdate(pos),
            (err) => this._onPositionError(err),
            options
        );
    }

    _stopWatchingPosition() {
        if (this._watchId !== null) {
            navigator.geolocation.clearWatch(this._watchId);
            this._watchId = null;
        }
    }

    async _onPositionUpdate(position) {
        this._position = position;

        if (!this.zoneId) return;

        try {
            const data = await this._post('/geofence-check', {
                zone_id: this.zoneId,
                lat:     position.coords.latitude,
                lng:     position.coords.longitude,
            });

            this.onStatusChange({
                canCheckIn: data.within,
                distanceM:  data.distance_m,
                radiusM:    data.radius_m,
                message:    data.within
                    ? 'You are within the job site area.'
                    : `You are ${data.distance_m ? Math.round(data.distance_m) + ' m' : 'too far'} from the job site (radius: ${data.radius_m ?? '?'} m).`,
            });
        } catch (_) {
            // Network error — allow check-in as best-effort
            this.onStatusChange({ canCheckIn: true, distanceM: null, radiusM: null, message: 'Geofence check unavailable.' });
        }
    }

    _onPositionError(err) {
        this.onStatusChange({
            canCheckIn: true,   // GPS unavailable — allow with warning flag
            distanceM:  null,
            radiusM:    null,
            message:    `GPS error (code ${err.code}): ${err.message}. Check-in will be flagged unverified.`,
        });
    }

    async _post(path, body) {
        const res = await fetch(this.apiBase + path, {
            method:  'POST',
            headers: {
                'Content-Type':  'application/json',
                'Accept':        'application/json',
                'X-CSRF-TOKEN':  this.csrfToken,
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

    _getConsent() {
        return localStorage.getItem(this._CONSENT_KEY);
    }

    _saveConsent(level) {
        localStorage.setItem(this._CONSENT_KEY, level);
    }

    /**
     * Show a simple consent dialog using a <dialog> element (or confirm() fallback).
     * Returns true if user agreed.
     */
    async _showConsentDialog() {
        const existing = document.getElementById('gps-consent-dialog');
        if (existing) existing.remove();

        return new Promise((resolve) => {
            // Prefer native <dialog>; fall back to window.confirm
            if (typeof HTMLDialogElement === 'undefined') {
                resolve(window.confirm(
                    'This app needs access to your GPS location to verify your presence at the job site for check-in/check-out.\n\n' +
                    'Location is only collected while you are using the app.\n\n' +
                    'Tap OK to allow location access.'
                ));
                return;
            }

            const dialog = document.createElement('dialog');
            dialog.id = 'gps-consent-dialog';
            dialog.setAttribute('aria-modal', 'true');
            dialog.setAttribute('aria-labelledby', 'gps-consent-title');
            dialog.style.cssText = 'border:none;border-radius:12px;padding:24px;max-width:380px;width:90%;box-shadow:0 8px 32px rgba(0,0,0,.2);';
            dialog.innerHTML = `
                <h2 id="gps-consent-title" style="font-size:1.1rem;margin-bottom:12px;">📍 Location Permission Required</h2>
                <p style="font-size:.9rem;color:#555;margin-bottom:16px;">
                    This app uses your GPS location to verify your presence at the job site before
                    enabling check-in / check-out. Location data is only collected while you are
                    actively using the app.
                </p>
                <p style="font-size:.85rem;color:#777;margin-bottom:20px;">
                    Your location will not be shared with third parties and will be retained according
                    to your organisation's privacy policy.
                </p>
                <div style="display:flex;gap:10px;justify-content:flex-end;">
                    <button id="gps-consent-deny"  style="padding:8px 18px;border:1px solid #ccc;background:#fff;border-radius:6px;cursor:pointer;">Deny</button>
                    <button id="gps-consent-allow" style="padding:8px 18px;background:#0d6efd;color:#fff;border:none;border-radius:6px;cursor:pointer;">Allow Location</button>
                </div>`;

            document.body.appendChild(dialog);
            dialog.showModal();

            dialog.querySelector('#gps-consent-allow').addEventListener('click', () => {
                dialog.close();
                dialog.remove();
                resolve(true);
            });

            dialog.querySelector('#gps-consent-deny').addEventListener('click', () => {
                dialog.close();
                dialog.remove();
                resolve(false);
            });
        });
    }
}
