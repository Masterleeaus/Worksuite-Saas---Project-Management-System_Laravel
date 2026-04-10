@extends('fsmcore::layouts.master')

@section('fsm_content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>Edit Location: {{ $location->name }}</h2>
    <a href="{{ route('fsmcore.locations.show', $location->id) }}" class="btn btn-outline-secondary">Back</a>
</div>
<div class="card"><div class="card-body">
    <form method="POST" action="{{ route('fsmcore.locations.update', $location->id) }}">
        @csrf
        @include('fsmcore::locations._form', ['location' => $location])
        <div class="mt-3">
            <button type="submit" class="btn btn-primary">Save</button>
            <a href="{{ route('fsmcore.locations.show', $location->id) }}" class="btn btn-link">Cancel</a>
        </div>
    </form>
</div></div>
@endsection
