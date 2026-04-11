@extends('layouts/layoutMaster')

@section('title', ($customer->name ?? 'Anonymous') . ' — Customer Detail')

@section('content')
<div class="container-fluid">
    <div class="d-flex align-items-center mb-4">
        <a href="{{ route('titanagents.chatbot.customers.index', $chatbot) }}" class="btn btn-outline-secondary me-3">
            <i class="ti ti-arrow-left"></i>
        </a>
        <div>
            <h4 class="mb-0">{{ $customer->name ?? 'Anonymous' }}</h4>
            <small class="text-muted">{{ $customer->email }} &bull; {{ ucfirst($customer->channel_type) }}</small>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <h5 class="mb-0">{{ $customer->conversation_count }}</h5>
                    <small class="text-muted">Total Conversations</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <h5 class="mb-0">{{ $customer->phone ?? '—' }}</h5>
                    <small class="text-muted">Phone</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <h5 class="mb-0">{{ $customer->last_seen_at?->diffForHumans() ?? 'Never' }}</h5>
                    <small class="text-muted">Last Seen</small>
                </div>
            </div>
        </div>
    </div>

    <h5 class="mb-3">Conversations</h5>

    @forelse($conversations as $conversation)
        <div class="card mb-3">
            <div class="card-header d-flex align-items-center">
                <span class="badge {{ $conversation->status === 'resolved' ? 'bg-success' : ($conversation->status === 'escalated' ? 'bg-warning' : 'bg-primary') }} me-2">
                    {{ ucfirst($conversation->status) }}
                </span>
                <small class="text-muted me-auto">{{ $conversation->started_at?->format('M d, Y H:i') ?? $conversation->created_at->format('M d, Y H:i') }}</small>
                <small class="text-muted">{{ $conversation->message_count }} messages</small>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-sm mb-0">
                        <tbody>
                            @foreach($conversation->history as $msg)
                                <tr class="{{ $msg->role === 'user' ? 'table-light' : '' }}">
                                    <td width="80"><span class="badge {{ $msg->role === 'user' ? 'bg-primary' : 'bg-secondary' }}">{{ ucfirst($msg->role) }}</span></td>
                                    <td>{{ $msg->content }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @empty
        <div class="card"><div class="card-body text-center text-muted">No conversations found.</div></div>
    @endforelse

    @if($conversations->hasPages())
        {{ $conversations->links() }}
    @endif
</div>
@endsection
