@php
    $myRole = isset($activeRoom) ? $activeRoom->getMemberRole(user()->id) : null;
    $isRoomAdmin = in_array($myRole, ['owner', 'admin']);
    $canPin = $isRoomAdmin;
@endphp
<div class="tt-message d-flex mb-3" data-msg-id="{{ $message->id }}">
    <div class="flex-shrink-0 mr-3">
        @php $initial = strtoupper(substr($message->author?->name ?? '?', 0, 1)); @endphp
        <div class="rounded-circle bg-secondary d-flex align-items-center justify-content-center text-white"
             style="width:36px;height:36px;font-size:14px;">
            {{ $initial }}
        </div>
    </div>
    <div class="flex-grow-1 position-relative">
        <div class="d-flex align-items-baseline flex-wrap">
            <strong class="mr-2">{{ $message->author?->name ?? 'Unknown' }}</strong>
            <small class="text-muted mr-2">{{ $message->created_at?->format('g:i A') }}</small>
            @if($message->is_edited)
                <small class="text-muted">(edited)</small>
            @endif
            @if($message->isPinned())
                <span class="badge badge-warning ml-2 badge-sm"><i class="fa fa-thumbtack"></i> Pinned</span>
            @endif
        </div>

        {{-- Message body with @mention highlighting --}}
        <div class="tt-msg-body mt-1">
            {!! preg_replace('/@([a-zA-Z0-9_.]+)/', '<span class="tt-highlight-mention">@$1</span>', e($message->body)) !!}
        </div>

        {{-- File attachments --}}
        @if($message->files->count())
        <div class="tt-msg-files mt-1">
            @foreach($message->files as $file)
                <a href="{{ Storage::disk($file->disk ?? config('filesystems.default', 'local'))->url($file->filename) }}"
                   class="badge badge-light border mr-1"
                   target="_blank" download="{{ $file->original_filename }}">
                    <i class="fa fa-paperclip"></i> {{ $file->original_filename }}
                </a>
            @endforeach
        </div>
        @endif

        {{-- Reactions --}}
        @if($message->reactions->count())
        <div class="tt-reactions mt-1">
            @foreach($message->reactionSummary() as $emoji => $count)
                <button class="btn btn-sm btn-outline-secondary py-0 px-1 mr-1 tt-react-toggle"
                        data-msg="{{ $message->id }}" data-emoji="{{ $emoji }}">
                    {{ $emoji }} <span class="badge badge-secondary">{{ $count }}</span>
                </button>
            @endforeach
        </div>
        @else
        <div class="tt-reactions mt-1"></div>
        @endif

        {{-- Thread reply count --}}
        @if($message->thread_reply_count > 0)
        <div class="mt-1 tt-thread-count">
            <a href="#" class="text-primary small tt-thread-btn" data-msg="{{ $message->id }}">
                <i class="fa fa-comments"></i>
                {{ $message->thread_reply_count }} {{ Str::plural('reply', $message->thread_reply_count) }}
            </a>
        </div>
        @endif

        {{-- Action buttons (shown on hover via CSS) --}}
        <div class="tt-msg-actions mt-1 d-none" style="font-size:0.78rem;">
            <a href="#" class="text-muted mr-2 tt-react-btn" data-msg="{{ $message->id }}">😊</a>
            <a href="#" class="text-muted mr-2 tt-thread-btn" data-msg="{{ $message->id }}">Reply</a>
            <a href="#" class="text-muted mr-2 tt-save-btn" data-msg="{{ $message->id }}">
                @if($message->isSavedBy(user()->id)) <span class="text-warning">★ Saved</span> @else Save @endif
            </a>
            @if($canPin)
                @if($message->isPinned())
                    <a href="#" class="text-warning mr-2 tt-unpin-btn" data-msg="{{ $message->id }}">Unpin</a>
                @else
                    <a href="#" class="text-muted mr-2 tt-pin-btn" data-msg="{{ $message->id }}">Pin</a>
                @endif
            @endif
        </div>
    </div>
</div>
