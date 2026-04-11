@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0"><i class="ti-clipboard me-2"></i>Client Extras Requests</h2>
        <div class="d-flex gap-2 align-items-center">
            <form method="GET" class="d-flex gap-2">
                <select name="status" class="form-select form-select-sm" style="width:150px;">
                    <option value="">All Statuses</option>
                    <option value="pending"        {{ request('status') === 'pending'        ? 'selected' : '' }}>Pending</option>
                    <option value="acknowledged"   {{ request('status') === 'acknowledged'   ? 'selected' : '' }}>Acknowledged</option>
                    <option value="added_to_job"   {{ request('status') === 'added_to_job'   ? 'selected' : '' }}>Added to Job</option>
                </select>
                <button type="submit" class="btn btn-sm btn-outline-secondary">Filter</button>
                @if(request('status'))
                    <a href="{{ route('clientpulse.admin.extras.requests') }}" class="btn btn-sm btn-outline-secondary">Clear</a>
                @endif
            </form>
            <a href="{{ route('clientpulse.admin.extras.index') }}" class="btn btn-sm btn-outline-primary">
                Manage Items
            </a>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Client</th>
                            <th>Job</th>
                            <th>Extras</th>
                            <th>Note</th>
                            <th>Status</th>
                            <th>Submitted</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                    @forelse($requests as $req)
                        <tr class="{{ $req->status === 'pending' ? 'table-warning' : '' }}">
                            <td>{{ $req->id }}</td>
                            <td>{{ $req->client?->name ?? '—' }}</td>
                            <td>
                                @if($req->order)
                                    @if(class_exists(\Modules\FSMPortal\Http\Controllers\PortalJobController::class))
                                        <a href="{{ route('fsmportal.jobs.show', $req->fsm_order_id) }}">
                                            {{ $req->order->name }}
                                        </a>
                                    @else
                                        {{ $req->order->name }}
                                    @endif
                                    @if($req->order->scheduled_date_start)
                                        <br><small class="text-muted">{{ $req->order->scheduled_date_start->format('d M Y') }}</small>
                                    @endif
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                            <td>
                                @if(!empty($req->items))
                                    @php
                                        $itemNames = \Modules\ClientPulse\Models\ExtrasItem::whereIn('id', $req->items)->pluck('name');
                                    @endphp
                                    @foreach($itemNames as $name)
                                        <span class="badge bg-light text-dark me-1">{{ $name }}</span>
                                    @endforeach
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                            <td>
                                @if($req->custom_note)
                                    <span class="d-inline-block text-truncate" style="max-width:180px;"
                                          title="{{ $req->custom_note }}">{{ $req->custom_note }}</span>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                            <td>
                                @if($req->status === 'pending')
                                    <span class="badge bg-warning text-dark">Pending</span>
                                @elseif($req->status === 'acknowledged')
                                    <span class="badge bg-info text-dark">Acknowledged</span>
                                @elseif($req->status === 'added_to_job')
                                    <span class="badge bg-success">Added to Job</span>
                                @else
                                    <span class="badge bg-secondary">{{ $req->status }}</span>
                                @endif
                            </td>
                            <td>
                                <small class="text-muted">{{ $req->created_at->format('d M Y H:i') }}</small>
                            </td>
                            <td class="text-end">
                                @if($req->status === 'pending')
                                    <form method="POST"
                                          action="{{ route('clientpulse.admin.extras.acknowledge', $req->id) }}">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-sm btn-outline-primary">
                                            Acknowledge
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">No extras requests found.</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="mt-3">{{ $requests->links() }}</div>

</div>
@endsection
