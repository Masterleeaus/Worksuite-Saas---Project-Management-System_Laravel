@extends('fsmworkflow::layouts.master')

@section('fsmworkflow_content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h2>Edit Stage Action</h2>
        <p class="text-muted mb-0">Stage: <strong>{{ $stage->name }}</strong></p>
    </div>
    <a href="{{ route('fsmworkflow.stage_actions.index', $stage->id) }}" class="btn btn-outline-secondary">← Back</a>
</div>

<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('fsmworkflow.stage_actions.update', [$stage->id, $action->id]) }}">
            @csrf
            @include('fsmworkflow::stage_actions._form', ['action' => $action])
            <div class="mt-3">
                <button type="submit" class="btn btn-primary">Save Changes</button>
                <a href="{{ route('fsmworkflow.stage_actions.index', $stage->id) }}" class="btn btn-outline-secondary ms-2">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
