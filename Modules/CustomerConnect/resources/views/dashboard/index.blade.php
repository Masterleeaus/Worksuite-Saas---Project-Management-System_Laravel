@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex align-items-center justify-content-between mb-3">
        <h4 class="mb-0">Customer Connect</h4>
        <a href="{{ route('customerconnect.campaigns.index') }}" class="btn btn-primary">Campaigns</a>
    </div>

    <div class="row g-3 mb-3">
        <div class="col-md-3"><div class="card"><div class="card-body"><div class="text-muted">Campaigns</div><div class="h3">{{ $campaigns }}</div></div></div></div>
        <div class="col-md-3"><div class="card"><div class="card-body"><div class="text-muted">Queued</div><div class="h3">{{ $queued }}</div></div></div></div>
        <div class="col-md-3"><div class="card"><div class="card-body"><div class="text-muted">Sent</div><div class="h3">{{ $sent }}</div></div></div></div>
        <div class="col-md-3"><div class="card"><div class="card-body"><div class="text-muted">Failed</div><div class="h3">{{ $failed }}</div></div></div></div>
    </div>

    <div class="card">
        <div class="card-header">Deliveries by channel</div>
        <div class="card-body">
            <div class="row g-2">
                @forelse($byChannel as $ch => $total)
                    <div class="col-md-3">
                        <div class="border rounded p-3">
                            <div class="text-muted">{{ ucfirst($ch) }}</div>
                            <div class="h4 mb-0">{{ $total }}</div>
                        </div>
                    </div>
                @empty
                    <div class="text-muted">No deliveries yet.</div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
