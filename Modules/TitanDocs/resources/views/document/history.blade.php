@extends('layouts.app')
@php
    $pageTitle = __('Document History');
@endphp
@section('page-title')
    {{ __('AI Document History') }}
@endsection
@section('page-breadcrumb')
{{ __('AI Document History') }}
@endsection
@section('page-action')
@endsection
@section('content')
<div class="row">
    <div class="col-sm-12">
        <div class="card">
            <div class="card-body table-border-style">
                <div class="table-responsive">
                    <table class="table mb-0 pc-dt-simple" id="assets">
                        <thead>
                        <tr>
                            <th>{{__('Document Name')}}</th>
                            <th>{{__('Category')}}</th>
                            <th>{{__('Language')}}</th>
                            <th>{{__('word used')}}</th>
                            <th>{{__('Created On')}}</th>
                            <th>{{__('Action')}}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($document as $temp)
                            <tr class="font-style">
                            <td>{{ucfirst(trans($temp->doc_name))}}</td>
                            <td>{{ucfirst(trans($temp->category))}}</td>
                            <td>{{$temp->language}}</td>
                            <td>{{$temp->max_tokens}}</td>
                            <td>{{$temp->created_on}}</td>
                            <td>
                                <div class="action-btn bg-info ms-2">
                                    <a href="{{ route('aidocument.document.edit',[$temp->id,$temp->template_id]) }}" class="mx-3 btn btn-sm  align-items-center">
                                        <i class="ti ti-pencil text-white" data-bs-toggle="tooltip" title="{{ __('Edit') }}"></i>
                                    </a>
                                </div>
                                <div class="action-btn bg-danger ms-2">
                                {!! Form::open(['method' => 'DELETE', 'route' => ['aidocument.document.destroy', [$temp->id]]]) !!}
                                
                                 <a href="#"data-bs-toggle="tooltip" title="" data-original-title="Delete" class="mx-3 btn btn-sm  align-items-center show_confirm" data-bs-original-title="Delete">
                                       <i class="ti ti-trash text-white"></i>
                                    </a>
                                {!! Form::close() !!}
                                    
                                </div>
                            </td>
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
@push('scripts')

@endpush
