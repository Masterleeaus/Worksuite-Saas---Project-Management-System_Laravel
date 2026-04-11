@extends('synapsedispatch::layouts.master')

@section('synapse_content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>Edit Team: {{ $team->name }}</h2>
    <a href="{{ route('synapsedispatch.teams.index') }}" class="btn btn-outline-secondary">← Back</a>
</div>
<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('synapsedispatch.teams.update', $team) }}">
            @csrf @method('PUT')
            <div class="row g-3">
                <div class="col-md-12">
                    <label class="form-label">Name <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                           value="{{ old('name', $team->name) }}" required>
                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-12">
                    <label class="form-label">Description</label>
                    <textarea name="description" class="form-control" rows="3">{{ old('description', $team->description) }}</textarea>
                </div>
            </div>
            <div class="mt-4">
                <button type="submit" class="btn btn-primary">Save Changes</button>
                <a href="{{ route('synapsedispatch.teams.show', $team) }}" class="btn btn-secondary ms-2">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
