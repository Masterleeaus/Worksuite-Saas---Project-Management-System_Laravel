<div class="row g-3">
    <div class="col-md-6">
        <label class="form-label fw-semibold">Name <span class="text-danger">*</span></label>
        <input type="text" name="name" class="form-control" required value="{{ old('name', $stage?->name) }}">
    </div>
    <div class="col-md-2">
        <label class="form-label">Sequence</label>
        <input type="number" name="sequence" class="form-control" min="0" value="{{ old('sequence', $stage?->sequence ?? 0) }}">
    </div>
    <div class="col-md-2">
        <label class="form-label">Color</label>
        <input type="color" name="color" class="form-control form-control-color" value="{{ old('color', $stage?->color ?? '#6c757d') }}">
    </div>
    <div class="col-md-2 d-flex align-items-end">
        <div class="form-check mb-2">
            <input type="hidden" name="is_completion_stage" value="0">
            <input type="checkbox" name="is_completion_stage" value="1" class="form-check-input" id="is_completion"
                   {{ old('is_completion_stage', $stage?->is_completion_stage) ? 'checked' : '' }}>
            <label for="is_completion" class="form-check-label">Completion Stage</label>
        </div>
    </div>
    <div class="col-12">
        <label class="form-label">Description</label>
        <textarea name="description" class="form-control" rows="2">{{ old('description', $stage?->description) }}</textarea>
    </div>
</div>
