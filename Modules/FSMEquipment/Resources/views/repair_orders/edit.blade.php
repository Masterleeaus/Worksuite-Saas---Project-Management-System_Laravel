@extends('fsmequipment::layouts.master')

@section('fsmequipment_content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>Edit Repair Order: {{ $repair->name }}</h2>
    <div class="d-flex gap-2">
        <a href="{{ route('fsmequipment.repair-orders.show', $repair->id) }}" class="btn btn-outline-primary">View</a>
        <a href="{{ route('fsmequipment.repair-orders.index') }}" class="btn btn-outline-secondary">Back</a>
    </div>
</div>

<form method="POST" action="{{ route('fsmequipment.repair-orders.update', $repair->id) }}">
    @csrf
    @include('fsmequipment::repair_orders._form', ['selectedEquipment' => null, 'selectedOrder' => null])
    <div class="mt-3">
        <button type="submit" class="btn btn-primary">Update Repair Order</button>
    </div>
</form>
@endsection
