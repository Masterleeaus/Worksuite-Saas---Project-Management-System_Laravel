<?php

namespace Modules\ZeroPay\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Modules\ZeroPay\Models\ZeroPayBankMatch;
use Modules\ZeroPay\Services\BankMatchService;

class ZeroPayBankMatchApiController extends Controller
{
    public function __construct(private readonly BankMatchService $bankMatches)
    {
    }

    public function index()
    {
        $matches = $this->bankMatches->queued()->latest()->paginate(20);

        return response()->json(['status' => 'success', 'data' => $matches]);
    }

    public function accept(int $id)
    {
        $match = ZeroPayBankMatch::query()->findOrFail($id);
        $match = $this->bankMatches->accept($match);

        return response()->json(['status' => 'success', 'message' => 'Bank match accepted.', 'data' => $match]);
    }
}
