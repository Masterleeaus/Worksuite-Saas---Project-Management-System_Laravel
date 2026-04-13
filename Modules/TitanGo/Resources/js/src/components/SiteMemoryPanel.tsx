// Titan Go — SiteMemoryPanel
// Displays site context: access codes, parking, hazards, preferences, etc.

import React, { useState, useEffect } from 'react';
import { KeyRound, Car, AlertTriangle, Wrench, UserCheck, FileText, RefreshCw } from 'lucide-react';
import { SiteMemory, SiteNote, SiteNoteType } from '../types';
import { apiClient } from '../services/api.service';

interface Props {
  visitId: string | number;
  isOnline: boolean;
}

const TYPE_META: Record<SiteNoteType, { label: string; icon: React.ElementType; color: string }> = {
  entry:       { label: 'Entry',        icon: FileText,    color: 'text-zinc-400' },
  access_code: { label: 'Access Code',  icon: KeyRound,    color: 'text-amber-400' },
  parking:     { label: 'Parking',      icon: Car,         color: 'text-blue-400' },
  equipment:   { label: 'Equipment',    icon: Wrench,      color: 'text-purple-400' },
  preference:  { label: 'Preferences',  icon: UserCheck,   color: 'text-emerald-400' },
  hazard:      { label: 'Hazard',       icon: AlertTriangle, color: 'text-red-400' },
  repeat:      { label: 'Repeat Notes', icon: RefreshCw,   color: 'text-sky-400' },
};

export function SiteMemoryPanel({ visitId, isOnline }: Props) {
  const [memory,  setMemory]  = useState<SiteMemory | null>(null);
  const [loading, setLoading] = useState(false);

  useEffect(() => {
    if (!isOnline) return;
    setLoading(true);
    apiClient
      .get<{ location_id: number; notes: any }>(`/api/nexus/v1/jobs/${visitId}/site-memory`)
      .then(data => setMemory({ locationId: data.location_id, notes: data.notes ?? {} }))
      .catch(e => console.warn('[SiteMemory] fetch failed', e))
      .finally(() => setLoading(false));
  }, [visitId, isOnline]);

  if (loading) {
    return (
      <div className="text-zinc-600 text-xs text-center py-6 animate-pulse">
        Loading site memory…
      </div>
    );
  }

  if (!memory || Object.keys(memory.notes).length === 0) {
    return (
      <div className="text-zinc-700 text-xs text-center py-6">
        No site memory recorded for this location.
      </div>
    );
  }

  const types = Object.keys(memory.notes) as SiteNoteType[];

  return (
    <div className="space-y-3">
      <h3 className="text-[10px] font-black uppercase tracking-widest text-zinc-500">
        Site Memory
      </h3>
      {types.map(type => {
        const meta  = TYPE_META[type] ?? { label: type, icon: FileText, color: 'text-zinc-400' };
        const Icon  = meta.icon;
        const items = memory.notes[type] ?? [];

        return (
          <div key={type} className="bg-zinc-900/50 border border-zinc-800 rounded-xl p-4 space-y-2">
            <div className={`flex items-center gap-2 ${meta.color}`}>
              <Icon size={14} />
              <span className="text-[10px] font-black uppercase tracking-widest">{meta.label}</span>
            </div>
            {items.map((note: SiteNote) => (
              <p key={note.id} className="text-sm text-zinc-300 pl-5">
                {note.content}
              </p>
            ))}
          </div>
        );
      })}
    </div>
  );
}
