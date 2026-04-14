@extends('layouts.app')

@section('content')
<div class="container-fluid">
  <div class="d-flex align-items-center justify-content-between mb-3">
    <h4 class="mb-0">Customer Connect — Inbox</h4>
    @if(isset($savedFilters) && $savedFilters->count())
      <div class="dropdown">
        <button class="btn btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown">Saved filters</button>
        <div class="dropdown-menu dropdown-menu-end">
          @foreach($savedFilters as $f)
            <a class="dropdown-item" href="{{ request()->fullUrlWithQuery($f->criteria ?? []) }}">{{ $f->name }}</a>
          @endforeach
        </div>
      </div>
    @endif
  </div>

  <form method="GET" class="card mb-3">
    <div class="card-body">
      <div class="row g-2">
        <div class="col-md-6">
          <input name="q" value="{{ $q ?? '' }}" class="form-control" placeholder="Search customer, phone, email, subject, preview...">
        </div>
        <div class="col-md-2">
          <select name="status" class="form-select">
            <option value="">All statuses</option>
            <option value="open" @selected(request('status')==='open')>Open</option>
            <option value="pending" @selected(request('status')==='pending')>Pending</option>
            <option value="closed" @selected(request('status')==='closed')>Closed</option>
          </select>
        </div>
        <div class="col-md-2">
          <select name="channel" class="form-select">
            <option value="">All channels</option>
            <option value="email" @selected(request('channel')==='email')>Email</option>
            <option value="sms" @selected(request('channel')==='sms')>SMS</option>
            <option value="whatsapp" @selected(request('channel')==='whatsapp')>WhatsApp</option>
            <option value="telegram" @selected(request('channel')==='telegram')>Telegram</option>
          </select>
        </div>
        <div class="col-md-2 d-grid">
          <button class="btn btn-primary">Filter</button>
        </div>
      </div>
    </div>
  </form>

  <form method="POST" action="{{ route('customerconnect.inbox.bulk') }}" class="card">
    @csrf
    <div class="card-header d-flex gap-2 align-items-center">
      <select name="action" class="form-select w-auto">
        <option value="close">Close</option>
        <option value="open">Reopen</option>
        <option value="assign">Assign</option>
        <option value="tag">Add tag</option>
      </select>
      <input type="number" name="assigned_to_user_id" class="form-control w-auto" placeholder="Assignee user_id">
      <input type="number" name="tag_id" class="form-control w-auto" placeholder="Tag id">
      <button class="btn btn-outline-primary">Apply</button>

      <div class="ms-auto small text-muted">
        {{ $threads->total() }} threads
      </div>
    </div>

    <div class="table-responsive">
      <table class="table table-hover mb-0 align-middle">
        <thead>
          <tr>
            <th style="width:40px;"></th>
            <th>Customer</th>
            <th>Channel</th>
            <th>Status</th>
            <th>Last message</th>
            <th class="text-end">Updated</th>
          </tr>
        </thead>
        <tbody>
          @forelse($threads as $t)
            <tr>
              <td><input type="checkbox" name="thread_ids[]" value="{{ $t->id }}"></td>
              <td>
                <div class="fw-semibold">{{ $t->contact->display_name ?? 'Unknown' }}</div>
                <div class="small text-muted">{{ $t->contact->phone_e164 ?? $t->contact->email ?? '' }}</div>
              </td>
              <td><span class="badge bg-secondary">{{ strtoupper($t->channel) }}</span></td>
              <td><span class="badge bg-light text-dark">{{ ucfirst($t->status) }}</span></td>
              <td>
                <a href="{{ route('customerconnect.inbox.thread', $t->id) }}" class="text-decoration-none">
                  {{ \Illuminate\Support\Str::limit($t->last_message_preview ?? '', 90) }}
                </a>
              </td>
              <td class="text-end small text-muted">{{ optional($t->last_message_at)->diffForHumans() }}</td>
            </tr>
          @empty
            <tr><td colspan="6" class="text-center py-4 text-muted">No threads.</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>

    <div class="card-footer">
      {{ $threads->links() }}
    </div>
  </form>

  <form method="POST" action="{{ route('customerconnect.filters.store') }}" class="mt-3">
    @csrf
    <input type="hidden" name="criteria[q]" value="{{ request('q') }}">
    <input type="hidden" name="criteria[status]" value="{{ request('status') }}">
    <input type="hidden" name="criteria[channel]" value="{{ request('channel') }}">
    <div class="d-flex gap-2">
      <input name="name" class="form-control w-auto" placeholder="Save current filter as...">
      <button class="btn btn-outline-secondary">Save filter</button>
    </div>
  </form>
</div>
@endsection
