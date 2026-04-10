@extends('layouts.app')

@section('content')
    <div class="content-wrapper">
        <div class="row">
            <div class="col-12">
                <x-cards.data :title="__('qrcode::app.scanLogs') . ' — ' . $qrCodeData->title">
                    <x-slot name="buttons">
                        <a href="{{ route('qrcode.show', $qrCodeData->id) }}" class="btn btn-secondary">
                            @lang('app.back')
                        </a>
                    </x-slot>

                    @if ($scanLogs->isEmpty())
                        <x-cards.no-record icon="qrcode" :message="__('qrcode::app.noScanLogs')" />
                    @else
                        <x-table>
                            <x-slot name="thead">
                                <th>#</th>
                                <th>@lang('app.user')</th>
                                <th>@lang('qrcode::app.ipAddress')</th>
                                <th>@lang('qrcode::app.scannedAt')</th>
                            </x-slot>

                            @foreach ($scanLogs as $index => $log)
                                <tr>
                                    <td>{{ $scanLogs->firstItem() + $index }}</td>
                                    <td>
                                        @if ($log->user)
                                            {{ $log->user->name }}
                                        @else
                                            <span class="text-muted">@lang('app.guest')</span>
                                        @endif
                                    </td>
                                    <td>{{ $log->ip_address }}</td>
                                    <td>{{ $log->scanned_at?->format(company()->date_format . ' ' . company()->time_format) }}</td>
                                </tr>
                            @endforeach
                        </x-table>

                        <div class="d-flex justify-content-center mt-3">
                            {{ $scanLogs->links() }}
                        </div>
                    @endif
                </x-cards.data>
            </div>
        </div>
    </div>
@endsection
