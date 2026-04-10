@extends('fsmskill::layouts.master')

@section('fsmskill_content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>Levels for: {{ $skill->name }}</h2>
    <div class="d-flex gap-2">
        <a href="{{ route('fsmskill.skills.index') }}" class="btn btn-outline-secondary">← Skills</a>
        <a href="{{ route('fsmskill.skill-levels.create', $skill->id) }}" class="btn btn-success">+ New Level</a>
    </div>
</div>

<div class="table-responsive">
    <table class="table table-hover align-middle">
        <thead class="table-light">
        <tr>
            <th>Name</th>
            <th>Progress (%)</th>
            <th>Default</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        @forelse($levels as $level)
            <tr>
                <td>{{ $level->name }}</td>
                <td>
                    <div class="progress" style="height:8px;width:100px;">
                        <div class="progress-bar" style="width:{{ $level->progress }}%;"></div>
                    </div>
                    <small class="text-muted">{{ $level->progress }}%</small>
                </td>
                <td>
                    @if($level->default_level)
                        <span class="badge bg-primary">Default</span>
                    @endif
                </td>
                <td>
                    <a href="{{ route('fsmskill.skill-levels.edit', [$skill->id, $level->id]) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                    <form method="POST" action="{{ route('fsmskill.skill-levels.destroy', [$skill->id, $level->id]) }}" class="d-inline"
                          onsubmit="return confirm('Delete this level?')">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr><td colspan="4" class="text-center text-muted py-4">No levels defined for this skill yet.</td></tr>
        @endforelse
        </tbody>
    </table>
</div>
@endsection
