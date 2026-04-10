@extends('fsmavailability::layouts.master')

@section('fsmavailability_content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>Edit Working-Hour Rule – {{ $worker->name }}</h2>
    <a href="{{ route('fsmavailability.rules.index', $worker->id) }}" class="btn btn-outline-secondary">Back</a>
</div>

<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('fsmavailability.rules.update', [$worker->id, $rule->id]) }}">
            @csrf
            @include('fsmavailability::rules._form', ['days' => $days, 'rule' => $rule])
            <button type="submit" class="btn btn-primary">Update Rule</button>
        </form>
    </div>
</div>
@endsection
