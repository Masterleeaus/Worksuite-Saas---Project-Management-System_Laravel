@extends('fsmequipment::layouts.master')

@section('fsmequipment_content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>Repair Orders</h2>
    <div class="d-flex gap-2">
        <a href="{{ route('fsmequipment.repair-orders.create') }}" class="btn btn-success">+ New Repair Order</a>
        <a href="{{ route('fsmequipment.downtime.index') }}" class="btn btn-outline-info">Downtime Report</a>
        <a href="{{ route('fsmequipment.repair-templates.index') }}" class="btn btn-outline-secondary">Templates</a>
    </div>
</div>

<form method="GET" class="row g-2 mb-3">
    <div class="col-md-3">
        <input type="text" name="q" class="form-control" placeholder="Search name…" value="{{ $filter['q'] ?? '' }}">
    </div>
    <div class="col-md-2">
        <select name="stage" class="form-select">
            <option value="">All Stages</option>
            @foreach($stages as $key => $label)
                <option value="{{ $key }}" {{ ($filter['stage'] ?? '') === $key ? 'selected' : '' }}>{{ $label }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-2">
        <select name="priority" class="form-select">
            <option value="">All Priorities</option>
            @foreach($priorities as $key => $label)
                <option value="{{ $key }}" {{ ($filter['priority'] ?? '') === $key ? 'selected' : '' }}>{{ $label }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-3">
        <select name="equipment_id" class="form-select">
            <option value="">All Equipment</option>
            @foreach($equipmentList as $eq)
                <option value="{{ $eq->id }}" {{ ($filter['equipment_id'] ?? '') == $eq->id ? 'selected' : '' }}>{{ $eq->name }}</option>
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
                <th>Reference</th>
                <th>Equipment</th>
                <th>Priority</th>
                <th>Stage</th>
                <th>Reported</th>
                <th>Assigned To</th>
                <th>Cost</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        @forelse($repairs as $repair)
            <tr>
                <td><a href="{{ route('fsmequipment.repair-orders.show', $repair->id) }}">{{ $repair->name }}</a></td>
                <td>{{ $repair->equipment?->name ?? '—' }}</td>
                <td>
                    @if($repair->priority === 'urgent')
                        <span class="badge bg-danger">Urgent</span>
                    @elseif($repair->priority === 'low')
                        <span class="badge bg-secondary">Low</span>
                    @else
                        <span class="badge bg-warning text-dark">Normal</span>
                    @endif
                </td>
                <td>
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
                </td>
                <td>{{ $repair->date_reported?->format('d M Y') ?? '—' }}</td>
                <td>{{ $repair->assignee?->name ?? '—' }}</td>
                <td>{{ $repair->cost !== null ? number_format($repair->cost, 2) : '—' }}</td>
                <td>
                    <a href="{{ route('fsmequipment.repair-orders.show', $repair->id) }}" class="btn btn-sm btn-outline-primary">View</a>
                    <a href="{{ route('fsmequipment.repair-orders.edit', $repair->id) }}" class="btn btn-sm btn-outline-secondary">Edit</a>
                    <form method="POST" action="{{ route('fsmequipment.repair-orders.destroy', $repair->id) }}" class="d-inline" onsubmit="return confirm('Delete repair order?')">
                        @csrf <button class="btn btn-sm btn-outline-danger">Delete</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr><td colspan="8" class="text-center text-muted py-4">No repair orders found.</td></tr>
        @endforelse
        </tbody>
    </table>
</div>
{{ $repairs->links() }}
@endsection
