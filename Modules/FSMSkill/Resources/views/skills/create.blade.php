@extends('fsmskill::layouts.master')

@section('fsmskill_content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>New Skill</h2>
    <a href="{{ route('fsmskill.skills.index') }}" class="btn btn-outline-secondary">← Back</a>
</div>
<div class="card" style="max-width:600px;">
    <div class="card-body">
        <form method="POST" action="{{ route('fsmskill.skills.store') }}">
            @csrf
            @include('fsmskill::skills._form', ['skill' => null, 'types' => $types])
            <button type="submit" class="btn btn-primary">Create</button>
        </form>
    </div>
</div>
@endsection
