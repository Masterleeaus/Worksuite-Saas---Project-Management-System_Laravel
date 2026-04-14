@extends('communication::layouts.master')

@section('communication_content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2 class="mb-0">
        <i class="bi bi-pencil-square me-2"></i>Compose Message
    </h2>
    <a href="{{ route('communications.index') }}" class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-arrow-left me-1"></i>Back to Inbox
    </a>
</div>

<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('communications.send') }}">
            @csrf

            <div class="row mb-3">
                <label class="col-sm-2 col-form-label">Channel</label>
                <div class="col-sm-4">
                    <select name="type" class="form-select @error('type') is-invalid @enderror" id="channelSelect">
                        @foreach($channels as $key => $label)
                            <option value="{{ $key }}" {{ old('type') === $key ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('type')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <label class="col-sm-2 col-form-label">Use Template</label>
                <div class="col-sm-4">
                    <select name="template_id" class="form-select" id="templateSelect">
                        <option value="">— No template —</option>
                        @foreach($templates as $tpl)
                            <option value="{{ $tpl->id }}" data-subject="{{ $tpl->subject }}" data-body="{{ $tpl->body }}" data-type="{{ $tpl->type }}">{{ $tpl->name }} ({{ ucfirst($tpl->type) }})</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="mb-3 row">
                <label class="col-sm-2 col-form-label">To</label>
                <div class="col-sm-10">
                    <input type="text" name="to_address" class="form-control @error('to_address') is-invalid @enderror" placeholder="email@example.com or +61400000000" value="{{ old('to_address') }}">
                    @error('to_address')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>

            <div class="mb-3 row" id="subjectRow">
                <label class="col-sm-2 col-form-label">Subject</label>
                <div class="col-sm-10">
                    <input type="text" name="subject" id="subjectField" class="form-control @error('subject') is-invalid @enderror" placeholder="Email subject…" value="{{ old('subject') }}">
                    @error('subject')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>

            <div class="mb-3 row">
                <label class="col-sm-2 col-form-label">Message</label>
                <div class="col-sm-10">
                    <textarea name="body" id="bodyField" rows="8" class="form-control @error('body') is-invalid @enderror" placeholder="Type your message here…">{{ old('body') }}</textarea>
                    @error('body')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>

            <div class="row">
                <div class="col-sm-10 offset-sm-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-send me-1"></i>Send Message
                    </button>
                    <a href="{{ route('communications.index') }}" class="btn btn-outline-secondary ms-2">Cancel</a>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const channelSelect  = document.getElementById('channelSelect');
    const templateSelect = document.getElementById('templateSelect');
    const subjectRow     = document.getElementById('subjectRow');
    const subjectField   = document.getElementById('subjectField');
    const bodyField      = document.getElementById('bodyField');

    function toggleSubject() {
        subjectRow.style.display = channelSelect.value === 'email' ? '' : 'none';
    }

    channelSelect.addEventListener('change', toggleSubject);
    toggleSubject();

    templateSelect.addEventListener('change', function () {
        const opt = this.options[this.selectedIndex];
        if (this.value) {
            if (opt.dataset.subject) subjectField.value = opt.dataset.subject;
            if (opt.dataset.body)    bodyField.value    = opt.dataset.body;
            if (opt.dataset.type)    channelSelect.value = opt.dataset.type;
            toggleSubject();
        }
    });
});
</script>
@endsection
