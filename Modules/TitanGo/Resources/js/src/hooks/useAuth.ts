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

      // Request notification permission and try to register token
      const granted = await notificationService.requestPermission();
      if (granted && (window as any).firebaseFcmToken) {
        await notificationService.registerToken((window as any).firebaseFcmToken);
      }
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
