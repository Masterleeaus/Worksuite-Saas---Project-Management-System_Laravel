@extends('fsmsalestock::layouts.master')

@section('fsm_content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>Stock Requisition #{{ $requisition->id }}</h2>
    <div>
        @if($requisition->status === 'draft')
            <form method="POST" action="{{ route('fsmsalestock.submit', $requisition->id) }}" class="d-inline">
                @csrf <button class="btn btn-primary">Submit</button>
            </form>
        @elseif($requisition->status === 'submitted')
            <form method="POST" action="{{ route('fsmsalestock.fulfill', $requisition->id) }}" class="d-inline">
                @csrf <button class="btn btn-success">Mark Fulfilled</button>
            </form>
        @elseif($requisition->status === 'fulfilled')
            <form method="POST" action="{{ route('fsmsalestock.bill', $requisition->id) }}" class="d-inline">
                @csrf <button class="btn btn-info">Mark Billed</button>
            </form>
        @endif
        <a href="{{ route('fsmsalestock.edit', $requisition->id) }}" class="btn btn-outline-secondary ms-1">Edit</a>
    </div>
</div>

<div class="card mb-4">
    <div class="card-body">
        <div class="row">
            <div class="col-md-3"><strong>FSM Order:</strong> {{ $requisition->fsmOrder?->name ?? '—' }}</div>
            <div class="col-md-3"><strong>Invoice:</strong> {{ $requisition->invoice?->number ?? '—' }}</div>
            <div class="col-md-3"><strong>Status:</strong> <span class="badge bg-secondary">{{ $statuses[$requisition->status] ?? $requisition->status }}</span></div>
            <div class="col-md-3"><strong>Requested:</strong> {{ $requisition->requested_date?->format('d M Y') ?? '—' }}</div>
        </div>
        @if($requisition->notes)
            <div class="mt-2"><strong>Notes:</strong> {{ $requisition->notes }}</div>
        @endif
    </div>
</div>

<h5>Line Items</h5>
<div class="table-responsive mb-3">
    <table class="table table-sm align-middle">
        <thead class="table-light">
        <tr>
            <th>Product Name</th>
            <th>Part #</th>
            <th>Qty</th>
            <th>Unit Cost</th>
            <th>Line Total</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        @forelse($requisition->lines as $line)
            <tr>
                <td>{{ $line->product_name }}</td>
                <td>{{ $line->part_number ?? '—' }}</td>
                <td>{{ $line->quantity }}</td>
                <td>${{ number_format($line->unit_cost, 2) }}</td>
                <td>${{ number_format($line->lineTotal(), 2) }}</td>
                <td>
                    <form method="POST" action="{{ route('fsmsalestock.lines.destroy', $line->id) }}" class="d-inline" onsubmit="return confirm('Remove this line?')">
                        @csrf <button class="btn btn-sm btn-outline-danger">Remove</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr><td colspan="6" class="text-muted text-center py-3">No line items yet.</td></tr>
        @endforelse
        </tbody>
        @if($requisition->lines->count())
        <tfoot>
            <tr>
                <td colspan="4" class="text-end fw-bold">Total:</td>
                <td class="fw-bold">${{ number_format($requisition->totalCost(), 2) }}</td>
                <td></td>
            </tr>
        </tfoot>
        @endif
    </table>
</div>

<h6>Add Line Item</h6>
<form method="POST" action="{{ route('fsmsalestock.lines.store', $requisition->id) }}" class="row g-2 align-items-end">
    @csrf
    <div class="col-md-3">
        <input type="text" name="product_name" class="form-control" placeholder="Product name *" required maxlength="256">
    </div>
    <div class="col-md-2">
        <input type="text" name="part_number" class="form-control" placeholder="Part #" maxlength="128">
    </div>
    <div class="col-md-1">
        <input type="number" name="quantity" class="form-control" placeholder="Qty" step="0.001" min="0.001" value="1" required>
    </div>
    <div class="col-md-2">
        <input type="number" name="unit_cost" class="form-control" placeholder="Unit cost" step="0.01" min="0" value="0" required>
    </div>
    <div class="col-md-2">
        <input type="text" name="notes" class="form-control" placeholder="Notes">
    </div>
    <div class="col-md-1">
        <button type="submit" class="btn btn-success w-100">Add</button>
    </div>
</form>
@endsection
