@extends('fsmcore::layouts.master')

@section('fsm_content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>FSM Locations</h2>
    <a href="{{ route('fsmcore.locations.create') }}" class="btn btn-success">+ New Location</a>
</div>

<form method="GET" class="row g-2 mb-3">
    <div class="col-md-4">
        <input type="text" name="q" class="form-control" placeholder="Search…" value="{{ $filter['q'] ?? '' }}">
    </div>
    <div class="col-md-2">
        <button type="submit" class="btn btn-primary">Filter</button>
    </div>
</form>

<div class="table-responsive">
    <table class="table table-hover align-middle">
        <thead class="table-light">
        <tr>
            <th>Name</th><th>Address</th><th>Territory</th><th>GPS</th><th>Active</th><th>Actions</th>
        </tr>
        </thead>
        <tbody>
        @forelse($locations as $loc)
            <tr>
                <td><a href="{{ route('fsmcore.locations.show', $loc->id) }}">{{ $loc->name }}</a></td>
                <td>{{ $loc->street ? $loc->street.', ' : '' }}{{ $loc->city }}</td>
                <td>{{ $loc->territory?->name ?? '—' }}</td>
                <td>{{ $loc->latitude ? $loc->latitude.', '.$loc->longitude : '—' }}</td>
                <td>{{ $loc->active ? '✅' : '❌' }}</td>
                <td>
                    <a href="{{ route('fsmcore.locations.edit', $loc->id) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                    <form method="POST" action="{{ route('fsmcore.locations.destroy', $loc->id) }}" class="d-inline" onsubmit="return confirm('Delete?')">
                        @csrf <button class="btn btn-sm btn-outline-danger">Delete</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr><td colspan="6" class="text-center text-muted py-4">No locations found.</td></tr>
        @endforelse
        </tbody>
    </table>
</div>
{{ $locations->links() }}
@endsection
