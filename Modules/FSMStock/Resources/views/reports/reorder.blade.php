@extends('fsmstock::layouts.master')
@section('fsmstock_content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0">Reorder Report — Items Below Minimum Qty</h4>
    <a href="{{ route('fsmstock.reports.export') }}" class="btn btn-outline-secondary btn-sm">Export CSV</a>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>Name</th><th>Category</th><th>Unit</th>
                    <th>Current Qty</th><th>Min Qty</th><th>Supplier</th>
                </tr>
            </thead>
            <tbody>
                @forelse($items as $item)
                <tr>
                    <td>{{ $item->name }}</td>
                    <td>{{ optional($item->category)->name ?? '-' }}</td>
                    <td>{{ $item->unit }}</td>
                    <td class="text-danger fw-bold">{{ $item->current_qty }}</td>
                    <td>{{ $item->min_qty }}</td>
                    <td>{{ $item->supplier ?? '-' }}</td>
                </tr>
                @empty
                <tr><td colspan="6" class="text-center text-success">All items are sufficiently stocked.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
