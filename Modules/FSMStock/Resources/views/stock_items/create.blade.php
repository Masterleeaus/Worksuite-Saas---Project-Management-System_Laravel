@extends('fsmstock::layouts.master')
@section('fsmstock_content')
<div class="mb-3">
    <a href="{{ route('fsmstock.stock-items.index') }}" class="btn btn-sm btn-outline-secondary">&larr; Back</a>
</div>
<div class="card" style="max-width:780px">
    <div class="card-header">New Stock Item</div>
    <div class="card-body">
        <form method="POST" action="{{ route('fsmstock.stock-items.store') }}">
            @csrf
            @include('fsmstock::stock_items._form')
            <button type="submit" class="btn btn-primary">Create Item</button>
        </form>
    </div>
</div>
@endsection
