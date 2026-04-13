// Titan Go — useVisits hook
// Manages today's visits, caching, and visit lifecycle actions.

import { useState, useEffect, useCallback } from 'react';
import { Visit, ChecklistStep } from '../types';
import { apiClient } from '../services/api.service';
import { syncService } from '../services/sync.service';
import { storage } from '../utils/storage';

const VISITS_KEY = 'visits_today';

interface TodayResponse {
  today: any[];
  next_visit: any | null;
  summary: { total: number; completed: number; remaining: number };
}

function normaliseVisit(raw: any): Visit {
  return {
    id:             raw.id,
    name:           raw.name ?? raw.description ?? '',
    customerName:   raw.location?.name ?? '',
    address:        [raw.location?.street, raw.location?.city, raw.location?.state]
                      .filter(Boolean).join(', '),
    locationId:     raw.location?.id,
    latitude:       raw.location?.lat,
    longitude:      raw.location?.lng,
    locationNotes:  raw.location?.notes,
    stage:          raw.stage ?? '',
    isComplete:     raw.is_complete ?? false,
    scheduledStart: raw.scheduled_start,
    scheduledEnd:   raw.scheduled_end,
    dateStart:      raw.date_start,
    dateEnd:        raw.date_end,
    priority:       raw.priority ?? 'medium',
    description:    raw.description,
    steps:          (raw.template?.checklist ?? []).map((instruction: string, i: number): ChecklistStep => ({
      id:                String(i),
      instruction,
      completed:         false,
      photoRequired:     false,
      signatureRequired: false,
      isRequired:        true,
    })),
  };
}

export function useVisits(isOnline: boolean) {
  const [visits, setVisits] = useState<Visit[]>(() => {
    return storage.get<Visit[]>(VISITS_KEY) ?? [];
  });
  const [nextVisit, setNextVisit] = useState<Visit | null>(null);
  const [summary, setSummary] = useState({ total: 0, completed: 0, remaining: 0 });
  const [activeVisitId, setActiveVisitId] = useState<string | number | null>(null);
  const [loading, setLoading] = useState(false);

  const activeVisit = visits.find(v => v.id === activeVisitId) ?? null;

  const fetchToday = useCallback(async () => {
    if (!isOnline) return;
    setLoading(true);
    try {
      const data = await apiClient.get<TodayResponse>('/api/nexus/v1/dashboard');
      const normalised = (data.today ?? []).map(normaliseVisit);
      setVisits(normalised);
      storage.set(VISITS_KEY, normalised);
      setSummary(data.summary ?? { total: 0, completed: 0, remaining: 0 });
      if (data.next_visit) setNextVisit(normaliseVisit(data.next_visit));
    } catch (e) {
      console.warn('[useVisits] fetchToday failed', e);
    } finally {
      setLoading(false);
    }
  }, [isOnline]);

  useEffect(() => {
    fetchToday();
  }, [fetchToday]);

  const completeStep = useCallback(
    (visitId: string | number, stepId: string, photo?: string, notes?: string) => {
      setVisits(prev =>
        prev.map(v =>
          v.id === visitId
            ? {
                ...v,
                steps: v.steps.map(s =>
                  s.id === stepId
                    ? { ...s, completed: true, photoCaptured: photo, notes }
                    : s,
                ),
              }
            : v,
        ),
      );
    },
    [],
  );

  const checkIn = useCallback(
    async (visitId: string | number) => {
      if (isOnline) {
        try {
          await apiClient.post(`/api/fsm/v1/orders/${visitId}/checkin`);
        } catch {
          syncService.enqueue('checkin', {}, visitId);
        }
      } else {
        syncService.enqueue('checkin', {}, visitId);
      }
      setVisits(prev =>
        prev.map(v =>
          v.id === visitId ? { ...v, dateStart: new Date().toISOString(), stage: 'On Site' } : v,
        ),
      );
    },
    [isOnline],
  );

  const checkOut = useCallback(
    async (visitId: string | number) => {
      if (isOnline) {
        try {
          await apiClient.post(`/api/fsm/v1/orders/${visitId}/checkout`);
        } catch {
          syncService.enqueue('checkout', {}, visitId);
        }
      } else {
        syncService.enqueue('checkout', {}, visitId);
      }
      setVisits(prev =>
        prev.map(v =>
          v.id === visitId ? { ...v, dateEnd: new Date().toISOString() } : v,
        ),
      );
    },
    [isOnline],
  );

  const complete = useCallback(
    async (visitId: string | number) => {
      if (isOnline) {
        try {
          await apiClient.post(`/api/fsm/v1/orders/${visitId}/complete`);
        } catch {
          syncService.enqueue('complete', {}, visitId);
        }
      } else {
        syncService.enqueue('complete', {}, visitId);
      }
      setVisits(prev =>
        prev.map(v =>
          v.id === visitId ? { ...v, isComplete: true, stage: 'Completed' } : v,
        ),
      );
    },
    [isOnline],
  );

  return {
    visits,
    nextVisit,
    summary,
    activeVisit,
    activeVisitId,
    loading,
    setActiveVisitId,
    completeStep,
    checkIn,
    checkOut,
    complete,
    refresh: fetchToday,
  };
}
