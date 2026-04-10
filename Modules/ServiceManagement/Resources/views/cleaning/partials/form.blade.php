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
    {{-- Name --}}
    <div class="col-md-6">
        <label class="form-label">{{ __('app.name') }} <span class="text-danger">*</span></label>
        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
               value="{{ old('name', optional($service)->name) }}" required maxlength="191">
        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    {{-- Category --}}
    <div class="col-md-6">
        <label class="form-label">{{ __('app.category') }}</label>
        <select name="category_id" class="form-control @error('category_id') is-invalid @enderror">
            <option value="">-- {{ __('app.selectCategory') }} --</option>
            @foreach ($categories as $cat)
                <option value="{{ $cat->id }}" @selected(old('category_id', optional($service)->category_id) == $cat->id)>
                    {{ $cat->name }}
                </option>
            @endforeach
        </select>
        @error('category_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    {{-- Short Description --}}
    <div class="col-md-12">
        <label class="form-label">{{ __('app.shortDescription', [], 'Short Description') }}</label>
        <input type="text" name="short_description" class="form-control @error('short_description') is-invalid @enderror"
               value="{{ old('short_description', optional($service)->short_description) }}" maxlength="500">
        @error('short_description') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    {{-- Description --}}
    <div class="col-md-12">
        <label class="form-label">{{ __('app.description') }}</label>
        <textarea name="description" class="form-control @error('description') is-invalid @enderror" rows="4">{{ old('description', optional($service)->description) }}</textarea>
        @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    {{-- Duration --}}
    <div class="col-md-4">
        <label class="form-label">{{ __('app.durationMinutes', [], 'Duration (minutes)') }}</label>
        <input type="number" name="duration_minutes" class="form-control @error('duration_minutes') is-invalid @enderror"
               value="{{ old('duration_minutes', optional($service)->duration_minutes) }}" min="0">
        @error('duration_minutes') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    {{-- Base Price --}}
    <div class="col-md-4">
        <label class="form-label">{{ __('app.basePrice', [], 'Base Price') }}</label>
        <input type="number" name="base_price" class="form-control @error('base_price') is-invalid @enderror"
               value="{{ old('base_price', optional($service)->base_price) }}" min="0" step="0.01">
        @error('base_price') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    {{-- Frequency --}}
    <div class="col-md-4">
        <label class="form-label">{{ __('app.frequency', [], 'Frequency') }}</label>
        <select name="frequency" class="form-control @error('frequency') is-invalid @enderror">
            <option value="">-- {{ __('app.selectFrequency', [], 'Select Frequency') }} --</option>
            @foreach ($frequencyOptions as $key => $label)
                <option value="{{ $key }}" @selected(old('frequency', optional($service)->frequency) === $key)>{{ $label }}</option>
            @endforeach
        </select>
        @error('frequency') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    {{-- Zone --}}
    @if ($zones->isNotEmpty())
        <div class="col-md-4">
            <label class="form-label">{{ __('app.zone', [], 'Zone') }}</label>
            <select name="zone_id" class="form-control @error('zone_id') is-invalid @enderror">
                <option value="">-- {{ __('app.allZones', [], 'All Zones') }} --</option>
                @foreach ($zones as $zone)
                    <option value="{{ $zone->id }}" @selected(old('zone_id', optional($service)->zone_id) == $zone->id)>
                        {{ $zone->name }}
                    </option>
                @endforeach
            </select>
            @error('zone_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
    @endif

    {{-- Thumbnail --}}
    <div class="col-md-4">
        <label class="form-label">{{ __('app.thumbnail', [], 'Thumbnail / Icon') }}</label>
        <input type="file" name="thumbnail" class="form-control @error('thumbnail') is-invalid @enderror" accept="image/*">
        @error('thumbnail') <div class="invalid-feedback">{{ $message }}</div> @enderror
        @if ($service && $service->thumbnail)
            <div class="mt-1">
                <img src="{{ $service->thumbnail_full_path }}" alt="thumbnail" height="50">
            </div>
        @endif
    </div>

    {{-- Eco Friendly --}}
    <div class="col-md-2">
        <label class="form-label d-block">{{ __('app.ecoFriendly', [], 'Eco-Friendly') }}</label>
        <div class="form-check form-switch mt-2">
            <input class="form-check-input" type="checkbox" name="eco_friendly" id="eco_friendly" value="1"
                   @checked(old('eco_friendly', optional($service)->eco_friendly))>
            <label class="form-check-label" for="eco_friendly">{{ __('app.ecoGreenClean', [], 'Green Clean') }}</label>
        </div>
    </div>

    {{-- Active --}}
    <div class="col-md-2">
        <label class="form-label d-block">{{ __('app.status') }}</label>
        <div class="form-check form-switch mt-2">
            <input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1"
                   @checked(old('is_active', $service ? (bool) $service->is_active : true))>
            <label class="form-check-label" for="is_active">{{ __('app.active') }}</label>
        </div>
    </div>
</div>
