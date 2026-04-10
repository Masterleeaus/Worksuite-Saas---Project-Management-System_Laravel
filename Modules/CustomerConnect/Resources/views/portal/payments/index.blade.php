@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="mb-4">
        <h2 class="mb-1">Payment History</h2>
        <p class="text-muted mb-0">View your payments and pay outstanding invoices online.</p>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('info'))
        <div class="alert alert-info alert-dismissible fade show" role="alert">
            {{ session('info') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Outstanding invoices --}}
    @if($outstandingInvoices->isNotEmpty())
    <div class="card mb-4 border-warning">
        <div class="card-header bg-warning text-dark">
            <h5 class="mb-0"><i class="fa fa-exclamation-circle me-2"></i>Outstanding Invoices</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Invoice #</th>
                            <th>Due Date</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($outstandingInvoices as $invoice)
                        <tr>
                            <td>{{ $invoice->invoice_number ?? ('#' . $invoice->id) }}</td>
                            <td>{{ $invoice->due_date ? \Carbon\Carbon::parse($invoice->due_date)->format('d M Y') : '—' }}</td>
                            <td>{{ $invoice->currency_symbol ?? '$' }}{{ number_format($invoice->total ?? 0, 2) }}</td>
                            <td><span class="badge bg-warning text-dark">{{ ucfirst($invoice->status ?? 'unpaid') }}</span></td>
                            <td class="text-end">
                                @if(Route::has('customerconnect.portal.payments.pay-invoice'))
                                    <form action="{{ route('customerconnect.portal.payments.pay-invoice', $invoice->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-primary">Pay Now</button>
                                    </form>
                                @endif
                                @if(Route::has('customerconnect.portal.invoices.download'))
                                    <a href="{{ route('customerconnect.portal.invoices.download', $invoice->id) }}" class="btn btn-sm btn-outline-secondary" target="_blank">
                                        <i class="fa fa-download"></i>
                                    </a>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif

    {{-- Payment history --}}
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0"><i class="fa fa-credit-card me-2 text-success"></i>Payment History</h5>
        </div>
        <div class="card-body p-0">
            @if($payments->isEmpty())
                <div class="p-4 text-center text-muted">No payment records found.</div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Date</th>
                                <th>Amount</th>
                                <th>Method</th>
                                <th>Reference</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($payments as $payment)
                            <tr>
                                <td>{{ isset($payment->paid_on) ? \Carbon\Carbon::parse($payment->paid_on)->format('d M Y') : '—' }}</td>
                                <td>{{ isset($payment->currency_symbol) ? $payment->currency_symbol : '$' }}{{ number_format($payment->amount ?? 0, 2) }}</td>
                                <td>{{ $payment->payment_gateway ?? $payment->type ?? '—' }}</td>
                                <td>{{ $payment->transaction_id ?? '—' }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
