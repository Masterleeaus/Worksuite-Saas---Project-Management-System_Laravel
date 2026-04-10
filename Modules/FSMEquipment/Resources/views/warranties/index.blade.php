@extends('fsmequipment::layouts.master')

@section('fsmequipment_content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>Warranties: {{ $equipment->name }}</h2>
    <div class="d-flex gap-2">
        <a href="{{ route('fsmequipment.warranties.create', $equipment->id) }}" class="btn btn-success">+ Add Warranty</a>
        <a href="{{ route('fsmcore.equipment.edit', $equipment->id) }}" class="btn btn-outline-secondary">Back to Equipment</a>
    </div>
</div>

@php
    use Modules\FSMEquipment\Models\EquipmentWarranty;
    $activeWarranty = $warranties->first(fn($w) => $w->warrantyStatus() !== 'expired');
@endphp

@if($activeWarranty)
    @php $status = $activeWarranty->warrantyStatus(); @endphp
    @if($status === 'active')
        <div class="alert alert-success">✓ Under Warranty – expires {{ $activeWarranty->warranty_end->format('d M Y') }}</div>
    @elseif($status === 'expiring_soon')
        <div class="alert alert-warning">⚠ Warranty expiring in {{ now()->diffInDays($activeWarranty->warranty_end) }} day(s) – {{ $activeWarranty->warranty_end->format('d M Y') }}</div>
    @endif
@else
    <div class="alert alert-secondary">✗ No active warranty</div>
@endif

<div class="table-responsive">
    <table class="table table-hover align-middle">
        <thead class="table-light">
            <tr>
                <th>Warranty #</th>
                <th>Supplier</th>
                <th>Start</th>
                <th>End</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        @forelse($warranties as $warranty)
            @php $wStatus = $warranty->warrantyStatus(); @endphp
            <tr>
                <td>{{ $warranty->warranty_number ?? '—' }}</td>
                <td>{{ $warranty->supplier ?? '—' }}</td>
                <td>{{ $warranty->warranty_start->format('d M Y') }}</td>
                <td>{{ $warranty->warranty_end->format('d M Y') }}</td>
                <td>
                    @if($wStatus === 'active')
                        <span class="badge bg-success">Under Warranty ✓</span>
                    @elseif($wStatus === 'expiring_soon')
                        <span class="badge bg-warning text-dark">Expiring Soon ⚠</span>
                    @else
                        <span class="badge bg-danger">Expired ✗</span>
                    @endif
                </td>
                <td>
                    <a href="{{ route('fsmequipment.warranties.edit', [$equipment->id, $warranty->id]) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                    <form method="POST" action="{{ route('fsmequipment.warranties.destroy', [$equipment->id, $warranty->id]) }}" class="d-inline" onsubmit="return confirm('Delete warranty record?')">
                        @csrf <button class="btn btn-sm btn-outline-danger">Delete</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr><td colspan="6" class="text-center text-muted py-4">No warranty records found.</td></tr>
        @endforelse
        </tbody>
    </table>
</div>
{{ $warranties->links() }}
@endsection
