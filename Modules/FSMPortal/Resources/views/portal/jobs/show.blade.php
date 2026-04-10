@extends('layouts.app')

@section('content')
<div class="container py-4">

    {{-- Flash messages --}}
    @foreach(['success','info','error'] as $type)
        @if(session($type))
            <div class="alert alert-{{ $type === 'error' ? 'danger' : $type }} alert-dismissible fade show" role="alert">
                {{ session($type) }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
    @endforeach

    {{-- Back link --}}
    <a href="{{ route('fsmportal.jobs.index') }}" class="btn btn-link ps-0 mb-3">
        &larr; Back to My Jobs
    </a>

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-start mb-4 flex-wrap gap-2">
        <div>
            <h2 class="mb-1">{{ $order->name }}</h2>
            <span class="text-muted">{{ $order->location?->name ?? '' }}</span>
        </div>
        <div class="d-flex gap-2 flex-wrap">
            @if(config('fsmportal.allow_pdf_download', true))
                <a href="{{ route('fsmportal.jobs.pdf', $order->id) }}" class="btn btn-outline-secondary" target="_blank">
                    <i class="ti-download me-1"></i>Download Report
                </a>
            @endif
        </div>
    </div>

    <div class="row g-4">

        {{-- Left column --}}
        <div class="col-lg-8">

            {{-- Status Timeline --}}
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white fw-semibold">Job Progress</div>
                <div class="card-body">
                    <div class="d-flex align-items-center flex-wrap gap-0">
                        @foreach($stages as $index => $stage)
                            @php
                                $isCurrent  = $order->stage_id === $stage->id;
                                $isPast     = $order->stage && $order->stage->sequence > $stage->sequence;
                                $isPastOrCurrent = $isCurrent || $isPast;
                                $color      = $stage->color ?? '#6c757d';
                            @endphp
                            <div class="d-flex align-items-center">
                                {{-- Stage node --}}
                                <div class="text-center" style="min-width:80px;">
                                    <div class="rounded-circle d-inline-flex align-items-center justify-content-center mb-1 border border-2"
                                         style="width:36px;height:36px;
                                                background:{{ $isPastOrCurrent ? $color : '#e9ecef' }};
                                                border-color:{{ $color }} !important;
                                                color:{{ $isPastOrCurrent ? '#fff' : '#adb5bd' }};">
                                        @if($isPast)
                                            <i class="ti-check" style="font-size:14px;"></i>
                                        @elseif($isCurrent)
                                            <i class="ti-location-pin" style="font-size:14px;"></i>
                                        @else
                                            <span style="font-size:11px;">{{ $index + 1 }}</span>
                                        @endif
                                    </div>
                                    <div class="small {{ $isCurrent ? 'fw-bold' : 'text-muted' }}" style="font-size:11px;line-height:1.2;">
                                        {{ $stage->name }}
                                    </div>
                                </div>
                                {{-- Connector --}}
                                @if(!$loop->last)
                                    <div style="height:2px;width:24px;background:{{ $isPast ? $color : '#dee2e6' }};flex-shrink:0;margin-bottom:18px;"></div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Job Details --}}
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white fw-semibold">Job Details</div>
                <div class="card-body">
                    <dl class="row mb-0">
                        <dt class="col-sm-4 text-muted">Reference</dt>
                        <dd class="col-sm-8">{{ $order->name }}</dd>

                        <dt class="col-sm-4 text-muted">Location / Site</dt>
                        <dd class="col-sm-8">
                            {{ $order->location?->name ?? '—' }}
                            @if($order->location?->street)
                                <br><small class="text-muted">
                                    {{ implode(', ', array_filter([
                                        $order->location->street,
                                        $order->location->city,
                                        $order->location->state,
                                        $order->location->zip,
                                    ])) }}
                                </small>
                            @endif
                        </dd>

                        <dt class="col-sm-4 text-muted">Scheduled Start</dt>
                        <dd class="col-sm-8">
                            {{ $order->scheduled_date_start?->format('d M Y H:i') ?? '—' }}
                        </dd>

                        <dt class="col-sm-4 text-muted">Scheduled End</dt>
                        <dd class="col-sm-8">
                            {{ $order->scheduled_date_end?->format('d M Y H:i') ?? '—' }}
                        </dd>

                        @if($checkInTime)
                            <dt class="col-sm-4 text-muted">Actual Check-in</dt>
                            <dd class="col-sm-8">
                                <span class="badge bg-success">
                                    <i class="ti-check me-1"></i>{{ $checkInTime->format('d M Y H:i') }}
                                </span>
                            </dd>
                        @endif

                        @if($order->date_end)
                            <dt class="col-sm-4 text-muted">Job Completed</dt>
                            <dd class="col-sm-8">
                                <span class="badge bg-primary">{{ $order->date_end->format('d M Y H:i') }}</span>
                            </dd>
                        @endif

                        <dt class="col-sm-4 text-muted">Current Stage</dt>
                        <dd class="col-sm-8">
                            @if($order->stage)
                                <span class="badge rounded-pill"
                                      style="background:{{ $order->stage->color ?? '#6c757d' }};color:#fff;">
                                    {{ $order->stage->name }}
                                </span>
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </dd>
                    </dl>
                </div>
            </div>

            {{-- Evidence Photos --}}
            @if($evidencePhotos->isNotEmpty())
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-white fw-semibold">
                        <i class="ti-camera me-1"></i>Completion Photos
                    </div>
                    <div class="card-body">
                        <div class="row g-2">
                            @foreach($evidencePhotos as $photo)
                                <div class="col-6 col-md-4 col-lg-3">
                                    <a href="{{ $photo->photo_url }}" target="_blank" rel="noopener">
                                        <img src="{{ $photo->photo_url }}"
                                             alt="{{ $photo->original_filename }}"
                                             class="img-fluid rounded border"
                                             style="aspect-ratio:1/1;object-fit:cover;width:100%;">
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            {{-- Description / Notes --}}
            @if($order->description)
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-white fw-semibold">Notes</div>
                    <div class="card-body">
                        <p class="mb-0 text-muted">{{ $order->description }}</p>
                    </div>
                </div>
            @endif

        </div>

        {{-- Right column --}}
        <div class="col-lg-4">

            {{-- Assigned Worker --}}
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white fw-semibold">Assigned Worker</div>
                <div class="card-body text-center">
                    @if($order->person)
                        @if($order->person->image)
                            <img src="{{ asset('user-uploads/avatar/' . $order->person->image) }}"
                                 class="rounded-circle mb-2 border"
                                 style="width:72px;height:72px;object-fit:cover;"
                                 alt="{{ $order->person->name }}">
                        @else
                            <div class="rounded-circle d-inline-flex align-items-center justify-content-center bg-secondary text-white mb-2"
                                 style="width:72px;height:72px;font-size:28px;">
                                {{ strtoupper(substr($order->person->name, 0, 1)) }}
                            </div>
                        @endif
                        <div class="fw-semibold">{{ $order->person->name }}</div>
                    @else
                        <span class="text-muted">Not yet assigned</span>
                    @endif
                </div>
            </div>

            {{-- Request Re-clean --}}
            @if(config('fsmportal.allow_reclean_request', true))
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-white fw-semibold">Not Satisfied?</div>
                    <div class="card-body">
                        @if($recleanRequest && $recleanRequest->isPending())
                            <div class="alert alert-warning mb-0 py-2">
                                <i class="ti-reload me-1"></i>Re-clean request submitted. Our team will be in touch.
                            </div>
                        @elseif($recleanRequest && $recleanRequest->status === 'accepted')
                            <div class="alert alert-success mb-0 py-2">
                                <i class="ti-check me-1"></i>Re-clean accepted. A new job will be scheduled.
                            </div>
                        @else
                            <p class="text-muted small mb-3">
                                If you are not satisfied with the result, you can request a re-clean.
                                Our team will review and contact you.
                            </p>
                            <form method="POST" action="{{ route('fsmportal.jobs.reclean', $order->id) }}">
                                @csrf
                                <div class="mb-3">
                                    <textarea name="reason" class="form-control form-control-sm" rows="3"
                                              placeholder="Briefly describe the issue (optional)…"
                                              maxlength="1000"></textarea>
                                </div>
                                <button type="submit" class="btn btn-warning btn-sm w-100">
                                    <i class="ti-reload me-1"></i>Request Re-clean
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            @endif

            {{-- Live status indicator --}}
            @if(!$order->date_end && config('fsmportal.status_poll_interval', 0) > 0)
                <div class="card shadow-sm mb-4" id="live-status-card">
                    <div class="card-header bg-white fw-semibold d-flex align-items-center gap-2">
                        <span class="badge bg-success rounded-pill" style="width:10px;height:10px;padding:0;display:inline-block;" id="live-dot"></span>
                        Live Status
                    </div>
                    <div class="card-body">
                        <div id="live-stage-name" class="text-muted small">Loading…</div>
                    </div>
                </div>

                <script>
                (function() {
                    var interval = {{ (int) config('fsmportal.status_poll_interval', 30) * 1000 }};
                    var url = '{{ route('fsmportal.jobs.status', $order->id) }}';
                    var stageName = document.getElementById('live-stage-name');

                    function poll() {
                        fetch(url, {
                            headers: { 'X-Requested-With': 'XMLHttpRequest' }
                        })
                        .then(function(r) { return r.json(); })
                        .then(function(data) {
                            if (stageName && data.stage_name) {
                                stageName.textContent = 'Current stage: ' + data.stage_name;
                            }
                            if (data.date_end) {
                                // Job complete – stop polling
                                clearInterval(timer);
                                var card = document.getElementById('live-status-card');
                                if (card) card.style.display = 'none';
                            }
                        })
                        .catch(function() { /* silently ignore */ });
                    }

                    poll();
                    var timer = setInterval(poll, interval);
                })();
                </script>
            @endif

        </div>
    </div>
</div>
@endsection
