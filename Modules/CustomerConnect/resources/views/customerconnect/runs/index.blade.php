@extends('layouts.app')
@section('content')
<div class="container-fluid">
    <h4 class="mb-3">Runs</h4>
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead><tr><th>ID</th><th>Campaign</th><th>Status</th><th>Scheduled</th><th></th></tr></thead>
                    <tbody>
                    @forelse($runs as $r)
                        <tr>
                            <td>#{{ $r->id }}</td>
                            <td>{{ $r->campaign->name ?? '-' }}</td>
                            <td><span class="badge badge-secondary">{{ $r->status }}</span></td>
                            <td>{{ optional($r->scheduled_at)->format('Y-m-d H:i') }}</td>
                            <td class="text-right"><a class="btn btn-sm btn-outline-secondary" href="{{ route('customerconnect.runs.show', $r) }}">View</a></td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-muted">No runs yet.</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if(method_exists($runs,'links'))
            <div class="card-footer">{{ $runs->links() }}</div>
        @endif
    </div>
</div>
@endsection
