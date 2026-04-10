@extends('layouts.app')

@section('content')

<div class="w-100 d-flex">
    <x-setting-sidebar :activeMenu="$activeSettingMenu"/>

    <x-setting-card>
        <x-slot name="header">
            <div class="s-b-n-header" id="tabs">
                <h2 class="mb-0 p-20 f-21 font-weight-normal border-bottom-grey">
                    @lang('sms::modules.notificationLog')
                </h2>
            </div>
        </x-slot>

        <div class="col-xl-12 col-lg-12 col-md-12 ntfcn-tab-content-left w-100 py-4">

            {{-- Filters --}}
            <form method="GET" action="{{ route('account.sms-notification-log.index') }}" class="row mb-3">
                <div class="col-md-3">
                    <select name="channel" class="form-control f-14">
                        <option value="">-- @lang('sms::modules.allChannels') --</option>
                        <option value="sms" @selected(request('channel') === 'sms')>SMS</option>
                        <option value="whatsapp" @selected(request('channel') === 'whatsapp')>WhatsApp</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <select name="status" class="form-control f-14">
                        <option value="">-- @lang('sms::modules.allStatuses') --</option>
                        <option value="pending" @selected(request('status') === 'pending')>Pending</option>
                        <option value="delivered" @selected(request('status') === 'delivered')>Delivered</option>
                        <option value="failed" @selected(request('status') === 'failed')>Failed</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <input type="text" name="trigger" class="form-control f-14"
                           placeholder="@lang('sms::modules.filterByTrigger')"
                           value="{{ request('trigger') }}">
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-outline-secondary">
                        <i class="fa fa-filter"></i> @lang('app.filter')
                    </button>
                </div>
            </form>

            <div class="table-responsive">
                <table class="table table-hover table-sm f-13">
                    <thead class="thead-light">
                        <tr>
                            <th>@lang('app.date')</th>
                            <th>@lang('app.client')</th>
                            <th>@lang('sms::modules.toNumber')</th>
                            <th>@lang('sms::modules.channel')</th>
                            <th>@lang('sms::modules.triggerType')</th>
                            <th>@lang('sms::modules.status')</th>
                            <th>@lang('sms::modules.message')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($logs as $log)
                        <tr>
                            <td>{{ $log->created_at->format('d M Y H:i') }}</td>
                            <td>{{ optional($log->user)->name ?? '—' }}</td>
                            <td>{{ $log->to_number }}</td>
                            <td>
                                @if($log->channel === 'whatsapp')
                                    <span class="badge badge-success">WhatsApp</span>
                                @else
                                    <span class="badge badge-info">SMS</span>
                                @endif
                            </td>
                            <td><code>{{ $log->trigger_type }}</code></td>
                            <td>
                                @if($log->status === 'delivered')
                                    <span class="badge badge-success">{{ $log->status }}</span>
                                @elseif($log->status === 'failed')
                                    <span class="badge badge-danger" title="{{ $log->error_message }}">{{ $log->status }}</span>
                                @else
                                    <span class="badge badge-warning">{{ $log->status }}</span>
                                @endif
                            </td>
                            <td>
                                <small>{{ \Illuminate\Support\Str::limit($log->message, 80) }}</small>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted">@lang('app.noDataFound')</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{ $logs->links() }}

        </div>
    </x-setting-card>
</div>

@endsection
