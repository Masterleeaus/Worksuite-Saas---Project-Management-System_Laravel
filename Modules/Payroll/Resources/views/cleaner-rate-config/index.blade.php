@extends('layouts.app')

@section('content')
<div class="w-100 d-flex">
    <x-setting-sidebar :activeMenu="$activeSettingMenu"/>
    <x-setting-card>
        <x-slot name="header">
            <div class="s-b-n-header" id="tabs">
                <h3 class="mb-0 p-20 f-21 font-weight-normal text-capitalize">
                    @lang('payroll::app.menu.cleanerRateConfig')
                </h3>
            </div>
        </x-slot>

        <x-slot name="buttons">
            <x-forms.button-primary icon="plus" id="addRateConfig" class="mb-2">
                @lang('app.addNew')
            </x-forms.button-primary>
        </x-slot>

        <x-slot name="card">
            <div class="col-lg-12 p-4">
                <div class="table-responsive">
                    <table class="table table-hover border-0 w-100 dataTable">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>@lang('app.employee') <small class="text-muted">(blank = global)</small></th>
                                <th>@lang('payroll::app.contractRef')</th>
                                <th>@lang('payroll::app.baseRate') ($/hr)</th>
                                <th>@lang('payroll::app.nightRateMultiplier')</th>
                                <th>@lang('payroll::app.saturdayMultiplier')</th>
                                <th>@lang('payroll::app.sundayMultiplier')</th>
                                <th>@lang('payroll::app.publicHolidayMultiplier')</th>
                                <th>@lang('payroll::app.commissionPerRoom')</th>
                                <th>@lang('app.status')</th>
                                <th>@lang('app.action')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($configs as $config)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{!! optional($config->user)->name ?? '<em class="text-muted">Global Default</em>' !!}</td>
                                <td>{{ $config->contract_ref ?? '—' }}</td>
                                <td>${{ number_format($config->base_rate, 2) }}</td>
                                <td>{{ $config->night_rate_multiplier }}×</td>
                                <td>{{ $config->saturday_multiplier }}×</td>
                                <td>{{ $config->sunday_multiplier }}×</td>
                                <td>
                                    @if($config->public_holiday_fixed_rate)
                                        ${{ number_format($config->public_holiday_fixed_rate, 2) }}/hr
                                    @else
                                        {{ $config->public_holiday_multiplier }}×
                                    @endif
                                </td>
                                <td>${{ number_format($config->commission_per_room, 2) }}</td>
                                <td>
                                    @if($config->is_active)
                                        <span class="badge badge-success">@lang('app.active')</span>
                                    @else
                                        <span class="badge badge-secondary">@lang('app.inactive')</span>
                                    @endif
                                </td>
                                <td>
                                    <button class="btn btn-xs btn-outline-primary edit-rate-config" data-id="{{ $config->id }}">
                                        <i class="fa fa-edit"></i>
                                    </button>
                                    <form method="POST" action="{{ route('cleaner-rate-configs.destroy', $config->id) }}" class="d-inline">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-xs btn-outline-danger"
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

<div class="modal fade" id="rateConfigModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div id="rateConfigModalContent"></div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $('#addRateConfig').click(function () {
        $.ajax({
            url: '{{ route("cleaner-rate-configs.create") }}',
            success: function (data) {
                $('#rateConfigModalContent').html(data.html);
                $('#rateConfigModal').modal('show');
            }
        });
    });

    $('body').on('click', '.edit-rate-config', function () {
        var id = $(this).data('id');
        $.ajax({
            url: '{{ route("cleaner-rate-configs.edit", ":id") }}'.replace(':id', id),
            success: function (data) {
                $('#rateConfigModalContent').html(data.html);
                $('#rateConfigModal').modal('show');
            }
        });
    });
</script>
@endpush
