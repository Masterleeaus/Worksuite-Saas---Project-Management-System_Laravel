@extends('layouts.app')

@section('content')

<div class="w-100 d-flex">
    <x-setting-sidebar :activeMenu="$activeSettingMenu"/>

    <x-setting-card>
        <x-slot name="header">
            <div class="s-b-n-header" id="tabs">
                <h2 class="mb-0 p-20 f-21 font-weight-normal border-bottom-grey">
                    @lang('sms::modules.optOutManagement')
                </h2>
            </div>
        </x-slot>

        <div class="col-xl-10 col-lg-12 col-md-12 ntfcn-tab-content-left w-100 py-4">

            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            {{-- Add phone manually --}}
            <form method="POST" action="{{ route('account.sms-opt-outs.store') }}" class="mb-4 d-flex">
                @csrf
                <input type="text" name="phone_number" class="form-control f-14 mr-2" style="max-width:280px"
                       placeholder="+61400000000" required>
                <button type="submit" class="btn btn-warning">
                    <i class="fa fa-ban"></i> @lang('sms::modules.addOptOut')
                </button>
            </form>

            <div class="table-responsive">
                <table class="table table-hover f-13">
                    <thead class="thead-light">
                        <tr>
                            <th>@lang('sms::modules.phoneNumber')</th>
                            <th>@lang('app.client')</th>
                            <th>@lang('sms::modules.optedOutAt')</th>
                            <th>@lang('app.action')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($optOuts as $optOut)
                        <tr>
                            <td>{{ $optOut->phone_number }}</td>
                            <td>{{ optional($optOut->user)->name ?? '—' }}</td>
                            <td>{{ optional($optOut->opted_out_at)->format('d M Y H:i') ?? '—' }}</td>
                            <td>
                                <form method="POST"
                                      action="{{ route('account.sms-opt-outs.destroy', $optOut->id) }}"
                                      onsubmit="return confirm('@lang('messages.confirmDelete')')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                        <i class="fa fa-trash"></i> @lang('app.remove')
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted">@lang('app.noDataFound')</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{ $optOuts->links() }}

        </div>
    </x-setting-card>
</div>

@endsection
