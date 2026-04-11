@extends('layouts.app')

@section('content')
    <div class="content-wrapper">
        <div class="d-flex justify-content-between align-items-center action-bar mb-4">
            <h4 class="mb-0">@lang('security::app.work_permits') — #{{ $permit->id }}</h4>
            <div>
                @if (($permit->status ?? 'pending') === 'pending' && Route::has('security.work_permits.approve'))
                    <a href="{{ route('security.work_permits.approve', $permit->id) }}" class="btn btn-success btn-sm mr-2">
                        <i class="fa fa-check mr-1"></i> Approve
                    </a>
                @endif
                <a href="{{ route('security.work-permits.edit', $permit->id) }}" class="btn btn-secondary btn-sm mr-2">
                    <i class="fa fa-edit mr-1"></i> @lang('app.edit')
                </a>
                <a href="{{ route('security.work-permits.index') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="fa fa-arrow-left mr-1"></i> @lang('app.back')
                </a>
            </div>
        </div>

        <div class="card shadow-sm">
            <div class="card-body">
                <dl class="row">
                    <dt class="col-sm-3">Contractor</dt><dd class="col-sm-9">{{ $permit->contractor_name }}</dd>
                    <dt class="col-sm-3">Company</dt><dd class="col-sm-9">{{ $permit->company_name ?? '—' }}</dd>
                    <dt class="col-sm-3">Work Description</dt><dd class="col-sm-9">{{ $permit->work_description }}</dd>
                    <dt class="col-sm-3">Start Date</dt><dd class="col-sm-9">{{ $permit->start_date?->format(company()->date_format ?? 'Y-m-d') }}</dd>
                    <dt class="col-sm-3">End Date</dt><dd class="col-sm-9">{{ $permit->end_date?->format(company()->date_format ?? 'Y-m-d') }}</dd>
                    <dt class="col-sm-3">@lang('app.status')</dt>
                    <dd class="col-sm-9"><span class="badge badge-{{ $permit->status === 'approved' ? 'success' : ($permit->status === 'rejected' ? 'danger' : 'warning') }}">{{ ucfirst($permit->status ?? 'pending') }}</span></dd>
                </dl>
            </div>
        </div>
    </div>
@endsection
