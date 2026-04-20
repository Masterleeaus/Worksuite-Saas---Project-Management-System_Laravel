@extends('fsmsaleagreement::layouts.master')

@section('fsm_content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>Sale Agreements — Invoice Links</h2>
</div>

<div class="table-responsive">
    <table class="table table-hover align-middle">
        <thead class="table-light">
        <tr>
            <th>Invoice #</th>
            <th>Client</th>
            <th>Agreement ID</th>
            <th>Invoice Date</th>
            <th>Orders</th>
            <th>Synced</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        @forelse($invoices as $invoice)
            @php
                $synced = $invoice->orders->every(fn($o) => $o->agreement_id == $invoice->agreement_id);
            @endphp
            <tr>
                <td>{{ $invoice->number }}</td>
                <td>{{ $invoice->client?->name ?? '—' }}</td>
                <td>{{ $invoice->agreement_id }}</td>
                <td>{{ $invoice->invoice_date?->format('d M Y') ?? '—' }}</td>
                <td>{{ $invoice->orders->count() }}</td>
                <td>
                    @if($synced)
                        <span class="badge bg-success">Synced</span>
                    @else
                        <span class="badge bg-warning text-dark">Pending</span>
                    @endif
                </td>
                <td>
                    @unless($synced)
                        <form method="POST" action="{{ route('fsmsaleagreement.propagate', $invoice->id) }}" class="d-inline">
                            @csrf
                            <button class="btn btn-sm btn-outline-primary">Propagate</button>
                        </form>
                    @endunless
                </td>
            </tr>
        @empty
            <tr><td colspan="7" class="text-center text-muted py-4">No invoices linked to agreements.</td></tr>
        @endforelse
        </tbody>
    </table>
</div>
{{ $invoices->links() }}
@endsection
