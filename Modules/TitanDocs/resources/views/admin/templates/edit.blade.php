@extends('layouts.app')
@php
    $pageTitle = __('Edit Template');
@endphp
@section('page-title'){{ __('Edit Template') }}@endsection
@section('page-breadcrumb'){{ __('TitanDocs') }}@endsection

@section('content')
<div class="row">
  <div class="col-lg-8">
    <div class="card border-0">
      <div class="card-header">
        <h5 class="mb-0">Edit Template #{{ $tpl->id }}</h5>
        <p class="text-muted mb-0">Assign KB scope and approve before sync.</p>
      </div>
      <div class="card-body">
        @if(session('success'))
          <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="mb-3">
          <label class="form-label">Template</label>
          <div class="form-control-plaintext">
            <strong>{{ $tpl->name }}</strong> <span class="text-muted">({{ $tpl->slug }})</span>
          </div>
          <div class="mt-2">
            @if(!empty($tpl->approved_at))
              <span class="badge bg-success">Approved</span>
              <span class="text-muted ms-1"><small>{{ $tpl->approved_at }}</small></span>
            @else
              <span class="badge bg-secondary">Draft</span>
            @endif
          </div>
        </div>

        <form method="POST" action="{{ route('titan.docs.templates.update', $tpl->id) }}">
          @csrf
          <div class="mb-3">
            <label class="form-label">KB Collection Key</label>
            <select class="form-select" name="kb_collection_key" required>
              @php $current = $tpl->kb_collection_key ?? 'kb_general_cleaning'; @endphp
              @foreach($kbCollections as $c)
                <option value="{{ $c['key'] }}" @if($current === $c['key']) selected @endif>
                  {{ $c['title'] }} — {{ $c['key'] }} ({{ $c['scope_type'] }})
                </option>
              @endforeach
            </select>
            <div class="form-text">General templates go to <code>kb_general_cleaning</code>. Specialist templates should go to the matching <code>kb_agent_*</code> collection.</div>
          </div>

          <button class="btn btn-primary" type="submit">Save</button>
          <a class="btn btn-outline-secondary" href="{{ route('titan.docs.templates.index') }}">Back</a>
        </form>

        <div class="d-flex gap-2 flex-wrap mt-3">
          @if(!empty($tpl->approved_at))
            <form method="POST" action="{{ route('titan.docs.templates.unapprove', $tpl->id) }}">
              @csrf
              <button class="btn btn-outline-secondary" type="submit">Unapprove</button>
            </form>
          @else
            <form method="POST" action="{{ route('titan.docs.templates.approve', $tpl->id) }}">
              @csrf
              <button class="btn btn-success" type="submit">Approve</button>
            </form>
          @endif
        </div>

        <div class="mt-2 text-muted">
          <small>
            Default behaviour: KB sync ingests only <strong>approved</strong> templates.
            Use the Settings toggle <em>Include drafts</em> only for testing.
          </small>
        </div>

      </div>
    </div>
  </div>
</div>
@endsection