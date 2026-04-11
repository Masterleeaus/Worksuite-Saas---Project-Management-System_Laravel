@extends('layouts.app')

@section('content')
    <div class="content-wrapper">
        <div class="d-flex justify-content-between align-items-center action-bar mb-4">
            <h4 class="mb-0">@lang('security::app.packages')</h4>
            @if (Route::has('security.packages.create'))
                <a href="{{ route('security.packages.create') }}" class="btn btn-primary btn-sm">
                    <i class="fa fa-plus mr-1"></i> @lang('app.create')
                </a>
            @endif
        </div>

        <div class="d-flex flex-column w-tables rounded mt-3 bg-white">
            <x-datatable.table class="border-0">
                <x-slot name="thead">
                    <tr>
                        <th>#</th>
                        <th>Tracking Number</th>
                        <th>Sender</th>
                        <th>Recipient</th>
                        <th>Received At</th>
                        <th>@lang('app.status')</th>
                        <th class="text-right">@lang('app.action')</th>
                    </tr>
                </x-slot>
                <x-slot name="tbody">
                    @forelse ($packages ?? [] as $package)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $package->tracking_number ?? '—' }}</td>
                            <td>{{ $package->sender_name }}</td>
                            <td>{{ $package->recipient->name ?? '—' }}</td>
                            <td>{{ $package->received_at?->format('Y-m-d H:i') ?? '—' }}</td>
                            <td><span class="badge badge-{{ $package->status === 'collected' ? 'success' : 'info' }}">{{ ucfirst($package->status) }}</span></td>
                            <td class="text-right">
                                <a href="{{ route('security.packages.show', $package->id) }}" class="btn btn-sm btn-outline-primary"><i class="fa fa-eye"></i></a>
                                <a href="{{ route('security.packages.edit', $package->id) }}" class="btn btn-sm btn-outline-secondary"><i class="fa fa-edit"></i></a>
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
