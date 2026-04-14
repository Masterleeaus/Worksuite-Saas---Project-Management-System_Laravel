@extends('layouts.app')

@section('content')
    <div class="content-wrapper">
        <div class="row">
            <div class="col-lg-12 col-sm-12 p-0">
                <div class="d-flex justify-content-between action-bar">
                    <h4 class="f-21 font-weight-normal text-capitalize mb-0">
                        @lang('titantheme::titantheme.white_label')
                    </h4>
                </div>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-lg-10">
                <div class="card">
                    <div class="card-body">
                        <form id="white-label-form" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-md-4">
                                    <x-forms.file allowedFileExtensions="png jpg jpeg svg"
                                                  :fieldLabel="__('titantheme::titantheme.logo')"
                                                  fieldName="logo"
                                                  fieldId="logo"
                                                  :fieldValue="$logoUrl ?? null"/>
                                </div>
                                <div class="col-md-4">
                                    <x-forms.file allowedFileExtensions="ico png svg"
                                                  :fieldLabel="__('titantheme::titantheme.favicon')"
                                                  fieldName="favicon"
                                                  fieldId="favicon"
                                                  :fieldValue="$faviconUrl ?? null"/>
                                </div>
                                <div class="col-md-4">
                                    <x-forms.file allowedFileExtensions="png jpg jpeg svg"
                                                  :fieldLabel="__('titantheme::titantheme.login_bg')"
                                                  fieldName="login_bg"
                                                  fieldId="login_bg"
                                                  :fieldValue="$loginBgUrl ?? null"/>
                                </div>
                                <div class="col-md-12">
                                    <x-forms.text :fieldLabel="__('titantheme::titantheme.app_name')" fieldName="app_name"/>
                                </div>
                                <div class="col-md-6">
                                    <x-forms.email :fieldLabel="__('titantheme::titantheme.support_email')" fieldName="support_email"/>
                                </div>
                                <div class="col-md-6">
                                    <x-forms.text :fieldLabel="__('titantheme::titantheme.custom_domain')" fieldName="custom_domain"/>
                                </div>
                            </div>

                            <div class="text-right">
                                <x-forms.button-primary id="save-white-label" icon="check">
                                    @lang('app.save')
                                </x-forms.button-primary>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $('#save-white-label').click(function (e) {
            e.preventDefault();
            $.easyAjax({
                url: "{{ route('titantheme.white-label.update') }}",
                container: '#white-label-form',
                blockUI: true,
                type: "POST",
                file: true,
                data: new FormData(document.getElementById('white-label-form'))
            });
        });
    </script>
@endpush
