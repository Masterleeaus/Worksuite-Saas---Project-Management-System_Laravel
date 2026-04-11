@extends('synapsedispatch::layouts.master')

@section('synapse_content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>Dispatch Workers</h2>
    <a href="{{ route('synapsedispatch.workers.create') }}" class="btn btn-success">+ New Worker</a>
</div>

<form method="GET" class="row g-2 mb-3">
    <div class="col-md-3">
        <select name="team_id" class="form-select form-select-sm">
            <option value="">All Teams</option>
            @foreach($teams as $team)
                <option value="{{ $team->id }}" @selected(($filter['team_id'] ?? '') == $team->id)>{{ $team->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-3">
        <input type="text" name="q" class="form-control form-control-sm" placeholder="Search name / code…"
               value="{{ $filter['q'] ?? '' }}">
    </div>
    <div class="col-auto">
        <button type="submit" class="btn btn-primary btn-sm">Filter</button>
        <a href="{{ route('synapsedispatch.workers.index') }}" class="btn btn-secondary btn-sm">Reset</a>
    </div>
</form>

<div class="card">
    <div class="table-responsive">
        <table class="table table-sm table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>Code</th>
                    <th>Name</th>
                    <th>Team</th>
                    <th>Skills</th>
                    <th>Active</th>
                    <th>Location</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse($workers as $worker)
                <tr>
                    <td><code>{{ $worker->code }}</code></td>
                    <td>{{ $worker->name }}</td>
                    <td>{{ $worker->team?->name ?? '—' }}</td>
                    <td>
                        @forelse((array)($worker->skills ?? []) as $skill)
                            <span class="badge bg-light text-dark border">{{ $skill }}</span>
                        @empty
                            <span class="text-muted">—</span>
                        @endforelse
                    </td>
                    <td>
                        @if($worker->is_active)
                            <span class="badge bg-success">Active</span>
                        @else
                            <span class="badge bg-secondary">Inactive</span>
                        @endif
                    </td>
                    <td>{{ $worker->location?->location_code ?? '—' }}</td>
                    <td class="text-end">
                        <a href="{{ route('synapsedispatch.workers.show', $worker) }}" class="btn btn-xs btn-outline-info">View</a>
                        <a href="{{ route('synapsedispatch.workers.edit', $worker) }}" class="btn btn-xs btn-outline-secondary">Edit</a>
                        <form method="POST" action="{{ route('synapsedispatch.workers.destroy', $worker) }}" class="d-inline"
                              onsubmit="return confirm('Delete worker {{ $worker->name }}?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-xs btn-outline-danger">Delete</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="text-center text-muted py-3">No workers found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($workers->hasPages())
    <div class="card-footer">{{ $workers->links() }}</div>
    @endif
</div>
@endsection
