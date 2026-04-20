@extends('fsmrouteavailability::layouts.master')

@section('fsm_content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>New Blackout Day</h2>
    <a href="{{ route('fsmrouteavailability.days.index') }}" class="btn btn-secondary">Back</a>
</div>

<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('fsmrouteavailability.days.store') }}">
            @csrf
            @include('fsmrouteavailability::days._form')
        </form>
    </div>
</div>
@endsection
