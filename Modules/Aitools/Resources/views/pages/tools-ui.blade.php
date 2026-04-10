@extends('layouts.app')

@section('content')
<div class="container py-3">
  <h3 class="mb-3">Titan Zero — Tools</h3>
  
<div class="card">
  <div class="card-body">
    <p class="text-muted">Browse registered tools and their schemas.</p>
    <div id="aitools-tools-ui"></div>
  </div>
</div>

@push('scripts')
<script>
(async function(){
  const el = document.getElementById('aitools-tools-ui');
  if(!el) return;
  const res = await fetch('/account/aitools/tools', { headers:{'Accept':'application/json'}});
  const data = await res.json().catch(()=>({success:false}));
  if(!data.success){ el.innerHTML = '<p class="text-danger">Unable to load tools.</p>'; return; }
  const tools = data.tools || [];
  el.innerHTML = tools.map(t => `
    <div class="border rounded p-2 mb-2">
      <div class="fw-bold">${t.name}</div>
      <div class="text-muted small mb-2">${t.description||''}</div>
      <details>
        <summary>Schema</summary>
        <pre class="mt-2 mb-0" style="white-space:pre-wrap;">${JSON.stringify(t.schema||{}, null, 2)}</pre>
      </details>
      <div class="mt-2">
        <code>/tool ${t.name} {}</code>
      </div>
    </div>
  `).join('') || '<p class="text-muted">No tools registered.</p>';
})();
</script>
@endpush

</div>
@endsection
