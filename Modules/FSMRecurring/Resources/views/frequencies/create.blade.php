@extends('fsmcore::layouts.master')

@section('fsm_content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>New Frequency Rule</h2>
    <a href="{{ route('fsmrecurring.frequencies.index') }}" class="btn btn-outline-secondary">Back</a>
</div>

<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('fsmrecurring.frequencies.store') }}">
            @csrf
            @include('fsmrecurring::frequencies._form', ['frequency' => null])
            <div class="mt-3">
                <button type="submit" class="btn btn-success">Create Rule</button>
                <a href="{{ route('fsmrecurring.frequencies.index') }}" class="btn btn-link">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
