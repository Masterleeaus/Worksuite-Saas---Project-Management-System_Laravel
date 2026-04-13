// Titan Go — useChecklist hook
// Loads checklist steps with per-worker completion state from the backend.
// Falls back to the locally-cached steps when offline.
// Exposes completeStep() which persists to the server and queues offline.

import { useState, useEffect, useCallback } from 'react';
import { ChecklistStep } from '../types';
import { apiClient } from '../services/api.service';
import { syncService } from '../services/sync.service';
import { storage } from '../utils/storage';

interface ApiStep {
  index:         number;
  instruction:   string;
  completed:     boolean;
  completed_at?: string;
  has_photo:     boolean;
  has_signature: boolean;
  notes?:        string;
}

interface ChecklistResponse {
  visit_id:        number;
  template_name?:  string;
  steps:           ApiStep[];
  completed_count: number;
  total_count:     number;
}

function cacheKey(visitId: string | number) {
  return `checklist_${visitId}`;
}

function toChecklistStep(s: ApiStep): ChecklistStep {
  return {
    id:                String(s.index),
    instruction:       s.instruction,
    completed:         s.completed,
    photoRequired:     s.has_photo,
    signatureRequired: s.has_signature,
    notes:             s.notes,
    isRequired:        true,
  };
}

export function useChecklist(
  visitId: string | number | null,
  isOnline: boolean,
  fallbackSteps?: ChecklistStep[],
) {
  const [steps, setSteps] = useState<ChecklistStep[]>(() => {
    if (!visitId) return fallbackSteps ?? [];
    return storage.get<ChecklistStep[]>(cacheKey(visitId)) ?? fallbackSteps ?? [];
  });
  const [templateName, setTemplateName] = useState<string | undefined>();
  const [loading, setLoading] = useState(false);

  const fetch = useCallback(async () => {
    if (!visitId || !isOnline) return;
    setLoading(true);
    try {
      const data = await apiClient.get<ChecklistResponse>(
        `/api/nexus/v1/jobs/${visitId}/checklist`,
      );
      const mapped = data.steps.map(toChecklistStep);
      setSteps(mapped);
      setTemplateName(data.template_name);
      storage.set(cacheKey(visitId), mapped);
    } catch (e) {
      console.warn('[useChecklist] fetch failed', e);
    } finally {
      setLoading(false);
    }
  }, [visitId, isOnline]);

  useEffect(() => {
    fetch();
  }, [fetch]);

  const completeStep = useCallback(
    async (
      stepIdx: number,
      extras?: { photo?: string; signature?: string; notes?: string },
    ) => {
      // Optimistic update
      setSteps(prev =>
        prev.map(s =>
          s.id === String(stepIdx)
            ? {
                ...s,
                completed:        true,
                photoCaptured:    extras?.photo    ?? s.photoCaptured,
                signatureCaptured: extras?.signature ?? s.signatureCaptured,
                notes:            extras?.notes    ?? s.notes,
              }
            : s,
        ),
      );

      const payload: Record<string, unknown> = {
        step_index: stepIdx,
        notes:      extras?.notes      ?? null,
        photo_data: extras?.photo      ?? null,
        signature_data: extras?.signature ?? null,
      };

      if (isOnline) {
        try {
          await apiClient.post(`/api/nexus/v1/jobs/${visitId}/checklist/step`, payload);
        } catch {
          syncService.enqueue('checklist_step', payload, visitId ?? undefined);
        }
      } else {
        syncService.enqueue('checklist_step', payload, visitId ?? undefined);
      }

      // Persist updated steps locally
      setSteps(prev => {
        storage.set(cacheKey(visitId!), prev);
        return prev;
      });
    },
    [visitId, isOnline],
  );

  const completedCount = steps.filter(s => s.completed).length;

  return {
    steps,
    templateName,
    loading,
    completedCount,
    totalCount: steps.length,
    completeStep,
    refresh: fetch,
  };
}
