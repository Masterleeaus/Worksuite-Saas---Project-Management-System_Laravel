@extends('communication::layouts.master')

@section('communication_content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2 class="mb-0">
        <i class="bi bi-inbox me-2"></i>Communications Inbox
    </h2>
    <div class="d-flex gap-2">
        <a href="{{ route('communications.compose') }}" class="btn btn-primary btn-sm">
            <i class="bi bi-pencil-square me-1"></i>Compose
        </a>
        <a href="{{ route('communications.bulk') }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-envelope-paper me-1"></i>Bulk Send
        </a>
    </div>
</div>

{{-- Filters --}}
<form method="GET" class="row g-2 mb-3">
    <div class="col-md-2">
        <select name="type" class="form-select form-select-sm">
            <option value="">All Channels</option>
            @foreach($channels as $key => $label)
                <option value="{{ $key }}" {{ ($filter['type'] ?? '') === $key ? 'selected' : '' }}>{{ $label }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-2">
        <select name="status" class="form-select form-select-sm">
            <option value="">All Statuses</option>
            @foreach($statuses as $key => $label)
                <option value="{{ $key }}" {{ ($filter['status'] ?? '') === $key ? 'selected' : '' }}>{{ $label }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-4">
        <input type="text" name="q" class="form-control form-control-sm" placeholder="Search subject, body, address…" value="{{ $filter['q'] ?? '' }}">
    </div>
    <div class="col-auto">
        <button class="btn btn-sm btn-secondary">Filter</button>
        <a href="{{ route('communications.index') }}" class="btn btn-sm btn-outline-secondary">Reset</a>
    </div>
</form>

<div class="card">
    <div class="table-responsive">
        <table class="table table-sm table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>Channel</th>
                    <th>To</th>
                    <th>Subject / Body Preview</th>
                    <th>Status</th>
                    <th>Sent At</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse($communications as $msg)
                <tr>
                    <td>
                        @php
                            $icons = ['email'=>'envelope','sms'=>'chat-dots','chat'=>'chat','push'=>'bell'];
                            $icon = $icons[$msg->type] ?? 'question-circle';
                        @endphp
                        <i class="bi bi-{{ $icon }} me-1"></i>
                        {{ $channels[$msg->type] ?? ucfirst($msg->type) }}
                    </td>
                    <td class="text-truncate" style="max-width:160px;">{{ $msg->to_address }}</td>
                    <td class="text-truncate" style="max-width:280px;">
                        @if($msg->subject)
                            <strong>{{ $msg->subject }}</strong><br>
                        @endif
                        <small class="text-muted">{{ \Illuminate\Support\Str::limit(strip_tags($msg->body), 80) }}</small>
                    </td>
                    <td>
                        @php
                            $badgeMap = ['queued'=>'secondary','sent'=>'info','delivered'=>'primary','failed'=>'danger','read'=>'success'];
                            $badge = $badgeMap[$msg->status] ?? 'light';
                        @endphp
                        <span class="badge bg-{{ $badge }}">{{ $statuses[$msg->status] ?? ucfirst($msg->status) }}</span>
                    </td>
                    <td>{{ $msg->sent_at ? $msg->sent_at->format('d M Y H:i') : '—' }}</td>
                    <td>
                        <a href="{{ route('communications.show', $msg->id) }}" class="btn btn-xs btn-outline-primary btn-sm">View</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center py-4 text-muted">No communications found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($communications->hasPages())
    <div class="card-footer">
        {{ $communications->links() }}
    </div>
    @endif
</div>
@endsection
