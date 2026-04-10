@extends('fsmvehicle::layouts.master')

@section('fsmvehicle_content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>Mileage Report</h2>
    <a href="{{ route('fsmvehicle.vehicles.index') }}" class="btn btn-outline-secondary">← Fleet</a>
</div>

<form method="GET" class="row g-2 mb-4">
    <div class="col-md-3">
        <label class="form-label">Vehicle</label>
        <select name="vehicle_id" class="form-select">
            <option value="">All Vehicles</option>
            @foreach($vehicles as $v)
                <option value="{{ $v->id }}" {{ $vehicleId == $v->id ? 'selected' : '' }}>{{ $v->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-3">
        <label class="form-label">From</label>
        <input type="date" name="from" class="form-control" value="{{ $from?->format('Y-m-d') }}">
    </div>
    <div class="col-md-3">
        <label class="form-label">To</label>
        <input type="date" name="to" class="form-control" value="{{ $to?->format('Y-m-d') }}">
    </div>
    <div class="col-md-3 d-flex align-items-end">
        <button type="submit" class="btn btn-primary w-100">Generate</button>
    </div>
</form>

@if($logs->isNotEmpty())
<div class="alert alert-info mb-3">
    Total km driven: <strong>{{ number_format($totalKm) }} km</strong>
    across {{ $logs->count() }} log entries.
</div>

<div class="table-responsive">
    <table class="table table-hover align-middle">
        <thead class="table-light">
        <tr>
            <th>Date</th>
            <th>Vehicle</th>
            <th>Start (km)</th>
            <th>End (km)</th>
            <th>Driven (km)</th>
            <th>Job</th>
            <th>Notes</th>
        </tr>
        </thead>
        <tbody>
        @foreach($logs as $log)
            <tr>
                <td>{{ $log->log_date->format('d M Y') }}</td>
                <td>
                    <a href="{{ route('fsmvehicle.vehicles.show', $log->vehicle->id) }}">
                        {{ $log->vehicle->name }}
                    </a>
                </td>
                <td>{{ number_format($log->odometer_start) }}</td>
                <td>{{ number_format($log->odometer_end) }}</td>
                <td><strong>{{ number_format($log->km_driven) }}</strong></td>
                <td>
                    @if($log->order)
                        <a href="{{ route('fsmcore.orders.show', $log->order->id) }}">{{ $log->order->name }}</a>
                    @else
                        —
                    @endif
                </td>
                <td>{{ $log->notes ?? '—' }}</td>
            </tr>
        @endforeach
        </tbody>
        <tfoot>
        <tr class="table-secondary">
            <td colspan="4" class="text-end fw-semibold">Total</td>
            <td class="fw-bold">{{ number_format($totalKm) }} km</td>
            <td colspan="2"></td>
        </tr>
        </tfoot>
    </table>
</div>
@else
<p class="text-muted">No mileage logs found for the selected filters.</p>
@endif
@endsection
