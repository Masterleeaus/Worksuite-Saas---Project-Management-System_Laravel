@extends('fsmroute::layouts.master')

@section('fsm_content')
<h2 class="mb-3">New Day Route</h2>
@include('fsmroute::day_routes._form', ['dayRoute' => null])
@endsection
