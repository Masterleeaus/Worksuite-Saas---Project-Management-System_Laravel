@extends('fsmworkflow::layouts.master')

@section('fsmworkflow_content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>Job Size Tiers</h2>
    <a href="{{ route('fsmworkflow.sizes.create') }}" class="btn btn-success">+ New Size</a>
</div>

<div class="card">
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th style="width:60px;">Seq</th>
                    <th style="width:60px;">Code</th>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Active</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($sizes as $size)
                <tr>
                    <td>{{ $size->sequence }}</td>
                    <td><span class="badge bg-secondary fs-6">{{ $size->code }}</span></td>
                    <td>{{ $size->name }}</td>
                    <td class="text-muted">{{ $size->description ?? '—' }}</td>
                    <td>
                        @if($size->active)
                            <span class="badge bg-success">Yes</span>
                        @else
                            <span class="badge bg-secondary">No</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('fsmworkflow.sizes.edit', $size->id) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                        <form method="POST" action="{{ route('fsmworkflow.sizes.destroy', $size->id) }}" class="d-inline"
                              onsubmit="return confirm('Delete this size tier?')">
                            @csrf
                            <button class="btn btn-sm btn-outline-danger">Delete</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="text-center text-muted py-4">No size tiers found. <a href="{{ route('fsmworkflow.sizes.create') }}">Create one</a>.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
