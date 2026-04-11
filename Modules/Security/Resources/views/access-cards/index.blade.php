@extends('layouts.app')

@section('content')
    <div class="content-wrapper">
        <div class="d-flex justify-content-between align-items-center action-bar mb-4">
            <h4 class="mb-0">@lang('security::app.access_cards')</h4>
            @if (Route::has('security.access-cards.create'))
                <a href="{{ route('security.access-cards.create') }}" class="btn btn-primary btn-sm">
                    <i class="fa fa-plus mr-1"></i> @lang('app.create')
                </a>
            @endif
        </div>

        <div class="d-flex flex-column w-tables rounded mt-3 bg-white">
            <x-datatable.table class="border-0">
                <x-slot name="thead">
                    <tr>
                        <th>#</th>
                        <th>@lang('app.user')</th>
                        <th>Card Number</th>
                        <th>Card Type</th>
                        <th>@lang('app.status')</th>
                        <th>Issued Date</th>
                        <th>Expiry Date</th>
                        <th class="text-right">@lang('app.action')</th>
                    </tr>
                </x-slot>
                <x-slot name="tbody">
                    @forelse ($cards ?? [] as $card)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $card->user->name ?? '—' }}</td>
                            <td>{{ $card->card_number }}</td>
                            <td>{{ $card->card_type }}</td>
                            <td>
                                @php $cls = match($card->status) { 'active' => 'badge-success', 'expired' => 'badge-danger', default => 'badge-secondary' }; @endphp
                                <span class="badge {{ $cls }}">{{ ucfirst($card->status) }}</span>
                            </td>
                            <td>{{ $card->issued_date?->format(company()->date_format ?? 'Y-m-d') }}</td>
                            <td>{{ $card->expiry_date?->format(company()->date_format ?? 'Y-m-d') }}</td>
                            <td class="text-right">
                                <a href="{{ route('security.access-cards.show', $card->id) }}" class="btn btn-sm btn-outline-primary"><i class="fa fa-eye"></i></a>
                                <a href="{{ route('security.access-cards.edit', $card->id) }}" class="btn btn-sm btn-outline-secondary"><i class="fa fa-edit"></i></a>
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
