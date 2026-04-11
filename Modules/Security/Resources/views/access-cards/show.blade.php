@extends('layouts.app')

@section('content')
    <div class="content-wrapper">
        <div class="d-flex justify-content-between align-items-center action-bar mb-4">
            <h4 class="mb-0">@lang('security::app.access_cards') — #{{ $card->id }}</h4>
            <div>
                <a href="{{ route('security.access-cards.edit', $card->id) }}" class="btn btn-secondary btn-sm mr-2">
                    <i class="fa fa-edit mr-1"></i> @lang('app.edit')
                </a>
                <a href="{{ route('security.access-cards.index') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="fa fa-arrow-left mr-1"></i> @lang('app.back')
                </a>
            </div>
        </div>

        <div class="card shadow-sm">
            <div class="card-body">
                <dl class="row">
                    <dt class="col-sm-3">@lang('app.user')</dt>
                    <dd class="col-sm-9">{{ $card->user->name ?? '—' }}</dd>

                    <dt class="col-sm-3">Card Number</dt>
                    <dd class="col-sm-9">{{ $card->card_number }}</dd>

                    <dt class="col-sm-3">Card Type</dt>
                    <dd class="col-sm-9">{{ $card->card_type ?? '—' }}</dd>

                    <dt class="col-sm-3">@lang('app.status')</dt>
                    <dd class="col-sm-9"><span class="badge badge-{{ $card->status === 'active' ? 'success' : 'secondary' }}">{{ ucfirst($card->status) }}</span></dd>

                    <dt class="col-sm-3">Issued Date</dt>
                    <dd class="col-sm-9">{{ $card->issued_date?->format(company()->date_format ?? 'Y-m-d') }}</dd>

                    <dt class="col-sm-3">Expiry Date</dt>
                    <dd class="col-sm-9">{{ $card->expiry_date?->format(company()->date_format ?? 'Y-m-d') }}</dd>

                    <dt class="col-sm-3">Notes</dt>
                    <dd class="col-sm-9">{{ $card->notes ?? '—' }}</dd>
                </dl>
            </div>
        </div>
    </div>
@endsection
