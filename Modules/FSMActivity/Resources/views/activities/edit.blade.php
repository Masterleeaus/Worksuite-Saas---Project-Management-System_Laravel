@extends('fsmactivity::layouts.master')

@section('fsmactivity_content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>Edit Activity – Order: {{ $order->name }}</h2>
    <a href="{{ route('fsmactivity.activities.index', $order->id) }}" class="btn btn-outline-secondary">Back</a>
</div>

<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('fsmactivity.activities.update', [$order->id, $activity->id]) }}">
            @csrf

            <div class="mb-3">
                <label class="form-label fw-semibold">Activity Type</label>
                <select name="activity_type_id" class="form-select">
                    <option value="">— None —</option>
                    @foreach($types as $type)
                        <option value="{{ $type->id }}"
                            {{ old('activity_type_id', $activity->activity_type_id) == $type->id ? 'selected' : '' }}>
                            {{ $type->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Summary</label>
                <input type="text" name="summary" class="form-control"
                       value="{{ old('summary', $activity->summary) }}" maxlength="255">
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Note</label>
                <textarea name="note" class="form-control" rows="4">{{ old('note', $activity->note) }}</textarea>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Due Date</label>
                <input type="date" name="due_date" class="form-control"
                       value="{{ old('due_date', $activity->due_date?->format('Y-m-d')) }}">
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Assigned To</label>
                <select name="assigned_to" class="form-select">
                    <option value="">— Unassigned —</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}"
                            {{ old('assigned_to', $activity->assigned_to) == $user->id ? 'selected' : '' }}>
                            {{ $user->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">State</label>
                <select name="state" class="form-select">
                    @foreach(\Modules\FSMActivity\Models\FSMActivity::STATES as $val => $label)
                        <option value="{{ $val }}" {{ old('state', $activity->state) === $val ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Update</button>
            <a href="{{ route('fsmactivity.activities.index', $order->id) }}" class="btn btn-outline-secondary ms-2">Cancel</a>
        </form>
    </div>
</div>
@endsection
