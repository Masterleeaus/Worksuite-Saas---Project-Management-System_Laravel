@extends('layouts/layoutMaster')

@section('title', 'Inbox — ' . $chatbot->name)

@section('content')
<div class="container-fluid">
    <div class="d-flex align-items-center mb-4">
        <a href="{{ route('titanagents.chatbot.show', $chatbot) }}" class="btn btn-outline-secondary me-3">
            <i class="ti ti-arrow-left"></i>
        </a>
        <h4 class="mb-0">Conversation Inbox — {{ $chatbot->name }}</h4>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">{{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
    @endif

    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Customer</th>
                            <th>Channel</th>
                            <th>Status</th>
                            <th>Messages</th>
                            <th>Started</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($conversations as $conv)
                        @php
                            $statusColors = ['open'=>'primary','resolved'=>'success','escalated'=>'danger'];
                            $color = $statusColors[$conv->status] ?? 'secondary';
                        @endphp
                        <tr>
                            <td><span class="text-muted small">{{ $conv->id }}</span></td>
                            <td>
                                @if($conv->customer)
                                    <strong>{{ $conv->customer->name ?? 'Anonymous' }}</strong>
                                    @if($conv->customer->email)
                                        <br><small class="text-muted">{{ $conv->customer->email }}</small>
                                    @endif
                                @else
                                    <span class="text-muted">Anonymous</span>
                                @endif
                            </td>
                            <td>
                                @php $channelIcons = ['web'=>'ti-world','telegram'=>'ti-brand-telegram','whatsapp'=>'ti-brand-whatsapp','messenger'=>'ti-brand-facebook']; @endphp
                                <span class="badge bg-label-secondary">
                                    <i class="ti {{ $channelIcons[$conv->channel_type] ?? 'ti-message' }} me-1"></i>
                                    {{ ucfirst($conv->channel_type ?? 'web') }}
                                </span>
                            </td>
                            <td><span class="badge bg-{{ $color }}">{{ ucfirst($conv->status) }}</span></td>
                            <td>{{ $conv->message_count }}</td>
                            <td>{{ $conv->started_at ? $conv->started_at->diffForHumans() : $conv->created_at->diffForHumans() }}</td>
                            <td>
                                <a href="{{ route('titanagents.chatbot.inbox.show', [$chatbot, $conv]) }}" class="btn btn-sm btn-outline-primary">View</a>
                                @if($conv->status === 'open')
                                <form method="POST" action="{{ route('titanagents.chatbot.inbox.resolve', [$chatbot, $conv]) }}" class="d-inline">
                                    @csrf
                                    <button class="btn btn-sm btn-outline-success">Resolve</button>
                                </form>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-5">
                                <i class="ti ti-messages fs-1 d-block mb-2"></i>
                                No conversations yet.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($conversations->hasPages())
            <div class="card-footer">{{ $conversations->links() }}</div>
        @endif
    </div>
</div>
@endsection
