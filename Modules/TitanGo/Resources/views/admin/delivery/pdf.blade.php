<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Proof of Delivery — #{{ $order->id }}</title>
<style>
/* ── Reset & base ──────────────────────────────────────────────────────── */
*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
body { font-family: DejaVu Sans, Arial, sans-serif; font-size: 11px; color: #1a1a1a; line-height: 1.45; }
h1   { font-size: 18px; font-weight: 700; }
h2   { font-size: 14px; font-weight: 700; margin-bottom: 4px; }
h3   { font-size: 12px; font-weight: 700; margin-bottom: 3px; }

/* ── Page layout ───────────────────────────────────────────────────────── */
.page       { padding: 28px 32px; max-width: 900px; margin: 0 auto; }

/* ── Header ────────────────────────────────────────────────────────────── */
.header     { display: flex; justify-content: space-between; align-items: flex-start;
              border-bottom: 2px solid #1d4ed8; padding-bottom: 12px; margin-bottom: 18px; }
.brand      { font-size: 10px; text-transform: uppercase; letter-spacing: 0.06em;
              color: #1d4ed8; font-weight: 700; }
.doc-title  { font-size: 20px; font-weight: 800; }
.doc-sub    { font-size: 10px; color: #6b7280; margin-top: 2px; }

/* ── Meta grid ─────────────────────────────────────────────────────────── */
.meta-grid  { display: table; width: 100%; border-collapse: collapse; margin-bottom: 18px; }
.meta-row   { display: table-row; }
.meta-cell  { display: table-cell; width: 25%; padding: 6px 8px; border: 1px solid #e5e7eb; vertical-align: top; }
.meta-label { font-size: 9px; text-transform: uppercase; color: #6b7280; letter-spacing: 0.07em; margin-bottom: 2px; }
.meta-value { font-weight: 600; font-size: 11px; }

/* ── Progress bar ──────────────────────────────────────────────────────── */
.progress-box   { margin-bottom: 14px; }
.progress-label { font-size: 10px; color: #374151; margin-bottom: 4px; }
.progress-track { height: 6px; background: #e5e7eb; border-radius: 3px; }
.progress-fill  { height: 6px; background: #16a34a; border-radius: 3px; }
.progress-fill.partial { background: #f59e0b; }

/* ── Checklist table ───────────────────────────────────────────────────── */
table        { width: 100%; border-collapse: collapse; margin-bottom: 16px; }
thead th     { background: #f3f4f6; font-size: 9px; text-transform: uppercase;
               letter-spacing: 0.07em; padding: 5px 7px; border: 1px solid #d1d5db; color: #374151; }
tbody td     { padding: 5px 7px; border: 1px solid #e5e7eb; vertical-align: top; }
tr.incomplete { background: #fffbeb; }
.badge       { display: inline-block; font-size: 8px; padding: 1px 5px; border-radius: 3px;
               font-weight: 700; text-transform: uppercase; }
.badge-done  { background: #dcfce7; color: #166534; }
.badge-skip  { background: #f3f4f6; color: #6b7280; }

/* ── Evidence images ───────────────────────────────────────────────────── */
.evidence-img { max-height: 56px; max-width: 80px; border: 1px solid #d1d5db;
                border-radius: 3px; object-fit: contain; margin-right: 4px; vertical-align: middle; }

/* ── Footer ────────────────────────────────────────────────────────────── */
.footer      { margin-top: 24px; border-top: 1px solid #d1d5db; padding-top: 8px;
               font-size: 9px; color: #9ca3af; display: flex; justify-content: space-between; }
</style>
</head>
<body>
@php
    $location  = $order->location;
    $worker    = $order->person;
    $stage     = $order->stage;
    $total     = $steps->count();
    $completed = $steps->where('completed', true)->count();
    $allDone   = $completed === $total && $total > 0;
    $pct       = $total > 0 ? round($completed / $total * 100) : 0;
@endphp

<div class="page">

    {{-- ── Header ─────────────────────────────────────────────────────── --}}
    <div class="header">
        <div>
            <div class="brand">Titan Go · Field Execution</div>
            <div class="doc-title">Proof of Delivery</div>
            <div class="doc-sub">Visit #{{ $order->id }}</div>
        </div>
        <div style="text-align:right">
            <div style="font-weight:700;font-size:12px">{{ $order->name }}</div>
            <div style="font-size:10px;color:#6b7280">
                {{ now()->format('d M Y') }}
            </div>
        </div>
    </div>

    {{-- ── Visit meta ─────────────────────────────────────────────────── --}}
    <div class="meta-grid">
        <div class="meta-row">
            <div class="meta-cell">
                <div class="meta-label">Location</div>
                <div class="meta-value">
                    @if($location)
                        {{ $location->name }}<br>
                        <span style="font-weight:400;font-size:10px">
                            {{ implode(', ', array_filter([
                                $location->street,
                                $location->city,
                                $location->state,
                                $location->zip,
                            ])) }}
                        </span>
                    @else
                        —
                    @endif
                </div>
            </div>
            <div class="meta-cell">
                <div class="meta-label">Technician</div>
                <div class="meta-value">{{ $worker?->name ?? '—' }}</div>
            </div>
            <div class="meta-cell">
                <div class="meta-label">Stage</div>
                <div class="meta-value">{{ $stage?->name ?? '—' }}</div>
            </div>
            <div class="meta-cell">
                <div class="meta-label">Date</div>
                <div class="meta-value">
                    {{ $order->date_start?->format('d M Y H:i') ?? '—' }}<br>
                    <span style="font-weight:400;font-size:10px">
                        to {{ $order->date_end?->format('H:i') ?? '—' }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    {{-- ── Progress ────────────────────────────────────────────────────── --}}
    <div class="progress-box">
        <div class="progress-label">
            Checklist: {{ $completed }} of {{ $total }} steps completed ({{ $pct }}%)
        </div>
        <div class="progress-track">
            <div class="progress-fill {{ $allDone ? '' : 'partial' }}" style="width:{{ $pct }}%"></div>
        </div>
    </div>

    {{-- ── Checklist table ─────────────────────────────────────────────── --}}
    <table>
        <thead>
        <tr>
            <th style="width:28px">#</th>
            <th>Step instruction</th>
            <th style="width:60px">Status</th>
            <th style="width:90px">Completed at</th>
            <th>Notes</th>
            <th style="width:100px">Evidence</th>
        </tr>
        </thead>
        <tbody>
        @forelse($steps as $step)
            <tr class="{{ $step['completed'] ? '' : 'incomplete' }}">
                <td style="color:#6b7280">{{ $step['index'] + 1 }}</td>
                <td>
                    {{ $step['instruction'] }}
                    @if($step['photo_required'])
                        <span class="badge" style="background:#dbeafe;color:#1d4ed8;margin-left:3px">📷</span>
                    @endif
                    @if($step['signature_required'])
                        <span class="badge" style="background:#fce7f3;color:#9d174d;margin-left:3px">✍</span>
                    @endif
                </td>
                <td>
                    @if($step['completed'])
                        <span class="badge badge-done">✓ Done</span>
                    @else
                        <span class="badge badge-skip">—</span>
                    @endif
                </td>
                <td style="font-size:9px;color:#6b7280">
                    {{ $step['completed_at'] ? \Carbon\Carbon::parse($step['completed_at'])->format('d M H:i') : '—' }}
                </td>
                <td style="font-size:10px">{{ $step['notes'] ?? '—' }}</td>
                <td>
                    @if($step['photo_data'])
                        <img class="evidence-img" src="{{ $step['photo_data'] }}" alt="Photo">
                    @endif
                    @if($step['signature_data'])
                        <img class="evidence-img" src="{{ $step['signature_data'] }}" alt="Signature">
                    @endif
                    @if(!$step['photo_data'] && !$step['signature_data'])
                        <span style="color:#9ca3af">—</span>
                    @endif
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="6" style="text-align:center;color:#9ca3af;padding:12px">
                    No checklist steps.
                </td>
            </tr>
        @endforelse
        </tbody>
    </table>

    {{-- ── Footer ─────────────────────────────────────────────────────── --}}
    <div class="footer">
        <span>Generated {{ now()->format('d M Y H:i') }} UTC · Titan Go by Worksuite</span>
        <span>Visit #{{ $order->id }} · {{ $order->name }}</span>
    </div>

</div>
</body>
</html>
