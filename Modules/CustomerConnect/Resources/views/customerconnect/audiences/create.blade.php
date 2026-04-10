@extends('layouts.app')
@section('content')
<div class="container-fluid">
    <h4 class="mb-3">New Audience</h4>
    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('customerconnect.audiences.store') }}">
                @csrf
                @include('customerconnect::customerconnect.audiences._form', ['audience' => null])
                <button class="btn btn-primary">Create</button>
                <a class="btn btn-light" href="{{ route('customerconnect.audiences.index') }}">Cancel</a>
            </form>
        </div>
    </div>
</div>
@endsection
