@extends('fsmavailability::layouts.master')

@section('fsmavailability_content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>Working-Hour Rules – {{ $worker->name }}</h2>
    <div class="d-flex gap-2">
        <a href="{{ route('fsmavailability.rules.create', $worker->id) }}" class="btn btn-primary">+ Add Rule</a>
        <a href="{{ route('fsmavailability.calendar.index', ['person_id' => $worker->id]) }}" class="btn btn-outline-info">📅 Calendar</a>
    </div>
</div>

@if($rules->isEmpty())
    <div class="alert alert-info">No working-hour rules defined for this worker.</div>
@else
<div class="table-responsive">
    <table class="table table-hover align-middle">
        <thead class="table-light">
        <tr>
            <th>Day</th>
            <th>Start</th>
            <th>End</th>
            <th>Active</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        @foreach($rules as $rule)
            <tr>
                <td>{{ \Modules\FSMAvailability\Models\FSMAvailabilityRule::$days[$rule->day_of_week] ?? $rule->day_of_week }}</td>
                <td>{{ $rule->time_start }}</td>
                <td>{{ $rule->time_end }}</td>
                <td>
                    @if($rule->active)
                        <span class="badge bg-success">Yes</span>
                    @else
                        <span class="badge bg-secondary">No</span>
                    @endif
                </td>
                <td>
                    <a href="{{ route('fsmavailability.rules.edit', [$worker->id, $rule->id]) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                    <form method="POST" action="{{ route('fsmavailability.rules.destroy', [$worker->id, $rule->id]) }}" class="d-inline"
                          onsubmit="return confirm('Delete this rule?')">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                    </form>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
@endif
@endsection
