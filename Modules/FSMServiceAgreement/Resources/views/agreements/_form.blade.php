{{-- Shared form partial for create / edit --}}
<div class="row g-3">
    <div class="col-md-6">
        <label class="form-label">Client (Partner ID)</label>
        <input type="number" name="partner_id" class="form-control"
               value="{{ old('partner_id', $agreement?->partner_id) }}"
               placeholder="User ID of the client">
        <small class="text-muted">Enter the user/client ID from the system.</small>
    </div>

    <div class="col-md-3">
        <label class="form-label">Start Date <span class="text-danger">*</span></label>
        <input type="date" name="start_date" class="form-control" required
               value="{{ old('start_date', $agreement?->start_date?->format('Y-m-d')) }}">
    </div>

    <div class="col-md-3">
        <label class="form-label">End Date</label>
        <input type="date" name="end_date" class="form-control"
               value="{{ old('end_date', $agreement?->end_date?->format('Y-m-d')) }}">
        <small class="text-muted">Leave blank for ongoing agreements.</small>
    </div>

    <div class="col-md-4">
        <label class="form-label">Contract Value ($)</label>
        <input type="number" name="value" class="form-control" step="0.01" min="0"
               value="{{ old('value', $agreement?->value ?? '0.00') }}">
    </div>

    <div class="col-md-4">
        <label class="form-label">Sites / Locations</label>
        <select name="location_ids[]" class="form-select" multiple>
            @foreach($locations as $loc)
                <option value="{{ $loc->id }}"
                    {{ in_array($loc->id, old('location_ids', $agreement?->locations?->pluck('id')->toArray() ?? [])) ? 'selected' : '' }}>
                    {{ $loc->name }}
                </option>
            @endforeach
        </select>
        <small class="text-muted">Hold Ctrl/Cmd to select multiple.</small>
    </div>

    <div class="col-md-4">
        <label class="form-label">Job Templates</label>
        <select name="template_ids[]" class="form-select" multiple>
            @foreach($templates as $tpl)
                <option value="{{ $tpl->id }}"
                    {{ in_array($tpl->id, old('template_ids', $agreement?->templates?->pluck('id')->toArray() ?? [])) ? 'selected' : '' }}>
                    {{ $tpl->name }}
                </option>
            @endforeach
        </select>
        <small class="text-muted">Hold Ctrl/Cmd to select multiple.</small>
    </div>

    <div class="col-12">
        <label class="form-label">Recurrence Rule (JSON)</label>
        <textarea name="recurrence_rule" class="form-control font-monospace" rows="3"
                  placeholder='{"frequency":"fortnightly","day_of_week":"Monday"}'>{{ old('recurrence_rule', $agreement ? json_encode($agreement->recurrence_rule, JSON_PRETTY_PRINT) : '') }}</textarea>
        <small class="text-muted">Optional JSON recurrence pattern for the recurring job engine.</small>
    </div>

    <div class="col-12">
        <label class="form-label">Contract Notes / Terms</label>
        <textarea name="notes" class="form-control" rows="4">{{ old('notes', $agreement?->notes) }}</textarea>
    </div>
</div>

{{-- Agreement Line Items --}}
<hr class="my-4">
<h5>Line Items <small class="text-muted fw-normal">(per-site / per-service pricing)</small></h5>

<div id="agreement-lines">
    @php
        $lines = old('line_service_description')
            ? array_map(null,
                old('line_service_description', []),
                old('line_location_id', []),
                old('line_frequency', []),
                old('line_unit_price', [])
              )
            : ($agreement?->lines?->map(fn($l) => [
                $l->service_description,
                $l->location_id,
                $l->frequency,
                $l->unit_price,
              ])->toArray() ?? []);
    @endphp

    @foreach($lines as $i => $line)
    <div class="row g-2 mb-2 agreement-line-row">
        <div class="col-md-4">
            <input type="text" name="line_service_description[]" class="form-control"
                   placeholder="Service description" value="{{ $line[0] ?? '' }}" required>
        </div>
        <div class="col-md-3">
            <select name="line_location_id[]" class="form-select">
                <option value="">— All sites —</option>
                @foreach($locations as $loc)
                    <option value="{{ $loc->id }}" {{ ($line[1] ?? null) == $loc->id ? 'selected' : '' }}>{{ $loc->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <input type="text" name="line_frequency[]" class="form-control"
                   placeholder="Frequency" value="{{ $line[2] ?? '' }}">
        </div>
        <div class="col-md-2">
            <div class="input-group">
                <span class="input-group-text">$</span>
                <input type="number" name="line_unit_price[]" class="form-control"
                       placeholder="0.00" step="0.01" min="0" value="{{ $line[3] ?? '0.00' }}">
            </div>
        </div>
        <div class="col-md-1 d-flex align-items-center">
            <button type="button" class="btn btn-sm btn-outline-danger remove-line-btn">✕</button>
        </div>
    </div>
    @endforeach
</div>

<button type="button" class="btn btn-outline-secondary btn-sm mt-2" id="add-line-btn">+ Add Line Item</button>

<script>
(function () {
    const container = document.getElementById('agreement-lines');
    const locations = @json($locations->map(fn($l) => ['id' => $l->id, 'name' => $l->name]));

    function buildRow() {
        const row = document.createElement('div');
        row.className = 'row g-2 mb-2 agreement-line-row';

        let locOptions = '<option value="">— All sites —</option>';
        locations.forEach(l => { locOptions += `<option value="${l.id}">${l.name}</option>`; });

        row.innerHTML = `
            <div class="col-md-4">
                <input type="text" name="line_service_description[]" class="form-control" placeholder="Service description" required>
            </div>
            <div class="col-md-3">
                <select name="line_location_id[]" class="form-select">${locOptions}</select>
            </div>
            <div class="col-md-2">
                <input type="text" name="line_frequency[]" class="form-control" placeholder="Frequency">
            </div>
            <div class="col-md-2">
                <div class="input-group">
                    <span class="input-group-text">$</span>
                    <input type="number" name="line_unit_price[]" class="form-control" placeholder="0.00" step="0.01" min="0" value="0.00">
                </div>
            </div>
            <div class="col-md-1 d-flex align-items-center">
                <button type="button" class="btn btn-sm btn-outline-danger remove-line-btn">✕</button>
            </div>`;

        return row;
    }

    document.getElementById('add-line-btn').addEventListener('click', function () {
        container.appendChild(buildRow());
    });

    container.addEventListener('click', function (e) {
        if (e.target.classList.contains('remove-line-btn')) {
            e.target.closest('.agreement-line-row').remove();
        }
    });
})();
</script>
