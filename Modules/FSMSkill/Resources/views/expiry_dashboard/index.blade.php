@extends('fsmskill::layouts.master')

@section('fsmskill_content')
<h2 class="mb-4">Certification Expiry Dashboard</h2>

@if($expired->isNotEmpty())
<div class="card border-danger mb-4">
    <div class="card-header bg-danger text-white fw-semibold">
        🔴 Expired Certifications ({{ $expired->count() }})
    </div>
    <div class="card-body p-0">
        <table class="table table-sm mb-0">
            <thead class="table-light">
            <tr>
                <th>Worker</th>
                <th>Skill</th>
                <th>Type</th>
                <th>Level</th>
                <th>Expired On</th>
                <th>Certificate</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            @foreach($expired as $es)
                <tr class="table-danger">
                    <td>
                        <a href="{{ route('fsmskill.employee-skills.index', $es->user_id) }}">
                            {{ $es->user?->name ?? "User #{$es->user_id}" }}
                        </a>
                    </td>
                    <td>{{ $es->skill?->name ?? '—' }}</td>
                    <td>{{ $es->skill?->skillType?->name ?? '—' }}</td>
                    <td>{{ $es->skillLevel?->name ?? '—' }}</td>
                    <td><strong>{{ $es->expiry_date->format('d M Y') }}</strong></td>
                    <td>
                        @if($es->certificate_path)
                            <a href="{{ Storage::url($es->certificate_path) }}" target="_blank">View</a>
                        @else
                            —
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('fsmskill.employee-skills.edit', [$es->user_id, $es->id]) }}"
                           class="btn btn-sm btn-outline-primary">Update</a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif

@if($expiringSoon->isNotEmpty())
<div class="card border-warning mb-4">
    <div class="card-header bg-warning fw-semibold">
        🟡 Expiring Within {{ $days }} Days ({{ $expiringSoon->count() }})
    </div>
    <div class="card-body p-0">
        <table class="table table-sm mb-0">
            <thead class="table-light">
            <tr>
                <th>Worker</th>
                <th>Skill</th>
                <th>Type</th>
                <th>Level</th>
                <th>Expires On</th>
                <th>Days Left</th>
                <th>Certificate</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            @foreach($expiringSoon as $es)
                @php $daysLeft = (int) now()->diffInDays($es->expiry_date, false); @endphp
                <tr class="table-warning">
                    <td>
                        <a href="{{ route('fsmskill.employee-skills.index', $es->user_id) }}">
                            {{ $es->user?->name ?? "User #{$es->user_id}" }}
                        </a>
                    </td>
                    <td>{{ $es->skill?->name ?? '—' }}</td>
                    <td>{{ $es->skill?->skillType?->name ?? '—' }}</td>
                    <td>{{ $es->skillLevel?->name ?? '—' }}</td>
                    <td>{{ $es->expiry_date->format('d M Y') }}</td>
                    <td>
                        <span class="badge {{ $daysLeft <= 7 ? 'bg-danger' : 'bg-warning text-dark' }}">
                            {{ $daysLeft }} days
                        </span>
                    </td>
                    <td>
                        @if($es->certificate_path)
                            <a href="{{ Storage::url($es->certificate_path) }}" target="_blank">View</a>
                        @else
                            —
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('fsmskill.employee-skills.edit', [$es->user_id, $es->id]) }}"
                           class="btn btn-sm btn-outline-primary">Update</a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif

@if($expired->isEmpty() && $expiringSoon->isEmpty())
    <div class="alert alert-success">
        ✅ All certifications are current. No expirations within {{ $days }} days.
    </div>
@endif
@endsection
