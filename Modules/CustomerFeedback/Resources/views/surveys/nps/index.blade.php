@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <div class="row">
        <div class="col-md-12">
            <x-cards.data :title="__('customer-feedback::modules.nps')">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>{{ __('app.id') }}</th>
                                <th>{{ __('customer-feedback::app.client') }}</th>
                                <th>NPS Score</th>
                                <th>Service</th>
                                <th>Cleaner</th>
                                <th>Punctuality</th>
                                <th>Status</th>
                                <th>Sent At</th>
                                <th>{{ __('app.action') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($surveys as $survey)
                            <tr>
                                <td>{{ $survey->id }}</td>
                                <td>{{ optional($survey->client)->name ?? '--' }}</td>
                                <td>
                                    @if($survey->nps_score !== null)
                                        <span class="badge badge-{{ $survey->nps_score >= 9 ? 'success' : ($survey->nps_score >= 7 ? 'warning' : 'danger') }}">
                                            {{ $survey->nps_score }}/10
                                        </span>
                                    @else
                                        <span class="text-muted">--</span>
                                    @endif
                                </td>
                                <td>{{ $survey->service_rating ? $survey->service_rating . '★' : '--' }}</td>
                                <td>{{ $survey->cleaner_rating ? $survey->cleaner_rating . '★' : '--' }}</td>
                                <td>{{ $survey->punctuality_rating ? $survey->punctuality_rating . '★' : '--' }}</td>
                                <td>
                                    @if($survey->isCompleted())
                                        <span class="badge badge-success">Completed</span>
                                    @elseif($survey->isExpired())
                                        <span class="badge badge-secondary">Expired</span>
                                    @else
                                        <span class="badge badge-warning">Pending</span>
                                    @endif
                                </td>
                                <td>{{ $survey->sent_at?->format('d M Y H:i') ?? '--' }}</td>
                                <td>
                                    @if(user()->permission('publish_testimonials') === 'all')
                                    <form method="POST" action="{{ route('nps.toggle-public', $survey->id) }}" class="d-inline">
                                        @csrf
                                        <button class="btn btn-sm btn-{{ $survey->is_public ? 'success' : 'outline-secondary' }}" title="Toggle Testimonial">
                                            <i class="fa fa-star"></i>
                                        </button>
                                    </form>
                                    @endif
                                    @if(user()->permission('manage_surveys') === 'all')
                                    <form method="POST" action="{{ route('nps.destroy', $survey->id) }}" class="d-inline" onsubmit="return confirm('Delete?')">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-danger"><i class="fa fa-trash"></i></button>
                                    </form>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="9" class="text-center text-muted">No surveys found.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                {{ $surveys->links() }}
            </x-cards.data>
        </div>
    </div>
</div>
@endsection
