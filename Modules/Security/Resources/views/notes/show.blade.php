@extends('layouts.app')

@section('content')
    <div class="content-wrapper">
        <div class="d-flex justify-content-between align-items-center action-bar mb-4">
            <h4 class="mb-0">@lang('security::app.notes') — #{{ $note->id }}</h4>
            <div>
                <a href="{{ route('security.notes.edit', $note->id) }}" class="btn btn-secondary btn-sm mr-2">
                    <i class="fa fa-edit mr-1"></i> @lang('app.edit')
                </a>
                <a href="{{ route('security.notes.index') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="fa fa-arrow-left mr-1"></i> @lang('app.back')
                </a>
            </div>
        </div>

        <div class="card shadow-sm">
            <div class="card-body">
                <dl class="row">
                    <dt class="col-sm-3">Title</dt><dd class="col-sm-9">{{ $note->title }}</dd>
                    <dt class="col-sm-3">Incident Type</dt><dd class="col-sm-9">{{ $note->incident_type ?? '—' }}</dd>
                    <dt class="col-sm-3">Content</dt><dd class="col-sm-9">{{ $note->content }}</dd>
                    <dt class="col-sm-3">@lang('app.status')</dt>
                    <dd class="col-sm-9"><span class="badge badge-{{ $note->status === 'resolved' ? 'success' : 'warning' }}">{{ ucfirst($note->status) }}</span></dd>
                    <dt class="col-sm-3">@lang('app.date')</dt><dd class="col-sm-9">{{ $note->created_at?->format(company()->date_format ?? 'Y-m-d') }}</dd>
                </dl>
            </div>
        </div>
    </div>
@endsection
