<?php

namespace Modules\FSMProject\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Estimate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Modules\FSMCore\Models\FSMOrder;

class EstimateToOrderController extends Controller
{
    public function convert(Request $request, int $estimateId)
    {
        $estimate = Estimate::query()->findOrFail($estimateId);

        if ((string) $estimate->status !== 'accepted') {
            return redirect()->back()->with('error', 'Only accepted estimates can be converted to FSM orders.');
        }

        $userCompanyId = (int) (auth()->user()->company_id ?? 0);

        if ($userCompanyId > 0 && (int) $estimate->company_id > 0 && (int) $estimate->company_id !== $userCompanyId) {
            abort(404);
        }

        $order = FSMOrder::create([
            'company_id' => $estimate->company_id ?: $userCompanyId,
            'name' => $this->nextOrderReference(),
            'person_id' => $estimate->client_id,
            'estimate_id' => $estimate->id,
            'description' => $this->resolveOrderDescription($estimate),
        ]);

        return redirect()->route('fsmcore.orders.show', $order->id)
            ->with('success', "FSM Order {$order->name} created from estimate {$estimate->estimate_number}.");
    }

    private function nextOrderReference(): string
    {
        $last = FSMOrder::max('id') ?? 0;
        $prefix = config('fsmcore.order_reference_prefix', 'ORD');

        return $prefix . '-' . str_pad((string) ((int) $last + 1), 5, '0', STR_PAD_LEFT);
    }

    private function resolveOrderDescription(Estimate $estimate): ?string
    {
        if (! empty($estimate->description)) {
            return $estimate->description;
        }

        if (! Schema::hasTable('estimate_items')) {
            return null;
        }

        $itemNames = $estimate->items()
            ->whereNotNull('item_name')
            ->pluck('item_name')
            ->filter()
            ->take(5)
            ->implode(', ');

        if ($itemNames === '') {
            return null;
        }

        return 'Estimate items: ' . $itemNames;
    }
}
