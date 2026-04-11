@extends('synapsedispatch::layouts.master')

@section('synapse_content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>Edit Worker: {{ $worker->name }}</h2>
    <a href="{{ route('synapsedispatch.workers.index') }}" class="btn btn-outline-secondary">← Back</a>
</div>
<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('synapsedispatch.workers.update', $worker) }}">
            @csrf @method('PUT')
            <div class="row g-3">
                <div class="col-md-9">
                    <label class="form-label">Name <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                           value="{{ old('name', $worker->name) }}" required>
                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-3 d-flex align-items-end">
                    <div class="form-check mb-2">
                        <input type="hidden" name="is_active" value="0">
                        <input class="form-check-input" type="checkbox" name="is_active" value="1" id="is_active"
                               @checked(old('is_active', $worker->is_active))>
                        <label class="form-check-label" for="is_active">Active</label>
                    </div>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Team</label>
                    <select name="team_id" class="form-select">
                        <option value="">— none —</option>
                        @foreach($teams as $team)
                            <option value="{{ $team->id }}" @selected(old('team_id', $worker->team_id)==$team->id)>{{ $team->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Location</label>
                    <select name="location_id" class="form-select">
                        <option value="">— none —</option>
                        @foreach($locations as $loc)
                            <option value="{{ $loc->id }}" @selected(old('location_id', $worker->location_id)==$loc->id)>
                                {{ $loc->location_code }} {{ $loc->address ? '– '.$loc->address : '' }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Worksuite User ID</label>
                    <input type="number" name="worksuite_user_id" class="form-control"
                           value="{{ old('worksuite_user_id', $worker->worksuite_user_id) }}">
                </div>
                <div class="col-12">
                    <label class="form-label">Skills (comma-separated)</label>
                    <input type="text" name="skills_raw" class="form-control" id="skills_raw"
                           value="{{ old('skills_raw', implode(', ', (array)($worker->skills ?? []))) }}">
                </div>
            </div>
            <div class="mt-4">
                <button type="submit" class="btn btn-primary">Save Changes</button>
                <a href="{{ route('synapsedispatch.workers.show', $worker) }}" class="btn btn-secondary ms-2">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
