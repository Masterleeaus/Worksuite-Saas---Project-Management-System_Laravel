@extends('fsmskill::layouts.master')

@section('fsmskill_content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>Edit Skill Type: {{ $type->name }}</h2>
    <a href="{{ route('fsmskill.skill-types.index') }}" class="btn btn-outline-secondary">← Back</a>
</div>

<div class="card" style="max-width:600px;">
    <div class="card-body">
        <form method="POST" action="{{ route('fsmskill.skill-types.update', $type->id) }}">
            @csrf
            @include('fsmskill::skill_types._form', ['type' => $type])
            <button type="submit" class="btn btn-primary">Update</button>
        </form>
    </div>
</div>
@endsection
