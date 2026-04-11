@extends('synapsedispatch::layouts.master')

@section('synapse_content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>Dispatch Jobs</h2>
    <a href="{{ route('synapsedispatch.jobs.create') }}" class="btn btn-success">+ New Job</a>
</div>

{{-- Filters --}}
<form method="GET" class="row g-2 mb-3">
    <div class="col-md-3">
        <select name="team_id" class="form-select form-select-sm">
            <option value="">All Teams</option>
            @foreach($teams as $team)
                <option value="{{ $team->id }}" @selected(($filter['team_id'] ?? '') == $team->id)>{{ $team->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-3">
        <select name="planning_status" class="form-select form-select-sm">
            <option value="">All Statuses</option>
            @foreach(\Modules\SynapseDispatch\Enums\PlanningStatus::cases() as $status)
                <option value="{{ $status->value }}" @selected(($filter['planning_status'] ?? '') === $status->value)>{{ $status->label() }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-3">
        <input type="text" name="q" class="form-control form-control-sm" placeholder="Search code / name…"
               value="{{ $filter['q'] ?? '' }}">
    </div>
    <div class="col-auto">
        <button type="submit" class="btn btn-primary btn-sm">Filter</button>
        <a href="{{ route('synapsedispatch.jobs.index') }}" class="btn btn-secondary btn-sm">Reset</a>
    </div>
</form>

<div class="card">
    <div class="table-responsive">
        <table class="table table-sm table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>Code</th>
                    <th>Type</th>
                    <th>Name</th>
                    <th>Planning</th>
                    <th>Life Cycle</th>
                    <th>Team</th>
                    <th>Scheduled Start</th>
                    <th>Worker</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse($jobs as $job)
                <tr>
                    <td><code>{{ $job->code }}</code></td>
                    <td>{{ $job->job_type }}</td>
                    <td>{{ $job->name ?? '—' }}</td>
                    <td>
                        <span class="badge {{ $job->planning_status->badgeClass() }}">
                            {{ $job->planning_status->label() }}
                        </span>
                    </td>
                    <td>
                        <span class="badge {{ $job->life_cycle_status->badgeClass() }}">
                            {{ $job->life_cycle_status->label() }}
                        </span>
                    </td>
                    <td>{{ $job->team?->name ?? '—' }}</td>
                    <td>{{ $job->scheduled_start_datetime?->format('Y-m-d H:i') ?? '—' }}</td>
                    <td>{{ $job->scheduledPrimaryWorker?->name ?? '—' }}</td>
                    <td class="text-end">
                        <a href="{{ route('synapsedispatch.jobs.show', $job) }}" class="btn btn-xs btn-outline-info">View</a>
                        <a href="{{ route('synapsedispatch.jobs.edit', $job) }}" class="btn btn-xs btn-outline-secondary">Edit</a>
                        <form method="POST" action="{{ route('synapsedispatch.jobs.destroy', $job) }}" class="d-inline"
                              onsubmit="return confirm('Delete job {{ $job->code }}?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-xs btn-outline-danger">Delete</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="9" class="text-center text-muted py-3">No jobs found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($jobs->hasPages())
    <div class="card-footer">{{ $jobs->links() }}</div>
    @endif
</div>
@endsection
