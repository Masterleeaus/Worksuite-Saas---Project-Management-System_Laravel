@extends('fsmcore::layouts.master')

@section('fsm_content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>New Location</h2>
    <a href="{{ route('fsmcore.locations.index') }}" class="btn btn-outline-secondary">Back</a>
</div>
<div class="card"><div class="card-body">
    <form method="POST" action="{{ route('fsmcore.locations.store') }}">
        @csrf
        @include('fsmcore::locations._form', ['location' => null])
        <div class="mt-3">
            <button type="submit" class="btn btn-success">Create</button>
            <a href="{{ route('fsmcore.locations.index') }}" class="btn btn-link">Cancel</a>
        </div>
    </form>
</div></div>
@endsection
