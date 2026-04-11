{{--
    FSM Report Module — Sidebar Section
    Injects FSM report links into the existing Reports sidebar group.
    Push this into the parent layout via: @stack('fsm-report-sidebar')
--}}
<li class="d-flex align-items-center mt-1">
    <a href="{{ route('report.fsm.bookings') }}"
       class="f-15 {{ request()->routeIs('report.fsm.bookings*') ? 'text-primary font-weight-bold' : 'text-dark-grey' }}">
        <i class="material-icons f-16 mr-2">event_note</i>
        Booking Performance
    </a>
</li>
<li class="d-flex align-items-center mt-1">
    <a href="{{ route('report.fsm.cleaner-scorecard') }}"
       class="f-15 {{ request()->routeIs('report.fsm.cleaner-scorecard*') ? 'text-primary font-weight-bold' : 'text-dark-grey' }}">
        <i class="material-icons f-16 mr-2">star</i>
        Cleaner Scorecard
    </a>
</li>
<li class="d-flex align-items-center mt-1">
    <a href="{{ route('report.fsm.zone-revenue') }}"
       class="f-15 {{ request()->routeIs('report.fsm.zone-revenue*') ? 'text-primary font-weight-bold' : 'text-dark-grey' }}">
        <i class="material-icons f-16 mr-2">map</i>
        Revenue by Zone
    </a>
</li>
<li class="d-flex align-items-center mt-1">
    <a href="{{ route('report.fsm.chemical-usage') }}"
       class="f-15 {{ request()->routeIs('report.fsm.chemical-usage*') ? 'text-primary font-weight-bold' : 'text-dark-grey' }}">
        <i class="material-icons f-16 mr-2">science</i>
        Chemical Usage
    </a>
</li>
<li class="d-flex align-items-center mt-1">
    <a href="{{ route('report.fsm.route-efficiency') }}"
       class="f-15 {{ request()->routeIs('report.fsm.route-efficiency*') ? 'text-primary font-weight-bold' : 'text-dark-grey' }}">
        <i class="material-icons f-16 mr-2">directions_car</i>
        Route Efficiency
    </a>
</li>
