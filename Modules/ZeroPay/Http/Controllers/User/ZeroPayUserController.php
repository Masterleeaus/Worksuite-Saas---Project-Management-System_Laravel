<?php

namespace Modules\ZeroPay\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Modules\ZeroPay\Models\ZeroPaySession;

class ZeroPayUserController extends Controller
{
    public function index()
    {
        $companyId = auth()->user()?->company_id;

        $sessions = ZeroPaySession::query()
            ->where('company_id', $companyId)
            ->latest()
            ->paginate(20);

        return response()->json(['status' => 'success', 'data' => $sessions]);
    }
}
