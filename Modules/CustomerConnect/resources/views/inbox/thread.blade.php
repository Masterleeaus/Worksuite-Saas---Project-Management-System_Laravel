@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex align-items-center justify-content-between mb-3">
        <div>
            <h4 class="mb-0">
                {{ $thread->contact->display_name ?? 'Unknown' }}
                <span class="badge bg-secondary ms-2">{{ strtoupper($thread->channel) }}</span>
                <span class="badge bg-{{ $thread->status==='closed' ? 'dark' : 'success' }} ms-1">{{ ucfirst($thread->status) }}</span>
            </h4>

            <div class="text-muted small">
                {{ $thread->contact->email ?? $thread->contact->phone_e164 ?? $thread->contact->telegram_chat_id ?? '' }}
            </div>

            @php
                $canUseTitanZero = function_exists('user') && user() && (method_exists(user(), 'can') ? user()->can('titanzero.use') : false);
            @endphp

            @if($canUseTitanZero)
                <div class="mt-2">
                    @if (\Illuminate\Support\Facades\View::exists('titanzero::partials.ask_button') && \Illuminate\Support\Facades\Route::has('titan.zero.heroes.index'))
                        @include('titanzero::partials.ask_button', [
                            'heroKey' => 'comms',
                            'intent' => 'draft_scope',
                            'record_type' => 'customerconnect_thread',
                            'record_id' => $thread->id,
                            'fields' => [
                                'channel' => $thread->channel,
                                'status' => $thread->status,
                                'last_message_preview' => $thread->last_message_preview,
                            ],
                            'return_url' => url()->current(),
                        ])
                    @elseif(\Illuminate\Support\Facades\Route::has('titan.zero.index'))
                        <a class="btn btn-outline-primary btn-sm" href="{{ route('titan.zero.index') }}">Ask Titan Zero</a>
                    @endif
                </div>
            @endif
        </div>

        <div class="d-flex gap-2">
            <a href="{{ route('customerconnect.inbox.index') }}" class="btn btn-light">Back</a>
            <a href="{{ route('customerconnect.inbox.threads.events', $thread->id) }}" class="btn btn-outline-secondary">Events</a>
            <form method="post" action="{{ route('customerconnect.inbox.threads.close', $thread->id) }}">
                @csrf
                <button class="btn btn-outline-secondary">{{ $thread->status==='closed' ? 'Reopen' : 'Close' }}</button>
            </form>
        </div>
    </div>

    <div class="row g-3">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-body" style="max-height: 65vh; overflow:auto;">
                    @forelse($thread->messages as $msg)
                        <div class="mb-3">
                            <div class="d-flex align-items-center justify-content-between">
                                <strong>{{ $msg->direction === 'inbound' ? 'Customer' : 'You' }}</strong>
                                <span class="text-muted small">
                                    {{ $msg->created_at?->format('Y-m-d H:i') }} · {{ strtoupper($msg->status) }}
                                </span>
                            </div>
                            <div class="border rounded p-2 mt-1">
                                {!! nl2br(e($msg->body_text ?? '')) !!}

                                @if(is_array($msg->meta) && !empty($msg->meta['media']))
                                    <div class="mt-2">
                                        <div class="small text-muted">Attachments:</div>
                                        <ul class="mb-0">
                                            @foreach($msg->meta['media'] as $m)
                                                @if(!empty($m['url']))
                                                    <li><a href="{{ $m['url'] }}" target="_blank" rel="noopener">{{ $m['content_type'] ?? 'file' }}</a></li>
                                                @elseif(!empty($m['file_id']))
                                                    <li><span class="text-muted">{{ $m['type'] ?? 'file' }}</span> · {{ $m['file_name'] ?? $m['file_id'] }}</li>
                                                @endif
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="text-muted">No messages yet.</div>
                    @endforelse
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-body">
                    <form method="post" action="{{ route('customerconnect.inbox.threads.send', $thread->id) }}">
                        @csrf
                        <div class="mb-2">
                            <label class="form-label">Reply</label>
                            <textarea name="body" class="form-control" rows="4" required></textarea>
                        </div>
                        <button class="btn btn-primary">Send</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card">
                <div class="card-body">
                    <h6>Assignment</h6>
                    <form method="post" action="{{ route('customerconnect.inbox.threads.assign', $thread->id) }}">
                        @csrf
                        <div class="d-flex gap-2">
                            <select class="form-select" name="assigned_to_user_id">
                                <option value="">Unassigned</option>
                                @foreach(($assignees ?? []) as $u)
                                    <option value="{{ $u['id'] }}" @selected(($thread->assigned_to_user_id ?? null)==$u['id'])>{{ $u['name'] }}</option>
                                @endforeach
                            </select>
                            <button class="btn btn-outline-secondary">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
