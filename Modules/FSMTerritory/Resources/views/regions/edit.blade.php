@extends('fsmterritory::layouts.master')

@section('fsm_content')
<div class="mb-3">
    <h2>Edit Region: {{ $region->name }}</h2>
</div>

<form method="POST" action="{{ route('fsmterritory.regions.update', $region->id) }}">
    @csrf
    @include('fsmterritory::regions._form')
</form>
@endsection
