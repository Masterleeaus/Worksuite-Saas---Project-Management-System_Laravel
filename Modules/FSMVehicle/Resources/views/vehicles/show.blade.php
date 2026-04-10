@extends('fsmvehicle::layouts.master')

@section('fsmvehicle_content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>
        {{ $vehicle->name }}
        @if(!$vehicle->active)
            <span class="badge bg-secondary ms-2">Retired</span>
        @endif
        @if($vehicle->isDueForService())
            <span class="badge bg-danger ms-2">⚠ Service Due</span>
        @endif
    </h2>
    <div class="d-flex gap-2">
        <a href="{{ route('fsmvehicle.vehicles.edit', $vehicle->id) }}" class="btn btn-outline-primary">Edit</a>
        <a href="{{ route('fsmvehicle.vehicles.index') }}" class="btn btn-outline-secondary">← Fleet</a>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-6">
        <div class="card h-100">
            <div class="card-header fw-semibold">Vehicle Details</div>
            <div class="card-body">
                <dl class="row mb-0">
                    <dt class="col-sm-5">License Plate</dt>
                    <dd class="col-sm-7">{{ $vehicle->license_plate ?? '—' }}</dd>
                    <dt class="col-sm-5">Make / Model / Year</dt>
                    <dd class="col-sm-7">{{ implode(' ', array_filter([$vehicle->make, $vehicle->model, $vehicle->year])) ?: '—' }}</dd>
                    <dt class="col-sm-5">VIN</dt>
                    <dd class="col-sm-7">{{ $vehicle->vin ?? '—' }}</dd>
                    <dt class="col-sm-5">Primary Driver</dt>
                    <dd class="col-sm-7">{{ $vehicle->driver?->name ?? '—' }}</dd>
                    <dt class="col-sm-5">Current Mileage</dt>
                    <dd class="col-sm-7">{{ number_format($vehicle->current_mileage) }} km</dd>
                    <dt class="col-sm-5">Last Service</dt>
                    <dd class="col-sm-7">{{ $vehicle->last_service_date?->format('d M Y') ?? '—' }}</dd>
                    <dt class="col-sm-5">Next Service at</dt>
                    <dd class="col-sm-7">
                        @if($vehicle->next_service_mileage)
                            {{ number_format($vehicle->next_service_mileage) }} km
                            @if($vehicle->isDueForService())
                                <span class="badge bg-danger ms-1">Due!</span>
                            @endif
                        @else
                            —
                        @endif
                    </dd>
                    @if($vehicle->notes)
                    <dt class="col-sm-5">Notes</dt>
                    <dd class="col-sm-7">{{ $vehicle->notes }}</dd>
                    @endif
                </dl>
            </div>
        </div>
    </div>

    {{-- Add Mileage Log --}}
    <div class="col-md-6">
        <div class="card h-100">
            <div class="card-header fw-semibold">Log Mileage</div>
            <div class="card-body">
                <form method="POST" action="{{ route('fsmvehicle.mileage.store', $vehicle->id) }}">
                    @csrf
                    @include('fsmvehicle::mileage_logs._form', ['vehicle' => $vehicle])
                    <div class="mt-3">
                        <button type="submit" class="btn btn-success w-100">Add Log</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- Mileage Log History --}}
<div class="card mb-4">
    <div class="card-header fw-semibold">Mileage Log History</div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-sm mb-0">
                <thead class="table-light">
                <tr>
                    <th>Date</th>
                    <th>Start (km)</th>
                    <th>End (km)</th>
                    <th>Driven (km)</th>
                    <th>Job</th>
                    <th>Logged by</th>
                    <th>Notes</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                @forelse($vehicle->mileageLogs->sortByDesc('log_date') as $log)
                    <tr>
                        <td>{{ $log->log_date->format('d M Y') }}</td>
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
                        <td>{{ $log->logger?->name ?? '—' }}</td>
                        <td>{{ $log->notes ?? '—' }}</td>
                        <td>
                            <form method="POST"
                                  action="{{ route('fsmvehicle.mileage.destroy', [$vehicle->id, $log->id]) }}"
                                  onsubmit="return confirm('Delete this log entry?')">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-outline-danger">×</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="8" class="text-center text-muted py-3">No mileage logs yet.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Job History --}}
<div class="card">
    <div class="card-header fw-semibold">Job / Order History</div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-sm mb-0">
                <thead class="table-light">
                <tr>
                    <th>Order</th>
                    <th>Stage</th>
                    <th>Scheduled Start</th>
                </tr>
                </thead>
                <tbody>
                @forelse($vehicle->orders->sortByDesc('id') as $order)
                    <tr>
                        <td><a href="{{ route('fsmcore.orders.show', $order->id) }}">{{ $order->name }}</a></td>
                        <td>
                            @if($order->stage)
                                <span class="badge" style="background:{{ $order->stage->color ?? '#6c757d' }};">{{ $order->stage->name }}</span>
                            @else
                                —
                            @endif
                        </td>
                        <td>{{ $order->scheduled_date_start?->format('d M Y H:i') ?? '—' }}</td>
                    </tr>
                @empty
                    <tr><td colspan="3" class="text-center text-muted py-3">No orders assigned.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
