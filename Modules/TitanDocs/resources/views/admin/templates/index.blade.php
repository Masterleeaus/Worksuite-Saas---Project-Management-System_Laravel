@extends('layouts.app')
@php
    $pageTitle = __('TitanDocs Templates');
@endphp
@section('page-title'){{ __('TitanDocs Templates') }}@endsection
@section('page-breadcrumb'){{ __('TitanDocs') }}@endsection

@section('content')
<div class="row">
  <div class="col-12">
    <div class="card border-0">
      <div class="card-header d-flex align-items-center justify-content-between">
        <div>

        <h5 class="mb-0">TitanDocs Templates</h5>
        
        </div>
        <div>
          <a href="{{ route('titan.docs.templates.create') }}" class="btn btn-primary btn-sm">{{ __('Add Template') }}</a>
        </div>
        
      </div>
      <div class="card-body">
        @if(session('success'))
          <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="table-responsive">
          <table class="table align-middle">
            <thead>
              <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Slug</th>
                <th>KB Collection</th>
                <th>Approved</th>
                <th></th>
              </tr>
            </thead>
            <tbody>
              @foreach($templates as $t)
              <tr>
                <td>{{ $t->id }}</td>
                <td>{{ $t->name }}</td>
                <td>{{ $t->slug }}</td>
                <td><code>{{ $t->kb_collection_key ?? 'kb_general_cleaning' }}</code></td>
                <td>
                  @if(!empty($t->approved_at))
                    <span class="badge bg-success">Approved</span>
                  @else
                    <span class="badge bg-secondary">Draft</span>
                  @endif
                </td>
                <td><a class="btn btn-sm btn-primary" href="{{ route('titan.docs.templates.edit', $t->id) }}">Edit</a></td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection