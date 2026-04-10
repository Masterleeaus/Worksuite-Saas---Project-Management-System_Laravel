<div class="row g-3">
    <div class="col-md-6">
        <label class="form-label">Location</label>
        <select name="location_id" class="form-select">
            <option value="">— None —</option>
            @foreach($locations as $loc)
                <option value="{{ $loc->id }}" {{ old('location_id', $order?->location_id) == $loc->id ? 'selected' : '' }}>{{ $loc->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-6">
        <label class="form-label">Team</label>
        <select name="team_id" class="form-select">
            <option value="">— None —</option>
            @foreach($teams as $team)
                <option value="{{ $team->id }}" {{ old('team_id', $order?->team_id) == $team->id ? 'selected' : '' }}>{{ $team->name }}</option>
            @endforeach
        </select>
    </div>
    @if(!empty($vehicles) && $vehicles->count())
    <div class="col-md-6">
        <label class="form-label">Worker / Cleaner</label>
        <select name="person_id" id="order_person_id" class="form-select">
            <option value="">— None —</option>
            @foreach(\App\Models\User::orderBy('name')->get() as $user)
                <option value="{{ $user->id }}" {{ old('person_id', $order?->person_id) == $user->id ? 'selected' : '' }}>
                    {{ $user->name }}
                </option>
            @endforeach
        </select>
    </div>
    <div class="col-md-6">
        <label class="form-label">Vehicle / Van</label>
        <select name="vehicle_id" id="order_vehicle_id" class="form-select">
            <option value="">— None —</option>
            @foreach($vehicles as $vehicle)
                <option value="{{ $vehicle->id }}"
                        data-driver="{{ $vehicle->person_id }}"
                        {{ old('vehicle_id', $order?->vehicle_id) == $vehicle->id ? 'selected' : '' }}>
                    {{ $vehicle->name }}{{ $vehicle->license_plate ? ' ('.$vehicle->license_plate.')' : '' }}
                </option>
            @endforeach
        </select>
    </div>
    <script>
    (function () {
        var personSel  = document.getElementById('order_person_id');
        var vehicleSel = document.getElementById('order_vehicle_id');
        if (!personSel || !vehicleSel) return;
        personSel.addEventListener('change', function () {
            var personId = this.value;
            if (!personId) return;
            // Only auto-fill when vehicle is currently empty
            if (vehicleSel.value) return;
            var opts = vehicleSel.querySelectorAll('option[data-driver]');
            for (var i = 0; i < opts.length; i++) {
                if (opts[i].getAttribute('data-driver') === personId) {
                    vehicleSel.value = opts[i].value;
                    break;
                }
            }
        });
    })();
    </script>
    @else
    <div class="col-md-6">
        <label class="form-label">Worker / Cleaner</label>
        <select name="person_id" class="form-select">
            <option value="">— None —</option>
            @foreach(\App\Models\User::orderBy('name')->get() as $user)
                <option value="{{ $user->id }}" {{ old('person_id', $order?->person_id) == $user->id ? 'selected' : '' }}>
                    {{ $user->name }}
                </option>
            @endforeach
        </select>
    </div>
    @endif
    <div class="col-md-6">
        <label class="form-label">Stage</label>
        <select name="stage_id" class="form-select">
            <option value="">— None —</option>
            @foreach($stages as $stage)
                <option value="{{ $stage->id }}" {{ old('stage_id', $order?->stage_id) == $stage->id ? 'selected' : '' }}>{{ $stage->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-6">
        <label class="form-label">Template</label>
        <select name="template_id" class="form-select">
            <option value="">— None —</option>
            @foreach($templates as $tpl)
                <option value="{{ $tpl->id }}" {{ old('template_id', $order?->template_id) == $tpl->id ? 'selected' : '' }}>{{ $tpl->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-6">
        <label class="form-label">Priority</label>
        <select name="priority" class="form-select">
            <option value="0" {{ old('priority', $order?->priority ?? '0') === '0' ? 'selected' : '' }}>Normal</option>
            <option value="1" {{ old('priority', $order?->priority) === '1' ? 'selected' : '' }}>Urgent</option>
        </select>
    </div>
    <div class="col-md-3">
        <label class="form-label">Scheduled Start</label>
        <input type="datetime-local" name="scheduled_date_start" class="form-control"
               value="{{ old('scheduled_date_start', $order?->scheduled_date_start?->format('Y-m-d\TH:i')) }}">
    </div>
    <div class="col-md-3">
        <label class="form-label">Scheduled End</label>
        <input type="datetime-local" name="scheduled_date_end" class="form-control"
               value="{{ old('scheduled_date_end', $order?->scheduled_date_end?->format('Y-m-d\TH:i')) }}">
    </div>
    @if($order)
    <div class="col-md-3">
        <label class="form-label">Actual Start (Check-In)</label>
        <input type="datetime-local" name="date_start" class="form-control"
               value="{{ old('date_start', $order->date_start?->format('Y-m-d\TH:i')) }}">
    </div>
    <div class="col-md-3">
        <label class="form-label">Actual End (Check-Out)</label>
        <input type="datetime-local" name="date_end" class="form-control"
               value="{{ old('date_end', $order->date_end?->format('Y-m-d\TH:i')) }}">
    </div>
    @endif
    <div class="col-12">
        <label class="form-label">Description / Notes</label>
        <textarea name="description" class="form-control" rows="4">{{ old('description', $order?->description) }}</textarea>
    </div>
    <div class="col-md-6">
        <label class="form-label">Tags</label>
        <select name="tag_ids[]" class="form-select" multiple>
            @foreach($tags as $tag)
                <option value="{{ $tag->id }}" {{ in_array($tag->id, old('tag_ids', $order?->tags?->pluck('id')->toArray() ?? [])) ? 'selected' : '' }}>{{ $tag->name }}</option>
            @endforeach
        </select>
        <small class="text-muted">Hold Ctrl/Cmd to select multiple.</small>
    </div>
    <div class="col-md-6">
        <label class="form-label">Equipment Required</label>
        <select name="equipment_ids[]" class="form-select" multiple>
            @foreach($equipment as $eq)
                <option value="{{ $eq->id }}" {{ in_array($eq->id, old('equipment_ids', $order?->equipment?->pluck('id')->toArray() ?? [])) ? 'selected' : '' }}>{{ $eq->name }}</option>
            @endforeach
        </select>
        <small class="text-muted">Hold Ctrl/Cmd to select multiple.</small>
    </div>
</div>
