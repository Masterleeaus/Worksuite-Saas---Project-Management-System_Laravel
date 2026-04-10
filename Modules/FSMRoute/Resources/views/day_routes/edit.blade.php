@extends('fsmroute::layouts.master')

@section('fsm_content')
<h2 class="mb-3">Edit Day Route: {{ $dayRoute->name }}</h2>
@include('fsmroute::day_routes._form', ['dayRoute' => $dayRoute])
@endsection
