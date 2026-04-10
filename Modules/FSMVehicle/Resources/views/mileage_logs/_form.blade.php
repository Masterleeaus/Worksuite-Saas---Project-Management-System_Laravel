<div class="row g-2">
    <div class="col-12">
        <label class="form-label">Date <span class="text-danger">*</span></label>
        <input type="date" name="log_date" class="form-control" required
               value="{{ old('log_date', date('Y-m-d')) }}">
    </div>
    <div class="col-6">
        <label class="form-label">Odometer Start (km) <span class="text-danger">*</span></label>
        <input type="number" name="odometer_start" class="form-control" required min="0"
               value="{{ old('odometer_start', $vehicle->current_mileage) }}">
    </div>
    <div class="col-6">
        <label class="form-label">Odometer End (km) <span class="text-danger">*</span></label>
        <input type="number" name="odometer_end" class="form-control" required min="0"
               value="{{ old('odometer_end') }}">
    </div>
    <div class="col-12">
        <label class="form-label">Related Job (optional)</label>
        <select name="fsm_order_id" class="form-select">
            <option value="">— None —</option>
            @foreach($vehicle->orders()->orderByDesc('id')->limit(50)->get() as $order)
                <option value="{{ $order->id }}" {{ old('fsm_order_id') == $order->id ? 'selected' : '' }}>
                    {{ $order->name }}
                </option>
            @endforeach
        </select>
    </div>
    <div class="col-12">
        <label class="form-label">Notes</label>
        <textarea name="notes" class="form-control" rows="2">{{ old('notes') }}</textarea>
    </div>
</div>
