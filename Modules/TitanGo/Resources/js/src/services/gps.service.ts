// Titan Go — GPS Tracking Service
//
// Tracking strategy:
//   foreground — Uses the native @capacitor-community/background-geolocation
//                plugin if available (high-accuracy, 30-second heartbeat).
//                Falls back to browser navigator.geolocation on web.
//   background — Same native plugin with reduced heartbeat (2 min) and a
//                foreground-service notification so Android does not kill
//                the process. Falls back to browser geolocation on web.
//   off        — Stops all tracking.
//
// Each successful fix is posted to POST /api/fsm/v1/location/ping.
// Failed posts are queued via syncService for offline replay.

import { GpsCoords, TrackingMode } from '../types';
import { apiClient } from './api.service';
import { syncService } from './sync.service';

const FOREGROUND_INTERVAL_MS = 30_000;   // 30 s
const BACKGROUND_INTERVAL_MS = 120_000;  // 2 min

// Lazy-load the Capacitor background-geolocation plugin to allow web tree-shaking.
type BgGeoPlugin = typeof import('@capacitor-community/background-geolocation').BackgroundGeolocation;

async function getBgGeoPlugin(): Promise<BgGeoPlugin | null> {
  try {
    const { BackgroundGeolocation } = await import('@capacitor-community/background-geolocation');
    return BackgroundGeolocation;
  } catch {
    return null;
  }
}

class GpsService {
  private mode: TrackingMode = 'off';
  private visitId: string | number | null = null;
  private timer: ReturnType<typeof setInterval> | null = null;
  private bgWatcherId: string | null = null;
  private lastCoords: GpsCoords | null = null;

  // ─── Public API ────────────────────────────────────────────────────────────

  async start(mode: TrackingMode, visitId?: string | number): Promise<void> {
    if (mode === 'off') { await this.stop(); return; }

    await this.stop(); // clear any previous session
    this.mode    = mode;
    this.visitId = visitId ?? null;

    const bgGeo = await getBgGeoPlugin();

    if (bgGeo) {
      await this.startNative(bgGeo, mode);
    } else {
      this.startWeb(mode);
    }
  }

  async stop(): Promise<void> {
    // Stop native watcher if running
    if (this.bgWatcherId !== null) {
      const bgGeo = await getBgGeoPlugin();
      if (bgGeo) {
        try { await bgGeo.removeWatcher({ id: this.bgWatcherId }); } catch {}
      }
      this.bgWatcherId = null;
    }

    // Stop web interval if running
    if (this.timer !== null) {
      clearInterval(this.timer);
      this.timer = null;
    }

    this.mode = 'off';
  }

  getMode(): TrackingMode { return this.mode; }
  getLastCoords(): GpsCoords | null { return this.lastCoords; }

  // ─── Native (Capacitor) ────────────────────────────────────────────────────

  private async startNative(bgGeo: BgGeoPlugin, mode: TrackingMode): Promise<void> {
    const backgroundMessage = mode === 'background'
      ? 'Titan Go is tracking your location for this visit.'
      : undefined;

    // addWatcher returns an opaque watcher ID; positions are delivered to the callback.
    this.bgWatcherId = await bgGeo.addWatcher(
      {
        backgroundMessage,   // enables background mode on iOS; foreground-service on Android
        requestPermissions:    true,
        stale:                 false,
        distanceFilter:        mode === 'background' ? 50 : 10, // meters
      },
      async (position, error) => {
        if (error) {
          console.warn('[GpsService] native error:', error.message);
          return;
        }
        if (!position) return;

        const coords: GpsCoords = {
          latitude:  position.latitude,
          longitude: position.longitude,
          accuracy:  position.accuracy,
        };
        this.lastCoords = coords;
        await this.sendPingFromCoords(coords);
      },
    );
  }

  // ─── Web fallback ──────────────────────────────────────────────────────────

  private startWeb(mode: TrackingMode): void {
    if (!navigator.geolocation) return;
    const interval = mode === 'foreground' ? FOREGROUND_INTERVAL_MS : BACKGROUND_INTERVAL_MS;
    this.sendPingWeb();
    this.timer = setInterval(() => this.sendPingWeb(), interval);
  }

  private sendPingWeb(): void {
    navigator.geolocation.getCurrentPosition(
      async (pos) => {
        const coords: GpsCoords = {
          latitude:  pos.coords.latitude,
          longitude: pos.coords.longitude,
          accuracy:  pos.coords.accuracy,
        };
        this.lastCoords = coords;
        await this.sendPingFromCoords(coords);
      },
      (err) => console.warn('[GpsService] geolocation error:', err.message),
      { enableHighAccuracy: true, timeout: 10_000 },
    );
  }

  // ─── Shared send ───────────────────────────────────────────────────────────

  private async sendPingFromCoords(coords: GpsCoords): Promise<void> {
    const payload = {
      latitude:      coords.latitude,
      longitude:     coords.longitude,
      accuracy:      coords.accuracy,
      visit_id:      this.visitId,
      tracking_mode: this.mode,
    };

    try {
      await apiClient.post('/api/fsm/v1/location/ping', payload);
    } catch {
      syncService.enqueue('location_ping', payload, this.visitId ?? undefined);
    }
  }
}

export const gpsService = new GpsService();
