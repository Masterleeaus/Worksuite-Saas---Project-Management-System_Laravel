@extends('fsmstock::layouts.master')
@section('fsmstock_content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0">Stock Items</h4>
    <a href="{{ route('fsmstock.stock-items.create') }}" class="btn btn-primary btn-sm">+ New Item</a>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>Name</th><th>Category</th><th>Unit</th>
                    <th>Current Qty</th><th>Min Qty</th><th>Cost Price</th>
                    <th>Supplier</th><th>Active</th><th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($items as $item)
                <tr>
                    <td>{{ $item->name }}</td>
                    <td>{{ optional($item->category)->name ?? '-' }}</td>
                    <td>{{ $item->unit }}</td>
                    <td class="{{ $item->isBelowMinQty() ? 'text-danger fw-bold' : '' }}">{{ $item->current_qty }}</td>
                    <td>{{ $item->min_qty }}</td>
                    <td>{{ number_format($item->cost_price, 2) }}</td>
                    <td>{{ $item->supplier ?? '-' }}</td>
                    <td>
                        <span class="badge bg-{{ $item->active ? 'success' : 'secondary' }}">
                            {{ $item->active ? 'Yes' : 'No' }}
                        </span>
                    </td>
                    <td>
                        <a href="{{ route('fsmstock.stock-items.edit', $item->id) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                        <form method="POST" action="{{ route('fsmstock.stock-items.destroy', $item->id) }}" class="d-inline"
                              onsubmit="return confirm('Delete this item?')">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="9" class="text-center text-muted">No stock items found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-footer">{{ $items->links() }}</div>
</div>
@endsection
