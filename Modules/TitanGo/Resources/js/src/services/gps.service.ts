// Titan Go — GPS Tracking Service
// Foreground tracking during visit, background while dispatched.
// Sends heartbeat pings to POST /api/fsm/v1/location/ping

import { GpsCoords, TrackingMode } from '../types';
import { apiClient } from './api.service';
import { syncService } from './sync.service';

const FOREGROUND_INTERVAL_MS = 30_000;   // 30 s
const BACKGROUND_INTERVAL_MS = 120_000;  // 2 min

class GpsService {
  private mode: TrackingMode = 'off';
  private visitId: string | number | null = null;
  private timer: ReturnType<typeof setInterval> | null = null;
  private lastCoords: GpsCoords | null = null;

  start(mode: TrackingMode, visitId?: string | number): void {
    if (mode === 'off') { this.stop(); return; }
    this.mode    = mode;
    this.visitId = visitId ?? null;
    this.stop(); // clear any previous timer

    const interval =
      mode === 'foreground' ? FOREGROUND_INTERVAL_MS : BACKGROUND_INTERVAL_MS;

    // Send immediately then on interval
    this.sendPing();
    this.timer = setInterval(() => this.sendPing(), interval);
  }

  stop(): void {
    if (this.timer !== null) {
      clearInterval(this.timer);
      this.timer = null;
    }
    this.mode = 'off';
  }

  getMode(): TrackingMode {
    return this.mode;
  }

  getLastCoords(): GpsCoords | null {
    return this.lastCoords;
  }

  private async sendPing(): Promise<void> {
    if (!navigator.geolocation) return;

    navigator.geolocation.getCurrentPosition(
      async (pos) => {
        const coords: GpsCoords = {
          latitude:  pos.coords.latitude,
          longitude: pos.coords.longitude,
          accuracy:  pos.coords.accuracy,
        };
        this.lastCoords = coords;

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
          // Queue for offline replay
          syncService.enqueue('location_ping', payload, this.visitId ?? undefined);
        }
      },
      (err) => console.warn('[GpsService] geolocation error', err.message),
      { enableHighAccuracy: true, timeout: 10_000 },
    );
  }
}

export const gpsService = new GpsService();
