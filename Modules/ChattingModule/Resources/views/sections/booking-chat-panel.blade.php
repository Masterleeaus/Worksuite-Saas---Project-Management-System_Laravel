{{--
    ChattingModule — Booking Chat Panel
    Injected via @stack('booking-chat') in the BookingModule details view.
    Provides an embedded real-time chat thread scoped to the current booking.
--}}
@if(class_exists(\Modules\ChattingModule\Models\ChatRoom::class))
<div class="card mt-4" id="booking-chat-panel">
    <div class="card-header d-flex align-items-center gap-2">
        <span class="material-icons">chat</span>
        <h5 class="mb-0">{{ translate('Booking Chat') }}</h5>
        <span class="badge bg-danger ms-auto d-none" id="chat-unread-badge">0</span>
    </div>
    <div class="card-body p-0">
        <div id="booking-chat-messages"
             class="overflow-auto p-3"
             style="max-height:400px;"
             data-booking-id="{{ $booking->id ?? '' }}">
            {{-- Messages loaded via JS --}}
        </div>
    </div>
    <div class="card-footer">
        <form id="booking-chat-form" class="d-flex gap-2 align-items-end">
            @csrf
            <input type="hidden" name="booking_id" value="{{ $booking->id ?? '' }}">
            <input type="hidden" name="receiver_id" id="chat-receiver-id" value="">
            <div class="flex-grow-1">
                <textarea name="message"
                          id="chat-message-input"
                          class="form-control"
                          rows="2"
                          maxlength="5000"
                          placeholder="{{ translate('Type a message…') }}"></textarea>
            </div>
            <div class="d-flex flex-column gap-1">
                <label class="btn btn-outline-secondary btn-sm mb-0" title="{{ translate('Attach file') }}">
                    <span class="material-icons">attach_file</span>
                    <input type="file" name="attachment" class="d-none" id="chat-attachment"
                           accept="image/*,application/pdf,.doc,.docx,.xls,.xlsx">
                </label>
                <button type="submit" class="btn btn--primary btn-sm">
                    <span class="material-icons">send</span>
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
(function () {
    const bookingId = '{{ $booking->id ?? '' }}';
    const currentUserId = {{ user()->id ?? 0 }};
    const messagesEl = document.getElementById('booking-chat-messages');
    const form = document.getElementById('booking-chat-form');

    function escapeHtml(text) {
        const div = document.createElement('div');
        div.appendChild(document.createTextNode(text || ''));
        return div.innerHTML;
    }

    function renderMessage(msg) {
        const isMine = msg.from === currentUserId;
        const name = isMine
            ? '{{ user()->name ?? "You" }}'
            : escapeHtml(msg.from_user ? msg.from_user.name : 'User ' + msg.from);
        const time = new Date(msg.created_at).toLocaleTimeString([], {hour:'2-digit',minute:'2-digit'});
        let body = '';

        if (msg.message_type === 'location') {
            try {
                const loc = JSON.parse(msg.message || '{}');
                body = `<a href="https://maps.google.com/?q=${escapeHtml(loc.lat)},${escapeHtml(loc.lng)}" target="_blank" rel="noopener noreferrer">
                            <span class="material-icons">location_on</span> View location
                        </a>`;
            } catch (e) {
                body = escapeHtml(msg.message);
            }
        } else if (msg.attachment_path) {
            const attachUrl = '/account/booking-chat/attachment/' + msg.id;
            body = `<a href="${attachUrl}" target="_blank" rel="noopener noreferrer">
                        <span class="material-icons">attach_file</span> Attachment
                    </a>`;
            if (msg.message) {
                body = escapeHtml(msg.message) + ' ' + body;
            }
        } else {
            body = escapeHtml(msg.message);
        }

        const deletedClass = msg.is_deleted ? 'text-muted fst-italic' : '';
        const deletedText  = msg.is_deleted ? '(deleted)' : body;

        return `<div class="d-flex ${isMine ? 'justify-content-end' : ''} mb-2">
            <div class="chat-bubble ${isMine ? 'bg-primary text-white' : 'bg-light'} rounded p-2" style="max-width:75%;">
                <small class="fw-semibold d-block mb-1">${name}</small>
                <span class="${deletedClass}">${deletedText}</span>
                <small class="d-block text-end opacity-75 mt-1">${time}</small>
            </div>
        </div>`;
    }

    function loadMessages() {
        fetch(`/account/booking-chat/${bookingId}/messages`, {
            headers: {'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json'}
        })
        .then(r => r.json())
        .then(data => {
            if (data.messages) {
                messagesEl.innerHTML = data.messages.map(renderMessage).join('');
                messagesEl.scrollTop = messagesEl.scrollHeight;
            }
        })
        .catch(() => {});
    }

    form.addEventListener('submit', function (e) {
        e.preventDefault();
        const fd = new FormData(form);
        fetch('/account/booking-chat/send', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'X-Requested-With': 'XMLHttpRequest',
            },
            body: fd
        })
        .then(r => r.json())
        .then(data => {
            if (data.status === 'success') {
                form.querySelector('textarea[name="message"]').value = '';
                form.querySelector('#chat-attachment').value = '';
                loadMessages();
            }
        })
        .catch(() => {});
    });

    // Initial load
    loadMessages();

    // Poll every 15 seconds for new messages (existing Worksuite echo/pusher handles real-time)
    setInterval(loadMessages, 15000);
})();
</script>
@endpush
@endif
