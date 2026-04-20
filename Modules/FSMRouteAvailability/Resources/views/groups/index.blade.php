@extends('fsmrouteavailability::layouts.master')

@section('fsm_content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>FSM Blackout Groups</h2>
    <a href="{{ route('fsmrouteavailability.groups.create') }}" class="btn btn-success">+ New Blackout Group</a>
</div>

<form method="GET" class="row g-2 mb-3">
    <div class="col-md-4">
        <input type="text" name="q" class="form-control" placeholder="Search…" value="{{ request('q') }}">
    </div>
    <div class="col-md-2">
        <button type="submit" class="btn btn-primary">Filter</button>
    </div>
</form>

<div class="table-responsive">
    <table class="table table-hover align-middle">
        <thead class="table-light">
        <tr>
            <th>Name</th>
            <th>Description</th>
            <th>Days Count</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        @forelse($groups as $group)
            <tr>
                <td>{{ $group->name }}</td>
                <td>{{ $group->description ?: '—' }}</td>
                <td>{{ $group->blackout_days_count }}</td>
                <td>
                    <a href="{{ route('fsmrouteavailability.groups.edit', $group->id) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                    <form method="POST" action="{{ route('fsmrouteavailability.groups.destroy', $group->id) }}" class="d-inline" onsubmit="return confirm('Delete this blackout group?')">
                        @csrf
                        <button class="btn btn-sm btn-outline-danger">Delete</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr><td colspan="4" class="text-center text-muted py-4">No blackout groups found.</td></tr>
        @endforelse
        </tbody>
    </table>
</div>
{{ $groups->links() }}
@endsection
