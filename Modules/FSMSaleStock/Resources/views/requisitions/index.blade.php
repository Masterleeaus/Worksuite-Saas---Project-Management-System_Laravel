@extends('fsmsalestock::layouts.master')

@section('fsm_content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>Stock Requisitions</h2>
    <a href="{{ route('fsmsalestock.create') }}" class="btn btn-success">+ New Requisition</a>
</div>

<form method="GET" class="row g-2 mb-3">
    <div class="col-md-3">
        <select name="status" class="form-select">
            <option value="">— All Statuses —</option>
            @foreach($statuses as $key => $label)
                <option value="{{ $key }}" @selected(($filter['status'] ?? '') == $key)>{{ $label }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-2">
        <button type="submit" class="btn btn-primary">Filter</button>
        <a href="{{ route('fsmsalestock.index') }}" class="btn btn-outline-secondary">Clear</a>
    </div>
</form>

<div class="table-responsive">
    <table class="table table-hover align-middle">
        <thead class="table-light">
        <tr>
            <th>#</th>
            <th>FSM Order</th>
            <th>Invoice</th>
            <th>Status</th>
            <th>Requested</th>
            <th>Total Cost</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        @forelse($requisitions as $req)
            <tr>
                <td>{{ $req->id }}</td>
                <td>{{ $req->fsmOrder?->name ?? '—' }}</td>
                <td>{{ $req->invoice?->number ?? '—' }}</td>
                <td><span class="badge bg-secondary">{{ $statuses[$req->status] ?? $req->status }}</span></td>
                <td>{{ $req->requested_date?->format('d M Y') ?? '—' }}</td>
                <td>${{ number_format($req->totalCost(), 2) }}</td>
                <td>
                    <a href="{{ route('fsmsalestock.show', $req->id) }}" class="btn btn-sm btn-outline-info">View</a>
                    <a href="{{ route('fsmsalestock.edit', $req->id) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                    <form method="POST" action="{{ route('fsmsalestock.destroy', $req->id) }}" class="d-inline" onsubmit="return confirm('Delete this requisition?')">
                        @csrf
                        <button class="btn btn-sm btn-outline-danger">Delete</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr><td colspan="7" class="text-center text-muted py-4">No requisitions found.</td></tr>
        @endforelse
        </tbody>
    </table>
</div>
{{ $requisitions->links() }}
@endsection
