@extends('layouts.app')

@section('content')
<div class="container py-3">
  <h3 class="mb-3">Titan Zero — Conversations</h3>
  
<div class="card">
  <div class="card-body">
    <div id="aitools-convos"></div>
  </div>
</div>

@push('scripts')
<script>
(async function(){
  const el = document.getElementById('aitools-convos');
  if(!el) return;
  const res = await fetch('/account/aitools/conversations', { headers:{'Accept':'application/json'}});
  const data = await res.json().catch(()=>({success:false}));
  if(!data.success){ el.innerHTML = '<p class="text-danger">Unable to load conversations.</p>'; return; }
  const rows = (data.conversations||[]).map(c => `
    <tr>
      <td><a href="/account/aitools/conversations/${c.id}">#${c.id}</a></td>
      <td>${(c.title||'').replace(/</g,'&lt;')}</td>
      <td>${(c.created_at||'')}</td>
    </tr>
  `).join('');
  el.innerHTML = `
    <table class="table table-sm">
      <thead><tr><th>ID</th><th>Title</th><th>Created</th></tr></thead>
      <tbody>${rows || '<tr><td colspan="3" class="text-muted">No conversations yet.</td></tr>'}</tbody>
    </table>
  `;
})();
</script>
@endpush

</div>
@endsection
