@extends('fsmterritory::layouts.master')

@section('fsm_content')
<h2 class="mb-3">Edit Territory: {{ $territory->name }}</h2>
@include('fsmterritory::territories._form', ['territory' => $territory])
@endsection
