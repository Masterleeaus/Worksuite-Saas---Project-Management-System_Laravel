@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1">My Properties</h2>
            <p class="text-muted mb-0">Manage your service addresses and property details.</p>
        </div>
        @if(Route::has('customerconnect.portal.properties.create'))
            <a href="{{ route('customerconnect.portal.properties.create') }}" class="btn btn-primary">
                <i class="fa fa-plus me-1"></i> Add Property
            </a>
        @endif
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($properties->isEmpty())
        <div class="card">
            <div class="card-body text-center text-muted py-5">
                <i class="fa fa-home fa-3x mb-3 d-block"></i>
                No properties registered yet.
                @if(Route::has('customerconnect.portal.properties.create'))
                    <br><a href="{{ route('customerconnect.portal.properties.create') }}" class="btn btn-sm btn-primary mt-3">Add Your First Property</a>
                @endif
            </div>
        </div>
    @else
        <div class="row g-3">
            @foreach($properties as $property)
            <div class="col-md-6 col-lg-4">
                <div class="card h-100">
                    <div class="card-body">
                        <h5 class="card-title mb-1">{{ $property->name }}</h5>
                        <p class="text-muted small mb-2">{{ $property->address }}</p>
                        <div class="d-flex gap-2 flex-wrap">
                            @if($property->property_type)
                                <span class="badge bg-light text-dark">{{ ucfirst(str_replace('_', ' ', $property->property_type)) }}</span>
                            @endif
                            @if($property->bedrooms)
                                <span class="badge bg-light text-dark"><i class="fa fa-bed me-1"></i>{{ $property->bedrooms }} bed</span>
                            @endif
                            @if($property->bathrooms)
                                <span class="badge bg-light text-dark"><i class="fa fa-bath me-1"></i>{{ $property->bathrooms }} bath</span>
                            @endif
                        </div>
                        @if($property->access_method)
                            <p class="mt-2 mb-0 small text-muted"><strong>Access:</strong> {{ ucfirst(str_replace('_', ' ', $property->access_method)) }}</p>
                        @endif
                        @if($property->special_instructions)
                            <p class="mt-1 mb-0 small text-muted"><strong>Notes:</strong> {{ Str::limit($property->special_instructions, 100) }}</p>
                        @endif
                    </div>
                    @if(Route::has('customerconnect.portal.properties.edit'))
                        <div class="card-footer">
                            <a href="{{ route('customerconnect.portal.properties.edit', $property->id) }}" class="btn btn-sm btn-outline-secondary">
                                <i class="fa fa-edit me-1"></i> Edit
                            </a>
                        </div>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
