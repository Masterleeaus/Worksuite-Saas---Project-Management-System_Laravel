@extends('fsmcore::layouts.master')

@section('fsm_content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>New Team</h2>
    <a href="{{ route('fsmcore.teams.index') }}" class="btn btn-outline-secondary">Back</a>
</div>
<div class="card"><div class="card-body">
    <form method="POST" action="{{ route('fsmcore.teams.store') }}">
        @csrf
        @include('fsmcore::teams._form', ['team' => null])
        <div class="mt-3">
            <button type="submit" class="btn btn-success">Create</button>
        </div>
    </form>
</div></div>
@endsection
