@extends('fsmcrm::layouts.master')

@section('fsmcrm_content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>Convert Lead to FSM Order</h2>
    <a href="{{ route('fsmcrm.leads.show', $lead->id) }}" class="btn btn-outline-secondary">← Back to Lead</a>
</div>

<div class="alert alert-info">
    Converting: <strong>{{ $lead->name }}</strong>
    @if($lead->contact_name)
        &mdash; {{ $lead->contact_name }}
    @endif
</div>

<div class="card">
    <div class="card-header fw-semibold">New FSM Order Details</div>
    <div class="card-body">
        <form method="POST" action="{{ route('fsmcrm.leads.convert.store', $lead->id) }}">
            @csrf

            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Location</label>
                    <select name="location_id" class="form-select @error('location_id') is-invalid @enderror">
                        <option value="">— None —</option>
                        @foreach($locations as $loc)
                            <option value="{{ $loc->id }}"
                                @selected(old('location_id', $lead->fsm_location_id) == $loc->id)>
                                {{ $loc->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('location_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">Stage</label>
                    <select name="stage_id" class="form-select @error('stage_id') is-invalid @enderror">
                        <option value="">— None —</option>
                        @foreach($stages as $stage)
                            <option value="{{ $stage->id }}" @selected(old('stage_id') == $stage->id)>{{ $stage->name }}</option>
                        @endforeach
                    </select>
                    @error('stage_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">Template / Service Type</label>
                    <select name="template_id" class="form-select @error('template_id') is-invalid @enderror">
                        <option value="">— None —</option>
                        @foreach($templates as $tmpl)
                            <option value="{{ $tmpl->id }}"
                                @selected(old('template_id', $lead->service_type_id) == $tmpl->id)>
                                {{ $tmpl->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('template_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-3">
                    <label class="form-label">Priority</label>
                    <select name="priority" class="form-select @error('priority') is-invalid @enderror">
                        <option value="0" @selected(old('priority', '0') === '0')>Normal</option>
                        <option value="1" @selected(old('priority') === '1')>Urgent</option>
                    </select>
                    @error('priority') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">Scheduled Start</label>
                    <input type="datetime-local" name="scheduled_date_start"
                           class="form-control @error('scheduled_date_start') is-invalid @enderror"
                           value="{{ old('scheduled_date_start', $lead->close_date?->format('Y-m-d') ? $lead->close_date->format('Y-m-d') . 'T08:00' : '') }}">
                    @error('scheduled_date_start') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">Scheduled End</label>
                    <input type="datetime-local" name="scheduled_date_end"
                           class="form-control @error('scheduled_date_end') is-invalid @enderror"
                           value="{{ old('scheduled_date_end') }}">
                    @error('scheduled_date_end') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-12">
                    <label class="form-label">Description</label>
                    <textarea name="description" rows="4"
                              class="form-control @error('description') is-invalid @enderror">{{ old('description', $lead->notes) }}</textarea>
                    @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                @if($canCreateAgreement && $lead->create_recurring)
                    <div class="col-12">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="create_agreement" id="createAgreement" value="1"
                                   @checked(old('create_agreement'))>
                            <label class="form-check-label fw-semibold text-info" for="createAgreement">
                                Create recurring service agreement after order?
                            </label>
                        </div>
                    </div>
                @endif
            </div>

            <div class="mt-4">
                <button type="submit" class="btn btn-success">✅ Create FSM Order</button>
                <a href="{{ route('fsmcrm.leads.show', $lead->id) }}" class="btn btn-outline-secondary ms-2">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
