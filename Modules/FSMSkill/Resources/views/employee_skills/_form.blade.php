@if(!$empSkill)
{{-- On create: allow selecting the skill --}}
<div class="mb-3">
    <label class="form-label">Skill <span class="text-danger">*</span></label>
    <select name="skill_id" id="skill_select" class="form-select" required>
        <option value="">— Select skill —</option>
        @foreach($skills as $sk)
            <option value="{{ $sk->id }}" {{ old('skill_id') == $sk->id ? 'selected' : '' }}>
                {{ $sk->skillType?->name ? '['.$sk->skillType->name.'] ' : '' }}{{ $sk->name }}
            </option>
        @endforeach
    </select>
</div>
<div class="mb-3">
    <label class="form-label">Level</label>
    <select name="skill_level_id" id="level_select" class="form-select">
        <option value="">— None —</option>
    </select>
</div>
<script>
(function () {
    var skillSel = document.getElementById('skill_select');
    var levelSel = document.getElementById('level_select');
    if (!skillSel || !levelSel) return;

    function loadLevels(skillId) {
        levelSel.innerHTML = '<option value="">— None —</option>';
        if (!skillId) return;
        fetch('{{ route("fsmskill.ajax.levels", "__SKILL__") }}'.replace('__SKILL__', skillId))
            .then(function(r){ return r.json(); })
            .then(function(levels){
                levels.forEach(function(l){
                    var opt = document.createElement('option');
                    opt.value = l.id;
                    opt.textContent = l.name + ' (' + l.progress + '%)';
                    levelSel.appendChild(opt);
                });
            });
    }

    skillSel.addEventListener('change', function(){ loadLevels(this.value); });
    if (skillSel.value) loadLevels(skillSel.value);
})();
</script>
@else
{{-- On edit: skill is fixed; only level/expiry/notes editable --}}
<div class="mb-3">
    <label class="form-label">Skill</label>
    <input type="text" class="form-control" readonly
           value="{{ $empSkill->skill?->name ?? '—' }}">
</div>
<div class="mb-3">
    <label class="form-label">Level</label>
    <select name="skill_level_id" class="form-select">
        <option value="">— None —</option>
        @foreach($levels as $lv)
            <option value="{{ $lv->id }}"
                {{ old('skill_level_id', $empSkill->skill_level_id) == $lv->id ? 'selected' : '' }}>
                {{ $lv->name }} ({{ $lv->progress }}%)
            </option>
        @endforeach
    </select>
</div>
@endif

<div class="mb-3">
    <label class="form-label">Expiry Date</label>
    <input type="date" name="expiry_date" class="form-control"
           value="{{ old('expiry_date', $empSkill?->expiry_date?->format('Y-m-d') ?? '') }}">
    <small class="text-muted">Leave blank if this skill does not expire.</small>
</div>

<div class="mb-3">
    <label class="form-label">Certificate Document</label>
    <input type="file" name="certificate" class="form-control" accept=".pdf,.jpg,.jpeg,.png">
    @if($empSkill?->certificate_path)
        <small class="text-muted">Current: <a href="{{ Storage::url($empSkill->certificate_path) }}" target="_blank">View certificate</a></small>
    @endif
</div>

<div class="mb-3">
    <label class="form-label">Notes</label>
    <textarea name="notes" class="form-control" rows="3">{{ old('notes', $empSkill?->notes ?? '') }}</textarea>
</div>
