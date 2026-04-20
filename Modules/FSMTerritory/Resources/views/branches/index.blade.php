@extends('fsmterritory::layouts.master')

@section('fsm_content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>FSM Branches</h2>
    <a href="{{ route('fsmterritory.branches.create') }}" class="btn btn-success">+ New Branch</a>
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
            <th>District</th>
            <th>Region</th>
            <th>Manager</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        @forelse($branches as $branch)
            <tr>
                <td>{{ $branch->name }}</td>
                <td>{{ $branch->district?->name ?? '—' }}</td>
                <td>{{ $branch->district?->region?->name ?? '—' }}</td>
                <td>{{ $branch->manager?->name ?? '—' }}</td>
                <td>
                    <a href="{{ route('fsmterritory.branches.edit', $branch->id) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                    <form method="POST" action="{{ route('fsmterritory.branches.destroy', $branch->id) }}" class="d-inline" onsubmit="return confirm('Delete this branch?')">
                        @csrf
                        <button class="btn btn-sm btn-outline-danger">Delete</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr><td colspan="5" class="text-center text-muted py-4">No branches found.</td></tr>
        @endforelse
        </tbody>
    </table>
</div>
{{ $branches->links() }}
@endsection
