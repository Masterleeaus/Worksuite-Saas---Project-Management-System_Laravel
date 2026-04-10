@extends('fsmroute::layouts.master')

@section('fsm_content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>{{ $dayRoute->name }}</h2>
    <div>
        <a href="{{ route('fsmroute.day_routes.board', ['date' => $dayRoute->date->format('Y-m-d')]) }}" class="btn btn-outline-info btn-sm me-1">📋 Board</a>
        <a href="{{ route('fsmroute.day_routes.print', $dayRoute->id) }}" class="btn btn-outline-secondary btn-sm me-1" target="_blank">🖨 Print</a>
        <a href="{{ route('fsmroute.day_routes.edit', $dayRoute->id) }}" class="btn btn-outline-primary btn-sm">Edit</a>
    </div>
</div>

{{-- FSMAvailability: flag banner when worker is unavailable --}}
@if(isset($dayRoute->availability_flagged) && $dayRoute->availability_flagged)
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        ⚠ <strong>Worker Unavailability Flag:</strong> The assigned worker has an approved leave/unavailability exception that overlaps this Day Route.
        This route requires reassignment.
        @if($dayRoute->person_id && class_exists(\Modules\FSMAvailability\Http\Controllers\AvailabilityCalendarController::class))
            <a href="{{ route('fsmavailability.calendar.index', ['person_id' => $dayRoute->person_id, 'week' => $dayRoute->date->toDateString()]) }}"
               class="alert-link ms-2">View Worker Calendar →</a>
        @endif
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="row mb-3">
    <div class="col-md-6">
        <table class="table table-bordered table-sm">
            <tr><th>Route</th><td>{{ $dayRoute->route?->name ?? '—' }}</td></tr>
            <tr><th>Date</th><td>{{ $dayRoute->date->format('D, M j Y') }}</td></tr>
            <tr>
                <th>Worker</th>
                <td>
                    {{ $dayRoute->person?->name ?? '—' }}
                    @if($dayRoute->person_id && class_exists(\Modules\FSMAvailability\Services\AvailabilityService::class) && \Illuminate\Support\Facades\Schema::hasTable('fsm_availability_exceptions'))
                        @php
                            $dayStart = $dayRoute->date->copy()->startOfDay();
                            $dayEnd   = $dayRoute->date->copy()->endOfDay();
                            $avail    = app(\Modules\FSMAvailability\Services\AvailabilityService::class)
                                ->checkAvailability($dayRoute->person_id, $dayStart, $dayEnd);
                        @endphp
                        @if(!$avail['available'])
                            <span class="badge bg-danger ms-1" title="{{ $avail['reason'] }}">Unavailable</span>
                        @else
                            <span class="badge bg-success ms-1">Available</span>
                        @endif
                    @endif
                </td>
            </tr>
            <tr><th>State</th><td><span class="badge bg-secondary">{{ ucfirst($dayRoute->state) }}</span></td></tr>
            <tr><th>Planned Start</th><td>{{ $dayRoute->date_start_planned?->format('H:i') ?? '—' }}</td></tr>
            <tr><th>Work Time</th><td>{{ $dayRoute->work_time }}h</td></tr>
            <tr><th>Max Allow Time</th><td>{{ $dayRoute->max_allow_time }}h</td></tr>
        </table>
    </div>
</div>

<h4>Orders ({{ $dayRoute->orderCount() }})</h4>
<ol class="list-group list-group-numbered">
    @forelse($dayRoute->orders as $order)
        <li class="list-group-item d-flex justify-content-between align-items-start">
            <div>
                <a href="{{ route('fsmcore.orders.show', $order->id) }}" class="fw-semibold text-decoration-none">
                    {{ $order->name }}
                </a>
                @if($order->location)
                    <br><small class="text-muted">📍 {{ $order->location->name }}
                        @if($order->location->street) — {{ $order->location->street }}, {{ $order->location->city }} @endif
                    </small>
                @endif
            </div>
            <span class="badge bg-light text-dark">#{{ $order->route_sequence + 1 }}</span>
        </li>
    @empty
        <li class="list-group-item text-muted">No orders assigned.</li>
    @endforelse
</ol>
@endsection
