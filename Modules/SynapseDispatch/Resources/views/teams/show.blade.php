@extends('synapsedispatch::layouts.master')

@section('synapse_content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>Team: {{ $team->name }}</h2>
    <div class="d-flex gap-2">
        <a href="{{ route('synapsedispatch.teams.edit', $team) }}" class="btn btn-outline-secondary">Edit</a>
        <a href="{{ route('synapsedispatch.teams.index') }}" class="btn btn-outline-secondary">← Back</a>
    </div>
</div>
<div class="row g-3">
    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <dl class="row mb-0">
                    <dt class="col-sm-5">Code</dt><dd class="col-sm-7"><code>{{ $team->code }}</code></dd>
                    <dt class="col-sm-5">Name</dt><dd class="col-sm-7">{{ $team->name }}</dd>
                    <dt class="col-sm-5">Description</dt><dd class="col-sm-7">{{ $team->description ?? '—' }}</dd>
                </dl>
            </div>
        </div>
    </div>
    <div class="col-md-8">
        <div class="card">
            <div class="card-header fw-semibold">Workers ({{ $team->workers->count() }})</div>
            <ul class="list-group list-group-flush">
                @forelse($team->workers as $worker)
                <li class="list-group-item py-2 d-flex justify-content-between">
                    <span>{{ $worker->name }} <code class="text-muted small">{{ $worker->code }}</code></span>
                    @if($worker->is_active)
                        <span class="badge bg-success">Active</span>
                    @else
                        <span class="badge bg-secondary">Inactive</span>
                    @endif
                </li>
                @empty
                <li class="list-group-item text-muted">No workers in this team.</li>
                @endforelse
            </ul>
        </div>
    </div>
</div>
@endsection
