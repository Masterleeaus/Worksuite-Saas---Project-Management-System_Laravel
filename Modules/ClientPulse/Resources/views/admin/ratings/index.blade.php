@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <h2 class="mb-4"><i class="ti-star me-2 text-warning"></i>Client Ratings</h2>

    {{-- Cleaner aggregate scores --}}
    @if($cleanerStats->isNotEmpty())
        <div class="card shadow-sm mb-4">
            <div class="card-header"><strong>Cleaner Rating Summary</strong></div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Cleaner</th>
                                <th>Avg Rating</th>
                                <th>Total Ratings</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($cleanerStats as $stat)
                            <tr>
                                <td>{{ $stat->cleaner?->name ?? '(Unassigned)' }}</td>
                                <td>
                                    @php $avg = round($stat->avg_stars, 1); @endphp
                                    @for($s = 1; $s <= 5; $s++)
                                        <i class="ti-star{{ $s <= $avg ? '' : '-o' }} text-warning"></i>
                                    @endfor
                                    <small class="text-muted ms-1">{{ number_format($avg, 1) }}</small>
                                </td>
                                <td>{{ $stat->total_ratings }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif

    {{-- Individual ratings --}}
    <div class="card shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center">
            <strong>Individual Feedback</strong>
            {{-- Filters --}}
            <form method="GET" class="d-flex gap-2">
                <select name="stars" class="form-select form-select-sm" style="width:120px;">
                    <option value="">All Stars</option>
                    @for($s = 5; $s >= 1; $s--)
                        <option value="{{ $s }}" {{ request('stars') == $s ? 'selected' : '' }}>{{ $s }} ★</option>
                    @endfor
                </select>
                <button type="submit" class="btn btn-sm btn-outline-secondary">Filter</button>
                @if(request()->hasAny(['stars','cleaner_id']))
                    <a href="{{ route('clientpulse.admin.ratings.index') }}" class="btn btn-sm btn-outline-secondary">Clear</a>
                @endif
            </form>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Job</th>
                            <th>Client</th>
                            <th>Cleaner</th>
                            <th>Rating</th>
                            <th>Comment</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                    @forelse($ratings as $r)
                        <tr>
                            <td>
                                @if(class_exists(\Modules\FSMPortal\Http\Controllers\PortalJobController::class))
                                    <a href="{{ route('fsmportal.jobs.show', $r->fsm_order_id) }}">
                                        {{ $r->order?->name ?? '#'.$r->fsm_order_id }}
                                    </a>
                                @else
                                    {{ $r->order?->name ?? '#'.$r->fsm_order_id }}
                                @endif
                            </td>
                            <td>{{ $r->client?->name ?? '—' }}</td>
                            <td>{{ $r->cleaner?->name ?? '—' }}</td>
                            <td>
                                @for($s = 1; $s <= 5; $s++)
                                    <i class="ti-star{{ $s <= $r->stars ? '' : '-o' }} text-warning"></i>
                                @endfor
                            </td>
                            <td>
                                @if($r->comment)
                                    <span class="d-inline-block text-truncate" style="max-width:200px;"
                                          title="{{ $r->comment }}">{{ $r->comment }}</span>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                            <td>
                                <small class="text-muted">
                                    {{ $r->rated_at ? $r->rated_at->format('d M Y H:i') : '—' }}
                                </small>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">No ratings yet.</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="mt-3">{{ $ratings->links() }}</div>

</div>
@endsection
