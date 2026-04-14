@extends('layouts.app')
@php
    $pageTitle = __('Add TitanDocs Template');
@endphp
@section('page-title'){{ __('Add TitanDocs Template') }}@endsection
@section('page-breadcrumb'){{ __('TitanDocs') }}@endsection

@section('content')
<div class="row">
  <div class="col-12">
    <div class="card border-0">
      <div class="card-header d-flex align-items-center justify-content-between">
        <h5 class="mb-0">{{ __('Add Template') }}</h5>
        <a href="{{ route('titan.docs.templates.index') }}" class="btn btn-light btn-sm">{{ __('Back') }}</a>
      </div>
      <div class="card-body">
        @if ($errors->any())
          <div class="alert alert-danger">
            <ul class="mb-0">
              @foreach($errors->all() as $e)
                <li>{{ $e }}</li>
              @endforeach
            </ul>
          </div>
        @endif

        <form method="POST" action="{{ route('titan.docs.templates.store') }}">
          @csrf
          <div class="row">
            <div class="col-md-6">
              <label class="f-14 text-dark-grey mb-1">{{ __('Name') }}</label>
              <input class="form-control" name="name" value="{{ old('name') }}" required>
            </div>
            <div class="col-md-6">
              <label class="f-14 text-dark-grey mb-1">{{ __('Slug') }}</label>
              <input class="form-control" name="slug" value="{{ old('slug') }}" required>
              <small class="text-muted">e.g. swms_cleaning, contract_basic</small>
            </div>
          </div>

          <div class="row mt-3">
            <div class="col-md-6">
              <label class="f-14 text-dark-grey mb-1">{{ __('Template Code') }}</label>
              <input class="form-control" name="template_code" value="{{ old('template_code') }}" required>
              <small class="text-muted">Internal code used by the document generator.</small>
            </div>
            <div class="col-md-6">
              <label class="f-14 text-dark-grey mb-1">{{ __('KB Collection') }}</label>
              <select class="form-control" name="kb_collection_key">
                <option value="kb_general_cleaning">kb_general_cleaning</option>
                @foreach(($kbCollections ?? []) as $c)
                  <option value="{{ $c['key'] }}">{{ $c['key'] }} — {{ $c['title'] }}</option>
                @endforeach
              </select>
            </div>
          </div>

          <div class="mt-3">
            <label class="f-14 text-dark-grey mb-1">{{ __('Description') }}</label>
            <textarea class="form-control" name="description" rows="4">{{ old('description') }}</textarea>
          </div>

          <div class="mt-4 d-flex justify-content-end">
            <button class="btn btn-primary" type="submit">{{ __('Create Template') }}</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection
