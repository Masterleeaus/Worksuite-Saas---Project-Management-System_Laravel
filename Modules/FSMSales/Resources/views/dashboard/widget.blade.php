@extends('fsmsales::layouts.master')

@section('fsmsales_content')
<h2 class="mb-4">Revenue Dashboard</h2>

<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card text-center border-primary">
            <div class="card-body">
                <div class="fs-3 fw-bold text-primary">${{ number_format($invoiced, 2) }}</div>
                <div class="text-muted small">Invoiced This Month</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-center border-success">
            <div class="card-body">
                <div class="fs-3 fw-bold text-success">${{ number_format($collected, 2) }}</div>
                <div class="text-muted small">Collected This Month</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-center border-warning">
            <div class="card-body">
                <div class="fs-3 fw-bold text-warning">${{ number_format($outstanding, 2) }}</div>
                <div class="text-muted small">Outstanding Balance</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-center {{ $overdueCount > 0 ? 'border-danger' : 'border-secondary' }}">
            <div class="card-body">
                <div class="fs-3 fw-bold {{ $overdueCount > 0 ? 'text-danger' : 'text-secondary' }}">{{ $overdueCount }}</div>
                <div class="text-muted small">Overdue Invoices</div>
            </div>
        </div>
    </div>
</div>

<div class="d-flex gap-2 flex-wrap">
    <a href="{{ route('fsmsales.invoices.index') }}" class="btn btn-outline-primary">All Invoices</a>
    <a href="{{ route('fsmsales.invoices.index', ['status' => 'overdue']) }}" class="btn btn-outline-danger">Overdue</a>
    <a href="{{ route('fsmsales.unbilled.index') }}" class="btn btn-outline-warning">Unbilled Orders</a>
    <a href="{{ route('fsmsales.recurring.index') }}" class="btn btn-outline-info">Recurring Queue</a>
    <a href="{{ route('fsmsales.bulk.create') }}" class="btn btn-outline-secondary">Bulk Invoice</a>
</div>
@endsection
