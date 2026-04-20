<?php

namespace Modules\FSMSaleRecurringAgreement\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\FSMRecurring\Models\FSMRecurring;
use Modules\FSMSaleRecurringAgreement\Services\RecurringAgreementService;
use Modules\FSMServiceAgreement\Models\FSMServiceAgreement;

class RecurringAgreementController extends Controller
{
    public function __construct(private RecurringAgreementService $service) {}

    public function index(Request $request)
    {
        $recurrings = FSMRecurring::with(['location', 'recurringTemplate'])
            ->when($request->agreement_id, fn($q, $v) => $q->where('agreement_id', $v))
            ->orderByDesc('created_at')
            ->paginate(20)
            ->withQueryString();

        $agreements = FSMServiceAgreement::orderBy('name')->get();
        $filter = $request->only(['agreement_id']);

        return view('fsmsalerecurringagreement::index', compact('recurrings', 'agreements', 'filter'));
    }

    public function linkForm(int $id)
    {
        $recurring  = FSMRecurring::findOrFail($id);
        $agreements = FSMServiceAgreement::where('state', 'active')->orderBy('name')->get();

        return view('fsmsalerecurringagreement::link', compact('recurring', 'agreements'));
    }

    public function link(Request $request, int $id)
    {
        $recurring = FSMRecurring::findOrFail($id);
        $data = $request->validate([
            'agreement_id' => 'required|integer|exists:fsm_service_agreements,id',
        ]);
        $agreement = FSMServiceAgreement::findOrFail($data['agreement_id']);
        $this->service->link($recurring, $agreement);

        return redirect()->route('fsmsalerecurringagreement.index')
            ->with('success', 'Recurring linked to agreement.');
    }

    public function unlink(int $id)
    {
        $recurring = FSMRecurring::findOrFail($id);
        $this->service->unlink($recurring);

        return redirect()->route('fsmsalerecurringagreement.index')
            ->with('success', 'Agreement unlinked from recurring.');
    }
}
