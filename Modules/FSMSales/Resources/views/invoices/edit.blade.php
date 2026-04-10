@extends('fsmsales::layouts.master')

@section('fsmsales_content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>Edit Invoice: {{ $invoice->number }}</h2>
    <a href="{{ route('fsmsales.invoices.show', $invoice->id) }}" class="btn btn-outline-secondary">← View</a>
</div>

<div class="row g-3">
    <div class="col-md-7">
        <div class="card mb-3">
            <div class="card-header fw-semibold">Invoice Details</div>
            <div class="card-body">
                <form method="POST" action="{{ route('fsmsales.invoices.update', $invoice->id) }}">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Client</label>
                            <select name="client_id" class="form-select">
                                <option value="">— None —</option>
                                @foreach($clients as $client)
                                    <option value="{{ $client->id }}" {{ $invoice->client_id == $client->id ? 'selected' : '' }}>
                                        {{ $client->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Invoice Date <span class="text-danger">*</span></label>
                            <input type="date" name="invoice_date" class="form-control" value="{{ $invoice->invoice_date->toDateString() }}" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Due Date</label>
                            <input type="date" name="due_date" class="form-control" value="{{ $invoice->due_date?->toDateString() }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select">
                                @foreach(config('fsmsales.invoice_statuses', []) as $key => $label)
                                    <option value="{{ $key }}" {{ $invoice->status === $key ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Amount Paid ($)</label>
                            <input type="number" step="0.01" min="0" name="amount_paid" class="form-control" value="{{ $invoice->amount_paid }}">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Notes</label>
                            <textarea name="notes" class="form-control" rows="2">{{ $invoice->notes }}</textarea>
                        </div>
                    </div>
                    <div class="mt-3">
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Add Line --}}
        <div class="card">
            <div class="card-header fw-semibold">Add Line Item</div>
            <div class="card-body">
                <form method="POST" action="{{ route('fsmsales.invoices.lines.add', $invoice->id) }}">
                    @csrf
                    <div class="row g-2">
                        <div class="col-md-2">
                            <select name="line_type" class="form-select form-select-sm">
                                <option value="service">Service</option>
                                <option value="timesheet">Timesheet</option>
                                <option value="stock">Stock</option>
                                <option value="equipment">Equipment</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <input type="text" name="description" class="form-control form-control-sm" placeholder="Description">
                        </div>
                        <div class="col-md-1">
                            <input type="number" step="0.01" min="0" name="qty" class="form-control form-control-sm" placeholder="Qty" value="1">
                        </div>
                        <div class="col-md-2">
                            <input type="number" step="0.01" min="0" name="unit_price" class="form-control form-control-sm" placeholder="Unit $">
                        </div>
                        <div class="col-md-2">
                            <input type="number" step="0.0001" min="0" max="1" name="tax_rate" class="form-control form-control-sm" placeholder="Tax (0.1=10%)">
                        </div>
                        <div class="col-md-1">
                            <button type="submit" class="btn btn-sm btn-success w-100">+</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-5">
        {{-- Existing lines --}}
        <div class="card">
            <div class="card-header fw-semibold">Lines</div>
            <div class="table-responsive">
                <table class="table table-sm mb-0">
                    <thead class="table-light"><tr><th>Desc</th><th class="text-end">Total</th><th></th></tr></thead>
                    <tbody>
                        @forelse($invoice->lines as $line)
                        <tr>
                            <td>
                                <span class="badge bg-secondary small">{{ ucfirst($line->line_type) }}</span>
                                {{ $line->description ?? '—' }}
                            </td>
                            <td class="text-end">${{ number_format($line->line_total, 2) }}</td>
                            <td>
                                <form method="POST" action="{{ route('fsmsales.invoices.lines.delete', [$invoice->id, $line->id]) }}">
                                    @csrf
                                    <button class="btn btn-xs btn-outline-danger btn-sm" onclick="return confirm('Remove line?')">×</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="3" class="text-muted text-center">No lines.</td></tr>
                        @endforelse
                    </tbody>
                    <tfoot class="table-light fw-semibold">
                        <tr><td class="text-end">Total</td><td class="text-end">${{ number_format($invoice->total, 2) }}</td><td></td></tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
