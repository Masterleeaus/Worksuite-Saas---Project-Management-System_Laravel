@extends('layouts.app')

@section('content')
    <div class="content-wrapper">
        <div class="d-flex justify-content-between align-items-center action-bar mb-4">
            <h4 class="mb-0">@lang('security::app.notes')</h4>
            @if (Route::has('security.notes.create'))
                <a href="{{ route('security.notes.create') }}" class="btn btn-primary btn-sm">
                    <i class="fa fa-plus mr-1"></i> @lang('app.create')
                </a>
            @endif
        </div>

        <div class="d-flex flex-column w-tables rounded mt-3 bg-white">
            <x-datatable.table class="border-0">
                <x-slot name="thead">
                    <tr>
                        <th>#</th>
                        <th>Title</th>
                        <th>Incident Type</th>
                        <th>@lang('app.status')</th>
                        <th>@lang('app.date')</th>
                        <th class="text-right">@lang('app.action')</th>
                    </tr>
                </x-slot>
                <x-slot name="tbody">
                    @forelse ($notes ?? [] as $note)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $note->title }}</td>
                            <td>{{ $note->incident_type ?? '—' }}</td>
                            <td><span class="badge badge-{{ $note->status === 'resolved' ? 'success' : 'warning' }}">{{ ucfirst($note->status) }}</span></td>
                            <td>{{ $note->created_at?->format(company()->date_format ?? 'Y-m-d') }}</td>
                            <td class="text-right">
                                <a href="{{ route('security.notes.show', $note->id) }}" class="btn btn-sm btn-outline-primary"><i class="fa fa-eye"></i></a>
                                <a href="{{ route('security.notes.edit', $note->id) }}" class="btn btn-sm btn-outline-secondary"><i class="fa fa-edit"></i></a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center text-muted py-4">@lang('app.noRecordFound')</td></tr>
                    @endforelse
                </x-slot>
            </x-datatable.table>
        </div>
    </div>
@endsection
