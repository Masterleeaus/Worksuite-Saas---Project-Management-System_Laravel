@extends('communication::layouts.master')

@section('communication_content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2 class="mb-0">
        <i class="bi bi-plus-circle me-2"></i>Create Automation Rule
    </h2>
    <a href="{{ route('communications.automations.index') }}" class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-arrow-left me-1"></i>Back to Automations
    </a>
</div>

<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('communications.automations.store') }}">
            @csrf

            <div class="mb-3 row">
                <label class="col-sm-3 col-form-label">Rule Name <span class="text-danger">*</span></label>
                <div class="col-sm-9">
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required>
                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>

            <div class="mb-3 row">
                <label class="col-sm-3 col-form-label">Trigger Event <span class="text-danger">*</span></label>
                <div class="col-sm-9">
                    <select name="trigger_event" class="form-select @error('trigger_event') is-invalid @enderror">
                        @foreach($triggerEvents as $key => $label)
                            <option value="{{ $key }}" {{ old('trigger_event') === $key ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('trigger_event')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>

            <div class="mb-3 row">
                <label class="col-sm-3 col-form-label">Template</label>
                <div class="col-sm-9">
                    <select name="template_id" class="form-select @error('template_id') is-invalid @enderror">
                        <option value="">— No template (compose manually) —</option>
                        @foreach($templates as $tpl)
                            <option value="{{ $tpl->id }}" {{ old('template_id') == $tpl->id ? 'selected' : '' }}>{{ $tpl->name }} ({{ ucfirst($tpl->type) }})</option>
                        @endforeach
                    </select>
                    @error('template_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>

            <div class="mb-3 row">
                <label class="col-sm-3 col-form-label">Recipient</label>
                <div class="col-sm-4">
                    <select name="recipient_type" class="form-select @error('recipient_type') is-invalid @enderror">
                        @foreach($recipientTypes as $key => $label)
                            <option value="{{ $key }}" {{ old('recipient_type', 'customer') === $key ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('recipient_type')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>

            <div class="mb-3 row">
                <label class="col-sm-3 col-form-label">Delay (minutes)</label>
                <div class="col-sm-3">
                    <input type="number" name="delay_minutes" class="form-control @error('delay_minutes') is-invalid @enderror" value="{{ old('delay_minutes', 0) }}" min="0">
                    @error('delay_minutes')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    <small class="form-text text-muted">0 = send immediately after trigger.</small>
                </div>
            </div>

            <div class="mb-3 row">
                <label class="col-sm-3 col-form-label">Channel Override</label>
                <div class="col-sm-4">
                    <select name="channel" class="form-select @error('channel') is-invalid @enderror">
                        <option value="">— Use template's channel —</option>
                        <option value="email" {{ old('channel') === 'email' ? 'selected' : '' }}>Email</option>
                        <option value="sms" {{ old('channel') === 'sms' ? 'selected' : '' }}>SMS</option>
                        <option value="chat" {{ old('channel') === 'chat' ? 'selected' : '' }}>Chat</option>
                        <option value="push" {{ old('channel') === 'push' ? 'selected' : '' }}>Push</option>
                    </select>
                    @error('channel')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>

            <div class="row">
                <div class="col-sm-9 offset-sm-3">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save me-1"></i>Save Rule
                    </button>
                    <a href="{{ route('communications.automations.index') }}" class="btn btn-outline-secondary ms-2">Cancel</a>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
