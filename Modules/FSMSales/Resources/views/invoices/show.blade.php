@extends('fsmsales::layouts.master')

@section('fsmsales_content')
@php $sl = $invoice->statusLabel(); @endphp

<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>Invoice: {{ $invoice->number }}</h2>
    <div class="d-flex gap-2">
        <a href="{{ route('fsmsales.invoices.edit', $invoice->id) }}" class="btn btn-primary">Edit</a>
        <form method="POST" action="{{ route('fsmsales.invoices.destroy', $invoice->id) }}" onsubmit="return confirm('Delete this invoice?');">
            @csrf
            <button class="btn btn-outline-danger">Delete</button>
        </form>
        <a href="{{ route('fsmsales.invoices.index') }}" class="btn btn-outline-secondary">← Invoices</a>
    </div>
</div>

<div class="row g-3">
    <div class="col-md-8">
        {{-- Invoice details --}}
        <div class="card mb-3">
            <div class="card-header fw-semibold d-flex justify-content-between">
                Invoice Details
                <span class="badge {{ $sl['class'] }}">{{ $sl['label'] }}</span>
            </div>
            <div class="card-body">
                <dl class="row mb-0">
                    <dt class="col-sm-4">Number</dt><dd class="col-sm-8">{{ $invoice->number }}</dd>
                    <dt class="col-sm-4">Client</dt><dd class="col-sm-8">{{ $invoice->client?->name ?? '—' }}</dd>
                    <dt class="col-sm-4">Invoice Date</dt><dd class="col-sm-8">{{ $invoice->invoice_date->format('d M Y') }}</dd>
                    <dt class="col-sm-4">Due Date</dt>
                    <dd class="col-sm-8">
                        {{ $invoice->due_date?->format('d M Y') ?? '—' }}
                        @if($invoice->isOverdue())
                            <span class="badge bg-danger ms-1">OVERDUE</span>
                        @endif
                    </dd>
                    @if($invoice->notes)
                    <dt class="col-sm-4">Notes</dt><dd class="col-sm-8">{{ $invoice->notes }}</dd>
                    @endif
                </dl>
            </div>
        </div>

        {{-- Line items --}}
        <div class="card mb-3">
            <div class="card-header fw-semibold">Line Items</div>
            <div class="table-responsive">
                <table class="table table-sm mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Type</th>
                            <th>Description</th>
                            <th class="text-end">Qty</th>
                            <th class="text-end">Unit Price</th>
                            <th class="text-end">Tax</th>
                            <th class="text-end">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($invoice->lines as $line)
                        <tr>
                            <td><span class="badge bg-secondary">{{ ucfirst($line->line_type) }}</span></td>
                            <td>{{ $line->description ?? '—' }}</td>
                            <td class="text-end">{{ number_format($line->qty, 2) }}</td>
                            <td class="text-end">${{ number_format($line->unit_price, 2) }}</td>
                            <td class="text-end">${{ number_format($line->line_tax, 2) }}</td>
                            <td class="text-end">${{ number_format($line->line_total, 2) }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="6" class="text-center text-muted">No lines yet.</td></tr>
                        @endforelse
                    </tbody>
                    <tfoot class="table-light fw-semibold">
                        <tr>
                            <td colspan="4" class="text-end">Subtotal</td>
                            <td colspan="2" class="text-end">${{ number_format($invoice->subtotal, 2) }}</td>
                        </tr>
                        <tr>
                            <td colspan="4" class="text-end">Tax</td>
                            <td colspan="2" class="text-end">${{ number_format($invoice->tax_total, 2) }}</td>
                        </tr>
                        <tr>
                            <td colspan="4" class="text-end">Total</td>
                            <td colspan="2" class="text-end fs-5">${{ number_format($invoice->total, 2) }}</td>
                        </tr>
                        <tr>
                            <td colspan="4" class="text-end text-success">Paid</td>
                            <td colspan="2" class="text-end text-success">${{ number_format($invoice->amount_paid, 2) }}</td>
                        </tr>
                        <tr>
                            <td colspan="4" class="text-end fw-bold {{ $invoice->balance_due > 0 ? 'text-danger' : 'text-success' }}">Balance Due</td>
                            <td colspan="2" class="text-end fw-bold {{ $invoice->balance_due > 0 ? 'text-danger' : 'text-success' }}">${{ number_format($invoice->balance_due, 2) }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        {{-- Linked Orders --}}
        @if($invoice->orders->isNotEmpty())
        <div class="card">
            <div class="card-header fw-semibold">Linked FSM Orders</div>
            <div class="table-responsive">
                <table class="table table-sm mb-0">
                    <thead class="table-light">
                        <tr><th>Reference</th><th>Location</th><th>Completed</th><th></th></tr>
                    </thead>
                    <tbody>
                        @foreach($invoice->orders as $order)
                        <tr>
                            <td>{{ $order->name }}</td>
                            <td>{{ $order->location?->name ?? '—' }}</td>
                            <td>{{ $order->date_end?->format('d M Y H:i') ?? '—' }}</td>
                            <td><a href="{{ route('fsmcore.orders.show', $order->id) }}" class="btn btn-xs btn-outline-primary btn-sm">View Order</a></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif
    </div>

    <div class="col-md-4">
        {{-- Payment summary widget --}}
        <div class="card">
            <div class="card-header fw-semibold">Payment Summary</div>
            <div class="card-body text-center">
                <div class="fs-3 fw-bold">${{ number_format($invoice->total, 2) }}</div>
                <div class="text-muted small mb-2">Total Invoice Value</div>
                <div class="progress mb-2" style="height:18px;">
                    @php $pct = $invoice->total > 0 ? min(100, round($invoice->amount_paid / $invoice->total * 100)) : 0; @endphp
                    <div class="progress-bar bg-success" style="width:{{ $pct }}%">{{ $pct }}%</div>
                </div>
                <div class="text-success">${{ number_format($invoice->amount_paid, 2) }} paid</div>
                <div class="{{ $invoice->balance_due > 0 ? 'text-danger' : 'text-success' }} mt-1 fw-semibold">
                    ${{ number_format($invoice->balance_due, 2) }} outstanding
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
