@extends('fsmsales::layouts.master')

@section('fsmsales_content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>Unbilled Orders</h2>
    <div class="d-flex gap-2">
        <a href="{{ route('fsmsales.bulk.create') }}" class="btn btn-primary">Bulk Invoice</a>
        <a href="{{ route('fsmsales.invoices.index') }}" class="btn btn-outline-secondary">← Invoices</a>
    </div>
</div>

<form method="GET" class="row g-2 mb-3">
    <div class="col-md-2">
        <input type="date" name="from" class="form-control form-control-sm" value="{{ $filter['from'] ?? '' }}" placeholder="From">
    </div>
    <div class="col-md-2">
        <input type="date" name="to" class="form-control form-control-sm" value="{{ $filter['to'] ?? '' }}" placeholder="To">
    </div>
    <div class="col-md-3">
        <select name="team_id" class="form-select form-select-sm">
            <option value="">All Teams</option>
            @foreach($teams as $team)
                <option value="{{ $team->id }}" {{ ($filter['team_id'] ?? '') == $team->id ? 'selected' : '' }}>{{ $team->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-auto">
        <button class="btn btn-sm btn-secondary">Filter</button>
        <a href="{{ route('fsmsales.unbilled.index') }}" class="btn btn-sm btn-outline-secondary">Reset</a>
    </div>
</form>

<div class="card">
    <div class="table-responsive">
        <table class="table table-sm table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>Reference</th>
                    <th>Location</th>
                    <th>Team</th>
                    <th>Worker</th>
                    <th>Completed</th>
                    <th>Billing Policy</th>
                    <th>Amount</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $order)
                <tr>
                    <td><a href="{{ route('fsmcore.orders.show', $order->id) }}">{{ $order->name }}</a></td>
                    <td>{{ $order->location?->name ?? '—' }}</td>
                    <td>{{ $order->team?->name ?? '—' }}</td>
                    <td>{{ $order->person?->name ?? '—' }}</td>
                    <td>{{ $order->date_end?->format('d M Y H:i') ?? '—' }}</td>
                    <td>
                        @php $policies = config('fsmsales.billing_policies', []); @endphp
                        <span class="badge bg-secondary">{{ $policies[$order->billing_policy] ?? $order->billing_policy }}</span>
                    </td>
                    <td>{{ $order->billing_amount !== null ? '$' . number_format($order->billing_amount, 2) : '—' }}</td>
                    <td>
                        <form method="POST" action="{{ route('fsmsales.invoices.from-order', $order->id) }}">
                            @csrf
                            <button class="btn btn-sm btn-outline-primary">Invoice</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="8" class="text-center text-muted py-4">No unbilled completed orders found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-3">{{ $orders->links() }}</div>
@endsection
