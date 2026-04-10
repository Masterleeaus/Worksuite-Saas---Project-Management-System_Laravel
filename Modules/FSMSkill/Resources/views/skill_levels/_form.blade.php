<div class="mb-3">
    <label class="form-label">Name <span class="text-danger">*</span></label>
    <input type="text" name="name" class="form-control" required maxlength="128"
           value="{{ old('name', $level->name ?? '') }}">
    <small class="text-muted">E.g. Competent, Certified, Expert</small>
</div>
<div class="mb-3">
    <label class="form-label">Progress (%) <span class="text-danger">*</span></label>
    <input type="number" name="progress" class="form-control" required min="0" max="100"
           value="{{ old('progress', $level->progress ?? 0) }}">
    <small class="text-muted">0–100 proficiency percentage. Used for level comparison during validation.</small>
</div>
<div class="mb-3 form-check">
    <input type="checkbox" name="default_level" id="default_level" class="form-check-input" value="1"
           {{ old('default_level', ($level->default_level ?? false) ? '1' : '0') == '1' ? 'checked' : '' }}>
    <label class="form-check-label" for="default_level">Default level for this skill</label>
</div>
