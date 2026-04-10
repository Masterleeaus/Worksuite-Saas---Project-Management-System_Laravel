@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <h2>{{ __('Edit Document Template') }}</h2>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
    @endif

    <form action="{{ route('titandocs.templates.update', $template->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="card">
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label">{{ __('Template Name') }}</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name', $template->name) }}" required>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">{{ __('Template Type') }}</label>
                        <select name="template_type" class="form-control" required>
                            @foreach(['client','employee','contract','letter'] as $type)
                            <option value="{{ $type }}" {{ old('template_type', $template->template_type) === $type ? 'selected' : '' }}>
                                {{ ucfirst($type) }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">{{ __('Document Type') }}</label>
                        <input type="text" name="document_type" class="form-control" value="{{ old('document_type', $template->document_type) }}" required>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">{{ __('HTML Content') }}</label>
                    <small class="text-muted d-block mb-1">{{ __('Use {{field_name}} placeholders for merge fields.') }}</small>
                    <textarea name="html_content" class="form-control" rows="15" required>{{ old('html_content', $template->html_content) }}</textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label">{{ __('Required Fields') }}</label>
                    <input type="text" name="required_fields_csv" class="form-control" id="requiredFieldsCsv"
                           value="{{ old('required_fields_csv', implode(', ', $template->required_fields ?? [])) }}">
                    <div id="requiredFieldsContainer"></div>
                </div>
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-primary">{{ __('Update Template') }}</button>
                <a href="{{ route('titandocs.templates.index') }}" class="btn btn-secondary">{{ __('Cancel') }}</a>
            </div>
        </div>
    </form>
</div>

<script>
document.querySelector('form').addEventListener('submit', function(e) {
    var csv = document.getElementById('requiredFieldsCsv').value;
    var fields = csv.split(',').map(f => f.trim()).filter(f => f.length > 0);
    fields.forEach(function(f) {
        var input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'required_fields[]';
        input.value = f;
        document.getElementById('requiredFieldsContainer').appendChild(input);
    });
});
</script>
@endsection
