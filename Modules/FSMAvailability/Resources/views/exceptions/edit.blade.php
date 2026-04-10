@extends('fsmavailability::layouts.master')

@section('fsmavailability_content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>Edit Exception</h2>
    <a href="{{ route('fsmavailability.exceptions.show', $exception->id) }}" class="btn btn-outline-secondary">Back</a>
</div>

<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('fsmavailability.exceptions.update', $exception->id) }}">
            @csrf
            @include('fsmavailability::exceptions._form', ['workers' => $workers, 'reasons' => $reasons, 'exception' => $exception])
            <button type="submit" class="btn btn-primary">Update</button>
        </form>
    </div>
</div>
@endsection
