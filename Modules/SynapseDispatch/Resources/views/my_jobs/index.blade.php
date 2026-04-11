@extends('synapsedispatch::layouts.master')

@section('synapse_content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2>My Jobs</h2>
        <p class="text-muted mb-0">Worker: <strong>{{ $worker->name }}</strong> <code class="ms-1">{{ $worker->code }}</code></p>
    </div>
</div>

@if($jobs->isEmpty())
<div class="alert alert-info">
    <strong>No jobs assigned yet.</strong> Check back soon — your dispatcher will assign jobs to you.
</div>
@else
<div class="row g-3">
    @foreach($jobs as $job)
    <div class="col-md-6 col-lg-4">
        <div class="card h-100 border-start border-4
            @if($job->planning_status->value === 'D') border-primary
            @elseif($job->planning_status->value === 'P') border-info
            @else border-secondary @endif">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <h6 class="card-title mb-0"><code>{{ $job->code }}</code></h6>
                    <span class="badge {{ $job->life_cycle_status->badgeClass() }}">{{ $job->life_cycle_status->label() }}</span>
                </div>
                @if($job->name)
                <p class="text-muted small mb-2">{{ $job->name }}</p>
                @endif

                <dl class="row small mb-0">
                    @if($job->scheduled_start_datetime)
                    <dt class="col-5">Scheduled</dt>
                    <dd class="col-7">{{ $job->scheduled_start_datetime->format('D M j, H:i') }}</dd>
                    <dt class="col-5">Duration</dt>
                    <dd class="col-7">{{ $job->scheduled_duration_minutes ?? $job->requested_duration_minutes }} min</dd>
                    @endif
                    @if($job->location)
                    <dt class="col-5">Location</dt>
                    <dd class="col-7">{{ $job->location->address ?? $job->location->location_code }}</dd>
                    @endif
                    @if($job->team)
                    <dt class="col-5">Team</dt>
                    <dd class="col-7">{{ $job->team->name }}</dd>
                    @endif
                </dl>
            </div>
            @if($job->location?->geo_latitude && $job->location?->geo_longitude)
            <div class="card-footer bg-transparent py-2">
                <a href="https://maps.google.com/?q={{ $job->location->geo_latitude }},{{ $job->location->geo_longitude }}"
                   target="_blank" class="btn btn-xs btn-outline-secondary">
                    📍 Open in Maps
                </a>
            </div>
            @endif
        </div>
    </div>
    @endforeach
</div>
@endif
@endsection
