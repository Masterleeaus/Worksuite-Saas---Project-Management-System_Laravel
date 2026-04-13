// Titan Go — Notification Service
// Registers FCM token and manages push notification subscriptions per tenant.

import { authService } from './auth.service';
import { apiClient } from './api.service';

class NotificationService {
  private registered = false;

  /**
   * Register the device FCM token with the backend.
   * Must be called after the worker logs in.
   *
   * PUT /api/nexus/v1/worker/fcm-token
   */
  async registerToken(fcmToken: string): Promise<void> {
    const session = authService.getSession();
    if (!session) return;

    await apiClient.put('/api/nexus/v1/worker/fcm-token', {
      fcm_token: fcmToken,
      device_id: session.deviceId,
    });

    this.registered = true;
    console.log('[NotificationService] FCM token registered.');
  }

  /**
   * Request browser notification permission.
   * Returns true if granted.
   */
  async requestPermission(): Promise<boolean> {
    if (!('Notification' in window)) return false;
    if (Notification.permission === 'granted') return true;

    const result = await Notification.requestPermission();
    return result === 'granted';
  }

  /**
   * Show a local notification (fallback when push is not available).
   */
  show(title: string, body: string, icon?: string): void {
    if (Notification.permission !== 'granted') return;
    try {
      new Notification(title, { body, icon });
    } catch (e) {
      console.warn('[NotificationService] Failed to show notification', e);
    }
  }

  isRegistered(): boolean {
    return this.registered;
  }
}

export const notificationService = new NotificationService();
