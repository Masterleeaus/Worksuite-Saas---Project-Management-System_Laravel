@extends('layouts.app')

@section('content')
<div class="container-fluid">
  <div class="d-flex justify-content-between align-items-start mb-3">
    <div>
      <div class="d-flex align-items-center gap-2">
        <h4 class="mb-0">{{ $campaign->name }}</h4>
        <span class="badge bg-secondary">{{ ucfirst($campaign->status) }}</span>
      </div>
      <div class="mt-2">
        @php
  $tzIntent = 'draft_campaign_copy';
  $tzHeroKey = 'comms';
@endphp
@if (\Illuminate\Support\Facades\Gate::allows('titanzero.use'))
  @if (\Illuminate\Support\Facades\View::exists('titanzero::partials.ask_button'))
    @include('titanzero::partials.ask_button', [
      'heroKey' => $tzHeroKey,
      'intent' => $tzIntent,
      'return_url' => url()->current(),
      'page' => ['route_name' => request()->route()?->getName(), 'url' => url()->current()],
      'record' => ['record_type' => 'campaign', 'record_id' => $campaign->id],
      'fields' => [],
    ])
  @elseif (\Illuminate\Support\Facades\Route::has('titan.zero.index'))
    <a href="{{ route('titan.zero.index') }}" class="btn btn-outline-primary btn-sm">
      <i class="ti ti-sparkles"></i> Ask Titan Zero
    </a>
  @endif
@endif

      </div>
      <div class="text-muted mt-2">{{ $campaign->description ?: '—' }}</div>
    </div>

    <div class="d-flex gap-2">
      <a href="{{ route('customerconnect.campaigns.preview', $campaign) }}" class="btn btn-outline-secondary">Preview</a>
      <a href="{{ route('customerconnect.campaigns.steps.index', $campaign) }}" class="btn btn-outline-primary">Steps</a>

      @if ($campaign->status !== 'active')
        <form method="POST" action="{{ route('customerconnect.campaigns.activate', $campaign) }}">
          @csrf
          <button class="btn btn-success">Activate</button>
        </form>
      @else
        <form method="POST" action="{{ route('customerconnect.campaigns.pause', $campaign) }}">
          @csrf
          <button class="btn btn-warning">Pause</button>
        </form>
      @endif
    </div>
  </div>

  <div class="row g-3">
    <div class="col-lg-6">
      <div class="card">
        <div class="card-body">
          <h5 class="mb-3">Steps</h5>
          @if ($campaign->steps->count() === 0)
            <div class="text-muted">No steps yet.</div>
          @else
            <div class="table-responsive">
              <table class="table table-sm">
                <thead>
                  <tr>
                    <th>#</th>
                    <th>Type</th>
                    <th>Channel</th>
                    <th>Delay</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($campaign->steps as $step)
                    <tr>
                      <td>{{ $loop->iteration }}</td>
                      <td>{{ $step->step_type }}</td>
                      <td>{{ $step->channel ?? '—' }}</td>
                      <td>{{ $step->delay_minutes ? $step->delay_minutes.' mins' : '—' }}</td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          @endif
        </div>
      </div>
    </div>

    <div class="col-lg-6">
      <div class="card">
        <div class="card-body">
          <h5 class="mb-3">Runs</h5>
          @if ($campaign->runs->count() === 0)
            <div class="text-muted">No runs created yet.</div>
          @else
            <div class="table-responsive">
              <table class="table table-sm">
                <thead>
                  <tr>
                    <th>ID</th>
                    <th>Status</th>
                    <th>Created</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($campaign->runs->take(10) as $run)
                    <tr>
                      <td>
                        <a href="{{ route('customerconnect.runs.show', $run) }}">#{{ $run->id }}</a>
                      </td>
                      <td>{{ $run->status }}</td>
                      <td>{{ $run->created_at?->diffForHumans() }}</td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          @endif

          <form method="POST" action="{{ route('customerconnect.runs.build', $campaign) }}" class="mt-3">
            @csrf
            <button class="btn btn-primary">Build Run + Deliveries</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
