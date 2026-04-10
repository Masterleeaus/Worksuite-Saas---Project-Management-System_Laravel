@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <div class="row mb-3">
        <div class="col-12">
            <h4>{{ isset($campaign) ? 'Edit Campaign' : 'Create Campaign' }}</h4>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ isset($campaign) ? route('titanreach.campaigns.update', $campaign->id) : route('titanreach.campaigns.store') }}">
                @csrf

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
                    </div>
                @endif

                <div class="form-group">
                    <label>Name</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name', $campaign->name ?? '') }}" required>
                </div>

                <div class="form-group">
                    <label>Channel</label>
                    <select name="channel" class="form-control" required>
                        @foreach(['whatsapp','sms','telegram','call','multi'] as $ch)
                            <option value="{{ $ch }}" @selected(old('channel', $campaign->channel ?? '') === $ch)>{{ ucfirst($ch) }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label>Audience Type</label>
                    <select name="audience_type" class="form-control" required>
                        @foreach(['contact_list','segment','manual'] as $at)
                            <option value="{{ $at }}" @selected(old('audience_type', $campaign->audience_type ?? '') === $at)>{{ ucfirst(str_replace('_', ' ', $at)) }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label>Audience ID (Contact List or Segment ID)</label>
                    <input type="number" name="audience_id" class="form-control" value="{{ old('audience_id', $campaign->audience_id ?? '') }}">
                </div>

                <div class="form-group">
                    <label>Content / Message</label>
                    <textarea name="content" class="form-control" rows="4">{{ old('content', $campaign->content ?? '') }}</textarea>
                </div>

                <div class="form-group">
                    <label>Call Script (for call campaigns)</label>
                    <textarea name="call_script" class="form-control" rows="3">{{ old('call_script', $campaign->call_script ?? '') }}</textarea>
                </div>

                <div class="form-group">
                    <label>Schedule At</label>
                    <input type="datetime-local" name="scheduled_at" class="form-control" value="{{ old('scheduled_at', isset($campaign->scheduled_at) ? $campaign->scheduled_at->format('Y-m-d\TH:i') : '') }}">
                </div>

                <button type="submit" class="btn btn-primary">{{ isset($campaign) ? 'Update' : 'Create' }} Campaign</button>
                <a href="{{ route('titanreach.campaigns.index') }}" class="btn btn-secondary ml-2">Cancel</a>
            </form>
        </div>
    </div>
</div>
@endsection
