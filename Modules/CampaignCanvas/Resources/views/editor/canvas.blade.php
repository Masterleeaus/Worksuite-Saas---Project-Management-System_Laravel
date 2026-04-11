@extends('campaigncanvas::layouts.master')

@section('campaigncanvas-content')
<style>
.cc-editor-wrap   { display:flex; flex-direction:column; height:100vh; overflow:hidden; }
.cc-editor-body   { display:flex; flex:1; overflow:hidden; }
.cc-canvas-area   { flex:1; background:#e0e0e0; overflow:auto; display:flex; align-items:center; justify-content:center; padding:20px; }
#cc-canvas        { background:#fff; box-shadow:0 2px 12px rgba(0,0,0,.2); cursor:default; }
#cc-canvas.dragging { cursor:move; }
</style>

<div class="cc-editor-wrap">
    {{-- Toolbar --}}
    @include('campaigncanvas::editor.toolbar')

    <div class="cc-editor-body">
        {{-- Canvas --}}
        <div class="cc-canvas-area">
            <canvas id="cc-canvas" width="1080" height="1080"></canvas>
        </div>

        {{-- Properties panel --}}
        @include('campaigncanvas::editor.propsbar')
    </div>
</div>

{{-- Tooltip --}}
@include('campaigncanvas::editor.tooltip')

<script>
// ──────────────────────────────────────────────────────────────────────────────
// CampaignCanvas — lightweight canvas editor
// ──────────────────────────────────────────────────────────────────────────────
(function () {
'use strict';

// ── State ──────────────────────────────────────────────────────────────────
const DOC_UUID    = @json($document?->uuid);
const DOC_NAME    = @json($document?->name ?? __('campaigncanvas::campaigncanvas.untitled'));
const SAVE_URL    = DOC_UUID
    ? `/account/campaign-canvas/documents/${DOC_UUID}`
    : `/account/campaign-canvas/documents`;
const UPLOAD_URL  = '/account/campaign-canvas/upload-image';
const CSRF        = '{{ csrf_token() }}';

let elements   = @json($document?->payload ?? []);
let history    = [JSON.stringify(elements)];
let historyIdx = 0;
let selected   = null;
let dragState  = null;
let autosaveT  = null;
let docUuid    = DOC_UUID;

// ── Canvas setup ───────────────────────────────────────────────────────────
const canvas  = document.getElementById('cc-canvas');
const ctx     = canvas.getContext('2d');

function render() {
    ctx.clearRect(0, 0, canvas.width, canvas.height);
    // White background
    ctx.fillStyle = '#ffffff';
    ctx.fillRect(0, 0, canvas.width, canvas.height);

    const sorted = [...elements].sort((a, b) => (a.z || 0) - (b.z || 0));
    sorted.forEach(el => {
        ctx.save();
        ctx.globalAlpha = el.opacity ?? 1;

        if (el.type === 'rect') {
            ctx.fillStyle = el.color || '#4a90e2';
            ctx.fillRect(el.x, el.y, el.w, el.h);
        } else if (el.type === 'circle') {
            ctx.fillStyle = el.color || '#e24a4a';
            ctx.beginPath();
            ctx.ellipse(el.x + el.w/2, el.y + el.h/2, el.w/2, el.h/2, 0, 0, Math.PI*2);
            ctx.fill();
        } else if (el.type === 'text') {
            ctx.fillStyle = el.color || '#222222';
            ctx.font = `${el.fontSize || 24}px sans-serif`;
            ctx.fillText(el.text || 'Text', el.x, el.y + (el.fontSize || 24));
        } else if (el.type === 'image' && el._img) {
            ctx.drawImage(el._img, el.x, el.y, el.w, el.h);
        }

        // Selection outline
        if (selected && selected.id === el.id) {
            ctx.strokeStyle = '#007bff';
            ctx.lineWidth   = 2;
            ctx.setLineDash([5, 3]);
            ctx.strokeRect(el.x - 2, el.y - 2, el.w + 4, el.h + 4);
            ctx.setLineDash([]);
        }

        ctx.restore();
    });
}

// ── Element helpers ────────────────────────────────────────────────────────
let _eid = Date.now();
function uid() { return 'el_' + (++_eid); }

function makeRect()   { return { id: uid(), type: 'rect',   x: 100, y: 100, w: 200, h: 120, color: '#4a90e2', opacity: 1, z: elements.length }; }
function makeCircle() { return { id: uid(), type: 'circle', x: 150, y: 150, w: 150, h: 150, color: '#e24a4a', opacity: 1, z: elements.length }; }
function makeText()   { return { id: uid(), type: 'text',   x: 100, y: 100, w: 200, h: 40,  color: '#222222', opacity: 1, z: elements.length, text: 'Double-click to edit', fontSize: 24 }; }

function addElement(el) {
    elements.push(el);
    selected = el;
    pushHistory();
    render();
    updatePropsPanel();
}

// ── History ────────────────────────────────────────────────────────────────
function pushHistory() {
    history = history.slice(0, historyIdx + 1);
    history.push(JSON.stringify(elements.map(e => { const c = {...e}; delete c._img; return c; })));
    historyIdx = history.length - 1;
    scheduleAutosave();
}

function restoreHistory(idx) {
    const snap = JSON.parse(history[idx]);
    elements = snap;
    // Reload images
    elements.forEach(el => { if (el.type === 'image' && el.src) loadImage(el); });
    selected = null;
    render();
    updatePropsPanel();
}

window.ccUndo = () => { if (historyIdx > 0) { historyIdx--; restoreHistory(historyIdx); } };
window.ccRedo = () => { if (historyIdx < history.length - 1) { historyIdx++; restoreHistory(historyIdx); } };

// ── Tool actions ───────────────────────────────────────────────────────────
window.ccAddRect   = () => addElement(makeRect());
window.ccAddCircle = () => addElement(makeCircle());
window.ccAddText   = () => addElement(makeText());
window.ccLayerUp   = () => { if (selected) { selected.z = (selected.z ?? 0) + 1; pushHistory(); render(); } };
window.ccLayerDown = () => { if (selected) { selected.z = Math.max(0, (selected.z ?? 0) - 1); pushHistory(); render(); } };
window.ccDeleteSelected = () => {
    if (!selected) return;
    elements = elements.filter(e => e.id !== selected.id);
    selected = null;
    pushHistory();
    render();
    updatePropsPanel();
};

// ── Hit-testing ────────────────────────────────────────────────────────────
function hitTest(mx, my) {
    const sorted = [...elements].sort((a, b) => (b.z || 0) - (a.z || 0));
    for (const el of sorted) {
        if (mx >= el.x && mx <= el.x + el.w && my >= el.y && my <= el.y + el.h) return el;
    }
    return null;
}

// ── Mouse events ───────────────────────────────────────────────────────────
function canvasXY(e) {
    const r = canvas.getBoundingClientRect();
    const scaleX = canvas.width  / r.width;
    const scaleY = canvas.height / r.height;
    return [(e.clientX - r.left) * scaleX, (e.clientY - r.top) * scaleY];
}

canvas.addEventListener('mousedown', e => {
    const [mx, my] = canvasXY(e);
    const hit = hitTest(mx, my);
    selected = hit || null;
    if (hit) {
        dragState = { el: hit, ox: mx - hit.x, oy: my - hit.y };
        canvas.classList.add('dragging');
    }
    render();
    updatePropsPanel();
});

canvas.addEventListener('mousemove', e => {
    if (!dragState) return;
    const [mx, my] = canvasXY(e);
    dragState.el.x = Math.round(mx - dragState.ox);
    dragState.el.y = Math.round(my - dragState.oy);
    render();
    syncPropsPosition();
});

canvas.addEventListener('mouseup', () => {
    if (dragState) { pushHistory(); dragState = null; canvas.classList.remove('dragging'); }
});

canvas.addEventListener('mouseleave', () => {
    if (dragState) { pushHistory(); dragState = null; canvas.classList.remove('dragging'); }
});

// Double-click on text element → inline edit via prompt
canvas.addEventListener('dblclick', e => {
    const [mx, my] = canvasXY(e);
    const hit = hitTest(mx, my);
    if (hit && hit.type === 'text') {
        const newText = prompt('Edit text:', hit.text || '');
        if (newText !== null) { hit.text = newText; pushHistory(); render(); updatePropsPanel(); }
    }
});

// ── Properties panel ───────────────────────────────────────────────────────
function updatePropsPanel() {
    const empty = document.getElementById('cc-props-empty');
    const panel = document.getElementById('cc-props-panel');
    const textSection = document.getElementById('cc-props-text');

    if (!selected) {
        empty.style.display = '';
        panel.style.display = 'none';
        return;
    }
    empty.style.display = 'none';
    panel.style.display = '';
    textSection.style.display = selected.type === 'text' ? '' : 'none';

    document.getElementById('cc-prop-x').value       = Math.round(selected.x);
    document.getElementById('cc-prop-y').value       = Math.round(selected.y);
    document.getElementById('cc-prop-w').value       = Math.round(selected.w);
    document.getElementById('cc-prop-h').value       = Math.round(selected.h);
    document.getElementById('cc-prop-color').value   = selected.color || '#000000';
    document.getElementById('cc-prop-opacity').value = Math.round((selected.opacity ?? 1) * 100);
    document.getElementById('cc-prop-opacity-val').textContent = Math.round((selected.opacity ?? 1) * 100);
    if (selected.type === 'text') {
        document.getElementById('cc-prop-fontsize').value = selected.fontSize || 24;
        document.getElementById('cc-prop-text').value     = selected.text || '';
    }
}

function syncPropsPosition() {
    if (!selected) return;
    document.getElementById('cc-prop-x').value = Math.round(selected.x);
    document.getElementById('cc-prop-y').value = Math.round(selected.y);
}

window.ccApplyProp = (prop, value) => {
    if (!selected) return;
    if (['x','y','w','h','fontSize'].includes(prop)) value = parseFloat(value);
    if (prop === 'opacity') value = parseFloat(value);
    selected[prop] = value;
    pushHistory();
    render();
};

// ── Image upload ───────────────────────────────────────────────────────────
function loadImage(el) {
    const img = new Image();
    img.onload = () => { el._img = img; render(); };
    img.src = el.src;
}

document.getElementById('cc-upload-btn').addEventListener('click', () => {
    document.getElementById('cc-file-input').click();
});

document.getElementById('cc-file-input').addEventListener('change', async e => {
    const file = e.target.files[0];
    if (!file) return;
    const fd = new FormData();
    fd.append('image', file);
    fd.append('_token', '{{ csrf_token() }}');
    const res = await fetch(UPLOAD_URL, { method: 'POST', body: fd });
    const data = await res.json();
    const img  = new Image();
    img.onload = () => {
        const el = {
            id:      uid(),
            type:    'image',
            x:       50,
            y:       50,
            w:       Math.min(img.naturalWidth,  400),
            h:       Math.min(img.naturalHeight, 400),
            opacity: 1,
            z:       elements.length,
            src:     data.url,
            _img:    img,
        };
        addElement(el);
    };
    img.src = data.url;
    e.target.value = '';
});

// ── Save / Autosave ────────────────────────────────────────────────────────
function setStatus(msg) {
    document.getElementById('cc-save-status').textContent = msg;
}

async function doSave() {
    setStatus('{{ __('campaigncanvas::campaigncanvas.saving') }}');
    // Snapshot canvas as preview (small JPEG)
    const tmpCanvas = document.createElement('canvas');
    tmpCanvas.width  = 300;
    tmpCanvas.height = 300;
    const tmpCtx = tmpCanvas.getContext('2d');
    tmpCtx.drawImage(canvas, 0, 0, 300, 300);
    const preview = tmpCanvas.toDataURL('image/jpeg', 0.7);

    const payload = elements.map(e => { const c = {...e}; delete c._img; return c; });
    const body    = JSON.stringify({ name: DOC_NAME, payload, preview });

    try {
        const method = docUuid ? 'PUT' : 'POST';
        const url    = docUuid ? `/account/campaign-canvas/documents/${docUuid}` : '/account/campaign-canvas/documents';
        const res    = await fetch(url, {
            method,
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
            body,
        });
        const data = await res.json();
        if (!docUuid && data.uuid) {
            docUuid = data.uuid;
            window.history.replaceState({}, '', `/account/campaign-canvas/editor/${docUuid}`);
        }
        setStatus('{{ __('campaigncanvas::campaigncanvas.saved') }}');
    } catch (err) {
        setStatus('{{ __('campaigncanvas::campaigncanvas.save_failed') }}');
    }
}

function scheduleAutosave() {
    clearTimeout(autosaveT);
    autosaveT = setTimeout(doSave, 3000);
}

window.ccSave = doSave;

// ── Bootstrap: load existing payload images ────────────────────────────────
elements.forEach(el => { if (el.type === 'image' && el.src) loadImage(el); });
render();
updatePropsPanel();

})();
</script>
@endsection
