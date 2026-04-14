@extends('layouts.app')
@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">Delivery #{{ $delivery->id }}</h4>
        <a class="btn btn-light" href="{{ route('customerconnect.deliveries.index') }}">Back</a>
    </div>
    <div class="card">
        <div class="card-body">
            <div><strong>Channel:</strong> {{ $delivery->channel }}</div>
            <div><strong>To:</strong> {{ $delivery->to }}</div>
            <div><strong>Status:</strong> {{ $delivery->status }}</div>
            <hr>
            <div><strong>Subject:</strong> {{ $delivery->subject }}</div>
            <div class="mt-2"><strong>Body:</strong></div>
            <pre class="mb-0" style="white-space: pre-wrap;">{{ $delivery->body }}</pre>
        </div>
    </div>
</div>
@endsection
