// Titan Go — SyncService
// Persists mutations locally when offline and replays them when connectivity returns.
// Queue types: note | status | issue | location_ping | checkin | checkout | complete

import { SyncQueueItem, QueueItemType } from '../types';
import { storage } from '../utils/storage';
import { apiClient } from './api.service';

const QUEUE_KEY  = 'titan_go_sync_queue';
const MAX_SIZE   = 200;
const MAX_RETRY  = 3;

type Listener = (items: SyncQueueItem[]) => void;

class SyncService {
  private queue: SyncQueueItem[] = [];
  private processing = false;
  private listeners = new Set<Listener>();

  constructor() {
    const saved = storage.get<SyncQueueItem[]>(QUEUE_KEY);
    if (Array.isArray(saved)) {
      this.queue = saved.map(i => ({
        ...i,
        status: i.status === 'processing' ? 'pending' : i.status,
      }));
    }
  }

  enqueue(
    type: QueueItemType,
    payload: Record<string, unknown>,
    visitId?: string | number,
  ): string {
    const id = `${Date.now()}_${Math.random().toString(36).slice(2, 9)}`;
    const item: SyncQueueItem = {
      id,
      type,
      visitId,
      payload,
      timestamp:  Date.now(),
      retryCount: 0,
      maxRetries: MAX_RETRY,
      status:     'pending',
    };
    this.queue.push(item);
    if (this.queue.length > MAX_SIZE) this.queue.shift();
    this.save();
    this.notify();
    return id;
  }

  async flush(): Promise<void> {
    if (this.processing) return;
    const pending = this.queue.filter(
      i => i.status === 'pending' && i.retryCount < i.maxRetries,
    );
    if (pending.length === 0) return;

    this.processing = true;
    try {
      const res = await apiClient.post<{ results: Record<string, { status: string; message?: string }> }>(
        '/api/nexus/v1/sync',
        { queue: pending.map(i => ({
          id:      i.id,
          type:    i.type,
          visit_id: i.visitId,
          payload: i.payload,
          timestamp: i.timestamp,
        })) },
      );

      for (const item of pending) {
        const r = res.results?.[item.id];
        if (r?.status === 'ok') {
          item.status = 'synced';
        } else {
          item.retryCount++;
          item.error  = r?.message ?? 'Unknown error';
          item.status = item.retryCount >= item.maxRetries ? 'failed' : 'pending';
        }
      }

      // Keep only non-synced items
      this.queue = this.queue.filter(i => i.status !== 'synced');
      this.save();
      this.notify();
    } catch {
      // Network error — leave items as pending for next attempt
      for (const item of pending) {
        item.status = 'pending';
      }
    } finally {
      this.processing = false;
    }
  }

  getPendingCount(): number {
    return this.queue.filter(i => i.status === 'pending').length;
  }

  subscribe(fn: Listener): () => void {
    this.listeners.add(fn);
    return () => this.listeners.delete(fn);
  }

  getQueue(): SyncQueueItem[] {
    return [...this.queue];
  }

  private save(): void {
    storage.set(QUEUE_KEY, this.queue);
  }

  private notify(): void {
    const copy = [...this.queue];
    this.listeners.forEach(fn => { try { fn(copy); } catch {} });
  }
}

export const syncService = new SyncService();
