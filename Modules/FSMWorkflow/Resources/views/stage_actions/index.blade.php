@extends('fsmworkflow::layouts.master')

@section('fsmworkflow_content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h2>Stage Actions</h2>
        <p class="text-muted mb-0">Stage: <strong>{{ $stage->name }}</strong></p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('fsmcore.stages.index') }}" class="btn btn-outline-secondary">← Stages</a>
        <a href="{{ route('fsmworkflow.stage_actions.create', $stage->id) }}" class="btn btn-success">+ New Action</a>
    </div>
</div>

<div class="card">
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th style="width:60px;">Seq</th>
                    <th>Name</th>
                    <th>Action Type</th>
                    <th>Active</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($actions as $action)
                <tr>
                    <td>{{ $action->sequence }}</td>
                    <td>{{ $action->name ?? '—' }}</td>
                    <td>
                        <span class="badge bg-primary">{{ \Modules\FSMWorkflow\Models\FSMStageAction::ACTION_TYPES[$action->action_type] ?? $action->action_type }}</span>
                    </td>
                    <td>
                        @if($action->active)
                            <span class="badge bg-success">Yes</span>
                        @else
                            <span class="badge bg-secondary">No</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('fsmworkflow.stage_actions.edit', [$stage->id, $action->id]) }}" class="btn btn-sm btn-outline-primary">Edit</a>

                        {{-- Test fire form --}}
                        <button class="btn btn-sm btn-outline-info" data-bs-toggle="modal" data-bs-target="#testModal{{ $action->id }}">
                            Test
                        </button>

                        <form method="POST" action="{{ route('fsmworkflow.stage_actions.destroy', [$stage->id, $action->id]) }}" class="d-inline"
                              onsubmit="return confirm('Delete this action?')">
                            @csrf
                            <button class="btn btn-sm btn-outline-danger">Delete</button>
                        </form>

                        {{-- Test Fire Modal --}}
                        <div class="modal fade" id="testModal{{ $action->id }}" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <form method="POST" action="{{ route('fsmworkflow.stage_actions.test', [$stage->id, $action->id]) }}">
                                        @csrf
                                        <div class="modal-header">
                                            <h5 class="modal-title">Test-fire: {{ $action->name ?? $action->action_type }}</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <label class="form-label">Select FSM Order to test against</label>
                                            <input type="number" name="order_id" class="form-control" placeholder="Order ID" required>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                            <button type="submit" class="btn btn-primary">Fire</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="text-center text-muted py-4">No actions configured for this stage.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
