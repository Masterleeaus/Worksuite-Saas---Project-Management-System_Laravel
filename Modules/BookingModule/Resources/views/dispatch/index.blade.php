@extends('layouts.main')

@section('page-title')
    {{ __('bookingmodule::dispatch.title') }}
@endsection

@section('page-breadcrumb')
    {{ __('bookingmodule::dispatch.title') }}
@endsection

@push('css')
    <link rel="stylesheet" href="{{ asset('Modules/Appointment/Resources/assets/css/dispatch.css') }}">
@endpush

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex flex-wrap gap-2 justify-content-between align-items-center">
                        <div>
                            <h5 class="mb-0">{{ __('bookingmodule::dispatch.title') }}</h5>
                            <small class="text-muted">{{ __('bookingmodule::dispatch.date') }}: {{ $dispatchDate }}</small>
                        </div>
                        <form method="get" class="d-flex gap-2 align-items-center">
                            <input type="date" class="form-control" name="date" value="{{ $dispatchDate }}">
                            <input type="number" class="form-control" name="workspace" value="{{ $workspaceId }}" placeholder="{{ __('bookingmodule::dispatch.workspace') }}">
                            <button class="btn btn-sm btn-primary" type="submit">{{ __('bookingmodule::dispatch.refresh') }}</button>
                        </form>
                    </div>
                </div>
                <div class="card-body">
                    \1
<div class="dispatch-toolbar mb-3 d-flex flex-wrap gap-2 align-items-center">
  <input type="text" class="form-control form-control-sm" style="max-width:220px" id="dispatchStaffSearch" placeholder="{{ __(\'bookingmodule::dispatch.filters.staff_search\') }}">
  <button class="btn btn-sm btn-light" id="dispatchShowUnassigned">{{ __(\'bookingmodule::dispatch.filters.unassigned_only\') }}</button>
  <button class="btn btn-sm btn-light" id="dispatchShowAll">{{ __(\'bookingmodule::dispatch.filters.show_all\') }}</button>
</div>

                        <div class="dispatch-column dispatch-unassigned dispatch-unassigned-column" data-user-id="">
                            <div class="dispatch-column-header">
                                <strong>{{ __('bookingmodule::dispatch.unassigned') }}</strong>
                            </div>
                            <div class="dispatch-column-body" data-dropzone="1">
                                @foreach($schedules as $s)
                                    @php
                                        $uid = $s->assigned_to ?? $s->user_id;
                                    @endphp
                                    @if(empty($uid))
                                        @include('bookingmodule::dispatch.partials._schedule_card', ['schedule' => $s])
                                    @endif
                                @endforeach
                            </div>
                        </div>

                        @foreach($staff as $u)
                            <div class="dispatch-column" data-user-id="{{ $u->id }}">
                                <div class="dispatch-column-header">
                                    <strong>{{ $u->name }}</strong>
                                </div>
                                <div class="dispatch-column-body" data-dropzone="1">
                                    @foreach($schedules as $s)
                                        @php
                                            $uid = $s->assigned_to ?? $s->user_id;
                                        @endphp
                                        @if((int)$uid === (int)$u->id)
                                            @include('bookingmodule::dispatch.partials._schedule_card', ['schedule' => $s])
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-3 text-muted">
                        <small>{{ __('bookingmodule::dispatch.hint') }}</small>
                        @include('bookingmodule::dispatch.partials._legend')
                    </div>
                </div>
            </div>
        </div>
    
<!-- Quick Edit Modal -->
<div class="modal fade" id="dispatchQuickEditModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content" id="dispatchQuickEditModalContent">
      <!-- loaded via AJAX -->
    </div>
  </div>
</div>

</div>
@endsection

@push('script-page')
    <script src="{{ asset('Modules/Appointment/Resources/assets/js/dispatch-board.js"></script>
<script src="{{ asset('modules/appointment/js/dispatch-quick-edit.js') }}') }}"></script>
@endpush
