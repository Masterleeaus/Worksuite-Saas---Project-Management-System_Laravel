@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <div class="row mb-3">
        <div class="col-12 d-flex justify-content-between align-items-center">
            <h4>Call Campaigns</h4>
            <a href="{{ route('titanreach.calls.create') }}" class="btn btn-success">+ New Call Campaign</a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card">
        <div class="card-body p-0">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Name</th>
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
                        <td><span class="badge badge-{{ $campaign->status === 'running' ? 'success' : 'secondary' }}">{{ ucfirst($campaign->status) }}</span></td>
                        <td>{{ ucfirst(str_replace('_', ' ', $campaign->audience_type)) }}</td>
                        <td>{{ $campaign->scheduled_at ? $campaign->scheduled_at->format('Y-m-d H:i') : '—' }}</td>
                        <td class="small">{{ $campaign->stats ? json_encode($campaign->stats) : '—' }}</td>
                        <td>
                            <a href="{{ route('titanreach.calls.edit', $campaign->id) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                            <form method="POST" action="{{ route('titanreach.calls.run', $campaign->id) }}" class="d-inline">
                                @csrf
                                <button class="btn btn-sm btn-success">Run</button>
                            </form>
                            <form method="POST" action="{{ route('titanreach.calls.destroy', $campaign->id) }}" class="d-inline" onsubmit="return confirm('Delete?')">
                                @csrf
                                <button class="btn btn-sm btn-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="text-center text-muted py-3">No call campaigns found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="mt-3">{{ $campaigns->withQueryString()->links() }}</div>
</div>
@endsection
