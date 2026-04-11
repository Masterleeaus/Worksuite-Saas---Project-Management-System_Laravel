@extends('layouts.app')

@section('content')
    <div class="content-wrapper">
        <div class="d-flex justify-content-between align-items-center action-bar mb-4">
            <h4 class="mb-0">@lang('security::app.inout_permits') — #{{ $permit->id }}</h4>
            <div>
                @if ($permit->status === 'pending' && Route::has('security.inout_permits.approve'))
                    <a href="{{ route('security.inout_permits.approve', $permit->id) }}" class="btn btn-success btn-sm mr-2">
                        <i class="fa fa-check mr-1"></i> Approve
                    </a>
                @endif
                <a href="{{ route('security.inout-permits.edit', $permit->id) }}" class="btn btn-secondary btn-sm mr-2">
                    <i class="fa fa-edit mr-1"></i> @lang('app.edit')
                </a>
                <a href="{{ route('security.inout-permits.index') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="fa fa-arrow-left mr-1"></i> @lang('app.back')
                </a>
            </div>
        </div>

        <div class="card shadow-sm">
            <div class="card-body">
                <dl class="row">
                    <dt class="col-sm-3">Visitor Name</dt><dd class="col-sm-9">{{ $permit->visitor_name }}</dd>
                    <dt class="col-sm-3">Visitor Phone</dt><dd class="col-sm-9">{{ $permit->visitor_phone ?? '—' }}</dd>
                    <dt class="col-sm-3">Purpose</dt><dd class="col-sm-9">{{ $permit->purpose }}</dd>
                    <dt class="col-sm-3">Entry Time</dt><dd class="col-sm-9">{{ $permit->entry_time?->format('Y-m-d H:i') ?? '—' }}</dd>
                    <dt class="col-sm-3">Exit Time</dt><dd class="col-sm-9">{{ $permit->exit_time?->format('Y-m-d H:i') ?? '—' }}</dd>
                    <dt class="col-sm-3">@lang('app.status')</dt>
                    <dd class="col-sm-9"><span class="badge badge-{{ $permit->status === 'approved' ? 'success' : ($permit->status === 'rejected' ? 'danger' : 'warning') }}">{{ ucfirst($permit->status) }}</span></dd>
                    <dt class="col-sm-3">Approved By</dt><dd class="col-sm-9">{{ $permit->approver->name ?? '—' }}</dd>
                </dl>
            </div>
        </div>
    </div>
@endsection
