@extends('layouts.app')

@section('content')
<h1>Create Prompt</h1>

@php
    $draft = request('draft', '');
@endphp

<form method="POST" action="{{ route('ai-tools.prompts.store') }}">
@csrf

<div class="mb-3">
    <label class="form-label">Name</label>
    <input class="form-control" name="name" placeholder="Name" required>
</div>

<div class="mb-3">
    <label class="form-label">Namespace</label>
    <input class="form-control" name="namespace" placeholder="Namespace" value="zero" required>
</div>

<div class="mb-3">
    <label class="form-label">Prompt body</label>
    <textarea class="form-control" name="prompt_body" rows="8" placeholder="Prompt body">{{ $draft }}</textarea>
    <div class="form-text">Tip: select text in any Quill editor and click “Create (Zero)” to prefill this.</div>
</div>

<div class="mb-3">
    <label class="form-label">Variables JSON</label>
    <textarea class="form-control" name="variables" rows="4" placeholder='{"fields":[...]}'></textarea>
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

<button type="submit" class="btn btn-primary">Create Prompt</button>
</form>
@endsection