<div class="row g-3">
    <div class="col-md-8">
        <label class="form-label fw-semibold">Name <span class="text-danger">*</span></label>
        <input type="text" name="name" class="form-control" required value="{{ old('name', $template?->name) }}">
    </div>
    <div class="col-md-2">
        <label class="form-label">Est. Duration (min)</label>
        <input type="number" name="estimated_duration_minutes" class="form-control" min="0"
               value="{{ old('estimated_duration_minutes', $template?->estimated_duration_minutes) }}">
    </div>
    <div class="col-md-2 d-flex align-items-end">
        <div class="form-check mb-2">
            <input type="hidden" name="active" value="0">
            <input type="checkbox" name="active" value="1" class="form-check-input" id="tpl_active"
                   {{ old('active', $template?->active ?? true) ? 'checked' : '' }}>
            <label for="tpl_active" class="form-check-label">Active</label>
        </div>
    </div>
    <div class="col-12">
        <label class="form-label">Description</label>
        <textarea name="description" class="form-control" rows="3">{{ old('description', $template?->description) }}</textarea>
    </div>
    <div class="col-12">
        <label class="form-label">Checklist Items</label>
        <textarea name="checklist" class="form-control" rows="5"
                  placeholder="One checklist item per line…">{{ old('checklist', $template ? implode("\n", $template->checklist ?? []) : '') }}</textarea>
        <small class="text-muted">Enter one item per line. These will be stored as a checklist.</small>
    </div>
</div>
