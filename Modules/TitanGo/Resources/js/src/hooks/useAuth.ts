// Titan Go — useAuth hook
import { useState, useCallback } from 'react';
import { authService } from '../services/auth.service';
import { notificationService } from '../services/notification.service';
import { AuthSession } from '../types';

export function useAuth() {
  const [session, setSession] = useState<AuthSession | null>(() =>
    authService.isAuthenticated() ? authService.getSession() : null,
  );
  const [loading, setLoading]   = useState(false);
  const [error,   setError]     = useState<string | null>(null);

  const login = useCallback(async (email: string, password: string) => {
    setLoading(true);
    setError(null);
    try {
      const s = await authService.login({ email, password });
      setSession(s);

      // Initialise push notifications after successful login.
      // init() handles both the Capacitor native plugin (Android/iOS)
      // and the web browser Notification API fallback — no FCM credentials
      // needed in JS; they live in the native Google Services JSON.
      notificationService.init().catch((e) =>
        console.warn('[useAuth] push notification init failed:', e),
      );
    } catch (e: any) {
      setError(e?.message ?? 'Login failed');
    } finally {
      setLoading(false);
    }
  }, []);

  const logout = useCallback(async () => {
    await authService.logout();
    setSession(null);
  }, []);

  return { session, loading, error, login, logout };
}
