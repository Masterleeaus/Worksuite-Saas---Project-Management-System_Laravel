@extends('fsmcore::layouts.master')

@section('fsm_content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>FSM Teams</h2>
    <a href="{{ route('fsmcore.teams.create') }}" class="btn btn-success">+ New Team</a>
</div>

<div class="table-responsive">
    <table class="table table-hover align-middle">
        <thead class="table-light">
        <tr><th>Name</th><th>Description</th><th>Members</th><th>Active</th><th>Actions</th></tr>
        </thead>
        <tbody>
        @forelse($teams as $team)
            <tr>
                <td><a href="{{ route('fsmcore.teams.show', $team->id) }}">{{ $team->name }}</a></td>
                <td>{{ $team->description ?? '—' }}</td>
                <td>{{ $team->members_count }}</td>
                <td>{{ $team->active ? '✅' : '❌' }}</td>
                <td>
                    <a href="{{ route('fsmcore.teams.edit', $team->id) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                    <form method="POST" action="{{ route('fsmcore.teams.destroy', $team->id) }}" class="d-inline" onsubmit="return confirm('Delete?')">
                        @csrf <button class="btn btn-sm btn-outline-danger">Delete</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr><td colspan="5" class="text-center text-muted py-4">No teams yet.</td></tr>
        @endforelse
        </tbody>
    </table>
</div>
{{ $teams->links() }}
@endsection
