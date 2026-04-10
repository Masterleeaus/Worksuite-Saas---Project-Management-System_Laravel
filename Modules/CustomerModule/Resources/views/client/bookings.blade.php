<div class="col-12">

    <div class="d-flex justify-content-between align-items-center action-bar mb-2">
        <h5 class="mb-0">{{ __('customermodule::app.bookingHistory') }}</h5>
    </div>

    @if ($fsmOrders->isEmpty())
        <div class="card card-body text-center text-muted py-5">
            <i class="fa fa-calendar-alt fa-2x mb-2"></i>
            <p>{{ __('customermodule::app.noBookingsYet') }}</p>
        </div>
    @else
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th>{{ __('app.id') }}</th>
                        <th>{{ __('app.title') }}</th>
                        <th>{{ __('customermodule::app.location') }}</th>
                        <th>{{ __('customermodule::app.stage') }}</th>
                        <th>{{ __('app.date') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($fsmOrders as $order)
                        <tr>
                            <td>
                                @if (class_exists(\Modules\FSMCore\Models\FSMOrder::class))
                                    <a href="{{ route('fsmcore.orders.show', $order->id) }}">
                                        {{ $order->name }}
                                    </a>
                                @else
                                    {{ $order->name }}
                                @endif
                            </td>
                            <td>{{ $order->description ?? '--' }}</td>
                            <td>{{ $order->location?->name ?? '--' }}</td>
                            <td>
                                @if ($order->stage)
                                    <span class="badge" style="background-color:{{ $order->stage->color ?? '#6c757d' }}">
                                        {{ $order->stage->name }}
                                    </span>
                                @else
                                    --
                                @endif
                            </td>
                            <td>{{ $order->scheduled_date_start ? $order->scheduled_date_start->format(company()->date_format) : '--' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif

</div>
