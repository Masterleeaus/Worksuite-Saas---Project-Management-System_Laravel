@extends('layouts.app')

@section('content')
    <div class="content-wrapper">
        <div class="d-flex justify-content-between align-items-center action-bar mb-4">
            <h4 class="mb-0">@lang('security::app.access_log_details') — #{{ $log->id }}</h4>
            <a href="{{ route('security.access_logs.index') }}" class="btn btn-outline-secondary btn-sm">
                <i class="fa fa-arrow-left mr-1"></i> @lang('app.back')
            </a>
        </div>

        <div class="card shadow-sm">
            <div class="card-body">
                <dl class="row">
                    <dt class="col-sm-3">Log Type</dt><dd class="col-sm-9">{{ $log->log_type ?? '—' }}</dd>
                    <dt class="col-sm-3">Entity</dt><dd class="col-sm-9">{{ $log->entity_type ?? '—' }} {{ $log->entity_id ? '#'.$log->entity_id : '' }}</dd>
                    <dt class="col-sm-3">@lang('app.user')</dt><dd class="col-sm-9">{{ $log->user->name ?? '—' }}</dd>
                    <dt class="col-sm-3">IP Address</dt><dd class="col-sm-9">{{ $log->ip_address ?? '—' }}</dd>
                    <dt class="col-sm-3">Result</dt>
                    <dd class="col-sm-9">
                        <span class="badge badge-{{ ($log->access_result ?? '') === 'granted' ? 'success' : 'danger' }}">
                            {{ ucfirst($log->access_result ?? '—') }}
                        </span>
                    </dd>
                    <dt class="col-sm-3">Description</dt><dd class="col-sm-9">{{ $log->description ?? '—' }}</dd>
                    <dt class="col-sm-3">@lang('app.date')</dt><dd class="col-sm-9">{{ $log->created_at?->format('Y-m-d H:i:s') }}</dd>
                </dl>
            </div>
        </div>
    </div>
@endsection
