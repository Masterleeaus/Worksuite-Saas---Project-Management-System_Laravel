@extends('layouts.app')

@section('content')
    <div class="content-wrapper">
        <div class="d-flex justify-content-between align-items-center action-bar mb-4">
            <h4 class="mb-0">@lang('security::app.dashboard')</h4>
        </div>

        <div class="row">
            <div class="col-md-2 col-sm-6 mb-3">
                <div class="card text-center shadow-sm border-primary">
                    <div class="card-body p-3">
                        <h4 class="mb-0 text-primary">{{ $stats['access_cards'] ?? 0 }}</h4>
                        <small class="text-muted">@lang('security::app.access_cards')</small>
                    </div>
                </div>
            </div>
            <div class="col-md-2 col-sm-6 mb-3">
                <div class="card text-center shadow-sm border-warning">
                    <div class="card-body p-3">
                        <h4 class="mb-0 text-warning">{{ $stats['pending_permits'] ?? 0 }}</h4>
                        <small class="text-muted">@lang('security::app.pending_approvals')</small>
                    </div>
                </div>
            </div>
            <div class="col-md-2 col-sm-6 mb-3">
                <div class="card text-center shadow-sm border-info">
                    <div class="card-body p-3">
                        <h4 class="mb-0 text-info">{{ $stats['work_permits'] ?? 0 }}</h4>
                        <small class="text-muted">@lang('security::app.work_permits')</small>
                    </div>
                </div>
            </div>
            <div class="col-md-2 col-sm-6 mb-3">
                <div class="card text-center shadow-sm border-success">
                    <div class="card-body p-3">
                        <h4 class="mb-0 text-success">{{ $stats['packages'] ?? 0 }}</h4>
                        <small class="text-muted">@lang('security::app.packages')</small>
                    </div>
                </div>
            </div>
            <div class="col-md-2 col-sm-6 mb-3">
                <div class="card text-center shadow-sm">
                    <div class="card-body p-3">
                        <h4 class="mb-0">{{ $stats['parking'] ?? 0 }}</h4>
                        <small class="text-muted">@lang('security::app.parking')</small>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-md-6 mb-3">
                <div class="card shadow-sm">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h6 class="mb-0">@lang('security::app.pending_approvals')</h6>
                        @if (Route::has('security.approvals'))
                            <a href="{{ route('security.approvals') }}" class="btn btn-sm btn-outline-primary">@lang('app.viewAll')</a>
                        @endif
                    </div>
                    <div class="card-body p-0">
                        <p class="text-muted text-center p-4">@lang('app.noRecordFound')</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-3">
                <div class="card shadow-sm">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h6 class="mb-0">@lang('security::app.access_logs')</h6>
                        @if (Route::has('security.access_logs.index'))
                            <a href="{{ route('security.access_logs.index') }}" class="btn btn-sm btn-outline-primary">@lang('app.viewAll')</a>
                        @endif
                    </div>
                    <div class="card-body p-0">
                        <p class="text-muted text-center p-4">@lang('app.noRecordFound')</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
