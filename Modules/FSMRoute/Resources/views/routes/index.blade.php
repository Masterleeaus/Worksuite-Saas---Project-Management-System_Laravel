@extends('fsmroute::layouts.master')

@section('fsm_content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>FSM Routes</h2>
    <a href="{{ route('fsmroute.routes.create') }}" class="btn btn-success">+ New Route</a>
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
            <th>Worker</th>
            <th>Days</th>
            <th>Locations</th>
            <th>Max Orders</th>
            <th>Active</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        @forelse($routes as $route)
            <tr>
                <td>{{ $route->name }}</td>
                <td>{{ $route->person?->name ?? '—' }}</td>
                <td>{{ $route->days->pluck('name')->implode(', ') ?: '—' }}</td>
                <td>{{ $route->locations->count() }}</td>
                <td>{{ $route->max_order }}</td>
                <td>
                    @if($route->active)
                        <span class="badge bg-success">Active</span>
                    @else
                        <span class="badge bg-secondary">Inactive</span>
                    @endif
                </td>
                <td>
                    <a href="{{ route('fsmroute.routes.edit', $route->id) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                    <form method="POST" action="{{ route('fsmroute.routes.destroy', $route->id) }}" class="d-inline" onsubmit="return confirm('Delete this route?')">
                        @csrf
                        <button class="btn btn-sm btn-outline-danger">Delete</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr><td colspan="7" class="text-center text-muted py-4">No routes found.</td></tr>
        @endforelse
        </tbody>
    </table>
</div>
{{ $routes->links() }}
@endsection
