<div class="row g-3">
    <div class="col-md-8">
        <label class="form-label fw-semibold">Name <span class="text-danger">*</span></label>
        <input type="text" name="name" class="form-control" required value="{{ old('name', $template?->name) }}">
    </div>
    <div class="col-md-2">
        <label class="form-label">Est. Duration (min)</label>
        <input type="number" name="estimated_duration_minutes" class="form-control" min="0"
               value="{{ old('estimated_duration_minutes', $template?->estimated_duration_minutes) }}">
    </div>
    <div class="col-md-2 d-flex align-items-end">
        <div class="form-check mb-2">
            <input type="hidden" name="active" value="0">
            <input type="checkbox" name="active" value="1" class="form-check-input" id="tpl_active"
                   {{ old('active', $template?->active ?? true) ? 'checked' : '' }}>
            <label for="tpl_active" class="form-check-label">Active</label>
        </div>
    </div>
    <div class="col-12">
        <label class="form-label">Description</label>
        <textarea name="description" class="form-control" rows="3">{{ old('description', $template?->description) }}</textarea>
    </div>

    {{-- ── Rich Checklist Step Builder ─────────────────────────────────────── --}}
    <div class="col-12">
        <label class="form-label fw-semibold">Checklist Steps</label>
        <small class="d-block text-muted mb-2">
            Add steps in order. Each step can optionally require a photo and/or a
            customer signature before it is marked complete in Titan Go.
        </small>

        <div id="checklist-steps-container">
            {{--
                Seed existing steps. Each step is an object:
                  { instruction, photo_required, signature_required, is_required }
                For backward-compat, $template->checklist may still be plain strings
                if the data-migration hasn't run yet — we handle that below.
            --}}
            @php
                $existingSteps = [];
                if ($template && !empty($template->checklist)) {
                    foreach ($template->checklist as $step) {
                        if (is_array($step)) {
                            $existingSteps[] = $step;
                        } elseif (is_string($step) && trim($step) !== '') {
                            $existingSteps[] = [
                                'instruction'        => $step,
                                'photo_required'     => false,
                                'signature_required' => false,
                                'is_required'        => true,
                            ];
                        }
                    }
                }
            @endphp

            @foreach($existingSteps as $idx => $step)
            <div class="checklist-step-row border rounded p-3 mb-2 bg-light" data-index="{{ $idx }}">
                <div class="row g-2 align-items-center">
                    <div class="col-md-6">
                        <input type="text"
                               name="steps[{{ $idx }}][instruction]"
                               class="form-control"
                               placeholder="Step instruction…"
                               value="{{ $step['instruction'] }}"
                               required>
                    </div>
                    <div class="col-md-2 d-flex align-items-center gap-2">
                        <input type="checkbox"
                               name="steps[{{ $idx }}][photo_required]"
                               class="form-check-input"
                               id="photo_{{ $idx }}"
                               {{ $step['photo_required'] ? 'checked' : '' }}>
                        <label for="photo_{{ $idx }}" class="form-check-label small">📷 Photo</label>
                    </div>
                    <div class="col-md-2 d-flex align-items-center gap-2">
                        <input type="checkbox"
                               name="steps[{{ $idx }}][signature_required]"
                               class="form-check-input"
                               id="sig_{{ $idx }}"
                               {{ $step['signature_required'] ? 'checked' : '' }}>
                        <label for="sig_{{ $idx }}" class="form-check-label small">✍️ Sig</label>
                    </div>
                    <div class="col-md-1 d-flex align-items-center gap-2">
                        <input type="checkbox"
                               name="steps[{{ $idx }}][is_required]"
                               class="form-check-input"
                               id="req_{{ $idx }}"
                               {{ $step['is_required'] ? 'checked' : '' }}>
                        <label for="req_{{ $idx }}" class="form-check-label small">Req</label>
                    </div>
                    <div class="col-md-1 text-end">
                        <button type="button" class="btn btn-sm btn-outline-danger remove-step-btn">✕</button>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        {{-- Hidden JSON field that gets populated on submit --}}
        <input type="hidden" name="checklist" id="checklist-json-input">

        <button type="button" id="add-step-btn" class="btn btn-sm btn-outline-primary mt-2">
            + Add Step
        </button>
    </div>
</div>

<script>
(function () {
    const container = document.getElementById('checklist-steps-container');
    const addBtn    = document.getElementById('add-step-btn');
    const jsonInput = document.getElementById('checklist-json-input');

    let nextIdx = container.querySelectorAll('.checklist-step-row').length;

    function buildRow(idx) {
        const div = document.createElement('div');
        div.className    = 'checklist-step-row border rounded p-3 mb-2 bg-light';
        div.dataset.index = idx;
        div.innerHTML    = `
            <div class="row g-2 align-items-center">
                <div class="col-md-6">
                    <input type="text" name="steps[${idx}][instruction]" class="form-control"
                           placeholder="Step instruction…" required>
                </div>
                <div class="col-md-2 d-flex align-items-center gap-2">
                    <input type="checkbox" name="steps[${idx}][photo_required]"
                           class="form-check-input" id="photo_${idx}">
                    <label for="photo_${idx}" class="form-check-label small">📷 Photo</label>
                </div>
                <div class="col-md-2 d-flex align-items-center gap-2">
                    <input type="checkbox" name="steps[${idx}][signature_required]"
                           class="form-check-input" id="sig_${idx}">
                    <label for="sig_${idx}" class="form-check-label small">✍️ Sig</label>
                </div>
                <div class="col-md-1 d-flex align-items-center gap-2">
                    <input type="checkbox" name="steps[${idx}][is_required]"
                           class="form-check-input" id="req_${idx}" checked>
                    <label for="req_${idx}" class="form-check-label small">Req</label>
                </div>
                <div class="col-md-1 text-end">
                    <button type="button" class="btn btn-sm btn-outline-danger remove-step-btn">✕</button>
                </div>
            </div>`;
        return div;
    }

    addBtn.addEventListener('click', () => {
        container.appendChild(buildRow(nextIdx++));
    });

    container.addEventListener('click', (e) => {
        if (e.target.classList.contains('remove-step-btn')) {
            e.target.closest('.checklist-step-row').remove();
        }
    });

    // On any form submit, serialise steps to JSON and put in hidden field.
    // This lets the controller receive a single well-typed JSON string.
    const form = addBtn.closest('form') ?? document.querySelector('form');
    if (form) {
        form.addEventListener('submit', () => {
            const steps = [];
            container.querySelectorAll('.checklist-step-row').forEach(row => {
                const instruction = row.querySelector('[name$="[instruction]"]')?.value?.trim();
                if (!instruction) return;
                steps.push({
                    instruction,
                    photo_required:     row.querySelector('[name$="[photo_required]"]')?.checked  ?? false,
                    signature_required: row.querySelector('[name$="[signature_required]"]')?.checked ?? false,
                    is_required:        row.querySelector('[name$="[is_required]"]')?.checked       ?? true,
                });
            });
            jsonInput.value = JSON.stringify(steps);
        });
    }
})();
</script>
