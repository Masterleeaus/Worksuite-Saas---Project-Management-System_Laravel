// Titan Go — Media Upload Service
// Handles before/after/issue/signature media items with offline queue support.

import { MediaItem, MediaType } from '../types';
import { apiClient } from './api.service';
import { syncService } from './sync.service';
import { storage } from '../utils/storage';

const MEDIA_QUEUE_KEY = 'titan_go_media_queue';

class MediaUploadService {
  private queue: MediaItem[] = [];

  constructor() {
    const saved = storage.get<MediaItem[]>(MEDIA_QUEUE_KEY);
    if (Array.isArray(saved)) this.queue = saved;
  }

  /**
   * Upload a photo or signature immediately; queue offline if unavailable.
   */
  async upload(
    visitId: string | number,
    type: MediaType,
    data: string, // base64
    isOnline: boolean,
  ): Promise<void> {
    const item: MediaItem = {
      id:      `${Date.now()}_${Math.random().toString(36).slice(2, 8)}`,
      visitId,
      type,
      data,
      queued:  !isOnline,
    };

    if (!isOnline) {
      this.queue.push(item);
      this.save();
      // Also add to sync queue so the sync service knows about it
      syncService.enqueue('note', { type, visit_id: visitId, data_ref: item.id }, visitId);
      return;
    }

    const endpoint =
      type === 'signature'
        ? `/api/fsm/v1/orders/${visitId}/photos`
        : `/api/fsm/v1/orders/${visitId}/photos`;

    try {
      await apiClient.post(endpoint, {
        photo_type: type,
        data,
      });
    } catch {
      item.queued = true;
      this.queue.push(item);
      this.save();
    }
  }

  /**
   * Flush queued media items when connectivity is restored.
   */
  async flush(visitId: string | number): Promise<void> {
    const pending = this.queue.filter(i => i.visitId === visitId && i.queued);
    for (const item of pending) {
      try {
        await apiClient.post(`/api/fsm/v1/orders/${visitId}/photos`, {
          photo_type: item.type,
          data:       item.data,
        });
        item.queued = false;
      } catch {
        // Leave in queue
      }
    }
    this.queue = this.queue.filter(i => i.queued);
    this.save();
  }

  getQueuedCount(): number {
    return this.queue.filter(i => i.queued).length;
  }

  private save(): void {
    storage.set(MEDIA_QUEUE_KEY, this.queue);
  }
}

export const mediaUploadService = new MediaUploadService();
