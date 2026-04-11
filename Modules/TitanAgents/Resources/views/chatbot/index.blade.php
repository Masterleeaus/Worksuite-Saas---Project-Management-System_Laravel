@extends('layouts/layoutMaster')

@section('title', 'Chatbots')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0">Chatbots</h4>
        <a href="{{ route('titanagents.chatbot.create') }}" class="btn btn-primary">
            <i class="ti ti-plus me-1"></i> New Chatbot
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">
        @forelse($chatbots as $chatbot)
            <div class="col-md-4 mb-4">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            @if($chatbot->avatar)
                                <img src="{{ $chatbot->avatar->url }}" alt="Avatar" class="rounded-circle me-2" width="40" height="40">
                            @else
                                <div class="avatar avatar-sm me-2 bg-label-primary rounded-circle d-flex align-items-center justify-content-center" style="width:40px;height:40px;">
                                    <i class="ti ti-robot"></i>
                                </div>
                            @endif
                            <div>
                                <h6 class="mb-0">{{ $chatbot->name }}</h6>
                                <small class="text-muted">{{ ucfirst($chatbot->ai_provider) }}</small>
                            </div>
                            <span class="badge ms-auto {{ $chatbot->status === 'active' ? 'bg-success' : 'bg-secondary' }}">
                                {{ ucfirst($chatbot->status) }}
                            </span>
                        </div>
                        @if($chatbot->description)
                            <p class="text-muted small mb-3">{{ Str::limit($chatbot->description, 100) }}</p>
                        @endif
                    </div>
                    <div class="card-footer d-flex gap-2">
                        <a href="{{ route('titanagents.chatbot.show', $chatbot) }}" class="btn btn-sm btn-outline-primary flex-fill">View</a>
                        <a href="{{ route('titanagents.chatbot.edit', $chatbot) }}" class="btn btn-sm btn-outline-secondary flex-fill">Edit</a>
                        <form method="POST" action="{{ route('titanagents.chatbot.destroy', $chatbot) }}" onsubmit="return confirm('Delete this chatbot?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger">Delete</button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="card">
                    <div class="card-body text-center py-5">
                        <i class="ti ti-robot fs-1 text-muted mb-3 d-block"></i>
                        <p class="text-muted">No chatbots yet. <a href="{{ route('titanagents.chatbot.create') }}">Create your first one</a>.</p>
                    </div>
                </div>
            </div>
        @endforelse
    </div>
</div>
@endsection
