@extends('layouts/layoutMaster')

@section('title', 'Builder — ' . $chatbot->name)

@push('styles')
<style>
.builder-layout { display: grid; grid-template-columns: 1fr 420px; gap: 1.5rem; min-height: calc(100vh - 180px); }
.builder-preview { position: sticky; top: 80px; height: calc(100vh - 180px); display: flex; align-items: center; justify-content: center; background: #f8f9fa; border-radius: 12px; overflow: hidden; }
.preview-device { background: #e5e7eb; border-radius: 16px; width: 390px; height: 520px; position: relative; overflow: hidden; box-shadow: 0 20px 60px rgba(0,0,0,.15); }
.preview-device-bar { background: #d1d5db; height: 28px; display: flex; align-items: center; padding: 0 10px; gap: 6px; }
.preview-device-bar span { width: 10px; height: 10px; border-radius: 50%; }
.preview-device-bar span:nth-child(1){background:#ef4444;} .preview-device-bar span:nth-child(2){background:#f59e0b;} .preview-device-bar span:nth-child(3){background:#22c55e;}
.preview-device-content { height: calc(100% - 28px); background: #fff; position: relative; }

/* Widget preview styles */
.widget-preview-launcher { position: absolute; width: 52px; height: 52px; border-radius: 50%; display: flex; align-items: center; justify-content: center; cursor: pointer; box-shadow: 0 4px 16px rgba(0,0,0,.2); transition: transform .2s; }
.widget-preview-launcher:hover { transform: scale(1.05); }
.widget-preview-window { position: absolute; width: 320px; border-radius: 12px; box-shadow: 0 8px 32px rgba(0,0,0,.18); display: flex; flex-direction: column; overflow: hidden; transition: opacity .2s, transform .2s; }
.widget-preview-window.hidden { opacity: 0; pointer-events: none; transform: translateY(10px) scale(.96); }
.widget-header { padding: 14px 16px; display: flex; align-items: center; gap: 10px; }
.widget-header-avatar { width: 36px; height: 36px; border-radius: 50%; background: rgba(255,255,255,.25); display: flex; align-items: center; justify-content: center; font-size: 18px; }
.widget-header h6 { margin: 0; font-size: 14px; color: #fff; font-weight: 600; }
.widget-header small { color: rgba(255,255,255,.75); font-size: 11px; }
.widget-header .close-btn { margin-left: auto; background: none; border: none; color: rgba(255,255,255,.8); cursor: pointer; font-size: 18px; line-height: 1; }
.widget-messages { flex: 1; overflow-y: auto; padding: 12px; display: flex; flex-direction: column; gap: 8px; background: #fff; }
.chat-bubble { max-width: 80%; padding: 8px 12px; border-radius: 12px; font-size: 13px; line-height: 1.4; }
.chat-bubble.bot { align-self: flex-start; border-bottom-left-radius: 4px; }
.chat-bubble.user { align-self: flex-end; border-bottom-right-radius: 4px; }
.widget-input-bar { padding: 10px 12px; border-top: 1px solid #e5e7eb; display: flex; gap: 8px; background: #fff; }
.widget-input-bar input { flex: 1; border: 1px solid #d1d5db; border-radius: 20px; padding: 6px 14px; font-size: 12px; outline: none; }
.widget-input-bar button { background: var(--preview-color, #6366f1); color: #fff; border: none; border-radius: 50%; width: 32px; height: 32px; cursor: pointer; display: flex; align-items: center; justify-content: center; }
.widget-powered { text-align: center; padding: 4px; font-size: 10px; color: #9ca3af; background: #fff; }

/* Nav tabs style */
.builder-tabs .nav-link { border-radius: 6px 6px 0 0; font-size: 13px; padding: 8px 16px; }
</style>
@endpush

@section('content')
<div class="container-fluid">
    {{-- Header --}}
    <div class="d-flex align-items-center mb-4">
        <a href="{{ route('titanagents.chatbot.show', $chatbot) }}" class="btn btn-outline-secondary me-3">
            <i class="ti ti-arrow-left"></i>
        </a>
        <div class="flex-fill">
            <h4 class="mb-0">Chatbot Builder — <span id="preview-title">{{ $chatbot->name }}</span></h4>
            <small class="text-muted">Visual builder with live preview</small>
        </div>
        <button type="submit" form="builder-form" class="btn btn-primary">
            <i class="ti ti-device-floppy me-1"></i> Save Changes
        </button>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">{{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
    @endif
    @if($errors->any())
        <div class="alert alert-danger">{{ $errors->first() }}</div>
    @endif

    <form id="builder-form" method="POST" action="{{ route('titanagents.chatbot.builder.update', $chatbot) }}">
        @csrf @method('PUT')

        <div class="builder-layout">
            {{-- Left: tabbed settings --}}
            <div>
                <ul class="nav nav-tabs builder-tabs mb-0" id="builderTabs">
                    <li class="nav-item"><button class="nav-link active" data-bs-toggle="tab" data-bs-target="#tab-general" type="button">General</button></li>
                    <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-appearance" type="button">Appearance</button></li>
                    <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-behaviour" type="button">Behaviour</button></li>
                    <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-embed" type="button">Embed</button></li>
                </ul>

                <div class="tab-content card border-top-0 rounded-0 rounded-bottom p-4">

                    {{-- GENERAL TAB --}}
                    <div class="tab-pane fade show active" id="tab-general">
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label">Chatbot Name</label>
                                <input type="text" name="name" id="input-name" class="form-control"
                                    value="{{ old('name', $chatbot->name) }}" required>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Description</label>
                                <textarea name="description" class="form-control" rows="2">{{ old('description', $chatbot->description) }}</textarea>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">AI Provider</label>
                                <select name="ai_provider" id="input-provider" class="form-select" required>
                                    <option value="openai"    {{ old('ai_provider',$chatbot->ai_provider)==='openai'    ? 'selected':'' }}>OpenAI</option>
                                    <option value="anthropic" {{ old('ai_provider',$chatbot->ai_provider)==='anthropic' ? 'selected':'' }}>Anthropic</option>
                                    <option value="gemini"    {{ old('ai_provider',$chatbot->ai_provider)==='gemini'    ? 'selected':'' }}>Google Gemini</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">AI Model <small class="text-muted">(leave blank for default)</small></label>
                                <input type="text" name="ai_model" class="form-control"
                                    value="{{ old('ai_model', $chatbot->ai_model) }}" placeholder="e.g. gpt-4o-mini">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Temperature</label>
                                <input type="number" name="temperature" step="0.01" min="0" max="2" class="form-control"
                                    value="{{ old('temperature', $chatbot->temperature) }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Max Tokens</label>
                                <input type="number" name="max_tokens" min="100" max="8000" class="form-control"
                                    value="{{ old('max_tokens', $chatbot->max_tokens) }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Status</label>
                                <select name="status" class="form-select">
                                    <option value="active"   {{ $chatbot->status==='active'   ? 'selected':'' }}>Active</option>
                                    <option value="inactive" {{ $chatbot->status==='inactive' ? 'selected':'' }}>Inactive</option>
                                </select>
                            </div>
                            <div class="col-12">
                                <label class="form-label">System Prompt</label>
                                <textarea name="system_prompt" class="form-control" rows="4"
                                    placeholder="Instructions that define this chatbot's personality and scope...">{{ old('system_prompt', $chatbot->system_prompt) }}</textarea>
                            </div>
                        </div>
                    </div>

                    {{-- APPEARANCE TAB --}}
                    <div class="tab-pane fade" id="tab-appearance">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Theme / Accent Color</label>
                                <div class="input-group">
                                    <input type="color" name="settings[theme_color]" id="input-theme-color" class="form-control form-control-color"
                                        value="{{ old('settings.theme_color', $settings['theme_color']) }}" title="Theme color">
                                    <input type="text" id="input-theme-color-hex" class="form-control" 
                                        value="{{ old('settings.theme_color', $settings['theme_color']) }}" placeholder="#6366f1" maxlength="7">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Header Text</label>
                                <input type="text" name="settings[header_text]" id="input-header-text" class="form-control"
                                    value="{{ old('settings.header_text', $settings['header_text']) }}" placeholder="Chat with us">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Launcher Icon</label>
                                <select name="settings[launcher_icon]" id="input-launcher-icon" class="form-select">
                                    <option value="bubble" {{ $settings['launcher_icon']==='bubble' ? 'selected':'' }}>💬 Chat Bubble</option>
                                    <option value="robot"  {{ $settings['launcher_icon']==='robot'  ? 'selected':'' }}>🤖 Robot</option>
                                    <option value="heart"  {{ $settings['launcher_icon']==='heart'  ? 'selected':'' }}>💜 Heart</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Widget Position</label>
                                <select name="settings[position]" id="input-position" class="form-select">
                                    <option value="bottom-right" {{ $settings['position']==='bottom-right' ? 'selected':'' }}>Bottom Right</option>
                                    <option value="bottom-left"  {{ $settings['position']==='bottom-left'  ? 'selected':'' }}>Bottom Left</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Window Width (px)</label>
                                <input type="number" name="settings[window_width]" id="input-width" class="form-control" min="280" max="600"
                                    value="{{ old('settings.window_width', $settings['window_width']) }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Window Height (px)</label>
                                <input type="number" name="settings[window_height]" id="input-height" class="form-control" min="400" max="800"
                                    value="{{ old('settings.window_height', $settings['window_height']) }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Font Size (px)</label>
                                <input type="number" name="settings[font_size]" id="input-font-size" class="form-control" min="11" max="20"
                                    value="{{ old('settings.font_size', $settings['font_size']) }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">User Bubble Color</label>
                                <div class="input-group">
                                    <input type="color" name="settings[user_bubble_color]" id="input-user-bubble" class="form-control form-control-color"
                                        value="{{ old('settings.user_bubble_color', $settings['user_bubble_color']) }}">
                                    <span class="input-group-text">User</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Bot Bubble Color</label>
                                <div class="input-group">
                                    <input type="color" name="settings[bot_bubble_color]" id="input-bot-bubble" class="form-control form-control-color"
                                        value="{{ old('settings.bot_bubble_color', $settings['bot_bubble_color']) }}">
                                    <span class="input-group-text">Bot</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">User Text Color</label>
                                <input type="color" name="settings[user_text_color]" id="input-user-text" class="form-control form-control-color"
                                    value="{{ old('settings.user_text_color', $settings['user_text_color']) }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Bot Text Color</label>
                                <input type="color" name="settings[bot_text_color]" id="input-bot-text" class="form-control form-control-color"
                                    value="{{ old('settings.bot_text_color', $settings['bot_text_color']) }}">
                            </div>
                        </div>
                    </div>

                    {{-- BEHAVIOUR TAB --}}
                    <div class="tab-pane fade" id="tab-behaviour">
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label">Welcome Message</label>
                                <input type="text" name="welcome_message" id="input-welcome" class="form-control"
                                    value="{{ old('welcome_message', $chatbot->welcome_message) }}"
                                    placeholder="Hello! How can I help you today?">
                            </div>
                            <div class="col-12">
                                <label class="form-label">Fallback Message <small class="text-muted">(when AI fails)</small></label>
                                <input type="text" name="fallback_message" class="form-control"
                                    value="{{ old('fallback_message', $chatbot->fallback_message) }}"
                                    placeholder="Sorry, I am unable to respond right now.">
                            </div>
                            <div class="col-12">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="settings[show_powered_by]" id="input-powered-by"
                                        value="1" {{ ($settings['show_powered_by'] ?? true) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="input-powered-by">Show "Powered by WorkSuite" footer</label>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="settings[initial_open]" id="input-initial-open"
                                        value="1" {{ ($settings['initial_open'] ?? false) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="input-initial-open">Open chat window automatically on page load</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- EMBED TAB --}}
                    <div class="tab-pane fade" id="tab-embed">
                        <p class="text-muted mb-3">Copy one of the snippets below and paste it into your website's <code>&lt;body&gt;</code> tag.</p>

                        <div class="mb-4">
                            <label class="form-label fw-semibold">🚀 Script Embed (recommended)</label>
                            <div class="position-relative">
                                <textarea class="form-control font-monospace" rows="6" id="embed-script" readonly>{{ '<script>
(function(){
  var s=document.createElement("script");
  s.src="'.url('/chatbot-widget.js').'";
  s.setAttribute("data-chatbot-id","'.$chatbot->id.'");
  s.setAttribute("data-api-url","'.url('/api/chatbot').'");
  s.async=true;
  document.head.appendChild(s);
})();
</script>' }}</textarea>
                                <button type="button" class="btn btn-sm btn-outline-primary position-absolute top-0 end-0 m-2" onclick="copyEmbed('embed-script')">
                                    <i class="ti ti-copy"></i> Copy
                                </button>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold">📦 iFrame Embed</label>
                            <div class="position-relative">
                                <textarea class="form-control font-monospace" rows="5" id="embed-iframe" readonly>{{ '<iframe
  src="'.url('/chatbot-widget/'.$chatbot->id.'/frame').'"
  style="border:none;position:fixed;bottom:0;right:0;width:420px;height:600px;z-index:9999;"
  allow="microphone"
></iframe>' }}</textarea>
                                <button type="button" class="btn btn-sm btn-outline-primary position-absolute top-0 end-0 m-2" onclick="copyEmbed('embed-iframe')">
                                    <i class="ti ti-copy"></i> Copy
                                </button>
                            </div>
                        </div>

                        <div class="alert alert-info d-flex gap-2">
                            <i class="ti ti-info-circle fs-5 mt-1 flex-shrink-0"></i>
                            <div>
                                <strong>Chatbot ID:</strong> <code class="user-select-all">{{ $chatbot->id }}</code><br>
                                <strong>API Base URL:</strong> <code class="user-select-all">{{ url('/api/chatbot') }}</code><br>
                                <strong>Widget Frame URL:</strong> <code class="user-select-all">{{ url('/chatbot-widget/'.$chatbot->id.'/frame') }}</code>
                            </div>
                        </div>
                    </div>

                </div>{{-- end tab-content --}}
            </div>

            {{-- Right: live preview --}}
            <div class="builder-preview">
                <div>
                    <p class="text-muted text-center small mb-2 fw-semibold text-uppercase" style="letter-spacing:.5px;">Live Preview</p>
                    <div class="preview-device">
                        <div class="preview-device-bar">
                            <span></span><span></span><span></span>
                        </div>
                        <div class="preview-device-content" id="preview-site">
                            {{-- Simulated website background --}}
                            <div style="padding:16px;font-size:12px;color:#6b7280;">
                                <div style="width:60%;height:10px;background:#e5e7eb;border-radius:4px;margin-bottom:8px;"></div>
                                <div style="width:85%;height:8px;background:#f3f4f6;border-radius:4px;margin-bottom:6px;"></div>
                                <div style="width:70%;height:8px;background:#f3f4f6;border-radius:4px;margin-bottom:6px;"></div>
                                <div style="width:40%;height:8px;background:#f3f4f6;border-radius:4px;"></div>
                            </div>

                            {{-- Widget window --}}
                            <div class="widget-preview-window" id="preview-window"
                                style="bottom:64px;width:290px;height:370px;background:#fff;{{ $settings['position']==='bottom-left' ? 'left:10px;' : 'right:10px;' }}">
                                <div class="widget-header" id="preview-header" style="background:{{ $settings['theme_color'] }};">
                                    <div class="widget-header-avatar">
                                        <span id="preview-launcher-icon">💬</span>
                                    </div>
                                    <div>
                                        <h6 id="preview-header-title">{{ $settings['header_text'] }}</h6>
                                        <small>We typically reply instantly</small>
                                    </div>
                                    <button class="close-btn" type="button" onclick="togglePreviewWindow()">×</button>
                                </div>
                                <div class="widget-messages" id="preview-messages">
                                    <div class="chat-bubble bot" id="preview-welcome"
                                        style="background:{{ $settings['bot_bubble_color'] }};color:{{ $settings['bot_text_color'] }};font-size:{{ $settings['font_size'] }}px;">
                                        {{ $chatbot->welcome_message ?: 'Hello! How can I help you today?' }}
                                    </div>
                                    <div class="chat-bubble user"
                                        style="background:{{ $settings['user_bubble_color'] }};color:{{ $settings['user_text_color'] }};font-size:{{ $settings['font_size'] }}px;">
                                        I need some help.
                                    </div>
                                    <div class="chat-bubble bot"
                                        style="background:{{ $settings['bot_bubble_color'] }};color:{{ $settings['bot_text_color'] }};font-size:{{ $settings['font_size'] }}px;">
                                        Of course! What can I help you with?
                                    </div>
                                </div>
                                <div class="widget-input-bar">
                                    <input type="text" placeholder="Type a message…" disabled style="font-size:{{ $settings['font_size'] }}px;">
                                    <button type="button" style="background:{{ $settings['theme_color'] }};">
                                        <i class="ti ti-send" style="font-size:14px;"></i>
                                    </button>
                                </div>
                                <div class="widget-powered" id="preview-powered">
                                    Powered by WorkSuite
                                </div>
                            </div>

                            {{-- Launcher button --}}
                            <div class="widget-preview-launcher" id="preview-launcher"
                                style="background:{{ $settings['theme_color'] }};bottom:10px;{{ $settings['position']==='bottom-left' ? 'left:10px;' : 'right:10px;' }}"
                                onclick="togglePreviewWindow()">
                                <span id="preview-launcher-bubble" style="font-size:22px;">💬</span>
                            </div>
                        </div>
                    </div>
                    <p class="text-center text-muted small mt-2">Click the bubble to toggle the window</p>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
const iconMap = { bubble: '💬', robot: '🤖', heart: '💜' };
let windowOpen = true;

function togglePreviewWindow() {
    windowOpen = !windowOpen;
    document.getElementById('preview-window').classList.toggle('hidden', !windowOpen);
}

function updatePreview() {
    const color   = document.getElementById('input-theme-color')?.value || '#6366f1';
    const header  = document.getElementById('input-header-text')?.value || '';
    const icon    = document.getElementById('input-launcher-icon')?.value || 'bubble';
    const pos     = document.getElementById('input-position')?.value || 'bottom-right';
    const fontSize= document.getElementById('input-font-size')?.value || 14;
    const userBg  = document.getElementById('input-user-bubble')?.value || color;
    const botBg   = document.getElementById('input-bot-bubble')?.value  || '#f3f4f6';
    const userTxt = document.getElementById('input-user-text')?.value  || '#ffffff';
    const botTxt  = document.getElementById('input-bot-text')?.value   || '#111827';
    const welcome = document.getElementById('input-welcome')?.value    || 'Hello! How can I help you today?';
    const name    = document.getElementById('input-name')?.value       || '';
    const poweredBy = document.getElementById('input-powered-by')?.checked;

    // Header
    const ph = document.getElementById('preview-header');
    if (ph) ph.style.background = color;
    const pht = document.getElementById('preview-header-title');
    if (pht) pht.textContent = header || name;
    document.querySelectorAll('#preview-title').forEach(el => el.textContent = name);

    // Icon
    const emoji = iconMap[icon] || '💬';
    ['preview-launcher-icon','preview-launcher-bubble'].forEach(id => {
        const el = document.getElementById(id);
        if (el) el.textContent = emoji;
    });

    // Launcher
    const pl = document.getElementById('preview-launcher');
    if (pl) {
        pl.style.background = color;
        if (pos === 'bottom-left') { pl.style.left='10px'; pl.style.right=''; }
        else { pl.style.right='10px'; pl.style.left=''; }
    }

    // Window position
    const pw = document.getElementById('preview-window');
    if (pw) {
        if (pos === 'bottom-left') { pw.style.left='10px'; pw.style.right=''; }
        else { pw.style.right='10px'; pw.style.left=''; }
    }

    // Bubbles
    document.querySelectorAll('.chat-bubble.bot').forEach(b => {
        b.style.background = botBg; b.style.color = botTxt; b.style.fontSize = fontSize+'px';
    });
    document.querySelectorAll('.chat-bubble.user').forEach(b => {
        b.style.background = userBg; b.style.color = userTxt; b.style.fontSize = fontSize+'px';
    });
    document.querySelector('.widget-input-bar button').style.background = color;
    document.querySelector('.widget-input-bar input').style.fontSize = fontSize+'px';

    // Welcome message
    const pw2 = document.getElementById('preview-welcome');
    if (pw2) pw2.textContent = welcome;

    // Powered by
    const ppb = document.getElementById('preview-powered');
    if (ppb) ppb.style.display = poweredBy ? '' : 'none';

    // Sync hex field
    const hexField = document.getElementById('input-theme-color-hex');
    if (hexField && document.activeElement !== hexField) hexField.value = color;
    document.documentElement.style.setProperty('--preview-color', color);
}

function copyEmbed(id) {
    const el = document.getElementById(id);
    navigator.clipboard.writeText(el.value).then(() => {
        const btn = el.nextElementSibling;
        const orig = btn.innerHTML;
        btn.innerHTML = '<i class="ti ti-check"></i> Copied!';
        setTimeout(() => btn.innerHTML = orig, 2000);
    });
}

// Bind all inputs
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('[id^="input-"]').forEach(el => {
        el.addEventListener('input', updatePreview);
        el.addEventListener('change', updatePreview);
    });

    // Sync hex field → color picker
    const hexField = document.getElementById('input-theme-color-hex');
    if (hexField) {
        hexField.addEventListener('input', function() {
            const v = this.value;
            if (/^#[0-9a-fA-F]{6}$/.test(v)) {
                document.getElementById('input-theme-color').value = v;
                updatePreview();
            }
        });
    }

    updatePreview();
});
</script>
@endpush
