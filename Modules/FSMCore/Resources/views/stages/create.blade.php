@extends('fsmcore::layouts.master')

@section('fsm_content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>New Stage</h2>
    <a href="{{ route('fsmcore.stages.index') }}" class="btn btn-outline-secondary">Back</a>
</div>
<div class="card"><div class="card-body">
    <form method="POST" action="{{ route('fsmcore.stages.store') }}">
        @csrf
        @include('fsmcore::stages._form', ['stage' => null])
        <div class="mt-3"><button type="submit" class="btn btn-success">Create</button></div>
    </form>
</div></div>
@endsection
