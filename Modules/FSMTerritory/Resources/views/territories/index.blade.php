@extends('fsmterritory::layouts.master')

@section('fsm_content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>FSM Territories</h2>
    <a href="{{ route('fsmterritory.territories.create') }}" class="btn btn-success">+ New Territory</a>
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
            <th>Branch</th>
            <th>Type</th>
            <th>Zip Codes</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        @forelse($territories as $territory)
            <tr>
                <td>{{ $territory->name }}</td>
                <td>{{ $territory->branch?->name ?? '—' }}</td>
                <td>{{ \Modules\FSMTerritory\Models\FSMTerritory::TYPES[$territory->type] ?? $territory->type ?? '—' }}</td>
                <td>{{ $territory->zip_codes ? \Illuminate\Support\Str::limit($territory->zip_codes, 60) : '—' }}</td>
                <td>
                    <a href="{{ route('fsmterritory.territories.edit', $territory->id) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                    <form method="POST" action="{{ route('fsmterritory.territories.destroy', $territory->id) }}" class="d-inline" onsubmit="return confirm('Delete this territory?')">
                        @csrf
                        <button class="btn btn-sm btn-outline-danger">Delete</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr><td colspan="5" class="text-center text-muted py-4">No territories found.</td></tr>
        @endforelse
        </tbody>
    </table>
</div>
{{ $territories->links() }}
@endsection
