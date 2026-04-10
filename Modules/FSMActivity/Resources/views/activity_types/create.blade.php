@extends('fsmactivity::layouts.master')

@section('fsmactivity_content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>New Activity Type</h2>
    <a href="{{ route('fsmactivity.types.index') }}" class="btn btn-outline-secondary">Back</a>
</div>

<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('fsmactivity.types.store') }}">
            @csrf

            <div class="mb-3">
                <label class="form-label fw-semibold">Name <span class="text-danger">*</span></label>
                <input type="text" name="name" class="form-control" value="{{ old('name') }}" required maxlength="128">
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Icon</label>
                <input type="text" name="icon" class="form-control" value="{{ old('icon') }}" maxlength="64"
                       placeholder="e.g. fa-phone, 📞">
            </div>

            <div class="row mb-3">
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Delay Count</label>
                    <input type="number" name="delay_count" class="form-control" value="{{ old('delay_count', 1) }}" min="1">
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Delay Unit</label>
                    <select name="delay_unit" class="form-select">
                        @foreach(['days' => 'Days', 'weeks' => 'Weeks', 'months' => 'Months'] as $val => $label)
                            <option value="{{ $val }}" {{ old('delay_unit', 'days') === $val ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Default User</label>
                <select name="default_user_id" class="form-select">
                    <option value="">— None —</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}" {{ old('default_user_id') == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Summary</label>
                <input type="text" name="summary" class="form-control" value="{{ old('summary') }}" maxlength="255">
            </div>

            <div class="mb-3 form-check">
                <input type="hidden" name="active" value="0">
                <input type="checkbox" name="active" value="1" class="form-check-input" id="active"
                    {{ old('active', '1') ? 'checked' : '' }}>
                <label class="form-check-label" for="active">Active</label>
            </div>

            <button type="submit" class="btn btn-primary">Create</button>
            <a href="{{ route('fsmactivity.types.index') }}" class="btn btn-outline-secondary ms-2">Cancel</a>
        </form>
    </div>
</div>
@endsection
