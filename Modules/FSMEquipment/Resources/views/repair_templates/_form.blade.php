<div class="row g-3">
    <div class="col-md-6">
        <label class="form-label fw-semibold">Template Name <span class="text-danger">*</span></label>
        <input type="text" name="name" class="form-control" required value="{{ old('name', $template?->name) }}">
    </div>
    <div class="col-md-3">
        <label class="form-label">Equipment Category</label>
        <input type="text" name="equipment_category" class="form-control" value="{{ old('equipment_category', $template?->equipment_category) }}">
    </div>
    <div class="col-md-3">
        <label class="form-label">Estimated Hours</label>
        <input type="number" name="estimated_hours" class="form-control" step="0.5" min="0" value="{{ old('estimated_hours', $template?->estimated_hours) }}">
    </div>
    <div class="col-12">
        <label class="form-label">Standard Fault Description</label>
        <textarea name="description" class="form-control" rows="3">{{ old('description', $template?->description) }}</textarea>
    </div>
    <div class="col-12">
        <label class="form-label">Standard Parts Needed</label>
        <textarea name="standard_parts" class="form-control" rows="2">{{ old('standard_parts', $template?->standard_parts) }}</textarea>
    </div>
</div>
