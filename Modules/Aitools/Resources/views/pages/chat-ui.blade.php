@extends('layouts.app')

@section('content')
<div class="container py-3">
  <h3 class="mb-3">Titan Zero</h3>
  
<div class="card">
  <div class="card-body">
    <p class="text-muted mb-2">Use the floating assistant (bottom-right), or chat below.</p>
    <div id="aitools-full-chat" data-conversation-id=""></div>
  </div>
</div>

@push('scripts')
<script>
(function(){
  const root = document.getElementById('aitools-full-chat');
  if(!root) return;

  const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
  let conversationId = localStorage.getItem('aitools_conversation_id') || '';

  function esc(s){ return (s||'').replace(/[&<>"']/g, c=>({ '&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;' }[c])); }

  root.innerHTML = `
    <div class="d-flex gap-2 mb-2">
      <input class="form-control" id="aitools-msg" placeholder="Ask Titan Zero…" />
      <button class="btn btn-primary" id="aitools-send">Send</button>
      <button class="btn btn-outline-secondary" id="aitools-new">New</button>
    </div>
    <div class="border rounded p-2" style="height: 420px; overflow:auto;" id="aitools-log"></div>
  `;

  const log = root.querySelector('#aitools-log');
  const input = root.querySelector('#aitools-msg');

  function append(role, content){
    const div = document.createElement('div');
    div.className = 'mb-2';
    div.innerHTML = `<div class="small text-muted">${esc(role)}</div><div>${esc(content).replace(/\n/g,'<br>')}</div>`;
    log.appendChild(div);
    log.scrollTop = log.scrollHeight;
  }

  async function loadHistory(){
    if(!conversationId) return;
    const res = await fetch(`/account/aitools/chat/${conversationId}`, { headers: { 'Accept':'application/json' }});
    if(!res.ok) return;
    const data = await res.json();
    if(!data.success) return;
    log.innerHTML = '';
    (data.messages||[]).forEach(m => append(m.role, m.content));
  }

  async function send(){
    const msg = input.value.trim();
    if(!msg) return;
    append('user', msg);
    input.value = '';
    const res = await fetch('/account/aitools/chat', {
      method:'POST',
      headers:{
        'Content-Type':'application/json',
        'Accept':'application/json',
        'X-CSRF-TOKEN': csrf
      },
      body: JSON.stringify({ message: msg, conversation_id: conversationId ? parseInt(conversationId,10) : null })
    });
    const data = await res.json().catch(()=>({success:false,message:'Bad response'}));
    if(data.success){
      conversationId = String(data.conversation_id || '');
      if(conversationId) localStorage.setItem('aitools_conversation_id', conversationId);
      append('assistant', data.reply || '');
    } else {
      append('error', data.message || 'Request failed');
    }
  }

  root.querySelector('#aitools-send').addEventListener('click', send);
  input.addEventListener('keydown', (e)=>{ if(e.key==='Enter') send(); });
  root.querySelector('#aitools-new').addEventListener('click', ()=>{
    conversationId = '';
    localStorage.removeItem('aitools_conversation_id');
    log.innerHTML = '';
  });

  loadHistory();
})();
</script>
@endpush

</div>
@endsection
