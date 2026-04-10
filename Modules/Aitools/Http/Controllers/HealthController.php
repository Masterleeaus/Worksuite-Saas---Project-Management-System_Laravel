<?php

namespace Modules\Aitools\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Modules\Aitools\Tools\ToolRegistry;

class HealthController extends Controller
{
    public function __construct(protected ToolRegistry $registry)
    {
    }

    public function health(): JsonResponse
    {
        $companyId = null;
        try {
            if (function_exists('company') && company()) {
                $companyId = (int) company()->id;
            } elseif (auth()->user() && isset(auth()->user()->company_id)) {
                $companyId = (int) auth()->user()->company_id;
            }
        } catch (\Throwable $e) {
            $companyId = null;
        }

        return response()->json([
            'success' => true,
            'module' => 'Aitools',
            'company_id' => $companyId,
            'user_id' => auth()->id(),
            'tools_count' => count($this->registry->all()),
            'app_timezone' => config('app.timezone', 'UTC'),
        ]);
    }
}
