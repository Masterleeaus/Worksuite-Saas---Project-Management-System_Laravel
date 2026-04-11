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
        <h2 class="mb-0"><i class="ti-calendar me-2"></i>Cleaning History</h2>
        <div>
            @if(config('clientpulse.allow_extras_requests', true))
                <a href="{{ route('clientpulse.portal.extras.create') }}" class="btn btn-outline-primary me-2">
                    <i class="ti-plus me-1"></i>Request Extras
                </a>
            @endif
        </div>
    </div>

    {{-- Upcoming visits (if FSMRecurring is active) --}}
    @if($upcomingVisits->isNotEmpty())
        <div class="card border-primary mb-4">
            <div class="card-header bg-primary text-white">
                <i class="ti-calendar me-2"></i>Upcoming Scheduled Visits
            </div>
            <ul class="list-group list-group-flush">
                @foreach($upcomingVisits as $visit)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <strong>{{ $visit->name }}</strong>
                            <span class="text-muted ms-2">{{ $visit->location?->name ?? '' }}</span>
                        </div>
                        <div class="text-end">
                            @if($visit->scheduled_date_start)
                                <span class="badge bg-light text-dark">
                                    {{ $visit->scheduled_date_start->format('d M Y, H:i') }}
                                </span>
                            @endif
                            @if($visit->person)
                                <small class="text-muted ms-2">{{ $visit->person->name }}</small>
                            @endif
                        </div>
                    </li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- History timeline --}}
    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Date</th>
                            <th>Time Window</th>
                            <th>Cleaner</th>
                            <th>Duration</th>
                            <th>Status</th>
                            <th>Rating</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                    @forelse($history as $order)
                        @php
                            $duration = '';
                            if ($order->date_start && $order->date_end) {
                                $mins = $order->date_start->diffInMinutes($order->date_end);
                                $duration = $mins >= 60
                                    ? floor($mins / 60) . 'h ' . ($mins % 60) . 'm'
                                    : $mins . 'm';
                            }
                            $existingRating = $ratingsMap->get($order->id);
                            $hasPhotos = $hasEvidencePhotos->has($order->id);
                        @endphp
                        <tr>
                            <td>
                                <a href="{{ route('fsmportal.jobs.show', $order->id) }}" class="fw-semibold">
                                    {{ $order->scheduled_date_start
                                        ? $order->scheduled_date_start->format('d M Y')
                                        : ($order->date_end ? $order->date_end->format('d M Y') : '—') }}
                                </a>
                            </td>
                            <td>
                                @if($order->scheduled_date_start)
                                    {{ $order->scheduled_date_start->format('H:i') }}
                                    @if($order->scheduled_date_end)
                                        – {{ $order->scheduled_date_end->format('H:i') }}
                                    @endif
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                            <td>{{ $order->person?->name ?? '—' }}</td>
                            <td>{{ $duration ?: '—' }}</td>
                            <td>
                                @if($order->stage)
                                    <span class="badge rounded-pill"
                                          style="background-color:{{ $order->stage->color ?? '#198754' }};color:#fff;">
                                        {{ $order->stage->name }}
                                    </span>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                            <td>
                                @if($existingRating)
                                    @for($s = 1; $s <= 5; $s++)
                                        <i class="ti-star{{ $s <= $existingRating ? '' : '-o' }} text-warning"></i>
                                    @endfor
                                @else
                                    <a href="{{ route('clientpulse.portal.rating.show', $order->id) }}"
                                       class="btn btn-sm btn-outline-warning">
                                        Rate
                                    </a>
                                @endif
                            </td>
                            <td class="text-end">
                                @if($hasPhotos)
                                    <a href="{{ route('fsmportal.jobs.show', $order->id) }}#photos"
                                       class="btn btn-sm btn-outline-secondary me-1"
                                       title="View Photos">
                                        <i class="ti-image"></i>
                                    </a>
                                @endif
                                <a href="{{ route('fsmportal.jobs.show', $order->id) }}"
                                   class="btn btn-sm btn-outline-primary">
                                    View
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-5">
                                <i class="ti-calendar fs-1 d-block mb-2 opacity-25"></i>
                                No completed jobs found yet.
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="mt-3">{{ $history->links() }}</div>

</div>
@endsection
