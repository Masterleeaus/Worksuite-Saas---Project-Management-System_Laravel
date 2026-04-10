@extends('layouts.app')

@php
    $pageTitle = __('AI Document Templates');
@endphp

@section('page-title')
    {{ __('AI Document') }}
@endsection

@section('page-breadcrumb')
    {{ __('AI Document') }}
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card border-0">
            <div class="card-header bg-white d-flex align-items-center justify-content-between">
                <h5 class="mb-0">{{ __('Templates') }}</h5>
                <a href="{{ route('titan.docs.templates.create') }}" class="btn btn-primary btn-sm">
                    <i class="fa fa-plus"></i>
                </a>
            </div>
            <div class="card-body">
                <form method="GET" class="d-flex gap-2 mb-3">
                    <input type="text" name="q" class="form-control" placeholder="{{ __('Search templates...') }}" value="{{ request('q') }}">
                    <button class="btn btn-success">{{ __('Search') }}</button>

                    <div class="ms-auto d-flex gap-2">
                        <select name="per_page" class="form-control" style="max-width:140px">
                            @foreach([10,25,50,100] as $pp)
                                <option value="{{ $pp }}" @selected((int)request('per_page',10)==$pp)>{{ $pp }} {{ __('per page') }}</option>
                            @endforeach
                        </select>
                    </div>
                </form>

                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>{{ __('Title') }}</th>
                                <th>{{ __('Category') }}</th>
                                <th>{{ __('Status') }}</th>
                                <th class="text-end">{{ __('Actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($template as $t)
                                <tr>
                                    <td>{{ $t->name }}</td>
                                    <td>
                                        @php
                                            $cat = null;
                                            try { $cat = \Modules\TitanDocs\Entities\AiTemplateCategory::find($t->category_id); } catch (\Throwable $e) {}
                                        @endphp
                                        {{ $cat->name ?? $t->category_id }}
                                    </td>
                                    <td>
                                        @if($t->status)
                                            <span class="badge bg-success">{{ __('Active') }}</span>
                                        @else
                                            <span class="badge bg-secondary">{{ __('Inactive') }}</span>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        <a href="{{ route('titan.docs.templates.edit', $t->id) }}" class="btn btn-sm btn-light"><i class="fa fa-edit"></i></a>
                                        <a href="{{ route('aidocument.document.show', ['doc_id'=>0,'id'=>$t->id]) }}" class="btn btn-sm btn-light"><i class="fa fa-bolt"></i></a>
                                    </td>
                                </tr>
                            @endforeach
                            @if(count($template)===0)
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-4">{{ __('No templates found.') }}</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>

                <div class="mt-3">
                    {{-- AiTemplateController currently passes a Collection; pagination is a later enhancement --}}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
