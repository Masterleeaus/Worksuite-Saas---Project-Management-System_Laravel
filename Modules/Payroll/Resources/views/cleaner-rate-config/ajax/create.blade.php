<div class="modal-header">
    <h5 class="modal-title">@lang('payroll::app.addRateConfig')</h5>
    <button type="button" class="close" data-dismiss="modal">×</button>
</div>
<form id="saveRateConfig" method="POST" action="{{ route('cleaner-rate-configs.store') }}">
    @csrf
    <div class="modal-body">
        <div class="row">
            <div class="col-md-6">
                <x-forms.select name="user_id" :fieldLabel="__('payroll::app.employee') . ' (blank = Global Default)'" :fieldRequired="false">
                    <option value="">-- Global Default --</option>
                    @foreach($employees as $emp)
                        <option value="{{ $emp->id }}">{{ $emp->name }}</option>
                    @endforeach
                </x-forms.select>
            </div>
            <div class="col-md-6">
                <x-forms.input-group>
                    <x-forms.label for="contract_ref">@lang('payroll::app.contractRef') <small>(optional)</small></x-forms.label>
                    <input class="form-control" type="text" id="contract_ref" name="contract_ref" placeholder="e.g. CLIENT-001">
                </x-forms.input-group>
            </div>
            <div class="col-md-6">
                <x-forms.input-group>
                    <x-forms.label for="base_rate" fieldRequired="true">@lang('payroll::app.baseRate') ($/hr)</x-forms.label>
                    <input class="form-control" type="number" step="0.01" min="0" id="base_rate" name="base_rate" value="25.00" required>
                </x-forms.input-group>
            </div>
            <div class="col-md-6">
                <x-forms.input-group>
                    <x-forms.label for="night_rate_cutoff" fieldRequired="true">@lang('payroll::app.nightRateCutoff')</x-forms.label>
                    <input class="form-control" type="time" id="night_rate_cutoff" name="night_rate_cutoff" value="22:00" required>
                </x-forms.input-group>
            </div>
            <div class="col-md-6">
                <x-forms.input-group>
                    <x-forms.label for="night_rate_multiplier" fieldRequired="true">@lang('payroll::app.nightRateMultiplier')</x-forms.label>
                    <input class="form-control" type="number" step="0.01" min="1" id="night_rate_multiplier" name="night_rate_multiplier" value="1.25" required>
                </x-forms.input-group>
            </div>
            <div class="col-md-6">
                <x-forms.input-group>
                    <x-forms.label for="saturday_multiplier" fieldRequired="true">@lang('payroll::app.saturdayMultiplier')</x-forms.label>
                    <input class="form-control" type="number" step="0.01" min="1" id="saturday_multiplier" name="saturday_multiplier" value="1.25" required>
                </x-forms.input-group>
            </div>
            <div class="col-md-6">
                <x-forms.input-group>
                    <x-forms.label for="sunday_multiplier" fieldRequired="true">@lang('payroll::app.sundayMultiplier')</x-forms.label>
                    <input class="form-control" type="number" step="0.01" min="1" id="sunday_multiplier" name="sunday_multiplier" value="1.50" required>
                </x-forms.input-group>
            </div>
            <div class="col-md-6">
                <x-forms.input-group>
                    <x-forms.label for="public_holiday_multiplier" fieldRequired="true">@lang('payroll::app.publicHolidayMultiplier')</x-forms.label>
                    <input class="form-control" type="number" step="0.01" min="1" id="public_holiday_multiplier" name="public_holiday_multiplier" value="2.25" required>
                </x-forms.input-group>
            </div>
            <div class="col-md-6">
                <x-forms.input-group>
                    <x-forms.label for="public_holiday_fixed_rate">@lang('payroll::app.publicHolidayFixedRate') ($/hr, overrides multiplier)</x-forms.label>
                    <input class="form-control" type="number" step="0.01" min="0" id="public_holiday_fixed_rate" name="public_holiday_fixed_rate" placeholder="Leave blank to use multiplier">
                </x-forms.input-group>
            </div>
            <div class="col-md-6">
                <x-forms.input-group>
                    <x-forms.label for="commission_per_room">@lang('payroll::app.commissionPerRoom') ($/room, optional)</x-forms.label>
                    <input class="form-control" type="number" step="0.01" min="0" id="commission_per_room" name="commission_per_room" value="0">
                </x-forms.input-group>
            </div>
            <div class="col-md-12">
                <x-forms.input-group>
                    <x-forms.label for="notes">@lang('app.notes')</x-forms.label>
                    <textarea class="form-control" id="notes" name="notes" rows="2"></textarea>
                </x-forms.input-group>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <x-forms.button-cancel data-dismiss="modal">@lang('app.cancel')</x-forms.button-cancel>
        <x-forms.button-primary id="saveRateConfigBtn" icon="check">@lang('app.save')</x-forms.button-primary>
    </div>
</form>
<script>
    $('#saveRateConfigBtn').click(function () {
        $.easyAjax({
            url: $('#saveRateConfig').attr('action'),
            container: '#saveRateConfig',
            type: 'POST',
            data: $('#saveRateConfig').serialize(),
            success: function (data) {
                if (data.status === 'success') {
                    window.location.reload();
                }
            }
        });
    });
</script>
