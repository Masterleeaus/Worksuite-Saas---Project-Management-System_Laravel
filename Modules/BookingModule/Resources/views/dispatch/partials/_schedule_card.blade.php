@php
    $title = $schedule->name ?? $schedule->title ?? __('bookingmodule::dispatch.booking');
    $start = $schedule->start_time ?? '';
    $end = $schedule->end_time ?? '';
@endphp

<div class="dispatch-card" draggable="true"
     data-schedule-id="{{ $schedule->id }}"
     data-date="{{ $schedule->date }}"
     data-start-time="{{ $start }}"
     data-end-time="{{ $end }}">
    <div class="dispatch-card-title">{{ $title }}</div>
    <div class="dispatch-card-meta">
        <span class="badge bg-secondary">{{ $schedule->status ?? '' }}</span>
        <span class="text-muted">{{ $start }} - {{ $end }}</span>
    </div>
</div>
