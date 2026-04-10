<div class="mb-3">
    <label class="form-label fw-semibold">Day of Week <span class="text-danger">*</span></label>
    <select name="day_of_week" class="form-select" required>
        <option value="">— Select day —</option>
        @foreach($days as $key => $label)
            <option value="{{ $key }}" {{ old('day_of_week', $rule->day_of_week ?? '') === $key ? 'selected' : '' }}>
                {{ $label }}
            </option>
        @endforeach
    </select>
</div>

<div class="row g-3 mb-3">
    <div class="col-md-6">
        <label class="form-label fw-semibold">Shift Start <span class="text-danger">*</span></label>
        <input type="time" name="time_start" class="form-control" required
               value="{{ old('time_start', $rule->time_start ?? '08:00') }}">
    </div>
    <div class="col-md-6">
        <label class="form-label fw-semibold">Shift End <span class="text-danger">*</span></label>
        <input type="time" name="time_end" class="form-control" required
               value="{{ old('time_end', $rule->time_end ?? '17:00') }}">
    </div>
</div>

<div class="mb-3 form-check">
    <input type="hidden" name="active" value="0">
    <input type="checkbox" name="active" value="1" class="form-check-input" id="active"
           {{ old('active', $rule->active ?? true) ? 'checked' : '' }}>
    <label class="form-check-label" for="active">Active</label>
</div>
