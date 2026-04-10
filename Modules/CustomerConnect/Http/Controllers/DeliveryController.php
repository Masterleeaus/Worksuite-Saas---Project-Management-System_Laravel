<?php

namespace Modules\CustomerConnect\Http\Controllers;

use App\Http\Controllers\AccountBaseController;
use Modules\CustomerConnect\Entities\Delivery;

class DeliveryController extends AccountBaseController
{
    public function index()
    {
        $this->pageTitle = 'Customer Connect - Deliveries';

        $deliveries = Delivery::query()
            ->where('company_id', company()->id)
            ->latest()
            ->paginate(30);

        return view('customerconnect::customerconnect.deliveries.index', compact('deliveries'));
    }

    public function show(Delivery $delivery)
    {
        abort_unless((int)$delivery->company_id === (int)company()->id, 404);
        return view('customerconnect::customerconnect.deliveries.show', compact('delivery'));
    }
}
