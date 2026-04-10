@extends('fsmsales::layouts.master')

@section('fsmsales_content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>Recurring Invoices</h2>
    <a href="{{ route('fsmsales.invoices.index') }}" class="btn btn-outline-secondary">← Invoices</a>
</div>

<form method="GET" class="row g-2 mb-3">
    <div class="col-md-2">
        <select name="status" class="form-select form-select-sm">
            <option value="">All Statuses</option>
            @foreach($statuses as $key => $label)
                <option value="{{ $key }}" {{ ($filter['status'] ?? '') === $key ? 'selected' : '' }}>{{ $label }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-2">
        <select name="schedule" class="form-select form-select-sm">
            <option value="">All Schedules</option>
            @foreach($schedules as $key => $label)
                <option value="{{ $key }}" {{ ($filter['schedule'] ?? '') === $key ? 'selected' : '' }}>{{ $label }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-auto">
        <button class="btn btn-sm btn-secondary">Filter</button>
        <a href="{{ route('fsmsales.recurring.index') }}" class="btn btn-sm btn-outline-secondary">Reset</a>
    </div>
</form>

<div class="card">
    <div class="table-responsive">
        <table class="table table-sm table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>Client</th>
                    <th>Schedule</th>
                    <th>Period</th>
                    <th class="text-end">Amount</th>
                    <th>Due</th>
                    <th>Status</th>
                    <th>Invoice</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse($recurring as $entry)
                @php $sl = $entry->statusLabel(); @endphp
                <tr>
                    <td><a href="{{ route('fsmsales.recurring.show', $entry->id) }}">#{{ $entry->id }}</a></td>
                    <td>{{ $entry->client?->name ?? '—' }}</td>
                    <td>{{ $entry->scheduleLabel() }}</td>
                    <td class="small">{{ $entry->period_start?->format('d M Y') ?? '—' }} → {{ $entry->period_end?->format('d M Y') ?? '—' }}</td>
                    <td class="text-end">${{ number_format($entry->amount, 2) }}</td>
                    <td class="{{ $entry->isOverdue() ? 'text-danger fw-semibold' : '' }}">
                        {{ $entry->due_date?->format('d M Y') ?? '—' }}
                    </td>
                    <td><span class="badge {{ $sl['class'] }}">{{ $sl['label'] }}</span></td>
                    <td>
                        @if($entry->fsm_sales_invoice_id)
                            <a href="{{ route('fsmsales.invoices.show', $entry->fsm_sales_invoice_id) }}" class="btn btn-xs btn-sm btn-outline-info">View</a>
                        @else —
                        @endif
                    </td>
                    <td>
                        @if($entry->status === 'draft')
                        <form method="POST" action="{{ route('fsmsales.recurring.convert', $entry->id) }}" class="d-inline">
                            @csrf
                            <button class="btn btn-sm btn-outline-primary">Convert</button>
                        </form>
                        @endif
                        @if(!in_array($entry->status, ['paid']))
                        <form method="POST" action="{{ route('fsmsales.recurring.mark-paid', $entry->id) }}" class="d-inline">
                            @csrf
                            <button class="btn btn-sm btn-outline-success">Paid</button>
                        </form>
                        @endif
                    </td>
                </tr>
                @empty
                <tr><td colspan="9" class="text-center text-muted py-4">No recurring invoices found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-3">{{ $recurring->links() }}</div>
@endsection
