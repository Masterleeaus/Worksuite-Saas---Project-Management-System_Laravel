{{-- Shared frequency-set form partial --}}
<div class="row g-3">
    <div class="col-md-6">
        <label class="form-label">Name <span class="text-danger">*</span></label>
        <input type="text" name="name" class="form-control" required
               value="{{ old('name', $set?->name) }}">
    </div>
    <div class="col-md-2">
        <label class="form-label">Schedule Days <span class="text-danger">*</span></label>
        <input type="number" name="schedule_days" class="form-control" min="1" required
               value="{{ old('schedule_days', $set?->schedule_days ?? 30) }}">
        <small class="text-muted">Days ahead to generate orders.</small>
    </div>
    <div class="col-md-2">
        <label class="form-label">Buffer Early</label>
        <input type="number" name="buffer_early" class="form-control" min="0"
               value="{{ old('buffer_early', $set?->buffer_early ?? 0) }}">
        <small class="text-muted">Days before OK.</small>
    </div>
    <div class="col-md-2">
        <label class="form-label">Buffer Late</label>
        <input type="number" name="buffer_late" class="form-control" min="0"
               value="{{ old('buffer_late', $set?->buffer_late ?? 0) }}">
        <small class="text-muted">Days after OK.</small>
    </div>
    <div class="col-md-3">
        <div class="form-check mt-4">
            <input class="form-check-input" type="checkbox" name="active" id="set_active" value="1"
                   {{ old('active', $set?->active ?? true) ? 'checked' : '' }}>
            <label class="form-check-label" for="set_active">Active</label>
        </div>
    </div>
    <div class="col-12">
        <label class="form-label">Frequency Rules</label>
        <select name="frequency_ids[]" class="form-select" multiple style="height:150px;">
            @foreach($frequencies as $freq)
                @php $selected = in_array($freq->id, old('frequency_ids', $set?->frequencies?->pluck('id')->toArray() ?? [])); @endphp
                <option value="{{ $freq->id }}" {{ $selected ? 'selected' : '' }}>
                    {{ $freq->name }} ({{ $freq->interval_type }}{{ $freq->is_exclusive ? ' – exclusive' : '' }})
                </option>
            @endforeach
        </select>
        <small class="text-muted">Hold Ctrl/Cmd to select multiple.</small>
    </div>
</div>
