@extends('fsmcore::layouts.master')

@section('fsm_content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>Kanban Board</h2>
    <div class="d-flex gap-2">
        <a href="{{ route('fsmcore.orders.index') }}" class="btn btn-outline-secondary">List View</a>
        <a href="{{ route('fsmcore.orders.create') }}" class="btn btn-success">+ New Order</a>
    </div>
</div>

<div class="d-flex gap-3 overflow-auto pb-3" id="kanban-board" style="align-items: flex-start;">
    @foreach($stages as $stage)
        <div class="kanban-column flex-shrink-0" style="width:260px;" data-stage-id="{{ $stage->id }}">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center"
                     style="border-top: 4px solid {{ $stage->color ?? '#6c757d' }};">
                    <span class="fw-semibold">{{ $stage->name }}</span>
                    <span class="badge bg-secondary">{{ $stage->orders->count() }}</span>
                </div>
                <div class="card-body p-2 kanban-cards" data-stage-id="{{ $stage->id }}" style="min-height:120px;">
                    @foreach($stage->orders as $order)
                        <div class="card mb-2 shadow-sm kanban-card" data-order-id="{{ $order->id }}" draggable="true">
                            <div class="card-body p-2">
                                <div class="d-flex justify-content-between">
                                    <a href="{{ route('fsmcore.orders.show', $order->id) }}" class="fw-semibold text-decoration-none">{{ $order->name }}</a>
                                    @if($order->priority === '1')
                                        <span class="badge bg-danger">Urgent</span>
                                    @endif
                                </div>
                                @if($order->location)
                                    <div class="small text-muted mt-1"><i class="fas fa-map-marker-alt"></i> {{ $order->location->name }}</div>
                                @endif
                                @if($order->person)
                                    <div class="small text-muted"><i class="fas fa-user"></i> {{ $order->person->name }}</div>
                                @endif
                                @if($order->scheduled_date_start)
                                    <div class="small text-muted"><i class="fas fa-clock"></i> {{ $order->scheduled_date_start->format('d M H:i') }}</div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endforeach
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    let draggedCard = null;

    document.querySelectorAll('.kanban-card').forEach(card => {
        card.addEventListener('dragstart', e => {
            draggedCard = card;
            e.dataTransfer.effectAllowed = 'move';
        });
        card.addEventListener('dragend', () => { draggedCard = null; });
    });

    document.querySelectorAll('.kanban-cards').forEach(col => {
        col.addEventListener('dragover', e => { e.preventDefault(); e.dataTransfer.dropEffect = 'move'; });
        col.addEventListener('drop', e => {
            e.preventDefault();
            if (!draggedCard) return;
            const stageId = col.dataset.stageId;
            const orderId = draggedCard.dataset.orderId;
            col.appendChild(draggedCard);

            fetch(`/account/fsm/orders/${orderId}/stage`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content ?? ''
                },
                body: JSON.stringify({ stage_id: stageId })
            }).catch(err => console.error('Stage update failed', err));
        });
    });
});
</script>
@endsection
