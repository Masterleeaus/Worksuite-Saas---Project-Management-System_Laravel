@extends('layouts/layoutMaster')

@section('title', 'Channels — ' . $chatbot->name)

@section('content')
<div class="container-fluid">
    <div class="d-flex align-items-center mb-4">
        <a href="{{ route('titanagents.chatbot.show', $chatbot) }}" class="btn btn-outline-secondary me-3">
            <i class="ti ti-arrow-left"></i>
        </a>
        <h4 class="mb-0">Channels — {{ $chatbot->name }}</h4>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card mb-4">
        <div class="card-header"><h6 class="mb-0">Add / Update Channel</h6></div>
        <div class="card-body">
            <form method="POST" action="{{ route('titanagents.chatbot.channels.store', $chatbot) }}">
                @csrf
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Channel Type</label>
                        <select name="channel_type" class="form-select" required>
                            @foreach(['web' => 'Web Widget', 'telegram' => 'Telegram', 'whatsapp' => 'WhatsApp', 'messenger' => 'Messenger'] as $value => $label)
                                <option value="{{ $value }}">{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Channel Identifier</label>
                        <input type="text" name="channel_identifier" class="form-control" placeholder="Bot token, page ID, etc.">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Webhook URL</label>
                        <input type="url" name="webhook_url" class="form-control" placeholder="https://...">
                    </div>
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary">Save Channel</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-header"><h6 class="mb-0">Configured Channels</h6></div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Type</th>
                            <th>Identifier</th>
                            <th>Webhook URL</th>
                            <th>Active</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($channels as $channel)
                            <tr>
                                <td><span class="badge bg-primary">{{ ucfirst($channel->channel_type) }}</span></td>
                                <td>{{ $channel->channel_identifier ?? '—' }}</td>
                                <td>{{ $channel->webhook_url ? Str::limit($channel->webhook_url, 40) : '—' }}</td>
                                <td>
                                    <span class="badge {{ $channel->is_active ? 'bg-success' : 'bg-secondary' }}">
                                        {{ $channel->is_active ? 'Yes' : 'No' }}
                                    </span>
                                </td>
                                <td>
                                    <form method="POST" action="{{ route('titanagents.chatbot.channels.destroy', [$chatbot, $channel]) }}" class="d-inline">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger" onclick="return confirm('Remove?')">Remove</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="text-center text-muted py-4">No channels configured yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
