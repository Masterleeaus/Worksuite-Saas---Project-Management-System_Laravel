<div class="modal-header">
    <h5 class="modal-title">@lang('payroll::app.editRateConfig')</h5>
    <button type="button" class="close" data-dismiss="modal">×</button>
</div>
<form id="updateRateConfig" method="POST"
      action="{{ route('cleaner-rate-configs.update', $config->id) }}">
    @csrf
    @method('PUT')
    <div class="modal-body">
        <div class="row">
            <div class="col-md-6">
                <x-forms.select name="user_id" :fieldLabel="__('payroll::app.employee') . ' (blank = Global Default)'" :fieldRequired="false">
                    <option value="">-- Global Default --</option>
                    @foreach($employees as $emp)
                        <option value="{{ $emp->id }}" @if($config->user_id == $emp->id) selected @endif>{{ $emp->name }}</option>
                    @endforeach
                </x-forms.select>
            </div>
            <div class="col-md-6">
                <x-forms.input-group>
                    <x-forms.label for="contract_ref">@lang('payroll::app.contractRef') <small>(optional)</small></x-forms.label>
                    <input class="form-control" type="text" id="contract_ref" name="contract_ref" value="{{ $config->contract_ref }}">
                </x-forms.input-group>
            </div>
            <div class="col-md-6">
                <x-forms.input-group>
                    <x-forms.label for="base_rate" fieldRequired="true">@lang('payroll::app.baseRate') ($/hr)</x-forms.label>
                    <input class="form-control" type="number" step="0.01" min="0" id="base_rate" name="base_rate" value="{{ $config->base_rate }}" required>
                </x-forms.input-group>
            </div>
            <div class="col-md-6">
                <x-forms.input-group>
                    <x-forms.label for="night_rate_cutoff" fieldRequired="true">@lang('payroll::app.nightRateCutoff')</x-forms.label>
                    <input class="form-control" type="time" id="night_rate_cutoff" name="night_rate_cutoff" value="{{ substr($config->night_rate_cutoff, 0, 5) }}" required>
                </x-forms.input-group>
            </div>
            <div class="col-md-6">
                <x-forms.input-group>
                    <x-forms.label for="night_rate_multiplier" fieldRequired="true">@lang('payroll::app.nightRateMultiplier')</x-forms.label>
                    <input class="form-control" type="number" step="0.01" min="1" id="night_rate_multiplier" name="night_rate_multiplier" value="{{ $config->night_rate_multiplier }}" required>
                </x-forms.input-group>
            </div>
            <div class="col-md-6">
                <x-forms.input-group>
                    <x-forms.label for="saturday_multiplier" fieldRequired="true">@lang('payroll::app.saturdayMultiplier')</x-forms.label>
                    <input class="form-control" type="number" step="0.01" min="1" id="saturday_multiplier" name="saturday_multiplier" value="{{ $config->saturday_multiplier }}" required>
                </x-forms.input-group>
            </div>
            <div class="col-md-6">
                <x-forms.input-group>
                    <x-forms.label for="sunday_multiplier" fieldRequired="true">@lang('payroll::app.sundayMultiplier')</x-forms.label>
                    <input class="form-control" type="number" step="0.01" min="1" id="sunday_multiplier" name="sunday_multiplier" value="{{ $config->sunday_multiplier }}" required>
                </x-forms.input-group>
            </div>
            <div class="col-md-6">
                <x-forms.input-group>
                    <x-forms.label for="public_holiday_multiplier" fieldRequired="true">@lang('payroll::app.publicHolidayMultiplier')</x-forms.label>
                    <input class="form-control" type="number" step="0.01" min="1" id="public_holiday_multiplier" name="public_holiday_multiplier" value="{{ $config->public_holiday_multiplier }}" required>
                </x-forms.input-group>
            </div>
            <div class="col-md-6">
                <x-forms.input-group>
                    <x-forms.label for="public_holiday_fixed_rate">@lang('payroll::app.publicHolidayFixedRate') ($/hr)</x-forms.label>
                    <input class="form-control" type="number" step="0.01" min="0" id="public_holiday_fixed_rate" name="public_holiday_fixed_rate" value="{{ $config->public_holiday_fixed_rate }}" placeholder="Leave blank to use multiplier">
                </x-forms.input-group>
            </div>
            <div class="col-md-6">
                <x-forms.input-group>
                    <x-forms.label for="commission_per_room">@lang('payroll::app.commissionPerRoom') ($/room)</x-forms.label>
                    <input class="form-control" type="number" step="0.01" min="0" id="commission_per_room" name="commission_per_room" value="{{ $config->commission_per_room }}">
                </x-forms.input-group>
            </div>
            <div class="col-md-6">
                <x-forms.select name="is_active" :fieldLabel="__('app.status')" :fieldRequired="true">
                    <option value="1" @if($config->is_active) selected @endif>@lang('app.active')</option>
                    <option value="0" @if(!$config->is_active) selected @endif>@lang('app.inactive')</option>
                </x-forms.select>
            </div>
            <div class="col-md-12">
                <x-forms.input-group>
                    <x-forms.label for="notes">@lang('app.notes')</x-forms.label>
                    <textarea class="form-control" id="notes" name="notes" rows="2">{{ $config->notes }}</textarea>
                </x-forms.input-group>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <x-forms.button-cancel data-dismiss="modal">@lang('app.cancel')</x-forms.button-cancel>
        <x-forms.button-primary id="updateRateConfigBtn" icon="check">@lang('app.update')</x-forms.button-primary>
    </div>
</form>
<script>
    $('#updateRateConfigBtn').click(function () {
        $.easyAjax({
            url: $('#updateRateConfig').attr('action'),
            container: '#updateRateConfig',
            type: 'POST',
            data: $('#updateRateConfig').serialize(),
            success: function (data) {
                if (data.status === 'success') {
                    window.location.reload();
                }
            }
        });
    });
</script>
