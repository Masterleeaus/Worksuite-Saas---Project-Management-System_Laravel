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
        <label class="form-label">{{ __('app.service', [], 'Service') }} <span class="text-danger">*</span></label>
        <select name="service_id" class="form-control @error('service_id') is-invalid @enderror" required>
            <option value="">-- {{ __('app.selectService', [], 'Select Service') }} --</option>
            @foreach ($services as $svc)
                <option value="{{ $svc->id }}" @selected(old('service_id', optional($rule ?? null)->service_id) == $svc->id)>
                    {{ $svc->name }}
                </option>
            @endforeach
        </select>
        @error('service_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    @if ($zones->isNotEmpty())
        <div class="col-md-6">
            <label class="form-label">{{ __('app.zone', [], 'Zone (optional)') }}</label>
            <select name="zone_id" class="form-control @error('zone_id') is-invalid @enderror">
                <option value="">-- {{ __('app.allZones', [], 'All Zones') }} --</option>
                @foreach ($zones as $zone)
                    <option value="{{ $zone->id }}" @selected(old('zone_id', optional($rule ?? null)->zone_id) == $zone->id)>
                        {{ $zone->name }}
                    </option>
                @endforeach
            </select>
            @error('zone_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
    @endif

    <div class="col-md-6">
        <label class="form-label">{{ __('app.label', [], 'Label') }}</label>
        <input type="text" name="label" class="form-control @error('label') is-invalid @enderror"
               value="{{ old('label', optional($rule ?? null)->label) }}" maxlength="191"
               placeholder="{{ __('app.labelPlaceholder', [], 'e.g. Standard Zone A Pricing') }}">
        @error('label') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="col-md-3">
        <label class="form-label">{{ __('app.basePrice', [], 'Base Price Override') }}</label>
        <input type="number" name="base_price_override" class="form-control @error('base_price_override') is-invalid @enderror"
               value="{{ old('base_price_override', optional($rule ?? null)->base_price_override) }}" min="0" step="0.01">
        @error('base_price_override') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="col-md-3">
        <label class="form-label">{{ __('app.perBedroomPrice', [], 'Per Bedroom Price') }}</label>
        <input type="number" name="per_bedroom_price" class="form-control @error('per_bedroom_price') is-invalid @enderror"
               value="{{ old('per_bedroom_price', optional($rule ?? null)->per_bedroom_price) }}" min="0" step="0.01">
        @error('per_bedroom_price') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="col-md-3">
        <label class="form-label">{{ __('app.perBathroomPrice', [], 'Per Bathroom Price') }}</label>
        <input type="number" name="per_bathroom_price" class="form-control @error('per_bathroom_price') is-invalid @enderror"
               value="{{ old('per_bathroom_price', optional($rule ?? null)->per_bathroom_price) }}" min="0" step="0.01">
        @error('per_bathroom_price') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="col-md-3">
        <label class="form-label">{{ __('app.minPrice', [], 'Minimum Price') }}</label>
        <input type="number" name="min_price" class="form-control @error('min_price') is-invalid @enderror"
               value="{{ old('min_price', optional($rule ?? null)->min_price) }}" min="0" step="0.01">
        @error('min_price') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="col-md-3">
        <label class="form-label d-block">{{ __('app.status') }}</label>
        <div class="form-check form-switch mt-2">
            <input class="form-check-input" type="checkbox" name="is_active" id="is_active_pricing" value="1"
                   @checked(old('is_active', isset($rule) ? (bool) $rule->is_active : true))>
            <label class="form-check-label" for="is_active_pricing">{{ __('app.active') }}</label>
        </div>
    </div>
</div>
