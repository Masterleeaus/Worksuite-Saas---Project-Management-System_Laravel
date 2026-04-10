@extends('fsmavailability::layouts.master')

@section('fsmavailability_content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>Request Leave / Unavailability</h2>
    <a href="{{ route('fsmavailability.exceptions.index') }}" class="btn btn-outline-secondary">Back</a>
</div>

<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('fsmavailability.exceptions.store') }}">
            @csrf
            @include('fsmavailability::exceptions._form', ['workers' => $workers, 'reasons' => $reasons])
            <button type="submit" class="btn btn-primary">Submit Request</button>
        </form>
    </div>
</div>
@endsection
