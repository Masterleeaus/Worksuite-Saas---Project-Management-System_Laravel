@extends('fsmskill::layouts.master')

@section('fsmskill_content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>Edit Skill: {{ $empSkill->skill?->name }} for {{ $user->name }}</h2>
    <a href="{{ route('fsmskill.employee-skills.index', $user->id) }}" class="btn btn-outline-secondary">← Back</a>
</div>
<div class="card" style="max-width:640px;">
    <div class="card-body">
        <form method="POST" action="{{ route('fsmskill.employee-skills.update', [$user->id, $empSkill->id]) }}"
              enctype="multipart/form-data">
            @csrf
            @include('fsmskill::employee_skills._form', ['empSkill' => $empSkill])
            <button type="submit" class="btn btn-primary">Update</button>
        </form>
    </div>
</div>
@endsection
