@extends('fsmserviceagreement::layouts.master')

@section('fsm_content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>New Service Agreement</h2>
    <a href="{{ route('fsmserviceagreement.agreements.index') }}" class="btn btn-outline-secondary">← Back</a>
</div>

<form method="POST" action="{{ route('fsmserviceagreement.agreements.store') }}">
    @csrf
    @include('fsmserviceagreement::agreements._form', ['agreement' => null])
    <div class="mt-4">
        <button type="submit" class="btn btn-success">Create Agreement</button>
        <a href="{{ route('fsmserviceagreement.agreements.index') }}" class="btn btn-outline-secondary ms-2">Cancel</a>
    </div>
</form>
@endsection
