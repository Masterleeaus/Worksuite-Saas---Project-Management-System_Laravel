@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="mb-0">Add Property</h2>
                @if(Route::has('customerconnect.portal.properties.index'))
                    <a href="{{ route('customerconnect.portal.properties.index') }}" class="btn btn-outline-secondary">
                        <i class="fa fa-arrow-left me-1"></i> Back
                    </a>
                @endif
            </div>

            <div class="card">
                <div class="card-body">
                    <form action="{{ route('customerconnect.portal.properties.store') }}" method="POST">
                        @csrf
                        @include('customerconnect::portal.properties._form')
                        <div class="d-flex gap-2 mt-4">
                            <button type="submit" class="btn btn-primary">Save Property</button>
                            @if(Route::has('customerconnect.portal.properties.index'))
                                <a href="{{ route('customerconnect.portal.properties.index') }}" class="btn btn-outline-secondary">Cancel</a>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
