@extends('fsmvehicle::layouts.master')

@section('fsmvehicle_content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>Add Vehicle</h2>
    <a href="{{ route('fsmvehicle.vehicles.index') }}" class="btn btn-outline-secondary">← Back to Fleet</a>
</div>

<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('fsmvehicle.vehicles.store') }}">
            @csrf
            @include('fsmvehicle::vehicles._form')
            <div class="mt-4">
                <button type="submit" class="btn btn-success">Save Vehicle</button>
                <a href="{{ route('fsmvehicle.vehicles.index') }}" class="btn btn-link text-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
