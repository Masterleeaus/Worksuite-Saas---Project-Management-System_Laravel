@extends('fsmterritory::layouts.master')

@section('fsm_content')
<div class="mb-3">
    <h2>New Branch</h2>
</div>

<form method="POST" action="{{ route('fsmterritory.branches.store') }}">
    @csrf
    @include('fsmterritory::branches._form')
</form>
@endsection
