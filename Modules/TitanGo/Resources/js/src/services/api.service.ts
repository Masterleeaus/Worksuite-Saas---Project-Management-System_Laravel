// Titan Go — Tenant-safe API client
// All requests include X-Company-ID, Authorization, and X-Device-ID headers.

import { AuthSession } from '../types';

const BASE_URL = (import.meta as any).env?.VITE_API_BASE_URL ?? '';

export class ApiError extends Error {
  constructor(
    public readonly status: number,
    message: string,
    public readonly body?: unknown,
  ) {
    super(message);
    this.name = 'ApiError';
  }
}

class ApiClient {
  private session: AuthSession | null = null;

  setSession(s: AuthSession | null): void {
    this.session = s;
  }

  private headers(): Record<string, string> {
    const h: Record<string, string> = {
      'Content-Type': 'application/json',
      Accept: 'application/json',
    };
    if (this.session) {
      h['Authorization'] = `Bearer ${this.session.token}`;
      h['X-Company-ID']  = String(this.session.companyId);
      h['X-Device-ID']   = this.session.deviceId ?? '';
    }
    return h;
  }

  async request<T>(
    method: string,
    path: string,
    body?: unknown,
    signal?: AbortSignal,
  ): Promise<T> {
    const url = `${BASE_URL}${path}`;
    const res = await fetch(url, {
      method,
      headers: this.headers(),
      body: body !== undefined ? JSON.stringify(body) : undefined,
      signal,
    });

    if (!res.ok) {
      let errBody: unknown;
      try { errBody = await res.json(); } catch {}
      const msg = (errBody as any)?.message ?? res.statusText;
      throw new ApiError(res.status, msg, errBody);
    }

    if (res.status === 204) return undefined as T;
    return res.json() as Promise<T>;
  }

  get<T>(path: string, signal?: AbortSignal): Promise<T> {
    return this.request<T>('GET', path, undefined, signal);
  }

  post<T>(path: string, body?: unknown): Promise<T> {
    return this.request<T>('POST', path, body);
  }

  put<T>(path: string, body?: unknown): Promise<T> {
    return this.request<T>('PUT', path, body);
  }

  delete<T>(path: string): Promise<T> {
    return this.request<T>('DELETE', path);
  }
}

export const apiClient = new ApiClient();
