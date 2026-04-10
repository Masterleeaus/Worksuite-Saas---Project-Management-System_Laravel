@php
    $company_settings = getCompanyAllSetting();
@endphp
{{ Form::open(['url' => 'schedules/changeaction', 'method' => 'post']) }}
<div class="modal-body">
    <div class="card-body pb-0 pt-2">
        <dl class="row mb-0 align-items-center">
            <dt class="col-sm-4 h6 text-sm">{{ __('Name') }}</dt>
            <dd class="col-sm-8 text-sm">
                {{ !empty($schedule->name) ? $schedule->name : '' }}
            </dd>
            <dt class="col-sm-4 h6 text-sm">{{ __('Email') }}</dt>
            <dd class="col-sm-8 text-sm">
                {{ !empty($schedule->email) ? $schedule->email : '' }}
            </dd>
            <dt class="col-sm-4 h6 text-sm">{{ __('Phone') }}</dt>
            <dd class="col-sm-8 text-sm">
                {{ !empty($schedule->phone) ? $schedule->phone : '' }}
            </dd>
            <dt class="col-sm-4 h6 text-sm">{{ __('Date') }}</dt>
            <dd class="col-sm-8 text-sm">
                {{ company_date_formate($schedule->date) }}
            </dd>
            <dt class="col-sm-4 h6 text-sm">{{ __('Start Time') }}</dt>
            <dd class="col-sm-8 text-sm">
                {{ company_time_formate($schedule->start_time) }}
            </dd>
            <dt class="col-sm-4 h6 text-sm">{{ __('End Time') }}</dt>
            <dd class="col-sm-8 text-sm">
                {{ company_time_formate($schedule->end_time) }}
            </dd>
            <dt class="col-sm-4 h6 text-sm">{{ __('Appointment') }}</dt>
            <dd class="col-sm-8 text-sm">
                {{ !empty($schedule->appointment_id) ? $schedule->appointment->name : '' }}
            </dd>
            <dt class="col-sm-4 h6 text-sm">{{ __('Status') }}</dt>
            <dd class="col-sm-8 text-sm">
                {{ !empty($schedule->status) ? $schedule->status : '' }}
            </dd>
            <dt class="col-sm-4 h6 text-sm">{{ __('Meeting Type') }}</dt>
            <dd class="col-sm-8 text-sm">
                {{ !empty($schedule->meeting_type) ? $schedule->meeting_type : '-' }}<br>

                @if (
                    $schedule->meeting_type == 'Zoom Meeting' &&
                        (empty($company_settings['zoom_account_id']) ||
                            empty($company_settings['zoom_client_id']) ||
                            empty($company_settings['zoom_client_secret'])))
                    <span class="text-danger">{{ __('Please first add zoom meeting credential ') }}<a
                            href="{{ url('settings#zoom-sidenav') }}" target="_blank">{{ __('here') }}</a>.</span>
                @endif

                {{-- @if (!empty(check_file($company_settings['google_meet_json_file'])) && !empty($company_settings['google_meet_json_file']))
                    <span
                        class="text-danger">{{ __('You have not authorized your google account to Create Google Meeting. Click ') }}
                        <a href="{{ route('auth.googlemeet') }}"
                            target="_blank">{{ __('here') }}</a>{{ __(' to authorize.') }}</span>
                @endif --}}

                @if (
                    $schedule->meeting_type == 'Google Meet' &&
                        (empty(check_file(company_setting('google_meet_json_file'))) &&
                            empty(company_setting('google_meet_json_file'))))
                    <span class="text-danger">{{ __('Please first add Google meet credential ') }}<a
                            href="{{ url('settings#googlemeet-sidenav') }}"
                            target="_blank">{{ __('here') }}</a>.</span>
                @endif

            </dd>

        </dl>
    </div>
    @if (!empty($questions))
        <div class="card-body pb-0 pt-2">
            <hr style="color: #e3e3e3;">
            <h6 class="mb-4">{{ __('Questions') }}</h6>
            <dl class="row mb-0 align-items-center">
                @foreach ($questions as $key => $question)
                    <dt class="col-sm-6 h6 text-sm">{{ $key }}</dt>
                    <dd class="col-sm-6 text-sm">
                        @if (is_array($question))
                            {{ implode(', ', $question) }}
                        @else
                            {{ $question }}
                        @endif
                    </dd>
                @endforeach
            </dl>
        </div>
    @endif

    @if (!empty($users))
        <div class="card-body pb-0 pt-2">
            <hr style="color: #e3e3e3;">
            <h6 class="mb-3">{{ __('Staff assignment') }}</h6>

            <form method="POST" action="{{ route('appointment.schedules.assign', \Crypt::encrypt($schedule->id)) }}">
                @csrf
                <div class="row g-2 align-items-end">
                    <div class="col-md-8">
                        <label class="form-label">{{ __('Assigned staff') }}</label>
                        <select class="form-control" name="assigned_to" id="assignedToDropdown">
                            <option value="">{{ __('Unassigned') }}</option>
                            @foreach ($users as $key => $user)
                                <option value="{{ $key }}" {{ (int)$schedule->effective_assignee_id === (int)$key ? 'selected' : '' }}>
                                    {{ $user }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <button type="submit" class="btn btn-primary w-100">{{ __('bookingmodule::assignment.actions.save') }}</button>
                    </div>
                    <div class="col-12">
                        <label class="form-label">{{ __('Note') }}</label>
                        <input class="form-control" name="note" placeholder="{{ __('Optional note') }}" />
                    </div>
                </div>
            </form>

            @if (!empty($schedule->assignments) && $schedule->assignments->count() > 0)
                <div class="mt-3">
                    <h6 class="mb-2">{{ __('bookingmodule::assignment.actions.history') }}</h6>
                    <ul class="list-group">
                        @foreach ($schedule->assignments as $a)
                            <li class="list-group-item d-flex justify-content-between">
                                <div>
                                    <strong>{{ ucfirst($a->action) }}</strong>
                                    <span class="text-muted">#{{ $a->id }}</span>
                                    @if (!empty($a->note))
                                        <div class="text-muted">{{ $a->note }}</div>
                                    @endif
                                </div>
                                <div class="text-muted">{{ company_date_formate($a->created_at) }}</div>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>
    @endif

    <div class="card-body pb-0 pt-2">
        <hr style="color: #e3e3e3;">
        <h6 class="mb-3">{{ __('bookingmodule::assignment.labels.assigned_to') }}</h6>
        <div class="col-md-10">
            <select class="form-control" name="user_id" id="userDropdown">
                <option value="">{{ __('Unassigned') }}</option>
                @foreach ($users as $key => $user)
                    <option value="{{ $key }}" {{ ((int)($schedule->effective_assignee_id) === (int)$key) ? 'selected' : '' }}>
                        {{ $user }}
                    </option>
                @endforeach
            </select>
            <small class="text-muted">{{ __('This staff member will own the booking.') }}</small>
        </div>
    </div>

    <input type="hidden" value="{{ $schedule->id }}" name="schedule_id">


</div>

@if(\Modules\BookingModule\Support\AppointmentPermission::check(auth()->user(), 'schedule action'))
    @if ($schedule->status == 'Pending')
        <div class="modal-footer">
            <input type="submit" value="Approved" class="btn btn-success rounded" name="status">
            <input type="submit" value="Reject" class="btn btn-danger rounded" name="status" id="rejectButton">
        </div>
    @elseif($schedule->status == 'Approved')
        <div class="modal-footer">
            <input type="submit" value="Complete" class="btn btn-info rounded" name="status">
        </div>
    @endif
@endif

{{ Form::close() }}

<script>
    // Disable the user dropdown used by the legacy approval flow when rejecting.
    const reject = document.getElementById('rejectButton');
    if (reject) {
        reject.addEventListener('click', function() {
            const dd = document.getElementById('userDropdown');
            if (dd) dd.disabled = true;
        });
    }
</script>
