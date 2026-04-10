@extends('fsmequipment::layouts.master')

@section('fsmequipment_content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>Edit Warranty: {{ $equipment->name }}</h2>
    <a href="{{ route('fsmequipment.warranties.index', $equipment->id) }}" class="btn btn-outline-secondary">Back</a>
</div>

<form method="POST" action="{{ route('fsmequipment.warranties.update', [$equipment->id, $warranty->id]) }}">
    @csrf
    @include('fsmequipment::warranties._form')
    <div class="mt-3">
        <button type="submit" class="btn btn-primary">Update Warranty</button>
    </div>
</form>
@endsection
