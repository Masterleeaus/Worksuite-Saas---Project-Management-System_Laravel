@extends('layouts.app')

@section('content')
<div class="titan-talk-wrap d-flex" style="height: calc(100vh - 130px); overflow: hidden;">

    {{-- ===== SIDEBAR ===== --}}
    <div class="titan-talk-sidebar bg-white border-right" style="width:260px; min-width:260px; overflow-y:auto;">

        {{-- Search --}}
        <div class="p-3 border-bottom">
            <div class="input-group input-group-sm">
                <input type="text" id="tt-search-input" class="form-control" placeholder="Search messages, rooms…">
                <div class="input-group-append">
                    <span class="input-group-text"><i class="fa fa-search"></i></span>
                </div>
            </div>
        </div>

        {{-- Channels Section --}}
        <div class="p-3">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <small class="text-muted font-weight-bold text-uppercase">Channels</small>
                <button class="btn btn-sm btn-link p-0" data-toggle="modal" data-target="#createRoomModal" title="New Channel">
                    <i class="fa fa-plus"></i>
                </button>
            </div>

            @forelse($rooms->where('type', 'public') as $room)
                <a href="{{ route('titan.talk.room.show', $room->id) }}"
                   class="d-block px-2 py-1 rounded {{ isset($activeRoom) && $activeRoom->id == $room->id ? 'bg-primary text-white' : 'text-dark' }} mb-1 tt-room-link"
                   data-room-id="{{ $room->id }}">
                    <span class="tt-channel-prefix">#</span> {{ $room->name }}
                    <span class="tt-unread-badge badge badge-danger badge-pill float-right" data-room="{{ $room->id }}" style="display:none;"></span>
                </a>
            @empty
                <small class="text-muted">No public channels yet.</small>
            @endforelse
        </div>

        {{-- Private Channels --}}
        @if($rooms->whereIn('type', ['private', 'private_group'])->count())
        <div class="px-3 pb-2">
            <small class="text-muted font-weight-bold text-uppercase">Private</small>
            @foreach($rooms->whereIn('type', ['private', 'private_group']) as $room)
                <a href="{{ route('titan.talk.room.show', $room->id) }}"
                   class="d-block px-2 py-1 rounded {{ isset($activeRoom) && $activeRoom->id == $room->id ? 'bg-primary text-white' : 'text-dark' }} mb-1"
                   data-room-id="{{ $room->id }}">
                    <i class="fa fa-lock fa-xs"></i> {{ $room->name }}
                </a>
            @endforeach
        </div>
        @endif

        {{-- DMs --}}
        @if($rooms->where('type', 'dm')->count())
        <div class="px-3 pb-2">
            <small class="text-muted font-weight-bold text-uppercase">Direct Messages</small>
            @foreach($rooms->where('type', 'dm') as $room)
                <a href="{{ route('titan.talk.room.show', $room->id) }}"
                   class="d-block px-2 py-1 rounded {{ isset($activeRoom) && $activeRoom->id == $room->id ? 'bg-primary text-white' : 'text-dark' }} mb-1"
                   data-room-id="{{ $room->id }}">
                    <i class="fa fa-circle fa-xs text-success"></i> {{ $room->name }}
                </a>
            @endforeach
        </div>
        @endif

        {{-- Ops Rooms --}}
        @php
        $opsTypes = ['booking','service_job','site','worker_team','issue','dispatch'];
        $opsRooms = $rooms->whereIn('type', $opsTypes);
        @endphp
        @if($opsRooms->count())
        <div class="px-3 pb-2">
            <small class="text-muted font-weight-bold text-uppercase">Operations</small>
            @foreach($opsRooms as $room)
                <a href="{{ route('titan.talk.room.show', $room->id) }}"
                   class="d-block px-2 py-1 rounded {{ isset($activeRoom) && $activeRoom->id == $room->id ? 'bg-primary text-white' : 'text-dark' }} mb-1"
                   data-room-id="{{ $room->id }}">
                    <i class="fa fa-briefcase fa-xs"></i> {{ $room->name }}
                </a>
            @endforeach
        </div>
        @endif

        {{-- Saved / Pinned --}}
        <div class="px-3 pb-3 mt-2 border-top pt-2">
            <a href="{{ route('titan.talk.message.saved') }}" class="d-block text-muted px-2 py-1">
                <i class="fa fa-bookmark fa-sm"></i> Saved Items
            </a>
        </div>
    </div>

    {{-- ===== MAIN PANE ===== --}}
    <div class="titan-talk-main flex-grow-1 d-flex flex-column" style="overflow:hidden;">

        @if(isset($activeRoom))
            {{-- Room Header --}}
            <div class="titan-talk-header border-bottom p-3 d-flex justify-content-between align-items-center bg-white">
                <div>
                    <strong>
                        @if(in_array($activeRoom->type, ['private', 'private_group'])) <i class="fa fa-lock"></i> @else # @endif
                        {{ $activeRoom->name }}
                    </strong>
                    @if($activeRoom->description)
                        <small class="text-muted ml-2">{{ $activeRoom->description }}</small>
                    @endif
                </div>
                <div class="d-flex align-items-center">
                    <span class="text-muted mr-3"><i class="fa fa-users"></i> {{ $activeRoom->members->count() }} members</span>
                    <button class="btn btn-sm btn-outline-secondary mr-2" data-toggle="modal" data-target="#roomMembersModal">
                        <i class="fa fa-user-plus"></i> Members
                    </button>
                    <button class="btn btn-sm btn-outline-secondary" data-toggle="modal" data-target="#pinnedMessagesModal">
                        <i class="fa fa-thumbtack"></i> Pinned
                    </button>
                </div>
            </div>

            {{-- Messages Area --}}
            <div id="tt-messages-pane" class="flex-grow-1 p-3" style="overflow-y:auto;">
                @foreach($messages as $msg)
                    @include('titantalk::partials.message', ['message' => $msg])
                @endforeach
            </div>

            {{-- Message Input --}}
            <div class="titan-talk-input-bar border-top p-3 bg-white">
                <form id="tt-message-form" data-room="{{ $activeRoom->id }}">
                    @csrf
                    <div class="input-group">
                        <input type="text" id="tt-message-body" class="form-control"
                               placeholder="Message #{{ $activeRoom->name }}…"
                               autocomplete="off">
                        <div class="input-group-append">
                            <label class="btn btn-outline-secondary mb-0" title="Attach file">
                                <i class="fa fa-paperclip"></i>
                                <input type="file" id="tt-file-input" multiple style="display:none;">
                            </label>
                            <button type="submit" class="btn btn-primary">
                                <i class="fa fa-paper-plane"></i>
                            </button>
                        </div>
                    </div>
                    <div id="tt-file-preview" class="mt-2"></div>
                </form>
            </div>

        @else
            {{-- No room selected --}}
            <div class="flex-grow-1 d-flex align-items-center justify-content-center">
                <div class="text-center text-muted">
                    <i class="fa fa-comments fa-4x mb-3"></i>
                    <h5>Welcome to TitanTalk</h5>
                    <p>Select a channel or start a new conversation.</p>
                    <button class="btn btn-primary" data-toggle="modal" data-target="#createRoomModal">
                        <i class="fa fa-plus"></i> Create Channel
                    </button>
                </div>
            </div>
        @endif
    </div>

    {{-- ===== THREAD PANEL (hidden by default) ===== --}}
    <div id="tt-thread-panel" class="bg-white border-left" style="width:320px; min-width:320px; display:none; overflow-y:auto;">
        <div class="p-3 border-bottom d-flex justify-content-between">
            <strong>Thread</strong>
            <button class="btn btn-sm btn-link p-0" id="tt-close-thread"><i class="fa fa-times"></i></button>
        </div>
        <div id="tt-thread-messages" class="p-3"></div>
        <div class="p-3 border-top">
            <div class="input-group">
                <input type="text" id="tt-thread-reply-body" class="form-control" placeholder="Reply in thread…">
                <div class="input-group-append">
                    <button id="tt-thread-reply-btn" class="btn btn-primary" data-parent-id="">
                        <i class="fa fa-paper-plane"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ===== MODALS ===== --}}

{{-- Create Room Modal --}}
<div class="modal fade" id="createRoomModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Create Channel / Room</h5>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <form id="tt-create-room-form">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label>Name</label>
                        <input type="text" name="name" class="form-control" placeholder="e.g. general, dispatch-team" required>
                    </div>
                    <div class="form-group">
                        <label>Type</label>
                        <select name="type" class="form-control">
                            <option value="public">Public Channel</option>
                            <option value="private">Private Channel</option>
                            <option value="team">Team Room</option>
                            <option value="department">Department Room</option>
                            <option value="project">Project Room</option>
                            <option value="dispatch">Dispatch</option>
                            <option value="finance">Finance</option>
                            <option value="sales">Sales</option>
                            <option value="issue">Issue / Escalation</option>
                            <option value="private_group">Private Group</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Description (optional)</label>
                        <textarea name="description" class="form-control" rows="2"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Create</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Room Members Modal --}}
@if(isset($activeRoom))
<div class="modal fade" id="roomMembersModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Members — #{{ $activeRoom->name }}</h5>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body">
                <ul class="list-group list-group-flush mb-3">
                    @foreach($activeRoom->members as $member)
                    <li class="list-group-item d-flex justify-content-between align-items-center py-2">
                        <div>
                            @if($member->image)
                                <img src="{{ $member->image_url ?? asset('img/default-profile.png') }}" class="rounded-circle mr-2" style="width:28px;height:28px;object-fit:cover;">
                            @endif
                            {{ $member->name }}
                            <small class="text-muted ml-1">({{ $member->pivot->role }})</small>
                        </div>
                    </li>
                    @endforeach
                </ul>
                @if(in_array($activeRoom->getMemberRole(user()->id), ['owner', 'admin']))
                <hr>
                <p class="font-weight-bold">Invite Members</p>
                <form id="tt-invite-form" data-room="{{ $activeRoom->id }}">
                    @csrf
                    <div class="form-group">
                        <select name="user_ids[]" class="form-control select2" multiple="multiple" style="width:100%">
                            {{-- populated via JS / server --}}
                        </select>
                    </div>
                    <button type="submit" class="btn btn-sm btn-primary">Invite</button>
                </form>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- Pinned Messages Modal --}}
<div class="modal fade" id="pinnedMessagesModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Pinned Messages</h5>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body" id="tt-pinned-list">
                <div class="text-center text-muted py-4"><i class="fa fa-spin fa-spinner"></i> Loading…</div>
            </div>
        </div>
    </div>
</div>
@endif
@endsection

@section('scripts')
<script>
(function () {
    const CSRF = document.querySelector('meta[name="csrf-token"]')?.content ?? '';

    // ---- AJAX helper ----
    async function apiPost(url, data, isFormData = false) {
        const opts = {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
        };
        if (isFormData) {
            opts.body = data;
        } else {
            opts.headers['Content-Type'] = 'application/json';
            opts.body = JSON.stringify(data);
        }
        const res = await fetch(url, opts);
        return res.json();
    }

    async function apiGet(url) {
        const res = await fetch(url, { headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': CSRF } });
        return res.json();
    }

    // ---- Message form submit ----
    const msgForm = document.getElementById('tt-message-form');
    if (msgForm) {
        msgForm.addEventListener('submit', async function (e) {
            e.preventDefault();
            const roomId = this.dataset.room;
            const body   = document.getElementById('tt-message-body').value.trim();
            if (!body) return;

            const fd = new FormData();
            fd.append('body', body);
            fd.append('_token', CSRF);

            const files = document.getElementById('tt-file-input').files;
            for (let f of files) { fd.append('files[]', f); }

            const result = await apiPost(`/account/titan-talk/rooms/${roomId}/messages`, fd, true);
            if (result.status === 'success') {
                document.getElementById('tt-message-body').value = '';
                document.getElementById('tt-file-input').value = '';
                document.getElementById('tt-file-preview').innerHTML = '';
                appendMessage(result.message);
                scrollMessages();
            }
        });
    }

    // ---- Append new message to pane ----
    function appendMessage(msg) {
        const pane = document.getElementById('tt-messages-pane');
        if (!pane) return;
        const div = document.createElement('div');
        div.className = 'tt-message d-flex mb-3';
        div.dataset.msgId = msg.id;
        div.innerHTML = `
            <div class="flex-shrink-0 mr-3">
                <div class="rounded-circle bg-secondary d-flex align-items-center justify-content-center text-white" style="width:36px;height:36px;">
                    ${(msg.author?.name ?? '?')[0].toUpperCase()}
                </div>
            </div>
            <div class="flex-grow-1">
                <div>
                    <strong class="mr-2">${escHtml(msg.author?.name ?? 'Unknown')}</strong>
                    <small class="text-muted">${formatTime(msg.created_at)}</small>
                </div>
                <div class="tt-msg-body">${escHtml(msg.body)}</div>
                <div class="tt-msg-actions mt-1" style="font-size:0.78rem;">
                    <a href="#" class="text-muted mr-2 tt-react-btn" data-msg="${msg.id}">😊</a>
                    <a href="#" class="text-muted mr-2 tt-thread-btn" data-msg="${msg.id}">Reply</a>
                    <a href="#" class="text-muted tt-save-btn" data-msg="${msg.id}">Save</a>
                </div>
            </div>`;
        pane.appendChild(div);
    }

    function scrollMessages() {
        const pane = document.getElementById('tt-messages-pane');
        if (pane) pane.scrollTop = pane.scrollHeight;
    }

    scrollMessages();

    // ---- Create room form ----
    const createForm = document.getElementById('tt-create-room-form');
    if (createForm) {
        createForm.addEventListener('submit', async function (e) {
            e.preventDefault();
            const fd = new FormData(this);
            const data = Object.fromEntries(fd.entries());
            const result = await apiPost('/account/titan-talk/rooms', data);
            if (result.status === 'success') {
                window.location.href = `/account/titan-talk/room/${result.room.id}`;
            } else {
                alert('Error creating room.');
            }
        });
    }

    // ---- Thread panel ----
    document.addEventListener('click', async function (e) {
        const btn = e.target.closest('.tt-thread-btn');
        if (!btn) return;
        e.preventDefault();
        const msgId = btn.dataset.msg;
        const panel = document.getElementById('tt-thread-panel');
        panel.style.display = 'flex';
        panel.style.flexDirection = 'column';
        document.getElementById('tt-thread-reply-btn').dataset.parentId = msgId;
        const data = await apiGet(`/account/titan-talk/messages/${msgId}/thread`);
        const container = document.getElementById('tt-thread-messages');
        container.innerHTML = '';
        (data.replies ?? []).forEach(r => {
            const d = document.createElement('div');
            d.className = 'mb-2';
            d.innerHTML = `<strong>${escHtml(r.author?.name ?? '?')}</strong><br><span>${escHtml(r.body)}</span>`;
            container.appendChild(d);
        });
    });

    document.getElementById('tt-close-thread')?.addEventListener('click', function () {
        document.getElementById('tt-thread-panel').style.display = 'none';
    });

    document.getElementById('tt-thread-reply-btn')?.addEventListener('click', async function () {
        const parentId = this.dataset.parentId;
        const body = document.getElementById('tt-thread-reply-body').value.trim();
        if (!body || !parentId) return;
        await apiPost(`/account/titan-talk/messages/${parentId}/thread`, { body });
        document.getElementById('tt-thread-reply-body').value = '';
        // Reload thread
        const btn = document.querySelector(`.tt-thread-btn[data-msg="${parentId}"]`);
        if (btn) btn.click();
    });

    // ---- Save message ----
    document.addEventListener('click', async function (e) {
        const btn = e.target.closest('.tt-save-btn');
        if (!btn) return;
        e.preventDefault();
        await apiPost(`/account/titan-talk/messages/${btn.dataset.msg}/save`, {});
        btn.textContent = 'Saved ✓';
    });

    // ---- Pinned messages modal ----
    document.getElementById('pinnedMessagesModal')?.addEventListener('show.bs.modal', async function () {
        const roomId = {{ $activeRoom->id ?? 'null' }};
        if (!roomId) return;
        const list = document.getElementById('tt-pinned-list');
        const data = await apiGet(`/account/titan-talk/rooms/${roomId}/pinned`);
        list.innerHTML = '';
        (data.pins ?? []).forEach(pin => {
            const d = document.createElement('div');
            d.className = 'border-bottom pb-2 mb-2';
            d.innerHTML = `<strong>${escHtml(pin.message?.author?.name ?? '?')}</strong>: ${escHtml(pin.message?.body ?? '')}`;
            list.appendChild(d);
        });
        if (!data.pins?.length) list.innerHTML = '<p class="text-muted">No pinned messages.</p>';
    });

    // ---- Search ----
    const searchInput = document.getElementById('tt-search-input');
    if (searchInput) {
        let timer;
        searchInput.addEventListener('input', function () {
            clearTimeout(timer);
            timer = setTimeout(async () => {
                const q = this.value.trim();
                if (q.length < 2) return;
                const data = await apiGet(`/account/titan-talk/search?q=${encodeURIComponent(q)}`);
                console.log('TitanTalk search:', data);
                // TODO: render results in a dropdown
            }, 300);
        });
    }

    // ---- Unread counts ----
    async function loadUnreadCounts() {
        const data = await apiGet('/account/titan-talk/unread-counts');
        if (!data.counts) return;
        Object.entries(data.counts).forEach(([roomId, count]) => {
            const badge = document.querySelector(`.tt-unread-badge[data-room="${roomId}"]`);
            if (badge) {
                if (count > 0) {
                    badge.textContent = count;
                    badge.style.display = '';
                } else {
                    badge.style.display = 'none';
                }
            }
        });
    }
    loadUnreadCounts();
    setInterval(loadUnreadCounts, 30000);

    // ---- Helpers ----
    function escHtml(str) {
        return String(str ?? '').replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
    }

    function formatTime(iso) {
        if (!iso) return '';
        return new Date(iso).toLocaleTimeString([], {hour:'2-digit', minute:'2-digit'});
    }
})();
</script>
@endsection
