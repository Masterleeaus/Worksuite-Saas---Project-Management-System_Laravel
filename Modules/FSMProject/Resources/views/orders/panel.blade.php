@php($orders = $orders ?? collect())

<div class="card mt-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span class="fw-semibold">FSM Orders</span>
        <div class="d-flex align-items-center gap-2">
            <span class="badge badge-primary">{{ $orders->count() }}</span>
            <a href="{{ route('fsmproject.create') }}" class="btn btn-sm btn-outline-primary">Link Order</a>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table mb-0">
                <thead>
                <tr>
                    <th>Name</th>
                    <th>Stage</th>
                    <th>Scheduled Date</th>
                    <th>Assigned Worker</th>
                    <th>Status</th>
                    <th class="text-end">Actions</th>
                </tr>
                </thead>
                <tbody>
                @forelse($orders as $order)
                    <tr>
                        <td>{{ $order->name ?? ('Order #' . $order->id) }}</td>
                        <td>{{ $order->stage?->name ?? '—' }}</td>
                        <td>{{ $order->scheduled_date_start?->format(company()->date_format ?? 'Y-m-d') ?? '—' }}</td>
                        <td>{{ $order->person?->name ?? '—' }}</td>
                        <td>
                            @if(optional($order->stage)->is_completion_stage)
                                <span class="badge badge-success">Completed</span>
                            @else
                                <span class="badge badge-warning">Open</span>
                            @endif
                        </td>
                        <td class="text-end">
                            <form action="{{ route('fsmproject.destroy', $order->id) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-outline-danger">Unlink</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">No FSM orders linked to this project.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
