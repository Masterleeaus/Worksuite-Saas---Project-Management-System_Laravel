@extends('layouts.app')
@section('content')
<div class="container">
    <h1>{{ __('inventory::labels.title') }}</h1>
    <ul>
        <li><a href="{{ route('inventory.items.index') }}">Items</a></li>
        <li><a href="{{ route('inventory.warehouses.index') }}">Warehouses</a></li>
        <li><a href="{{ route('inventory.movements.index') }}">Movements</a></li>
    </ul>
</div>
@endsection
