@extends('fsmskill::layouts.master')

@section('fsmskill_content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>Skill Types</h2>
    <a href="{{ route('fsmskill.skill-types.create') }}" class="btn btn-success">+ New Skill Type</a>
</div>

<div class="table-responsive">
    <table class="table table-hover align-middle">
        <thead class="table-light">
        <tr>
            <th>Name</th>
            <th>Description</th>
            <th>Skills</th>
            <th>Active</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        @forelse($types as $type)
            <tr>
                <td>{{ $type->name }}</td>
                <td>{{ $type->description ?? '—' }}</td>
                <td>{{ $type->skills_count }}</td>
                <td>
                    @if($type->active)
                        <span class="badge bg-success">Yes</span>
                    @else
                        <span class="badge bg-secondary">No</span>
                    @endif
                </td>
                <td>
                    <a href="{{ route('fsmskill.skill-types.edit', $type->id) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                    <form method="POST" action="{{ route('fsmskill.skill-types.destroy', $type->id) }}" class="d-inline"
                          onsubmit="return confirm('Delete this skill type?')">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr><td colspan="5" class="text-center text-muted py-4">No skill types defined yet.</td></tr>
        @endforelse
        </tbody>
    </table>
</div>

{{ $types->links() }}
@endsection
