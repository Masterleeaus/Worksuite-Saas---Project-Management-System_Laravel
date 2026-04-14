@extends('layouts.app')
@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">Preview: {{ $campaign->name }}</h4>
        <a href="{{ route('customerconnect.campaigns.show', $campaign) }}" class="btn btn-outline-secondary">Back</a>
    </div>

    <div class="card">
        <div class="card-body">
            @foreach($campaign->steps as $step)
                <div class="border rounded p-3 mb-3">
                    <div class="d-flex justify-content-between">
                        <div>
                            <strong>Step #{{ $step->position }}</strong> — {{ strtoupper($step->type) }} @if($step->channel) ({{ $step->channel }}) @endif
                        </div>
                        <div class="text-muted">Delay: {{ (int)$step->delay_minutes }} min</div>
                    </div>
                    @if($step->subject)
                        <div class="mt-2"><strong>Subject:</strong> {{ $step->subject }}</div>
                    @endif
                    @if($step->body)
                        <div class="mt-2"><strong>Body:</strong></div>
                        <pre class="mb-0" style="white-space: pre-wrap;">{{ $step->body }}</pre>
                    @endif
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
