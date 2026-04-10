<div class="mb-3">
    <label class="form-label">Skill Type</label>
    <select name="skill_type_id" class="form-select">
        <option value="">— None —</option>
        @foreach($types as $type)
            <option value="{{ $type->id }}"
                {{ old('skill_type_id', $skill->skill_type_id ?? '') == $type->id ? 'selected' : '' }}>
                {{ $type->name }}
            </option>
        @endforeach
    </select>
</div>
<div class="mb-3">
    <label class="form-label">Name <span class="text-danger">*</span></label>
    <input type="text" name="name" class="form-control" required maxlength="128"
           value="{{ old('name', $skill->name ?? '') }}">
</div>
<div class="mb-3">
    <label class="form-label">Description</label>
    <textarea name="description" class="form-control" rows="3">{{ old('description', $skill->description ?? '') }}</textarea>
</div>
<div class="mb-3 form-check">
    <input type="checkbox" name="active" id="active" class="form-check-input" value="1"
           {{ old('active', ($skill->active ?? true) ? '1' : '0') == '1' ? 'checked' : '' }}>
    <label class="form-check-label" for="active">Active</label>
</div>
