@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <div class="row mb-3">
        <div class="col-12 d-flex justify-content-between align-items-center">
            <h4>Segments</h4>
            <a href="{{ route('titanreach.segments.create') }}" class="btn btn-success">+ New Segment</a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card">
        <div class="card-body p-0">
            <table class="table table-hover mb-0">
                <thead>
                    <tr><th>Name</th><th>Description</th><th>Created</th><th>Actions</th></tr>
                </thead>
                <tbody>
                    @forelse($segments as $segment)
                    <tr>
                        <td>{{ $segment->name }}</td>
                        <td>{{ Str::limit($segment->description ?? '', 60) }}</td>
                        <td>{{ $segment->created_at->format('Y-m-d') }}</td>
                        <td>
                            <a href="{{ route('titanreach.segments.edit', $segment->id) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                            <form method="POST" action="{{ route('titanreach.segments.destroy', $segment->id) }}" class="d-inline" onsubmit="return confirm('Delete?')">
                                @csrf
                                <button class="btn btn-sm btn-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="4" class="text-center text-muted py-3">No segments found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="mt-3">{{ $segments->withQueryString()->links() }}</div>
</div>
@endsection
