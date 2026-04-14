@extends('layouts.app')

@section('content')
<div class="row">
  <div class="col-12">
    <x-alert type="info">
      <div class="d-flex justify-content-between align-items-center">
        <div>
          <strong>Thread Events</strong>
          <div class="small text-muted">Thread #{{ $threadId }}</div>
        </div>
        <div>
          <a class="btn btn-sm btn-outline-secondary" href="{{ route('customerconnect.inbox.thread', $threadId) }}">Back to thread</a>
        </div>
      </div>
    </x-alert>

    <div class="card mb-3">
      <div class="card-body">
        <div class="d-flex flex-wrap gap-3">
          <div>
            <div class="small text-muted">First response</div>
            <div class="fw-semibold">
              @if(!is_null($sla['first_response_seconds']))
                {{ gmdate('H:i:s', $sla['first_response_seconds']) }}
              @else
                —
              @endif
            </div>
          </div>
          <div>
            <div class="small text-muted">Awaiting response</div>
            <div class="fw-semibold">
              @if(!is_null($sla['awaiting_response_seconds']))
                {{ gmdate('H:i:s', $sla['awaiting_response_seconds']) }}
              @else
                —
              @endif
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="card">
      <div class="card-header d-flex justify-content-between align-items-center">
        <span class="fw-semibold">Event timeline</span>
        <span class="small text-muted">{{ $events->total() }} events</span>
      </div>
      <div class="card-body p-0">
        <table class="table mb-0">
          <thead>
            <tr>
              <th style="width: 160px;">Time</th>
              <th style="width: 90px;">Direction</th>
              <th style="width: 120px;">Event</th>
              <th>Provider</th>
              <th style="width: 220px;">Provider ID</th>
              <th>Details</th>
            </tr>
          </thead>
          <tbody>
            @foreach($events as $e)
              <tr>
                <td class="small text-muted">{{ \Carbon\Carbon::parse($e->created_at)->format('Y-m-d H:i:s') }}</td>
                <td><span class="badge bg-{{ $e->direction === 'inbound' ? 'secondary' : 'primary' }}">{{ $e->direction }}</span></td>
                <td><span class="badge bg-light text-dark">{{ $e->event_type }}</span></td>
                <td class="small">{{ $e->provider ?? '—' }}</td>
                <td class="small text-monospace">{{ $e->provider_message_id ?? '—' }}</td>
                <td class="small text-muted">
                  @if($e->payload_json)
                    <details>
                      <summary>payload</summary>
                      <pre class="m-0 p-2 bg-light" style="max-width: 520px; white-space: pre-wrap;">{{ $e->payload_json }}</pre>
                    </details>
                  @else
                    —
                  @endif
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
      <div class="card-footer">
        {{ $events->links() }}
      </div>
    </div>
  </div>
</div>
@endsection
