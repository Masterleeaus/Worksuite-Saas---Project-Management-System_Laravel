@extends('fsmsales::layouts.master')

@section('fsmsales_content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>Bulk Create Invoices</h2>
    <a href="{{ route('fsmsales.invoices.index') }}" class="btn btn-outline-secondary">← Invoices</a>
</div>

<div class="card" style="max-width: 600px;">
    <div class="card-header fw-semibold">Select Date Range</div>
    <div class="card-body">
        <p class="text-muted small">Creates draft invoices for all completed, unbilled FSM Orders whose actual end date falls within the selected period.</p>
        <form method="POST" action="{{ route('fsmsales.bulk.store') }}">
            @csrf
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">From <span class="text-danger">*</span></label>
                    <input type="date" name="from" class="form-control" value="{{ old('from', now()->startOfMonth()->toDateString()) }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">To <span class="text-danger">*</span></label>
                    <input type="date" name="to" class="form-control" value="{{ old('to', now()->toDateString()) }}" required>
                </div>
            </div>
            <div class="mt-3">
                <button type="submit" class="btn btn-primary">Create Invoices</button>
            </div>
        </form>
    </div>
</div>
@endsection
