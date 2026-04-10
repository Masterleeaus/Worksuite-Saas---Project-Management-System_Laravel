@extends('fsmstock::layouts.master')
@section('fsmstock_content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0">Stock Moves</h4>
    <a href="{{ route('fsmstock.stock-moves.export') }}" class="btn btn-outline-secondary btn-sm">Export CSV</a>
</div>

{{-- Manual adjustment form --}}
<div class="card mb-4" style="max-width:720px">
    <div class="card-header">Manual Stock Adjustment</div>
    <div class="card-body">
        <form method="POST" action="{{ route('fsmstock.stock-moves.store') }}">
            @csrf
            <div class="row g-2 align-items-end">
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Product</label>
                    <select name="product_id" class="form-select" required>
                        <option value="">— Select —</option>
                        @foreach($moves->getCollection()->pluck('product')->filter()->unique('id') as $p)
                            <option value="{{ $p->id }}">{{ $p->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label fw-semibold">Qty</label>
                    <input type="number" name="qty" class="form-control" step="0.0001" min="0.001" required>
                </div>
                <div class="col-md-2">
                    <label class="form-label fw-semibold">Direction</label>
                    <select name="direction" class="form-select" required>
                        <option value="in">In</option>
                        <option value="out">Out</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold">Reason</label>
                    <input type="text" name="reason" class="form-control" maxlength="191">
                </div>
                <div class="col-md-1">
                    <button type="submit" class="btn btn-primary w-100">Save</button>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table table-sm table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>#</th><th>Product</th><th>Qty</th><th>Dir</th>
                    <th>Order</th><th>Worker</th><th>Reason</th><th>Moved At</th>
                </tr>
            </thead>
            <tbody>
                @forelse($moves as $move)
                <tr>
                    <td>{{ $move->id }}</td>
                    <td>{{ optional($move->product)->name ?? '-' }}</td>
                    <td>{{ $move->qty }}</td>
                    <td>
                        <span class="badge bg-{{ $move->direction === 'in' ? 'success' : 'danger' }}">
                            {{ strtoupper($move->direction) }}
                        </span>
                    </td>
                    <td>{{ $move->fsm_order_id ?? '-' }}</td>
                    <td>{{ optional($move->mover)->name ?? '-' }}</td>
                    <td>{{ $move->reason ?? '-' }}</td>
                    <td>{{ $move->moved_at ? $move->moved_at->format('d M Y H:i') : '-' }}</td>
                </tr>
                @empty
                <tr><td colspan="8" class="text-center text-muted">No moves found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-footer">{{ $moves->links() }}</div>
</div>
@endsection
