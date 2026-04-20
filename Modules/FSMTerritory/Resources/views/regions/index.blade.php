@extends('fsmterritory::layouts.master')

@section('fsm_content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>FSM Regions</h2>
    <a href="{{ route('fsmterritory.regions.create') }}" class="btn btn-success">+ New Region</a>
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
            <th>Name</th>
            <th>Manager</th>
            <th>Districts</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        @forelse($regions as $region)
            <tr>
                <td>{{ $region->name }}</td>
                <td>{{ $region->manager?->name ?? '—' }}</td>
                <td>{{ $region->districts->count() }}</td>
                <td>
                    <a href="{{ route('fsmterritory.regions.edit', $region->id) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                    <form method="POST" action="{{ route('fsmterritory.regions.destroy', $region->id) }}" class="d-inline" onsubmit="return confirm('Delete this region?')">
                        @csrf
                        <button class="btn btn-sm btn-outline-danger">Delete</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr><td colspan="4" class="text-center text-muted py-4">No regions found.</td></tr>
        @endforelse
        </tbody>
    </table>
</div>
{{ $regions->links() }}
@endsection
