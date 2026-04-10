<div class="mb-3">
    <label class="form-label">Name <span class="text-danger">*</span></label>
    <input type="text" name="name" class="form-control" required maxlength="128"
           value="{{ old('name', $type->name ?? '') }}">
</div>
<div class="mb-3">
    <label class="form-label">Description</label>
    <textarea name="description" class="form-control" rows="3">{{ old('description', $type->description ?? '') }}</textarea>
</div>
<div class="mb-3 form-check">
    <input type="checkbox" name="active" id="active" class="form-check-input" value="1"
           {{ old('active', ($type->active ?? true) ? '1' : '0') == '1' ? 'checked' : '' }}>
    <label class="form-check-label" for="active">Active</label>
</div>
