@extends('fsmequipmentwarranty::layouts.master')

@section('fsm_content')
<h2 class="mb-3">Edit Warranty Profile: {{ $profile->name }}</h2>
@include('fsmequipmentwarranty::profiles._form', ['profile' => $profile])
@endsection
