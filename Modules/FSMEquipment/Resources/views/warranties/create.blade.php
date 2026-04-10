@extends('fsmequipment::layouts.master')

@section('fsmequipment_content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>Add Warranty: {{ $equipment->name }}</h2>
    <a href="{{ route('fsmequipment.warranties.index', $equipment->id) }}" class="btn btn-outline-secondary">Back</a>
</div>

<form method="POST" action="{{ route('fsmequipment.warranties.store', $equipment->id) }}">
    @csrf
    @include('fsmequipment::warranties._form', ['warranty' => null])
    <div class="mt-3">
        <button type="submit" class="btn btn-success">Add Warranty</button>
    </div>
</form>
@endsection
