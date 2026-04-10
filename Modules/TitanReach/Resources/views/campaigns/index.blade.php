@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <div class="row mb-3">
        <div class="col-12 d-flex justify-content-between align-items-center">
            <h4>Campaigns</h4>
            <a href="{{ route('titanreach.campaigns.create') }}" class="btn btn-success">+ New Campaign</a>
        </div>
    </div>

    {{-- Filters --}}
    <form method="GET" action="{{ route('titanreach.campaigns.index') }}" class="row mb-3">
        <div class="col-md-3">
            <select name="channel" class="form-control">
                <option value="">All Channels</option>
                @foreach(['whatsapp','sms','telegram','call','multi'] as $ch)
                    <option value="{{ $ch }}" @selected(request('channel') === $ch)>{{ ucfirst($ch) }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3">
            <select name="status" class="form-control">
                <option value="">All Statuses</option>
                @foreach(['draft','scheduled','running','paused','completed'] as $s)
                    <option value="{{ $s }}" @selected(request('status') === $s)>{{ ucfirst($s) }}</option>
                @endforeach
            </select>
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
                        <th>Name</th>
                        <th>Channel</th>
                        <th>Status</th>
                        <th>Audience</th>
                        <th>Scheduled At</th>
                        <th>Stats</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($campaigns as $campaign)
                    <tr>
                        <td>{{ $campaign->name }}</td>
                        <td><span class="badge badge-info">{{ ucfirst($campaign->channel) }}</span></td>
                        <td><span class="badge badge-{{ $campaign->status === 'running' ? 'success' : 'secondary' }}">{{ ucfirst($campaign->status) }}</span></td>
                        <td>{{ ucfirst($campaign->audience_type) }} {{ $campaign->audience_id ? '#'.$campaign->audience_id : '' }}</td>
                        <td>{{ $campaign->scheduled_at ? $campaign->scheduled_at->format('Y-m-d H:i') : '—' }}</td>
                        <td class="small">{{ $campaign->stats ? implode(', ', array_map(fn($k,$v) => "$k:$v", array_keys($campaign->stats), $campaign->stats)) : '—' }}</td>
                        <td>
                            <a href="{{ route('titanreach.campaigns.edit', $campaign->id) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                            <form method="POST" action="{{ route('titanreach.campaigns.run', $campaign->id) }}" class="d-inline">
                                @csrf
                                <button class="btn btn-sm btn-success">Run</button>
                            </form>
                            <form method="POST" action="{{ route('titanreach.campaigns.destroy', $campaign->id) }}" class="d-inline" onsubmit="return confirm('Delete?')">
                                @csrf
                                <button class="btn btn-sm btn-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="text-center text-muted py-3">No campaigns found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="mt-3">{{ $campaigns->withQueryString()->links() }}</div>
</div>
@endsection
