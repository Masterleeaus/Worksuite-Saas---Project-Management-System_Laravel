// Titan Go — SignaturePad
// Canvas-based signature capture for checklist signature-required steps.
// Emits a base64 PNG string via onCapture when the worker confirms.

import React, { useRef, useState, useEffect, useCallback } from 'react';
import { X, RotateCcw, Check } from 'lucide-react';

interface Props {
  label?: string;
  onCapture: (dataUrl: string) => void;
  onCancel: () => void;
}

export function SignaturePad({ label, onCapture, onCancel }: Props) {
  const canvasRef = useRef<HTMLCanvasElement>(null);
  const [drawing, setDrawing]     = useState(false);
  const [hasStrokes, setHasStrokes] = useState(false);
  const lastPos = useRef<{ x: number; y: number } | null>(null);

  // Clear canvas on mount
  useEffect(() => {
    const canvas = canvasRef.current;
    if (!canvas) return;
    const ctx = canvas.getContext('2d');
    if (!ctx) return;
    ctx.fillStyle = '#18181b'; // zinc-900
    ctx.fillRect(0, 0, canvas.width, canvas.height);
  }, []);

  // --- Pointer helpers ---

  const getPos = (e: React.TouchEvent | React.MouseEvent, canvas: HTMLCanvasElement) => {
    const rect = canvas.getBoundingClientRect();
    const scaleX = canvas.width  / rect.width;
    const scaleY = canvas.height / rect.height;

    if ('touches' in e) {
      const t = e.touches[0];
      return {
        x: (t.clientX - rect.left) * scaleX,
        y: (t.clientY - rect.top)  * scaleY,
      };
    }
    return {
      x: ((e as React.MouseEvent).clientX - rect.left) * scaleX,
      y: ((e as React.MouseEvent).clientY - rect.top)  * scaleY,
    };
  };

  const startDraw = useCallback(
    (e: React.TouchEvent | React.MouseEvent) => {
      e.preventDefault();
      const canvas = canvasRef.current;
      if (!canvas) return;
      setDrawing(true);
      setHasStrokes(true);
      lastPos.current = getPos(e, canvas);
    },
    [],
  );

  const draw = useCallback(
    (e: React.TouchEvent | React.MouseEvent) => {
      if (!drawing) return;
      e.preventDefault();
      const canvas = canvasRef.current;
      if (!canvas || !lastPos.current) return;
      const ctx = canvas.getContext('2d');
      if (!ctx) return;

      const pos = getPos(e, canvas);
      ctx.beginPath();
      ctx.moveTo(lastPos.current.x, lastPos.current.y);
      ctx.lineTo(pos.x, pos.y);
      ctx.strokeStyle = '#f59e0b'; // amber-500
      ctx.lineWidth   = 2.5;
      ctx.lineCap     = 'round';
      ctx.lineJoin    = 'round';
      ctx.stroke();
      lastPos.current = pos;
    },
    [drawing],
  );

  const stopDraw = useCallback(() => {
    setDrawing(false);
    lastPos.current = null;
  }, []);

  const clear = () => {
    const canvas = canvasRef.current;
    if (!canvas) return;
    const ctx = canvas.getContext('2d');
    if (!ctx) return;
    ctx.fillStyle = '#18181b';
    ctx.fillRect(0, 0, canvas.width, canvas.height);
    setHasStrokes(false);
  };

  const confirm = () => {
    const canvas = canvasRef.current;
    if (!canvas || !hasStrokes) return;
    const dataUrl = canvas.toDataURL('image/png');
    onCapture(dataUrl);
  };

  return (
    <div className="fixed inset-0 bg-black/80 backdrop-blur-sm z-50 flex items-end">
      <div className="w-full bg-[#0f0f14] border-t border-zinc-800 rounded-t-2xl p-5 space-y-4">
        {/* Header */}
        <div className="flex items-center justify-between">
          <p className="text-[10px] font-black uppercase tracking-widest text-amber-400">
            {label ?? 'Sign Here'}
          </p>
          <button onClick={onCancel} className="text-zinc-500 hover:text-white">
            <X size={18} />
          </button>
        </div>

        {/* Canvas */}
        <canvas
          ref={canvasRef}
          width={800}
          height={260}
          className="w-full rounded-xl border border-zinc-800 touch-none cursor-crosshair"
          style={{ height: 180 }}
          onMouseDown={startDraw}
          onMouseMove={draw}
          onMouseUp={stopDraw}
          onMouseLeave={stopDraw}
          onTouchStart={startDraw}
          onTouchMove={draw}
          onTouchEnd={stopDraw}
        />

        <p className="text-[9px] text-zinc-700 text-center uppercase tracking-widest">
          Draw your signature above
        </p>

        {/* Actions */}
        <div className="flex gap-3">
          <button
            onClick={clear}
            className="flex-1 py-3 bg-zinc-900 border border-zinc-800 text-zinc-400 font-black text-[10px] uppercase rounded-xl flex items-center justify-center gap-2 hover:border-zinc-700 transition-all"
          >
            <RotateCcw size={13} /> Clear
          </button>
          <button
            onClick={confirm}
            disabled={!hasStrokes}
            className="flex-1 py-3 bg-emerald-600 hover:bg-emerald-500 disabled:opacity-40 text-white font-black text-[10px] uppercase rounded-xl flex items-center justify-center gap-2 transition-all active:scale-95"
          >
            <Check size={13} /> Confirm
          </button>
        </div>
      </div>
    </div>
  );
}
