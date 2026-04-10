@extends('layouts.app')

@section('content')
<div class="container py-4">

    {{-- Flash messages --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('info'))
        <div class="alert alert-info alert-dismissible fade show" role="alert">
            {{ session('info') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0"><i class="ti-briefcase me-2"></i>My Jobs</h2>
    </div>

    {{-- Filters --}}
    <form method="GET" class="row g-2 mb-4">
        <div class="col-md-3">
            <select name="status" class="form-select">
                <option value="">All Statuses</option>
                <option value="upcoming"    {{ ($filter['status'] ?? '') === 'upcoming'    ? 'selected' : '' }}>Upcoming</option>
                <option value="in_progress" {{ ($filter['status'] ?? '') === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                <option value="completed"   {{ ($filter['status'] ?? '') === 'completed'   ? 'selected' : '' }}>Completed</option>
            </select>
        </div>
        <div class="col-md-3">
            <input type="date" name="date_from" class="form-control" placeholder="From date"
                   value="{{ $filter['date_from'] ?? '' }}">
        </div>
        <div class="col-md-3">
            <input type="date" name="date_to" class="form-control" placeholder="To date"
                   value="{{ $filter['date_to'] ?? '' }}">
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn btn-primary w-100">Filter</button>
        </div>
        @if(array_filter($filter))
            <div class="col-md-1">
                <a href="{{ route('fsmportal.jobs.index') }}" class="btn btn-outline-secondary w-100">Clear</a>
            </div>
        @endif
    </form>

    {{-- Jobs table --}}
    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Reference</th>
                            <th>Location / Site</th>
                            <th>Scheduled</th>
                            <th>Assigned Worker</th>
                            <th>Stage</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                    @forelse($orders as $order)
                        <tr>
                            <td>
                                <a href="{{ route('fsmportal.jobs.show', $order->id) }}" class="fw-semibold">
                                    {{ $order->name }}
                                </a>
                            </td>
                            <td>{{ $order->location?->name ?? '—' }}</td>
                            <td>
                                @if($order->scheduled_date_start)
                                    {{ $order->scheduled_date_start->format('d M Y') }}<br>
                                    <small class="text-muted">{{ $order->scheduled_date_start->format('H:i') }}
                                        @if($order->scheduled_date_end)
                                            – {{ $order->scheduled_date_end->format('H:i') }}
                                        @endif
                                    </small>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                            <td>{{ $order->person?->name ?? '—' }}</td>
                            <td>
                                @if($order->stage)
                                    <span class="badge rounded-pill"
                                          style="background-color:{{ $order->stage->color ?? '#6c757d' }}; color:#fff;">
                                        {{ $order->stage->name }}
                                    </span>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                            <td class="text-end">
                                <a href="{{ route('fsmportal.jobs.show', $order->id) }}"
                                   class="btn btn-sm btn-outline-primary">View</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-5">
                                <i class="ti-briefcase fs-1 d-block mb-2 opacity-25"></i>
                                No jobs found.
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="mt-3">{{ $orders->links() }}</div>

</div>
@endsection
