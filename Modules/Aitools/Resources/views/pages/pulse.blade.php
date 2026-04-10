@extends('layouts.app')

@section('content')
<div class="container py-3">
  <h3 class="mb-3">Titan Zero — Pulse</h3>
  
<div class="card"><div class="card-body">
  <div id="aitools-pulse"></div>
</div></div>

@push('scripts')
<script>
(async function(){
  const el = document.getElementById('aitools-pulse');
  if(!el) return;
  const res = await fetch('/account/aitools/tools/get_business_pulse', {
    method:'POST',
    headers:{'Content-Type':'application/json','Accept':'application/json','X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''},
    body: JSON.stringify({ args: { hours: 24 } })
  });
  const data = await res.json().catch(()=>({success:false}));
  if(!data.success){ el.innerHTML='<p class="text-danger">Unable to load pulse.</p>'; return; }
  el.innerHTML = `<pre style="white-space:pre-wrap;">${JSON.stringify(data.result||data, null, 2)}</pre>`;
})();
</script>
@endpush

</div>
@endsection
