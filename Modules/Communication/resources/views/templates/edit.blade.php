@extends('communication::layouts.master')

@section('communication_content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2 class="mb-0">
        <i class="bi bi-file-earmark-text me-2"></i>Edit Template
    </h2>
    <a href="{{ route('communications.templates.index') }}" class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-arrow-left me-1"></i>Back to Templates
    </a>
</div>

<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('communications.templates.update', $template->id) }}">
            @csrf @method('PUT')

            <div class="mb-3 row">
                <label class="col-sm-2 col-form-label">Name <span class="text-danger">*</span></label>
                <div class="col-sm-10">
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $template->name) }}" required>
                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>

            <div class="mb-3 row">
                <label class="col-sm-2 col-form-label">Channel <span class="text-danger">*</span></label>
                <div class="col-sm-4">
                    <select name="type" class="form-select @error('type') is-invalid @enderror" id="tplType">
                        @foreach($types as $key => $label)
                            <option value="{{ $key }}" {{ old('type', $template->type) === $key ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('type')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>

            <div class="mb-3 row" id="subjectRow">
                <label class="col-sm-2 col-form-label">Subject</label>
                <div class="col-sm-10">
                    <input type="text" name="subject" class="form-control @error('subject') is-invalid @enderror" value="{{ old('subject', $template->subject) }}" placeholder="Use {variable} placeholders">
                    @error('subject')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>

            <div class="mb-3 row">
                <label class="col-sm-2 col-form-label">Body <span class="text-danger">*</span></label>
                <div class="col-sm-10">
                    <textarea name="body" rows="10" class="form-control @error('body') is-invalid @enderror" required>{{ old('body', $template->body) }}</textarea>
                    @error('body')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    <small class="form-text text-muted">
                        Available placeholders: <code>{customer_name}</code> <code>{booking_date}</code> <code>{cleaner_name}</code> <code>{service_name}</code> <code>{address}</code> <code>{company_name}</code>
                    </small>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-10 offset-sm-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save me-1"></i>Update Template
                    </button>
                    <a href="{{ route('communications.templates.index') }}" class="btn btn-outline-secondary ms-2">Cancel</a>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const tplType    = document.getElementById('tplType');
    const subjectRow = document.getElementById('subjectRow');
    function toggleSubject() {
        subjectRow.style.display = (tplType.value === 'email' || tplType.value === 'all') ? '' : 'none';
    }
    tplType.addEventListener('change', toggleSubject);
    toggleSubject();
});
</script>
@endsection
