@extends('fsmavailability::layouts.master')

@section('fsmavailability_content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>Team Availability Grid</h2>
    <div class="d-flex gap-2">
        <a href="{{ route('fsmavailability.calendar.index') }}" class="btn btn-outline-info">📅 Calendar</a>
        <a href="{{ route('fsmavailability.exceptions.create') }}" class="btn btn-primary">+ Request Leave</a>
    </div>
</div>

{{-- Week navigation --}}
<form method="GET" class="row g-2 mb-3 align-items-end">
    <div class="col-md-5">
        <label class="form-label fw-semibold">Week of</label>
        <div class="d-flex gap-2">
            <a href="?week={{ $weekStart->copy()->subWeek()->toDateString() }}"
               class="btn btn-outline-secondary">‹ Prev</a>
            <input type="date" name="week" class="form-control" value="{{ $weekStart->toDateString() }}"
                   onchange="this.form.submit()">
            <a href="?week={{ $weekStart->copy()->addWeek()->toDateString() }}"
               class="btn btn-outline-secondary">Next ›</a>
        </div>
    </div>
</form>

<div class="table-responsive">
    <table class="table table-bordered table-sm align-middle text-center">
        <thead class="table-light">
        <tr>
            <th class="text-start">Worker</th>
            @foreach($weekDays as $day)
                <th>{{ $day->format('D') }}<br><small>{{ $day->format('d M') }}</small></th>
            @endforeach
        </tr>
        </thead>
        <tbody>
        @foreach($workers as $worker)
            <tr>
                <td class="text-start fw-semibold">
                    <a href="{{ route('fsmavailability.calendar.index', ['person_id' => $worker->id, 'week' => $weekStart->toDateString()]) }}">
                        {{ $worker->name }}
                    </a>
                </td>
                @foreach($weekDays as $day)
                    @php
                        $status = $grid[$worker->id][$day->toDateString()] ?? 'no_rule';
                        [$cellClass, $icon] = match($status) {
                            'available'   => ['table-success', '✔'],
                            'unavailable' => ['table-danger',  '✘'],
                            default       => ['table-secondary', '—'],
                        };
                    @endphp
                    <td class="{{ $cellClass }}">{{ $icon }}</td>
                @endforeach
            </tr>
        @endforeach
        </tbody>
    </table>
</div>

<div class="d-flex gap-3 mt-2 small">
    <span class="badge bg-success">✔ Available</span>
    <span class="badge bg-danger">✘ Unavailable (approved exception)</span>
    <span class="badge bg-secondary">— No working-hour rule</span>
</div>
@endsection
