// Titan Go — Core Types
// Terminology: company (was provider), worker (was serviceman), visit (was order/booking)

// ─── Visit lifecycle ────────────────────────────────────────────────────────
export type VisitStage =
  | 'Draft'
  | 'Planned'
  | 'Scheduled'
  | 'Dispatched'
  | 'En Route'
  | 'On Site'
  | 'In Progress'
  | 'Awaiting Review'
  | 'Completed'
  | 'Invoiced'
  | 'Closed';

// ─── Checklist ───────────────────────────────────────────────────────────────
export interface ChecklistStep {
  id: string;
  instruction: string;
  completed: boolean;
  photoRequired: boolean;
  signatureRequired: boolean;
  photoCaptured?: string;  // base64
  signatureCaptured?: string;
  notes?: string;
  isRequired: boolean;
}

// ─── Visit (was: Job / Order / Booking) ─────────────────────────────────────
export interface Visit {
  id: string | number;
  name: string;
  // customer / location
  customerName: string;
  address: string;
  locationId?: number;
  latitude?: number;
  longitude?: number;
  locationNotes?: string;
  // lifecycle
  stage: VisitStage | string;
  isComplete: boolean;
  scheduledStart?: string;
  scheduledEnd?: string;
  dateStart?: string;
  dateEnd?: string;
  // execution
  steps: ChecklistStep[];
  priority?: 'low' | 'medium' | 'high';
  description?: string;
  // meta
  companyId?: number;
  workerId?: number;
}

// ─── Worker (was: serviceman / tech) ─────────────────────────────────────────
export interface Worker {
  id: number;
  name: string;
  email: string;
  companyId: number;
  deviceId?: string;
}

// ─── Auth / Session ──────────────────────────────────────────────────────────
export interface AuthSession {
  token: string;
  expiresAt: string;
  worker: Worker;
  companyId: number;
  deviceId: string;
}

// ─── GPS ─────────────────────────────────────────────────────────────────────
export interface GpsCoords {
  latitude: number;
  longitude: number;
  accuracy?: number;
}

export type TrackingMode = 'foreground' | 'background' | 'off';

// ─── Site Memory ─────────────────────────────────────────────────────────────
export type SiteNoteType =
  | 'entry'
  | 'access_code'
  | 'parking'
  | 'equipment'
  | 'preference'
  | 'hazard'
  | 'repeat';

export interface SiteNote {
  id: string | number;
  type: SiteNoteType;
  content: string;
  createdAt?: string;
}

export interface SiteMemory {
  locationId: number;
  notes: Partial<Record<SiteNoteType, SiteNote[]>>;
}

// ─── Issues ──────────────────────────────────────────────────────────────────
export type IssueType =
  | 'access_blocked'
  | 'customer_absent'
  | 'damage'
  | 'extra_work'
  | 'safety_risk'
  | 'equipment_missing';

export interface Issue {
  id?: string | number;
  visitId: string | number;
  type: IssueType;
  description?: string;
  status: 'open' | 'resolved' | 'escalated';
  createdAt?: string;
}

// ─── Worker quick-action statuses ────────────────────────────────────────────
export type WorkerStatusSignal =
  | 'arrived'
  | 'running_late'
  | 'access_issue'
  | 'need_supplies'
  | 'job_complete'
  | 'report_issue';

// ─── Media ───────────────────────────────────────────────────────────────────
export type MediaType = 'before' | 'after' | 'issue' | 'extra_work' | 'signature';

export interface MediaItem {
  id: string;
  visitId: string | number;
  type: MediaType;
  data: string; // base64 or URL
  uploadedAt?: string;
  queued?: boolean;
}

// ─── Offline queue ────────────────────────────────────────────────────────────
export type QueueItemType =
  | 'note'
  | 'status'
  | 'issue'
  | 'location_ping'
  | 'checkin'
  | 'checkout'
  | 'complete'
  | 'checklist_step';

export interface SyncQueueItem {
  id: string;
  type: QueueItemType;
  visitId?: string | number;
  payload: Record<string, unknown>;
  timestamp: number;
  retryCount: number;
  maxRetries: number;
  status: 'pending' | 'processing' | 'failed' | 'synced';
  error?: string;
}

// ─── App state ───────────────────────────────────────────────────────────────
export interface AppState {
  session: AuthSession | null;
  isOnline: boolean;
  activeVisitId: string | number | null;
  visits: Visit[];
  syncQueueCount: number;
  trackingMode: TrackingMode;
}

// ─── Notifications ────────────────────────────────────────────────────────────
export type NotificationTopic =
  | 'assignment'
  | 'schedule_change'
  | 'dispatch'
  | 'exception'
  | 'checklist_update';
