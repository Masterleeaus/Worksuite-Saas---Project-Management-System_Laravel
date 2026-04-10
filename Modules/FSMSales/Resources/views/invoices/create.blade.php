@extends('fsmsales::layouts.master')

@section('fsmsales_content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>New Invoice</h2>
    <a href="{{ route('fsmsales.invoices.index') }}" class="btn btn-outline-secondary">← Invoices</a>
</div>

<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('fsmsales.invoices.store') }}">
            @csrf

            @if($order)
            <input type="hidden" name="order_ids[]" value="{{ $order->id }}">
            <div class="alert alert-info small">Creating invoice for FSM Order <strong>{{ $order->name }}</strong>.</div>
            @endif

            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Client</label>
                    <select name="client_id" class="form-select">
                        <option value="">— Select Client —</option>
                        @foreach($clients as $client)
                            <option value="{{ $client->id }}" {{ old('client_id', $order?->location?->partner_id ?? '') == $client->id ? 'selected' : '' }}>
                                {{ $client->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3">
                    <label class="form-label">Invoice Date <span class="text-danger">*</span></label>
                    <input type="date" name="invoice_date" class="form-control" value="{{ old('invoice_date', now()->toDateString()) }}" required>
                </div>

                <div class="col-md-3">
                    <label class="form-label">Due Date</label>
                    <input type="date" name="due_date" class="form-control" value="{{ old('due_date', now()->addDays(config('fsmsales.payment_terms_days', 14))->toDateString()) }}">
                </div>

                <div class="col-12">
                    <label class="form-label">Notes</label>
                    <textarea name="notes" class="form-control" rows="3">{{ old('notes') }}</textarea>
                </div>
            </div>

            <div class="mt-3">
                <button type="submit" class="btn btn-primary">Create Invoice</button>
                <a href="{{ route('fsmsales.invoices.index') }}" class="btn btn-outline-secondary ms-2">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
