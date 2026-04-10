@extends('fsmvehicle::layouts.master')

@section('fsmvehicle_content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>Vehicle Fleet</h2>
    <div class="d-flex gap-2">
        <a href="{{ route('fsmvehicle.vehicles.report') }}" class="btn btn-outline-secondary">Mileage Report</a>
        <a href="{{ route('fsmvehicle.vehicles.create') }}" class="btn btn-success">+ Add Vehicle</a>
    </div>
</div>

{{-- Service Alerts --}}
@if($serviceAlerts->isNotEmpty())
<div class="alert alert-warning">
    <strong>⚠ Service Due:</strong>
    @foreach($serviceAlerts as $alert)
        <a href="{{ route('fsmvehicle.vehicles.show', $alert->id) }}" class="me-2">
            {{ $alert->name }} ({{ number_format($alert->current_mileage) }} km / next service at {{ number_format($alert->next_service_mileage) }} km)
        </a>
    @endforeach
</div>
@endif

{{-- Filters --}}
<form method="GET" class="row g-2 mb-3">
    <div class="col-md-4">
        <input type="text" name="q" class="form-control" placeholder="Search name, plate, make, model…"
               value="{{ $filter['q'] ?? '' }}">
    </div>
    <div class="col-md-3">
        <select name="active" class="form-select">
            <option value="">All</option>
            <option value="1" {{ ($filter['active'] ?? '') === '1' ? 'selected' : '' }}>Active</option>
            <option value="0" {{ ($filter['active'] ?? '') === '0' ? 'selected' : '' }}>Retired</option>
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
            <th>Vehicle</th>
            <th>Plate</th>
            <th>Make / Model</th>
            <th>Driver</th>
            <th>Mileage (km)</th>
            <th>Next Service (km)</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        @forelse($vehicles as $v)
            <tr>
                <td><a href="{{ route('fsmvehicle.vehicles.show', $v->id) }}">{{ $v->name }}</a></td>
                <td>{{ $v->license_plate ?? '—' }}</td>
                <td>{{ implode(' ', array_filter([$v->make, $v->model, $v->year ? "({$v->year})" : null])) ?: '—' }}</td>
                <td>{{ $v->driver?->name ?? '—' }}</td>
                <td>{{ number_format($v->current_mileage) }}</td>
                <td>
                    @if($v->next_service_mileage)
                        @if($v->isDueForService())
                            <span class="text-danger fw-bold">{{ number_format($v->next_service_mileage) }} ⚠</span>
                        @else
                            {{ number_format($v->next_service_mileage) }}
                        @endif
                    @else
                        —
                    @endif
                </td>
                <td>
                    @if($v->active)
                        <span class="badge bg-success">Active</span>
                    @else
                        <span class="badge bg-secondary">Retired</span>
                    @endif
                </td>
                <td>
                    <a href="{{ route('fsmvehicle.vehicles.edit', $v->id) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                    <form method="POST" action="{{ route('fsmvehicle.vehicles.destroy', $v->id) }}" class="d-inline"
                          onsubmit="return confirm('Delete this vehicle?')">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr><td colspan="8" class="text-center text-muted py-4">No vehicles found.</td></tr>
        @endforelse
        </tbody>
    </table>
</div>

{{ $vehicles->links() }}
@endsection
