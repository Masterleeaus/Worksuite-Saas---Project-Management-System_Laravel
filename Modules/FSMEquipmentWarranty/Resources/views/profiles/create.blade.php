@extends('fsmequipmentwarranty::layouts.master')

@section('fsm_content')
<h2 class="mb-3">New Warranty Profile</h2>
@include('fsmequipmentwarranty::profiles._form', ['profile' => null])
@endsection
