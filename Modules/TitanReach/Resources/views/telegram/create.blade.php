@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <div class="row mb-3">
        <div class="col-12 d-flex justify-content-between align-items-center">
            <h4>Add Telegram Bot</h4>
            <a href="{{ route('titanreach.telegram.index') }}" class="btn btn-outline-secondary">← Back</a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <form method="POST" action="{{ route('titanreach.telegram.store') }}">
                        @csrf

                        <div class="form-group">
                            <label>Bot Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                   value="{{ old('name') }}" required>
                            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="form-group">
                            <label>Bot Token <span class="text-danger">*</span>
                                <small class="text-muted">(from @BotFather)</small>
                            </label>
                            <input type="password" name="bot_token" class="form-control @error('bot_token') is-invalid @enderror"
                                   value="{{ old('bot_token') }}" required>
                            @error('bot_token')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="form-group">
                            <label>Webhook URL <small class="text-muted">(optional)</small></label>
                            <input type="url" name="webhook_url" class="form-control @error('webhook_url') is-invalid @enderror"
                                   value="{{ old('webhook_url') }}"
                                   placeholder="{{ url('api/titanreach/webhooks/telegram/inbound') }}">
                            <small class="form-text text-muted">
                                Telegram will POST incoming messages to this URL.
                            </small>
                            @error('webhook_url')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <button type="submit" class="btn btn-primary w-100">Save Bot</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
