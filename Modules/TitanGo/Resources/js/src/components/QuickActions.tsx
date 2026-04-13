// Titan Go — QuickActions
// Bottom quick-action bar for worker status signals.

import React from 'react';
import { MapPin, Clock, Lock, Package, CheckCircle2, AlertTriangle } from 'lucide-react';
import { WorkerStatusSignal } from '../types';
import { apiClient } from '../services/api.service';
import { syncService } from '../services/sync.service';

interface Props {
  visitId: string | number;
  isOnline: boolean;
  onAction?: (signal: WorkerStatusSignal) => void;
}

interface ActionDef {
  signal: WorkerStatusSignal;
  label: string;
  icon: React.ElementType;
  color: string;
}

const ACTIONS: ActionDef[] = [
  { signal: 'arrived',       label: 'Arrived',        icon: MapPin,         color: 'text-emerald-400' },
  { signal: 'running_late',  label: 'Running Late',   icon: Clock,          color: 'text-amber-400' },
  { signal: 'access_issue',  label: 'Access Issue',   icon: Lock,           color: 'text-red-400' },
  { signal: 'need_supplies', label: 'Need Supplies',  icon: Package,        color: 'text-purple-400' },
  { signal: 'job_complete',  label: 'Job Complete',   icon: CheckCircle2,   color: 'text-emerald-500' },
  { signal: 'report_issue',  label: 'Report Issue',   icon: AlertTriangle,  color: 'text-red-500' },
];

export function QuickActions({ visitId, isOnline, onAction }: Props) {
  const send = async (signal: WorkerStatusSignal) => {
    const payload = { status: signal };
    if (isOnline) {
      try {
        await apiClient.post(`/api/nexus/v1/jobs/${visitId}/status`, payload);
      } catch {
        syncService.enqueue('status', payload, visitId);
      }
    } else {
      syncService.enqueue('status', payload, visitId);
    }
    onAction?.(signal);
  };

  return (
    <div className="grid grid-cols-3 gap-2 py-3 px-4 border-t border-zinc-800 bg-[#0f0f14]/90 backdrop-blur-md">
      {ACTIONS.map(a => {
        const Icon = a.icon;
        return (
          <button
            key={a.signal}
            onClick={() => send(a.signal)}
            className="flex flex-col items-center gap-1 py-3 px-1 rounded-xl bg-zinc-900 hover:bg-zinc-800 border border-zinc-800 hover:border-zinc-700 transition-all active:scale-95"
          >
            <Icon size={18} className={a.color} />
            <span className="text-[8px] font-black uppercase tracking-widest text-zinc-500 text-center leading-tight">
              {a.label}
            </span>
          </button>
        );
      })}
    </div>
  );
}
