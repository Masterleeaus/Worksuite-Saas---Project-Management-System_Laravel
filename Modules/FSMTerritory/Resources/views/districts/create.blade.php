@extends('fsmterritory::layouts.master')

@section('fsm_content')
<div class="mb-3">
    <h2>New District</h2>
</div>

<form method="POST" action="{{ route('fsmterritory.districts.store') }}">
    @csrf
    @include('fsmterritory::districts._form')
</form>
@endsection
