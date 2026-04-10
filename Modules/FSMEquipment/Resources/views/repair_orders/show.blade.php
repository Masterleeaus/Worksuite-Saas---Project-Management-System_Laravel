@extends('fsmequipment::layouts.master')

@section('fsmequipment_content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>Repair Order: {{ $repair->name }}</h2>
    <div class="d-flex gap-2">
        <a href="{{ route('fsmequipment.repair-orders.edit', $repair->id) }}" class="btn btn-primary">Edit</a>
        <a href="{{ route('fsmequipment.repair-orders.index') }}" class="btn btn-outline-secondary">Back</a>
    </div>
</div>

<div class="row g-3">
    <div class="col-md-8">
        <div class="card mb-3">
            <div class="card-header fw-semibold">Repair Details</div>
            <div class="card-body">
                <dl class="row mb-0">
                    <dt class="col-sm-4">Reference</dt><dd class="col-sm-8">{{ $repair->name }}</dd>
                    <dt class="col-sm-4">Equipment</dt>
                    <dd class="col-sm-8">
                        {{ $repair->equipment?->name ?? '—' }}
                        @if($repair->under_warranty)
                            <span class="badge bg-success ms-1">Under Warranty ✓</span>
                        @endif
                    </dd>
                    <dt class="col-sm-4">Location</dt><dd class="col-sm-8">{{ $repair->location?->name ?? '—' }}</dd>
                    <dt class="col-sm-4">Template</dt><dd class="col-sm-8">{{ $repair->template?->name ?? '—' }}</dd>
                    <dt class="col-sm-4">FSM Order</dt>
                    <dd class="col-sm-8">
                        @if($repair->fsmOrder)
                            <a href="{{ route('fsmcore.orders.show', $repair->fsm_order_id) }}">{{ $repair->fsmOrder->name }}</a>
                        @else —
                        @endif
                    </dd>
                    <dt class="col-sm-4">Priority</dt>
                    <dd class="col-sm-8">
                        @if($repair->priority === 'urgent')
                            <span class="badge bg-danger">Urgent</span>
                        @elseif($repair->priority === 'low')
                            <span class="badge bg-secondary">Low</span>
                        @else
                            <span class="badge bg-warning text-dark">Normal</span>
                        @endif
                    </dd>
                    <dt class="col-sm-4">Stage</dt>
                    <dd class="col-sm-8">
                        @php
                            $stageBadge = [
                                'new'            => 'bg-secondary',
                                'in_progress'    => 'bg-primary',
                                'awaiting_parts' => 'bg-warning text-dark',
                                'completed'      => 'bg-success',
                                'cancelled'      => 'bg-danger',
                            ][$repair->stage] ?? 'bg-secondary';
                        @endphp
                        <span class="badge {{ $stageBadge }}">{{ \Modules\FSMEquipment\Models\RepairOrder::STAGES[$repair->stage] ?? $repair->stage }}</span>
                    </dd>
                    <dt class="col-sm-4">Reported By</dt><dd class="col-sm-8">{{ $repair->reporter?->name ?? '—' }}</dd>
                    <dt class="col-sm-4">Assigned To</dt><dd class="col-sm-8">{{ $repair->assignee?->name ?? '—' }}</dd>
                    <dt class="col-sm-4">Date Reported</dt><dd class="col-sm-8">{{ $repair->date_reported?->format('d M Y H:i') ?? '—' }}</dd>
                    <dt class="col-sm-4">Date Scheduled</dt><dd class="col-sm-8">{{ $repair->date_scheduled?->format('d M Y H:i') ?? '—' }}</dd>
                    <dt class="col-sm-4">Date Completed</dt><dd class="col-sm-8">{{ $repair->date_completed?->format('d M Y H:i') ?? '—' }}</dd>
                    @php $days = $repair->downtimeDays(); @endphp
                    @if($days !== null)
                    <dt class="col-sm-4">Downtime</dt><dd class="col-sm-8">{{ $days }} day(s)</dd>
                    @endif
                    <dt class="col-sm-4">Repair Cost</dt><dd class="col-sm-8">{{ $repair->cost !== null ? '$' . number_format($repair->cost, 2) : '—' }}</dd>
                    <dt class="col-sm-4">Description</dt><dd class="col-sm-8">{{ $repair->description ?? '—' }}</dd>
                    <dt class="col-sm-4">Parts Used</dt><dd class="col-sm-8">{{ $repair->parts_used ?? '—' }}</dd>
                    <dt class="col-sm-4">Root Cause</dt><dd class="col-sm-8">{{ $repair->root_cause ?? '—' }}</dd>
                </dl>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        @if($repair->equipment_id)
        <div class="card">
            <div class="card-header fw-semibold">Equipment</div>
            <div class="card-body">
                <strong>{{ $repair->equipment?->name }}</strong><br>
                <span class="text-muted">{{ $repair->equipment?->category ?? 'No category' }}</span>
                <div class="mt-2">
                    <a href="{{ route('fsmcore.equipment.edit', $repair->equipment_id) }}" class="btn btn-sm btn-outline-primary">View Equipment</a>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
