@extends('layouts.app')
@php
    $pageTitle = __('View Document');
@endphp
@section('page-title')
    {{ __('AI Document') }}
@endsection
@section('page-breadcrumb')
    {{ __('AI Document') }}
@endsection
@section('page-action')
@endsection
@section('content')
    <style type="text/css">
        .template-list-div:hover {
            -webkit-transform: translateY(-5px);
            -ms-transform: translateY(-5px);
            transform: translateY(-5px);
            box-shadow: 0 5px 10px rgba(0, 0, 0, 0.32);
            cursor: pointer;
            border-radius: 10px;
        }
    </style>
    <input value="{{url('/')}}" type="hidden" name="route_url" id="route_url">
    <div class="row">
        <form id="myForm" action="" method="post" enctype="multipart/form-data" class="mt-24">
            @csrf
            <div class="col-xxl-12 ">
                <div class="row">
                    <div class="col-lg-4 col-md-12 col-sm-12">
                        <div class="card border-0">
                            <div class="card-body p-4">
                                <div class="template">
                                    <div class="template-icon d-flex">
                                        <div class="btn btn-sm btn-light-primary">
                                            <i class="ti ti-template email-icon"></i>
                                        </div>
                                        <div class="ms-2 mt-1">
                                            <h6 class=" ">{{ $template->name }} </h6>
                                        </div>
                                    </div>
                                    <p class="text-muted mt-3">{{ $template->description }} </p>
                                </div>

                                <div class="template-body row">
                                    <input type="hidden" name="template" value="{{ $template->template_code }}">

                                    <div class="form-group col-md-12">
                                        <label class="form-label ">{{ __('Language') }}</label>
                                        <select class="form-control" name="language" id="language">
                                            @foreach ($languages as $lng)
                                                <option value="{{ $lng->code }}"
                                                    @if ($lng['code'] == 'en-US') selected @endif> {{ $lng->language }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <!-- form content -->
                                    {!! $html !!}
                                    <!--  -->
                                    <div class="form-group col-md-12">
                                        <label class="form-label ">{{ __('Creativity ') }}</label>
                                        <select class="form-control" name="creativity" id="creativity">
                                            <option disabled="">{{ __('Select Creativity level') }}</option>
                                            <option value="0">{{ __('Low') }}</option>
                                            <option value="0.5">{{ __('Average') }}</option>
                                            <option value="1" selected="">{{ __('High') }}</option>
                                        </select>
                                    </div>
                                    @if ($template->is_tone == 1)
                                        <div class="form-group col-md-12">
                                            <label class="form-label ">{{ __('Tone of Voice ') }}</label>
                                            <select class="form-control" id="tone_language" name="tone_language"
                                                data-placeholder="{{ __('Select tone of voice') }}">
                                                @foreach ($tones as $tone)
                                                    <option value="{{ $tone }}"
                                                        @if ('casual' == $tone) selected @endif>
                                                        {{ $tone }}</option>
                                                @endforeach

                                            </select>
                                        </div>
                                    @endif
                                    <div class="form-group col-md-6">
                                        <label class="form-label ">{{ __('Number of Results') }}</label>
                                        <select class="form-control" name="max_results" id="max_results">
                                            <option disabled="">{{ __('Select max number of result') }}</option>
                                            <option value="1" selected="">1</option>
                                            <option value="2">2</option>
                                            <option value="3">3</option>
                                            <option value="4">4</option>
                                            <option value="5">5</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label class="form-label ">{{ __('Max Result Length ') }}</label>
                                        <input type="text" class="form-control" name="words" id="words"
                                            value="200" placeholder="{{ __('Max Result Length') }}">
                                    </div>

                                </div>
                            </div>
                            <div class="card-footer mt-0">
                                <div class="text-end ">
                                    <button type="submit" name="submit" class="btn btn-primary"
                                        id="ai_generate">{{ __('Generate Text') }}</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-8 col-md-12 col-sm-12">
                        <div class="card card-body mb-2 p-3">
                            <div class="row gy-3 mb-3 align-items-center">
                                <div class="col-12">
                                    <label class="form-label">{{ __('Document Name:') }}</label>
                                </div>
                                <div class="col-lg-8 mt-0 col-md-7 col-sm-12">
                                    <div class="form-group mb-0">
                                        <input type="hidden" name="document_id" class="form-control"
                                            value="{{ $document->id }}">
                                        <input type="text" name="document_name" id="document_name"class="form-control"
                                            value="{{ $document->doc_name }}">
                                    </div>
                                </div>
                                <div class="col-lg-4 mt-0 col-md-12 col-sm-12 text-md-end">
                                    @if (\Auth::user()->isAbleTo('ai document save'))
                                        <a href="#" class="btn btn-sm  btn-primary" data-bs-toggle="tooltip"
                                            data-bs-original-title="{{ __('Save Document') }}"
                                            onclick="save_doc(<?= $document->id ?>)"><i data-feather="save"></i></a>
                                    @else
                                        <a href="#" class="btn btn-sm  btn-primary" data-bs-toggle="tooltip"
                                            data-bs-original-title="{{ __('Save Document') }}"
                                            onclick="preventAccess();"><i data-feather="save"></i></a>
                                    @endif
                                    @if (\Auth::user()->isAbleTo('ai document generate Word'))
                                        <a href="#" class="btn btn-sm  btn-info" data-bs-toggle="tooltip"
                                            data-bs-original-title="{{ __('All Response Export as Word Document') }}"
                                            onclick="exportWordDocallresponse(<?= $document->id ?>)"><i
                                                class="ti ti-file-download"></i></a>
                                    @else
                                        <a href="#" class="btn btn-sm  btn-info" data-bs-toggle="tooltip"
                                            data-bs-original-title="{{ __('All Response Export as Word Document') }}"
                                            onclick="preventAccess()"><i class="ti ti-file-download"></i></a>
                                    @endif
                                    @if (\Auth::user()->isAbleTo('ai document copy'))
                                        <a href="#" class="btn btn-sm  btn-warning" data-bs-toggle="tooltip"
                                            data-bs-original-title="{{ __('Copy All Response') }}"
                                            onclick="copyallresponse(<?= $document->id ?>)"><i class="ti ti-copy"></i></a>
                                    @else
                                        <a href="#" class="btn btn-sm  btn-warning" data-bs-toggle="tooltip"
                                            data-bs-original-title="{{ __('Copy All Response') }}"
                                            onclick="preventAccess()"><i class="ti ti-copy"></i></a>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div style="display:none;">
                            <div id="template-textarea">
                                <textarea class="ai-description" id="ai-description" cols="80" rows="100"></textarea>
                            </div>
                        </div>


                        <div class=" card border-0 card-body  " style="height: 640px;">
                            <div class="row">
                                @if (count($history_data) > 0)
                                    <div class="col-lg-2 col-md-2 col-sm-2 slider-button ">
                                        <a class="previous btn-sm btn btn-light-primary"
                                            onclick="plusSlides(1)">{{ __('Pre') }}</a>
                                        <a class="text" style="margin: 2px 10px"></a>
                                        <a class="next btn-sm btn btn-light-primary" style="pointer-events:none"
                                            onclick="plusSlides(-1)">{{ __('Next') }}</a>
                                    </div>
                                @else
                                    <div class="col-lg-2 col-md-2 col-sm-2 slider-button " style="display: none;">
                                        <a class="previous btn-sm btn btn-light-primary"
                                            onclick="plusSlides(1)">{{ __('Pre') }}</a>
                                        <a class="text" style="margin: 2px 10px"></a>
                                        <a class="next btn-sm btn btn-light-primary" style="pointer-events:none"
                                            onclick="plusSlides(-1)">{{ __('Next') }}</a>
                                    </div>
                                @endif
                                <div class="col-lg-8 col-md-8 col-sm-8 "></div>
                            </div>
                            <!--  -->
                            <div class="slideshow-container col-lg-12 col-md-12 col-sm-12 card-body p-2">
                                @if (count($history_data) > 0)
                                    @foreach ($history_data as $history)
                                        <div class="mySlides navbar-content" content="<?= $history->id ?>"
                                            id='content_<?= $history->id ?>'>
                                            <div style="float: right;margin-top: -45px;">
                                                @if (\Auth::user()->isAbleTo('ai document generate Word'))
                                                    <a href="#" class="btn-sm btn btn-light-info"
                                                        style="margin-left:5px" data-bs-toggle="tooltip"
                                                        data-bs-original-title="{{ __('Export as Word Document') }}"
                                                        onclick="exportWordDocrsponse(<?= $history->id ?>)"><i
                                                            class="ti ti-file-download"></i></a>
                                                @else
                                                    <a href="#" class="btn-sm btn btn-light-info"
                                                        style="margin-left:5px" data-bs-toggle="tooltip"
                                                        data-bs-original-title="{{ __('Export as Word Document') }}"
                                                        onclick="preventAccess()"><i class="ti ti-file-download"></i></a>
                                                @endif
                                                @if (\Auth::user()->isAbleTo('ai document copy'))
                                                    <a href="#" class="btn-sm btn btn-light-warning"
                                                        style="margin-left:5px" data-bs-toggle="tooltip"
                                                        data-bs-original-title="{{ __('Copy Response') }}"
                                                        onclick="copyresponse(<?= $history->id ?>)"><i
                                                            class="ti ti-copy"></i></a>
                                                @else
                                                    <a href="#" class="btn-sm btn btn-light-warning"
                                                        style="margin-left:5px" data-bs-toggle="tooltip"
                                                        data-bs-original-title="{{ __('Copy Response') }}"
                                                        onclick="preventAccess()"><i class="ti ti-copy"></i></a>
                                                @endif
                                            </div>
                                            <div style="height: 420px;overflow-y:auto;overflow-x:auto;"
                                                id="content_<?= $history->id ?>">

                                                <?= nl2br($history->content) ?>
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="data-not-found-div">
                                        <?php
                                            if(file_exists('Modules/TitanDocs/Resources/assets/upload/data-not-found.png')){
                                                $path = asset('Modules/TitanDocs/Resources/assets/upload/data-not-found.png');
                                        ?>
                                        <img src=" {{ $path }} " style="width: -webkit-fill-available;">
                                        <?php
                                            }

                                        ?>
                                    </div>
                                @endif
                            </div>
                            <div class="regenerate-section p-3"
                                style="display:@if (count($history_data) <= 0) none @endif">
                                <div class="col-md-12 text-end">

                                    <a class="btn btn-primary" id="regenerate-button" style="color: #fff"
                                        onclick="regenerate_response(<?= $document->id ?>,<?= $template->id ?>)">{{ __('Regenerate Response') }}</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    </div>
    </form>
    </div>
@endsection
@push('scripts')
    custom.js
    <script src="{{ asset('js/jquery.min.js') }}"></script>

    <script src="{{ asset('Modules/TitanDocs/Resources/assets/js/plugins/pdf/html2canvas.min.js') }}"></script>
    <script src="{{ asset('Modules/TitanDocs/Resources/assets/js/plugins/pdf/jspdf.umd.min.js') }}"></script>
    <script src="{{ asset('Modules/TitanDocs/Resources/assets/js/custom.js') }}"></script>
    <script type="text/javascript">
        function regenerate_response(prompt_id, template_id) {
            var token = $('meta[name="csrf-token"]').attr('content');
            $.ajax({
                url: '{{ url('/aidocument/regenerate/response') }}',
                method: "POST",
                data: {
                    "_token": token,
                    "prompt_id": prompt_id,
                    "template_id": template_id
                },
                beforeSend: function() {
                    $("#regenerate-button").empty();
                    var html = '<span class="spinner-grow spinner-grow-sm" role="status"></span>';
                    $("#regenerate-button").append(html);
                    $('#regenerate-button').prop('disabled', true);
                },
                success: function(data) {
                    if (data['status'] == 'success') {
                        if ($('.data-not-found-div').length) {
                            $('.data-not-found-div').remove();
                        }

                        let formatted_text_response = nl2br(data['text']);
                        $('#regenerate-button').prop('disabled', false);
                        $("#regenerate-button").empty();
                        var html = `{{ __('Regenerate Response') }}`;
                        $("#regenerate-button").append(html);
                        $(".mySlides").css("display", "none");
                        var html =
                            '<div class="mySlides" style="display:block"><div style="float: right;margin-top: -45px;"><a href="#" class="btn-sm btn  btn-light-info" style="margin-left:5px" data-bs-toggle="tooltip" data-bs-original-title="{{ __('Export as Word Document') }}" onclick="exportWordDocrsponse(' +
                            data["id"] +
                            ')"><i class="ti ti-file-download"></i></a><a class="btn-sm btn  btn-light-warning" style="margin-left:5px" data-bs-toggle="tooltip" data-bs-original-title="{{ __('Copy Response') }}" href="#" class="btn-sm btn-primary" onclick="copyresponse(' +
                            data["id"] +
                            ')"><i class="ti ti-copy"></i></a> </div><div style="height: 420px;overflow-y:auto;overflow-x:auto;" id="content_' +
                            data["id"] + '">' + formatted_text_response + '</div></div>';

                        $(".slideshow-container").append(html);
                        $(".slider-button").css('display', "");
                        scrolled()
                        var total = $('#total_word').val();
                        var percentage = (data['current'] * 100) / total;
                    } else {
                        $('#regenerate-button').prop('disabled', false);
                        $("#regenerate-button").empty();
                        var html = `{{ __('Regenerate Response') }}`;
                        $("#regenerate-button").append(html);
                        toastrs('error', data['message'], 'error');
                    }
                },
                error: function(data) {
                    $('#regenerate-button').prop('disabled', false);
                    $("#regenerate-button").empty();
                    var html = `{{ __('Regenerate Response') }}`;
                    $("#regenerate-button").append(html);
                    toastrs('error', data['responseJSON']['message'], 'error');
                }
            });
        }

        var slideIndex = 1;

        $('body').on('click', '#ai_generate', function(e) {
            e.preventDefault();
            let form = $("#myForm");
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '{{ url('/aidocument/process') }}',
                method: "POST",
                data: form.serialize(),
                beforeSend: function() {
                    $("#ai_generate").empty();
                    var html = '<span class="spinner-grow spinner-grow-sm" role="status"></span>';
                    $("#ai_generate").append(html);
                    $('#ai_generate').prop('disabled', true);
                },
                success: function(data) {
                    if (data['status'] == 'success') {
                        if ($('.data-not-found-div').length) {
                            $('.data-not-found-div').remove();
                        }

                        $('#ai_generate').prop('disabled', false);
                        $("#ai_generate").empty();
                        var html = `{{ __('Generate Text') }}`;
                        $("#ai_generate").append(html);
                        let formatted_text_response = nl2br(data['text']);
                        $(".mySlides").css("display", "none");
                        var html =
                            '<div class="mySlides" style="display:block"><div style="float: right;margin-top: -45px;"><a href="#" class="btn-sm btn  btn-light-info" style="margin-left:5px" data-bs-toggle="tooltip" data-bs-original-title="{{ __('Export as Word Document') }}" onclick="exportWordDocrsponse(' +
                            data["id"] +
                            ')"><i class="ti ti-file-download"></i></a><a class="btn-sm btn  btn-light-warning" style="margin-left:5px" data-bs-toggle="tooltip" data-bs-original-title="{{ __('Copy Response') }}" href="#" class="btn-sm btn-primary" onclick="copyresponse(' +
                            data["id"] +
                            ')"><i class="ti ti-copy"></i></a> </div><div style="height: 420px;overflow-y:auto;overflow-x:auto;" id="content_' +
                            data["id"] + '">' + formatted_text_response + '</div></div>';
                        $(".slideshow-container").append(html);
                        $(".slider-button").css('display', "");
                        $(".regenerate-section").css('display', "");
                        scrolled()

                    } else {
                        $('#ai_generate').prop('disabled', false);
                        $("#ai_generate").empty();
                        var html = `{{ __('Generate Text') }}`;
                        $("#ai_generate").append(html);
                        toastrs('error', data['message'], 'error');
                    }
                },
                error: function(data) {
                    $('#ai_generate').prop('disabled', false);
                    $("#ai_generate").empty();
                    var html = `{{ __('Generate Text') }}`;
                    $("#ai_generate").append(html);
                    toastrs('error', data['responseJSON']['message'], 'error');

                }
            });
        });

        function nl2br(str, is_xhtml) {
            var breakTag = (is_xhtml || typeof is_xhtml === 'undefined') ? '<br />' : '<br>';
            return (str + '').replace(/([^>\r\n]?)(\r\n|\n\r|\r|\n)/g, '$1' + breakTag + '$2');
        }
        showSlides(slideIndex);

        function plusSlides(n) {

            showSlides(slideIndex += n);
        }

        function currentSlide(n) {
            showSlides(slideIndex = n);
        }

        function showSlides(n) {
            var i;
            var slides = document.getElementsByClassName("mySlides");
            if (n > slides.length) {
                slideIndex = 1
            }
            if (n < 1) {
                slideIndex = slides.length
            }
            for (i = 0; i < slides.length; i++) {
                slides[i].style.display = "none";
            }

            slides[slideIndex - 1].style.display = "block";
            var limit = $('.mySlides').length;
            if (limit == n) {
                $('.previous').css('pointer-events', 'none');
            } else {
                $('.previous').css('pointer-events', 'all');

            }
            if (n != 1) {
                $('.next').css('pointer-events', 'all');
            } else {
                $('.next').css('pointer-events', 'none');
            }
        }

        function save_doc(id) {
            var document_name = $('#document_name').val();
            var token = $('meta[name="csrf-token"]').attr('content');
            $.ajax({
                url: '{{ url('/aidocument/save') }}',
                method: "POST",
                data: {
                    "_token": token,
                    "doc_id": id,
                    "document_name": document_name
                },
                success: function(data) {
                    if (data == 1) {
                        toastrs('success', __('Document Save successfully'), 'success');
                    } else {
                        toastrs('error', __('Something went wrong'), 'error');
                    }

                }
            });
        }


        function copyresponse(id) {
            var source = $('#content_' + id)[0];
            var range = document.createRange();
            range.selectNode(source);
            var selection = window.getSelection();
            selection.removeAllRanges();
            selection.addRange(range);

            try {
                var success = document.execCommand('copy');

                if (success) {
                    toastrs('success', __('Result text has been copied successfully'), 'success');
                } else {
                    toastrs('error', __('Failed to copy content to clipboard. Please try again or manually copy the content.'),
                        'error');

                }
            } catch (err) {

                toastrs('error', __('Unable to copy content to clipboard. Please try manually copying the content.'), 'error');
            }

            // Clear the selection
            selection.removeAllRanges();

        }

        function copyallresponse(id) {

            var source = $('#content_' + id)[0];
            var range = document.createRange();
            range.selectNode(source);
            var selection = window.getSelection();
            selection.removeAllRanges();
            selection.addRange(range);

            try {
                var success = document.execCommand('copy');

                if (success) {
                    toastrs('success', __('Result text has been copied successfully'), 'success');
                } else {
                    toastrs('error', __('Failed to copy content to clipboard. Please try again or manually copy the content.'),
                        'error');

                }
            } catch (err) {

                toastrs('error', __('Unable to copy content to clipboard. Please try manually copying the content.'), 'error');
            }

            // Clear the selection
            selection.removeAllRanges();
        }

        function preventAccess() {

            toastrs('error', __('Permission Denied.'), 'error');
        }
    </script>
@endpush