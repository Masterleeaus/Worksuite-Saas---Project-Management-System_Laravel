@extends('fsmskill::layouts.master')

@section('fsmskill_content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>Skills: {{ $user->name }}</h2>
    <div class="d-flex gap-2">
        <a href="{{ route('fsmskill.employee-skills.create', $user->id) }}" class="btn btn-success">+ Add Skill</a>
    </div>
</div>

<div class="table-responsive">
    <table class="table table-hover align-middle">
        <thead class="table-light">
        <tr>
            <th>Skill</th>
            <th>Type</th>
            <th>Level</th>
            <th>Expiry</th>
            <th>Certificate</th>
            <th>Notes</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        @forelse($skills as $es)
            <tr>
                <td>{{ $es->skill?->name ?? '—' }}</td>
                <td>{{ $es->skill?->skillType?->name ?? '—' }}</td>
                <td>{{ $es->skillLevel?->name ?? '—' }}</td>
                <td>
                    @if($es->expiry_date)
                        @if($es->isExpired())
                            <span class="badge bg-danger">Expired {{ $es->expiry_date->format('d M Y') }}</span>
                        @elseif($es->isExpiringSoon())
                            <span class="badge bg-warning text-dark">Expires {{ $es->expiry_date->format('d M Y') }}</span>
                        @else
                            <span class="text-success">{{ $es->expiry_date->format('d M Y') }}</span>
                        @endif
                    @else
                        <span class="text-muted">No expiry</span>
                    @endif
                </td>
                <td>
                    @if($es->certificate_path)
                        <a href="{{ Storage::url($es->certificate_path) }}" target="_blank" class="btn btn-sm btn-outline-secondary">View</a>
                    @else
                        <span class="text-muted">—</span>
                    @endif
                </td>
                <td><small>{{ $es->notes ?? '' }}</small></td>
                <td>
                    <a href="{{ route('fsmskill.employee-skills.edit', [$user->id, $es->id]) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                    <form method="POST" action="{{ route('fsmskill.employee-skills.destroy', [$user->id, $es->id]) }}" class="d-inline"
                          onsubmit="return confirm('Remove this skill?')">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-outline-danger">Remove</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr><td colspan="7" class="text-center text-muted py-4">No skills assigned to this worker yet.</td></tr>
        @endforelse
        </tbody>
    </table>
</div>
@endsection
