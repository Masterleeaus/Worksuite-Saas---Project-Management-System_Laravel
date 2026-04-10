@extends('fsmtimesheet::layouts.master')

@section('fsmtimesheet_content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>Timesheet Report</h2>
    <a href="{{ route('fsmtimesheet.report.export-csv', $filter) }}" class="btn btn-outline-success">
        ⬇ Export CSV
    </a>
</div>

{{-- Filters --}}
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('fsmtimesheet.report.index') }}" class="row g-2 align-items-end">
            <div class="col-md-3">
                <label class="form-label">Worker</label>
                <select name="user_id" class="form-select form-select-sm">
                    <option value="">— All workers —</option>
                    @foreach($users as $u)
                        <option value="{{ $u->id }}" {{ ($filter['user_id'] ?? '') == $u->id ? 'selected' : '' }}>
                            {{ $u->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">Date From</label>
                <input type="date" name="date_from" class="form-control form-control-sm"
                       value="{{ $filter['date_from'] ?? '' }}">
            </div>
            <div class="col-md-2">
                <label class="form-label">Date To</label>
                <input type="date" name="date_to" class="form-control form-control-sm"
                       value="{{ $filter['date_to'] ?? '' }}">
            </div>
            <div class="col-md-2">
                <label class="form-label">Order ID</label>
                <input type="number" name="fsm_order_id" class="form-control form-control-sm"
                       value="{{ $filter['fsm_order_id'] ?? '' }}" placeholder="Any">
            </div>
            <div class="col-md-auto">
                <button type="submit" class="btn btn-sm btn-primary">Filter</button>
                <a href="{{ route('fsmtimesheet.report.index') }}" class="btn btn-sm btn-outline-secondary">Reset</a>
            </div>
        </form>
    </div>
</div>

{{-- Total --}}
<div class="alert alert-info py-2 mb-3">
    Total hours (filtered): <strong>{{ number_format((float) $totalHours, 2) }}</strong>
</div>

{{-- Results --}}
<div class="card">
    <div class="card-body p-0">
        <table class="table table-sm mb-0">
            <thead class="table-light">
            <tr>
                <th>Date</th>
                <th>Order</th>
                <th>Location</th>
                <th>Worker</th>
                <th>Description</th>
                <th>Start</th>
                <th>End</th>
                <th>Hours</th>
            </tr>
            </thead>
            <tbody>
            @forelse($lines as $line)
                <tr>
                    <td>{{ $line->date?->format('d M Y') ?? '—' }}</td>
                    <td>
                        @if($line->order)
                            <a href="{{ route('fsmcore.orders.show', $line->order->id) }}">{{ $line->order->name }}</a>
                        @else
                            —
                        @endif
                    </td>
                    <td>{{ $line->order?->location?->name ?? '—' }}</td>
                    <td>{{ $line->user?->name ?? '—' }}</td>
                    <td>{{ $line->name ?? '—' }}</td>
                    <td>{{ $line->start_time ? substr($line->start_time, 0, 5) : '—' }}</td>
                    <td>{{ $line->end_time ? substr($line->end_time, 0, 5) : '—' }}</td>
                    <td>{{ number_format((float) $line->unit_amount, 2) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="text-center text-muted py-3">No records found.</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-3">{{ $lines->links() }}</div>
@endsection
