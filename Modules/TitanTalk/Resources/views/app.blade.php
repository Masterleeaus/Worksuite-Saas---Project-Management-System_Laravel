@extends('layouts.app')

@section('content')
<style>
.tt-room-link:hover { background: #f0f4ff !important; color: #333 !important; }
.tt-room-link.active { background: #4f46e5 !important; color: #fff !important; }
.tt-room-link.active .tt-unread-badge { background: #fff !important; color: #4f46e5 !important; }
.tt-message:hover .tt-msg-actions { display: block !important; }
.tt-emoji-picker { position:absolute; background:#fff; border:1px solid #ddd; border-radius:6px; padding:6px; z-index:9000; box-shadow:0 4px 12px rgba(0,0,0,.12); }
.tt-emoji-picker span { cursor:pointer; font-size:1.3rem; padding:2px; display:inline-block; }
.tt-emoji-picker span:hover { background:#f0f4ff; border-radius:4px; }
.tt-msg-actions { font-size:0.78rem; }
.tt-highlight-mention { background:#fff3cd; border-radius:3px; padding:0 2px; font-weight:600; }
#tt-thread-panel { flex-direction: column; }
</style>

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
                @include('titantalk::partials.room_link', ['room' => $room, 'prefix' => '#'])
            @empty
                <small class="text-muted">No public channels yet.</small>
            @endforelse
        </div>

        {{-- Private Channels --}}
        @if($rooms->whereIn('type', ['private', 'private_group'])->count())
        <div class="px-3 pb-2">
            <small class="text-muted font-weight-bold text-uppercase">Private</small>
            @foreach($rooms->whereIn('type', ['private', 'private_group']) as $room)
                @include('titantalk::partials.room_link', ['room' => $room, 'prefix' => '<i class="fa fa-lock fa-xs"></i>'])
            @endforeach
        </div>
        @endif

        {{-- DMs --}}
        <div class="px-3 pb-2">
            <div class="d-flex justify-content-between align-items-center mb-1">
                <small class="text-muted font-weight-bold text-uppercase">Direct Messages</small>
                <button class="btn btn-sm btn-link p-0" data-toggle="modal" data-target="#newDmModal" title="New DM">
                    <i class="fa fa-plus"></i>
                </button>
            </div>
            @foreach($rooms->where('type', 'dm') as $room)
                @include('titantalk::partials.room_link', ['room' => $room, 'prefix' => '<i class="fa fa-circle fa-xs text-success"></i>'])
            @endforeach
            @if(!$rooms->where('type', 'dm')->count())
                <small class="text-muted">No direct messages.</small>
            @endif
        </div>

        {{-- Ops Rooms --}}
        @php
        $opsTypes = ['booking','service_job','site','worker_team','issue','dispatch'];
        $opsRooms = $rooms->whereIn('type', $opsTypes);
        @endphp
        @if($opsRooms->count())
        <div class="px-3 pb-2">
            <small class="text-muted font-weight-bold text-uppercase">Operations</small>
            @foreach($opsRooms as $room)
                @include('titantalk::partials.room_link', ['room' => $room, 'prefix' => '<i class="fa fa-briefcase fa-xs"></i>'])
            @endforeach
        </div>
        @endif

        {{-- Other rooms not in above sets --}}
        @php
        $allShownTypes = array_merge(['public','private','private_group','dm'], $opsTypes);
        $otherRooms = $rooms->whereNotIn('type', $allShownTypes);
        @endphp
        @if($otherRooms->count())
        <div class="px-3 pb-2">
            <small class="text-muted font-weight-bold text-uppercase">Other Rooms</small>
            @foreach($otherRooms as $room)
                @include('titantalk::partials.room_link', ['room' => $room, 'prefix' => '#'])
            @endforeach
        </div>
        @endif

        {{-- Mentions, Saved / Pinned --}}
        <div class="px-3 pb-3 mt-2 border-top pt-2">
            <a href="#" id="tt-mentions-link" class="d-block text-muted px-2 py-1 rounded tt-room-link">
                <i class="fa fa-at fa-sm"></i> Mentions
                <span id="tt-mentions-badge" class="badge badge-danger badge-pill float-right" style="display:none;"></span>
            </a>
            <a href="{{ route('titan.talk.message.saved') }}" class="d-block text-muted px-2 py-1 rounded tt-room-link">
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
                        @if(in_array($activeRoom->type, ['private', 'private_group'])) <i class="fa fa-lock"></i> @elseif($activeRoom->type === 'dm') <i class="fa fa-user"></i> @else # @endif
                        {{ $activeRoom->name }}
                    </strong>
                    @if($activeRoom->description)
                        <small class="text-muted ml-2">{{ $activeRoom->description }}</small>
                    @endif
                </div>
                <div class="d-flex align-items-center">
                    <span class="text-muted mr-3"><i class="fa fa-users"></i> {{ $activeRoom->members->count() }} members</span>
                    @if($activeRoom->type !== 'dm')
                    <button class="btn btn-sm btn-outline-secondary mr-2" data-toggle="modal" data-target="#roomMembersModal">
                        <i class="fa fa-user-plus"></i> Members
                    </button>
                    @endif
                    <button class="btn btn-sm btn-outline-secondary mr-2" data-toggle="modal" data-target="#pinnedMessagesModal">
                        <i class="fa fa-thumbtack"></i> Pinned
                    </button>
                    @php
                    $myMembership = $activeRoom->roomMembers->where('user_id', user()->id)->first();
                    @endphp
                    @if($myMembership)
                    <button class="btn btn-sm btn-outline-secondary" id="tt-mute-btn"
                            data-room="{{ $activeRoom->id }}"
                            data-muted="{{ $myMembership->is_muted ? '1' : '0' }}"
                            title="{{ $myMembership->is_muted ? 'Unmute room' : 'Mute room' }}">
                        <i class="fa fa-{{ $myMembership->is_muted ? 'bell-slash' : 'bell' }}"></i>
                    </button>
                    @endif
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
                    <div id="tt-file-preview" class="mt-2 small text-muted"></div>
                </form>
            </div>

        @else
            {{-- No room selected --}}
            <div class="flex-grow-1 d-flex align-items-center justify-content-center">
                <div class="text-center text-muted">
                    <i class="fa fa-comments fa-4x mb-3"></i>
                    <h5>Welcome to TitanTalk</h5>
                    <p>Select a channel or start a new conversation.</p>
                    <button class="btn btn-primary mr-2" data-toggle="modal" data-target="#createRoomModal">
                        <i class="fa fa-plus"></i> Create Channel
                    </button>
                    <button class="btn btn-outline-primary" data-toggle="modal" data-target="#newDmModal">
                        <i class="fa fa-user"></i> New DM
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
        <div id="tt-thread-messages" class="p-3 flex-grow-1" style="overflow-y:auto;"></div>
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

{{-- New DM Modal --}}
<div class="modal fade" id="newDmModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">New Direct Message</h5>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>Search for a person</label>
                    <input type="text" id="tt-dm-search" class="form-control" placeholder="Type a name…">
                    <div id="tt-dm-results" class="mt-2"></div>
                </div>
            </div>
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
                            {{ $member->name }}
                            <small class="text-muted ml-1">({{ $member->pivot->role }})</small>
                        </div>
                        @if(in_array($activeRoom->getMemberRole(user()->id), ['owner', 'admin']) && $member->pivot->role !== 'owner' && $member->id !== user()->id)
                        <button class="btn btn-sm btn-outline-danger tt-remove-member-btn py-0"
                                data-room="{{ $activeRoom->id }}" data-user="{{ $member->id }}">
                            <i class="fa fa-times"></i>
                        </button>
                        @endif
                    </li>
                    @endforeach
                </ul>
                @if(in_array($activeRoom->getMemberRole(user()->id), ['owner', 'admin']))
                <hr>
                <p class="font-weight-bold">Invite Members</p>
                <form id="tt-invite-form" data-room="{{ $activeRoom->id }}">
                    @csrf
                    <div class="form-group">
                        <select name="user_ids[]" id="tt-invite-select" class="form-control" multiple="multiple" style="width:100%">
                            {{-- populated by JS search --}}
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

{{-- Emoji Picker Popover --}}
<div id="tt-emoji-picker" class="tt-emoji-picker" style="display:none;" data-msg-id="">
    @foreach(['👍','👎','❤️','😂','😮','😢','🎉','🔥','✅','❌','👀','🙏'] as $emoji)
        <span class="tt-emoji-pick" data-emoji="{{ $emoji }}">{{ $emoji }}</span>
    @endforeach
</div>

@endsection

@section('scripts')
<script>
(function () {
    const CSRF     = document.querySelector('meta[name="csrf-token"]')?.content ?? '';
    const ROOM_ID  = @json($activeRoom->id ?? null);
    const USER_ID  = @json(user()->id ?? null);
    const IS_ADMIN = @json(isset($activeRoom) && in_array($activeRoom->getMemberRole(user()->id), ['owner', 'admin']));

    // ---- Real-time: subscribe to active room's private channel via Pusher/Echo ----
    @if(pusher_settings()->status == 1)
    if (ROOM_ID && typeof window.Echo !== 'undefined') {
        window.Echo.private('titan-talk.room.' + ROOM_ID)
            .listen('.message.sent', function (data) {
                if (data.user_id !== USER_ID) {
                    appendMessage(data);
                    scrollMessages();
                }
            });

        window.Echo.private('titan-talk.user.' + USER_ID)
            .listen('.mention.received', function (data) {
                // Show @-badge on sidebar mentions link
                const badge = document.getElementById('tt-mentions-badge');
                if (badge) {
                    const cur = parseInt(badge.textContent || '0', 10);
                    badge.textContent = cur + 1;
                    badge.style.display = '';
                }
                // Also show unread badge on the source room's sidebar link
                if (data.room_id && data.room_id !== ROOM_ID) {
                    const badge2 = document.querySelector('.tt-unread-badge[data-room="' + data.room_id + '"]');
                    if (badge2) { badge2.textContent = '@'; badge2.style.display = ''; }
                }
            });
    }
    @endif

    // ---- AJAX helpers ----
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

    async function apiDelete(url) {
        const res = await fetch(url, {
            method: 'DELETE',
            headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
        });
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

    // ---- File preview ----
    document.getElementById('tt-file-input')?.addEventListener('change', function () {
        const preview = document.getElementById('tt-file-preview');
        preview.innerHTML = Array.from(this.files).map(f => `<span class="badge badge-light border mr-1"><i class="fa fa-paperclip"></i> ${escHtml(f.name)}</span>`).join('');
    });

    // ---- Append new message to pane ----
    function appendMessage(msg) {
        const pane = document.getElementById('tt-messages-pane');
        if (!pane) return;
        const div = document.createElement('div');
        div.className = 'tt-message d-flex mb-3';
        div.dataset.msgId = msg.id;
        const authorName = msg.author?.name ?? 'Unknown';
        const initial    = authorName[0].toUpperCase();
        const isOwner    = (msg.user_id === USER_ID) || IS_ADMIN;
        div.innerHTML = `
            <div class="flex-shrink-0 mr-3">
                <div class="rounded-circle bg-secondary d-flex align-items-center justify-content-center text-white" style="width:36px;height:36px;font-size:14px;">${escHtml(initial)}</div>
            </div>
            <div class="flex-grow-1 position-relative">
                <div class="d-flex align-items-baseline">
                    <strong class="mr-2">${escHtml(authorName)}</strong>
                    <small class="text-muted">${formatTime(msg.created_at)}</small>
                </div>
                <div class="tt-msg-body mt-1">${renderMentions(escHtml(msg.body))}</div>
                <div class="tt-reactions mt-1"></div>
                <div class="tt-msg-actions mt-1 d-none" style="font-size:0.78rem;">
                    <a href="#" class="text-muted mr-2 tt-react-btn" data-msg="${msg.id}">😊</a>
                    <a href="#" class="text-muted mr-2 tt-thread-btn" data-msg="${msg.id}">Reply</a>
                    <a href="#" class="text-muted mr-2 tt-save-btn" data-msg="${msg.id}">Save</a>
                    ${isOwner ? `<a href="#" class="text-muted mr-2 tt-pin-btn" data-msg="${msg.id}">Pin</a>` : ''}
                </div>
            </div>`;
        pane.appendChild(div);
    }

    // Highlight @mentions in message body
    function renderMentions(html) {
        return html.replace(/@([a-zA-Z0-9_.]+)/g, '<span class="tt-highlight-mention">@$1</span>');
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
            const fd   = new FormData(this);
            const data = Object.fromEntries(fd.entries());
            const result = await apiPost('/account/titan-talk/rooms', data);
            if (result.status === 'success') {
                window.location.href = `/account/titan-talk/room/${result.room.id}`;
            } else {
                alert(result.message ?? 'Error creating room.');
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
        document.getElementById('tt-thread-reply-btn').dataset.parentId = msgId;
        const data = await apiGet(`/account/titan-talk/messages/${msgId}/thread`);
        const container = document.getElementById('tt-thread-messages');
        container.innerHTML = '';
        // Parent
        if (data.parent) {
            const pd = document.createElement('div');
            pd.className = 'border-bottom pb-2 mb-3';
            pd.innerHTML = `<strong>${escHtml(data.parent.author?.name ?? '?')}</strong> <small class="text-muted">${formatTime(data.parent.created_at)}</small><div class="mt-1">${renderMentions(escHtml(data.parent.body))}</div>`;
            container.appendChild(pd);
        }
        const replyCount = data.replies?.length ?? 0;
        if (replyCount) {
            const h = document.createElement('small');
            h.className = 'text-muted d-block mb-2';
            h.textContent = `${replyCount} ${replyCount === 1 ? 'reply' : 'replies'}`;
            container.appendChild(h);
        }
        (data.replies ?? []).forEach(r => {
            const d = document.createElement('div');
            d.className = 'mb-2 d-flex';
            d.innerHTML = `
                <div class="rounded-circle bg-secondary d-flex align-items-center justify-content-center text-white mr-2 flex-shrink-0" style="width:28px;height:28px;font-size:11px;">${escHtml((r.author?.name ?? '?')[0].toUpperCase())}</div>
                <div><strong class="small">${escHtml(r.author?.name ?? '?')}</strong> <small class="text-muted">${formatTime(r.created_at)}</small><div>${renderMentions(escHtml(r.body))}</div></div>`;
            container.appendChild(d);
        });
        if (!replyCount) {
            container.innerHTML += '<p class="text-muted small">No replies yet.</p>';
        }
    });

    document.getElementById('tt-close-thread')?.addEventListener('click', function () {
        document.getElementById('tt-thread-panel').style.display = 'none';
    });

    document.getElementById('tt-thread-reply-btn')?.addEventListener('click', async function () {
        const parentId = this.dataset.parentId;
        const body = document.getElementById('tt-thread-reply-body').value.trim();
        if (!body || !parentId) return;
        const result = await apiPost(`/account/titan-talk/messages/${parentId}/thread`, { body });
        if (result.status === 'success') {
            document.getElementById('tt-thread-reply-body').value = '';
            // Reload thread by clicking reply button again (will show accurate count)
            const btn = document.querySelector(`.tt-thread-btn[data-msg="${parentId}"]`);
            if (btn) btn.click();
            // Update reply count badge using server-returned count
            const newCount = result.reply_count ?? 1;
            const msgDiv = document.querySelector(`.tt-message[data-msg-id="${parentId}"]`);
            if (msgDiv) {
                let countEl = msgDiv.querySelector('.tt-thread-count');
                if (!countEl) {
                    countEl = document.createElement('div');
                    countEl.className = 'mt-1 tt-thread-count';
                    msgDiv.querySelector('.flex-grow-1').appendChild(countEl);
                }
                const plural = newCount === 1 ? 'reply' : 'replies';
                countEl.innerHTML = `<a href="#" class="text-primary small tt-thread-btn" data-msg="${parentId}"><i class="fa fa-comments"></i> ${newCount} ${plural}</a>`;
            }
        }
    });

    // ---- Save message ----
    document.addEventListener('click', async function (e) {
        const btn = e.target.closest('.tt-save-btn');
        if (!btn) return;
        e.preventDefault();
        const result = await apiPost(`/account/titan-talk/messages/${btn.dataset.msg}/save`, {});
        if (result.status === 'success') {
            btn.textContent = '★ Saved';
            btn.classList.add('text-warning');
        }
    });

    // ---- Pin message ----
    document.addEventListener('click', async function (e) {
        const btn = e.target.closest('.tt-pin-btn');
        if (!btn) return;
        e.preventDefault();
        const msgId = btn.dataset.msg;
        const result = await apiPost(`/account/titan-talk/messages/${msgId}/pin`, {});
        if (result.status === 'success') {
            btn.textContent = '📌 Pinned';
            btn.classList.add('text-warning');
            // Show pinned badge on message
            const msgDiv = document.querySelector(`.tt-message[data-msg-id="${msgId}"]`);
            if (msgDiv) {
                const header = msgDiv.querySelector('.d-flex.align-items-baseline');
                if (header && !header.querySelector('.tt-pin-badge')) {
                    const badge = document.createElement('span');
                    badge.className = 'badge badge-warning ml-2 badge-sm tt-pin-badge';
                    badge.innerHTML = '<i class="fa fa-thumbtack"></i> Pinned';
                    header.appendChild(badge);
                }
            }
        }
    });

    // ---- Emoji picker (add new reaction) ----
    const emojiPicker = document.getElementById('tt-emoji-picker');

    document.addEventListener('click', function (e) {
        const btn = e.target.closest('.tt-react-btn');
        if (btn) {
            e.preventDefault();
            emojiPicker.dataset.msgId = btn.dataset.msg;
            const rect = btn.getBoundingClientRect();
            emojiPicker.style.top  = (rect.bottom + window.scrollY + 4) + 'px';
            emojiPicker.style.left = (rect.left + window.scrollX) + 'px';
            emojiPicker.style.display = 'block';
            return;
        }
        if (!emojiPicker.contains(e.target)) {
            emojiPicker.style.display = 'none';
        }
    });

    emojiPicker?.addEventListener('click', async function (e) {
        const pick = e.target.closest('.tt-emoji-pick');
        if (!pick) return;
        const msgId = this.dataset.msgId;
        const emoji = pick.dataset.emoji;
        emojiPicker.style.display = 'none';
        const result = await apiPost(`/account/titan-talk/messages/${msgId}/reactions`, { emoji });
        if (result.summary !== undefined) {
            updateReactions(msgId, result.summary, result.user_reactions ?? []);
        }
    });

    // ---- Toggle existing reaction ----
    document.addEventListener('click', async function (e) {
        const btn = e.target.closest('.tt-react-toggle');
        if (!btn) return;
        e.preventDefault();
        const msgId = btn.dataset.msg;
        const emoji = btn.dataset.emoji;
        // Toggle: if already reacted (tt-reacted class), DELETE; otherwise POST
        const isActive = btn.classList.contains('tt-reacted');
        let result;
        if (isActive) {
            result = await apiDelete(`/account/titan-talk/messages/${msgId}/reactions/${encodeURIComponent(emoji)}`);
        } else {
            result = await apiPost(`/account/titan-talk/messages/${msgId}/reactions`, { emoji });
        }
        if (result.summary !== undefined) {
            updateReactions(msgId, result.summary, result.user_reactions ?? []);
        }
    });

    function updateReactions(msgId, summary, userReactions) {
        userReactions = userReactions ?? [];
        const msgDiv = document.querySelector(`.tt-message[data-msg-id="${msgId}"]`);
        if (!msgDiv) return;
        const container = msgDiv.querySelector('.tt-reactions');
        if (!container) return;
        container.innerHTML = '';
        Object.entries(summary).forEach(([emoji, count]) => {
            const reacted = userReactions.includes(emoji);
            const btn = document.createElement('button');
            btn.className = `btn btn-sm py-0 px-1 mr-1 tt-react-toggle ${reacted ? 'btn-secondary tt-reacted' : 'btn-outline-secondary'}`;
            btn.dataset.msg   = msgId;
            btn.dataset.emoji = emoji;
            btn.title         = reacted ? 'Click to remove reaction' : 'Click to react';
            btn.innerHTML     = `${emoji} <span class="badge ${reacted ? 'badge-light' : 'badge-secondary'}">${count}</span>`;
            container.appendChild(btn);
        });
    }

    // ---- Pinned messages modal ----
    document.getElementById('pinnedMessagesModal')?.addEventListener('show.bs.modal', async function () {
        if (!ROOM_ID) return;
        const list = document.getElementById('tt-pinned-list');
        list.innerHTML = '<div class="text-center py-3"><i class="fa fa-spin fa-spinner"></i></div>';
        const data = await apiGet(`/account/titan-talk/rooms/${ROOM_ID}/pinned`);
        list.innerHTML = '';
        (data.pins ?? []).forEach(pin => {
            const d = document.createElement('div');
            d.className = 'border-bottom pb-2 mb-2 d-flex justify-content-between align-items-start';
            d.innerHTML = `
                <div>
                    <strong class="small">${escHtml(pin.message?.author?.name ?? '?')}</strong>
                    <span class="ml-2">${escHtml(pin.message?.body ?? '')}</span>
                </div>
                ${IS_ADMIN ? `<a href="#" class="text-danger small tt-unpin-link" data-msg="${pin.message_id}">Unpin</a>` : ''}`;
            list.appendChild(d);
        });
        if (!data.pins?.length) list.innerHTML = '<p class="text-muted">No pinned messages.</p>';
    });

    // Unpin from modal
    document.getElementById('tt-pinned-list')?.addEventListener('click', async function (e) {
        const link = e.target.closest('.tt-unpin-link');
        if (!link) return;
        e.preventDefault();
        const result = await apiDelete(`/account/titan-talk/messages/${link.dataset.msg}/pin`);
        if (result.status === 'success') {
            link.closest('.border-bottom').remove();
        }
    });

    // ---- Mute/unmute room ----
    document.getElementById('tt-mute-btn')?.addEventListener('click', async function () {
        const roomId = this.dataset.room;
        const muted  = this.dataset.muted === '1';
        const url    = `/account/titan-talk/rooms/${roomId}/${muted ? 'unmute' : 'mute'}`;
        const result = await apiPost(url, {});
        if (result.status === 'success') {
            this.dataset.muted = muted ? '0' : '1';
            this.title = muted ? 'Mute room' : 'Unmute room';
            this.querySelector('i').className = `fa fa-${muted ? 'bell' : 'bell-slash'}`;
        }
    });

    // ---- Remove member ----
    document.addEventListener('click', async function (e) {
        const btn = e.target.closest('.tt-remove-member-btn');
        if (!btn) return;
        if (!confirm('Remove this member?')) return;
        const roomId = btn.dataset.room;
        const userId = btn.dataset.user;
        const result = await apiDelete(`/account/titan-talk/rooms/${roomId}/members/${userId}`);
        if (result.status === 'success') {
            btn.closest('li').remove();
        }
    });

    // ---- New DM search ----
    let dmTimer;
    document.getElementById('tt-dm-search')?.addEventListener('input', function () {
        clearTimeout(dmTimer);
        const q = this.value.trim();
        if (q.length < 2) { document.getElementById('tt-dm-results').innerHTML = ''; return; }
        dmTimer = setTimeout(async () => {
            const data = await apiGet(`/account/titan-talk/search?q=${encodeURIComponent(q)}`);
            const container = document.getElementById('tt-dm-results');
            container.innerHTML = '';
            (data.users ?? []).forEach(u => {
                const a = document.createElement('a');
                a.href = `/account/titan-talk?dm_user_id=${encodeURIComponent(u.id)}`;
                a.className = 'd-block px-2 py-1 rounded text-dark';
                a.style.cssText = 'text-decoration:none;cursor:pointer;';
                a.onmouseenter = () => { a.style.background = '#f0f4ff'; };
                a.onmouseleave = () => { a.style.background = ''; };
                a.textContent = u.name;
                container.appendChild(a);
            });
            if (!data.users?.length) container.innerHTML = '<small class="text-muted">No users found.</small>';
        }, 250);
    });

    // ---- Search ----
    const searchInput = document.getElementById('tt-search-input');
    if (searchInput) {
        let timer;
        let searchController = null;
        searchInput.addEventListener('input', function () {
            clearTimeout(timer);
            if (searchController) { searchController.abort(); }
            timer = setTimeout(async () => {
                const q = this.value.trim();
                if (q.length < 2) return;
                searchController = new AbortController();
                try {
                    const res = await fetch(
                        `/account/titan-talk/search?q=${encodeURIComponent(q)}`,
                        { headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': CSRF }, signal: searchController.signal }
                    );
                    const data = await res.json();
                    renderSearchResults(data, q);
                } catch (err) {
                    if (err.name !== 'AbortError') console.error('Search error:', err);
                }
            }, 300);
        });
    }

    // ---- Unread counts (poll every 30s) ----
    async function loadUnreadCounts() {
        try {
            const data = await apiGet('/account/titan-talk/unread-counts');
            if (!data.counts) return;
            Object.entries(data.counts).forEach(([roomId, count]) => {
                const badge = document.querySelector(`.tt-unread-badge[data-room="${roomId}"]`);
                if (badge) {
                    if (count > 0) {
                        badge.textContent = count > 99 ? '99+' : count;
                        badge.style.display = '';
                    } else {
                        badge.style.display = 'none';
                    }
                }
            });
        } catch (e) {}
    }
    loadUnreadCounts();
    setInterval(loadUnreadCounts, 30000);

    // ---- Search result rendering ----
    function renderSearchResults(data, q) {
        let dropdown = document.getElementById('tt-search-dropdown');
        if (!dropdown) {
            dropdown = document.createElement('div');
            dropdown.id = 'tt-search-dropdown';
            dropdown.style.cssText = 'position:absolute;z-index:9999;background:#fff;border:1px solid #ccc;border-radius:4px;max-height:320px;overflow-y:auto;width:230px;box-shadow:0 4px 12px rgba(0,0,0,.1);';
            const searchWrap = document.getElementById('tt-search-input')?.parentElement;
            if (searchWrap) { searchWrap.style.position = 'relative'; searchWrap.appendChild(dropdown); }
        }
        dropdown.innerHTML = '';

        const total = (data.messages?.length ?? 0) + (data.rooms?.length ?? 0) + (data.users?.length ?? 0);
        if (total === 0) {
            dropdown.innerHTML = '<div class="px-3 py-2 text-muted small">No results for "' + escHtml(q) + '"</div>';
            dropdown.style.display = 'block';
            return;
        }

        function section(label, items, href) {
            if (!items?.length) return;
            const h = document.createElement('div');
            h.className = 'px-3 pt-2 pb-1';
            h.innerHTML = '<small class="text-muted font-weight-bold text-uppercase">' + escHtml(label) + '</small>';
            dropdown.appendChild(h);
            items.forEach(item => {
                const a = document.createElement('a');
                a.href = href(item);
                a.className = 'd-block px-3 py-1 text-dark small';
                a.style.cssText = 'text-decoration:none;';
                a.onmouseenter = () => { a.style.background = '#f8f9fa'; };
                a.onmouseleave = () => { a.style.background = ''; };
                const text = item.name ?? (item.author?.name ?? '') + ': ' + (item.body ?? '');
                a.textContent = text.length > 60 ? text.slice(0, 57) + '…' : text;
                dropdown.appendChild(a);
            });
        }

        section('Rooms',    data.rooms,    r => '/account/titan-talk/room/' + r.id);
        section('Messages', data.messages, m => '/account/titan-talk/room/' + m.room_id);
        section('Users',    data.users,    u => '/account/titan-talk?dm_user_id=' + encodeURIComponent(u.id));

        dropdown.style.display = 'block';
    }

    // Hide dropdown on outside click
    document.addEventListener('click', function (e) {
        const dd = document.getElementById('tt-search-dropdown');
        if (dd && !dd.contains(e.target) && e.target.id !== 'tt-search-input') {
            dd.style.display = 'none';
        }
    });

    // ---- Helpers ----
    function escHtml(str) {
        const div = document.createElement('div');
        div.textContent = String(str ?? '');
        return div.innerHTML;
    }

    function formatTime(iso) {
        if (!iso) return '';
        return new Date(iso).toLocaleTimeString([], {hour:'2-digit', minute:'2-digit'});
    }
})();
</script>
@endsection
