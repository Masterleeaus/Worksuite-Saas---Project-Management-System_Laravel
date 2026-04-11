@extends('layouts.app')

@section('content')
    <div class="content-wrapper">
        <div class="d-flex justify-content-between align-items-center action-bar mb-4">
            <h4 class="mb-0">@lang('security::app.access_logs')</h4>
            @if (Route::has('security.access_logs.denied_attempts'))
                <a href="{{ route('security.access_logs.denied_attempts') }}" class="btn btn-danger btn-sm">
                    <i class="fa fa-ban mr-1"></i> @lang('security::app.denied_access_attempts')
                </a>
            @endif
        </div>

        <div class="d-flex flex-column w-tables rounded mt-3 bg-white">
            <x-datatable.table class="border-0">
                <x-slot name="thead">
                    <tr>
                        <th>#</th>
                        <th>Log Type</th>
                        <th>Entity</th>
                        <th>@lang('app.user')</th>
                        <th>IP Address</th>
                        <th>Result</th>
                        <th>@lang('app.date')</th>
                        <th class="text-right">@lang('app.action')</th>
                    </tr>
                </x-slot>
                <x-slot name="tbody">
                    @forelse ($logs ?? [] as $log)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $log->log_type ?? '—' }}</td>
                            <td>{{ $log->entity_type ?? '—' }} #{{ $log->entity_id ?? '' }}</td>
                            <td>{{ $log->user->name ?? '—' }}</td>
                            <td>{{ $log->ip_address ?? '—' }}</td>
                            <td>
                                <span class="badge badge-{{ ($log->access_result ?? '') === 'granted' ? 'success' : (($log->access_result ?? '') === 'denied' ? 'danger' : 'secondary') }}">
                                    {{ ucfirst($log->access_result ?? '—') }}
                                </span>
                            </td>
                            <td>{{ $log->created_at?->format('Y-m-d H:i') }}</td>
                            <td class="text-right">
                                <a href="{{ route('security.access_logs.show', $log->id) }}" class="btn btn-sm btn-outline-primary"><i class="fa fa-eye"></i></a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="8" class="text-center text-muted py-4">@lang('app.noRecordFound')</td></tr>
                    @endforelse
                </x-slot>
            </x-datatable.table>
            @if (isset($logs) && method_exists($logs, 'links'))
                <div class="d-flex justify-content-between align-items-center px-3 pb-3">
                    <div>@lang('app.showing') {{ $logs->firstItem() ?? 0 }}–{{ $logs->lastItem() ?? 0 }} @lang('app.of') {{ $logs->total() }}</div>
                    {{ $logs->withQueryString()->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
