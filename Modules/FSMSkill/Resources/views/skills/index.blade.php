@extends('fsmskill::layouts.master')

@section('fsmskill_content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>Skills</h2>
    <div class="d-flex gap-2">
        <a href="{{ route('fsmskill.skill-types.index') }}" class="btn btn-outline-secondary">Skill Types</a>
        <a href="{{ route('fsmskill.skills.create') }}" class="btn btn-success">+ New Skill</a>
    </div>
</div>

<div class="table-responsive">
    <table class="table table-hover align-middle">
        <thead class="table-light">
        <tr>
            <th>Name</th>
            <th>Type</th>
            <th>Levels</th>
            <th>Active</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        @forelse($skills as $skill)
            <tr>
                <td>{{ $skill->name }}</td>
                <td>{{ $skill->skillType?->name ?? '—' }}</td>
                <td>
                    <a href="{{ route('fsmskill.skill-levels.index', $skill->id) }}">{{ $skill->levels_count }} level(s)</a>
                </td>
                <td>
                    @if($skill->active)
                        <span class="badge bg-success">Yes</span>
                    @else
                        <span class="badge bg-secondary">No</span>
                    @endif
                </td>
                <td>
                    <a href="{{ route('fsmskill.skill-levels.index', $skill->id) }}" class="btn btn-sm btn-outline-info">Levels</a>
                    <a href="{{ route('fsmskill.skills.edit', $skill->id) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                    <form method="POST" action="{{ route('fsmskill.skills.destroy', $skill->id) }}" class="d-inline"
                          onsubmit="return confirm('Delete this skill?')">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr><td colspan="5" class="text-center text-muted py-4">No skills defined yet.</td></tr>
        @endforelse
        </tbody>
    </table>
</div>

{{ $skills->links() }}
@endsection
