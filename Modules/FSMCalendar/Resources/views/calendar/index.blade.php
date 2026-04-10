@extends('fsmcalendar::layouts.master')

@section('fsmcalendar_content')

{{-- ── Page header ──────────────────────────────────────────────────────── --}}
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2><i class="fas fa-calendar-alt me-2"></i>FSM Calendar</h2>
    <div class="d-flex gap-2">
        <a href="{{ route('fsmcore.orders.index') }}"  class="btn btn-outline-secondary btn-sm">List</a>
        <a href="{{ route('fsmcore.orders.kanban') }}" class="btn btn-outline-secondary btn-sm">Kanban</a>
        <a href="{{ route('fsmcore.orders.create') }}" class="btn btn-success btn-sm">+ New Order</a>
    </div>
</div>

{{-- ── Filter bar ────────────────────────────────────────────────────────── --}}
<div class="card mb-3 shadow-sm">
    <div class="card-body py-2">
        <div class="row g-2 align-items-end">
            <div class="col-sm-3">
                <label class="form-label small mb-1">Worker</label>
                <select id="filter-worker" class="form-select form-select-sm">
                    <option value="">All workers</option>
                    @foreach($workers as $worker)
                        <option value="{{ $worker->id }}">{{ $worker->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-sm-3">
                <label class="form-label small mb-1">Team</label>
                <select id="filter-team" class="form-select form-select-sm">
                    <option value="">All teams</option>
                    @foreach($teams as $team)
                        <option value="{{ $team->id }}">{{ $team->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-sm-3">
                <label class="form-label small mb-1">Stage</label>
                <select id="filter-stage" class="form-select form-select-sm">
                    <option value="">All stages</option>
                    @foreach($stages as $stage)
                        <option value="{{ $stage->id }}">{{ $stage->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-sm-3 d-flex gap-2">
                <div class="form-check form-switch mt-3">
                    <input class="form-check-input" type="checkbox" id="toggle-resource-view">
                    <label class="form-check-label small" for="toggle-resource-view">Worker columns</label>
                </div>
                <button id="btn-apply-filters" class="btn btn-primary btn-sm mt-2">Apply</button>
            </div>
        </div>
    </div>
</div>

{{-- ── Stage colour legend ───────────────────────────────────────────────── --}}
<div class="d-flex flex-wrap gap-2 mb-3" id="stage-legend">
    @foreach($stages as $stage)
        @php
            $stageColor = is_numeric($stage->color) ? '#3788d8' : ($stage->color ?? '#3788d8');
        @endphp
        <span class="badge" style="background-color:{{ $stageColor }}; font-size:0.75rem;">
            {{ $stage->name }}
        </span>
    @endforeach
</div>

{{-- ── Calendar container ────────────────────────────────────────────────── --}}
<div class="card shadow-sm">
    <div class="card-body p-2">
        <div id="fsm-calendar" style="min-height:600px;"></div>
    </div>
</div>

{{-- ── Order detail side-drawer ──────────────────────────────────────────── --}}
<div class="offcanvas offcanvas-end" tabindex="-1" id="orderDrawer" aria-labelledby="orderDrawerLabel" style="width:420px;">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title" id="orderDrawerLabel">Order Detail</h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
    </div>
    <div class="offcanvas-body" id="orderDrawerBody">
        <div class="text-center py-5 text-muted">Loading…</div>
    </div>
</div>

{{-- ── FullCalendar CDN (v6, no premium needed for basic views) ─────────── --}}
<link  rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.css">
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {

    const CSRF     = document.querySelector('meta[name="csrf-token"]')?.content ?? '';
    const calEl    = document.getElementById('fsm-calendar');

    // ── Filter state ────────────────────────────────────────────────────────
    let filterWorker = '';
    let filterTeam   = '';
    let filterStage  = '';

    function buildEventsUrl(info, successCb, failureCb) {
        const params = new URLSearchParams({
            start: info.startStr,
            end:   info.endStr,
        });
        if (filterWorker) params.set('worker_id', filterWorker);
        if (filterTeam)   params.set('team_id',   filterTeam);
        if (filterStage)  params.set('stage_id',  filterStage);

        fetch(`{{ route('fsmcalendar.events') }}?${params}`)
            .then(r => r.json())
            .then(events => successCb(events))
            .catch(err  => failureCb(err));
    }

    // ── FullCalendar init ────────────────────────────────────────────────────
    const calendar = new FullCalendar.Calendar(calEl, {
        initialView: '{{ config('fsmcalendar.default_view', 'timeGridWeek') }}',
        headerToolbar: {
            left:   'prev,next today',
            center: 'title',
            right:  'dayGridMonth,timeGridWeek,timeGridDay,listWeek',
        },
        slotDuration:  '{{ config('fsmcalendar.slot_duration', '00:30:00') }}',
        businessHours: {
            startTime: '{{ config('fsmcalendar.business_hours_start', '07:00') }}',
            endTime:   '{{ config('fsmcalendar.business_hours_end',   '20:00') }}',
        },
        editable:      true,
        droppable:     true,
        nowIndicator:  true,
        navLinks:      true,
        dayMaxEvents:  true,
        events:        buildEventsUrl,

        // ── Event rendered: add urgent red dot ─────────────────────────────
        eventDidMount(info) {
            if (info.event.extendedProps.urgent) {
                const dot = document.createElement('span');
                dot.className = 'fc-urgent-dot';
                dot.title = 'Urgent';
                info.el.prepend(dot);
            }
        },

        // ── Click: open order detail in side-drawer (no full reload) ───────
        eventClick(info) {
            info.jsEvent.preventDefault();
            const orderId = info.event.extendedProps.orderId;
            openOrderDrawer(orderId);
        },

        // ── Drag-and-drop: reschedule ──────────────────────────────────────
        eventDrop(info) {
            const orderId = info.event.extendedProps.orderId;
            rescheduleOrder(orderId, info.event.startStr, info.event.endStr, info.revert);
        },

        // ── Resize: change duration ────────────────────────────────────────
        eventResize(info) {
            const orderId = info.event.extendedProps.orderId;
            rescheduleOrder(orderId, info.event.startStr, info.event.endStr, info.revert);
        },

        // ── Click on empty slot: quick-create order ────────────────────────
        select(info) {
            if (!confirm(`Create a new FSM Order from ${info.startStr} to ${info.endStr}?`)) return;

            const workerIdForSlot = info.resource ? info.resource.id : (filterWorker || null);

            fetch('{{ route('fsmcalendar.quick-create') }}', {
                method: 'POST',
                headers: {
                    'Content-Type':  'application/json',
                    'X-CSRF-TOKEN':  CSRF,
                },
                body: JSON.stringify({
                    start:     info.startStr,
                    end:       info.endStr,
                    worker_id: workerIdForSlot,
                }),
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    calendar.refetchEvents();
                    // Open the edit form immediately in the same page
                    window.location.href = data.edit_url;
                }
            })
            .catch(err => console.error('Quick-create failed', err));
        },
        selectable: true,
    });

    calendar.render();

    // ── Apply filter button ────────────────────────────────────────────────
    document.getElementById('btn-apply-filters').addEventListener('click', () => {
        filterWorker = document.getElementById('filter-worker').value;
        filterTeam   = document.getElementById('filter-team').value;
        filterStage  = document.getElementById('filter-stage').value;
        calendar.refetchEvents();
    });

    // ── Worker-column resource toggle ──────────────────────────────────────
    document.getElementById('toggle-resource-view').addEventListener('change', function () {
        if (this.checked) {
            // Switch to resourceTimeGridDay – requires FullCalendar premium.
            // Graceful fallback: show a notice.
            alert("Worker columns (resource view) requires FullCalendar Scheduler (premium).\n\nFor now, use the Worker filter to see a single worker's orders.");
            this.checked = false;
        }
    });

    // ── Reschedule helper ──────────────────────────────────────────────────
    function rescheduleOrder(orderId, start, end, revertFn) {
        fetch(`/account/fsm/calendar/orders/${orderId}/reschedule`, {
            method: 'POST',
            headers: {
                'Content-Type':  'application/json',
                'X-CSRF-TOKEN':  CSRF,
            },
            body: JSON.stringify({ start, end }),
        })
        .then(r => r.json())
        .then(data => {
            if (!data.success) {
                console.error('Reschedule error', data);
                revertFn();
            }
        })
        .catch(err => {
            console.error('Reschedule failed', err);
            revertFn();
        });
    }

    // ── Side-drawer helpers ────────────────────────────────────────────────
    function openOrderDrawer(orderId) {
        const body    = document.getElementById('orderDrawerBody');
        const drawer  = new bootstrap.Offcanvas(document.getElementById('orderDrawer'));
        body.innerHTML = '<div class="text-center py-5 text-muted"><div class="spinner-border" role="status"></div></div>';
        drawer.show();

        fetch(`/account/fsm/orders/${orderId}`, {
            headers: { 'X-Requested-With': 'XMLHttpRequest' },
        })
        .then(r => r.text())
        .then(html => {
            // Extract just the main content block from the full HTML page
            const parser  = new DOMParser();
            const doc     = parser.parseFromString(html, 'text/html');
            // Try to grab the order detail card / container-fluid content
            const content = doc.querySelector('.container-fluid') ?? doc.body;
            body.innerHTML = `
                <div>${content.innerHTML}</div>
                <div class="mt-3 d-flex gap-2">
                    <a href="/account/fsm/orders/${orderId}/edit" class="btn btn-primary btn-sm">Edit</a>
                    <a href="/account/fsm/orders/${orderId}"      class="btn btn-outline-secondary btn-sm">Full page</a>
                </div>`;
        })
        .catch(err => {
            body.innerHTML = `<div class="alert alert-danger">Failed to load order: ${err.message}</div>`;
        });
    }
});
</script>

<style>
/* Urgent event indicator */
.fc-urgent-dot {
    display: inline-block;
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background: #dc3545;
    margin-right: 4px;
    vertical-align: middle;
}

/* Make event titles slightly bolder */
.fc-event-title {
    font-weight: 500;
}

/* Tighten up event padding in timeGrid */
.fc-timegrid-event .fc-event-main {
    padding: 2px 4px;
}
</style>

@endsection
