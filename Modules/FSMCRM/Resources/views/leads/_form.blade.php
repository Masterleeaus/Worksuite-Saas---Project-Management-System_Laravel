{{-- Shared form fields for Create / Edit Lead --}}

<div class="row g-3">
    {{-- Core CRM fields --}}
    <div class="col-md-8">
        <label class="form-label fw-semibold">Company Name <span class="text-danger">*</span></label>
        <input type="text" name="company_name" class="form-control @error('company_name') is-invalid @enderror"
               value="{{ old('company_name', $lead?->company_name) }}" required>
        @error('company_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="col-md-4">
        <label class="form-label fw-semibold">Stage <span class="text-danger">*</span></label>
        <select name="stage" class="form-select @error('stage') is-invalid @enderror" required>
            @foreach($stages as $key => $label)
                <option value="{{ $key }}" @selected(old('stage', $lead?->stage ?? 'new') === $key)>{{ $label }}</option>
            @endforeach
        </select>
        @error('stage') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="col-md-4">
        <label class="form-label">Client Name <span class="text-danger">*</span></label>
        <input type="text" name="client_name" class="form-control @error('client_name') is-invalid @enderror"
               value="{{ old('client_name', $lead?->client_name) }}" required>
        @error('client_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="col-md-4">
        <label class="form-label">Email</label>
        <input type="email" name="client_email" class="form-control @error('client_email') is-invalid @enderror"
               value="{{ old('client_email', $lead?->client_email) }}">
        @error('client_email') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="col-md-4">
        <label class="form-label">Phone</label>
        <input type="text" name="mobile" class="form-control @error('mobile') is-invalid @enderror"
               value="{{ old('mobile', $lead?->mobile) }}">
        @error('mobile') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="col-md-4">
        <label class="form-label">Expected Revenue</label>
        <input type="number" name="expected_revenue" step="0.01" min="0"
               class="form-control @error('expected_revenue') is-invalid @enderror"
               value="{{ old('expected_revenue', $lead?->expected_revenue) }}">
        @error('expected_revenue') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="col-12">
        <label class="form-label">Notes / Requirements</label>
        <textarea name="note" rows="4" class="form-control @error('note') is-invalid @enderror">{{ old('note', $lead?->note) }}</textarea>
        @error('note') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    {{-- FSM fields --}}
    <div class="col-12"><hr><h6 class="text-muted">FSM Details</h6></div>

    <div class="col-md-6">
        <label class="form-label">FSM Location</label>
        <select name="fsm_location_id" class="form-select @error('fsm_location_id') is-invalid @enderror">
            <option value="">— None —</option>
            @foreach($locations as $loc)
                <option value="{{ $loc->id }}" @selected(old('fsm_location_id', $lead?->fsm_location_id) == $loc->id)>{{ $loc->name }}</option>
            @endforeach
        </select>
        @error('fsm_location_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="col-md-6">
        <label class="form-label">Service Type (Template)</label>
        <select name="fsm_service_type_id" class="form-select @error('fsm_service_type_id') is-invalid @enderror">
            <option value="">— None —</option>
            @foreach($templates as $tmpl)
                <option value="{{ $tmpl->id }}" @selected(old('fsm_service_type_id', $lead?->fsm_service_type_id) == $tmpl->id)>{{ $tmpl->name }}</option>
            @endforeach
        </select>
        @error('fsm_service_type_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="col-md-4">
        <label class="form-label">Number of Sites</label>
        <input type="number" name="site_count" min="1" class="form-control @error('site_count') is-invalid @enderror"
               value="{{ old('site_count', $lead?->site_count ?? 1) }}">
        @error('site_count') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="col-md-4">
        <label class="form-label">Estimated Hours / Visit</label>
        <input type="number" name="estimated_hours" step="0.25" min="0"
               class="form-control @error('estimated_hours') is-invalid @enderror"
               value="{{ old('estimated_hours', $lead?->estimated_hours) }}">
        @error('estimated_hours') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="col-md-4 d-flex align-items-end">
        <div class="form-check mb-2">
            <input class="form-check-input" type="checkbox" name="create_recurring" id="createRecurring" value="1"
                   @checked(old('create_recurring', $lead?->create_recurring))>
            <label class="form-check-label" for="createRecurring">
                Recurring contract?
            </label>
        </div>
    </div>
</div>
