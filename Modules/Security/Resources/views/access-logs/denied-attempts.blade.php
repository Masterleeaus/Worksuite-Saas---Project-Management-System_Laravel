@extends('layouts.app')

@section('content')
    <div class="content-wrapper">
        <div class="d-flex justify-content-between align-items-center action-bar mb-4">
            <h4 class="mb-0">@lang('security::app.denied_access_attempts')</h4>
            <a href="{{ route('security.access_logs.index') }}" class="btn btn-outline-secondary btn-sm">
                <i class="fa fa-arrow-left mr-1"></i> @lang('app.back')
            </a>
        </div>

        <div class="d-flex flex-column w-tables rounded mt-3 bg-white">
            <x-datatable.table class="border-0">
                <x-slot name="thead">
                    <tr>
                        <th>#</th>
                        <th>Entity</th>
                        <th>@lang('app.user')</th>
                        <th>IP Address</th>
                        <th>Description</th>
                        <th>@lang('app.date')</th>
                    </tr>
                </x-slot>
                <x-slot name="tbody">
                    @forelse ($logs ?? [] as $log)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $log->entity_type ?? '—' }} {{ $log->entity_id ? '#'.$log->entity_id : '' }}</td>
                            <td>{{ $log->user->name ?? '—' }}</td>
                            <td>{{ $log->ip_address ?? '—' }}</td>
                            <td>{{ \Str::limit($log->description, 60) }}</td>
                            <td>{{ $log->created_at?->format('Y-m-d H:i') }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center text-muted py-4">@lang('app.noRecordFound')</td></tr>
                    @endforelse
                </x-slot>
            </x-datatable.table>
        </div>
    </div>
@endsection
