@extends('layouts.app')

@section('content')
    <div class="content-wrapper">
        <div class="d-flex justify-content-between align-items-center action-bar mb-4">
            <h4 class="mb-0">@lang('security::app.audit_trail')</h4>
        </div>

        <div class="d-flex flex-column w-tables rounded mt-3 bg-white">
            <x-datatable.table class="border-0">
                <x-slot name="thead">
                    <tr>
                        <th>#</th>
                        <th>@lang('app.type')</th>
                        <th>@lang('app.user')</th>
                        <th>@lang('app.action')</th>
                        <th>@lang('app.date')</th>
                    </tr>
                </x-slot>
                <x-slot name="tbody">
                    @forelse ($logs ?? [] as $log)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $log->log_type ?? '—' }}</td>
                            <td>{{ $log->user->name ?? '—' }}</td>
                            <td>{{ $log->action ?? '—' }}</td>
                            <td>{{ $log->created_at?->format(company()->date_format ?? 'Y-m-d') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">@lang('app.noRecordFound')</td>
                        </tr>
                    @endforelse
                </x-slot>
            </x-datatable.table>
        </div>
    </div>
@endsection
