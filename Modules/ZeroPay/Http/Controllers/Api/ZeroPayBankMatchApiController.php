<?php

namespace Modules\ZeroPay\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Modules\ZeroPay\Models\ZeroPayBankMatch;

class ZeroPayBankMatchApiController extends Controller
{
    public function index()
    {
        $matches = ZeroPayBankMatch::query()
            ->where('status', 'queued')
            ->latest()
            ->paginate(20);

        return response()->json(['status' => 'success', 'data' => $matches]);
    }

    public function accept(int $id)
    {
        $match = ZeroPayBankMatch::query()->findOrFail($id);
        $match->status = 'accepted';
        $match->matched_at = now();
        $match->save();

        return response()->json(['status' => 'success', 'message' => 'Bank match accepted.', 'data' => $match]);
    }
}
