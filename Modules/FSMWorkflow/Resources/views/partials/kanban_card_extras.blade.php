{{--
    Kanban card extras partial – rendered inside each order card on the kanban board
    when FSMWorkflow module is installed.

    Expected variables:
        $order  – FSMOrder model instance
        $cfg    – FSMKanbanConfig instance for this team/global
--}}

{{-- Job Size --}}
@if($cfg->show_size && !empty($order->size_id))
    @php $size = $order->size ?? null; @endphp
    @if($size)
        <div class="mt-1">
            <span class="badge bg-dark" title="Job size: {{ $size->name }}">{{ $size->code }}</span>
        </div>
    @endif
@endif

{{-- Vehicle --}}
@if($cfg->show_vehicle && !empty($order->vehicle_id))
    @php $vehicle = $order->vehicle ?? null; @endphp
    @if($vehicle)
        <div class="small text-muted mt-1"><i class="fas fa-car"></i> {{ $vehicle->name }}</div>
    @endif
@endif

{{-- Timesheet progress (FSMTimesheet module) --}}
@if($cfg->show_timesheet_progress && class_exists(\Modules\FSMTimesheet\Models\FSMTimesheetLine::class))
    @php
        $planned = (float)($order->template?->estimated_hours ?? 0);
        $logged  = \Modules\FSMTimesheet\Models\FSMTimesheetLine::where('fsm_order_id', $order->id)->sum('hours');
    @endphp
    @if($planned > 0)
        @php $pct = min(100, round($logged / $planned * 100)); @endphp
        <div class="mt-1" title="{{ $logged }}h logged of {{ $planned }}h planned">
            <div class="progress" style="height:4px;">
                <div class="progress-bar {{ $pct >= 100 ? 'bg-danger' : 'bg-success' }}"
                     role="progressbar" style="width:{{ $pct }}%"></div>
            </div>
        </div>
    @endif
@endif

{{-- Skills required badge (FSMSkill module) --}}
@if($cfg->show_skills && class_exists(\Modules\FSMSkill\Models\FSMOrderSkillRequirement::class))
    @php
        $skillCount = \Modules\FSMSkill\Models\FSMOrderSkillRequirement::where('fsm_order_id', $order->id)->count();
    @endphp
    @if($skillCount > 0)
        <div class="mt-1">
            <span class="badge bg-info text-dark" title="{{ $skillCount }} skill(s) required">
                <i class="fas fa-tools"></i> {{ $skillCount }} skill{{ $skillCount > 1 ? 's' : '' }}
            </span>
        </div>
    @endif
@endif

{{-- Stock status (FSMStock module) --}}
@if($cfg->show_stock_status && class_exists(\Modules\FSMStock\Models\FSMStockMove::class))
    @php
        $shortage = \Modules\FSMStock\Models\FSMStockMove::where('fsm_order_id', $order->id)
            ->where('status', 'shortage')
            ->exists();
    @endphp
    @if($shortage)
        <div class="mt-1">
            <span class="badge bg-danger" title="Stock shortage!"><i class="fas fa-exclamation-triangle"></i> Stock</span>
        </div>
    @endif
@endif

{{-- Warning flags --}}
@php
    $now = now();
    $isOverdue = $cfg->show_warning_overdue
        && $order->scheduled_date_end
        && \Carbon\Carbon::parse($order->scheduled_date_end)->isPast()
        && !($order->stage?->is_completion_stage ?? false);
@endphp
@if($isOverdue)
    <div class="mt-1">
        <span class="badge bg-warning text-dark" title="Overdue"><i class="fas fa-clock"></i> Overdue</span>
    </div>
@endif
