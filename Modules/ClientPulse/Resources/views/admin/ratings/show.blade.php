@extends('layouts.app')

@section('content')
<div class="container py-4">

    <div class="d-flex align-items-center mb-4 gap-3">
        <a href="{{ route('clientpulse.admin.ratings.index') }}" class="btn btn-outline-secondary btn-sm">
            &larr; Back to Ratings
        </a>
        <h2 class="mb-0">Rating #{{ $rating->id }}</h2>
    </div>

    <div class="row g-4">

        {{-- Rating Card --}}
        <div class="col-lg-6">
            <div class="card shadow-sm h-100">
                <div class="card-header fw-semibold">Rating Details</div>
                <div class="card-body">
                    <dl class="row mb-0">

                        <dt class="col-sm-4">Job</dt>
                        <dd class="col-sm-8">
                            @if($rating->order)
                                <a href="{{ route('fsmcore.orders.show', $rating->order) }}"
                                   target="_blank">
                                    {{ $rating->order->name ?? 'Job #'.$rating->fsm_order_id }}
                                </a>
                            @else
                                <span class="text-muted">Job #{{ $rating->fsm_order_id }}</span>
                            @endif
                        </dd>

                        <dt class="col-sm-4">Client</dt>
                        <dd class="col-sm-8">
                            {{ $rating->client->name ?? 'User #'.$rating->client_id }}
                            @if($rating->client?->email)
                                <br><small class="text-muted">{{ $rating->client->email }}</small>
                            @endif
                        </dd>

                        <dt class="col-sm-4">Cleaner</dt>
                        <dd class="col-sm-8">
                            {{ $rating->cleaner->name ?? ($rating->cleaner_id ? 'User #'.$rating->cleaner_id : '—') }}
                        </dd>

                        <dt class="col-sm-4">Stars</dt>
                        <dd class="col-sm-8">
                            @for($i = 1; $i <= 5; $i++)
                                <span style="color:{{ $i <= $rating->stars ? '#f59e0b' : '#d1d5db' }}; font-size:1.4rem;">&#9733;</span>
                            @endfor
                            <span class="ms-2 fw-semibold">{{ $rating->stars }}/5</span>
                        </dd>

                        <dt class="col-sm-4">Rated At</dt>
                        <dd class="col-sm-8">
                            {{ $rating->rated_at ? $rating->rated_at->format('d M Y, g:i A') : ($rating->created_at ? $rating->created_at->format('d M Y, g:i A') : '—') }}
                        </dd>

                    </dl>
                </div>
            </div>
        </div>

        {{-- Comment Card --}}
        <div class="col-lg-6">
            <div class="card shadow-sm h-100">
                <div class="card-header fw-semibold">Client Comment</div>
                <div class="card-body">
                    @if($rating->comment)
                        <blockquote class="blockquote mb-0">
                            <p class="fst-italic text-muted">"{{ $rating->comment }}"</p>
                        </blockquote>
                    @else
                        <p class="text-muted mb-0">No comment provided.</p>
                    @endif
                </div>
            </div>
        </div>

        {{-- Cleaner Aggregate --}}
        @if($rating->cleaner_id)
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header fw-semibold">Cleaner Overall Performance</div>
                <div class="card-body">
                    @php
                        $avg   = \Modules\ClientPulse\Models\JobRating::averageForCleaner($rating->cleaner_id);
                        $count = \Modules\ClientPulse\Models\JobRating::countForCleaner($rating->cleaner_id);
                    @endphp
                    <p class="mb-0">
                        <strong>{{ $rating->cleaner->name ?? 'Cleaner #'.$rating->cleaner_id }}</strong>
                        has an average of
                        <strong>{{ number_format($avg, 1) }}/5</strong>
                        across
                        <strong>{{ $count }}</strong> {{ Str::plural('rating', $count) }}.
                    </p>
                </div>
            </div>
        </div>
        @endif

    </div>{{-- /row --}}

</div>
@endsection
