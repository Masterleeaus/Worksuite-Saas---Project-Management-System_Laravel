@extends('fsmroute::layouts.master')

@section('fsm_content')
<h2 class="mb-3">New Route</h2>
@include('fsmroute::routes._form', ['route' => null])
@endsection
