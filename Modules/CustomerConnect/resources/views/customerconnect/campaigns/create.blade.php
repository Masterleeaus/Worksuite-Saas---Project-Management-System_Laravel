@extends('layouts.app')
@section('content')
<div class="container-fluid">
    <h4 class="mb-3">New Campaign</h4>
    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('customerconnect.campaigns.store') }}">
                @csrf
                @include('customerconnect::customerconnect.campaigns._form', ['campaign' => null])
                <button class="btn btn-primary">Create</button>
                <a class="btn btn-light" href="{{ route('customerconnect.campaigns.index') }}">Cancel</a>
            </form>
        </div>
    </div>
</div>
@endsection
