@extends('fsmcore::layouts.master')

@section('fsm_content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>FSM Equipment</h2>
    <a href="{{ route('fsmcore.equipment.create') }}" class="btn btn-success">+ New Equipment</a>
</div>

<form method="GET" class="row g-2 mb-3">
    <div class="col-md-4">
        <input type="text" name="q" class="form-control" placeholder="Search…" value="{{ $filter['q'] ?? '' }}">
    </div>
    <div class="col-md-2">
        <button type="submit" class="btn btn-primary">Filter</button>
    </div>
</form>

<div class="table-responsive">
    <table class="table table-hover align-middle">
        <thead class="table-light">
        <tr><th>Name</th><th>Category</th><th>Location</th><th>Warranty</th><th>Active</th><th>Actions</th></tr>
        </thead>
        <tbody>
        @forelse($equipment as $eq)
            <tr>
                <td><a href="{{ route('fsmcore.equipment.show', $eq->id) }}">{{ $eq->name }}</a></td>
                <td>{{ $eq->category ?? '—' }}</td>
                <td>{{ $eq->location?->name ?? '—' }}</td>
                <td>
                    @if(class_exists(\Modules\FSMEquipment\Models\EquipmentWarranty::class) && \Illuminate\Support\Facades\Schema::hasTable('fsm_equipment_warranties'))
                        @php
                            $w = \Modules\FSMEquipment\Models\EquipmentWarranty::where('equipment_id', $eq->id)
                                ->orderByDesc('warranty_end')->first();
                        @endphp
                        @if($w)
                            @if($w->warrantyStatus() === 'active')
                                <span class="badge bg-success">Under Warranty ✓</span>
                            @elseif($w->warrantyStatus() === 'expiring_soon')
                                <span class="badge bg-warning text-dark">Expiring Soon ⚠</span>
                            @else
                                <span class="badge bg-danger">Expired ✗</span>
                            @endif
                        @else
                            <span class="text-muted">—</span>
                        @endif
                    @else
                        {{ $eq->warranty_expiry?->format('d M Y') ?? '—' }}
                    @endif
                </td>
                <td>{{ $eq->active ? '✅' : '❌' }}</td>
                <td>
                    <a href="{{ route('fsmcore.equipment.show', $eq->id) }}" class="btn btn-sm btn-outline-info">View</a>
                    <a href="{{ route('fsmcore.equipment.edit', $eq->id) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                    @if(class_exists(\Modules\FSMEquipment\Http\Controllers\RepairOrderController::class))
                        <a href="{{ route('fsmequipment.repair-orders.create', ['equipment_id' => $eq->id]) }}" class="btn btn-sm btn-outline-danger">🔧 Fault</a>
                    @endif
                    <form method="POST" action="{{ route('fsmcore.equipment.destroy', $eq->id) }}" class="d-inline" onsubmit="return confirm('Delete?')">
                        @csrf <button class="btn btn-sm btn-outline-danger">Delete</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr><td colspan="6" class="text-center text-muted py-4">No equipment found.</td></tr>
        @endforelse
        </tbody>
    </table>
</div>
{{ $equipment->links() }}
@endsection
