<div class="row g-3">
    <div class="col-md-6">
        <label class="form-label fw-semibold">Reference <span class="text-danger">*</span></label>
        <input type="text" name="name" class="form-control" required value="{{ old('name', $repair?->name) }}">
    </div>
    <div class="col-md-3">
        <label class="form-label">Priority</label>
        <select name="priority" class="form-select" required>
            @foreach(\Modules\FSMEquipment\Models\RepairOrder::PRIORITIES as $key => $label)
                <option value="{{ $key }}" {{ old('priority', $repair?->priority ?? 'normal') === $key ? 'selected' : '' }}>{{ $label }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-3">
        <label class="form-label">Stage</label>
        <select name="stage" class="form-select" required>
            @foreach(\Modules\FSMEquipment\Models\RepairOrder::STAGES as $key => $label)
                <option value="{{ $key }}" {{ old('stage', $repair?->stage ?? 'new') === $key ? 'selected' : '' }}>{{ $label }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-4">
        <label class="form-label">Equipment</label>
        <select name="equipment_id" class="form-select">
            <option value="">— None —</option>
            @foreach($equipmentList as $eq)
                <option value="{{ $eq->id }}" {{ old('equipment_id', $repair?->equipment_id ?? $selectedEquipment?->id) == $eq->id ? 'selected' : '' }}>{{ $eq->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-4">
        <label class="form-label">Location</label>
        <select name="fsm_location_id" class="form-select">
            <option value="">— None —</option>
            @foreach($locations as $loc)
                <option value="{{ $loc->id }}" {{ old('fsm_location_id', $repair?->fsm_location_id) == $loc->id ? 'selected' : '' }}>{{ $loc->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-4">
        <label class="form-label">Template</label>
        <select name="template_id" class="form-select" id="repair_template_select">
            <option value="">— None —</option>
            @foreach($templates as $tpl)
                <option value="{{ $tpl->id }}" {{ old('template_id', $repair?->template_id) == $tpl->id ? 'selected' : '' }}>{{ $tpl->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-4">
        <label class="form-label">Reported By</label>
        <select name="reported_by" class="form-select">
            <option value="">— None —</option>
            @foreach($users as $u)
                <option value="{{ $u->id }}" {{ old('reported_by', $repair?->reported_by) == $u->id ? 'selected' : '' }}>{{ $u->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-4">
        <label class="form-label">Assigned To</label>
        <select name="assigned_to" class="form-select">
            <option value="">— None —</option>
            @foreach($users as $u)
                <option value="{{ $u->id }}" {{ old('assigned_to', $repair?->assigned_to) == $u->id ? 'selected' : '' }}>{{ $u->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-4">
        <label class="form-label">FSM Order</label>
        <input type="number" name="fsm_order_id" class="form-control" value="{{ old('fsm_order_id', $repair?->fsm_order_id ?? $selectedOrder?->id) }}">
    </div>
    <div class="col-md-4">
        <label class="form-label">Date Reported</label>
        <input type="datetime-local" name="date_reported" class="form-control"
               value="{{ old('date_reported', $repair?->date_reported?->format('Y-m-d\TH:i') ?? now()->format('Y-m-d\TH:i')) }}">
    </div>
    <div class="col-md-4">
        <label class="form-label">Date Scheduled</label>
        <input type="datetime-local" name="date_scheduled" class="form-control"
               value="{{ old('date_scheduled', $repair?->date_scheduled?->format('Y-m-d\TH:i')) }}">
    </div>
    <div class="col-md-4">
        <label class="form-label">Date Completed</label>
        <input type="datetime-local" name="date_completed" class="form-control"
               value="{{ old('date_completed', $repair?->date_completed?->format('Y-m-d\TH:i')) }}">
    </div>
    <div class="col-md-3">
        <label class="form-label">Repair Cost ($)</label>
        <input type="number" name="cost" class="form-control" step="0.01" min="0"
               value="{{ old('cost', $repair?->cost) }}">
    </div>
    <div class="col-12">
        <label class="form-label">Fault Description</label>
        <textarea name="description" class="form-control" rows="3">{{ old('description', $repair?->description) }}</textarea>
    </div>
    <div class="col-12">
        <label class="form-label">Parts Used</label>
        <textarea name="parts_used" class="form-control" rows="2">{{ old('parts_used', $repair?->parts_used) }}</textarea>
    </div>
    <div class="col-12">
        <label class="form-label">Root Cause Analysis</label>
        <textarea name="root_cause" class="form-control" rows="2">{{ old('root_cause', $repair?->root_cause) }}</textarea>
    </div>
</div>
