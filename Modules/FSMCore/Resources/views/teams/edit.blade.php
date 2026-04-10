@extends('fsmcore::layouts.master')

@section('fsm_content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>Edit Team: {{ $team->name }}</h2>
    <a href="{{ route('fsmcore.teams.show', $team->id) }}" class="btn btn-outline-secondary">Back</a>
</div>
<div class="card"><div class="card-body">
    <form method="POST" action="{{ route('fsmcore.teams.update', $team->id) }}">
        @csrf
        @include('fsmcore::teams._form', ['team' => $team])
        <div class="mt-3">
            <button type="submit" class="btn btn-primary">Save</button>
        </div>
    </form>
</div></div>
@endsection
