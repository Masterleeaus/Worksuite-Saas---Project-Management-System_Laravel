@extends('layouts.app')
@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div class="d-flex justify-content-between align-items-center">
        <h4 class="mb-0">Run #{{ $run->id }}</h4>
        <form method="POST" action="{{ route('customerconnect.runs.execute', $run) }}">
            @csrf
            <button class="btn btn-outline-primary">Execute</button>
        </form>
    </div>
        <a class="btn btn-light" href="{{ route('customerconnect.runs.index') }}">Back</a>
    </div>

    <div class="card mb-3">
        <div class="card-body">
            <div><strong>Campaign:</strong> {{ $run->campaign->name ?? '-' }}</div>
            <div><strong>Status:</strong> {{ $run->status }}</div>
        </div>
    </div>

    <div class="card">
        <div class="card-header"><strong>Deliveries</strong></div>
        <div class="card-body text-muted">
            Delivery table will be expanded in Pass 2/3.
        </div>
    </div>
</div>
@endsection
