@extends('layouts/layoutMaster')

@section('title', 'Conversation #' . $conversation->id)

@push('styles')
<style>
.transcript-wrap { display: flex; flex-direction: column; gap: 14px; max-width: 720px; margin: 0 auto; }
.msg-row { display: flex; gap: 10px; align-items: flex-end; }
.msg-row.user { flex-direction: row-reverse; }
.msg-avatar { width: 32px; height: 32px; border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0; font-size: 14px; }
.msg-bubble { max-width: 70%; padding: 10px 14px; border-radius: 14px; line-height: 1.5; font-size: 14px; }
.msg-row.bot  .msg-bubble { background: #f3f4f6; color: #111827; border-bottom-left-radius: 4px; }
.msg-row.user .msg-bubble { background: #6366f1; color: #fff; border-bottom-right-radius: 4px; }
.msg-row.system .msg-bubble { background: #fef9c3; color: #713f12; font-size: 12px; font-style: italic; max-width: 100%; }
.msg-time { font-size: 11px; color: #9ca3af; margin-top: 4px; }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <div class="d-flex align-items-center mb-4 gap-3">
        <a href="{{ route('titanagents.chatbot.inbox.index', $chatbot) }}" class="btn btn-outline-secondary">
            <i class="ti ti-arrow-left"></i>
        </a>
        <div class="flex-fill">
            <h4 class="mb-0">Conversation #{{ $conversation->id }}</h4>
            <small class="text-muted">
                {{ $chatbot->name }} &bull;
                {{ ucfirst($conversation->channel_type ?? 'web') }} &bull;
                Started {{ $conversation->started_at ? $conversation->started_at->diffForHumans() : $conversation->created_at->diffForHumans() }}
            </small>
        </div>
        @php $statusColors = ['open'=>'primary','resolved'=>'success','escalated'=>'danger']; @endphp
        <span class="badge bg-{{ $statusColors[$conversation->status] ?? 'secondary' }} fs-6">
            {{ ucfirst($conversation->status) }}
        </span>
        @if($conversation->status === 'open')
            <form method="POST" action="{{ route('titanagents.chatbot.inbox.resolve', [$chatbot, $conversation]) }}">
                @csrf
                <button class="btn btn-success btn-sm">Mark Resolved</button>
            </form>
            <form method="POST" action="{{ route('titanagents.chatbot.inbox.escalate', [$chatbot, $conversation]) }}">
                @csrf
                <button class="btn btn-warning btn-sm">Escalate</button>
            </form>
        @endif
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">{{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
    @endif

    <div class="row g-4">
        {{-- Transcript --}}
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header"><h6 class="mb-0">Transcript</h6></div>
                <div class="card-body" style="max-height:600px;overflow-y:auto;">
                    <div class="transcript-wrap">
                        @forelse($messages as $msg)
                        <div class="msg-row {{ $msg->role }}">
                            @if($msg->role === 'bot' || $msg->role === 'assistant')
                                <div class="msg-avatar bg-label-primary">🤖</div>
                            @elseif($msg->role === 'user')
                                <div class="msg-avatar bg-label-secondary">👤</div>
                            @else
                                <div class="msg-avatar bg-label-warning">⚙️</div>
                            @endif
                            <div>
                                <div class="msg-bubble">{{ $msg->content }}</div>
                                <div class="msg-time {{ $msg->role === 'user' ? 'text-end' : '' }}">
                                    {{ $msg->created_at->format('H:i · d M Y') }}
                                    @if($msg->token_count)
                                        &bull; {{ $msg->token_count }} tokens
                                    @endif
                                </div>
                            </div>
                        </div>
                        @empty
                            <p class="text-center text-muted">No messages recorded.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        {{-- Sidebar --}}
        <div class="col-lg-4">
            {{-- Customer info --}}
            <div class="card mb-3">
                <div class="card-header"><h6 class="mb-0">Customer</h6></div>
                <div class="card-body">
                    @if($customer)
                        <dl class="row small mb-0">
                            <dt class="col-5 text-muted">Name</dt><dd class="col-7">{{ $customer->name ?? '—' }}</dd>
                            <dt class="col-5 text-muted">Email</dt><dd class="col-7">{{ $customer->email ?? '—' }}</dd>
                            <dt class="col-5 text-muted">Phone</dt><dd class="col-7">{{ $customer->phone ?? '—' }}</dd>
                            <dt class="col-5 text-muted">Channel</dt><dd class="col-7">{{ ucfirst($customer->channel_type) }}</dd>
                            <dt class="col-5 text-muted">Conversations</dt><dd class="col-7">{{ $customer->conversation_count }}</dd>
                        </dl>
                    @else
                        <p class="text-muted small mb-0">Anonymous visitor</p>
                    @endif
                </div>
            </div>

            {{-- Conversation details --}}
            <div class="card">
                <div class="card-header"><h6 class="mb-0">Details</h6></div>
                <div class="card-body">
                    <dl class="row small mb-0">
                        <dt class="col-5 text-muted">Session ID</dt>
                        <dd class="col-7 text-truncate"><code class="small">{{ $conversation->session_id ?? '—' }}</code></dd>
                        <dt class="col-5 text-muted">Status</dt>
                        <dd class="col-7"><span class="badge bg-{{ $statusColors[$conversation->status]??'secondary' }}">{{ ucfirst($conversation->status) }}</span></dd>
                        <dt class="col-5 text-muted">Messages</dt>
                        <dd class="col-7">{{ $messages->count() }}</dd>
                        <dt class="col-5 text-muted">Started</dt>
                        <dd class="col-7">{{ ($conversation->started_at ?? $conversation->created_at)?->format('d M Y H:i') ?? '—' }}</dd>
                        <dt class="col-5 text-muted">Ended</dt>
                        <dd class="col-7">{{ $conversation->ended_at?->format('d M Y H:i') ?? '—' }}</dd>
                        @if($conversation->resolution_notes)
                        <dt class="col-5 text-muted">Notes</dt>
                        <dd class="col-7">{{ $conversation->resolution_notes }}</dd>
                        @endif
                    </dl>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
