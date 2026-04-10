@extends('layouts.app')

@section('content')
<div class="w-100 d-flex">
    <x-setting-sidebar :activeMenu="$activeSettingMenu"/>
    <x-setting-card>
        <x-slot name="header">
            <div class="s-b-n-header">
                <h3 class="mb-0 p-20 f-21 font-weight-normal text-capitalize">
                    @lang('payroll::app.menu.publicHolidays')
                </h3>
                <div class="d-flex p-20 pt-0">
                    <form method="GET" action="{{ route('public-holidays.index') }}" class="d-flex gap-2">
                        <select name="year" class="form-control mr-2">
                            @for($y = now()->year - 1; $y <= now()->year + 2; $y++)
                                <option value="{{ $y }}" @if($y == $year) selected @endif>{{ $y }}</option>
                            @endfor
                        </select>
                        <select name="state" class="form-control mr-2">
                            <option value="">-- All States --</option>
                            @foreach($states as $s)
                                <option value="{{ $s }}" @if($s == $selectedState) selected @endif>{{ $s }}</option>
                            @endforeach
                        </select>
                        <button type="submit" class="btn btn-outline-secondary">@lang('app.filter')</button>
                    </form>
                </div>
            </div>
        </x-slot>

        <x-slot name="buttons">
            <x-forms.button-primary icon="plus" id="addPublicHoliday" class="mb-2">
                @lang('app.addNew') @lang('payroll::app.menu.publicHoliday')
            </x-forms.button-primary>
        </x-slot>

        <x-slot name="card">
            <div class="col-lg-12 p-4">
                <div class="table-responsive">
                    <table class="table table-hover border-0 w-100 dataTable">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>@lang('app.date')</th>
                                <th>@lang('app.name')</th>
                                <th>@lang('payroll::app.state')</th>
                                <th>@lang('app.type')</th>
                                <th>@lang('app.action')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($holidays as $holiday)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $holiday->holiday_date->format(company()->date_format ?? 'd-m-Y') }}</td>
                                <td>{{ $holiday->name }}</td>
                                <td>{{ $holiday->state ?? 'National' }}</td>
                                <td>
                                    @if($holiday->is_manual)
                                        <span class="badge badge-info">Manual</span>
                                    @else
                                        <span class="badge badge-secondary">System</span>
                                    @endif
                                </td>
                                <td>
                                    <form method="POST" action="{{ route('public-holidays.destroy', $holiday->id) }}" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm"
                                            onclick="return confirm('@lang('messages.confirmDelete')')">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </x-slot>
    </x-setting-card>
</div>

<div class="modal fade" id="publicHolidayModal" tabindex="-1">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">@lang('payroll::app.addPublicHoliday')</h5>
                <button type="button" class="close" data-dismiss="modal">×</button>
            </div>
            <form id="savePublicHoliday" method="POST" action="{{ route('public-holidays.store') }}">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <x-forms.input-group>
                                <x-forms.label for="holiday_date" fieldRequired="true">@lang('app.date')</x-forms.label>
                                <input class="form-control" type="date" id="holiday_date" name="holiday_date" required>
                            </x-forms.input-group>
                        </div>
                        <div class="col-md-6">
                            <x-forms.input-group>
                                <x-forms.label for="holiday_name" fieldRequired="true">@lang('app.name')</x-forms.label>
                                <input class="form-control" type="text" id="holiday_name" name="name" required>
                            </x-forms.input-group>
                        </div>
                        <div class="col-md-6">
                            <x-forms.select name="state" :fieldLabel="__('payroll::app.state') . ' (blank = National)'" :fieldRequired="false">
                                <option value="">-- National --</option>
                                @foreach($states as $s)
                                    <option value="{{ $s }}">{{ $s }}</option>
                                @endforeach
                            </x-forms.select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <x-forms.button-cancel data-dismiss="modal">@lang('app.cancel')</x-forms.button-cancel>
                    <x-forms.button-primary id="savePublicHolidayBtn" icon="check">@lang('app.save')</x-forms.button-primary>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $('#addPublicHoliday').click(function () {
        $('#publicHolidayModal').modal('show');
    });

    $('#savePublicHolidayBtn').click(function () {
        $.easyAjax({
            url: $('#savePublicHoliday').attr('action'),
            container: '#savePublicHoliday',
            type: 'POST',
            data: $('#savePublicHoliday').serialize(),
            success: function (data) {
                if (data.status === 'success') {
                    window.location.reload();
                }
            }
        });
    });
</script>
@endpush
