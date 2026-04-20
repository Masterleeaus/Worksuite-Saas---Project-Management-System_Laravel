@extends('fsmsalestock::layouts.master')

@section('fsm_content')
<h2 class="mb-3">Edit Stock Requisition #{{ $requisition->id }}</h2>
@include('fsmsalestock::requisitions._form', ['requisition' => $requisition])
@endsection
