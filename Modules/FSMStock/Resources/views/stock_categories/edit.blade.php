@extends('fsmstock::layouts.master')
@section('fsmstock_content')
<div class="mb-3">
    <a href="{{ route('fsmstock.stock-categories.index') }}" class="btn btn-sm btn-outline-secondary">&larr; Back</a>
</div>
<div class="card" style="max-width:640px">
    <div class="card-header">Edit Stock Category: {{ $category->name }}</div>
    <div class="card-body">
        <form method="POST" action="{{ route('fsmstock.stock-categories.update', $category->id) }}">
            @csrf
            @include('fsmstock::stock_categories._form')
            <button type="submit" class="btn btn-primary">Update Category</button>
        </form>
    </div>
</div>
@endsection
