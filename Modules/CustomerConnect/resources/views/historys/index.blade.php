@extends('layouts.main')

@section('page-title')
    {{ __('NewsLetter') }}
@endsection
@section('page-breadcrumb')
    {{ __('Newsletter') }}
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
                                <th>#</th>
                                <th>{{ __('Module') }}</th>
                                <th> {{ __('Status') }}</th>
                                <th> {{__('Created At')}}</th>
                                @if(Laratrust::hasPermission('newsletter history show') || Laratrust::hasPermission('newsletter history delete'))
                                <th width="10%"> {{__('Action')}}</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($newsletters as $index =>  $newsletter)
                            <tr>
                            <th scope="row">{{++$index}}</th>
                            <td>
                                <div class="page-header">
                                    <ul class="breadcrumb  m-1">
                                        <li class="breadcrumb-item"> {{ ucfirst( Module_Alias_Name($newsletter->module)) }}</li>
                                        @if (!empty($newsletter->sub_module))
                                            <li class="breadcrumb-item"> {{ ucfirst($newsletter->sub_module) }} </li>
                                        @endif
                                    </ul>
                                </div>
                            </td>
                            <td>
                                @if ($newsletter->status == 1)
                                    <span
                                        class="badge fix_badges bg-primary p-2 px-3 rounded">Success</span>
                                @elseif($newsletter->status == 0)
                                    <span
                                        class="badge fix_badges bg-danger p-2 px-3 rounded">Failed</span>
                                @endif
                            </td>
                            <td>{{$newsletter->created_at->format('d M Y')}}</td>
                            @if(Laratrust::hasPermission('newsletter history show') || Laratrust::hasPermission('newsletter history delete'))

                            <td>
                                <div class="action-btn bg-warning ms-2">
                                    <a href="#" class="mx-3 btn btn-sm d-inline-flex align-items-center" title="{{__('View')}}" data-bs-toggle="tooltip" data-bs-placement="top" data-url="{{ route('newsletter-history.show',$newsletter->id) }}" data-ajax-popup="true" data-title="{{__('Mails Details')}}" data-size="xl"><span class="text-white"><i class="ti ti-eye"></i></span></a>
                                </div>
                                <div class="action-btn bg-danger ms-2">
                                    {{Form::open(array('route'=>array('newsletter-history.destroy', $newsletter->id),'class' => 'm-0'))}}
                                    @method('DELETE')
                                        <a
                                            class="mx-3 btn btn-sm  align-items-center bs-pass-para show_confirm"
                                            data-bs-toggle="tooltip" title="" data-bs-original-title="Delete"
                                            aria-label="Delete" data-confirm="{{__('Are You Sure?')}}" data-text="{{__('This action can not be undone. Do you want to continue?')}}"  data-confirm-yes="delete-form-{{$newsletter->id}}"><i
                                                class="ti ti-trash text-white text-white"></i></a>
                                    {{Form::close()}}
                                </div>
                            </td>
                            @endif
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
