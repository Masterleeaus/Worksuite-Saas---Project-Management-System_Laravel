@extends('layouts.app')

@section('content')
<div class="content-wrapper">

    {{-- ── Header ──────────────────────────────────────────────────────── --}}
    <div class="row mb-3">
        <div class="col-12 d-flex justify-content-between align-items-center flex-wrap gap-2">
            <h4 class="mb-0">
                <a href="{{ route('titanreach.inbox.index') }}" class="text-muted me-2">← Inbox</a>
                {{ $conversation->contact->name ?? 'Unknown' }}
                <span class="badge badge-info ms-1">{{ ucfirst($conversation->channel) }}</span>
            </h4>
            <div class="d-flex gap-2">
                {{-- Assign --}}
                <form method="POST" action="{{ route('titanreach.inbox.assign', $conversation->id) }}" class="d-flex gap-1">
                    @csrf
                    <input type="number" name="user_id" class="form-control form-control-sm" placeholder="User ID" style="width:90px" required>
                    <button class="btn btn-sm btn-outline-secondary">Assign</button>
                </form>

                {{-- Status --}}
                <form method="POST" action="{{ route('titanreach.inbox.status', $conversation->id) }}" class="d-flex gap-1">
                    @csrf
                    <select name="status" class="form-control form-control-sm w-auto">
                        @foreach(['open','pending','closed','spam'] as $s)
                            <option value="{{ $s }}" @selected($conversation->status === $s)>{{ ucfirst($s) }}</option>
                        @endforeach
                    </select>
                    <button class="btn btn-sm btn-outline-secondary">Update</button>
                </form>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- ── Message thread ─────────────────────────────────────────────── --}}
    <div class="card mb-3">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span>Conversation thread</span>
            <button class="btn btn-sm btn-outline-primary" id="suggestBtn"
                    data-url="{{ route('titanreach.inbox.suggest-reply', $conversation->id) }}">
                ✨ AI Suggest Reply
            </button>
        </div>
        <div class="card-body" id="messageThread"
             style="max-height:480px;overflow-y:auto;display:flex;flex-direction:column;gap:8px;padding:16px">
            @forelse($conversation->messages as $msg)
                <div class="d-flex {{ $msg->direction === 'outbound' ? 'justify-content-end' : 'justify-content-start' }}">
                    <div class="p-2 rounded" style="
                        max-width:72%;
                        background:{{ $msg->direction === 'outbound' ? '#0d6efd' : '#f3f4f6' }};
                        color:{{ $msg->direction === 'outbound' ? '#fff' : '#1a1a1a' }};
                        font-size:14px;
                    ">
                        @if($msg->media_url)
                            <div class="mb-1">
                                <a href="{{ $msg->media_url }}" target="_blank">📎 Media</a>
                            </div>
                        @endif
                        {{ $msg->content }}
                        <div style="font-size:10px;opacity:0.7;margin-top:3px;text-align:right">
                            {{ $msg->sent_at?->format('d M H:i') ?? $msg->created_at->format('d M H:i') }}
                        </div>
                    </div>
                </div>
            @empty
                <p class="text-center text-muted">No messages yet.</p>
            @endforelse
        </div>
    </div>

    {{-- ── AI suggestion banner (hidden until used) ───────────────────── --}}
    <div id="suggestionBanner" class="alert alert-info d-none mb-3" style="font-size:13px">
        <strong>AI suggestion:</strong>
        <span id="suggestionText"></span>
        <button class="btn btn-sm btn-outline-primary ms-2" id="useSuggestion">Use</button>
    </div>

    {{-- ── Contact info sidebar ────────────────────────────────────────── --}}
    <div class="row">
        <div class="col-md-8">
            {{-- Reply form placeholder — actual sending happens per-channel via dedicated routes --}}
            <div class="card">
                <div class="card-header">Reply</div>
                <div class="card-body">
                    <p class="text-muted small mb-2">
                        To send a message, use the
                        @if($conversation->channel === 'sms')
                            <a href="{{ route('titanreach.sms.index') }}">SMS panel</a>
                        @elseif($conversation->channel === 'whatsapp')
                            <a href="{{ route('titanreach.whatsapp.index') }}">WhatsApp panel</a>
                        @elseif($conversation->channel === 'telegram')
                            <a href="{{ route('titanreach.telegram.index') }}">Telegram panel</a>
                        @else
                            the appropriate channel panel
                        @endif
                        and address the reply to <strong>{{ $conversation->contact->phone ?? $conversation->contact->name ?? 'this contact' }}</strong>.
                    </p>
                    <textarea id="replyTextArea" class="form-control mb-2" rows="3"
                              placeholder="Compose your reply…"></textarea>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header">Contact</div>
                <div class="card-body" style="font-size:14px">
                    @if($conversation->contact)
                        <p><strong>{{ $conversation->contact->name }}</strong></p>
                        @if($conversation->contact->phone)
                            <p>📞 {{ $conversation->contact->phone }}</p>
                        @endif
                        @if($conversation->contact->email)
                            <p>✉ {{ $conversation->contact->email }}</p>
                        @endif
                        @if($conversation->contact->whatsapp_number)
                            <p>💬 {{ $conversation->contact->whatsapp_number }}</p>
                        @endif
                        <a href="{{ route('titanreach.contacts.edit', $conversation->contact->id) }}"
                           class="btn btn-sm btn-outline-secondary mt-2">Edit Contact</a>
                    @else
                        <p class="text-muted">No contact attached.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

</div>

<script>
document.getElementById('suggestBtn')?.addEventListener('click', function () {
    const url  = this.dataset.url;
    const btn  = this;
    btn.disabled = true;
    btn.textContent = '…';

    fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' } })
        .then(r => r.json())
        .then(data => {
            const text    = data.suggestion ?? '';
            const banner  = document.getElementById('suggestionBanner');
            const textEl  = document.getElementById('suggestionText');
            textEl.textContent = text;
            banner.classList.remove('d-none');

            document.getElementById('useSuggestion')?.addEventListener('click', function () {
                document.getElementById('replyTextArea').value = text;
                banner.classList.add('d-none');
            });
        })
        .catch(() => {})
        .finally(() => {
            btn.disabled = false;
            btn.textContent = '✨ AI Suggest Reply';
        });
});

// Scroll thread to bottom on load
const thread = document.getElementById('messageThread');
if (thread) thread.scrollTop = thread.scrollHeight;
</script>
@endsection
