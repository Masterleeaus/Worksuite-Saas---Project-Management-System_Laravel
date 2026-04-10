@extends('fsmskill::layouts.master')

@section('fsmskill_content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>Skill Requirements – Template: {{ $template->name }}</h2>
    <a href="{{ route('fsmcore.templates.edit', $template->id) }}" class="btn btn-outline-secondary">← Template</a>
</div>

<div class="row g-4">
    {{-- Current requirements --}}
    <div class="col-md-7">
        <div class="card">
            <div class="card-header fw-semibold">Required Skills</div>
            <div class="card-body p-0">
                <table class="table table-sm mb-0">
                    <thead class="table-light">
                    <tr>
                        <th>Skill</th>
                        <th>Type</th>
                        <th>Min. Level</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($requirements as $req)
                        <tr>
                            <td>{{ $req->skill?->name ?? '—' }}</td>
                            <td>{{ $req->skill?->skillType?->name ?? '—' }}</td>
                            <td>{{ $req->skillLevel?->name ?? 'Any' }}</td>
                            <td>
                                <form method="POST" action="{{ route('fsmskill.template-skills.destroy', [$template->id, $req->id]) }}"
                                      onsubmit="return confirm('Remove requirement?')">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-outline-danger">Remove</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="text-center text-muted py-3">No skill requirements set.</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Add requirement --}}
    <div class="col-md-5">
        <div class="card">
            <div class="card-header fw-semibold">Add Requirement</div>
            <div class="card-body">
                <form method="POST" action="{{ route('fsmskill.template-skills.store', $template->id) }}">
                    @csrf
                    <div class="mb-2">
                        <label class="form-label">Skill</label>
                        <select name="skill_id" id="req_skill_id" class="form-select" required>
                            <option value="">— Select skill —</option>
                            @foreach($skills as $sk)
                                <option value="{{ $sk->id }}">
                                    {{ $sk->skillType?->name ? '['.$sk->skillType->name.'] ' : '' }}{{ $sk->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-2">
                        <label class="form-label">Minimum Level</label>
                        <select name="skill_level_id" id="req_level_id" class="form-select">
                            <option value="">— Any —</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Add</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
(function () {
    var skillSel = document.getElementById('req_skill_id');
    var levelSel = document.getElementById('req_level_id');
    if (!skillSel || !levelSel) return;

    function loadLevels(skillId) {
        levelSel.innerHTML = '<option value="">— Any —</option>';
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
})();
</script>
@endsection
