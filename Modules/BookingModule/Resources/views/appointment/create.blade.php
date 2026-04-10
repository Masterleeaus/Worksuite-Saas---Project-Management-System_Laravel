{{ Form::open(['url' => 'appointments']) }}
<div class="modal-body">
    <div class="row">
        <div class="col-md-6 form-group">
            {{ Form::label('appointment_name', __('Appointment Name'), ['class' => 'col-form-label']) }}
            {{ Form::text('appointment_name', '', ['class' => 'form-control', 'required' => 'required', 'placeholder' => 'Enter Appointment Name']) }}
        </div>

        <div class="col-md-6 form-group">
            {{ Form::label('appointment_type', __('Appointment Type'), ['class' => 'col-form-label']) }}
            {{ Form::select('appointment_type', $appointment_type, null, ['class' => 'form-control', 'required' => 'required', 'id' => 'appointment_id', 'placeholder' => 'Select Appointment Type']) }}
        </div>

        {{-- <div class="col-md-6 form-group">
            {{ Form::label('date', __('Appointment Date'), ['class' => 'col-form-label']) }}
            {{ Form::date('date', null, ['class' => 'form-control ', 'required' => 'required', 'autocomplete' => 'off', 'placeholder' => 'Select appointment date']) }}
        </div> --}}

        <div class="col-md-12 form-group">
            {{ Form::label('value', __('Week Day'), ['class' => 'col-form-label']) }}
            <span id="weekday_id_span">
                <select class="multi-select weekday_data choices" id="week_day" data-toggle="select2" required
                    name="week_day[]" multiple="multiple">
                    @foreach ($week_days as $key => $week_day)
                        <option value="">{{ __('Select Week') }}</option>
                        <option value="{{ $key }}">{{ $week_day }}</option>
                    @endforeach
                </select>
            </span>
        </div>
        {{-- <div class="col-6">
            <div class="form-group">
                {{ Form::label('start_time', __('Start Time'), ['class' => 'col-form-label']) }}
                {{ Form::time('start_time', null, ['class' => 'form-control ', 'id' => 'start_time', 'required' => 'required']) }}
            </div>
        </div>
        <div class="col-6">
            <div class="form-group">
                {{ Form::label('end_time', __('End Time'), ['class' => 'col-form-label']) }}
                {{ Form::time('end_time', null, ['class' => 'form-control ', 'id' => 'end_time', 'required' => 'required']) }}
            </div>
        </div> --}}

        <div class="form-group col-md-12">
            <h6>{{ __('Questions') }}</h6>
            @foreach ($question as $q)
                <div class="form-check custom-checkbox">
                    <input type="checkbox" class="form-check-input" name="question_id[]" value="{{ $q->id }}"
                        @if ($q->is_required == 'on') required @endif id="question_{{ $q->id }}">
                    <label class="form-check-label" for="question_{{ $q->id }}">{{ $q->question }}
                        @if ($q->is_required == 'on')
                            <span class="text-danger">*</span>
                        @endif
                    </label>
                </div>
            @endforeach
        </div>

        <div class="col-md-6 form-group">
            {{ Form::label('enable', __('Enable:'), ['class' => 'col-form-label']) }}
            <div class="form-check form-switch custom-switch-v1">
                <input type="hidden" name="is_enabled" value="off">
                <input type="checkbox" class="form-check-input input-primary" id="customswitchv1-1 is_enabled"
                    name="is_enabled">
            </div>
        </div>
    </div>
</div>

<div class="modal-footer">
    <button type="button" class="btn  btn-light" data-bs-dismiss="modal">{{ __('Close') }}</button>
    <button type="submit" class="btn  btn-primary">{{ __('Create') }}</button>

</div>
{{ Form::close() }}
