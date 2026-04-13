// Titan Go — Notification Service
// Manages push notification registration and delivery via Capacitor PushNotifications.
//
// On Android/iOS: uses the native @capacitor/push-notifications plugin, which wraps
// FCM (Android) and APNs (iOS). The obtained registration token is sent to the
// backend via PUT /api/nexus/v1/worker/fcm-token.
//
// On web/desktop: falls back to the browser Notification API for local toasts.

import { authService } from './auth.service';
import { apiClient } from './api.service';

// Lazy-load the Capacitor plugin so the service tree-shakes cleanly in web builds.
async function getPushPlugin(): Promise<typeof import('@capacitor/push-notifications').PushNotifications | null> {
  try {
    const { PushNotifications } = await import('@capacitor/push-notifications');
    return PushNotifications;
  } catch {
    return null;
  }
}

class NotificationService {
  private registered = false;
  private token: string | null = null;

  /**
   * Initialise push notifications.
   * Call once after the worker logs in.
   *
   * 1. Requests OS permission.
   * 2. Registers with FCM / APNs.
   * 3. Uploads the token to the backend.
   * 4. Sets up foreground message handler.
   */
  async init(): Promise<void> {
    const Push = await getPushPlugin();

    if (Push) {
      await this.initCapacitor(Push);
    } else {
      // Web fallback
      await this.initWebFallback();
    }
  }

  private async initCapacitor(Push: typeof import('@capacitor/push-notifications').PushNotifications): Promise<void> {
    let permStatus = await Push.checkPermissions();

    if (permStatus.receive === 'prompt') {
      permStatus = await Push.requestPermissions();
    }

    if (permStatus.receive !== 'granted') {
      console.warn('[NotificationService] Push permission denied.');
      return;
    }

    // Register for push (triggers registration event below)
    await Push.register();

    Push.addListener('registration', async ({ value: token }) => {
      console.log('[NotificationService] Push token:', token);
      this.token = token;
      this.registered = true;
      await this.sendTokenToBackend(token);
    });

    Push.addListener('registrationError', ({ error }) => {
      console.error('[NotificationService] Registration error:', error);
    });

    Push.addListener('pushNotificationReceived', (notification) => {
      // App is in foreground — show local toast
      const title = notification.title ?? 'Titan Go';
      const body  = notification.body  ?? '';
      this.showLocal(title, body);
    });

    Push.addListener('pushNotificationActionPerformed', (action) => {
      // User tapped notification — could deep-link to visit
      console.log('[NotificationService] Action:', action.notification.data);
    });
  }

  private async initWebFallback(): Promise<void> {
    const granted = await this.requestBrowserPermission();
    if (!granted) return;

    // Check for service-worker-delivered FCM token set by the host app
    const fbToken = (window as any).firebaseFcmToken as string | undefined;
    if (fbToken) {
      this.token = fbToken;
      this.registered = true;
      await this.sendTokenToBackend(fbToken);
    }
  }

  /**
   * Register the device FCM/APNs token with the backend.
   * PUT /api/nexus/v1/worker/fcm-token
   */
  async sendTokenToBackend(token: string): Promise<void> {
    const session = authService.getSession();
    if (!session) return;
    try {
      await apiClient.put('/api/nexus/v1/worker/fcm-token', {
        fcm_token: token,
        device_id: session.deviceId,
      });
      console.log('[NotificationService] Token registered with backend.');
    } catch (e) {
      console.warn('[NotificationService] Token registration failed:', e);
    }
  }

  /**
   * Request browser (web) notification permission.
   */
  async requestBrowserPermission(): Promise<boolean> {
    if (!('Notification' in window)) return false;
    if (Notification.permission === 'granted') return true;
    const result = await Notification.requestPermission();
    return result === 'granted';
  }

  /**
   * Show a local notification (web fallback or foreground push).
   */
  showLocal(title: string, body: string, icon?: string): void {
    if (Notification.permission !== 'granted') return;
    try {
      new Notification(title, { body, icon });
    } catch (e) {
      console.warn('[NotificationService] Failed to show notification:', e);
    }
  }

  getToken(): string | null {
    return this.token;
  }

  isRegistered(): boolean {
    return this.registered;
  }
}

export const notificationService = new NotificationService();
