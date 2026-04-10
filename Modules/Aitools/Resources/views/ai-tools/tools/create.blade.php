@extends('layouts.app')

@section('content')
<h1>Create Tool</h1>

@php
    $draft = request('draft', '');
@endphp

<form method="POST" action="{{ route('ai-tools.tools.store') }}">
@csrf

<div class="mb-3">
    <label class="form-label">Name</label>
    <input class="form-control" name="name" placeholder="Name" required>
</div>

<div class="mb-3">
    <label class="form-label">Description</label>
    <textarea class="form-control" name="description" rows="5" placeholder="Description">{{ $draft }}</textarea>
    <div class="form-text">Tip: select text in any Quill editor and click “Create (Zero)” to prefill this.</div>
</div>

<div class="mb-3">
    <label class="form-label">Input schema JSON</label>
    <textarea class="form-control" name="input_schema" rows="4" placeholder='{"type":"object","properties":{}}'></textarea>
</div>

<div class="mb-3">
    <label class="form-label">Handler type</label>
    <input class="form-control" name="handler_type" placeholder="internal/http/function" value="internal" required>
</div>

<div class="mb-3">
    <label class="form-label">Risk level</label>
    <select class="form-select" name="risk_level">
        <option value="low">Low</option>
        <option value="medium">Medium</option>
        <option value="high">High</option>
    </select>
</div>

<div class="mb-3 form-check">
    <input class="form-check-input" type="checkbox" name="enabled" value="1" checked>
    <label class="form-check-label">Enabled</label>
</div>

<button type="submit" class="btn btn-primary">Create Tool</button>
</form>
@endsection