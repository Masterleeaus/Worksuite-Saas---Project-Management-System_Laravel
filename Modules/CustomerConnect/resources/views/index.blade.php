@extends('layouts.main')

@section('page-title')
    {{ __('NewsLetter') }}
@endsection

@section('page-breadcrumb')
    {{ __('Newsletter') }}
@endsection

@push('css')
<link href="{{  asset('assets/js/plugins/summernote-0.8.18-dist/summernote-lite.min.css')  }}" rel="stylesheet">
@endpush
@push('scripts')
<script src="{{ asset('assets/js/plugins/summernote-0.8.18-dist/summernote-lite.min.js') }}"></script>

@endpush

@section('content')
    <div class="row">

        <div class="col-6">
            <div class="card">
                <div class="card-body" id="newsletterModal">
                    <div class="p-3 card">
                        <ul class="nav nav-pills nav-fill" id="pills-tab" role="tablist">
                            @foreach ($modules as $module)
                                @if ($module == 'general' || module_is_active($module))
                                    <li class="nav-item tabselect" role="presentation">
                                        {{-- <button class="nav-link text-capitalize {{ $loop->index == 0 ? 'active' : '' }} "
                                            id="pills-{{ strtolower($module) }}-tab" data-bs-toggle="pill"
                                            data-bs-target="#pills-{{ strtolower($module) }}" data-name="sub-module"
                                            type="button">{{ Module_Alias_Name($module) }}</button> --}}

                                        <a class="nav-link text-capitalize {{ $loop->index == 0 ? 'active' : '' }}  " id="pills-{{ strtolower($module) }}-tab"
                                            data-bs-toggle="pill" data-bs-target="#pills-{{ strtolower($module) }}"
                                            type="button" role="tab" aria-controls="pills-home"
                                            aria-selected="true">{{ Module_Alias_Name($module) }}</a>
                                    </li>
                                @endif
                            @endforeach
                        </ul>
                    </div>

                    <form method="post" action="{{ route('newsletter.filter') }}" id="myForm">
                        @csrf
                        <input type="hidden" class="filter">
                        <div class="px-0 card-body">
                            <div class="tab-content" id="pills-tabContent">
                                @foreach ($modules as $module)
                                    @if ($module == 'general' || module_is_active($module))
                                        <div class="tab-pane text-capitalize fade show {{ $loop->index == 0 ? 'active' : '' }}"
                                            id="pills-{{ strtolower($module) }}" role="tabpanel"
                                            aria-labelledby="pills-{{ strtolower($module) }}-tab">
                                            <div class="row card-body">
                                                <div class="col-12 form-group">
                                                    {{ Form::label('send', __('Send Newsletter To'), ['class' => 'col-form-label']) }}
                                                    <select class="form-control send send_select" id="select">
                                                        <option value="" selected="">Select</option>
                                                        @foreach ($notify as $action)
                                                            @if ($action->module == $module)
                                                                <option value="{{ $action->id }}">
                                                                    {{ $action->submodule }}
                                                                </option>
                                                            @endif
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <hr>
                                            </div>
                                        </div>
                                    @endif
                                @endforeach

                                <div class="card-body">
                                    <div class="col-12" id="getfields">

                                    </div>
                                </div>
                            </div>
                        </div>
                </div>
            </div>
        </div>

        <div class="col-6">
            <div class="card">
                <div class="card-body">
                    <div class="form-group col-12">
                        {{ Form::label('subject', __('E-mail subject'), ['class' => 'col-form-label text-dark']) }}
                        {{ Form::text('subject', null, ['class' => 'form-control font-style', 'required' => 'required']) }}
                    </div>
                    <div class="form-group col-12">
                        {{ Form::label('content', __('Email Message'), ['class' => 'col-form-label text-dark']) }}
                        <textarea name="content"
                        class="form-control summernote  {{ !empty($errors->first('content')) ? 'is-invalid' : '' }}" required
                        id="content"></textarea>
                    </div>
                    <div class="col-md-12 text-end mb-3">
                        <input type="submit" value="{{ __('Send Newsletters') }}"
                            class="btn btn-print-invoice  btn-primary m-r-10">
                    </div>
                </div>
            </div>
        </div>
        </form>
    </div>
@endsection
@push('scripts')
<script src="{{ asset('assets/js/plugins/summernote-0.8.18-dist/summernote-lite.min.js') }}"></script>

    <script>
        document.getElementById("myForm").onsubmit = function(event) {
            event.preventDefault();
            var form = $(this).serialize();
            var activeButton = $('.nav-link.active').attr('data-bs-target');
            var active_slector = $(activeButton).find('select');
            var selected_value = active_slector.val();
            var additionalData = '&additionalField=' + selected_value + '';
            var combinedData = form + additionalData;
            $.ajax({
                type: "POST",
                url: '{{ route('newsletter.filter') }}',
                datType: 'json',
                data: combinedData,
                beforeSend: function() {
                    $(".loader-wrapper").removeClass('d-none');
                },
                success: function(data) {
                    $(".loader-wrapper").addClass('d-none');
                    toastrs('Success', data.response, 'success');
                },

            });
        };
    </script>
    <script>
        $(document).on('change', '.send_select', function() {
            var teamSection = $(this).parent().parent().parent();
            var workmodulId = $(this).val();
            $.ajax({
                url: '{{ route('newsletter.getcondition') }}',
                type: 'POST',

                data: {
                    "_token": "{{ csrf_token() }}",
                    "workmodule_id": workmodulId,
                },
                success: function(data) {
                    $('#getfields').empty();
                    $('#getfields').append(data.html)
                    $('.filter').append(data.html)

                },

            });
        });
        /* remove fields on active tab */
        $('.tabselect').click(function() {
            $('#getfields').empty();
        });
    </script>
@endpush
