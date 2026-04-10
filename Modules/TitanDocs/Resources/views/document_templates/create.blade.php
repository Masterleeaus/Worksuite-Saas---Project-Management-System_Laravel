@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <h2>{{ __('Create Document Template') }}</h2>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
    @endif

    <form action="{{ route('titandocs.templates.store') }}" method="POST">
        @csrf
        <div class="card">
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label">{{ __('Template Name') }}</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">{{ __('Template Type') }}</label>
                        <select name="template_type" class="form-control" required>
                            <option value="client">{{ __('Client') }}</option>
                            <option value="employee">{{ __('Employee') }}</option>
                            <option value="contract">{{ __('Contract') }}</option>
                            <option value="letter">{{ __('Letter') }}</option>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">{{ __('Document Type') }}</label>
                        <input type="text" name="document_type" class="form-control" value="{{ old('document_type') }}" placeholder="e.g. employment_letter, service_contract" required>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">{{ __('HTML Content') }}</label>
                    <small class="text-muted d-block mb-1">{{ __('Use {{field_name}} placeholders for merge fields.') }}</small>
                    <textarea name="html_content" class="form-control" rows="15" required>{{ old('html_content') }}</textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label">{{ __('Required Fields') }}</label>
                    <small class="text-muted d-block mb-1">{{ __('Comma-separated list of merge field names.') }}</small>
                    <input type="text" name="required_fields_csv" class="form-control" id="requiredFieldsCsv" value="{{ old('required_fields_csv') }}">
                    <div id="requiredFieldsContainer"></div>
                </div>
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-primary">{{ __('Create Template') }}</button>
                <a href="{{ route('titandocs.templates.index') }}" class="btn btn-secondary">{{ __('Cancel') }}</a>
            </div>
        </div>
    </form>
</div>

<script>
// Convert CSV field names to hidden array inputs on submit
document.querySelector('form').addEventListener('submit', function(e) {
    var csv = document.getElementById('requiredFieldsCsv').value;
    var fields = csv.split(',').map(f => f.trim()).filter(f => f.length > 0);
    fields.forEach(function(f, i) {
        var input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'required_fields[]';
        input.value = f;
        document.getElementById('requiredFieldsContainer').appendChild(input);
    });
});
</script>
@endsection
