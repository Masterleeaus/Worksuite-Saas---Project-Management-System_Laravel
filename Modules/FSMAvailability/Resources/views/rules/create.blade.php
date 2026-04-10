@extends('fsmavailability::layouts.master')

@section('fsmavailability_content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>Add Working-Hour Rule – {{ $worker->name }}</h2>
    <a href="{{ route('fsmavailability.rules.index', $worker->id) }}" class="btn btn-outline-secondary">Back</a>
</div>

<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('fsmavailability.rules.store', $worker->id) }}">
            @csrf
            @include('fsmavailability::rules._form', ['days' => $days])
            <button type="submit" class="btn btn-primary">Save Rule</button>
        </form>
    </div>
</div>
@endsection
