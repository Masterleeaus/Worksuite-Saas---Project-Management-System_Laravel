@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <div class="row mb-3">
        <div class="col-12 d-flex justify-content-between align-items-center">
            <h4>{{ isset($campaign) ? 'Edit' : 'New' }} Call Campaign</h4>
            <a href="{{ route('titanreach.calls.index') }}" class="btn btn-outline-secondary">← Back</a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-7">
            <div class="card">
                <div class="card-body">
                    @php
                        $action = isset($campaign)
                            ? route('titanreach.calls.update', $campaign->id)
                            : route('titanreach.calls.store');
                    @endphp
                    <form method="POST" action="{{ $action }}">
                        @csrf

                        <div class="form-group">
                            <label>Campaign Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                   value="{{ old('name', $campaign->name ?? '') }}" required>
                            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="form-group">
                            <label>Audience Type <span class="text-danger">*</span></label>
                            <select name="audience_type" class="form-control @error('audience_type') is-invalid @enderror" required>
                                @foreach(['contact_list' => 'Contact List', 'segment' => 'Segment', 'manual' => 'Manual'] as $val => $label)
                                    <option value="{{ $val }}" @selected(old('audience_type', $campaign->audience_type ?? '') === $val)>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                            @error('audience_type')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="form-group">
                            <label>Audience ID <small class="text-muted">(list or segment ID)</small></label>
                            <input type="number" name="audience_id" class="form-control @error('audience_id') is-invalid @enderror"
                                   value="{{ old('audience_id', $campaign->audience_id ?? '') }}">
                            @error('audience_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="form-group">
                            <label>Call Script / Message</label>
                            <textarea name="call_script" class="form-control @error('call_script') is-invalid @enderror"
                                      rows="5" placeholder="Hello, this is a message from…">{{ old('call_script', $campaign->call_script ?? '') }}</textarea>
                            @error('call_script')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="form-group">
                            <label>Scheduled At <small class="text-muted">(optional)</small></label>
                            <input type="datetime-local" name="scheduled_at" class="form-control @error('scheduled_at') is-invalid @enderror"
                                   value="{{ old('scheduled_at', isset($campaign->scheduled_at) ? $campaign->scheduled_at->format('Y-m-d\TH:i') : '') }}">
                            @error('scheduled_at')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <button type="submit" class="btn btn-primary w-100">
                            {{ isset($campaign) ? 'Update Campaign' : 'Create Campaign' }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
