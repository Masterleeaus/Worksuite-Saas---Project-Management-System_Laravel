@extends('fsmskill::layouts.master')

@section('fsmskill_content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>Edit Level: {{ $level->name }}</h2>
    <a href="{{ route('fsmskill.skill-levels.index', $skill->id) }}" class="btn btn-outline-secondary">← Back</a>
</div>
<div class="card" style="max-width:500px;">
    <div class="card-body">
        <form method="POST" action="{{ route('fsmskill.skill-levels.update', [$skill->id, $level->id]) }}">
            @csrf
            @include('fsmskill::skill_levels._form', ['level' => $level])
            <button type="submit" class="btn btn-primary">Update</button>
        </form>
    </div>
</div>
@endsection
