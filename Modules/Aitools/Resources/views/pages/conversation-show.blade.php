@extends('layouts.app')

@section('content')
<div class="container py-3">
  <h3 class="mb-3">Titan Zero — Conversation</h3>
  
<div class="card">
  <div class="card-body">
    <div id="aitools-convo-show" data-id="{{ $conversation_id }}"></div>
  </div>
</div>

@push('scripts')
<script>
(async function(){
  const el = document.getElementById('aitools-convo-show');
  if(!el) return;
  const id = el.getAttribute('data-id');
  const res = await fetch(`/account/aitools/chat/${id}`, { headers:{'Accept':'application/json'}});
  const data = await res.json().catch(()=>({success:false}));
  if(!data.success){ el.innerHTML = '<p class="text-danger">Conversation not found.</p>'; return; }
  const msgs = (data.messages||[]).map(m => `
    <div class="mb-3">
      <div class="small text-muted">${m.role}</div>
      <div>${(m.content||'').replace(/[&<>"']/g, c=>({ '&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;' }[c])).replace(/\n/g,'<br>')}</div>
    </div>
  `).join('');
  el.innerHTML = `<div class="mb-2 text-muted">Conversation #${id}</div>${msgs || '<div class="text-muted">No messages.</div>'}`;
})();
</script>
@endpush

</div>
@endsection
