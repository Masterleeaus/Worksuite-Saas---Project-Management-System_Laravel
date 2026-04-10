@extends('fsmworkflow::layouts.master')

@section('fsmworkflow_content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>
        @if($teamId)
            Kanban Config – {{ $teams->firstWhere('id', $teamId)?->name ?? 'Team #'.$teamId }}
        @else
            Kanban Config – Global Defaults
        @endif
    </h2>
    <a href="{{ route('fsmworkflow.kanban_config.index') }}" class="btn btn-outline-secondary">← Back</a>
</div>

<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ $teamId ? route('fsmworkflow.kanban_config.update', $teamId) : route('fsmworkflow.kanban_config.update.global') }}">
            @csrf

            <h5 class="mb-3">Kanban Card Fields</h5>

            @php
                $fields = [
                    'show_skills'             => 'Skills Required badge',
                    'show_stock_status'       => 'Stock Status indicator',
                    'show_vehicle'            => 'Vehicle Assigned tag',
                    'show_timesheet_progress' => 'Timesheet Progress bar',
                    'show_size'               => 'Job Size badge',
                ];
                $warnings = [
                    'show_warning_overdue'    => 'Overdue flag',
                    'show_warning_gps'        => 'GPS Check-in missing flag',
                    'show_warning_photo'      => 'Evidence Photo missing flag',
                    'show_warning_cert'       => 'Expired Certification flag',
                    'show_client_rating'      => 'Client Rating (last job)',
                ];
            @endphp

            <div class="row g-3 mb-4">
                @foreach($fields as $key => $label)
                <div class="col-md-4">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" role="switch"
                               name="{{ $key }}" id="{{ $key }}" value="1"
                               {{ $config->{$key} ? 'checked' : '' }}>
                        <label class="form-check-label" for="{{ $key }}">{{ $label }}</label>
                    </div>
                </div>
                @endforeach
            </div>

            <h5 class="mb-3">Warning Flags</h5>

            <div class="row g-3 mb-4">
                @foreach($warnings as $key => $label)
                <div class="col-md-4">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" role="switch"
                               name="{{ $key }}" id="{{ $key }}" value="1"
                               {{ $config->{$key} ? 'checked' : '' }}>
                        <label class="form-check-label" for="{{ $key }}">{{ $label }}</label>
                    </div>
                </div>
                @endforeach
            </div>

            <button type="submit" class="btn btn-primary">Save Configuration</button>
            <a href="{{ route('fsmworkflow.kanban_config.index') }}" class="btn btn-outline-secondary ms-2">Cancel</a>
        </form>
    </div>
</div>
@endsection
