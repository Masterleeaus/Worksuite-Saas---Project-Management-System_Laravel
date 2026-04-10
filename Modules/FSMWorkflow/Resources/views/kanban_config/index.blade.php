@extends('fsmworkflow::layouts.master')

@section('fsmworkflow_content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>Kanban Card Configuration</h2>
    <a href="{{ route('fsmworkflow.kanban_config.edit.global') }}" class="btn btn-outline-primary">Edit Global Defaults</a>
</div>

<p class="text-muted">Configure which additional fields appear on Kanban cards. Global defaults apply to all teams; per-team settings override the global defaults.</p>

<div class="card mb-4">
    <div class="card-header fw-semibold">Global Default</div>
    <div class="card-body">
        @php $global = \Modules\FSMWorkflow\Models\FSMKanbanConfig::forTeam(null); @endphp
        @include('fsmworkflow::kanban_config._badge_row', ['config' => $global])
        <div class="mt-2">
            <a href="{{ route('fsmworkflow.kanban_config.edit.global') }}" class="btn btn-sm btn-outline-primary">Edit</a>
        </div>
    </div>
</div>

@if($teams->count())
<div class="card">
    <div class="card-header fw-semibold">Per-Team Overrides</div>
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>Team</th>
                    <th>Skills</th>
                    <th>Stock</th>
                    <th>Vehicle</th>
                    <th>Timesheet</th>
                    <th>Size</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($teams as $team)
                @php $cfg = \Modules\FSMWorkflow\Models\FSMKanbanConfig::where('team_id', $team->id)->first(); @endphp
                <tr>
                    <td>{{ $team->name }}</td>
                    @if($cfg)
                        <td>@include('fsmworkflow::kanban_config._bool_badge', ['val' => $cfg->show_skills])</td>
                        <td>@include('fsmworkflow::kanban_config._bool_badge', ['val' => $cfg->show_stock_status])</td>
                        <td>@include('fsmworkflow::kanban_config._bool_badge', ['val' => $cfg->show_vehicle])</td>
                        <td>@include('fsmworkflow::kanban_config._bool_badge', ['val' => $cfg->show_timesheet_progress])</td>
                        <td>@include('fsmworkflow::kanban_config._bool_badge', ['val' => $cfg->show_size])</td>
                        <td>
                            <a href="{{ route('fsmworkflow.kanban_config.edit', $team->id) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                            <form method="POST" action="{{ route('fsmworkflow.kanban_config.destroy', $cfg->id) }}" class="d-inline"
                                  onsubmit="return confirm('Remove per-team config?')">
                                @csrf
                                <button class="btn btn-sm btn-outline-danger">Reset</button>
                            </form>
                        </td>
                    @else
                        <td colspan="5" class="text-muted fst-italic">Using global defaults</td>
                        <td>
                            <a href="{{ route('fsmworkflow.kanban_config.edit', $team->id) }}" class="btn btn-sm btn-outline-secondary">Customise</a>
                        </td>
                    @endif
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif
@endsection
