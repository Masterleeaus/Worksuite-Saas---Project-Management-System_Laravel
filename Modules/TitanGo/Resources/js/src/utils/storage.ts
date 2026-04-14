// Titan Go — Storage Utility
// Reused from nexus-field-ops with renamed keys.

const PREFIX = 'titan_go_';

export const storage = {
  get<T>(key: string): T | null {
    try {
      const v = localStorage.getItem(PREFIX + key);
      return v ? (JSON.parse(v) as T) : null;
    } catch {
      return null;
    }
  },

  set<T>(key: string, value: T): void {
    try {
      localStorage.setItem(PREFIX + key, JSON.stringify(value));
    } catch {}
  },

  remove(key: string): void {
    try {
      localStorage.removeItem(PREFIX + key);
    } catch {}
  },

  clear(): void {
    try {
      Object.keys(localStorage)
        .filter(k => k.startsWith(PREFIX))
        .forEach(k => localStorage.removeItem(k));
    } catch {}
  },
};
