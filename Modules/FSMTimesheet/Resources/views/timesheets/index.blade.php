@extends('fsmtimesheet::layouts.master')

@section('fsmtimesheet_content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>Timesheets – Order: {{ $order->name }}</h2>
    <div class="d-flex gap-2">
        <a href="{{ route('fsmtimesheet.timesheets.create', $order->id) }}" class="btn btn-primary">+ Add Line</a>
        <a href="{{ route('fsmcore.orders.show', $order->id) }}" class="btn btn-outline-secondary">← Order</a>
    </div>
</div>

{{-- Hours summary widget --}}
@php
    $progress = $plannedHours > 0 ? min(100, round(($effectiveHours / $plannedHours) * 100)) : 0;
    $progressClass = $progress >= 100 ? 'bg-danger' : ($progress >= 75 ? 'bg-warning' : 'bg-success');
@endphp
<div class="card mb-4">
    <div class="card-header fw-semibold">Hours Summary</div>
    <div class="card-body">
        <div class="row text-center mb-3">
            <div class="col">
                <div class="fs-4 fw-bold">{{ number_format($plannedHours, 2) }}</div>
                <div class="text-muted small">Planned Hours</div>
            </div>
            <div class="col">
                <div class="fs-4 fw-bold text-primary">{{ number_format($effectiveHours, 2) }}</div>
                <div class="text-muted small">Effective Hours</div>
            </div>
            <div class="col">
                <div class="fs-4 fw-bold {{ $remainingHours <= 0 ? 'text-danger' : 'text-success' }}">
                    {{ number_format($remainingHours, 2) }}
                </div>
                <div class="text-muted small">Remaining Hours</div>
            </div>
        </div>
        @if($plannedHours > 0)
        <div class="progress" style="height: 20px;">
            <div class="progress-bar {{ $progressClass }}" role="progressbar"
                 style="width: {{ $progress }}%;" aria-valuenow="{{ $progress }}"
                 aria-valuemin="0" aria-valuemax="100">
                {{ $progress }}%
            </div>
        </div>
        <div class="text-muted small mt-1">{{ $progress }}% of planned hours logged</div>
        @else
            <div class="text-muted small">No planned hours set on this order (add scheduled dates to see progress).</div>
        @endif
    </div>
</div>

{{-- Timesheet lines --}}
<div class="card">
    <div class="card-header fw-semibold">Timesheet Lines</div>
    <div class="card-body p-0">
        <table class="table table-sm mb-0">
            <thead class="table-light">
            <tr>
                <th>Date</th>
                <th>Worker</th>
                <th>Description</th>
                <th>Start</th>
                <th>End</th>
                <th>Hours</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            @forelse($lines as $line)
                <tr>
                    <td>{{ $line->date?->format('d M Y') ?? '—' }}</td>
                    <td>{{ $line->user?->name ?? '—' }}</td>
                    <td>{{ $line->name ?? '—' }}</td>
                    <td>{{ $line->start_time ? substr($line->start_time, 0, 5) : '—' }}</td>
                    <td>{{ $line->end_time ? substr($line->end_time, 0, 5) : '—' }}</td>
                    <td>{{ number_format((float) $line->unit_amount, 2) }}</td>
                    <td class="d-flex gap-1">
                        <a href="{{ route('fsmtimesheet.timesheets.edit', [$order->id, $line->id]) }}"
                           class="btn btn-sm btn-outline-primary">Edit</a>
                        <form method="POST"
                              action="{{ route('fsmtimesheet.timesheets.destroy', [$order->id, $line->id]) }}"
                              onsubmit="return confirm('Delete this timesheet line?')">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center text-muted py-3">No timesheet lines yet.</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
