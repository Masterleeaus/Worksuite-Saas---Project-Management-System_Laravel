// Titan Go — ChecklistView
// Renders checklist steps with photo-required and signature-required indicators.

import React, { useRef } from 'react';
import { CheckCircle2, Camera, PenLine, FileText } from 'lucide-react';
import { ChecklistStep } from '../types';

interface Props {
  steps: ChecklistStep[];
  onComplete: (stepId: string, photo?: string, notes?: string) => void;
}

export function ChecklistView({ steps, onComplete }: Props) {
  const fileInputRef = useRef<HTMLInputElement>(null);
  const [activeStep, setActiveStep] = React.useState<string | null>(null);
  const [noteText,   setNoteText]   = React.useState('');

  const handleCapture = (stepId: string) => {
    setActiveStep(stepId);
    fileInputRef.current?.click();
  };

  const handleFileChange = (e: React.ChangeEvent<HTMLInputElement>) => {
    const file = e.target.files?.[0];
    if (!file || !activeStep) return;

    const reader = new FileReader();
    reader.onload = ev => {
      const data = ev.target?.result as string;
      onComplete(activeStep, data, noteText || undefined);
      setActiveStep(null);
      setNoteText('');
    };
    reader.readAsDataURL(file);
    e.target.value = '';
  };

  const completedCount = steps.filter(s => s.completed).length;

  return (
    <div className="space-y-3">
      <div className="flex items-center justify-between px-1">
        <h3 className="text-[10px] font-black uppercase tracking-widest text-zinc-500">
          Checklist
        </h3>
        <span className="text-[10px] font-black text-zinc-500">
          {completedCount}/{steps.length}
        </span>
      </div>

      {/* Hidden file input for photo capture */}
      <input
        ref={fileInputRef}
        type="file"
        accept="image/*"
        capture="environment"
        className="hidden"
        onChange={handleFileChange}
      />

      {steps.map((step, idx) => (
        <div
          key={step.id}
          className={`bg-zinc-900/50 border rounded-xl p-4 transition-all ${
            step.completed
              ? 'border-emerald-800/50 opacity-60'
              : 'border-zinc-800 hover:border-zinc-700'
          }`}
        >
          <div className="flex items-start gap-3">
            <div className="mt-0.5 shrink-0">
              {step.completed ? (
                <CheckCircle2 size={18} className="text-emerald-500" />
              ) : (
                <div className="w-[18px] h-[18px] rounded-full border-2 border-zinc-700" />
              )}
            </div>

            <div className="flex-1 min-w-0">
              <p className="text-sm text-zinc-300">
                <span className="text-[9px] font-black text-zinc-600 mr-2 uppercase">
                  {idx + 1}.
                </span>
                {step.instruction}
              </p>

              {/* Requirement badges */}
              <div className="flex gap-2 mt-2 flex-wrap">
                {step.isRequired && (
                  <span className="text-[8px] font-black uppercase text-red-400 bg-red-900/20 border border-red-800/30 px-1.5 py-0.5 rounded">
                    Required
                  </span>
                )}
                {step.photoRequired && (
                  <span className="text-[8px] font-black uppercase text-amber-400 bg-amber-900/20 border border-amber-800/30 px-1.5 py-0.5 rounded flex items-center gap-1">
                    <Camera size={8} /> Photo
                  </span>
                )}
                {step.signatureRequired && (
                  <span className="text-[8px] font-black uppercase text-blue-400 bg-blue-900/20 border border-blue-800/30 px-1.5 py-0.5 rounded flex items-center gap-1">
                    <PenLine size={8} /> Signature
                  </span>
                )}
              </div>

              {/* Notes if captured */}
              {step.notes && (
                <p className="text-xs text-zinc-500 mt-2 italic">{step.notes}</p>
              )}

              {/* Photo preview */}
              {step.photoCaptured && (
                <img
                  src={step.photoCaptured}
                  alt="Captured"
                  className="mt-2 w-20 h-20 object-cover rounded-lg border border-zinc-700"
                />
              )}
            </div>

            {/* Action button */}
            {!step.completed && (
              <button
                onClick={() =>
                  step.photoRequired
                    ? handleCapture(step.id)
                    : onComplete(step.id)
                }
                className="shrink-0 px-3 py-1.5 bg-amber-500/10 hover:bg-amber-500/20 border border-amber-500/30 text-amber-400 text-[10px] font-black uppercase rounded-lg transition-all"
              >
                {step.photoRequired ? <Camera size={14} /> : <CheckCircle2 size={14} />}
              </button>
            )}
          </div>
        </div>
      ))}
    </div>
  );
}
