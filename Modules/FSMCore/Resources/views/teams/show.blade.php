@extends('fsmcore::layouts.master')

@section('fsm_content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>{{ $team->name }}</h2>
    <div class="d-flex gap-2">
        <a href="{{ route('fsmcore.teams.edit', $team->id) }}" class="btn btn-primary">Edit</a>
        <a href="{{ route('fsmcore.teams.index') }}" class="btn btn-outline-secondary">Back</a>
    </div>
</div>

<div class="row g-3">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header fw-semibold">Team Info</div>
            <div class="card-body">
                <p>{{ $team->description ?? 'No description.' }}</p>
                <span class="badge {{ $team->active ? 'bg-success' : 'bg-secondary' }}">{{ $team->active ? 'Active' : 'Inactive' }}</span>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header fw-semibold">Members ({{ $team->members->count() }})</div>
            <div class="card-body">
                @forelse($team->members as $member)
                    <div class="d-flex align-items-center mb-1">
                        <span class="me-2">👤</span> {{ $member->name }}
                    </div>
                @empty
                    <span class="text-muted">No members assigned.</span>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
