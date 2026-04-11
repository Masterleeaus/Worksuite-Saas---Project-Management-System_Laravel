@extends('layouts.app')

@section('content')
    <div class="content-wrapper">
        <div class="d-flex justify-content-between align-items-center action-bar mb-4">
            <h4 class="mb-0">@lang('security::app.pending_approvals')</h4>
        </div>

        <div class="d-flex flex-column w-tables rounded mt-3 bg-white">
            <x-datatable.table class="border-0">
                <x-slot name="thead">
                    <tr>
                        <th>#</th>
                        <th>@lang('app.type')</th>
                        <th>@lang('app.name')</th>
                        <th>@lang('app.status')</th>
                        <th>@lang('app.date')</th>
                        <th class="text-right">@lang('app.action')</th>
                    </tr>
                </x-slot>
                <x-slot name="tbody">
                    @forelse ($approvals ?? [] as $item)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $item->type ?? '—' }}</td>
                            <td>{{ $item->name ?? '—' }}</td>
                            <td><span class="badge badge-warning">@lang('app.pending')</span></td>
                            <td>{{ $item->created_at?->format(company()->date_format ?? 'Y-m-d') }}</td>
                            <td class="text-right">
                                <a href="#" class="btn btn-sm btn-outline-primary"><i class="fa fa-eye"></i></a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">@lang('app.noRecordFound')</td>
                        </tr>
                    @endforelse
                </x-slot>
            </x-datatable.table>
        </div>
    </div>
@endsection
