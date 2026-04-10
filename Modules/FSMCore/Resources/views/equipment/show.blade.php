@extends('fsmcore::layouts.master')

@section('fsm_content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>Equipment: {{ $item->name }}</h2>
    <div class="d-flex gap-2">
        @if(class_exists(\Modules\FSMEquipment\Http\Controllers\RepairOrderController::class))
            <a href="{{ route('fsmequipment.repair-orders.create', ['equipment_id' => $item->id]) }}" class="btn btn-danger">🔧 Report Fault</a>
            <a href="{{ route('fsmequipment.warranties.index', $item->id) }}" class="btn btn-outline-info">Warranties</a>
        @endif
        <a href="{{ route('fsmcore.equipment.edit', $item->id) }}" class="btn btn-primary">Edit</a>
        <a href="{{ route('fsmcore.equipment.index') }}" class="btn btn-outline-secondary">Back</a>
    </div>
</div>

{{-- Warranty badge --}}
@if(class_exists(\Modules\FSMEquipment\Models\EquipmentWarranty::class) && \Illuminate\Support\Facades\Schema::hasTable('fsm_equipment_warranties'))
@php
    $activeWarranty = \Modules\FSMEquipment\Models\EquipmentWarranty::where('equipment_id', $item->id)
        ->orderByDesc('warranty_end')->first();
@endphp
@if($activeWarranty)
    @php $wStatus = $activeWarranty->warrantyStatus(); @endphp
    <div class="mb-3">
        @if($wStatus === 'active')
            <span class="badge bg-success fs-6">Under Warranty ✓ — expires {{ $activeWarranty->warranty_end->format('d M Y') }}</span>
        @elseif($wStatus === 'expiring_soon')
            <span class="badge bg-warning text-dark fs-6">Warranty Expiring in {{ (int) now()->diffInDays($activeWarranty->warranty_end) }} days ⚠ ({{ $activeWarranty->warranty_end->format('d M Y') }})</span>
        @else
            <span class="badge bg-danger fs-6">Warranty Expired ✗ ({{ $activeWarranty->warranty_end->format('d M Y') }})</span>
        @endif
    </div>
@else
    <div class="mb-3"><span class="badge bg-secondary">No Warranty Record</span></div>
@endif
@endif

<div class="row g-3">
    <div class="col-md-8">
        <div class="card mb-3">
            <div class="card-header fw-semibold">Equipment Details</div>
            <div class="card-body">
                <dl class="row mb-0">
                    <dt class="col-sm-4">Name</dt><dd class="col-sm-8">{{ $item->name }}</dd>
                    <dt class="col-sm-4">Category</dt><dd class="col-sm-8">{{ $item->category ?? '—' }}</dd>
                    <dt class="col-sm-4">Location</dt><dd class="col-sm-8">{{ $item->location?->name ?? '—' }}</dd>
                    <dt class="col-sm-4">Warranty Expiry</dt><dd class="col-sm-8">{{ $item->warranty_expiry?->format('d M Y') ?? '—' }}</dd>
                    <dt class="col-sm-4">Active</dt><dd class="col-sm-8">{{ $item->active ? '✅ Yes' : '❌ No' }}</dd>
                    <dt class="col-sm-4">Notes</dt><dd class="col-sm-8">{{ $item->notes ?? '—' }}</dd>
                </dl>
            </div>
        </div>

        {{-- Repair Orders panel (FSMEquipment module) --}}
        @if(class_exists(\Modules\FSMEquipment\Models\RepairOrder::class) && \Illuminate\Support\Facades\Schema::hasTable('fsm_repair_orders'))
        @php
            $repairOrders = \Modules\FSMEquipment\Models\RepairOrder::where('equipment_id', $item->id)
                ->orderByDesc('date_reported')->limit(10)->get();
        @endphp
        <div class="card mb-3">
            <div class="card-header fw-semibold d-flex justify-content-between align-items-center">
                <span>Repair Orders</span>
                <div class="d-flex gap-2">
                    <a href="{{ route('fsmequipment.repair-orders.create', ['equipment_id' => $item->id]) }}" class="btn btn-sm btn-danger">🔧 Report Fault</a>
                    <a href="{{ route('fsmequipment.repair-orders.index', ['equipment_id' => $item->id]) }}" class="btn btn-sm btn-outline-primary">View All</a>
                </div>
            </div>
            @if($repairOrders->isEmpty())
                <div class="card-body text-muted">No repair orders for this equipment.</div>
            @else
            <div class="card-body p-0">
                <table class="table table-sm mb-0">
                    <thead class="table-light">
                        <tr><th>Reference</th><th>Priority</th><th>Stage</th><th>Reported</th><th>Cost</th></tr>
                    </thead>
                    <tbody>
                    @foreach($repairOrders as $ro)
                        <tr>
                            <td><a href="{{ route('fsmequipment.repair-orders.show', $ro->id) }}">{{ $ro->name }}</a></td>
                            <td>
                                @if($ro->priority === 'urgent')
                                    <span class="badge bg-danger">Urgent</span>
                                @elseif($ro->priority === 'low')
                                    <span class="badge bg-secondary">Low</span>
                                @else
                                    <span class="badge bg-warning text-dark">Normal</span>
                                @endif
                            </td>
                            <td>{{ \Modules\FSMEquipment\Models\RepairOrder::STAGES[$ro->stage] ?? $ro->stage }}</td>
                            <td>{{ $ro->date_reported?->format('d M Y') ?? '—' }}</td>
                            <td>{{ $ro->cost !== null ? '$'.number_format($ro->cost, 2) : '—' }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            @endif
        </div>
        @endif
    </div>
</div>
@endsection
