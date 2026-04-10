@extends('fsmequipment::layouts.master')

@section('fsmequipment_content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>Edit Template: {{ $template->name }}</h2>
    <a href="{{ route('fsmequipment.repair-templates.index') }}" class="btn btn-outline-secondary">Back</a>
</div>

<form method="POST" action="{{ route('fsmequipment.repair-templates.update', $template->id) }}">
    @csrf
    @include('fsmequipment::repair_templates._form')
    <div class="mt-3">
        <button type="submit" class="btn btn-primary">Update Template</button>
    </div>
</form>
@endsection
