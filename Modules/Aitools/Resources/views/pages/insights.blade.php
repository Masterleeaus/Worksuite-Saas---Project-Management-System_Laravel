@extends('layouts.app')

@section('content')
<div class="container py-3">
  <h3 class="mb-3">Titan Zero — Insights</h3>
  
<div class="card"><div class="card-body">
  <p class="text-muted">Insights are generated from recent signals and pulse summaries.</p>
  <div class="d-flex gap-2 mb-3">
    <a class="btn btn-outline-primary btn-sm" href="/account/aitools/pulse">Pulse</a>
    <a class="btn btn-outline-primary btn-sm" href="/account/aitools/signals">Signals</a>
  </div>
  <div class="border rounded p-2">
    <div class="small text-muted mb-1">Try:</div>
    <code>/tool get_business_pulse {"hours":24}</code>
  </div>
</div></div>

</div>
@endsection
