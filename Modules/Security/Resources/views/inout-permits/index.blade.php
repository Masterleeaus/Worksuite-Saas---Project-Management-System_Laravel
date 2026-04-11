@extends('layouts.app')

@section('content')
    <div class="content-wrapper">
        <div class="d-flex justify-content-between align-items-center action-bar mb-4">
            <h4 class="mb-0">@lang('security::app.inout_permits')</h4>
            @if (Route::has('security.inout-permits.create'))
                <a href="{{ route('security.inout-permits.create') }}" class="btn btn-primary btn-sm">
                    <i class="fa fa-plus mr-1"></i> @lang('app.create')
                </a>
            @endif
        </div>

        <div class="d-flex flex-column w-tables rounded mt-3 bg-white">
            <x-datatable.table class="border-0">
                <x-slot name="thead">
                    <tr>
                        <th>#</th>
                        <th>Visitor Name</th>
                        <th>Visitor Phone</th>
                        <th>Purpose</th>
                        <th>Entry Time</th>
                        <th>@lang('app.status')</th>
                        <th class="text-right">@lang('app.action')</th>
                    </tr>
                </x-slot>
                <x-slot name="tbody">
                    @forelse ($permits ?? [] as $permit)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $permit->visitor_name }}</td>
                            <td>{{ $permit->visitor_phone ?? '—' }}</td>
                            <td>{{ \Str::limit($permit->purpose, 40) }}</td>
                            <td>{{ $permit->entry_time?->format('Y-m-d H:i') }}</td>
                            <td><span class="badge badge-{{ $permit->status === 'approved' ? 'success' : ($permit->status === 'rejected' ? 'danger' : 'warning') }}">{{ ucfirst($permit->status) }}</span></td>
                            <td class="text-right">
                                <a href="{{ route('security.inout-permits.show', $permit->id) }}" class="btn btn-sm btn-outline-primary"><i class="fa fa-eye"></i></a>
                                <a href="{{ route('security.inout-permits.edit', $permit->id) }}" class="btn btn-sm btn-outline-secondary"><i class="fa fa-edit"></i></a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="text-center text-muted py-4">@lang('app.noRecordFound')</td></tr>
                    @endforelse
                </x-slot>
            </x-datatable.table>
        </div>
    </div>
@endsection
