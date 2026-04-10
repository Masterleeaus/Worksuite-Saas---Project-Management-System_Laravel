@extends('layouts.app')
@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">Edit Step #{{ $step->position }} — {{ $campaign->name }}</h4>
        <a href="{{ route('customerconnect.campaigns.steps.index', $campaign) }}" class="btn btn-outline-secondary">Back</a>
    </div>

    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('customerconnect.campaigns.steps.update', [$campaign, $step]) }}">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Position</label>
                        <input type="number" name="position" class="form-control" value="{{ $step->position }}">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Type</label>
                        <select class="form-control" name="type">
                            @foreach($types as $t)
                                <option value="{{ $t }}" @if($step->type === $t) selected @endif>{{ $t }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Channel</label>
                        <select class="form-control" name="channel">
                            <option value="">—</option>
                            @foreach($channels as $c)
                                <option value="{{ $c }}" @if($step->channel === $c) selected @endif>{{ $c }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Delay minutes</label>
                        <input type="number" name="delay_minutes" class="form-control" value="{{ (int)$step->delay_minutes }}" min="0">
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Subject</label>
                    <input type="text" name="subject" class="form-control" value="{{ $step->subject }}">
                </div>

                <div class="mb-3">
                    <label class="form-label">Body</label>
                    <textarea name="body" rows="8" class="form-control">{{ $step->body }}</textarea>
                </div>

                <button class="btn btn-primary">Save Step</button>
            </form>
        </div>
    </div>
</div>
@endsection
