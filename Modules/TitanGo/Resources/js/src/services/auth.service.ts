// Titan Go — AuthService
// Handles login/logout, session persistence, and company/device context.

import { AuthSession, Worker } from '../types';
import { apiClient } from './api.service';
import { storage } from '../utils/storage';
import { v4 as uuid } from '../utils/uuid';

const SESSION_KEY = 'titan_go_session';

interface LoginPayload {
  email: string;
  password: string;
}

interface LoginResponse {
  token: string;
  expires_at: string;
  worker: { id: number; name: string; email: string };
}

class AuthService {
  private session: AuthSession | null = null;

  constructor() {
    this.session = storage.get<AuthSession>(SESSION_KEY);
    if (this.session) {
      apiClient.setSession(this.session);
    }
  }

  getSession(): AuthSession | null {
    return this.session;
  }

  isAuthenticated(): boolean {
    if (!this.session) return false;
    // Check token expiry
    const exp = new Date(this.session.expiresAt).getTime();
    return Date.now() < exp;
  }

  async login(payload: LoginPayload): Promise<AuthSession> {
    const deviceId = storage.get<string>('titan_go_device_id') ?? uuid();
    storage.set('titan_go_device_id', deviceId);

    const res = await apiClient.post<LoginResponse>('/api/fsm/v1/auth/login', payload);

    const worker: Worker = {
      id:        res.worker.id,
      name:      res.worker.name,
      email:     res.worker.email,
      companyId: (res.worker as any).company_id ?? 0,
      deviceId,
    };

    const session: AuthSession = {
      token:     res.token,
      expiresAt: res.expires_at,
      worker,
      companyId: worker.companyId,
      deviceId,
    };

    this.session = session;
    storage.set(SESSION_KEY, session);
    apiClient.setSession(session);

    return session;
  }

  async logout(): Promise<void> {
    try {
      await apiClient.post('/api/fsm/v1/auth/logout');
    } catch {}
    this.session = null;
    storage.remove(SESSION_KEY);
    apiClient.setSession(null);
  }
}

export const authService = new AuthService();
