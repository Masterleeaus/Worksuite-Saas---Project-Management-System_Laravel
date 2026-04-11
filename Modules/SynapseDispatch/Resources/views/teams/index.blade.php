@extends('synapsedispatch::layouts.master')

@section('synapse_content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>Dispatch Teams</h2>
    <a href="{{ route('synapsedispatch.teams.create') }}" class="btn btn-success">+ New Team</a>
</div>
<div class="card">
    <div class="table-responsive">
        <table class="table table-sm table-hover mb-0">
            <thead class="table-light">
                <tr><th>Code</th><th>Name</th><th>Description</th><th>Workers</th><th>Jobs</th><th></th></tr>
            </thead>
            <tbody>
                @forelse($teams as $team)
                <tr>
                    <td><code>{{ $team->code }}</code></td>
                    <td>{{ $team->name }}</td>
                    <td>{{ Str::limit($team->description, 60) ?? '—' }}</td>
                    <td>{{ $team->workers_count }}</td>
                    <td>{{ $team->jobs_count }}</td>
                    <td class="text-end">
                        <a href="{{ route('synapsedispatch.teams.show', $team) }}" class="btn btn-xs btn-outline-info">View</a>
                        <a href="{{ route('synapsedispatch.teams.edit', $team) }}" class="btn btn-xs btn-outline-secondary">Edit</a>
                        <form method="POST" action="{{ route('synapsedispatch.teams.destroy', $team) }}" class="d-inline"
                              onsubmit="return confirm('Delete team {{ $team->name }}?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-xs btn-outline-danger">Delete</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="text-center text-muted py-3">No teams found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
