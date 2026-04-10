@extends('layouts.app')
@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h4 class="mb-0">Steps — {{ $campaign->name }}</h4>
            <div class="text-muted small">Reorder by submitting the list in desired order.</div>
        </div>
        <a href="{{ route('customerconnect.campaigns.show', $campaign) }}" class="btn btn-outline-secondary">Back</a>
    </div>

    <div class="row">
        <div class="col-lg-7">
            <div class="card mb-3">
                <div class="card-header">Existing Steps</div>
                <div class="card-body">
                    @if($campaign->steps->count() === 0)
                        <div class="text-muted">No steps yet.</div>
                    @else
                        <form method="POST" action="{{ route('customerconnect.campaigns.steps.reorder', $campaign) }}">
                            @csrf
                            <div class="mb-2 text-muted small">Order field expects a comma-separated list of step IDs.</div>
                            <input type="text" name="order_csv" class="form-control" value="{{ $campaign->steps->pluck('id')->implode(',') }}">
                            <button class="btn btn-outline-primary mt-2" type="submit"
                                onclick="event.preventDefault(); var v=document.querySelector('[name=order_csv]').value.split(',').map(x=>parseInt(x.trim())).filter(Boolean); var f=this.closest('form'); var inp=document.createElement('input'); inp.type='hidden'; inp.name='order'; inp.value=''; f.appendChild(inp); v.forEach(function(id){var h=document.createElement('input');h.type='hidden';h.name='order[]';h.value=id;f.appendChild(h);}); f.submit();">
                                Save Order
                            </button>
                        </form>

                        <hr>

                        <ul class="list-group">
                            @foreach($campaign->steps as $step)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <div>
                                        <div><strong>#{{ $step->position }}</strong> {{ strtoupper($step->type) }} @if($step->channel) — {{ $step->channel }} @endif</div>
                                        <div class="text-muted small">Step ID: {{ $step->id }} | Delay: {{ (int)$step->delay_minutes }} min</div>
                                    </div>
                                    <div class="d-flex gap-2">
                                        <a class="btn btn-sm btn-outline-secondary" href="{{ route('customerconnect.campaigns.steps.edit', [$campaign, $step]) }}">Edit</a>
                                        <form method="POST" action="{{ route('customerconnect.campaigns.steps.destroy', [$campaign, $step]) }}">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete step?')">Delete</button>
                                        </form>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-5">
            <div class="card">
                <div class="card-header">Add Step</div>
                <div class="card-body">
                    <form method="POST" action="{{ route('customerconnect.campaigns.steps.store', $campaign) }}">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Type</label>
                            <select class="form-control" name="type">
                                @foreach($types as $t)
                                    <option value="{{ $t }}">{{ $t }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Channel (for send steps)</label>
                            <select class="form-control" name="channel">
                                <option value="">—</option>
                                @foreach($channels as $c)
                                    <option value="{{ $c }}">{{ $c }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Delay minutes</label>
                            <input type="number" name="delay_minutes" class="form-control" value="0" min="0">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Subject (email only)</label>
                            <input type="text" name="subject" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Body</label>
                            <textarea name="body" rows="6" class="form-control"></textarea>
                        </div>
                        <button class="btn btn-primary">Add Step</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
