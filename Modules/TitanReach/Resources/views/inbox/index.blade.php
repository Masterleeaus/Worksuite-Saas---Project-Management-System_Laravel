@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <div class="row mb-3">
        <div class="col-12 d-flex justify-content-between align-items-center">
            <h4>Inbox</h4>
        </div>
    </div>

    {{-- Filters --}}
    <form method="GET" action="{{ route('titanreach.inbox.index') }}" class="row mb-3">
        <div class="col-md-3">
            <select name="channel" class="form-control">
                <option value="">All Channels</option>
                @foreach(['whatsapp','sms','telegram','call','email'] as $ch)
                    <option value="{{ $ch }}" @selected(($filters['channel'] ?? '') === $ch)>{{ ucfirst($ch) }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3">
            <select name="status" class="form-control">
                <option value="">All Statuses</option>
                @foreach(['open','pending','closed','spam'] as $s)
                    <option value="{{ $s }}" @selected(($filters['status'] ?? '') === $s)>{{ ucfirst($s) }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-4">
            <input type="text" name="search" class="form-control" placeholder="Search…" value="{{ $filters['search'] ?? '' }}">
        </div>
        <div class="col-md-2">
            <button class="btn btn-primary w-100">Filter</button>
        </div>
    </form>

    <div class="card">
        <div class="card-body p-0">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Contact</th>
                        <th>Channel</th>
                        <th>Last Message</th>
                        <th>Status</th>
                        <th>Unread</th>
                        <th>Updated</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($conversations as $conv)
                    <tr>
                        <td>{{ $conv->contact->name ?? '—' }}</td>
                        <td><span class="badge badge-info">{{ ucfirst($conv->channel) }}</span></td>
                        <td>{{ Str::limit($conv->last_message ?? '', 60) }}</td>
                        <td><span class="badge badge-{{ $conv->status === 'open' ? 'success' : 'secondary' }}">{{ ucfirst($conv->status) }}</span></td>
                        <td>
                            @if($conv->unread_count > 0)
                                <span class="badge badge-danger">{{ $conv->unread_count }}</span>
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>
                        <td>{{ $conv->updated_at->diffForHumans() }}</td>
                        <td><a href="{{ route('titanreach.inbox.show', $conv->id) }}" class="btn btn-sm btn-primary">View</a></td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="text-center text-muted py-3">No conversations found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3">
        {{ $conversations->withQueryString()->links() }}
    </div>
</div>
@endsection
