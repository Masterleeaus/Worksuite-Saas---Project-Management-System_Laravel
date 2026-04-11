<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>{{ $chatbot->name }}</title>
<style>
* { box-sizing: border-box; margin: 0; padding: 0; }
:root {
  --theme: {{ $settings['theme_color'] }};
  --user-bg: {{ $settings['user_bubble_color'] }};
  --user-color: {{ $settings['user_text_color'] }};
  --bot-bg: {{ $settings['bot_bubble_color'] }};
  --bot-color: {{ $settings['bot_text_color'] }};
  --fs: {{ $settings['font_size'] }}px;
}
body { font-family: -apple-system,BlinkMacSystemFont,'Segoe UI',sans-serif; background: #fff; height: 100vh; display: flex; flex-direction: column; }

/* Header */
.cb-header { background: var(--theme); color: #fff; padding: 14px 16px; display: flex; align-items: center; gap: 10px; flex-shrink: 0; }
.cb-avatar { width: 38px; height: 38px; border-radius: 50%; background: rgba(255,255,255,.2); display: flex; align-items: center; justify-content: center; font-size: 20px; }
.cb-header-info h5 { font-size: 15px; font-weight: 600; margin: 0; }
.cb-header-info small { opacity: .8; font-size: 11px; }
.cb-status-dot { width: 8px; height: 8px; border-radius: 50%; background: #22c55e; margin-top: 2px; }

/* Messages */
.cb-messages { flex: 1; overflow-y: auto; padding: 16px; display: flex; flex-direction: column; gap: 10px; }
.cb-typing { display: flex; align-items: flex-end; gap: 8px; }
.cb-typing-dots { background: var(--bot-bg); color: var(--bot-color); padding: 10px 14px; border-radius: 14px 14px 14px 4px; }
.cb-typing-dots span { display: inline-block; width: 6px; height: 6px; border-radius: 50%; background: currentColor; opacity: .6; animation: bounce 1.2s infinite; margin: 0 1px; }
.cb-typing-dots span:nth-child(2) { animation-delay: .2s; }
.cb-typing-dots span:nth-child(3) { animation-delay: .4s; }
@keyframes bounce { 0%,80%,100%{transform:translateY(0)} 40%{transform:translateY(-5px)} }

.cb-msg-row { display: flex; gap: 8px; align-items: flex-end; }
.cb-msg-row.user { flex-direction: row-reverse; }
.cb-msg-avatar { width: 28px; height: 28px; border-radius: 50%; flex-shrink: 0; background: var(--theme); color: #fff; display: flex; align-items: center; justify-content: center; font-size: 13px; }
.cb-msg-row.user .cb-msg-avatar { background: #e5e7eb; color: #374151; }
.cb-bubble { max-width: 80%; padding: 9px 13px; border-radius: 14px; font-size: var(--fs); line-height: 1.5; word-break: break-word; }
.cb-msg-row.bot  .cb-bubble { background: var(--bot-bg); color: var(--bot-color); border-bottom-left-radius: 4px; }
.cb-msg-row.user .cb-bubble { background: var(--user-bg); color: var(--user-color); border-bottom-right-radius: 4px; }
.cb-time { font-size: 10px; color: #9ca3af; margin-top: 3px; }
.cb-msg-row.user .cb-time { text-align: right; }

/* Canned responses */
.cb-canned-bar { padding: 8px 12px 4px; display: flex; flex-wrap: wrap; gap: 6px; border-top: 1px solid #f0f0f0; }
.cb-canned-chip { border: 1px solid var(--theme); color: var(--theme); background: transparent; border-radius: 20px; padding: 3px 10px; font-size: 11px; cursor: pointer; }
.cb-canned-chip:hover { background: var(--theme); color: #fff; }

/* Input bar */
.cb-input-bar { padding: 10px 12px; border-top: 1px solid #e5e7eb; display: flex; gap: 8px; flex-shrink: 0; }
.cb-input-bar input { flex: 1; border: 1px solid #d1d5db; border-radius: 22px; padding: 8px 16px; font-size: var(--fs); outline: none; transition: border-color .2s; }
.cb-input-bar input:focus { border-color: var(--theme); }
.cb-send-btn { background: var(--theme); color: #fff; border: none; border-radius: 50%; width: 38px; height: 38px; display: flex; align-items: center; justify-content: center; cursor: pointer; flex-shrink: 0; transition: opacity .2s; }
.cb-send-btn:disabled { opacity: .5; cursor: default; }

/* Footer */
.cb-footer { padding: 6px; text-align: center; font-size: 10px; color: #9ca3af; flex-shrink: 0; {{ $settings['show_powered_by'] ? '' : 'display:none;' }} }
.cb-footer a { color: inherit; }
</style>
</head>
<body>

<div class="cb-header">
    <div class="cb-avatar">
        @if($chatbot->avatar)
            <img src="{{ $chatbot->avatar->url }}" alt="" width="38" height="38" style="border-radius:50%;object-fit:cover;">
        @else
            @php $icons=['bubble'=>'💬','robot'=>'🤖','heart'=>'💜']; @endphp
            {{ $icons[$settings['launcher_icon']] ?? '💬' }}
        @endif
    </div>
    <div class="cb-header-info">
        <h5>{{ $settings['header_text'] ?: $chatbot->name }}</h5>
        <small>AI Assistant</small>
    </div>
    <div class="ms-auto d-flex align-items-center gap-2">
        <div class="cb-status-dot" title="Online"></div>
    </div>
</div>

<div class="cb-messages" id="cb-messages">
    {{-- welcome message injected by JS --}}
</div>

<div class="cb-canned-bar" id="cb-canned-bar" style="display:none;"></div>

<div class="cb-input-bar">
    <input type="text" id="cb-input" placeholder="Type a message…" autocomplete="off">
    <button class="cb-send-btn" id="cb-send-btn" onclick="sendMessage()">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg>
    </button>
</div>
<div class="cb-footer">Powered by <a href="#" target="_blank">WorkSuite</a></div>

<script>
const CHATBOT_ID  = '{{ $chatbot->id }}';
const API_BASE    = '{{ url('/api/chatbot') }}';
const WELCOME_MSG = @json($chatbot->welcome_message ?: 'Hello! How can I help you today?');

let sessionId = null;
let conversationId = null;
let isTyping = false;

const messagesEl = document.getElementById('cb-messages');
const inputEl    = document.getElementById('cb-input');
const sendBtn    = document.getElementById('cb-send-btn');

function addMessage(role, content, time) {
    const row = document.createElement('div');
    row.className = 'cb-msg-row ' + (role === 'user' ? 'user' : 'bot');

    const avatarEl = document.createElement('div');
    avatarEl.className = 'cb-msg-avatar';
    avatarEl.textContent = role === 'user' ? '👤' : '🤖';

    const inner = document.createElement('div');
    inner.style.maxWidth = '80%';

    const bubble = document.createElement('div');
    bubble.className = 'cb-bubble';
    bubble.textContent = content;

    const timeEl = document.createElement('div');
    timeEl.className = 'cb-time';
    timeEl.textContent = time || new Date().toLocaleTimeString([], {hour:'2-digit',minute:'2-digit'});

    inner.appendChild(bubble);
    inner.appendChild(timeEl);
    row.appendChild(avatarEl);
    row.appendChild(inner);

    messagesEl.appendChild(row);
    messagesEl.scrollTop = messagesEl.scrollHeight;
}

function showTyping() {
    const el = document.createElement('div');
    el.id = 'cb-typing';
    el.className = 'cb-typing';
    el.innerHTML = '<div class="cb-msg-avatar">🤖</div><div class="cb-typing-dots"><span></span><span></span><span></span></div>';
    messagesEl.appendChild(el);
    messagesEl.scrollTop = messagesEl.scrollHeight;
}

function hideTyping() {
    document.getElementById('cb-typing')?.remove();
}

async function initConversation() {
    try {
        const r = await fetch(`${API_BASE}/widget/${CHATBOT_ID}/start`, {
            method: 'POST',
            headers: {'Content-Type':'application/json','Accept':'application/json','X-Requested-With':'XMLHttpRequest'},
            body: JSON.stringify({channel:'web'})
        });
        const data = await r.json();
        sessionId = data.session_id;
        conversationId = data.conversation_id;
    } catch (e) {
        console.error('Init error', e);
    }
    addMessage('bot', WELCOME_MSG);
    loadCannedResponses();
}

async function loadCannedResponses() {
    try {
        const r = await fetch(`${API_BASE}/widget/${CHATBOT_ID}/canned`, {headers:{'Accept':'application/json'}});
        const responses = await r.json();
        const bar = document.getElementById('cb-canned-bar');
        if (responses.length > 0) {
            bar.style.display = 'flex';
            responses.slice(0,4).forEach(cr => {
                const chip = document.createElement('button');
                chip.className = 'cb-canned-chip';
                chip.textContent = cr.shortcut ? '/' + cr.shortcut + ' — ' + cr.title : cr.title;
                chip.onclick = () => { inputEl.value = cr.content; sendMessage(); };
                bar.appendChild(chip);
            });
        }
    } catch(e) {}
}

async function sendMessage() {
    const text = inputEl.value.trim();
    if (!text || isTyping) return;

    addMessage('user', text);
    inputEl.value = '';
    isTyping = true;
    sendBtn.disabled = true;

    showTyping();

    try {
        const r = await fetch(`${API_BASE}/message`, {
            method: 'POST',
            headers: {'Content-Type':'application/json','Accept':'application/json','X-Requested-With':'XMLHttpRequest'},
            body: JSON.stringify({session_id: sessionId, message: text})
        });
        const data = await r.json();
        hideTyping();
        addMessage('bot', data.reply || 'Sorry, I could not understand that.');
    } catch(e) {
        hideTyping();
        addMessage('bot', 'Connection error. Please try again.');
    }

    isTyping = false;
    sendBtn.disabled = false;
    inputEl.focus();
}

inputEl.addEventListener('keydown', e => { if (e.key === 'Enter' && !e.shiftKey) { e.preventDefault(); sendMessage(); } });

// Initialise on load
initConversation();
</script>
</body>
</html>
