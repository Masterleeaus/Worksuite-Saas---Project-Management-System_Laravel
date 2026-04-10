@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <div class="row">
        <div class="col-12">
            <h4 class="mb-4">TitanReach Dashboard</h4>
        </div>
    </div>

    {{-- Stats Cards --}}
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card">
                <div class="card-body text-center">
                    <h5>Conversations</h5>
                    @foreach($conversationCounts as $channel => $count)
                        <span class="badge badge-primary mr-1">{{ ucfirst($channel) }}: {{ $count }}</span>
                    @endforeach
                    @if(empty($conversationCounts))
                        <p class="text-muted">None yet</p>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body text-center">
                    <h5>Campaigns</h5>
                    @foreach($campaignCounts as $status => $count)
                        <span class="badge badge-secondary mr-1">{{ ucfirst($status) }}: {{ $count }}</span>
                    @endforeach
                    @if(empty($campaignCounts))
                        <p class="text-muted">None yet</p>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body text-center">
                    <h5>Quick Links</h5>
                    <a href="{{ route('titanreach.inbox.index') }}" class="btn btn-sm btn-outline-primary mb-1 d-block">Inbox</a>
                    <a href="{{ route('titanreach.campaigns.index') }}" class="btn btn-sm btn-outline-success mb-1 d-block">Campaigns</a>
                    <a href="{{ route('titanreach.contacts.index') }}" class="btn btn-sm btn-outline-info mb-1 d-block">Contacts</a>
                </div>
            </div>
        </div>
    </div>

    {{-- Recent Conversations --}}
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">Recent Conversations</div>
                <div class="card-body p-0">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Contact</th>
                                <th>Channel</th>
                                <th>Last Message</th>
                                <th>Status</th>
                                <th>Updated</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentConversations as $conv)
                            <tr>
                                <td>{{ $conv->contact->name ?? '—' }}</td>
                                <td><span class="badge badge-info">{{ ucfirst($conv->channel) }}</span></td>
                                <td>{{ Str::limit($conv->last_message ?? '', 60) }}</td>
                                <td><span class="badge badge-{{ $conv->status === 'open' ? 'success' : 'secondary' }}">{{ ucfirst($conv->status) }}</span></td>
                                <td>{{ $conv->updated_at->diffForHumans() }}</td>
                                <td><a href="{{ route('titanreach.inbox.show', $conv->id) }}" class="btn btn-sm btn-primary">View</a></td>
                            </tr>
                            @empty
                            <tr><td colspan="6" class="text-center text-muted">No conversations yet.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
