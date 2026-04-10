@extends('fsmcore::layouts.master')

@section('fsm_content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>{{ $location->name }}</h2>
    <div class="d-flex gap-2">
        <a href="{{ route('fsmcore.locations.edit', $location->id) }}" class="btn btn-primary">Edit</a>
        <a href="{{ route('fsmcore.locations.index') }}" class="btn btn-outline-secondary">Back</a>
    </div>
</div>

<div class="row g-3">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header fw-semibold">Location Details</div>
            <div class="card-body">
                <dl class="row mb-0">
                    <dt class="col-sm-4">Name</dt><dd class="col-sm-8">{{ $location->name }}</dd>
                    <dt class="col-sm-4">Territory</dt><dd class="col-sm-8">{{ $location->territory?->name ?? '—' }}</dd>
                    <dt class="col-sm-4">Street</dt><dd class="col-sm-8">{{ $location->street ?? '—' }}</dd>
                    <dt class="col-sm-4">City</dt><dd class="col-sm-8">{{ $location->city ?? '—' }}</dd>
                    <dt class="col-sm-4">State</dt><dd class="col-sm-8">{{ $location->state ?? '—' }}</dd>
                    <dt class="col-sm-4">ZIP</dt><dd class="col-sm-8">{{ $location->zip ?? '—' }}</dd>
                    <dt class="col-sm-4">Country</dt><dd class="col-sm-8">{{ $location->country ?? '—' }}</dd>
                    <dt class="col-sm-4">GPS</dt><dd class="col-sm-8">{{ $location->latitude ? $location->latitude.', '.$location->longitude : '—' }}</dd>
                    <dt class="col-sm-4">Notes</dt><dd class="col-sm-8">{{ $location->notes ?? '—' }}</dd>
                    <dt class="col-sm-4">Active</dt><dd class="col-sm-8">{{ $location->active ? 'Yes' : 'No' }}</dd>
                </dl>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header fw-semibold">Orders at this Location ({{ $location->orders->count() }})</div>
            <div class="card-body">
                @forelse($location->orders->take(10) as $order)
                    <div><a href="{{ route('fsmcore.orders.show', $order->id) }}">{{ $order->name }}</a></div>
                @empty
                    <span class="text-muted">No orders.</span>
                @endforelse
            </div>
        </div>
    </div>
</div>

@if(class_exists(\Modules\FSMStock\Models\FSMLocationEquipmentRegister::class) && \Illuminate\Support\Facades\Schema::hasTable('fsm_location_equipment_registers'))
<div class="row g-3 mt-1">
    <div class="col-12">
        <div class="card">
            <div class="card-header fw-semibold d-flex justify-content-between align-items-center">
                <span>🔧 Site Equipment Register</span>
                <a href="{{ route('fsmstock.location-equipment.index', $location->id) }}" class="btn btn-sm btn-outline-info">Manage</a>
            </div>
            <div class="card-body">
                @php
                    $siteEquipment = \Modules\FSMStock\Models\FSMLocationEquipmentRegister::with(['equipment', 'checkEvents' => fn($q) => $q->latest('checked_at')->take(1)])
                        ->where('location_id', $location->id)->where('active', true)->get();
                @endphp
                @if($siteEquipment->isEmpty())
                    <span class="text-muted">No equipment registered at this site.</span>
                @else
                <table class="table table-sm mb-0">
                    <thead class="table-light"><tr><th>Equipment</th><th>Last Check</th><th>Check Type</th></tr></thead>
                    <tbody>
                    @foreach($siteEquipment as $reg)
                        @php $lastCheck = $reg->checkEvents->first(); @endphp
                        <tr>
                            <td>{{ $reg->equipment?->name ?? '—' }}</td>
                            <td>{{ $lastCheck?->checked_at?->format('d M Y H:i') ?? '—' }}</td>
                            <td>{{ $lastCheck ? ($lastCheck->event_type === 'check_in' ? '✅ In' : '🔒 Out') : '—' }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                @endif
            </div>
        </div>
    </div>
</div>
@endif
@endsection
