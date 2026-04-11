@extends('synapsedispatch::layouts.master')

@section('synapse_content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>Job: <code>{{ $job->code }}</code></h2>
    <div class="d-flex gap-2">
        <a href="{{ route('synapsedispatch.jobs.edit', $job) }}" class="btn btn-outline-secondary">Edit</a>
        <a href="{{ route('synapsedispatch.jobs.index') }}" class="btn btn-outline-secondary">← Back</a>
    </div>
</div>

<div class="row g-3">
    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <dl class="row mb-0">
                    <dt class="col-sm-4">Code</dt><dd class="col-sm-8"><code>{{ $job->code }}</code></dd>
                    <dt class="col-sm-4">Type</dt><dd class="col-sm-8">{{ $job->job_type }}</dd>
                    <dt class="col-sm-4">Name</dt><dd class="col-sm-8">{{ $job->name ?? '—' }}</dd>
                    <dt class="col-sm-4">Description</dt><dd class="col-sm-8">{{ $job->description ?? '—' }}</dd>
                    <dt class="col-sm-4">Planning Status</dt>
                    <dd class="col-sm-8">
                        <span class="badge {{ $job->planning_status->badgeClass() }}">{{ $job->planning_status->label() }}</span>
                    </dd>
                    <dt class="col-sm-4">Life Cycle</dt>
                    <dd class="col-sm-8">
                        <span class="badge {{ $job->life_cycle_status->badgeClass() }}">{{ $job->life_cycle_status->label() }}</span>
                    </dd>
                    <dt class="col-sm-4">Team</dt><dd class="col-sm-8">{{ $job->team?->name ?? '—' }}</dd>
                    <dt class="col-sm-4">Location</dt><dd class="col-sm-8">{{ $job->location?->address ?? $job->location?->location_code ?? '—' }}</dd>
                    <dt class="col-sm-4">Requested Start</dt><dd class="col-sm-8">{{ $job->requested_start_datetime?->format('Y-m-d H:i') ?? '—' }}</dd>
                    <dt class="col-sm-4">Requested Duration</dt><dd class="col-sm-8">{{ $job->requested_duration_minutes }} min</dd>
                    <dt class="col-sm-4">Scheduled Start</dt><dd class="col-sm-8">{{ $job->scheduled_start_datetime?->format('Y-m-d H:i') ?? '—' }}</dd>
                    <dt class="col-sm-4">Scheduled Worker</dt><dd class="col-sm-8">{{ $job->scheduledPrimaryWorker?->name ?? '—' }}</dd>
                    <dt class="col-sm-4">Auto-Plan</dt><dd class="col-sm-8">{{ $job->auto_planning ? 'Yes' : 'No' }}</dd>
                </dl>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        {{-- Manual assign --}}
        @if($job->planning_status !== \Modules\SynapseDispatch\Enums\PlanningStatus::DISPATCHED)
        <div class="card mb-3">
            <div class="card-header fw-semibold">Manual Assign</div>
            <div class="card-body">
                <form method="POST" action="{{ route('synapsedispatch.jobs.manual_assign', $job) }}">
                    @csrf @method('PATCH')
                    <select name="worker_id" class="form-select mb-2" required>
                        <option value="">Select worker…</option>
                        @foreach(\Modules\SynapseDispatch\Models\DispatchWorker::where('is_active',true)->orderBy('name')->get() as $w)
                            <option value="{{ $w->id }}">{{ $w->name }}</option>
                        @endforeach
                    </select>
                    <button type="submit" class="btn btn-primary btn-sm w-100">Assign</button>
                </form>
            </div>
        </div>
        @endif

        {{-- Suggest workers --}}
        <div class="card">
            <div class="card-header fw-semibold">
                Top Candidates
                <a href="{{ route('synapsedispatch.planner.suggest', $job) }}" class="btn btn-xs btn-outline-info float-end"
                   target="_blank">JSON</a>
            </div>
            <div class="card-body p-2" id="suggestions">
                <div class="text-muted small">Loading…</div>
            </div>
        </div>
    </div>
</div>

{{-- Events timeline --}}
@if($job->events->isNotEmpty())
<div class="card mt-3">
    <div class="card-header fw-semibold">Audit Trail</div>
    <ul class="list-group list-group-flush">
        @foreach($job->events->sortByDesc('started_at') as $event)
        <li class="list-group-item py-2 small">
            <span class="text-muted me-2">{{ $event->started_at->format('Y-m-d H:i') }}</span>
            <span class="badge bg-secondary me-1">{{ $event->source }}</span>
            {{ $event->description }}
        </li>
        @endforeach
    </ul>
</div>
@endif

@push('scripts')
<script>
fetch('{{ route('synapsedispatch.planner.suggest', $job) }}')
    .then(r => r.json())
    .then(data => {
        const el = document.getElementById('suggestions');
        if (!data.length) { el.innerHTML = '<p class="text-muted small mb-0">No candidates found.</p>'; return; }
        el.innerHTML = data.map((w, i) => `
            <div class="d-flex justify-content-between align-items-center py-1 border-bottom">
                <div><strong>${i+1}. ${w.worker_name}</strong><br>
                    <small class="text-muted">${w.distance_km !== null ? w.distance_km.toFixed(1)+' km' : 'no location'}</small>
                </div>
                <span class="badge bg-primary">${w.score} pts</span>
            </div>`).join('');
    }).catch(() => {});
</script>
@endpush
@endsection
