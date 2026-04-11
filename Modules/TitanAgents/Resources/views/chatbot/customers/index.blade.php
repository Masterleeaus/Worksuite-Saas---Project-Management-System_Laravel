@extends('layouts/layoutMaster')

@section('title', 'Customers — ' . $chatbot->name)

@section('content')
<div class="container-fluid">
    <div class="d-flex align-items-center mb-4">
        <a href="{{ route('titanagents.chatbot.show', $chatbot) }}" class="btn btn-outline-secondary me-3">
            <i class="ti ti-arrow-left"></i>
        </a>
        <h4 class="mb-0">Customers — {{ $chatbot->name }}</h4>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Channel</th>
                            <th>Conversations</th>
                            <th>Last Seen</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($customers as $customer)
                            <tr>
                                <td>
                                    <a href="{{ route('titanagents.chatbot.customers.show', [$chatbot, $customer]) }}">
                                        {{ $customer->name ?? 'Anonymous' }}
                                    </a>
                                </td>
                                <td>{{ $customer->email ?? '—' }}</td>
                                <td><span class="badge bg-info">{{ ucfirst($customer->channel_type) }}</span></td>
                                <td>{{ $customer->conversations_count }}</td>
                                <td>{{ $customer->last_seen_at?->diffForHumans() ?? '—' }}</td>
                                <td>
                                    <a href="{{ route('titanagents.chatbot.customers.show', [$chatbot, $customer]) }}" class="btn btn-sm btn-outline-primary">View</a>
                                    <form method="POST" action="{{ route('titanagents.chatbot.customers.destroy', [$chatbot, $customer]) }}" class="d-inline">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete customer?')">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="text-center text-muted py-4">No customers yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($customers->hasPages())
            <div class="card-footer">{{ $customers->links() }}</div>
        @endif
    </div>
</div>
@endsection
