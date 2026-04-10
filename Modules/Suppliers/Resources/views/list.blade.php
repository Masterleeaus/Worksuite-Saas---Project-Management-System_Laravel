@extends('layouts.app')

@section('content')
<div class="container">
    <h3>Suppliers List</h3>
    @if(empty($suppliers))
        <p>No suppliers yet. Add some via seeding or CRUD you add next.</p>
    @else
        <ul>
        @foreach($suppliers as $s)
            <li>{ $s['name'] ?? 'Supplier' }</li>
        @endforeach
        </ul>
    @endif
</div>
@endsection
