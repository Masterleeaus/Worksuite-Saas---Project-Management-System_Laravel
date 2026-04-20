@extends('fsmterritory::layouts.master')

@section('fsm_content')
<h2 class="mb-3">New Territory</h2>
@include('fsmterritory::territories._form', ['territory' => null])
@endsection
