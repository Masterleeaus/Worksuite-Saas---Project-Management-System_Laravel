@extends('layouts.app')

@section('content')
    <div class="content-wrapper">
        <div class="d-flex justify-content-between align-items-center action-bar mb-4">
            <h4 class="mb-0">@lang('security::app.packages') — #{{ $package->id }}</h4>
            <div>
                <a href="{{ route('security.packages.edit', $package->id) }}" class="btn btn-secondary btn-sm mr-2">
                    <i class="fa fa-edit mr-1"></i> @lang('app.edit')
                </a>
                <a href="{{ route('security.packages.index') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="fa fa-arrow-left mr-1"></i> @lang('app.back')
                </a>
            </div>
        </div>

        <div class="card shadow-sm">
            <div class="card-body">
                <dl class="row">
                    <dt class="col-sm-3">Sender</dt><dd class="col-sm-9">{{ $package->sender_name }}</dd>
                    <dt class="col-sm-3">Tracking Number</dt><dd class="col-sm-9">{{ $package->tracking_number ?? '—' }}</dd>
                    <dt class="col-sm-3">Recipient</dt><dd class="col-sm-9">{{ $package->recipient->name ?? '—' }}</dd>
                    <dt class="col-sm-3">Description</dt><dd class="col-sm-9">{{ $package->description ?? '—' }}</dd>
                    <dt class="col-sm-3">Received At</dt><dd class="col-sm-9">{{ $package->received_at?->format('Y-m-d H:i') ?? '—' }}</dd>
                    <dt class="col-sm-3">Collected At</dt><dd class="col-sm-9">{{ $package->collected_at?->format('Y-m-d H:i') ?? '—' }}</dd>
                    <dt class="col-sm-3">@lang('app.status')</dt>
                    <dd class="col-sm-9"><span class="badge badge-{{ $package->status === 'collected' ? 'success' : 'info' }}">{{ ucfirst($package->status) }}</span></dd>
                </dl>
            </div>
        </div>
    </div>
@endsection
