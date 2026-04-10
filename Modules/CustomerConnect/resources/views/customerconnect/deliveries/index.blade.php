@extends('layouts.app')
@section('content')
<div class="container-fluid">
    <h4 class="mb-3">Deliveries</h4>
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead><tr><th>ID</th><th>Channel</th><th>To</th><th>Status</th><th>Sent</th><th></th></tr></thead>
                    <tbody>
                    @forelse($deliveries as $d)
                        <tr>
                            <td>#{{ $d->id }}</td>
                            <td>{{ $d->channel }}</td>
                            <td>{{ $d->to }}</td>
                            <td><span class="badge badge-secondary">{{ $d->status }}</span></td>
                            <td>{{ optional($d->sent_at)->format('Y-m-d H:i') }}</td>
                            <td class="text-right"><a class="btn btn-sm btn-outline-secondary" href="{{ route('customerconnect.deliveries.show', $d) }}">View</a></td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-muted">No deliveries yet.</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if(method_exists($deliveries,'links'))
            <div class="card-footer">{{ $deliveries->links() }}</div>
        @endif
    </div>
</div>
@endsection
