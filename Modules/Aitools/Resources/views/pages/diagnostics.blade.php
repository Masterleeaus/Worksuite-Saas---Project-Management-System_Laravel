@extends('layouts.app')

@section('content')
<div class="container py-3">
  <h3 class="mb-3">Titan Zero — Diagnostics</h3>
  
<div class="card"><div class="card-body">
  <div id="aitools-diag"></div>
</div></div>

@push('scripts')
<script>
(async function(){
  const el = document.getElementById('aitools-diag');
  if(!el) return;
  const res = await fetch('/account/aitools/health', { headers:{'Accept':'application/json'}});
  const data = await res.json().catch(()=>({success:false}));
  el.innerHTML = `<pre style="white-space:pre-wrap;">${JSON.stringify(data, null, 2)}</pre>`;
})();
</script>
@endpush

</div>
@endsection
