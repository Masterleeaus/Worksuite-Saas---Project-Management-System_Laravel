<div class="row g-3">
    <div class="col-md-6">
        <label class="form-label fw-semibold">Name <span class="text-danger">*</span></label>
        <input type="text" name="name" class="form-control" required value="{{ old('name', $territory?->name) }}">
    </div>
    <div class="col-md-3">
        <label class="form-label">Type</label>
        <select name="type" class="form-select">
            @foreach(['region','district','branch','territory'] as $t)
                <option value="{{ $t }}" {{ old('type', $territory?->type) === $t ? 'selected' : '' }}>{{ ucfirst($t) }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-3">
        <label class="form-label">Parent</label>
        <select name="parent_id" class="form-select">
            <option value="">— None —</option>
            @foreach($parents as $p)
                <option value="{{ $p->id }}" {{ old('parent_id', $territory?->parent_id) == $p->id ? 'selected' : '' }}>{{ $p->name }} ({{ $p->type }})</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-6">
        <label class="form-label">ZIP Codes</label>
        <input type="text" name="zip_codes" class="form-control" placeholder="e.g. 3000,3001,3002"
               value="{{ old('zip_codes', $territory?->zip_codes) }}">
        <small class="text-muted">Comma-separated ZIP/postcode boundaries.</small>
    </div>
    <div class="col-md-3 d-flex align-items-end">
        <div class="form-check mb-2">
            <input type="hidden" name="active" value="0">
            <input type="checkbox" name="active" value="1" class="form-check-input" id="t_active"
                   {{ old('active', $territory?->active ?? true) ? 'checked' : '' }}>
            <label for="t_active" class="form-check-label">Active</label>
        </div>
    </div>
    <div class="col-12">
        <label class="form-label">Description</label>
        <textarea name="description" class="form-control" rows="2">{{ old('description', $territory?->description) }}</textarea>
    </div>
</div>
