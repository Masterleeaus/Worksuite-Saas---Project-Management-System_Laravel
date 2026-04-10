@extends('layouts.master')

@section('content')
<div class="container">
  <h3>Suppliers</h3>

  {{-- Yajra DataTables table markup (server-side) --}}
  {!! $dataTable->table(['class' => 'table table-striped table-bordered w-100'], true) !!}
</div>
@endsection

@push('scripts')
  {{-- DataTables JS bootstrap --}}
  {!! $dataTable->scripts() !!}
@endpush