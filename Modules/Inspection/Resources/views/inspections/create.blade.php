@extends('layouts.app')

@section('content')
    <div class="content-wrapper">
        <div class="container-fluid">

            <h1 class="h3 mb-3">New Inspection</h1>

            <div class="card">
                <div class="card-body">
                    <form action="{{ route('inspections.store') }}" method="POST" id="inspection-form">
                        @csrf

                        @if($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Inspector <span class="text-danger">*</span></label>
                                    <select name="inspector_id" class="form-control select2" required>
                                        <option value="">— Select inspector —</option>
                                        @foreach($inspectors as $user)
                                            <option value="{{ $user->id }}"
                                                {{ old('inspector_id') == $user->id ? 'selected' : '' }}>
                                                {{ $user->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Status <span class="text-danger">*</span></label>
                                    <select name="status" class="form-control" required>
                                        @foreach($statuses as $status)
                                            <option value="{{ $status }}"
                                                {{ old('status', 'pending') === $status ? 'selected' : '' }}>
                                                {{ \Modules\Inspection\Support\Enums\InspectionStatus::label($status) }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Score (0–10)</label>
                                    <input type="number" name="score" class="form-control"
                                           min="0" max="10" step="0.1"
                                           value="{{ old('score') }}" placeholder="e.g. 8.5">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Inspected At</label>
                                    <input type="datetime-local" name="inspected_at" class="form-control"
                                           value="{{ old('inspected_at') }}">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Checklist Template</label>
                                    <select name="template_id" class="form-control select2" id="template-select">
                                        <option value="">— None —</option>
                                        @foreach($templates as $template)
                                            <option value="{{ $template->id }}"
                                                {{ old('template_id') == $template->id ? 'selected' : '' }}>
                                                {{ $template->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Booking ID</label>
                                    <input type="number" name="booking_id" class="form-control"
                                           value="{{ old('booking_id') }}" placeholder="Optional booking reference">
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Notes</label>
                                    <textarea name="notes" class="form-control" rows="3">{{ old('notes') }}</textarea>
                                </div>
                            </div>
                        </div>

                        {{-- Checklist Items --}}
                        <h5 class="mt-3 mb-2">Checklist Items</h5>
                        <div id="checklist-items">
                            @if(old('items'))
                                @foreach(old('items') as $i => $item)
                                    <div class="checklist-row row mb-2 align-items-center">
                                        <div class="col-md-4">
                                            <input type="text" name="items[{{ $i }}][area]"
                                                   class="form-control" placeholder="Area (e.g. Kitchen)"
                                                   value="{{ $item['area'] ?? '' }}" required>
                                        </div>
                                        <div class="col-md-3">
                                            <select name="items[{{ $i }}][passed]" class="form-control">
                                                <option value="1" {{ ($item['passed'] ?? '') == '1' ? 'selected' : '' }}>Pass</option>
                                                <option value="0" {{ ($item['passed'] ?? '') == '0' ? 'selected' : '' }}>Fail</option>
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <input type="text" name="items[{{ $i }}][notes]"
                                                   class="form-control" placeholder="Notes"
                                                   value="{{ $item['notes'] ?? '' }}">
                                        </div>
                                        <div class="col-md-1">
                                            <button type="button" class="btn btn-sm btn-outline-danger remove-item">
                                                <i class="fa fa-times"></i>
                                            </button>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                        <button type="button" class="btn btn-sm btn-outline-secondary mt-2" id="add-item">
                            <i class="fa fa-plus mr-1"></i> Add Item
                        </button>

                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary">Create Inspection</button>
                            <a href="{{ route('inspections.index') }}" class="btn btn-secondary ml-2">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
@endsection

@push('scripts')
<script>
(function () {
    let idx = {{ old('items') ? count(old('items')) : 0 }};

    document.getElementById('add-item').addEventListener('click', function () {
        const container = document.getElementById('checklist-items');
        container.insertAdjacentHTML('beforeend', `
            <div class="checklist-row row mb-2 align-items-center">
                <div class="col-md-4">
                    <input type="text" name="items[${idx}][area]"
                           class="form-control" placeholder="Area (e.g. Bathroom)" required>
                </div>
                <div class="col-md-3">
                    <select name="items[${idx}][passed]" class="form-control">
                        <option value="1">Pass</option>
                        <option value="0">Fail</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <input type="text" name="items[${idx}][notes]"
                           class="form-control" placeholder="Notes">
                </div>
                <div class="col-md-1">
                    <button type="button" class="btn btn-sm btn-outline-danger remove-item">
                        <i class="fa fa-times"></i>
                    </button>
                </div>
            </div>
        `);
        idx++;
    });

    document.getElementById('checklist-items').addEventListener('click', function (e) {
        if (e.target.closest('.remove-item')) {
            e.target.closest('.checklist-row').remove();
        }
    });
})();
</script>
@endpush
