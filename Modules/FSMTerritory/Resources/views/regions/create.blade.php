@extends('fsmterritory::layouts.master')

@section('fsm_content')
<div class="mb-3">
    <h2>New Region</h2>
</div>

<form method="POST" action="{{ route('fsmterritory.regions.store') }}">
    @csrf
    @include('fsmterritory::regions._form')
</form>
@endsection
