@extends('fsmsales::layouts.master')

@section('fsmsales_content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>Invoices</h2>
    <div class="d-flex gap-2">
        <a href="{{ route('fsmsales.invoices.create') }}" class="btn btn-primary">+ New Invoice</a>
        <a href="{{ route('fsmsales.bulk.create') }}" class="btn btn-outline-secondary">Bulk Create</a>
        <a href="{{ route('fsmsales.unbilled.index') }}" class="btn btn-outline-warning">Unbilled Orders</a>
        <a href="{{ route('fsmsales.recurring.index') }}" class="btn btn-outline-info">Recurring</a>
        <a href="{{ route('fsmsales.dashboard') }}" class="btn btn-outline-success">Dashboard</a>
    </div>
</div>

{{-- Filters --}}
<form method="GET" class="row g-2 mb-3">
    <div class="col-md-3">
        <select name="status" class="form-select form-select-sm">
            <option value="">All Statuses</option>
            @foreach($statuses as $key => $label)
                <option value="{{ $key }}" {{ ($filter['status'] ?? '') === $key ? 'selected' : '' }}>{{ $label }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-3">
        <input type="text" name="q" class="form-control form-control-sm" placeholder="Search number / notes…" value="{{ $filter['q'] ?? '' }}">
    </div>
    <div class="col-auto">
        <button class="btn btn-sm btn-secondary">Filter</button>
        <a href="{{ route('fsmsales.invoices.index') }}" class="btn btn-sm btn-outline-secondary">Reset</a>
    </div>
</form>

<div class="card">
    <div class="table-responsive">
        <table class="table table-sm table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>Number</th>
                    <th>Client</th>
                    <th>Date</th>
                    <th>Due</th>
                    <th class="text-end">Total</th>
                    <th class="text-end">Paid</th>
                    <th class="text-end">Balance</th>
                    <th>Status</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse($invoices as $invoice)
                @php $sl = $invoice->statusLabel(); @endphp
                <tr>
                    <td><a href="{{ route('fsmsales.invoices.show', $invoice->id) }}">{{ $invoice->number }}</a></td>
                    <td>{{ $invoice->client?->name ?? '—' }}</td>
                    <td>{{ $invoice->invoice_date->format('d M Y') }}</td>
                    <td>{{ $invoice->due_date?->format('d M Y') ?? '—' }}</td>
                    <td class="text-end">${{ number_format($invoice->total, 2) }}</td>
                    <td class="text-end">${{ number_format($invoice->amount_paid, 2) }}</td>
                    <td class="text-end">${{ number_format($invoice->balance_due, 2) }}</td>
                    <td><span class="badge {{ $sl['class'] }}">{{ $sl['label'] }}</span></td>
                    <td class="text-end">
                        <a href="{{ route('fsmsales.invoices.show', $invoice->id) }}" class="btn btn-xs btn-outline-primary btn-sm">View</a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="9" class="text-center text-muted py-4">No invoices found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-3">{{ $invoices->links() }}</div>
@endsection
