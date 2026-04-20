<?php

namespace Modules\ZeroPay\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\ZeroPay\Models\ZeroPaySetting;
use Modules\ZeroPay\Support\Helpers\ZeroPaySettings;

class ZeroPayAdminController extends Controller
{
    public function dashboard()
    {
        return redirect()->to('/titan/zero-pay-dashboard-page');
    }

    public function settings(Request $request)
    {
        $companyId = auth()->user()?->company_id;

        if ($request->isMethod('post')) {
            $existing = ZeroPaySetting::getCompanySettings($companyId);
            ZeroPaySetting::query()->updateOrCreate(
                ['company_id' => $companyId],
                ['settings' => array_replace_recursive($existing, $request->except(['_token']))]
            );

            return back()->with('status', 'ZeroPay settings saved.');
        }

        return response()->json([
            'status' => 'success',
            'data' => ZeroPaySettings::resolve($companyId),
        ]);
    }
}
