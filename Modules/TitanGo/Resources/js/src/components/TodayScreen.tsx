// Titan Go — TodayScreen
// Worker-first landing: today's visits, next preview, summary.

import React from 'react';
import { MapPin, CheckCircle2, Clock, AlertCircle, RefreshCw } from 'lucide-react';
import { Visit } from '../types';

interface Props {
  visits: Visit[];
  nextVisit: Visit | null;
  summary: { total: number; completed: number; remaining: number };
  onSelectVisit: (id: string | number) => void;
  onRefresh: () => void;
  loading: boolean;
}

const STAGE_COLOR: Record<string, string> = {
  Dispatched:     'text-blue-400  bg-blue-900/30  border-blue-700',
  'En Route':     'text-sky-400   bg-sky-900/30   border-sky-700',
  'On Site':      'text-amber-400 bg-amber-900/30 border-amber-700',
  'In Progress':  'text-orange-400 bg-orange-900/30 border-orange-700',
  Completed:      'text-emerald-400 bg-emerald-900/30 border-emerald-700',
};

function stageBadge(stage: string) {
  const cls = STAGE_COLOR[stage] ?? 'text-zinc-400 bg-zinc-800 border-zinc-700';
  return (
    <span className={`text-[9px] font-black uppercase tracking-widest px-2 py-0.5 rounded border ${cls}`}>
      {stage}
    </span>
  );
}

export function TodayScreen({ visits, nextVisit, summary, onSelectVisit, onRefresh, loading }: Props) {
  return (
    <div className="space-y-6 max-w-2xl mx-auto">
      {/* Header summary */}
      <div className="flex items-center justify-between">
        <div>
          <p className="text-[10px] text-zinc-500 font-black uppercase tracking-widest">
            {new Date().toLocaleDateString('en-AU', { weekday: 'long', day: 'numeric', month: 'long' })}
          </p>
          <h2 className="text-white font-black text-lg uppercase tracking-tight">Today's Visits</h2>
        </div>
        <button
          onClick={onRefresh}
          disabled={loading}
          className="p-2 text-zinc-500 hover:text-amber-400 transition-colors disabled:opacity-40"
          aria-label="Refresh"
        >
          <RefreshCw size={16} className={loading ? 'animate-spin' : ''} />
        </button>
      </div>

      {/* Summary bar */}
      <div className="grid grid-cols-3 gap-3">
        {[
          { label: 'Total',     value: summary.total,     color: 'text-zinc-300' },
          { label: 'Done',      value: summary.completed, color: 'text-emerald-400' },
          { label: 'Remaining', value: summary.remaining, color: 'text-amber-400' },
        ].map(s => (
          <div key={s.label} className="bg-zinc-900 border border-zinc-800 rounded-xl p-3 text-center">
            <p className={`text-2xl font-black ${s.color}`}>{s.value}</p>
            <p className="text-[9px] text-zinc-600 uppercase tracking-widest">{s.label}</p>
          </div>
        ))}
      </div>

      {/* Next visit preview */}
      {nextVisit && !nextVisit.isComplete && (
        <div className="bg-amber-500/10 border border-amber-500/30 rounded-xl p-4">
          <p className="text-[9px] text-amber-500 font-black uppercase tracking-widest mb-1">
            Next Visit
          </p>
          <p className="text-white font-bold text-sm">{nextVisit.name || nextVisit.customerName}</p>
          {nextVisit.scheduledStart && (
            <p className="text-zinc-400 text-xs mt-1 flex items-center gap-1">
              <Clock size={10} />
              {new Date(nextVisit.scheduledStart).toLocaleTimeString('en-AU', {
                hour: '2-digit', minute: '2-digit',
              })}
            </p>
          )}
        </div>
      )}

      {/* Visit list */}
      {visits.length === 0 && !loading && (
        <div className="text-center py-12 text-zinc-600">
          <CheckCircle2 size={40} className="mx-auto mb-3 opacity-30" />
          <p className="text-xs font-black uppercase tracking-widest">No visits scheduled today</p>
        </div>
      )}

      <div className="space-y-3">
        {visits.map(visit => (
          <button
            key={visit.id}
            onClick={() => onSelectVisit(visit.id)}
            className="w-full text-left bg-[#0f0f14] border border-zinc-800 hover:border-amber-500/40 rounded-xl p-4 transition-all active:scale-[0.98]"
          >
            <div className="flex items-start justify-between gap-2 mb-2">
              <div className="flex-1 min-w-0">
                <p className="text-white font-bold text-sm truncate">
                  {visit.name || visit.customerName || `Visit #${visit.id}`}
                </p>
                {visit.address && (
                  <p className="text-zinc-500 text-xs mt-0.5 flex items-center gap-1 truncate">
                    <MapPin size={10} />
                    {visit.address}
                  </p>
                )}
              </div>
              {visit.stage && stageBadge(visit.stage)}
            </div>

            <div className="flex items-center gap-3 text-[10px] text-zinc-600">
              {visit.scheduledStart && (
                <span className="flex items-center gap-1">
                  <Clock size={10} />
                  {new Date(visit.scheduledStart).toLocaleTimeString('en-AU', {
                    hour: '2-digit', minute: '2-digit',
                  })}
                </span>
              )}
              {visit.steps.length > 0 && (
                <span className="flex items-center gap-1">
                  <CheckCircle2 size={10} />
                  {visit.steps.filter(s => s.completed).length}/{visit.steps.length} steps
                </span>
              )}
              {visit.isComplete && (
                <span className="text-emerald-400 flex items-center gap-1 font-black uppercase">
                  <CheckCircle2 size={10} /> Complete
                </span>
              )}
            </div>
          </button>
        ))}
      </div>
    </div>
  );
}
