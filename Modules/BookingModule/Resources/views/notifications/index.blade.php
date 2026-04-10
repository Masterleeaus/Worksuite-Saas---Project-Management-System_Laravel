@extends('layouts.app')

@section('content')
<div class="container">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h3>@lang('bookingmodule::notifications.title')</h3>
    <form method="GET" class="form-inline">
      <input name="q" value="{{ request('q') }}" class="form-control mr-2" placeholder="@lang('bookingmodule::notifications.search')" />
      <button class="btn btn-outline-secondary">@lang('app.search')</button>
    </form>
  </div>

  <div class="card">
    <div class="card-body p-0">
      <table class="table mb-0">
        <thead>
          <tr>
            <th>@lang('bookingmodule::notifications.event')</th>
            <th>@lang('bookingmodule::notifications.message')</th>
            <th>@lang('bookingmodule::notifications.sent')</th>
            <th>@lang('bookingmodule::notifications.status')</th>
            <th></th>
          </tr>
        </thead>
        <tbody>
          @forelse($rows as $row)
            <tr>
              <td>{{ $row->event }}</td>
              <td>
                <div>{{ $row->title ?? '' }}</div>
                <div class="text-muted">{{ $row->message }}</div>
              </td>
              <td>{{ optional($row->sent_at)->format('Y-m-d H:i') }}</td>
              <td>
                @if($row->read_at)
                  <span class="badge badge-success">Read</span>
                @else
                  <span class="badge badge-warning">Unread</span>
                @endif
              </td>
              <td>
                @if(!$row->read_at)
                <form method="POST" action="{{ route('appointment.notifications.read', $row->id) }}">
                  @csrf
                  <button class="btn btn-sm btn-primary">@lang('bookingmodule::notifications.mark_read')</button>
                </form>
                @endif
              </td>
            </tr>
          @empty
            <tr><td colspan="5" class="text-center p-4">@lang('bookingmodule::notifications.empty')</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>

  <div class="mt-3">
    {{ $rows->links() }}
  </div>
</div>
@endsection
