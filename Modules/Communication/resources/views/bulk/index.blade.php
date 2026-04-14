@extends('communication::layouts.master')

@section('communication_content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2 class="mb-0">
        <i class="bi bi-envelope-paper me-2"></i>Bulk Message Send
    </h2>
    <a href="{{ route('communications.index') }}" class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-arrow-left me-1"></i>Back to Inbox
    </a>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header"><strong>Compose Bulk Message</strong></div>
            <div class="card-body">
                <form method="POST" action="{{ route('communications.bulk.send') }}">
                    @csrf

                    <div class="mb-3 row">
                        <label class="col-sm-3 col-form-label">Channel <span class="text-danger">*</span></label>
                        <div class="col-sm-4">
                            <select name="type" class="form-select @error('type') is-invalid @enderror" id="channelSelect">
                                @foreach($channels as $key => $label)
                                    <option value="{{ $key }}" {{ old('type') === $key ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                            @error('type')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-sm-5">
                            <select name="template_id" class="form-select" id="templateSelect">
                                <option value="">— Optional: Use Template —</option>
                                @foreach($templates as $tpl)
                                    <option value="{{ $tpl->id }}" data-subject="{{ $tpl->subject }}" data-body="{{ $tpl->body }}" data-type="{{ $tpl->type }}">{{ $tpl->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="mb-3 row" id="subjectRow">
                        <label class="col-sm-3 col-form-label">Subject</label>
                        <div class="col-sm-9">
                            <input type="text" name="subject" id="subjectField" class="form-control @error('subject') is-invalid @enderror" placeholder="Email subject…" value="{{ old('subject') }}">
                            @error('subject')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label class="col-sm-3 col-form-label">Message <span class="text-danger">*</span></label>
                        <div class="col-sm-9">
                            <textarea name="body" id="bodyField" rows="8" class="form-control @error('body') is-invalid @enderror" required>{{ old('body') }}</textarea>
                            @error('body')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label class="col-sm-3 col-form-label">Recipients <span class="text-danger">*</span></label>
                        <div class="col-sm-9">
                            <textarea name="recipients" rows="4" class="form-control @error('recipients') is-invalid @enderror" placeholder="Comma-separated emails or phone numbers:&#10;alice@example.com, bob@example.com, +61400000001" required>{{ old('recipients') }}</textarea>
                            @error('recipients')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            <small class="form-text text-muted">Separate multiple addresses with commas.</small>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-9 offset-sm-3">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-send me-1"></i>Send to All
                            </button>
                            <a href="{{ route('communications.index') }}" class="btn btn-outline-secondary ms-2">Cancel</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card">
            <div class="card-header"><strong>Tips</strong></div>
            <div class="card-body">
                <ul class="mb-0 ps-3 small text-muted">
                    <li>Use <code>{customer_name}</code> and other placeholders — they will be replaced per recipient where customer data is available.</li>
                    <li>Test with a small list first.</li>
                    <li>Messages are queued and sent in the background.</li>
                </ul>
            </div>
        </div>
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
