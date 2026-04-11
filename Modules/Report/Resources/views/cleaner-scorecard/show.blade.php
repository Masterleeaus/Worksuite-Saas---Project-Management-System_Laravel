@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <div class="d-flex justify-content-between align-items-center action-bar">
        <div>
            <a href="{{ route('report.fsm.cleaner-scorecard') }}" class="btn btn-light mr-2">
                <i class="material-icons f-16">arrow_back</i>
            </a>
            <h4 class="mb-0 f-18 d-inline-block">Scorecard — {{ $cleaner->name }}</h4>
        </div>
    </div>

    <div class="row mt-4">
        {{-- Completed --}}
        <div class="col-lg-2 col-md-4 col-6 mb-3">
            <div class="card shadow-sm border-0 text-center h-100">
                <div class="card-body">
                    <p class="text-muted f-12 mb-1">Jobs Completed</p>
                    <h3 class="f-w-600 text-success">{{ $scorecard['completed'] ?? 0 }}</h3>
                </div>
            </div>
        </div>
        {{-- Cancelled --}}
        <div class="col-lg-2 col-md-4 col-6 mb-3">
            <div class="card shadow-sm border-0 text-center h-100">
                <div class="card-body">
                    <p class="text-muted f-12 mb-1">Cancelled</p>
                    <h3 class="f-w-600 text-danger">{{ $scorecard['cancelled'] ?? 0 }}</h3>
                </div>
            </div>
        </div>
        {{-- Recleans --}}
        <div class="col-lg-2 col-md-4 col-6 mb-3">
            <div class="card shadow-sm border-0 text-center h-100">
                <div class="card-body">
                    <p class="text-muted f-12 mb-1">Recleans</p>
                    <h3 class="f-w-600 text-warning">{{ $scorecard['recleans'] ?? 0 }}</h3>
                </div>
            </div>
        </div>
        {{-- Rating --}}
        <div class="col-lg-2 col-md-4 col-6 mb-3">
            <div class="card shadow-sm border-0 text-center h-100">
                <div class="card-body">
                    <p class="text-muted f-12 mb-1">Avg Rating</p>
                    <h3 class="f-w-600">
                        @if(!empty($scorecard['avg_rating']))
                            <span class="text-warning">{{ $scorecard['avg_rating'] }} ★</span>
                        @else
                            <span class="text-muted">N/A</span>
                        @endif
                    </h3>
                </div>
            </div>
        </div>
        {{-- Punctuality --}}
        <div class="col-lg-2 col-md-4 col-6 mb-3">
            <div class="card shadow-sm border-0 text-center h-100">
                <div class="card-body">
                    <p class="text-muted f-12 mb-1">Punctuality</p>
                    <h3 class="f-w-600">
                        {{ $scorecard['punctuality_pct'] !== null ? $scorecard['punctuality_pct'] . '%' : '—' }}
                    </h3>
                </div>
            </div>
        </div>
        {{-- Complaints --}}
        <div class="col-lg-2 col-md-4 col-6 mb-3">
            <div class="card shadow-sm border-0 text-center h-100">
                <div class="card-body">
                    <p class="text-muted f-12 mb-1">Complaints</p>
                    <h3 class="f-w-600 {{ ($scorecard['complaints'] ?? 0) > 0 ? 'text-danger' : 'text-success' }}">
                        {{ $scorecard['complaints'] ?? 0 }}
                    </h3>
                </div>
            </div>
        </div>
    </div>

    <p class="text-muted f-12 mt-1">
        Period: {{ $fromDate }} — {{ $toDate }}
    </p>
</div>
@endsection
