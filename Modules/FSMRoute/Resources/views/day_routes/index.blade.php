@extends('fsmroute::layouts.master')

@section('fsm_content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>Day Routes</h2>
    <div>
        <a href="{{ route('fsmroute.day_routes.board') }}" class="btn btn-outline-info me-2">📋 Board</a>
        <a href="{{ route('fsmroute.day_routes.create') }}" class="btn btn-success">+ New Day Route</a>
    </div>
</div>

<form method="GET" class="row g-2 mb-3">
    <div class="col-md-3">
        <input type="date" name="date" class="form-control" value="{{ $filter['date'] ?? '' }}">
    </div>
    <div class="col-md-3">
        <select name="person_id" class="form-select">
            <option value="">— Any Worker —</option>
            @foreach($users as $user)
                <option value="{{ $user->id }}" {{ ($filter['person_id'] ?? '') == $user->id ? 'selected' : '' }}>
                    {{ $user->name }}
                </option>
            @endforeach
        </select>
    </div>
    <div class="col-md-2">
        <button type="submit" class="btn btn-primary">Filter</button>
        <a href="{{ route('fsmroute.day_routes.index') }}" class="btn btn-outline-secondary ms-1">Clear</a>
    </div>
</form>

<div class="table-responsive">
    <table class="table table-hover align-middle">
        <thead class="table-light">
        <tr>
            <th>Name</th>
            <th>Route</th>
            <th>Date</th>
            <th>Worker</th>
            <th>State</th>
            <th>Orders</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        @forelse($dayRoutes as $dr)
            <tr>
                <td><a href="{{ route('fsmroute.day_routes.show', $dr->id) }}">{{ $dr->name }}</a></td>
                <td>{{ $dr->route?->name ?? '—' }}</td>
                <td>{{ $dr->date?->format('Y-m-d') }}</td>
                <td>{{ $dr->person?->name ?? '—' }}</td>
                <td>
                    @php $stateColors = ['draft'=>'secondary','confirmed'=>'primary','done'=>'success']; @endphp
                    <span class="badge bg-{{ $stateColors[$dr->state] ?? 'secondary' }}">{{ ucfirst($dr->state) }}</span>
                </td>
                <td>{{ $dr->orderCount() }}</td>
                <td>
                    <a href="{{ route('fsmroute.day_routes.edit', $dr->id) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                    <a href="{{ route('fsmroute.day_routes.print', $dr->id) }}" class="btn btn-sm btn-outline-secondary" target="_blank">Print</a>
                    <form method="POST" action="{{ route('fsmroute.day_routes.destroy', $dr->id) }}" class="d-inline" onsubmit="return confirm('Delete?')">
                        @csrf
                        <button class="btn btn-sm btn-outline-danger">Delete</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr><td colspan="7" class="text-center text-muted py-4">No day routes found.</td></tr>
        @endforelse
        </tbody>
    </table>
</div>
{{ $dayRoutes->links() }}
@endsection
