@php
    $pageTitle = $pageTitle ?? __('AI Chat Pro');
    $pageIcon  = $pageIcon  ?? 'ti ti-message-circle';
@endphp

@extends('layouts.app')

@push('styles')
<style>
/* ── TitanZero AIChatPro ─────────────────────────────── */
.tz-chat-wrap          { display: flex; height: calc(100vh - 130px); min-height: 400px; gap: 0; }
.tz-chat-sidebar       { width: 260px; min-width: 180px; border-right: 1px solid var(--bs-border-color, #dee2e6);
                         display: flex; flex-direction: column; overflow: hidden; }
.tz-chat-sidebar-head  { padding: .75rem 1rem; font-weight: 600; font-size: .85rem;
                         border-bottom: 1px solid var(--bs-border-color, #dee2e6); }
.tz-cat-select         { padding: .5rem 1rem; border-bottom: 1px solid var(--bs-border-color, #dee2e6); }
.tz-session-list       { flex: 1; overflow-y: auto; padding: .25rem 0; }
.tz-session-item       { padding: .5rem 1rem; cursor: pointer; font-size: .83rem; white-space: nowrap;
                         overflow: hidden; text-overflow: ellipsis; border-radius: 0;
                         border-left: 3px solid transparent; transition: background .15s; }
.tz-session-item:hover { background: rgba(0,0,0,.04); }
.tz-session-item.active{ border-left-color: var(--bs-primary, #0d6efd);
                         background: rgba(13,110,253,.08); font-weight: 600; }
.tz-session-item .pin  { float: right; color: var(--bs-warning, #ffc107); font-size: .8rem; }

.tz-chat-main          { flex: 1; display: flex; flex-direction: column; overflow: hidden; }
.tz-chat-messages      { flex: 1; overflow-y: auto; padding: 1rem 1.25rem; }
.tz-msg                { margin-bottom: 1rem; display: flex; flex-direction: column; }
.tz-msg.user           { align-items: flex-end; }
.tz-msg.assistant      { align-items: flex-start; }
.tz-bubble             { max-width: 76%; padding: .6rem .9rem; border-radius: 1rem; font-size: .88rem;
                         line-height: 1.5; white-space: pre-wrap; word-break: break-word; }
.tz-msg.user .tz-bubble { background: var(--bs-primary, #0d6efd); color: #fff;
                          border-bottom-right-radius: .25rem; }
.tz-msg.assistant .tz-bubble { background: var(--bs-light, #f8f9fa);
                                border: 1px solid var(--bs-border-color, #dee2e6);
                                border-bottom-left-radius: .25rem; color: var(--bs-body-color, #212529); }
.tz-citations          { font-size: .78rem; color: var(--bs-secondary, #6c757d); margin-top: .25rem; }
.tz-typing-indicator   { display: none; align-items: center; gap: .3rem; padding: .5rem 1.25rem; color: #888; font-size: .83rem; }
.tz-typing-indicator.show { display: flex; }
.tz-dot                { width: 6px; height: 6px; border-radius: 50%; background: #888;
                         animation: tzDot .9s infinite ease-in-out; }
.tz-dot:nth-child(2) { animation-delay: .15s; }
.tz-dot:nth-child(3) { animation-delay: .30s; }
@keyframes tzDot { 0%,60%,100%{transform:scale(.8);opacity:.5} 30%{transform:scale(1.2);opacity:1} }

.tz-chat-input-bar     { padding: .75rem 1rem; border-top: 1px solid var(--bs-border-color, #dee2e6); display: flex; gap: .5rem; }
.tz-chat-input-bar textarea { flex: 1; resize: none; border-radius: .5rem; min-height: 42px; max-height: 140px; }
.tz-chat-input-bar button   { align-self: flex-end; }
.tz-empty-hint         { text-align: center; color: #aaa; margin-top: 3rem; font-size: .92rem; }
</style>
@endpush

@section('content')
<div class="container-fluid py-2 px-3">

    {{-- Header --}}
    <div class="d-flex align-items-center justify-content-between mb-2">
        <div>
            <h5 class="mb-0"><i class="ti ti-message-circle me-1"></i>{{ __('AI Chat Pro') }}</h5>
            <small class="text-muted">
                {{ $category->name ?? __('AI Chat') }}
                @if(!empty($category->helps_with))
                    — {{ $category->helps_with }}
                @endif
            </small>
        </div>
    </div>

    <div class="card p-0 overflow-hidden">
        <div class="tz-chat-wrap">

            {{-- Sidebar --}}
            <div class="tz-chat-sidebar">
                <div class="tz-chat-sidebar-head">
                    <i class="ti ti-list me-1"></i>{{ __('Conversations') }}
                </div>

                {{-- Category picker --}}
                @if($categories->count() > 1)
                <div class="tz-cat-select">
                    <select id="tzCategorySelect" class="form-select form-select-sm">
                        @foreach($categories as $cat)
                            <option value="{{ $cat->slug }}"
                                {{ isset($category) && $category->id === $cat->id ? 'selected' : '' }}>
                                {{ $cat->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                @endif

                {{-- Session list --}}
                <div class="tz-session-list" id="tzSessionList">
                    @forelse($list as $sess)
                        <div class="tz-session-item {{ isset($chat) && $chat->id === $sess->id ? 'active' : '' }}"
                             data-session-id="{{ $sess->id }}"
                             title="{{ $sess->title }}">
                            @if($sess->is_pinned)<span class="pin">★</span>@endif
                            {{ Str::limit($sess->title, 30) }}
                        </div>
                    @empty
                        <div class="text-muted small p-3">{{ __('No conversations yet.') }}</div>
                    @endforelse
                </div>

                {{-- New chat button --}}
                @auth
                <div class="p-2 border-top">
                    <button class="btn btn-outline-primary btn-sm w-100" id="tzNewSessionBtn"
                            data-category-id="{{ $category->id ?? '' }}">
                        <i class="ti ti-plus me-1"></i>{{ __('New Chat') }}
                    </button>
                </div>
                @endauth
            </div>

            {{-- Main chat area --}}
            <div class="tz-chat-main">
                <div class="tz-chat-messages" id="tzMessages">
                    @if(!isset($chat))
                        <div class="tz-empty-hint">
                            <i class="ti ti-message-circle" style="font-size:2rem;"></i><br>
                            {{ __('Select a conversation or start a new one.') }}
                        </div>
                    @else
                        @foreach($chat->messages()->orderBy('created_at', 'asc')->get() as $msg)
                            @if($msg->output && !$msg->input)
                                {{-- Greeting / system init --}}
                                <div class="tz-msg assistant">
                                    <div class="tz-bubble">{{ $msg->output }}</div>
                                </div>
                            @endif
                            @if($msg->input)
                                <div class="tz-msg user">
                                    <div class="tz-bubble">{{ $msg->input }}</div>
                                </div>
                                @if($msg->output)
                                    <div class="tz-msg assistant">
                                        <div class="tz-bubble">{!! nl2br(e($msg->output)) !!}</div>
                                    </div>
                                @endif
                            @endif
                        @endforeach
                    @endif
                </div>

                <div class="tz-typing-indicator" id="tzTyping">
                    <span class="tz-dot"></span><span class="tz-dot"></span><span class="tz-dot"></span>
                    <span>{{ __('Titan is thinking…') }}</span>
                </div>

                <div class="tz-chat-input-bar">
                    <textarea id="tzInput" class="form-control"
                              placeholder="{{ __('Type a message…') }}"
                              rows="1"
                              {{ !isset($chat) ? 'disabled' : '' }}></textarea>
                    <button class="btn btn-primary" id="tzSendBtn"
                            {{ !isset($chat) ? 'disabled' : '' }}>
                        <i class="ti ti-send"></i>
                    </button>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
(function () {
    'use strict';

    const SEND_URL       = @json(route('titan.zero.ai-chat.send'));
    const NEW_SESS_URL   = @json(route('titan.zero.ai-chat.session.new'));
    const CHAT_BASE_URL  = @json(rtrim(route('titan.zero.ai-chat.index'), '/'));
    const CSRF           = document.querySelector('meta[name="csrf-token"]')?.content ?? '';
    let currentSessionId = {{ $chat?->id ?? 'null' }};

    const msgBox   = document.getElementById('tzMessages');
    const input    = document.getElementById('tzInput');
    const sendBtn  = document.getElementById('tzSendBtn');
    const typing   = document.getElementById('tzTyping');
    const sessList = document.getElementById('tzSessionList');
    const newBtn   = document.getElementById('tzNewSessionBtn');
    const catSel   = document.getElementById('tzCategorySelect');

    /* ── Scroll to bottom ─────────────────────────────────── */
    function scrollDown() {
        if (msgBox) msgBox.scrollTop = msgBox.scrollHeight;
    }
    scrollDown();

    /* ── Auto-resize textarea ─────────────────────────────── */
    if (input) {
        input.addEventListener('input', () => {
            input.style.height = 'auto';
            input.style.height = Math.min(input.scrollHeight, 140) + 'px';
        });
        input.addEventListener('keydown', (e) => {
            if (e.key === 'Enter' && !e.shiftKey) { e.preventDefault(); doSend(); }
        });
    }
    if (sendBtn) sendBtn.addEventListener('click', doSend);

    /* ── Send message ─────────────────────────────────────── */
    async function doSend() {
        const msg = (input?.value ?? '').trim();
        if (!msg || !currentSessionId) return;

        appendBubble('user', msg);
        input.value = '';
        input.style.height = 'auto';
        setInputEnabled(false);
        typing.classList.add('show');
        scrollDown();

        try {
            const res = await fetch(SEND_URL, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': CSRF,
                    'X-Requested-With': 'XMLHttpRequest',
                },
                body: JSON.stringify({ message: msg, session_id: currentSessionId }),
            });
            const data = await res.json();
            typing.classList.remove('show');
            if (data.ok) {
                appendBubble('assistant', data.reply ?? '', data.citations ?? []);
            } else {
                appendBubble('assistant', data.message ?? '{{ __("An error occurred.") }}');
            }
        } catch (err) {
            typing.classList.remove('show');
            appendBubble('assistant', '{{ __("Connection error. Please try again.") }}');
        }
        setInputEnabled(true);
        scrollDown();
    }

    function appendBubble(role, text, citations) {
        const wrap = document.createElement('div');
        wrap.className = 'tz-msg ' + role;
        const bubble = document.createElement('div');
        bubble.className = 'tz-bubble';
        bubble.textContent = text;
        wrap.appendChild(bubble);

        if (citations && citations.length) {
            const citeEl = document.createElement('div');
            citeEl.className = 'tz-citations';
            citeEl.textContent = '{{ __("Sources:") }} ' +
                citations.map(c => c.document_title || c.document_id).filter(Boolean).join(', ');
            wrap.appendChild(citeEl);
        }

        msgBox.appendChild(wrap);
    }

    function setInputEnabled(on) {
        if (input)   input.disabled   = !on;
        if (sendBtn) sendBtn.disabled = !on;
    }

    /* ── Session list click ───────────────────────────────── */
    if (sessList) {
        sessList.addEventListener('click', (e) => {
            const item = e.target.closest('.tz-session-item');
            if (!item) return;
            const sid = item.dataset.sessionId;
            window.location.href = CHAT_BASE_URL + '/{{ $category?->slug ?? "" }}?session=' + sid;
        });
    }

    /* ── New session ──────────────────────────────────────── */
    if (newBtn) {
        newBtn.addEventListener('click', async () => {
            const catId = newBtn.dataset.categoryId;
            if (!catId) return;
            newBtn.disabled = true;
            try {
                const res = await fetch(NEW_SESS_URL, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': CSRF,
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                    body: JSON.stringify({ category_id: catId }),
                });
                const data = await res.json();
                if (data.ok) {
                    window.location.href = CHAT_BASE_URL + '/{{ $category?->slug ?? "" }}';
                }
            } finally {
                newBtn.disabled = false;
            }
        });
    }

    /* ── Category switch ──────────────────────────────────── */
    if (catSel) {
        catSel.addEventListener('change', () => {
            window.location.href = CHAT_BASE_URL + '/' + catSel.value;
        });
    }

})();
</script>
@endpush
