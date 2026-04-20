@php $editing = isset($requisition) && $requisition; @endphp
<form method="POST" action="{{ $editing ? route('fsmsalestock.update', $requisition->id) : route('fsmsalestock.store') }}">
    @csrf

    <div class="row">
        <div class="col-md-6 mb-3">
            <label class="form-label fw-semibold">FSM Order</label>
            <select name="fsm_order_id" class="form-select @error('fsm_order_id') is-invalid @enderror">
                <option value="">— None —</option>
                @foreach($orders as $order)
                    <option value="{{ $order->id }}" @selected(old('fsm_order_id', $editing ? $requisition->fsm_order_id : '') == $order->id)>
                        {{ $order->name }}
                    </option>
                @endforeach
            </select>
            @error('fsm_order_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="col-md-6 mb-3">
            <label class="form-label fw-semibold">Invoice</label>
            <select name="invoice_id" class="form-select @error('invoice_id') is-invalid @enderror">
                <option value="">— None —</option>
                @foreach($invoices as $invoice)
                    <option value="{{ $invoice->id }}" @selected(old('invoice_id', $editing ? $requisition->invoice_id : '') == $invoice->id)>
                        {{ $invoice->number }}
                    </option>
                @endforeach
            </select>
            @error('invoice_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
    </div>

    <div class="row">
        <div class="col-md-4 mb-3">
            <label class="form-label fw-semibold">Status <span class="text-danger">*</span></label>
            <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                @foreach($statuses as $key => $label)
                    <option value="{{ $key }}" @selected(old('status', $editing ? $requisition->status : 'draft') == $key)>{{ $label }}</option>
                @endforeach
            </select>
            @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="col-md-4 mb-3">
            <label class="form-label fw-semibold">Requested Date</label>
            <input type="date" name="requested_date" class="form-control @error('requested_date') is-invalid @enderror"
                   value="{{ old('requested_date', $editing ? $requisition->requested_date?->toDateString() : '') }}">
            @error('requested_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
    </div>

    <div class="mb-3">
        <label class="form-label fw-semibold">Notes</label>
        <textarea name="notes" class="form-control @error('notes') is-invalid @enderror" rows="3">{{ old('notes', $editing ? $requisition->notes : '') }}</textarea>
        @error('notes')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <button type="submit" class="btn btn-primary">{{ $editing ? 'Update Requisition' : 'Create Requisition' }}</button>
    <a href="{{ route('fsmsalestock.index') }}" class="btn btn-secondary ms-1">Cancel</a>
</form>
