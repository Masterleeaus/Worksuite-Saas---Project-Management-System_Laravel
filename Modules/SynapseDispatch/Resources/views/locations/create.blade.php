@extends('synapsedispatch::layouts.master')

@section('synapse_content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>New Location</h2>
    <a href="{{ route('synapsedispatch.locations.index') }}" class="btn btn-outline-secondary">← Back</a>
</div>
<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('synapsedispatch.locations.store') }}">
            @csrf
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Location Code <span class="text-danger">*</span></label>
                    <input type="text" name="location_code"
                           class="form-control @error('location_code') is-invalid @enderror"
                           value="{{ old('location_code') }}" required>
                    @error('location_code')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-8">
                    <label class="form-label">Address</label>
                    <input type="text" name="address" class="form-control @error('address') is-invalid @enderror"
                           value="{{ old('address') }}" placeholder="123 Main St, City, State">
                    @error('address')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label">Latitude</label>
                    <input type="number" step="0.00000001" name="geo_latitude"
                           class="form-control @error('geo_latitude') is-invalid @enderror"
                           value="{{ old('geo_latitude') }}" placeholder="-33.8688">
                    @error('geo_latitude')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label">Longitude</label>
                    <input type="number" step="0.00000001" name="geo_longitude"
                           class="form-control @error('geo_longitude') is-invalid @enderror"
                           value="{{ old('geo_longitude') }}" placeholder="151.2093">
                    @error('geo_longitude')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
            <div class="mt-4">
                <button type="submit" class="btn btn-success">Create Location</button>
                <a href="{{ route('synapsedispatch.locations.index') }}" class="btn btn-secondary ms-2">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
