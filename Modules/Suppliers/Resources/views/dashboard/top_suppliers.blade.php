<div class="card">
  <div class="card-body">
    <h5 class="card-title">Top Suppliers</h5>
    <ul class="list-unstyled mb-0">
      @foreach(($topSuppliers ?? []) as $s)
        <li>
          <strong>{{ $s->name }}</strong>
          <span class="text-muted"> (⭐ {{ $s->fsm_rating ?? '-' }})</span>
          <small class="d-block">Lead: {{ $s->fsm_lead_time_days ?? '—' }} days • Terms: {{ $s->fsm_payment_terms ?? '—' }}</small>
        </li>
      @endforeach
    </ul>
  </div>
</div>
