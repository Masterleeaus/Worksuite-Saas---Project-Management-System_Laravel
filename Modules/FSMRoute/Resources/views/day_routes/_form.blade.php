@php
    $action          = $dayRoute ? route('fsmroute.day_routes.update', $dayRoute->id) : route('fsmroute.day_routes.store');
    $selectedOrders  = old('order_ids', $dayRoute ? $dayRoute->orders->pluck('id')->toArray() : []);
@endphp

<form method="POST" action="{{ $action }}">
    @csrf
    <div class="row g-3">

        <div class="col-md-6">
            <label class="form-label">Route</label>
            <select name="route_id" class="form-select">
                <option value="">— None —</option>
                @foreach($routes as $route)
                    <option value="{{ $route->id }}" {{ old('route_id', $dayRoute?->route_id) == $route->id ? 'selected' : '' }}>
                        {{ $route->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="col-md-3">
            <label class="form-label">Date <span class="text-danger">*</span></label>
            <input type="date" name="date" class="form-control @error('date') is-invalid @enderror"
                   value="{{ old('date', $dayRoute?->date?->format('Y-m-d') ?? date('Y-m-d')) }}" required>
            @error('date')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="col-md-3">
            <label class="form-label">State</label>
            <select name="state" class="form-select">
                @foreach(['draft' => 'Draft', 'confirmed' => 'Confirmed', 'done' => 'Done'] as $val => $label)
                    <option value="{{ $val }}" {{ old('state', $dayRoute?->state ?? 'draft') === $val ? 'selected' : '' }}>
                        {{ $label }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="col-md-6">
            <label class="form-label">Assigned Worker</label>
            <select name="person_id" class="form-select">
                <option value="">— None —</option>
                @foreach($users as $user)
                    <option value="{{ $user->id }}" {{ old('person_id', $dayRoute?->person_id) == $user->id ? 'selected' : '' }}>
                        {{ $user->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="col-md-6">
            <label class="form-label">Planned Start</label>
            <input type="datetime-local" name="date_start_planned" class="form-control"
                   value="{{ old('date_start_planned', $dayRoute?->date_start_planned?->format('Y-m-d\TH:i')) }}">
        </div>

        <div class="col-md-3">
            <label class="form-label">Work Time (h)</label>
            <input type="number" step="0.5" min="0" name="work_time" class="form-control"
                   value="{{ old('work_time', $dayRoute?->work_time ?? 8.0) }}">
        </div>

        <div class="col-md-3">
            <label class="form-label">Max Allow Time (h)</label>
            <input type="number" step="0.5" min="0" name="max_allow_time" class="form-control"
                   value="{{ old('max_allow_time', $dayRoute?->max_allow_time ?? 10.0) }}">
        </div>

        <div class="col-12">
            <label class="form-label">Assigned Orders</label>
            <select name="order_ids[]" class="form-select" multiple size="8">
                @foreach($orders as $order)
                    <option value="{{ $order->id }}" {{ in_array($order->id, $selectedOrders) ? 'selected' : '' }}>
                        {{ $order->name }}{{ $order->location ? ' – ' . $order->location->name : '' }}
                    </option>
                @endforeach
            </select>
            <small class="text-muted">Hold Ctrl/Cmd to select multiple.</small>
        </div>

        <div class="col-12">
            <button type="submit" class="btn btn-primary">
                {{ $dayRoute ? 'Update Day Route' : 'Create Day Route' }}
            </button>
            <a href="{{ route('fsmroute.day_routes.index') }}" class="btn btn-secondary ms-2">Cancel</a>
        </div>

    </div>
</form>
