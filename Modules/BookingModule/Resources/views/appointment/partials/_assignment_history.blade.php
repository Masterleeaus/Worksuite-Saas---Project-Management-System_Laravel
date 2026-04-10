<div class="mt-3">
    <h5 class="mb-2">{{ __('bookingmodule::assignment.actions.history') }}</h5>
    <div class="table-responsive">
        <table class="table table-sm">
            <thead>
                <tr>
                    <th>{{ __('bookingmodule::assignment.labels.assigned_at') }}</th>
                    <th>{{ __('bookingmodule::assignment.labels.assigned_by') }}</th>
                    <th>{{ __('bookingmodule::assignment.labels.assigned_to') }}</th>
                    <th>{{ __('bookingmodule::assignment.labels.note') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse(($appointment->assignments ?? []) as $a)
                    <tr>
                        <td>{{ optional($a->created_at)->format('Y-m-d H:i') }}</td>
                        <td>{{ optional($a->fromUser)->name ?? '-' }}</td>
                        <td>{{ optional($a->toUser)->name ?? '-' }}</td>
                        <td>{{ $a->note }}</td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="text-muted">{{ __('bookingmodule::assignment.labels.unassigned') }}</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
