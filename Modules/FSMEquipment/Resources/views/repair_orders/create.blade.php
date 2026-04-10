@extends('fsmequipment::layouts.master')

@section('fsmequipment_content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>New Repair Order</h2>
    <a href="{{ route('fsmequipment.repair-orders.index') }}" class="btn btn-outline-secondary">Back</a>
</div>

<form method="POST" action="{{ route('fsmequipment.repair-orders.store') }}">
    @csrf
    @include('fsmequipment::repair_orders._form', ['repair' => null])
    <div class="mt-3">
        <button type="submit" class="btn btn-success">Create Repair Order</button>
    </div>
</form>
@endsection
