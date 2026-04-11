@extends('synapsedispatch::layouts.master')

@section('synapse_content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>New Dispatch Job</h2>
    <a href="{{ route('synapsedispatch.jobs.index') }}" class="btn btn-outline-secondary">← Back</a>
</div>

<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('synapsedispatch.jobs.store') }}">
            @csrf

            <div class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">Job Type</label>
                    <select name="job_type" class="form-select @error('job_type') is-invalid @enderror" required>
                        <option value="JOB" @selected(old('job_type','JOB')==='JOB')>JOB</option>
                        <option value="ABSENCE" @selected(old('job_type')==='ABSENCE')>ABSENCE</option>
                    </select>
                    @error('job_type')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-9">
                    <label class="form-label">Name</label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                           value="{{ old('name') }}" placeholder="Optional job name">
                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-12">
                    <label class="form-label">Description</label>
                    <textarea name="description" class="form-control" rows="3">{{ old('description') }}</textarea>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Team</label>
                    <select name="team_id" class="form-select">
                        <option value="">— none —</option>
                        @foreach($teams as $team)
                            <option value="{{ $team->id }}" @selected(old('team_id')==$team->id)>{{ $team->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Location</label>
                    <select name="location_id" class="form-select">
                        <option value="">— none —</option>
                        @foreach($locations as $loc)
                            <option value="{{ $loc->id }}" @selected(old('location_id')==$loc->id)>
                                {{ $loc->location_code }} {{ $loc->address ? '– '.$loc->address : '' }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Requested Primary Worker</label>
                    <select name="requested_primary_worker_id" class="form-select">
                        <option value="">— auto-plan —</option>
                        @foreach($workers as $worker)
                            <option value="{{ $worker->id }}" @selected(old('requested_primary_worker_id')==$worker->id)>{{ $worker->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Requested Start</label>
                    <input type="datetime-local" name="requested_start_datetime"
                           class="form-control @error('requested_start_datetime') is-invalid @enderror"
                           value="{{ old('requested_start_datetime') }}">
                    @error('requested_start_datetime')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label">Duration (minutes)</label>
                    <input type="number" name="requested_duration_minutes" min="1" step="0.5"
                           class="form-control @error('requested_duration_minutes') is-invalid @enderror"
                           value="{{ old('requested_duration_minutes', 60) }}">
                    @error('requested_duration_minutes')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-4 d-flex align-items-end">
                    <div class="form-check mb-2">
                        <input type="hidden" name="auto_planning" value="0">
                        <input class="form-check-input" type="checkbox" name="auto_planning" value="1" id="auto_planning"
                               @checked(old('auto_planning', true))>
                        <label class="form-check-label" for="auto_planning">Auto-Plan on Creation</label>
                    </div>
                </div>
            </div>

            <div class="mt-4">
                <button type="submit" class="btn btn-success">Create Job</button>
                <a href="{{ route('synapsedispatch.jobs.index') }}" class="btn btn-secondary ms-2">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
