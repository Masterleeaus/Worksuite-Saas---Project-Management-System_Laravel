@extends('fsmserviceagreement::layouts.master')

@section('fsm_content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>Edit Agreement: {{ $agreement->name }}</h2>
    <a href="{{ route('fsmserviceagreement.agreements.show', $agreement->id) }}" class="btn btn-outline-secondary">← Back</a>
</div>

<form method="POST" action="{{ route('fsmserviceagreement.agreements.update', $agreement->id) }}">
    @csrf @method('PUT')
    @include('fsmserviceagreement::agreements._form')
    <div class="mt-4">
        <button type="submit" class="btn btn-primary">Save Changes</button>
        <a href="{{ route('fsmserviceagreement.agreements.show', $agreement->id) }}" class="btn btn-outline-secondary ms-2">Cancel</a>
    </div>
</form>
@endsection
