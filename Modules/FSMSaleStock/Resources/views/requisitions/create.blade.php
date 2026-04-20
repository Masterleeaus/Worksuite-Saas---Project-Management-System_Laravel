@extends('fsmsalestock::layouts.master')

@section('fsm_content')
<h2 class="mb-3">New Stock Requisition</h2>
@include('fsmsalestock::requisitions._form', ['requisition' => null])
@endsection
