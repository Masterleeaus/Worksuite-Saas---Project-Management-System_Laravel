@extends('layouts.app')
@section('content')
<div class="container">
    <h2>Create Item</h2>
    <form method="post" action="{{ route('inventory.items.store') }}">
        @csrf
        <div><label>Name <input name="name" required></label></div>
        <div><label>SKU <input name="sku"></label></div>
        <div><label>Unit <input name="unit"></label></div>
        <button type="submit">Save</button>
    </form>
</div>
@endsection
