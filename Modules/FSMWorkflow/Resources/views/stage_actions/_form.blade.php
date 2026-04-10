{{-- Shared form for create/edit stage actions --}}
@php $isEdit = !is_null($action ?? null); @endphp

<div class="mb-3">
    <label class="form-label">Label <span class="text-muted">(optional, for your reference)</span></label>
    <input type="text" name="name" class="form-control" value="{{ old('name', $action->name ?? '') }}" maxlength="255">
</div>

<div class="mb-3">
    <label class="form-label fw-semibold">Action Type <span class="text-danger">*</span></label>
    <select name="action_type" id="actionTypeSelect" class="form-select" required>
        <option value="">— Select —</option>
        @foreach(\Modules\FSMWorkflow\Models\FSMStageAction::ACTION_TYPES as $value => $label)
            <option value="{{ $value }}" {{ old('action_type', $action->action_type ?? '') === $value ? 'selected' : '' }}>
                {{ $label }}
            </option>
        @endforeach
    </select>
</div>

{{-- create_activity fields --}}
<div class="mb-3 action-field d-none" id="field-activity_type_id">
    <label class="form-label">Activity Type</label>
    <select name="activity_type_id" class="form-select">
        <option value="">— None —</option>
        @foreach($activityTypes as $type)
            <option value="{{ $type->id }}" {{ (int)old('activity_type_id', $action->activity_type_id ?? 0) === $type->id ? 'selected' : '' }}>
                {{ $type->name }}
            </option>
        @endforeach
    </select>
</div>

{{-- webhook field --}}
<div class="mb-3 action-field d-none" id="field-webhook_url">
    <label class="form-label">Webhook URL</label>
    <input type="url" name="webhook_url" class="form-control" value="{{ old('webhook_url', $action->webhook_url ?? '') }}" maxlength="2048" placeholder="https://...">
</div>

{{-- message / payload field (shown for send_sms, send_email, webhook, custom) --}}
<div class="mb-3 action-field d-none" id="field-custom_payload">
    <label class="form-label">Message / Payload</label>
    <textarea name="custom_payload" class="form-control" rows="4" placeholder="Use {order_ref}, {stage}, {worker}, {location} tokens">{{ old('custom_payload', $action->custom_payload ?? '') }}</textarea>
    <div class="form-text">Available tokens: <code>{order_ref}</code>, <code>{stage}</code>, <code>{worker}</code>, <code>{location}</code></div>
</div>

<div class="mb-3">
    <label class="form-label">Sequence <span class="text-muted">(lower = fires first)</span></label>
    <input type="number" name="sequence" class="form-control" value="{{ old('sequence', $action->sequence ?? 0) }}" min="0" style="width:120px;">
</div>

<div class="mb-3 form-check">
    <input type="checkbox" name="active" id="active" class="form-check-input" value="1"
           {{ old('active', ($action->active ?? true) ? '1' : '0') === '1' ? 'checked' : '' }}>
    <label class="form-check-label" for="active">Active</label>
</div>

<script>
(function () {
    const select = document.getElementById('actionTypeSelect');

    const fieldMap = {
        'send_sms':        ['custom_payload'],
        'send_email':      ['custom_payload'],
        'create_activity': ['activity_type_id'],
        'create_invoice':  [],
        'webhook':         ['webhook_url', 'custom_payload'],
        'custom':          ['custom_payload'],
    };

    function updateFields() {
        document.querySelectorAll('.action-field').forEach(el => el.classList.add('d-none'));
        const fields = fieldMap[select.value] || [];
        fields.forEach(f => {
            const el = document.getElementById('field-' + f);
            if (el) el.classList.remove('d-none');
        });
    }

    select.addEventListener('change', updateFields);
    updateFields();
})();
</script>
