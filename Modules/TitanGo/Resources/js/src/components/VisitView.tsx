// Titan Go — VisitView
// Proof-of-service workflow: check-in → before photos → checklist → notes →
// signature → after photos → check-out → complete visit.

import React, { useState, useRef } from 'react';
import {
  ArrowLeft, MapPin, Clock, Camera, CheckCircle2,
  FileText, PenLine, AlertTriangle, Navigation, Map
} from 'lucide-react';
import { Visit } from '../types';
import { ChecklistView } from './ChecklistView';
import { SiteMemoryPanel } from './SiteMemoryPanel';
import { IssueForm } from './IssueForm';
import { gpsService } from '../services/gps.service';
import { mediaUploadService } from '../services/media.service';
import { useChecklist } from '../hooks/useChecklist';

type Tab = 'overview' | 'checklist' | 'site' | 'notes';

interface Props {
  visit: Visit;
  isOnline: boolean;
  onBack: () => void;
  onCheckIn:   (id: string | number) => void;
  onCheckOut:  (id: string | number) => void;
  onComplete:  (id: string | number) => void;
  onStepComplete: (visitId: string | number, stepId: string, photo?: string, signature?: string, notes?: string) => void;
}

export function VisitView({
  visit, isOnline, onBack, onCheckIn, onCheckOut, onComplete, onStepComplete
}: Props) {
  const [activeTab,    setActiveTab]    = useState<Tab>('overview');
  const [showIssue,    setShowIssue]    = useState(false);
  const [noteBody,     setNoteBody]     = useState('');
  const [noteSending,  setNoteSending]  = useState(false);
  const [noteSuccess,  setNoteSuccess]  = useState(false);
  const photoRef = useRef<HTMLInputElement>(null);
  const [photoType, setPhotoType] = useState<'before' | 'after'>('before');

  // Use live checklist from API, fall back to cached steps from visit
  const { steps, completedCount, totalCount, completeStep } = useChecklist(
    visit.id,
    isOnline,
    visit.steps,
  );

  const hasCheckedIn  = !!visit.dateStart;
  const hasCheckedOut = !!visit.dateEnd;
  const allStepsDone  = totalCount === 0 || completedCount === totalCount;

  const handleCheckIn = () => {
    gpsService.start('foreground', visit.id);
    onCheckIn(visit.id);
  };

  const handleCheckOut = () => {
    gpsService.stop();
    onCheckOut(visit.id);
  };

  const handleComplete = () => {
    onComplete(visit.id);
  };

  const capturePhoto = (type: 'before' | 'after') => {
    setPhotoType(type);
    photoRef.current?.click();
  };

  const handlePhotoFile = async (e: React.ChangeEvent<HTMLInputElement>) => {
    const file = e.target.files?.[0];
    if (!file) return;
    const reader = new FileReader();
    reader.onload = async ev => {
      const data = ev.target?.result as string;
      await mediaUploadService.upload(visit.id, photoType, data, isOnline);
    };
    reader.readAsDataURL(file);
    e.target.value = '';
  };

  const submitNote = async () => {
    if (!noteBody.trim()) return;
    setNoteSending(true);
    try {
      const { apiClient } = await import('../services/api.service');
      await apiClient.post(`/api/nexus/v1/jobs/${visit.id}/notes`, { body: noteBody });
      setNoteBody('');
      setNoteSuccess(true);
      setTimeout(() => setNoteSuccess(false), 2000);
    } catch {
      const { syncService } = await import('../services/sync.service');
      syncService.enqueue('note', { body: noteBody }, visit.id);
      setNoteBody('');
    } finally {
      setNoteSending(false);
    }
  };

  return (
    <div className="flex flex-col h-full">
      {/* Visit header */}
      <div className="px-4 py-4 border-b border-zinc-800 space-y-2">
        <button
          onClick={onBack}
          className="flex items-center gap-2 text-zinc-500 hover:text-white text-xs transition-colors mb-1"
        >
          <ArrowLeft size={14} /> Back to Today
        </button>
        <div className="flex items-start justify-between gap-2">
          <div className="min-w-0">
            <h2 className="text-white font-black text-base uppercase tracking-tight truncate">
              {visit.name || visit.customerName || `Visit #${visit.id}`}
            </h2>
            {visit.address && (
              <p className="text-zinc-500 text-xs flex items-center gap-1 mt-0.5 truncate">
                <MapPin size={10} /> {visit.address}
              </p>
            )}
          </div>
          {visit.latitude && visit.longitude && (
            <a
              href={`https://maps.google.com/?q=${visit.latitude},${visit.longitude}`}
              target="_blank"
              rel="noopener noreferrer"
              className="shrink-0 p-2 bg-zinc-900 border border-zinc-800 rounded-lg text-amber-400 hover:border-amber-500/40"
              aria-label="Navigate to site"
            >
              <Navigation size={14} />
            </a>
          )}
        </div>

        {/* Lifecycle actions */}
        <div className="flex gap-2 pt-1 flex-wrap">
          {!hasCheckedIn && !visit.isComplete && (
            <button
              onClick={handleCheckIn}
              className="px-4 py-2 bg-amber-500 hover:bg-amber-400 text-black font-black text-[10px] uppercase tracking-widest rounded-lg transition-all active:scale-95"
            >
              Check In
            </button>
          )}
          {hasCheckedIn && !hasCheckedOut && !visit.isComplete && (
            <button
              onClick={handleCheckOut}
              className="px-4 py-2 bg-zinc-800 hover:bg-zinc-700 text-zinc-300 font-black text-[10px] uppercase tracking-widest rounded-lg border border-zinc-700 transition-all active:scale-95"
            >
              Check Out
            </button>
          )}
          {allStepsDone && hasCheckedIn && !visit.isComplete && (
            <button
              onClick={handleComplete}
              className="px-4 py-2 bg-emerald-600 hover:bg-emerald-500 text-white font-black text-[10px] uppercase tracking-widest rounded-lg transition-all active:scale-95 shadow-[0_0_20px_rgba(16,185,129,0.2)]"
            >
              Complete Visit
            </button>
          )}
          {/* Photo capture */}
          <button
            onClick={() => capturePhoto('before')}
            className="px-3 py-2 bg-zinc-900 border border-zinc-800 text-zinc-400 font-black text-[10px] uppercase rounded-lg flex items-center gap-1 hover:border-zinc-700"
          >
            <Camera size={12} /> Before
          </button>
          <button
            onClick={() => capturePhoto('after')}
            className="px-3 py-2 bg-zinc-900 border border-zinc-800 text-zinc-400 font-black text-[10px] uppercase rounded-lg flex items-center gap-1 hover:border-zinc-700"
          >
            <Camera size={12} /> After
          </button>
          <button
            onClick={() => setShowIssue(true)}
            className="px-3 py-2 bg-red-900/20 border border-red-800/30 text-red-400 font-black text-[10px] uppercase rounded-lg flex items-center gap-1 hover:border-red-700/50"
          >
            <AlertTriangle size={12} /> Issue
          </button>
        </div>

        {/* Hidden photo input */}
        <input
          ref={photoRef}
          type="file"
          accept="image/*"
          capture="environment"
          className="hidden"
          onChange={handlePhotoFile}
        />
      </div>

      {/* Tabs */}
      <div className="flex border-b border-zinc-800 px-4 bg-[#0f0f14]">
        {(['overview', 'checklist', 'site', 'notes'] as Tab[]).map(tab => (
          <button
            key={tab}
            onClick={() => setActiveTab(tab)}
            className={`px-4 py-3 text-[10px] font-black uppercase tracking-widest border-b-2 transition-all ${
              activeTab === tab
                ? 'border-amber-500 text-amber-400'
                : 'border-transparent text-zinc-600 hover:text-zinc-400'
            }`}
          >
            {tab === 'overview'  ? 'Overview'   : null}
            {tab === 'checklist' ? 'Checklist'  : null}
            {tab === 'site'      ? 'Site'        : null}
            {tab === 'notes'     ? 'Notes'       : null}
          </button>
        ))}
      </div>

      {/* Tab content */}
      <div className="flex-1 overflow-y-auto p-4">
        {activeTab === 'overview' && (
          <div className="space-y-4">
            {visit.description && (
              <div className="bg-zinc-900/50 border border-zinc-800 rounded-xl p-4">
                <p className="text-[10px] text-zinc-600 uppercase font-black tracking-widest mb-1">Description</p>
                <p className="text-sm text-zinc-300">{visit.description}</p>
              </div>
            )}
            <div className="grid grid-cols-2 gap-3">
              {[
                { label: 'Scheduled Start', value: visit.scheduledStart && new Date(visit.scheduledStart).toLocaleString('en-AU', { hour: '2-digit', minute: '2-digit' }) },
                { label: 'Scheduled End',   value: visit.scheduledEnd   && new Date(visit.scheduledEnd).toLocaleString('en-AU', { hour: '2-digit', minute: '2-digit' }) },
                { label: 'Check-In',        value: visit.dateStart && new Date(visit.dateStart).toLocaleString() },
                { label: 'Check-Out',       value: visit.dateEnd   && new Date(visit.dateEnd).toLocaleString() },
              ].filter(r => r.value).map(r => (
                <div key={r.label} className="bg-zinc-900/50 border border-zinc-800 rounded-xl p-3">
                  <p className="text-[9px] text-zinc-600 uppercase font-black tracking-widest">{r.label}</p>
                  <p className="text-xs text-zinc-300 mt-0.5">{r.value}</p>
                </div>
              ))}
            </div>
          </div>
        )}

        {activeTab === 'checklist' && (
          steps.length > 0 ? (
            <ChecklistView
              steps={steps}
              onComplete={(stepId, photo, signature, notes) => {
                const idx = parseInt(stepId, 10);
                completeStep(idx, { photo, signature, notes });
                onStepComplete(visit.id, stepId, photo, signature, notes);
              }}
            />
          ) : (
            <p className="text-zinc-600 text-xs text-center py-8">No checklist for this visit.</p>
          )
        )}

        {activeTab === 'site' && (
          <SiteMemoryPanel visitId={visit.id} isOnline={isOnline} />
        )}

        {activeTab === 'notes' && (
          <div className="space-y-4">
            <textarea
              value={noteBody}
              onChange={e => setNoteBody(e.target.value)}
              placeholder="Add a note for this visit…"
              rows={4}
              className="w-full bg-zinc-900 border border-zinc-800 rounded-xl px-4 py-3 text-sm text-zinc-300 placeholder-zinc-700 focus:outline-none focus:border-amber-500 resize-none"
            />
            {noteSuccess && (
              <p className="text-emerald-400 text-xs font-black uppercase tracking-widest text-center">
                Note saved ✓
              </p>
            )}
            <button
              onClick={submitNote}
              disabled={!noteBody.trim() || noteSending}
              className="w-full bg-amber-500/10 hover:bg-amber-500/20 border border-amber-500/30 text-amber-400 font-black text-xs uppercase tracking-widest py-3 rounded-xl transition-all disabled:opacity-40"
            >
              {noteSending ? 'Saving…' : 'Save Note'}
            </button>
          </div>
        )}
      </div>

      {/* Issue form modal */}
      {showIssue && (
        <IssueForm
          visitId={visit.id}
          isOnline={isOnline}
          onClose={() => setShowIssue(false)}
        />
      )}
    </div>
  );
}
