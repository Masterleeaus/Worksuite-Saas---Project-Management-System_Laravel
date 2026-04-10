@extends('fsmstock::layouts.master')
@section('fsmstock_content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0">Stock &amp; Inventory Dashboard</h4>
</div>

<div class="row g-3 mb-4">
    <div class="col-sm-6 col-lg-3">
        <div class="card text-center border-primary">
            <div class="card-body">
                <h2 class="fw-bold text-primary">{{ $totalItems }}</h2>
                <div class="text-muted small">Total Items</div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="card text-center border-secondary">
            <div class="card-body">
                <h2 class="fw-bold text-secondary">{{ $totalCategories }}</h2>
                <div class="text-muted small">Categories</div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="card text-center border-danger">
            <div class="card-body">
                <h2 class="fw-bold text-danger">{{ $lowStockItems->count() }}</h2>
                <div class="text-muted small">Low Stock Items</div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="card text-center border-info">
            <div class="card-body">
                <h2 class="fw-bold text-info">{{ $recentMoves->count() }}</h2>
                <div class="text-muted small">Recent Moves (last 20)</div>
            </div>
        </div>
    </div>
</div>

@if($lowStockItems->isNotEmpty())
<div class="card mb-4">
    <div class="card-header bg-danger text-white">Low Stock Alerts</div>
    <div class="table-responsive">
        <table class="table table-sm table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>Name</th><th>Unit</th><th>Current Qty</th><th>Min Qty</th><th>Supplier</th><th></th>
                </tr>
            </thead>
            <tbody>
                @foreach($lowStockItems as $item)
                <tr>
                    <td>{{ $item->name }}</td>
                    <td>{{ $item->unit }}</td>
                    <td class="text-danger fw-bold">{{ $item->current_qty }}</td>
                    <td>{{ $item->min_qty }}</td>
                    <td>{{ $item->supplier ?? '-' }}</td>
                    <td><a href="{{ route('fsmstock.stock-items.edit', $item->id) }}" class="btn btn-xs btn-outline-primary">Edit</a></td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif

<div class="card">
    <div class="card-header">Recent Stock Moves</div>
    <div class="table-responsive">
        <table class="table table-sm table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>Product</th><th>Qty</th><th>Direction</th><th>Order</th><th>Worker</th><th>Moved At</th>
                </tr>
            </thead>
            <tbody>
                @forelse($recentMoves as $move)
                <tr>
                    <td>{{ optional($move->product)->name ?? '-' }}</td>
                    <td>{{ $move->qty }}</td>
                    <td>
                        <span class="badge bg-{{ $move->direction === 'in' ? 'success' : 'danger' }}">{{ strtoupper($move->direction) }}</span>
                    </td>
                    <td>{{ $move->fsm_order_id ?? '-' }}</td>
                    <td>{{ optional($move->mover)->name ?? '-' }}</td>
                    <td>{{ $move->moved_at ? $move->moved_at->format('d M Y H:i') : '-' }}</td>
                </tr>
                @empty
                <tr><td colspan="6" class="text-center text-muted">No recent moves.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
