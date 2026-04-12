@extends('layouts.app')

@section('content')
    <div class="content-wrapper">
        <div class="row">
            <div class="col-lg-12 col-sm-12 p-0">
                <div class="d-flex justify-content-between action-bar">
                    <div id="table-actions">
                        <h4 class="f-21 font-weight-normal text-capitalize mb-0">
                            @lang('titantheme::titantheme.customizer_settings')
                        </h4>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-lg-8 col-md-10 col-12">
                <div class="card">
                    <div class="card-body">
                        <form
                            method="POST"
                            action="{{ route('titantheme.customizer.setting.update') }}"
                        >
                            @csrf
                            @method('POST')

                            <div class="form-group mb-4">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div>
                                        <label class="f-14 font-weight-bold text-dark-grey mb-0">
                                            @lang('titantheme::titantheme.show_live_customizer')
                                        </label>
                                        <p class="f-12 text-light-text mt-1 mb-0">
                                            @lang('titantheme::titantheme.show_live_customizer_help')
                                        </p>
                                    </div>
                                    <div class="ml-3">
                                        <label class="switch">
                                            <input
                                                type="checkbox"
                                                name="show_live_customizer"
                                                id="show_live_customizer"
                                                {{ setting('show_live_customizer', '1') == '1' ? 'checked' : '' }}
                                            >
                                            <span class="slider round"></span>
                                        </label>
                                    </div>
                                </div>

                                <div class="alert alert-info mt-3 f-12 mb-0">
                                    @lang('titantheme::titantheme.customizer_settings_info')
                                </div>
                            </div>

                            <div class="form-group text-right">
                                <button type="submit" class="btn btn-primary btn-sm f-14">
                                    @lang('app.save')
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
