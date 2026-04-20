@extends('fsmterritory::layouts.master')

@section('fsm_content')
<div class="mb-3">
    <h2>Edit District: {{ $district->name }}</h2>
</div>

<form method="POST" action="{{ route('fsmterritory.districts.update', $district->id) }}">
    @csrf
    @include('fsmterritory::districts._form')
</form>
@endsection
