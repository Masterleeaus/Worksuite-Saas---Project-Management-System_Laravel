@extends('layouts.app')
@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">Edit Audience</h4>
        <a class="btn btn-light" href="{{ route('customerconnect.audiences.index') }}">Back</a>
    </div>
    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('customerconnect.audiences.update', $audience) }}">
                @csrf @method('PUT')
                @include('customerconnect::customerconnect.audiences._form', ['audience' => $audience])
                <button class="btn btn-primary">Save</button>
            </form>
        </div>
    </div>
    <div class="card mt-3">
        <div class="card-header"><strong>Members</strong></div>
        <div class="card-body text-muted">
            Members management will be added in Pass 2.
        </div>
    </div>
</div>
@endsection
