@extends('fsmcore::layouts.master')

@section('fsm_content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>Recurring Schedules</h2>
    <div class="d-flex gap-2">
        <a href="{{ route('fsmrecurring.frequency-sets.index') }}" class="btn btn-outline-secondary">Frequency Sets</a>
        <a href="{{ route('fsmrecurring.frequencies.index') }}" class="btn btn-outline-secondary">Frequencies</a>
        <a href="{{ route('fsmrecurring.recurring.create') }}" class="btn btn-success">+ New Schedule</a>
    </div>
</div>

{{-- Filters --}}
<form method="GET" class="row g-2 mb-3">
    <div class="col-md-4">
        <input type="text" name="q" class="form-control" placeholder="Search…" value="{{ $filter['q'] ?? '' }}">
    </div>
    <div class="col-md-3">
        <select name="state" class="form-select">
            <option value="">All States</option>
            @foreach($states as $key => $label)
                <option value="{{ $key }}" {{ ($filter['state'] ?? '') === $key ? 'selected' : '' }}>{{ $label }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-2">
        <button type="submit" class="btn btn-primary w-100">Filter</button>
    </div>
</form>

<div class="table-responsive">
    <table class="table table-hover align-middle">
        <thead class="table-light">
        <tr>
            <th>Ref</th>
            <th>State</th>
            <th>Location</th>
            <th>Frequency Set</th>
            <th>Start Date</th>
            <th>End Date</th>
            <th>Orders</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        @forelse($recurrings as $rec)
            @php
                $stateColors = [
                    'draft'    => 'secondary',
                    'progress' => 'success',
                    'suspend'  => 'warning',
                    'close'    => 'dark',
                ];
            @endphp
            <tr>
                <td><a href="{{ route('fsmrecurring.recurring.show', $rec->id) }}">{{ $rec->name }}</a></td>
                <td><span class="badge bg-{{ $stateColors[$rec->state] ?? 'secondary' }}">{{ $states[$rec->state] ?? $rec->state }}</span></td>
                <td>{{ $rec->location?->name ?? '—' }}</td>
                <td>{{ $rec->frequencySet?->name ?? '—' }}</td>
                <td>{{ $rec->start_date?->format('d M Y') ?? '—' }}</td>
                <td>{{ $rec->end_date?->format('d M Y') ?? '∞' }}</td>
                <td><span class="badge bg-info text-dark">{{ $rec->orders()->count() }}</span></td>
                <td>
                    <a href="{{ route('fsmrecurring.recurring.show', $rec->id) }}" class="btn btn-sm btn-outline-info">View</a>
                    <a href="{{ route('fsmrecurring.recurring.edit', $rec->id) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                </td>
            </tr>
        @empty
            <tr><td colspan="8" class="text-center text-muted py-4">No recurring schedules found.</td></tr>
        @endforelse
        </tbody>
    </table>
</div>

{{ $recurrings->links() }}
@endsection
