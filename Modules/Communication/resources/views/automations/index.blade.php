@extends('communication::layouts.master')

@section('communication_content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2 class="mb-0">
        <i class="bi bi-gear-wide-connected me-2"></i>Automation Rules
    </h2>
    <a href="{{ route('communications.automations.create') }}" class="btn btn-primary btn-sm">
        <i class="bi bi-plus-lg me-1"></i>New Rule
    </a>
</div>

<p class="text-muted small mb-3">
    Automation rules automatically send messages when trigger events occur (e.g. booking created → send confirmation email).
</p>

<div class="card">
    <div class="table-responsive">
        <table class="table table-sm table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>Name</th>
                    <th>Trigger</th>
                    <th>Template</th>
                    <th>Delay</th>
                    <th>Recipient</th>
                    <th>Status</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse($automations as $rule)
                <tr>
                    <td>{{ $rule->name }}</td>
                    <td>{{ $triggerEvents[$rule->trigger_event] ?? $rule->trigger_event }}</td>
                    <td>{{ $rule->template?->name ?? '—' }}</td>
                    <td>{{ $rule->delay_minutes > 0 ? $rule->delay_minutes . ' min' : 'Immediate' }}</td>
                    <td>{{ ucfirst($rule->recipient_type) }}</td>
                    <td>
                        <span class="badge bg-{{ $rule->status === 'active' ? 'success' : ($rule->status === 'paused' ? 'warning' : 'secondary') }}">
                            {{ ucfirst($rule->status) }}
                        </span>
                    </td>
                    <td>
                        <a href="{{ route('communications.automations.edit', $rule->id) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                        <form method="POST" action="{{ route('communications.automations.destroy', $rule->id) }}" class="d-inline" onsubmit="return confirm('Delete this automation rule?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger">Delete</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-4 text-muted">No automation rules configured yet.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($automations->hasPages())
    <div class="card-footer">
        {{ $automations->links() }}
    </div>
    @endif
</div>
@endsection
