@extends('fsmavailability::layouts.master')

@section('fsmavailability_content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>Availability Exceptions</h2>
    <div class="d-flex gap-2">
        <a href="{{ route('fsmavailability.exceptions.create') }}" class="btn btn-primary">+ Request Leave</a>
        <a href="{{ route('fsmavailability.grid.index') }}" class="btn btn-outline-secondary">📊 Team Grid</a>
    </div>
</div>

{{-- Filters --}}
<form method="GET" class="row g-2 mb-3">
    <div class="col-md-4">
        <select name="person_id" class="form-select form-select-sm" onchange="this.form.submit()">
            <option value="">— All Workers —</option>
            @foreach($workers as $w)
                <option value="{{ $w->id }}" {{ ($filter['person_id'] ?? '') == $w->id ? 'selected' : '' }}>
                    {{ $w->name }}
                </option>
            @endforeach
        </select>
    </div>
    <div class="col-md-3">
        <select name="state" class="form-select form-select-sm" onchange="this.form.submit()">
            <option value="">— All States —</option>
            @foreach($states as $val => $label)
                <option value="{{ $val }}" {{ ($filter['state'] ?? '') === $val ? 'selected' : '' }}>
                    {{ $label }}
                </option>
            @endforeach
        </select>
    </div>
    @if(array_filter($filter))
        <div class="col-auto">
            <a href="{{ route('fsmavailability.exceptions.index') }}" class="btn btn-sm btn-outline-secondary">Clear</a>
        </div>
    @endif
</form>

<div class="table-responsive">
    <table class="table table-hover align-middle">
        <thead class="table-light">
        <tr>
            <th>Worker</th>
            <th>From</th>
            <th>To</th>
            <th>Reason</th>
            <th>State</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        @forelse($exceptions as $ex)
            @php
                $stateClass = match($ex->state) {
                    'approved' => 'bg-success',
                    'rejected' => 'bg-danger',
                    default    => 'bg-warning text-dark',
                };
            @endphp
            <tr>
                <td>{{ $ex->person?->name ?? '—' }}</td>
                <td>{{ $ex->date_start->format('d M Y H:i') }}</td>
                <td>{{ $ex->date_end->format('d M Y H:i') }}</td>
                <td>{{ \Modules\FSMAvailability\Models\FSMAvailabilityException::$reasons[$ex->reason] ?? $ex->reason }}</td>
                <td><span class="badge {{ $stateClass }}">{{ \Modules\FSMAvailability\Models\FSMAvailabilityException::$states[$ex->state] ?? $ex->state }}</span></td>
                <td>
                    <a href="{{ route('fsmavailability.exceptions.show', $ex->id) }}" class="btn btn-sm btn-outline-primary">View</a>
                </td>
            </tr>
        @empty
            <tr><td colspan="6" class="text-center text-muted py-4">No exceptions found.</td></tr>
        @endforelse
        </tbody>
    </table>
</div>

{{ $exceptions->links() }}
@endsection
