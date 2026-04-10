@php
    $action      = $route ? route('fsmroute.routes.update', $route->id) : route('fsmroute.routes.store');
    $selectedDays = old('day_ids', $route ? $route->days->pluck('id')->toArray() : []);
    $selectedLocs = old('location_ids', $route ? $route->locations->pluck('id')->toArray() : []);
@endphp

<form method="POST" action="{{ $action }}">
    @csrf
    <div class="row g-3">

        <div class="col-md-6">
            <label class="form-label">Name <span class="text-danger">*</span></label>
            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                   value="{{ old('name', $route?->name) }}" required maxlength="256">
            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="col-md-6">
            <label class="form-label">Assigned Worker</label>
            <select name="person_id" class="form-select">
                <option value="">— None —</option>
                @foreach($users as $user)
                    <option value="{{ $user->id }}" {{ old('person_id', $route?->person_id) == $user->id ? 'selected' : '' }}>
                        {{ $user->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="col-md-3">
            <label class="form-label">Max Orders</label>
            <input type="number" name="max_order" class="form-control" min="0"
                   value="{{ old('max_order', $route?->max_order ?? 0) }}">
        </div>

        <div class="col-md-3 d-flex align-items-end">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="active" value="1" id="active"
                    {{ old('active', $route ? ($route->active ? '1' : '') : '1') == '1' ? 'checked' : '' }}>
                <label class="form-check-label" for="active">Active</label>
            </div>
        </div>

        <div class="col-12">
            <label class="form-label">Operating Days</label>
            <div class="d-flex flex-wrap gap-3">
                @foreach($days as $day)
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox"
                               name="day_ids[]" value="{{ $day->id }}"
                               id="day_{{ $day->id }}"
                               {{ in_array($day->id, $selectedDays) ? 'checked' : '' }}>
                        <label class="form-check-label" for="day_{{ $day->id }}">{{ $day->name }}</label>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="col-12">
            <label class="form-label">Locations</label>
            <select name="location_ids[]" class="form-select" multiple size="6">
                @foreach($locations as $loc)
                    <option value="{{ $loc->id }}" {{ in_array($loc->id, $selectedLocs) ? 'selected' : '' }}>
                        {{ $loc->name }}{{ $loc->city ? ' – ' . $loc->city : '' }}
                    </option>
                @endforeach
            </select>
            <small class="text-muted">Hold Ctrl/Cmd to select multiple.</small>
        </div>

        <div class="col-12">
            <button type="submit" class="btn btn-primary">
                {{ $route ? 'Update Route' : 'Create Route' }}
            </button>
            <a href="{{ route('fsmroute.routes.index') }}" class="btn btn-secondary ms-2">Cancel</a>
        </div>

    </div>
</form>
