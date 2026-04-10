@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1">My Invoices</h2>
            <p class="text-muted mb-0">View and download your invoices.</p>
        </div>
    </div>

    @if($invoices instanceof \Illuminate\Pagination\LengthAwarePaginator && $invoices->isEmpty())
        <div class="card">
            <div class="card-body text-center text-muted py-5">
                <i class="fa fa-file-invoice fa-3x mb-3 d-block"></i>
                No invoices found.
            </div>
        </div>
    @elseif(is_iterable($invoices) && collect($invoices)->isEmpty())
        <div class="card">
            <div class="card-body text-center text-muted py-5">
                <i class="fa fa-file-invoice fa-3x mb-3 d-block"></i>
                No invoices found.
            </div>
        </div>
    @else
        <div class="card">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Invoice #</th>
                                <th>Issue Date</th>
                                <th>Due Date</th>
                                <th>Amount</th>
                                <th>Status</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($invoices as $invoice)
                            <tr>
                                <td>{{ $invoice->invoice_number ?? ('#' . $invoice->id) }}</td>
                                <td>{{ $invoice->issue_date ? \Carbon\Carbon::parse($invoice->issue_date)->format('d M Y') : '—' }}</td>
                                <td>{{ $invoice->due_date ? \Carbon\Carbon::parse($invoice->due_date)->format('d M Y') : '—' }}</td>
                                <td>
                                    {{ $invoice->currency_symbol ?? '$' }}{{ number_format($invoice->total ?? 0, 2) }}
                                </td>
                                <td>
                                    @php
                                        $statusClass = match($invoice->status ?? 'unpaid') {
                                            'paid'    => 'bg-success',
                                            'partial' => 'bg-warning text-dark',
                                            default   => 'bg-danger',
                                        };
                                    @endphp
                                    <span class="badge {{ $statusClass }}">{{ ucfirst($invoice->status ?? 'unpaid') }}</span>
                                </td>
                                <td class="text-end">
                                    @if(Route::has('customerconnect.portal.invoices.download'))
                                        <a href="{{ route('customerconnect.portal.invoices.download', $invoice->id) }}" class="btn btn-sm btn-outline-secondary" target="_blank">
                                            <i class="fa fa-download me-1"></i> PDF
                                        </a>
                                    @endif
                                    @if(in_array($invoice->status ?? 'unpaid', ['unpaid', 'partial']) && Route::has('customerconnect.portal.payments.pay-invoice'))
                                        <form action="{{ route('customerconnect.portal.payments.pay-invoice', $invoice->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-primary">Pay Now</button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @if($invoices instanceof \Illuminate\Pagination\LengthAwarePaginator && $invoices->hasPages())
                <div class="card-footer">
                    {{ $invoices->links() }}
                </div>
            @endif
        </div>
    @endif
</div>
@endsection
