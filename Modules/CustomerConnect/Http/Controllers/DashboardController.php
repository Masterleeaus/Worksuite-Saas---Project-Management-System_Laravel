<?php

namespace Modules\CustomerConnect\Http\Controllers;

use App\Http\Controllers\AccountBaseController;
use Modules\CustomerConnect\Services\Analytics\KpiBuilder;

class DashboardController extends AccountBaseController
{
    public function index(KpiBuilder $kpis)
    {
        $companyId = company()->id;
        $data = $kpis->forCompany($companyId);

        return view('customerconnect::dashboard.index', $data);
    }
}
