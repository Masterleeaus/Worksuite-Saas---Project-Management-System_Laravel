@extends('layouts.app')
@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">Edit Campaign</h4>
        <a class="btn btn-light" href="{{ route('customerconnect.campaigns.index') }}">Back</a>
    </div>
    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('customerconnect.campaigns.update', $campaign) }}">
                @csrf @method('PUT')
                @include('customerconnect::customerconnect.campaigns._form', ['campaign' => $campaign])
                <button class="btn btn-primary">Save</button>
            </form>
        </div>
    </div>

    <div class="card mt-3">
        <div class="card-header"><strong>Steps (placeholder)</strong></div>
        <div class="card-body text-muted">
            Steps editor will be added in Pass 2.
        </div>
    </div>
</div>
@endsection
