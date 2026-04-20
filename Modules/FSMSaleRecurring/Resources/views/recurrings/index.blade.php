@extends('fsmsalerecurring::layouts.master')

@section('fsm_content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>FSM Sale Recurring</h2>
</div>

<form method="GET" class="row g-2 mb-3">
    <div class="col-md-4">
        <input type="text" name="q" class="form-control" placeholder="Search by name…" value="{{ $filter['q'] ?? '' }}">
    </div>
    <div class="col-md-2">
        <button type="submit" class="btn btn-primary">Filter</button>
    </div>
</form>

<div class="table-responsive">
    <table class="table table-hover align-middle">
        <thead class="table-light">
        <tr>
            <th>Name</th>
            <th>Location</th>
            <th>State</th>
            <th>Invoice Line</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        @forelse($recurrings as $recurring)
            <tr>
                <td>{{ $recurring->name }}</td>
                <td>{{ $recurring->location?->name ?? '—' }}</td>
                <td>
                    <span class="badge bg-secondary">
                        {{ \Modules\FSMRecurring\Models\FSMRecurring::$states[$recurring->state] ?? $recurring->state }}
                    </span>
                </td>
                <td>
                    @if($recurring->invoice_line_id)
                        @php $line = \Modules\FSMSales\Models\FSMSalesInvoiceLine::with('invoice')->find($recurring->invoice_line_id); @endphp
                        @if($line && $line->invoice)
                            {{ $line->invoice->number ?? ('INV #' . $line->invoice->id) }}
                        @else
                            #{{ $recurring->invoice_line_id }}
                        @endif
                    @else
                        <span class="text-muted">—</span>
                    @endif
                </td>
                <td>
                    <a href="{{ route('fsmsalerecurring.link_form', $recurring->id) }}" class="btn btn-sm btn-outline-primary">Link</a>
                    @if($recurring->invoice_line_id)
                        <form method="POST" action="{{ route('fsmsalerecurring.unlink', $recurring->id) }}" class="d-inline" onsubmit="return confirm('Remove invoice line link?')">
                            @csrf
                            <button class="btn btn-sm btn-outline-warning">Unlink</button>
                        </form>
                    @endif
                </td>
            </tr>
        @empty
            <tr><td colspan="5" class="text-center text-muted py-4">No recurring schedules found.</td></tr>
        @endforelse
        </tbody>
    </table>
</div>
{{ $recurrings->links() }}
@endsection
