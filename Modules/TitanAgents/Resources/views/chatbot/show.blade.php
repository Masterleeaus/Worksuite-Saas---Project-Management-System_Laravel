@extends('layouts/layoutMaster')

@section('title', $chatbot->name)

@section('content')
<div class="container-fluid">
    <div class="d-flex align-items-center mb-4">
        <a href="{{ route('titanagents.chatbot.index') }}" class="btn btn-outline-secondary me-3">
            <i class="ti ti-arrow-left"></i>
        </a>
        <div class="flex-fill">
            <h4 class="mb-0">{{ $chatbot->name }}</h4>
            <small class="text-muted">{{ ucfirst($chatbot->ai_provider) }} &bull; {{ $chatbot->ai_model }}</small>
        </div>
        <a href="{{ route('titanagents.chatbot.edit', $chatbot) }}" class="btn btn-outline-primary me-2">Edit</a>
        <span class="badge {{ $chatbot->status === 'active' ? 'bg-success' : 'bg-secondary' }} fs-6">
            {{ ucfirst($chatbot->status) }}
        </span>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body py-3">
                    <h5 class="mb-0">{{ $chatbot->conversations()->count() }}</h5>
                    <small class="text-muted">Conversations</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body py-3">
                    <h5 class="mb-0">{{ $chatbot->customers()->count() }}</h5>
                    <small class="text-muted">Customers</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body py-3">
                    <h5 class="mb-0">{{ $chatbot->articles()->count() }}</h5>
                    <small class="text-muted">KB Articles</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body py-3">
                    <h5 class="mb-0">{{ $chatbot->cannedResponses()->count() }}</h5>
                    <small class="text-muted">Canned Responses</small>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3">
        @foreach([
            ['route' => 'titanagents.chatbot.builder', 'icon' => 'ti-layout-grid', 'label' => 'Builder', 'desc' => 'Visual chatbot builder & embed code'],
            ['route' => 'titanagents.chatbot.inbox.index', 'icon' => 'ti-inbox', 'label' => 'Inbox', 'desc' => 'View all conversations'],
            ['route' => 'titanagents.chatbot.analytics.index', 'icon' => 'ti-chart-bar', 'label' => 'Analytics', 'desc' => 'View conversation stats'],
            ['route' => 'titanagents.chatbot.channels.index', 'icon' => 'ti-git-branch', 'label' => 'Channels', 'desc' => 'Configure deployment channels'],
            ['route' => 'titanagents.chatbot.kb.index', 'icon' => 'ti-books', 'label' => 'Knowledge Base', 'desc' => 'Manage KB articles'],
            ['route' => 'titanagents.chatbot.canned.index', 'icon' => 'ti-messages', 'label' => 'Canned Responses', 'desc' => 'Pre-defined responses'],
            ['route' => 'titanagents.chatbot.train.index', 'icon' => 'ti-brain', 'label' => 'Training', 'desc' => 'Train on KB articles'],
            ['route' => 'titanagents.chatbot.customers.index', 'icon' => 'ti-users', 'label' => 'Customers', 'desc' => 'View customer list'],
        ] as $item)
        <div class="col-md-4">
            <a href="{{ route($item['route'], $chatbot) }}" class="card text-decoration-none h-100">
                <div class="card-body d-flex align-items-center">
                    <div class="avatar avatar-md bg-label-primary rounded me-3 d-flex align-items-center justify-content-center" style="width:48px;height:48px;">
                        <i class="ti {{ $item['icon'] }} fs-4"></i>
                    </div>
                    <div>
                        <h6 class="mb-0">{{ $item['label'] }}</h6>
                        <small class="text-muted">{{ $item['desc'] }}</small>
                    </div>
                </div>
            </a>
        </div>
        @endforeach
    </div>
</div>
@endsection
