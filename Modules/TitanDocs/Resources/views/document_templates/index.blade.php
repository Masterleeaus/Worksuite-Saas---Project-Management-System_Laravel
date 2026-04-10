@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>{{ __('Document Template Library') }}</h2>
        @if(\Illuminate\Support\Facades\Route::has('titandocs.templates.create'))
        <a href="{{ route('titandocs.templates.create') }}" class="btn btn-primary">
            <i class="fa fa-plus"></i> {{ __('New Template') }}
        </a>
        @endif
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="row">
        @forelse($templates as $template)
        <div class="col-md-4 mb-4">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between">
                    <span class="badge badge-info">{{ ucfirst($template->template_type) }}</span>
                    @if($template->is_global)
                        <span class="badge badge-secondary">{{ __('Global') }}</span>
                    @endif
                    @if($template->is_approved)
                        <span class="badge badge-success">{{ __('Approved') }}</span>
                    @else
                        <span class="badge badge-warning">{{ __('Pending') }}</span>
                    @endif
                </div>
                <div class="card-body">
                    <h5 class="card-title">{{ $template->name }}</h5>
                    <p class="text-muted small">{{ $template->document_type }}</p>
                    @if($template->required_fields)
                    <p class="small">
                        <strong>{{ __('Fields:') }}</strong>
                        {{ implode(', ', $template->required_fields) }}
                    </p>
                    @endif
                </div>
                <div class="card-footer d-flex gap-2">
                    @if(!$template->is_global && \Illuminate\Support\Facades\Route::has('titandocs.templates.edit'))
                    <a href="{{ route('titandocs.templates.edit', $template->id) }}" class="btn btn-sm btn-outline-primary">
                        <i class="fa fa-edit"></i> {{ __('Edit') }}
                    </a>
                    @endif
                    @if(\Auth::user()->isAbleTo('manage_templates') && !$template->is_approved && \Illuminate\Support\Facades\Route::has('titandocs.templates.approve'))
                    <form action="{{ route('titandocs.templates.approve', $template->id) }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-success">
                            <i class="fa fa-check"></i> {{ __('Approve') }}
                        </button>
                    </form>
                    @endif
                </div>
            </div>
        </div>
        @empty
        <div class="col-12">
            <div class="alert alert-info">{{ __('No templates found.') }}</div>
        </div>
        @endforelse
    </div>
</div>
@endsection
