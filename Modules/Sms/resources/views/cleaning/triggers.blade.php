@extends('layouts.app')

@section('content')

<div class="w-100 d-flex">
    <x-setting-sidebar :activeMenu="$activeSettingMenu"/>

    <x-setting-card>
        <x-slot name="header">
            <div class="s-b-n-header" id="tabs">
                <h2 class="mb-0 p-20 f-21 font-weight-normal border-bottom-grey">
                    @lang('sms::modules.cleaningTriggers')
                </h2>
            </div>
        </x-slot>

        <div class="col-xl-10 col-lg-12 col-md-12 ntfcn-tab-content-left w-100 py-4">

            <form method="POST" action="{{ route('account.sms-cleaning-triggers.update') }}">
                @csrf
                @method('PUT')

                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>@lang('sms::modules.trigger')</th>
                                <th>@lang('sms::modules.enabledLabel')</th>
                                <th>@lang('sms::modules.messageTemplate')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($settings as $slugValue => $item)
                            @php
                                /** @var \Modules\Sms\Entities\SmsNotificationSetting $record */
                                $record = $item['record'];
                                /** @var \Modules\Sms\Enums\SmsNotificationSlug $slug */
                                $slug = $item['slug'];
                            @endphp
                            <tr>
                                <td class="align-middle f-14">
                                    <strong>{{ $slug->label() }}</strong>
                                    <br>
                                    <small class="text-muted">{{ $slug->value }}</small>
                                </td>
                                <td class="align-middle" style="width: 120px; text-align:center;">
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox"
                                               class="custom-control-input"
                                               id="enabled_{{ $slugValue }}"
                                               name="enabled[{{ $slugValue }}]"
                                               value="1"
                                               @if($record->send_sms === 'yes') checked @endif>
                                        <label class="custom-control-label" for="enabled_{{ $slugValue }}"></label>
                                    </div>
                                </td>
                                <td>
                                    <textarea
                                        name="template[{{ $slugValue }}]"
                                        rows="3"
                                        class="form-control f-13"
                                        placeholder="{{ $item['default_template'] }}"
                                    >{{ $record->custom_template ?? $item['default_template'] }}</textarea>
                                    <small class="text-muted">
                                        @lang('sms::modules.templateVariablesHint')
                                    </small>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-3">
                    <x-forms.button-primary id="save-triggers" class="mr-3" icon="check" type="submit">
                        @lang('app.save')
                    </x-forms.button-primary>
                    <a href="{{ route('account.sms-notification-log.index') }}" class="btn btn-secondary">
                        @lang('sms::modules.viewLog')
                    </a>
                    <a href="{{ route('account.sms-opt-outs.index') }}" class="btn btn-secondary ml-2">
                        @lang('sms::modules.manageOptOuts')
                    </a>
                </div>
            </form>

        </div>
    </x-setting-card>
</div>

@endsection
