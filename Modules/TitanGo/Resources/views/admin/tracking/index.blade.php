@extends('fsmcore::layouts.master')

@section('fsm_content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>Titan Go — Worker GPS Tracking</h2>
</div>

{{-- Filters --}}
<form method="GET" class="row g-2 mb-3">
    <div class="col-md-2">
        <input type="number" name="worker_id" class="form-control" placeholder="Worker ID"
               value="{{ $filter['worker_id'] ?? '' }}">
    </div>
    <div class="col-md-2">
        <input type="number" name="visit_id" class="form-control" placeholder="Visit ID"
               value="{{ $filter['visit_id'] ?? '' }}">
    </div>
    <div class="col-md-3">
        <input type="date" name="date" class="form-control"
               value="{{ $filter['date'] ?? '' }}">
    </div>
    <div class="col-md-2">
        <button type="submit" class="btn btn-primary w-100">Filter</button>
    </div>
    <div class="col-md-2">
        <a href="{{ route('titango.admin.tracking.index') }}" class="btn btn-outline-secondary w-100">Reset</a>
    </div>
</form>

<div class="table-responsive">
    <table class="table table-hover align-middle">
        <thead class="table-light">
        <tr>
            <th>#</th>
            <th>Worker ID</th>
            <th>Visit ID</th>
            <th>Mode</th>
            <th>Latitude</th>
            <th>Longitude</th>
            <th>Accuracy (m)</th>
            <th>Map</th>
            <th>Time</th>
        </tr>
        </thead>
        <tbody>
        @forelse($pings as $ping)
            <tr>
                <td>{{ $ping->id }}</td>
                <td>{{ $ping->worker_id }}</td>
                <td>{{ $ping->visit_id ?? '—' }}</td>
                <td>
                    <span class="badge {{ $ping->tracking_mode === 'foreground' ? 'bg-success' : 'bg-secondary' }}">
                        {{ $ping->tracking_mode ?? '—' }}
                    </span>
                </td>
                <td>{{ number_format($ping->latitude, 6) }}</td>
                <td>{{ number_format($ping->longitude, 6) }}</td>
                <td>{{ $ping->accuracy ? round($ping->accuracy, 1) : '—' }}</td>
                <td>
                    <a href="https://maps.google.com/?q={{ $ping->latitude }},{{ $ping->longitude }}"
                       target="_blank" rel="noopener noreferrer" class="btn btn-sm btn-outline-secondary">
                        Map ↗
                    </a>
                </td>
                <td>{{ $ping->created_at?->format('d/m/Y H:i:s') }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="9" class="text-center text-muted">No GPS pings found.</td>
            </tr>
        @endforelse
        </tbody>
    </table>
</div>

{{ $pings->links() }}
@endsection
