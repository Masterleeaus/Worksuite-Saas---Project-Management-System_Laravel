@extends('layouts.app')

@section('content')
    <div class="content-wrapper">
        <div class="d-flex justify-content-between align-items-center action-bar mb-4">
            <h4 class="mb-0">@lang('security::app.work_permits')</h4>
            @if (Route::has('security.work-permits.create'))
                <a href="{{ route('security.work-permits.create') }}" class="btn btn-primary btn-sm">
                    <i class="fa fa-plus mr-1"></i> @lang('app.create')
                </a>
            @endif
        </div>

        <div class="d-flex flex-column w-tables rounded mt-3 bg-white">
            <x-datatable.table class="border-0">
                <x-slot name="thead">
                    <tr>
                        <th>#</th>
                        <th>Contractor</th>
                        <th>Company</th>
                        <th>Work Description</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th>@lang('app.status')</th>
                        <th class="text-right">@lang('app.action')</th>
                    </tr>
                </x-slot>
                <x-slot name="tbody">
                    @forelse ($permits ?? [] as $permit)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $permit->contractor_name }}</td>
                            <td>{{ $permit->company_name ?? '—' }}</td>
                            <td>{{ \Str::limit($permit->work_description, 40) }}</td>
                            <td>{{ $permit->start_date?->format(company()->date_format ?? 'Y-m-d') }}</td>
                            <td>{{ $permit->end_date?->format(company()->date_format ?? 'Y-m-d') }}</td>
                            <td><span class="badge badge-{{ $permit->status === 'approved' ? 'success' : ($permit->status === 'rejected' ? 'danger' : 'warning') }}">{{ ucfirst($permit->status) }}</span></td>
                            <td class="text-right">
                                <a href="{{ route('security.work-permits.show', $permit->id) }}" class="btn btn-sm btn-outline-primary"><i class="fa fa-eye"></i></a>
                                <a href="{{ route('security.work-permits.edit', $permit->id) }}" class="btn btn-sm btn-outline-secondary"><i class="fa fa-edit"></i></a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="8" class="text-center text-muted py-4">@lang('app.noRecordFound')</td></tr>
                    @endforelse
                </x-slot>
            </x-datatable.table>
        </div>
    </div>
@endsection
