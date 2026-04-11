@extends('synapsedispatch::layouts.master')

@section('synapse_content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>Edit Job: <code>{{ $job->code }}</code></h2>
    <a href="{{ route('synapsedispatch.jobs.index') }}" class="btn btn-outline-secondary">← Back</a>
</div>

<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('synapsedispatch.jobs.update', $job) }}">
            @csrf @method('PUT')

            <div class="row g-3">
                <div class="col-md-9">
                    <label class="form-label">Name</label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                           value="{{ old('name', $job->name) }}">
                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-12">
                    <label class="form-label">Description</label>
                    <textarea name="description" class="form-control" rows="3">{{ old('description', $job->description) }}</textarea>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Team</label>
                    <select name="team_id" class="form-select">
                        <option value="">— none —</option>
                        @foreach($teams as $team)
                            <option value="{{ $team->id }}" @selected(old('team_id', $job->team_id)==$team->id)>{{ $team->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Location</label>
                    <select name="location_id" class="form-select">
                        <option value="">— none —</option>
                        @foreach($locations as $loc)
                            <option value="{{ $loc->id }}" @selected(old('location_id', $job->location_id)==$loc->id)>
                                {{ $loc->location_code }} {{ $loc->address ? '– '.$loc->address : '' }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Requested Start</label>
                    <input type="datetime-local" name="requested_start_datetime"
                           class="form-control @error('requested_start_datetime') is-invalid @enderror"
                           value="{{ old('requested_start_datetime', $job->requested_start_datetime?->format('Y-m-d\TH:i')) }}">
                </div>

                <div class="col-md-4">
                    <label class="form-label">Duration (minutes)</label>
                    <input type="number" name="requested_duration_minutes" min="1" step="0.5"
                           class="form-control @error('requested_duration_minutes') is-invalid @enderror"
                           value="{{ old('requested_duration_minutes', $job->requested_duration_minutes) }}">
                    @error('requested_duration_minutes')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-4 d-flex align-items-end">
                    <div class="form-check mb-2">
                        <input type="hidden" name="auto_planning" value="0">
                        <input class="form-check-input" type="checkbox" name="auto_planning" value="1" id="auto_planning"
                               @checked(old('auto_planning', $job->auto_planning))>
                        <label class="form-check-label" for="auto_planning">Auto-Plan</label>
                    </div>
                </div>
            </div>

            <div class="mt-4">
                <button type="submit" class="btn btn-primary">Save Changes</button>
                <a href="{{ route('synapsedispatch.jobs.show', $job) }}" class="btn btn-secondary ms-2">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
