@extends('fsmequipment::layouts.master')

@section('fsmequipment_content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>Equipment Downtime Report</h2>
    <a href="{{ route('fsmequipment.repair-orders.index') }}" class="btn btn-outline-secondary">Repair Orders</a>
</div>

<form method="GET" class="row g-2 mb-4 align-items-end">
    <div class="col-md-3">
        <label class="form-label">From</label>
        <input type="date" name="from" class="form-control" value="{{ $filter['from'] }}">
    </div>
    <div class="col-md-3">
        <label class="form-label">To</label>
        <input type="date" name="to" class="form-control" value="{{ $filter['to'] }}">
    </div>
    <div class="col-md-2">
        <button type="submit" class="btn btn-primary w-100">Run Report</button>
    </div>
</form>

@if(count($downtime) > 0)
<div class="table-responsive">
    <table class="table table-hover align-middle">
        <thead class="table-light">
            <tr>
                <th>Equipment</th>
                <th>Repair Orders</th>
                <th>Open Repairs</th>
                <th>Total Downtime (days)</th>
                <th>Total Repair Cost</th>
            </tr>
        </thead>
        <tbody>
        @foreach($downtime as $row)
            <tr>
                <td>{{ $row['equipment'] }}</td>
                <td>{{ $row['repair_count'] }}</td>
                <td>
                    @if($row['open_repairs'] > 0)
                        <span class="badge bg-warning text-dark">{{ $row['open_repairs'] }} open</span>
                    @else
                        <span class="text-muted">—</span>
                    @endif
                </td>
                <td>
                    @if($row['total_days'] > 7)
                        <span class="text-danger fw-semibold">{{ $row['total_days'] }}</span>
                    @else
                        {{ $row['total_days'] }}
                    @endif
                </td>
                <td>${{ number_format($row['total_cost'], 2) }}</td>
            </tr>
        @endforeach
        </tbody>
        <tfoot class="table-light">
            <tr>
                <th>Totals</th>
                <th>{{ array_sum(array_column($downtime, 'repair_count')) }}</th>
                <th>{{ array_sum(array_column($downtime, 'open_repairs')) }}</th>
                <th>{{ array_sum(array_column($downtime, 'total_days')) }}</th>
                <th>${{ number_format(array_sum(array_column($downtime, 'total_cost')), 2) }}</th>
            </tr>
        </tfoot>
    </table>
</div>
@else
    <div class="alert alert-info">No repair orders found for the selected period.</div>
@endif
@endsection
