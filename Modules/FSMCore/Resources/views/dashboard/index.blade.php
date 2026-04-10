@extends('fsmcore::layouts.master')

@section('fsm_content')
<div class="row mb-3">
    <div class="col">
        <h2><i class="fas fa-tools"></i> FSM Dashboard</h2>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card text-white bg-primary">
            <div class="card-body">
                <h5 class="card-title">Today's Orders</h5>
                <p class="card-text display-6">{{ $todayOrders }}</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white bg-danger">
            <div class="card-body">
                <h5 class="card-title">Overdue Orders</h5>
                <p class="card-text display-6">{{ $overdueOrders }}</p>
            </div>
        </div>
    </div>
</div>

@if(class_exists(\Modules\FSMActivity\Models\FSMActivity::class) && \Illuminate\Support\Facades\Schema::hasTable('fsm_activities'))
@php
    $activitiesToday = \Modules\FSMActivity\Models\FSMActivity::where('state', 'open')
        ->whereDate('due_date', now()->toDateString())
        ->count();
    $activitiesOverdue = \Modules\FSMActivity\Models\FSMActivity::where('state', 'open')
        ->whereDate('due_date', '<', now()->toDateString())
        ->count();
@endphp
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card text-white bg-warning">
            <div class="card-body">
                <h5 class="card-title">Activities Due Today</h5>
                <p class="card-text display-6">{{ $activitiesToday }}</p>
                <a href="{{ route('fsmactivity.dashboard') }}" class="text-white small">View →</a>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white bg-danger">
            <div class="card-body">
                <h5 class="card-title">Overdue Activities</h5>
                <p class="card-text display-6">{{ $activitiesOverdue }}</p>
                <a href="{{ route('fsmactivity.global.index', ['state' => 'overdue']) }}" class="text-white small">View →</a>
            </div>
        </div>
    </div>
</div>
@endif

<div class="row mb-4">
    <div class="col">
        <h4>Orders by Stage</h4>
        <div class="d-flex flex-wrap gap-2">
            @foreach($stages as $stage)
                <div class="card" style="min-width:140px;">
                    <div class="card-body text-center">
                        <span class="badge rounded-pill" style="background:{{ $stage->color ?? '#6c757d' }};">{{ $stage->orders_count }}</span>
                        <div class="mt-1 small fw-semibold">{{ $stage->name }}</div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>

<div class="d-flex gap-2 flex-wrap">
    <a href="{{ route('fsmcore.orders.index') }}" class="btn btn-primary">All Orders</a>
    <a href="{{ route('fsmcore.orders.kanban') }}" class="btn btn-outline-primary">Kanban View</a>
    <a href="{{ route('fsmcore.orders.create') }}" class="btn btn-success">+ New Order</a>
    <a href="{{ route('fsmcore.locations.index') }}" class="btn btn-outline-secondary">Locations</a>
    <a href="{{ route('fsmcore.teams.index') }}" class="btn btn-outline-secondary">Teams</a>
    <a href="{{ route('fsmcore.stages.index') }}" class="btn btn-outline-secondary">Stages</a>
    <a href="{{ route('fsmcore.territories.index') }}" class="btn btn-outline-secondary">Territories</a>
    <a href="{{ route('fsmcore.equipment.index') }}" class="btn btn-outline-secondary">Equipment</a>
    <a href="{{ route('fsmcore.templates.index') }}" class="btn btn-outline-secondary">Templates</a>
</div>
@endsection
