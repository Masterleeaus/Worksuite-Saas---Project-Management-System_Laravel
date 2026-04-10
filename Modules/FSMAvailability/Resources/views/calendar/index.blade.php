@extends('fsmavailability::layouts.master')

@section('fsmavailability_content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>Availability Calendar</h2>
    <a href="{{ route('fsmavailability.exceptions.create') }}" class="btn btn-primary">+ Request Leave</a>
</div>

{{-- Controls: worker selector + week nav --}}
<form method="GET" class="row g-2 mb-3 align-items-end">
    <div class="col-md-4">
        <label class="form-label fw-semibold">Worker</label>
        <select name="person_id" class="form-select" onchange="this.form.submit()">
            @foreach($workers as $w)
                <option value="{{ $w->id }}" {{ ($worker?->id == $w->id) ? 'selected' : '' }}>
                    {{ $w->name }}
                </option>
            @endforeach
        </select>
    </div>
    <div class="col-md-4">
        <label class="form-label fw-semibold">Week of</label>
        <div class="d-flex gap-2">
            <a href="?person_id={{ $worker?->id }}&week={{ $weekStart->copy()->subWeek()->toDateString() }}"
               class="btn btn-outline-secondary">‹ Prev</a>
            <input type="date" name="week" class="form-control" value="{{ $weekStart->toDateString() }}"
                   onchange="this.form.submit()">
            <a href="?person_id={{ $worker?->id }}&week={{ $weekStart->copy()->addWeek()->toDateString() }}"
               class="btn btn-outline-secondary">Next ›</a>
        </div>
    </div>
</form>

@if(!$worker)
    <div class="alert alert-info">Select a worker above to view their availability calendar.</div>
@else
<div class="table-responsive">
    <table class="table table-bordered text-center align-middle">
        <thead class="table-light">
        <tr>
            @foreach($weekDays as $day)
                <th>
                    {{ $day->format('D') }}<br>
                    <small>{{ $day->format('d M') }}</small>
                </th>
            @endforeach
        </tr>
        </thead>
        <tbody>
        <tr>
            @foreach($weekDays as $day)
                @php
                    $dow  = $dayMap[$day->isoWeekday()] ?? null;
                    $rule = $dow ? $rules->get($dow) : null;

                    // Check if any exception covers this day.
                    $dayException = null;
                    foreach ($exceptions as $ex) {
                        if ($ex->date_start->toDateString() <= $day->toDateString()
                            && $ex->date_end->toDateString() >= $day->toDateString()) {
                            $dayException = $ex;
                            break;
                        }
                    }

                    if ($dayException) {
                        $cellClass = 'table-danger';
                        $cellText  = '🚫 ' . (\Modules\FSMAvailability\Models\FSMAvailabilityException::$reasons[$dayException->reason] ?? $dayException->reason);
                    } elseif ($rule) {
                        $cellClass = 'table-success';
                        $cellText  = '✔ ' . $rule->time_start . '–' . $rule->time_end;
                    } else {
                        $cellClass = 'table-secondary';
                        $cellText  = '—';
                    }
                @endphp
                <td class="{{ $cellClass }}" style="min-width:110px;">
                    {!! $cellText !!}
                    @if($dayException && $dayException->notes)
                        <br><small class="text-muted">{{ Str::limit($dayException->notes, 30) }}</small>
                    @endif
                </td>
            @endforeach
        </tr>
        </tbody>
    </table>
</div>

<div class="d-flex gap-3 mt-2 small">
    <span class="badge bg-success">✔ Working hours defined</span>
    <span class="badge bg-danger">🚫 Exception (approved leave)</span>
    <span class="badge bg-secondary">— No working-hour rule</span>
</div>

<div class="mt-3">
    <a href="{{ route('fsmavailability.rules.index', $worker->id) }}" class="btn btn-outline-primary btn-sm">
        ⚙ Manage Working-Hour Rules
    </a>
    <a href="{{ route('fsmavailability.exceptions.index', ['person_id' => $worker->id]) }}" class="btn btn-outline-secondary btn-sm ms-2">
        📋 All Exceptions
    </a>
</div>
@endif
@endsection
