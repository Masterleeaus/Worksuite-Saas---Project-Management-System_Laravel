@extends('fsmsalerecurring::layouts.master')

@section('fsm_content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>Link Recurring to Invoice Line</h2>
    <a href="{{ route('fsmsalerecurring.index') }}" class="btn btn-secondary">Back</a>
</div>

<div class="card mb-4">
    <div class="card-header">Recurring Schedule</div>
    <div class="card-body">
        <dl class="row mb-0">
            <dt class="col-sm-3">Name</dt>
            <dd class="col-sm-9">{{ $recurring->name }}</dd>
            <dt class="col-sm-3">Location</dt>
            <dd class="col-sm-9">{{ $recurring->location?->name ?? '—' }}</dd>
            <dt class="col-sm-3">State</dt>
            <dd class="col-sm-9">
                <span class="badge bg-secondary">
                    {{ \Modules\FSMRecurring\Models\FSMRecurring::$states[$recurring->state] ?? $recurring->state }}
                </span>
            </dd>
            <dt class="col-sm-3">Current Invoice Line</dt>
            <dd class="col-sm-9">
                @if($recurring->invoice_line_id)
                    #{{ $recurring->invoice_line_id }}
                @else
                    <span class="text-muted">Not linked</span>
                @endif
            </dd>
        </dl>
    </div>
</div>

<div class="card">
    <div class="card-header">Select Invoice Line</div>
    <div class="card-body">
        <form method="POST" action="{{ route('fsmsalerecurring.link', $recurring->id) }}">
            @csrf

            <div class="mb-3">
                <label for="invoice_select" class="form-label">Invoice</label>
                <select id="invoice_select" class="form-select" onchange="filterLines(this.value)">
                    <option value="">— Select an invoice —</option>
                    @foreach($invoices as $invoice)
                        <option value="{{ $invoice->id }}">
                            {{ $invoice->number }}
                            ({{ $invoice->invoice_date?->format('Y-m-d') ?? '—' }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label for="invoice_line_id" class="form-label">Invoice Line <span class="text-danger">*</span></label>
                <select id="invoice_line_id" name="invoice_line_id" class="form-select @error('invoice_line_id') is-invalid @enderror" required>
                    <option value="">— Select a line —</option>
                    @foreach($invoices as $invoice)
                        @foreach($invoice->items as $line)
                            <option value="{{ $line->id }}"
                                data-invoice="{{ $invoice->id }}"
                                style="display:none">
                                [{{ $invoice->number }}]
                                {{ $line->item_summary ?? $line->item_name ?? ('Line #' . $line->id) }}
                                — {{ number_format($line->amount ?? 0, 2) }}
                            </option>
                        @endforeach
                    @endforeach
                </select>
                @error('invoice_line_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary">Link</button>
            <a href="{{ route('fsmsalerecurring.index') }}" class="btn btn-secondary ms-2">Cancel</a>
        </form>
    </div>
</div>

<script>
function filterLines(invoiceId) {
    const lineSelect = document.getElementById('invoice_line_id');
    const options = lineSelect.querySelectorAll('option[data-invoice]');
    options.forEach(function(opt) {
        opt.style.display = (invoiceId && opt.dataset.invoice === invoiceId) ? '' : 'none';
    });
    lineSelect.value = '';
}
</script>
@endsection
