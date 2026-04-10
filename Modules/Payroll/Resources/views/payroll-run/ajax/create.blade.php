<div class="modal-header">
    <h5 class="modal-title">@lang('payroll::app.newPayrollRun')</h5>
    <button type="button" class="close" data-dismiss="modal">×</button>
</div>
<form id="createPayrollRunForm" method="POST" action="{{ route('payroll-runs.store') }}">
    @csrf
    <div class="modal-body">
        <div class="row">
            <div class="col-md-6">
                <x-forms.input-group>
                    <x-forms.label for="period_start" fieldRequired="true">@lang('payroll::app.periodStart')</x-forms.label>
                    <input class="form-control" type="date" id="period_start" name="period_start" required>
                </x-forms.input-group>
            </div>
            <div class="col-md-6">
                <x-forms.input-group>
                    <x-forms.label for="period_end" fieldRequired="true">@lang('payroll::app.periodEnd')</x-forms.label>
                    <input class="form-control" type="date" id="period_end" name="period_end" required>
                </x-forms.input-group>
            </div>
            <div class="col-md-6">
                <x-forms.select name="state" :fieldLabel="__('payroll::app.state') . ' (for Public Holiday calendar)'" :fieldRequired="false">
                    <option value="">-- All States / National Only --</option>
                    @foreach($states as $s)
                        <option value="{{ $s }}">{{ $s }}</option>
                    @endforeach
                </x-forms.select>
            </div>
            <div class="col-md-12">
                <x-forms.input-group>
                    <x-forms.label for="run_notes">@lang('app.notes')</x-forms.label>
                    <textarea class="form-control" id="run_notes" name="notes" rows="2"></textarea>
                </x-forms.input-group>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <x-forms.button-cancel data-dismiss="modal">@lang('app.cancel')</x-forms.button-cancel>
        <x-forms.button-primary id="createPayrollRunBtn" icon="check">@lang('app.create')</x-forms.button-primary>
    </div>
</form>
<script>
    $('#createPayrollRunBtn').click(function () {
        $.easyAjax({
            url: $('#createPayrollRunForm').attr('action'),
            container: '#createPayrollRunForm',
            type: 'POST',
            data: $('#createPayrollRunForm').serialize(),
            success: function (data) {
                if (data.status === 'success' && data.redirectUrl) {
                    window.location.href = data.redirectUrl;
                }
            }
        });
    });
</script>
