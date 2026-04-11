@extends('synapsedispatch::layouts.master')

@section('synapse_content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>⚡ SynapseDispatch Dashboard</h2>
    <div class="d-flex gap-2 flex-wrap">
        <a href="{{ route('synapsedispatch.planner.gantt') }}" class="btn btn-primary">📅 Gantt Planner</a>
        <a href="{{ route('synapsedispatch.jobs.create') }}" class="btn btn-success">+ New Job</a>
        <a href="{{ route('synapsedispatch.locations.index') }}" class="btn btn-outline-secondary">📍 Locations</a>
        <a href="{{ route('synapsedispatch.my_jobs.index') }}" class="btn btn-outline-info">👷 My Jobs</a>
    </div>
</div>

{{-- Stats --}}
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card text-bg-secondary">
            <div class="card-body text-center">
                <div class="fs-2 fw-bold">{{ $unplannedCount }}</div>
                <div>Unplanned Jobs</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-bg-primary">
            <div class="card-body text-center">
                <div class="fs-2 fw-bold">{{ $dispatchedCount }}</div>
                <div>Dispatched Jobs</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-bg-success">
            <div class="card-body text-center">
                <div class="fs-2 fw-bold">{{ $workerCount }}</div>
                <div>Active Workers</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-bg-info">
            <div class="card-body text-center">
                <div class="fs-2 fw-bold">{{ $teams->count() }}</div>
                <div>Teams</div>
            </div>
        </div>
    </div>
</div>

{{-- Unplanned jobs queue --}}
<div class="card">
    <div class="card-header fw-semibold">Unplanned Job Queue</div>
    <div class="table-responsive">
        <table class="table table-sm table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>Code</th>
                    <th>Name</th>
                    <th>Team</th>
                    <th>Requested Start</th>
                    <th>Duration (min)</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($unplannedJobs as $job)
                <tr>
                    <td><code>{{ $job->code }}</code></td>
                    <td>{{ $job->name ?? '—' }}</td>
                    <td>{{ $job->team?->name ?? '—' }}</td>
                    <td>{{ $job->requested_start_datetime?->format('Y-m-d H:i') ?? '—' }}</td>
                    <td>{{ $job->requested_duration_minutes }}</td>
                    <td class="d-flex gap-1">
                        <a href="{{ route('synapsedispatch.planner.suggest', $job) }}"
                           class="btn btn-xs btn-outline-info" target="_blank">Suggest</a>
                        <form method="POST" action="{{ route('synapsedispatch.jobs.trigger_dispatch', $job) }}">
                            @csrf
                            <button class="btn btn-xs btn-outline-primary">Auto-Dispatch</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="text-center text-muted py-3">All jobs are planned ✓</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
