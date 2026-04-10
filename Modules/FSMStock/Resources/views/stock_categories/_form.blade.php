<div class="mb-3">
    <label class="form-label fw-semibold">Name <span class="text-danger">*</span></label>
    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
           value="{{ old('name', $category->name ?? '') }}" required maxlength="128">
    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
</div>

<div class="mb-3">
    <label class="form-label fw-semibold">Description</label>
    <textarea name="description" class="form-control" rows="3">{{ old('description', $category->description ?? '') }}</textarea>
</div>

<div class="mb-3 form-check">
    <input type="hidden" name="active" value="0">
    <input type="checkbox" name="active" value="1" id="active" class="form-check-input"
        {{ old('active', ($category->active ?? true) ? '1' : '0') == '1' ? 'checked' : '' }}>
    <label class="form-check-label" for="active">Active</label>
</div>
