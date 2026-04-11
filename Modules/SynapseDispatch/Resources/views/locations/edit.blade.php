@extends('synapsedispatch::layouts.master')

@section('synapse_content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>Edit Location: <code>{{ $location->location_code }}</code></h2>
    <a href="{{ route('synapsedispatch.locations.index') }}" class="btn btn-outline-secondary">← Back</a>
</div>
<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('synapsedispatch.locations.update', $location) }}">
            @csrf @method('PUT')
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Location Code</label>
                    <input type="text" class="form-control" value="{{ $location->location_code }}" disabled>
                    <small class="text-muted">Code cannot be changed after creation.</small>
                </div>
                <div class="col-md-8">
                    <label class="form-label">Address</label>
                    <input type="text" name="address" class="form-control @error('address') is-invalid @enderror"
                           value="{{ old('address', $location->address) }}">
                    @error('address')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label">Latitude</label>
                    <input type="number" step="0.00000001" name="geo_latitude"
                           class="form-control @error('geo_latitude') is-invalid @enderror"
                           value="{{ old('geo_latitude', $location->geo_latitude) }}">
                    @error('geo_latitude')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label">Longitude</label>
                    <input type="number" step="0.00000001" name="geo_longitude"
                           class="form-control @error('geo_longitude') is-invalid @enderror"
                           value="{{ old('geo_longitude', $location->geo_longitude) }}">
                    @error('geo_longitude')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
            <div class="mt-4">
                <button type="submit" class="btn btn-primary">Save Changes</button>
                <a href="{{ route('synapsedispatch.locations.index') }}" class="btn btn-secondary ms-2">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
