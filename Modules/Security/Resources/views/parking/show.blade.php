@extends('layouts.app')

@section('content')
    <div class="content-wrapper">
        <div class="d-flex justify-content-between align-items-center action-bar mb-4">
            <h4 class="mb-0">@lang('security::app.parking') — #{{ $record->id }}</h4>
            <div>
                <a href="{{ route('security.parking.edit', $record->id) }}" class="btn btn-secondary btn-sm mr-2">
                    <i class="fa fa-edit mr-1"></i> @lang('app.edit')
                </a>
                <a href="{{ route('security.parking.index') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="fa fa-arrow-left mr-1"></i> @lang('app.back')
                </a>
            </div>
        </div>

        <div class="card shadow-sm">
            <div class="card-body">
                <dl class="row">
                    <dt class="col-sm-3">Vehicle Plate</dt><dd class="col-sm-9">{{ $record->vehicle_plate }}</dd>
                    <dt class="col-sm-3">Vehicle Type</dt><dd class="col-sm-9">{{ $record->vehicle_type ?? '—' }}</dd>
                    <dt class="col-sm-3">Bay Number</dt><dd class="col-sm-9">{{ $record->bay_number ?? '—' }}</dd>
                    <dt class="col-sm-3">Entry Time</dt><dd class="col-sm-9">{{ $record->entry_time?->format('Y-m-d H:i') }}</dd>
                    <dt class="col-sm-3">Exit Time</dt><dd class="col-sm-9">{{ $record->exit_time?->format('Y-m-d H:i') ?? '—' }}</dd>
                    <dt class="col-sm-3">@lang('app.status')</dt>
                    <dd class="col-sm-9"><span class="badge badge-{{ $record->status === 'parked' ? 'success' : 'secondary' }}">{{ ucfirst($record->status) }}</span></dd>
                </dl>
            </div>
        </div>
    </div>
@endsection
