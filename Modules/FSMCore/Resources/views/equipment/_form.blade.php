<div class="row g-3">
    <div class="col-md-6">
        <label class="form-label fw-semibold">Name <span class="text-danger">*</span></label>
        <input type="text" name="name" class="form-control" required value="{{ old('name', $item?->name) }}">
    </div>
    <div class="col-md-3">
        <label class="form-label">Category</label>
        <input type="text" name="category" class="form-control" value="{{ old('category', $item?->category) }}">
    </div>
    <div class="col-md-3">
        <label class="form-label">Location</label>
        <select name="location_id" class="form-select">
            <option value="">— None —</option>
            @foreach($locations as $loc)
                <option value="{{ $loc->id }}" {{ old('location_id', $item?->location_id) == $loc->id ? 'selected' : '' }}>{{ $loc->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-3">
        <label class="form-label">Warranty Expiry</label>
        <input type="date" name="warranty_expiry" class="form-control" value="{{ old('warranty_expiry', $item?->warranty_expiry?->format('Y-m-d')) }}">
    </div>
    <div class="col-md-3 d-flex align-items-end">
        <div class="form-check mb-2">
            <input type="hidden" name="active" value="0">
            <input type="checkbox" name="active" value="1" class="form-check-input" id="eq_active"
                   {{ old('active', $item?->active ?? true) ? 'checked' : '' }}>
            <label for="eq_active" class="form-check-label">Active</label>
        </div>
    </div>
    <div class="col-12">
        <label class="form-label">Notes</label>
        <textarea name="notes" class="form-control" rows="3">{{ old('notes', $item?->notes) }}</textarea>
    </div>
</div>
