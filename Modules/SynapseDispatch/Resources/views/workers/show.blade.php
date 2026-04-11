@extends('synapsedispatch::layouts.master')

@section('synapse_content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>Worker: {{ $worker->name }}</h2>
    <div class="d-flex gap-2">
        <a href="{{ route('synapsedispatch.workers.edit', $worker) }}" class="btn btn-outline-secondary">Edit</a>
        <a href="{{ route('synapsedispatch.workers.index') }}" class="btn btn-outline-secondary">← Back</a>
    </div>
</div>
<div class="row g-3">
    <div class="col-md-5">
        <div class="card">
            <div class="card-body">
                <dl class="row mb-0">
                    <dt class="col-sm-5">Code</dt><dd class="col-sm-7"><code>{{ $worker->code }}</code></dd>
                    <dt class="col-sm-5">Name</dt><dd class="col-sm-7">{{ $worker->name }}</dd>
                    <dt class="col-sm-5">Active</dt><dd class="col-sm-7">{{ $worker->is_active ? 'Yes' : 'No' }}</dd>
                    <dt class="col-sm-5">Team</dt><dd class="col-sm-7">{{ $worker->team?->name ?? '—' }}</dd>
                    <dt class="col-sm-5">Location</dt><dd class="col-sm-7">{{ $worker->location?->address ?? $worker->location?->location_code ?? '—' }}</dd>
                    <dt class="col-sm-5">Skills</dt>
                    <dd class="col-sm-7">
                        @forelse((array)($worker->skills ?? []) as $skill)
                            <span class="badge bg-light text-dark border">{{ $skill }}</span>
                        @empty
                            <span class="text-muted">—</span>
                        @endforelse
                    </dd>
                </dl>
            </div>
        </div>
    </div>
    <div class="col-md-7">
        <div class="card">
            <div class="card-header fw-semibold">Scheduled Jobs</div>
            <div class="table-responsive">
                <table class="table table-sm mb-0">
                    <thead class="table-light">
                        <tr><th>Code</th><th>Start</th><th>Duration</th><th>Status</th></tr>
                    </thead>
                    <tbody>
                        @forelse($worker->scheduledJobs->sortByDesc('scheduled_start_datetime') as $job)
                        <tr>
                            <td><a href="{{ route('synapsedispatch.jobs.show', $job) }}">{{ $job->code }}</a></td>
                            <td>{{ $job->scheduled_start_datetime?->format('Y-m-d H:i') ?? '—' }}</td>
                            <td>{{ $job->scheduled_duration_minutes }} min</td>
                            <td><span class="badge {{ $job->planning_status->badgeClass() }}">{{ $job->planning_status->label() }}</span></td>
                        </tr>
                        @empty
                        <tr><td colspan="4" class="text-center text-muted">No scheduled jobs.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
