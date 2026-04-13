// Titan Go — IssueForm
// Structured issue escalation from the visit view.

import React, { useState } from 'react';
import { AlertTriangle, X } from 'lucide-react';
import { IssueType } from '../types';
import { apiClient } from '../services/api.service';
import { syncService } from '../services/sync.service';

interface Props {
  visitId: string | number;
  isOnline: boolean;
  onClose: () => void;
}

const ISSUE_TYPES: { type: IssueType; label: string; color: string }[] = [
  { type: 'access_blocked',    label: 'Access Blocked',     color: 'text-red-400' },
  { type: 'customer_absent',   label: 'Customer Absent',    color: 'text-orange-400' },
  { type: 'damage',            label: 'Damage Discovered',  color: 'text-red-500' },
  { type: 'extra_work',        label: 'Extra Work Required',color: 'text-amber-400' },
  { type: 'safety_risk',       label: 'Safety Risk',        color: 'text-red-600' },
  { type: 'equipment_missing', label: 'Equipment Missing',  color: 'text-purple-400' },
];

export function IssueForm({ visitId, isOnline, onClose }: Props) {
  const [issueType,    setIssueType]    = useState<IssueType | ''>('');
  const [description,  setDescription]  = useState('');
  const [submitting,   setSubmitting]   = useState(false);
  const [submitted,    setSubmitted]    = useState(false);

  const submit = async (e: React.FormEvent) => {
    e.preventDefault();
    if (!issueType) return;
    setSubmitting(true);

    const payload = { type: issueType, description: description || undefined };

    if (isOnline) {
      try {
        await apiClient.post(`/api/nexus/v1/jobs/${visitId}/issues`, payload);
      } catch {
        syncService.enqueue('issue', payload as Record<string, unknown>, visitId);
      }
    } else {
      syncService.enqueue('issue', payload as Record<string, unknown>, visitId);
    }

    setSubmitting(false);
    setSubmitted(true);
    setTimeout(onClose, 1500);
  };

  return (
    <div className="fixed inset-0 bg-black/70 backdrop-blur-sm z-50 flex items-end">
      <div className="w-full bg-[#0f0f14] border-t border-zinc-800 rounded-t-2xl p-6 space-y-5">
        {/* Header */}
        <div className="flex items-center justify-between">
          <div className="flex items-center gap-2 text-red-400">
            <AlertTriangle size={16} />
            <h2 className="text-sm font-black uppercase tracking-widest">Report Issue</h2>
          </div>
          <button onClick={onClose} className="text-zinc-500 hover:text-white">
            <X size={18} />
          </button>
        </div>

        {submitted ? (
          <p className="text-emerald-400 text-center font-black uppercase text-sm tracking-widest py-4">
            Issue Reported ✓
          </p>
        ) : (
          <form onSubmit={submit} className="space-y-4">
            {/* Type selection */}
            <div className="grid grid-cols-2 gap-2">
              {ISSUE_TYPES.map(it => (
                <button
                  key={it.type}
                  type="button"
                  onClick={() => setIssueType(it.type)}
                  className={`text-left p-3 rounded-xl border transition-all text-xs font-bold ${
                    issueType === it.type
                      ? 'border-amber-500 bg-amber-500/10'
                      : 'border-zinc-800 bg-zinc-900 hover:border-zinc-700'
                  }`}
                >
                  <span className={it.color}>{it.label}</span>
                </button>
              ))}
            </div>

            {/* Description */}
            <textarea
              value={description}
              onChange={e => setDescription(e.target.value)}
              placeholder="Additional details (optional)"
              rows={3}
              className="w-full bg-zinc-900 border border-zinc-800 rounded-xl px-4 py-3 text-sm text-zinc-300 placeholder-zinc-700 focus:outline-none focus:border-amber-500 resize-none"
            />

            <button
              type="submit"
              disabled={!issueType || submitting}
              className="w-full bg-red-600 hover:bg-red-500 disabled:opacity-40 text-white font-black text-xs uppercase tracking-widest py-4 rounded-xl transition-all active:scale-95"
            >
              {submitting ? 'Submitting…' : 'Submit Issue'}
            </button>
          </form>
        )}
      </div>
    </div>
  );
}
