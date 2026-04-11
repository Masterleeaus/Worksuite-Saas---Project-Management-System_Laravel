@extends('synapsedispatch::layouts.master')

@section('synapse_content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>⚡ Dispatch Gantt Planner</h2>
    <div class="d-flex gap-2">
        <a href="{{ route('synapsedispatch.planner.index') }}" class="btn btn-outline-secondary">📋 Dashboard</a>
        <a href="{{ route('synapsedispatch.jobs.create') }}" class="btn btn-success">+ New Job</a>
    </div>
</div>

{{-- Team filter --}}
<div class="row g-2 mb-3 align-items-end">
    <div class="col-md-3">
        <label class="form-label small mb-1">Team</label>
        <select id="team-filter" class="form-select form-select-sm">
            <option value="">All Teams</option>
            @foreach($teams as $team)
                <option value="{{ $team->id }}">{{ $team->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-auto">
        <button id="btn-today" class="btn btn-outline-primary btn-sm">Today</button>
        <button id="btn-prev"  class="btn btn-outline-secondary btn-sm">‹ Prev</button>
        <button id="btn-next"  class="btn btn-outline-secondary btn-sm">Next ›</button>
    </div>
    <div class="col-auto ms-auto">
        <select id="view-select" class="form-select form-select-sm">
            <option value="resourceTimelineDay">Day</option>
            <option value="resourceTimelineWeek">Week</option>
            <option value="resourceTimelineTwoWeeks">2 Weeks</option>
        </select>
    </div>
</div>

{{-- Gantt container --}}
<div id="gantt-calendar" style="height:580px; border-radius:8px; border:1px solid #dee2e6; background:#fff;"></div>

{{-- Reassign confirmation toast --}}
<div class="position-fixed bottom-0 end-0 p-3" style="z-index:1100">
    <div id="dispatch-toast" class="toast align-items-center text-bg-success border-0" role="alert">
        <div class="d-flex">
            <div class="toast-body" id="toast-msg">Job reassigned.</div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
        </div>
    </div>
</div>

@push('scripts')
{{-- FullCalendar v6 with Resource Timeline plugin --}}
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@fullcalendar/resource@6.1.11/index.global.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@fullcalendar/resource-timeline@6.1.11/index.global.min.js"></script>

<script>
(function () {
    let calendar;
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content ?? '';

    function getTeamId() {
        return document.getElementById('team-filter').value;
    }

    function buildEventUrl() {
        const tid = getTeamId();
        return '{{ route('synapsedispatch.jobs.fc_events') }}' + (tid ? '?team_id=' + tid : '');
    }

    function buildResourceUrl() {
        const tid = getTeamId();
        return '{{ route('synapsedispatch.workers.fc_resources') }}' + (tid ? '?team_id=' + tid : '');
    }

    function showToast(msg, ok = true) {
        const toast = document.getElementById('dispatch-toast');
        const msgEl = document.getElementById('toast-msg');
        msgEl.textContent = msg;
        toast.className = 'toast align-items-center border-0 ' + (ok ? 'text-bg-success' : 'text-bg-danger');
        bootstrap.Toast.getOrCreateInstance(toast, { delay: 3000 }).show();
    }

    document.addEventListener('DOMContentLoaded', function () {
        const el = document.getElementById('gantt-calendar');

        calendar = new FullCalendar.Calendar(el, {
            schedulerLicenseKey: 'CC-Attribution-NonCommercial-NoDerivatives',
            plugins: [],   // plugins are registered globally via CDN above
            initialView: 'resourceTimelineDay',
            headerToolbar: false,
            nowIndicator: true,
            editable: true,
            resourceAreaHeaderContent: 'Workers',
            resourceAreaWidth: '180px',
            resources: function (info, successCb, failureCb) {
                fetch(buildResourceUrl())
                    .then(r => r.json())
                    .then(successCb)
                    .catch(failureCb);
            },
            events: function (info, successCb, failureCb) {
                const base = buildEventUrl();
                const sep  = base.includes('?') ? '&' : '?';
                const url  = base + sep + 'start=' + info.startStr + '&end=' + info.endStr;
                fetch(url)
                    .then(r => r.json())
                    .then(successCb)
                    .catch(failureCb);
            },
            eventDrop: function (info) {
                const jobId    = info.event.id;
                const workerId = info.newResource ? info.newResource.id : info.event.getResources()[0]?.id;
                const start    = info.event.start.toISOString();

                fetch('/account/synapse-dispatch/jobs/' + jobId + '/reschedule', {
                    method: 'PATCH',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({ worker_id: workerId, start: start }),
                })
                .then(r => r.ok ? r.json() : Promise.reject(r))
                .then(data => showToast('✓ ' + data.job_code + ' reassigned'))
                .catch(() => {
                    info.revert();
                    showToast('Failed to reassign job', false);
                });
            },
        });

        calendar.render();

        // Nav buttons
        document.getElementById('btn-today').addEventListener('click', () => calendar.today());
        document.getElementById('btn-prev').addEventListener('click',  () => calendar.prev());
        document.getElementById('btn-next').addEventListener('click',  () => calendar.next());

        // View selector
        document.getElementById('view-select').addEventListener('change', function () {
            calendar.changeView(this.value);
        });

        // Team filter — refetch
        document.getElementById('team-filter').addEventListener('change', () => calendar.refetchEvents() && calendar.refetchResources());
    });
})();
</script>
@endpush
@endsection
