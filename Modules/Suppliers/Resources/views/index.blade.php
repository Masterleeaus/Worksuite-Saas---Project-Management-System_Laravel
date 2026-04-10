@extends('layouts.app')

@section('content')
<div class="container">
    <h3>Suppliers</h3>
    <p class="mb-3">Manage your vendor directory, documents, and compliance in one spot.</p>
    <a href="{ route('suppliers.list') }" class="btn btn-primary">View Suppliers</a>
</div>
@endsection
