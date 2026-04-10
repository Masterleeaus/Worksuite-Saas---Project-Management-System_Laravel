@if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="row g-3">
    <div class="col-md-6">
        <label class="form-label">{{ __('app.name') }} <span class="text-danger">*</span></label>
        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
               value="{{ old('name', optional($addon)->name) }}" required maxlength="191">
        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="col-md-3">
        <label class="form-label">{{ __('app.price') }} <span class="text-danger">*</span></label>
        <input type="number" name="price" class="form-control @error('price') is-invalid @enderror"
               value="{{ old('price', optional($addon)->price) }}" required min="0" step="0.01">
        @error('price') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="col-md-3">
        <label class="form-label">{{ __('app.durationExtra', [], 'Extra Duration (min)') }}</label>
        <input type="number" name="duration_extra" class="form-control @error('duration_extra') is-invalid @enderror"
               value="{{ old('duration_extra', optional($addon)->duration_extra) }}" min="0">
        @error('duration_extra') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="col-md-6">
        <label class="form-label">{{ __('app.service', [], 'Service (optional)') }}</label>
        <select name="service_id" class="form-control @error('service_id') is-invalid @enderror">
            <option value="">-- {{ __('app.allServices', [], 'All Services') }} --</option>
            @foreach ($services as $svc)
                <option value="{{ $svc->id }}" @selected(old('service_id', optional($addon)->service_id) == $svc->id)>
                    {{ $svc->name }}
                </option>
            @endforeach
        </select>
        @error('service_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="col-md-3">
        <label class="form-label d-block">{{ __('app.status') }}</label>
        <div class="form-check form-switch mt-2">
            <input class="form-check-input" type="checkbox" name="is_active" id="is_active_addon" value="1"
                   @checked(old('is_active', $addon ? (bool) $addon->is_active : true))>
            <label class="form-check-label" for="is_active_addon">{{ __('app.active') }}</label>
        </div>
    </div>
</div>
