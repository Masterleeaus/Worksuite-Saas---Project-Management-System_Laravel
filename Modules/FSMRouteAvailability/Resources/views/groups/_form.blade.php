<div class="mb-3">
    <label for="name" class="form-label">Name <span class="text-danger">*</span></label>
    <input type="text"
           id="name"
           name="name"
           class="form-control @error('name') is-invalid @enderror"
           value="{{ old('name', $group->name ?? '') }}"
           required
           maxlength="256">
    @error('name')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label for="description" class="form-label">Description</label>
    <textarea id="description"
              name="description"
              class="form-control @error('description') is-invalid @enderror"
              rows="3"
              maxlength="512">{{ old('description', $group->description ?? '') }}</textarea>
    @error('description')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

@if(isset($group) && $group->exists)
    <hr>
    <div class="d-flex justify-content-between align-items-center mb-2">
        <h5 class="mb-0">Blackout Days</h5>
        <a href="{{ route('fsmrouteavailability.days.create') }}?blackout_group_id={{ $group->id }}" class="btn btn-sm btn-outline-success">+ Add Day</a>
    </div>
    @if($group->blackoutDays->isEmpty())
        <p class="text-muted">No blackout days defined for this group.</p>
    @else
        <div class="table-responsive">
            <table class="table table-sm table-bordered align-middle">
                <thead class="table-light">
                <tr>
                    <th>Date</th>
                    <th>Label</th>
                    <th>Zip</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                @foreach($group->blackoutDays as $day)
                    <tr>
                        <td>{{ $day->date->format('Y-m-d') }}</td>
                        <td>{{ $day->label ?: '—' }}</td>
                        <td>{{ $day->zip ?: '—' }}</td>
                        <td>
                            <a href="{{ route('fsmrouteavailability.days.edit', $day->id) }}" class="btn btn-xs btn-outline-primary btn-sm">Edit</a>
                            <form method="POST" action="{{ route('fsmrouteavailability.days.destroy', $day->id) }}" class="d-inline" onsubmit="return confirm('Delete this blackout day?')">
                                @csrf
                                <button class="btn btn-xs btn-outline-danger btn-sm">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    @endif
@endif

<div class="mt-3">
    <button type="submit" class="btn btn-primary">Save</button>
    <a href="{{ route('fsmrouteavailability.groups.index') }}" class="btn btn-secondary ms-2">Cancel</a>
</div>
