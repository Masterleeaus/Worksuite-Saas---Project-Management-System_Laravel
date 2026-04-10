@extends('layouts.app')

@section('content')
    <div class="content-wrapper">
        <div class="d-flex justify-content-between align-items-center action-bar mb-3">
            <div class="d-flex align-items-center">
                <a href="{{ route('reviews.index') }}" class="btn btn-secondary btn-sm mr-2">
                    <i class="fa fa-arrow-left"></i> @lang('app.back')
                </a>
                <h4 class="mb-0">@lang('reviewmodule::modules.analytics')</h4>
            </div>
        </div>

        <div class="row">
            {{-- Rating Breakdown --}}
            <div class="col-md-5 mb-3">
                <div class="card shadow-sm h-100">
                    <div class="card-header">
                        <h5 class="mb-0">@lang('reviewmodule::modules.rating_breakdown')</h5>
                    </div>
                    <div class="card-body">
                        @foreach ($ratingBreakdown as $rb)
                            <div class="d-flex align-items-center mb-2">
                                <span class="mr-2" style="min-width:30px">{{ $rb->review_rating }} ★</span>
                                <div class="progress flex-grow-1" style="height:16px">
                                    @php $pct = $ratingBreakdown->sum('total') > 0 ? round($rb->total / $ratingBreakdown->sum('total') * 100) : 0; @endphp
                                    <div class="progress-bar bg-warning" style="width:{{ $pct }}%"></div>
                                </div>
                                <span class="ml-2 text-muted">{{ $rb->total }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Category Averages --}}
            <div class="col-md-7 mb-3">
                <div class="card shadow-sm h-100">
                    <div class="card-header">
                        <h5 class="mb-0">@lang('reviewmodule::modules.category_averages')</h5>
                    </div>
                    <div class="card-body">
                        @if($categoryBreakdown)
                            @foreach ([
                                'avg_overall'       => __('reviewmodule::modules.overall_rating'),
                                'avg_punctuality'   => __('reviewmodule::modules.punctuality'),
                                'avg_quality'       => __('reviewmodule::modules.quality'),
                                'avg_value'         => __('reviewmodule::modules.value'),
                                'avg_communication' => __('reviewmodule::modules.communication'),
                            ] as $key => $label)
                                @php $val = round($categoryBreakdown->$key ?? 0, 1); @endphp
                                <div class="d-flex align-items-center mb-2">
                                    <span class="mr-2" style="min-width:140px">{{ $label }}</span>
                                    <div class="progress flex-grow-1" style="height:16px">
                                        <div class="progress-bar bg-primary" style="width:{{ $val * 20 }}%"></div>
                                    </div>
                                    <span class="ml-2 text-muted">{{ $val }}/5</span>
                                </div>
                            @endforeach
                        @else
                            <p class="text-muted">@lang('reviewmodule::modules.no_data')</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- Monthly Trend --}}
        <div class="card shadow-sm">
            <div class="card-header">
                <h5 class="mb-0">@lang('reviewmodule::modules.monthly_trend')</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>@lang('reviewmodule::modules.month')</th>
                                <th>@lang('reviewmodule::modules.total_reviews')</th>
                                <th>@lang('reviewmodule::modules.avg_rating')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($monthlyTrend as $row)
                                <tr>
                                    <td>{{ $row->month }}</td>
                                    <td>{{ $row->total }}</td>
                                    <td>{{ round($row->avg_rating, 1) }} ★</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center text-muted py-3">@lang('reviewmodule::modules.no_data')</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
