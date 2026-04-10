@extends('layouts.app')
@section('content')
<div class="container">
    <h2>Edit Item</h2>
    <form method="post" action="{{ route('inventory.items.update', $item) }}">
        @csrf @method('PUT')
        <div><label>Name <input name="name" value="{{ $item->name }}" required></label></div>
        <div><label>SKU <input name="sku" value="{{ $item->sku }}"></label></div>
        <div><label>Unit <input name="unit" value="{{ $item->unit }}"></label></div>
        <button type="submit">Save</button>
    </form>
</div>
@endsection
