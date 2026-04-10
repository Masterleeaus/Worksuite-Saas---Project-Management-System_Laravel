<div class="mb-3">
    <label class="form-label fw-semibold">Worker <span class="text-danger">*</span></label>
    <select name="person_id" class="form-select" required>
        <option value="">— Select worker —</option>
        @foreach($workers as $w)
            <option value="{{ $w->id }}" {{ old('person_id', $exception->person_id ?? '') == $w->id ? 'selected' : '' }}>
                {{ $w->name }}
            </option>
        @endforeach
    </select>
</div>

<div class="row g-3 mb-3">
    <div class="col-md-6">
        <label class="form-label fw-semibold">From <span class="text-danger">*</span></label>
        <input type="datetime-local" name="date_start" class="form-control" required
               value="{{ old('date_start', isset($exception) ? $exception->date_start->format('Y-m-d\TH:i') : '') }}">
    </div>
    <div class="col-md-6">
        <label class="form-label fw-semibold">To <span class="text-danger">*</span></label>
        <input type="datetime-local" name="date_end" class="form-control" required
               value="{{ old('date_end', isset($exception) ? $exception->date_end->format('Y-m-d\TH:i') : '') }}">
    </div>
</div>

<div class="mb-3">
    <label class="form-label fw-semibold">Reason <span class="text-danger">*</span></label>
    <select name="reason" class="form-select" required>
        <option value="">— Select reason —</option>
        @foreach($reasons as $val => $label)
            <option value="{{ $val }}" {{ old('reason', $exception->reason ?? '') === $val ? 'selected' : '' }}>
                {{ $label }}
            </option>
        @endforeach
    </select>
</div>

<div class="mb-3">
    <label class="form-label fw-semibold">Notes</label>
    <textarea name="notes" class="form-control" rows="3">{{ old('notes', $exception->notes ?? '') }}</textarea>
</div>
