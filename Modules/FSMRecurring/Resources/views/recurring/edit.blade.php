@extends('fsmcore::layouts.master')

@section('fsm_content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>Edit Recurring Schedule: {{ $recurring->name }}</h2>
    <a href="{{ route('fsmrecurring.recurring.show', $recurring->id) }}" class="btn btn-outline-secondary">Back</a>
</div>

<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('fsmrecurring.recurring.update', $recurring->id) }}">
            @csrf @method('PUT')
            @include('fsmrecurring::recurring._form')
            <div class="mt-3">
                <button type="submit" class="btn btn-primary">Save Changes</button>
                <a href="{{ route('fsmrecurring.recurring.show', $recurring->id) }}" class="btn btn-link">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
