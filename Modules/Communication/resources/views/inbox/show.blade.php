@extends('communication::layouts.master')

@section('communication_content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2 class="mb-0">
        <i class="bi bi-envelope-open me-2"></i>Message Detail
    </h2>
    <a href="{{ route('communications.index') }}" class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-arrow-left me-1"></i>Back to Inbox
    </a>
</div>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <div>
            @php
                $icons = ['email'=>'envelope','sms'=>'chat-dots','chat'=>'chat','push'=>'bell'];
                $icon = $icons[$communication->type] ?? 'question-circle';
                $badgeMap = ['queued'=>'secondary','sent'=>'info','delivered'=>'primary','failed'=>'danger','read'=>'success'];
                $badge = $badgeMap[$communication->status] ?? 'light';
            @endphp
            <span class="me-2">
                <i class="bi bi-{{ $icon }}"></i>
                <strong>{{ ucfirst($communication->type) }}</strong>
            </span>
            <span class="badge bg-{{ $badge }}">{{ ucfirst($communication->status) }}</span>
        </div>
        <small class="text-muted">
            {{ $communication->created_at->format('d M Y H:i') }}
        </small>
    </div>
    <div class="card-body">
        <dl class="row">
            <dt class="col-sm-2">To</dt>
            <dd class="col-sm-10">{{ $communication->to_address ?: '—' }}</dd>

            @if($communication->subject)
            <dt class="col-sm-2">Subject</dt>
            <dd class="col-sm-10">{{ $communication->subject }}</dd>
            @endif

            @if($communication->template)
            <dt class="col-sm-2">Template</dt>
            <dd class="col-sm-10">{{ $communication->template->name }}</dd>
            @endif

            @if($communication->sent_at)
            <dt class="col-sm-2">Sent At</dt>
            <dd class="col-sm-10">{{ $communication->sent_at->format('d M Y H:i') }}</dd>
            @endif

            @if($communication->read_at)
            <dt class="col-sm-2">Read At</dt>
            <dd class="col-sm-10">{{ $communication->read_at->format('d M Y H:i') }}</dd>
            @endif
        </dl>

        <hr>
        <div class="message-body" style="white-space:pre-wrap;">{{ $communication->body }}</div>
    </div>
</div>
@endsection
