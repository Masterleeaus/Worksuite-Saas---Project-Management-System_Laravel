@extends('layouts.app')

@section('content')
    <div class="content-wrapper">
        <div class="d-flex justify-content-between align-items-center action-bar mb-4">
            <h4 class="mb-0">@lang('security::app.parking')</h4>
            @if (Route::has('security.parking.create'))
                <a href="{{ route('security.parking.create') }}" class="btn btn-primary btn-sm">
                    <i class="fa fa-plus mr-1"></i> @lang('app.create')
                </a>
            @endif
        </div>

        <div class="d-flex flex-column w-tables rounded mt-3 bg-white">
            <x-datatable.table class="border-0">
                <x-slot name="thead">
                    <tr>
                        <th>#</th>
                        <th>Vehicle Plate</th>
                        <th>Vehicle Type</th>
                        <th>Bay Number</th>
                        <th>Entry Time</th>
                        <th>Exit Time</th>
                        <th>@lang('app.status')</th>
                        <th class="text-right">@lang('app.action')</th>
                    </tr>
                </x-slot>
                <x-slot name="tbody">
                    @forelse ($records ?? [] as $record)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $record->vehicle_plate }}</td>
                            <td>{{ $record->vehicle_type ?? '—' }}</td>
                            <td>{{ $record->bay_number ?? '—' }}</td>
                            <td>{{ $record->entry_time?->format('Y-m-d H:i') }}</td>
                            <td>{{ $record->exit_time?->format('Y-m-d H:i') ?? '—' }}</td>
                            <td><span class="badge badge-{{ $record->status === 'parked' ? 'success' : 'secondary' }}">{{ ucfirst($record->status) }}</span></td>
                            <td class="text-right">
                                <a href="{{ route('security.parking.show', $record->id) }}" class="btn btn-sm btn-outline-primary"><i class="fa fa-eye"></i></a>
                                <a href="{{ route('security.parking.edit', $record->id) }}" class="btn btn-sm btn-outline-secondary"><i class="fa fa-edit"></i></a>
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
