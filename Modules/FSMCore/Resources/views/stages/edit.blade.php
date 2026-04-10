@extends('fsmcore::layouts.master')

@section('fsm_content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>Edit Stage: {{ $stage->name }}</h2>
    <div class="d-flex gap-2">
        <a href="{{ route('fsmcore.stages.index') }}" class="btn btn-outline-secondary">Back</a>
        @if(class_exists(\Modules\FSMWorkflow\Http\Controllers\StageActionController::class))
            <a href="{{ route('fsmworkflow.stage_actions.index', $stage->id) }}" class="btn btn-outline-info">
                <i class="fas fa-bolt"></i> Stage Actions
            </a>
        @endif
    </div>
</div>
<div class="card"><div class="card-body">
    <form method="POST" action="{{ route('fsmcore.stages.update', $stage->id) }}">
        @csrf
        @include('fsmcore::stages._form', ['stage' => $stage])
        <div class="mt-3"><button type="submit" class="btn btn-primary">Save</button></div>
    </form>
</div></div>
@endsection
